<?php

function tcm_certificate_filter(){

global $typenow;

if($typenow!=='trainee') return;

?>

<input
type="text"
name="certificate_number"
placeholder="Filter by certificate number"
value="<?php echo isset($_GET['certificate_number'])?esc_attr($_GET['certificate_number']):'';?>">

<?php

}

add_action('restrict_manage_posts','tcm_certificate_filter');

function tcm_filter_query($query){

global $pagenow;

if(
is_admin()
&& $pagenow=='edit.php'
&& isset($_GET['post_type'])
&& $_GET['post_type']=='trainee'
&& !empty($_GET['certificate_number'])
){

$query->set('meta_query',[
[
'key'=>'certificate_number',
'value'=>sanitize_text_field($_GET['certificate_number']),
'compare'=>'LIKE'
]
]);

}

}

add_action('pre_get_posts','tcm_filter_query');