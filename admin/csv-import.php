<?php

function tcm_csv_menu(){

add_submenu_page(
'edit.php?post_type=trainee',
'Import Certificates',
'Import CSV',
'manage_options',
'tcm-import',
'tcm_import_page'
);

}

add_action('admin_menu','tcm_csv_menu');

function tcm_import_page(){

?>

<div class="wrap">

<h2>Import Certificate CSV</h2>

<form method="post" enctype="multipart/form-data">

<input type="file" name="csv" required>

<input type="submit" name="upload_csv"
class="button button-primary"
value="Import Certificates">

</form>

</div>

<?php

if(isset($_POST['upload_csv'])){

$file=$_FILES['csv']['tmp_name'];

$handle=fopen($file,'r');

while(($data=fgetcsv($handle,1000,","))!==FALSE){

$name=$data[0];
$courses=explode('|',$data[1]);
$cert=$data[2];

$post_id=wp_insert_post([
'post_title'=>$name,
'post_type'=>'trainee',
'post_status'=>'publish'
]);

update_post_meta($post_id,'courses',$courses);
update_post_meta($post_id,'certificate_number',$cert);

}

echo "<p>Import complete</p>";

}

}