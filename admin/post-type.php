<?php

function tcm_register_trainee(){

register_post_type('trainee',[
"labels"=>[
'name'=>'Trainees',
'singular_name'=>'Trainee',
'menu_name'=>'Trainees',
'add_new_item'=>'Add New Trainee',
'edit_item'=>'Edit Trainee',
'view_item'=>'View Trainee',
'search_items'=>'Search Trainees',
'not_found'=>'No trainees found',
'not_found_in_trash'=>'No trainees found in trash',
'enter_title_here' => 'Trainee Full Name',
'all_items' => 'All Trainees',
],
'public'=>false,
'show_ui'=>true,
'menu_icon'=>'dashicons-awards',
'supports'=>['title'],
'menu_position'=>25
]);

}

function tcm_custom_enter_title_here($title, $post) {
    if ($post->post_type === 'trainee') {
        $title = 'Trainee Full Name';
    }
    return $title;
}
add_filter('enter_title_here', 'tcm_custom_enter_title_here', 10, 2);

add_action('init','tcm_register_trainee');