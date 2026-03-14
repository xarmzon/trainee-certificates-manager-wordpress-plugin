<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once TCM_PATH . 'certificates/certificate-template.php';
require_once TCM_PATH.'vendor/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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

    $options = new Options();
    $options->set('isRemoteEnabled',true);

    $dompdf = new Dompdf($options);

    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4','landscape');

    $dompdf->render();

    $dompdf->stream(
        "certificate-$cert.pdf",
        ["Attachment"=>true]
    );

    exit;
}

add_action('init', 'tcm_download_certificate');