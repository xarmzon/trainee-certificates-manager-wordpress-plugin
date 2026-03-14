<?php

function tcm_certificate_html($name,$courses,$cert){

$course_list=implode(', ',$courses);

$qr = tcm_generate_qr($cert);
$logo = TCM_URL.'assets/logo.png';
$signature = TCM_URL.'assets/signature.png';

return "

<html>

<head>

<style>

@page {
size: A4 landscape;
margin: 0;
}

body{
margin:0;
padding:0;
font-family: DejaVu Sans, sans-serif;
}

.certificate{

width:297mm;
height:210mm;

box-sizing:border-box;

padding:25mm;

border:8px solid #0E1579;

text-align:center;
}

.logo{
width:180px;
margin-bottom:15px;
}

.title{
font-size:42px;
font-weight:bold;
margin-bottom:10px;
}

.subtitle{
font-size:18px;
margin-bottom:20px;
}

.name{
font-size:36px;
font-weight:bold;
margin:20px 0;
}

.course{
font-size:20px;
margin-top:10px;
}

.qr{
margin-top:15px;
}

.cert-number{
margin-top:15px;
font-size:14px;
}

.signatures{

position:absolute;
bottom:25mm;
left:25mm;
right:25mm;

display:flex;
justify-content:space-between;

}

.sig{
text-align:center;
}

.sig-line{
width:200px;
border-top:1px solid #000;
margin-bottom:5px;
}

</style>

</head>

<body>

<div class='certificate'>

<img class='logo' src='$logo'>

<div class='title'>Certificate of Completion</div>

<div class='subtitle'>
This certificate is proudly presented to
</div>

<div class='name'>$name</div>

<div class='course'>
For successfully completing the course(s):
<br><br>
<strong>$courses</strong>
</div>

<div class='qr'>
<img src='$qr' width='120'>
</div>

<div class='cert-number'>
Certificate Number: $cert
</div>

<div class='signature'>

<div class='sig-box'>
<img class='logo' src='$signature'>
<div class='sig-line'></div>
Director
</div>

</div>

</div>

</body>

</html>

";

}