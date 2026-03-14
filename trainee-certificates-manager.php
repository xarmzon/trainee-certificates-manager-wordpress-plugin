<?php
/*
Plugin Name:    Trainee Certificates Manager
Description:    Manage trainees, verify certificates and download PDF certificates
Version:        1.0
Author:         Adelola Kayode Samson
Author URI:     https://github.com/xarmzon
*/

if (!defined('ABSPATH')) exit;

define('TCM_PATH', plugin_dir_path(__FILE__));
define('TCM_URL', plugin_dir_url(__FILE__));

require_once TCM_PATH.'admin/post-type.php';
require_once TCM_PATH.'admin/meta-box.php';
require_once TCM_PATH.'admin/admin-columns.php';
require_once TCM_PATH.'admin/csv-import.php';
require_once TCM_PATH.'admin/admin-search.php';
require_once TCM_PATH.'admin/admin-filter.php';

require_once TCM_PATH.'core/ajax-verify.php';
require_once TCM_PATH.'core/pdf-generator.php';

require_once TCM_PATH.'public/shortcode.php';
require_once TCM_PATH.'utils/qr-generator.php';

function tcm_scripts(){

wp_enqueue_script(
'tcm-js',
TCM_URL.'public/verify.js',
['jquery'],
'1.0',
true
);

wp_localize_script('tcm-js','tcm_ajax',[
'url'=>admin_url('admin-ajax.php')
]);

wp_enqueue_style(
'tcm-style',
TCM_URL.'public/verify.css'
);

}

add_action('wp_enqueue_scripts','tcm_scripts');

function tcm_elementor_widget($widgets_manager){

require_once TCM_PATH.'elementor/verify-widget.php';

$widgets_manager->register(new \TCM_Verify_Widget());

}

add_action('elementor/widgets/register','tcm_elementor_widget');