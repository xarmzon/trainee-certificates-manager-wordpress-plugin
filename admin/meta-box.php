<?php

function tcm_add_meta_box(){

add_meta_box(
'tcm_details',
'Training Details',
'tcm_meta_html',
'trainee'
);

}

add_action('add_meta_boxes','tcm_add_meta_box');

function tcm_meta_html($post){

$courses = get_post_meta($post->ID,'courses',true);
$cert = get_post_meta($post->ID,'certificate_number',true);

?>

<p>
<label>Courses (separate with | )</label>

<input
type="text"
name="courses"
value="<?php echo esc_attr(is_array($courses)?implode('|',$courses):$courses); ?>"
style="width:100%">
</p>

<p>
<label>Certificate Number</label>

<input
type="text"
name="certificate_number"
value="<?php echo esc_attr($cert); ?>"
style="width:100%">
</p>

<?php

}

function tcm_save_meta($post_id){

if(isset($_POST['courses'])){
$courses = explode('|',sanitize_text_field($_POST['courses']));
update_post_meta($post_id,'courses',$courses);
}

if(isset($_POST['certificate_number'])){
update_post_meta($post_id,'certificate_number',sanitize_text_field($_POST['certificate_number']));
}

}

add_action('save_post','tcm_save_meta');