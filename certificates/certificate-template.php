<?php

function tcm_certificate_html($name,$courses,$cert){

$course_list=implode(', ',$courses);

return "

<style>

body{
font-family:'Times New Roman',serif;
text-align:center;
padding:120px;
}

.title{
font-size:40px;
font-weight:bold;
}

.name{
font-size:36px;
margin:30px 0;
font-weight:bold;
}

.course{
font-size:22px;
}

.cert{
margin-top:40px;
}

</style>

<div class='title'>Certificate of Completion</div>

<p>This certifies that</p>

<div class='name'>$name</div>

<p>has successfully completed</p>

<div class='course'>$course_list</div>

<div class='cert'>Certificate Number: $cert</div>

";

}