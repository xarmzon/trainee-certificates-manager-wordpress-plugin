<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Import Page
 */
function tcm_register_import_csv_page()
{
    add_submenu_page(
        'edit.php?post_type=trainee',
        'Import Trainees',
        'Import CSV',
        'manage_options',
        'tcm-import-csv',
        'tcm_import_csv_page'
    );
}

add_action('admin_menu', 'tcm_register_import_csv_page');

/**
 * Import Page
 */
function tcm_import_csv_page()
{
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access.');
    }

    if (
        isset($_POST['tcm_import_csv']) &&
        check_admin_referer(
            'tcm_import_csv_action',
            'tcm_import_csv_nonce'
        )
    ) {
        tcm_process_csv_import();
    }

    ?>

    <div class="wrap">

        <style>

            .tcm-card{
                max-width:900px;
                background:#fff;
                padding:24px;
                margin-top:20px;
                border:1px solid #dcdcde;
                border-radius:8px;
                box-shadow:0 1px 3px rgba(0,0,0,.05);
            }

            .tcm-card h1{
                margin-top:0;
            }

            .tcm-card p{
                color:#50575e;
            }

            .tcm-file-input{
                width:100%;
                max-width:500px;
                padding:10px;
                border:1px solid #dcdcde;
                border-radius:6px;
                background:#fff;
            }

            .tcm-actions{
                margin-top:20px;
            }

            .tcm-info{
                margin-top:25px;
                background:#f6f7f7;
                border-left:4px solid #2271b1;
                padding:15px;
                border-radius:4px;
            }

            .tcm-info h3{
                margin-top:0;
            }

            .tcm-table{
                width:100%;
                border-collapse:collapse;
                margin-top:10px;
            }

            .tcm-table th,
            .tcm-table td{
                border:1px solid #dcdcde;
                padding:10px;
                text-align:left;
            }

            .tcm-table th{
                background:#f6f7f7;
            }

            .tcm-code{
                margin-top:15px;
                padding:15px;
                background:#fff;
                border:1px solid #dcdcde;
                border-radius:4px;
                overflow:auto;
                font-family:monospace;
                white-space:pre-wrap;
            }

        </style>

        <?php tcm_render_import_notice(); ?>

        <div class="tcm-card">

            <h1>Import Trainees CSV</h1>

            <p>
                Upload a CSV file containing trainee records.
                Duplicate certificate numbers will automatically be skipped.
            </p>

            <form method="post" enctype="multipart/form-data">

                <?php wp_nonce_field(
                    'tcm_import_csv_action',
                    'tcm_import_csv_nonce'
                ); ?>

                <input
                    type="file"
                    name="tcm_csv"
                    class="tcm-file-input"
                    accept=".csv"
                    required
                >

                <div class="tcm-actions">
                    <button
                        type="submit"
                        name="tcm_import_csv"
                        class="button button-primary"
                    >
                        Import CSV
                    </button>
                </div>

            </form>

            <div class="tcm-info">

                <h3>Expected CSV Format</h3>

                <table class="tcm-table">
                    <thead>
                        <tr>
                            <th>Column</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>full_name</td>
                            <td>Trainee Full Name</td>
                        </tr>
                        <tr>
                            <td>courses</td>
                            <td>Separate multiple courses using |</td>
                        </tr>
                        <tr>
                            <td>certificate_number</td>
                            <td>Unique certificate number</td>
                        </tr>
                    </tbody>
                </table>

                <div class="tcm-code">full_name,courses,certificate_number
Kayode Adelola,Product/UI-UX Design|Website design,MAYOVEST-TR-2026-001
Omisore Mayowa,Data science|Database Management,MAYOVEST-TR-2026-002</div>

            </div>

        </div>

    </div>

    <?php
}

/**
 * Process CSV Import
 */
function tcm_process_csv_import()
{
    $file = $_FILES['tcm_csv']['tmp_name'] ?? '';

    if (!$file) {
        return;
    }

    $handle = fopen($file, 'r');

    if (!$handle) {
        return;
    }

    $imported = 0;
    $duplicates = 0;
    $invalid = 0;

    fgetcsv($handle);

    while (($row = fgetcsv($handle)) !== false) {

        $full_name = sanitize_text_field(trim($row[0] ?? ''));
        $courses_raw = sanitize_text_field(trim($row[1] ?? ''));
        $certificate_number = sanitize_text_field(trim($row[2] ?? ''));

        if (
            empty($full_name) ||
            empty($courses_raw) ||
            empty($certificate_number)
        ) {
            $invalid++;
            continue;
        }

        $existing = new WP_Query([
            'post_type' => 'trainee',
            'post_status' => 'any',
            'posts_per_page' => 1,
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => 'certificate_number',
                    'value' => $certificate_number
                ]
            ]
        ]);

        if (!empty($existing->posts)) {
            $duplicates++;
            continue;
        }

        $courses = array_values(
            array_filter(
                array_map(
                    'trim',
                    explode('|', $courses_raw)
                )
            )
        );

        $post_id = wp_insert_post([
            'post_type' => 'trainee',
            'post_status' => 'publish',
            'post_title' => $full_name
        ]);

        if (!$post_id || is_wp_error($post_id)) {
            $invalid++;
            continue;
        }

        update_post_meta(
            $post_id,
            'courses',
            $courses
        );

        update_post_meta(
            $post_id,
            'certificate_number',
            $certificate_number
        );

        $imported++;
    }

    fclose($handle);

    set_transient(
        'tcm_import_notice',
        [
            'imported' => $imported,
            'duplicates' => $duplicates,
            'invalid' => $invalid
        ],
        60
    );

    wp_safe_redirect(
        admin_url(
            'edit.php?post_type=trainee&page=tcm-import-csv'
        )
    );

    exit;
}

/**
 * Notice
 */
function tcm_render_import_notice()
{
    $notice = get_transient('tcm_import_notice');

    if (!$notice) {
        return;
    }

    delete_transient('tcm_import_notice');

    ?>
    <div class="notice notice-success is-dismissible">
        <p>
            <strong>CSV Import Completed</strong>
            <br><br>

            Imported:
            <strong><?php echo intval($notice['imported']); ?></strong>

            <br>

            Duplicate Certificates Skipped:
            <strong><?php echo intval($notice['duplicates']); ?></strong>

            <br>

            Invalid Rows:
            <strong><?php echo intval($notice['invalid']); ?></strong>
        </p>
    </div>
    <?php
}