<?php

function tcm_search_certificates(){

$keyword = sanitize_text_field($_POST['keyword']);

$query = new WP_Query([
'post_type'=>'trainee',
's'=>'',
'meta_query'=>[
'relation'=>'OR',

[
'key'=>'certificate_number',
'value'=>$keyword,
'compare'=>'LIKE'
],

[
'key'=>'courses',
'value'=>$keyword,
'compare'=>'LIKE'
]
]
]);

$results = [];

if($query->have_posts()){

while($query->have_posts()){

$query->the_post();

$cert = get_post_meta(get_the_ID(),'certificate_number',true);

$courses = get_post_meta(get_the_ID(),'courses',true);

$results[] = [
'name'=>get_the_title(),
'courses'=>$courses,
'cert'=>$cert
];

}

}

wp_send_json($results);

}


function tcm_verify_certificate()
{

  if (!isset($_POST['cert'])) {
    wp_send_json([
      'status' => 'error',
      'message' => 'Certificate number is required'
    ]);
  }

  $cert = sanitize_text_field($_POST['cert']);

  if (empty($cert)) {
    wp_send_json([
      'status' => 'error',
      'message' => 'Invalid certificate number'
    ]);
  }

  $query = new WP_Query([
    'post_type' => 'trainee',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'meta_query' => [
      [
        'key'   => 'certificate_number',
        'value' => $cert,
        'compare' => '='
      ]
    ]
  ]);

  if (!$query->have_posts()) {

    wp_send_json([
      'status' => 'not_found',
      'message' => 'Certificate not found'
    ]);
  }

  $query->the_post();

  $post_id = get_the_ID();
  $name = get_the_title();
  $courses = get_post_meta($post_id, 'courses', true);

  if (!is_array($courses)) {
    $courses = [];
  }

  $response = [
    'status' => 'found',
    'name' => $name,
    'courses' => $courses,
    'cert' => $cert,
    'qr' => tcm_generate_qr($cert),
    'pdf' => site_url('?download_certificate=' . $cert)
  ];

  wp_reset_postdata();

  wp_send_json($response);
}

add_action('wp_ajax_tcm_verify', 'tcm_verify_certificate');     
add_action('wp_ajax_nopriv_tcm_verify', 'tcm_verify_certificate');

add_action('wp_ajax_tcm_search','tcm_search_certificates');
add_action('wp_ajax_nopriv_tcm_search','tcm_search_certificates');