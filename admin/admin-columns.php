<?php

function tcm_columns($columns){

$columns['certificate_number']='Certificate Number';
$columns['courses']='Courses';

return $columns;

}

add_filter('manage_trainee_posts_columns','tcm_columns');

function tcm_column_data($column,$post_id){

if($column=='certificate_number'){
echo get_post_meta($post_id,'certificate_number',true);
}

if($column=='courses'){

$courses = get_post_meta($post_id,'courses',true);

if(is_array($courses)){
echo implode(', ',$courses);
}

}

}

add_action('manage_trainee_posts_custom_column','tcm_column_data',10,2);