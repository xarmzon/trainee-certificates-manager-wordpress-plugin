<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once TCM_PATH . 'certificates/certificate-template.php';

/**
 * Handle certificate download
 */
function tcm_download_certificate()
{
    if (!isset($_GET['download_certificate'])) {
        return;
    }

    $cert = sanitize_text_field($_GET['download_certificate']);

    if (empty($cert)) {
        wp_die('Invalid certificate number');
    }

    $query = new WP_Query([
        'post_type'      => 'trainee',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'meta_query'     => [
            [
                'key'   => 'certificate_number',
                'value' => $cert,
                'compare' => '='
            ]
        ]
    ]);

    if (!$query->have_posts()) {
        wp_die('Certificate not found');
    }

    $query->the_post();

    $post_id = get_the_ID();
    $name = get_the_title();
    $courses = get_post_meta($post_id, 'courses', true);

    if (!is_array($courses)) {
        $courses = [];
    }

    $html = tcm_certificate_html($name, $courses, $cert);

    wp_reset_postdata();

    /**
     * NOTE:
     * This currently outputs HTML but labels it as PDF.
     * For true PDF generation you would use DomPDF or TCPDF.
     */

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename=certificate-' . $cert . '.pdf');

    echo $html;

    exit;
}

add_action('init', 'tcm_download_certificate');