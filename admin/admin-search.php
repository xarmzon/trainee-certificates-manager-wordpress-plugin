<?php

function tcm_admin_search($query){

global $pagenow;

if(
is_admin()
&& $pagenow==='edit.php'
&& isset($_GET['post_type'])
&& $_GET['post_type']==='trainee'
&& $query->is_search()
){

$search_term=$query->query_vars['s'];

$query->set('meta_query',[
'relation'=>'OR',

[
'key'=>'certificate_number',
'value'=>$search_term,
'compare'=>'LIKE'
],

[
'key'=>'courses',
'value'=>$search_term,
'compare'=>'LIKE'
]

]);

}

}

add_action('pre_get_posts','tcm_admin_search');