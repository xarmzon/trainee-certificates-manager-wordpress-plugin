<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Export Page
 */
function tcm_register_export_csv_page()
{
    add_submenu_page(
        'edit.php?post_type=trainee',
        'Export Trainees',
        'Export CSV',
        'manage_options',
        'tcm-export-csv',
        'tcm_export_csv_page'
    );
}

add_action('admin_menu', 'tcm_register_export_csv_page');

/**
 * Export Page
 */
function tcm_export_csv_page()
{
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access.');
    }

    if (
        isset($_POST['tcm_export_csv']) &&
        check_admin_referer(
            'tcm_export_csv_action',
            'tcm_export_csv_nonce'
        )
    ) {
        tcm_export_csv();
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

            .tcm-export-info{
                margin-top:20px;
                background:#f6f7f7;
                border-left:4px solid #00a32a;
                padding:15px;
                border-radius:4px;
            }

        </style>

        <div class="tcm-card">

            <h1>Export Trainees CSV</h1>

            <p>
                Export all trainees and certificate records into a CSV file.
            </p>

            <form method="post">

                <?php wp_nonce_field(
                    'tcm_export_csv_action',
                    'tcm_export_csv_nonce'
                ); ?>

                <button
                    type="submit"
                    name="tcm_export_csv"
                    class="button button-primary"
                >
                    Download CSV
                </button>

            </form>

            <div class="tcm-export-info">

                <strong>Export Includes:</strong>

                <ul>
                    <li>Trainee Full Name</li>
                    <li>Courses</li>
                    <li>Certificate Number</li>
                </ul>

            </div>

        </div>

    </div>

    <?php
}

/**
 * Export CSV
 */
function tcm_export_csv()
{
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access.');
    }

    $filename = sprintf(
        'trainees-%s.csv',
        date('Y-m-d-His')
    );

    if (ob_get_length()) {
        ob_end_clean();
    }

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    fputcsv($output, [
        'full_name',
        'courses',
        'certificate_number'
    ]);

    $query = new WP_Query([
        'post_type' => 'trainee',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'fields' => 'ids'
    ]);

    foreach ($query->posts as $post_id) {

        $courses = get_post_meta(
            $post_id,
            'courses',
            true
        );

        if (!is_array($courses)) {
            $courses = [];
        }

        fputcsv($output, [
            get_the_title($post_id),
            implode('|', $courses),
            get_post_meta(
                $post_id,
                'certificate_number',
                true
            )
        ]);
    }

    fclose($output);

    exit;
}