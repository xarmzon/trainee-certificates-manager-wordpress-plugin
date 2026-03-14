<?php

function tcm_certificate_html($name,$courses,$cert){

$course_list=implode(', ',$courses);

$qr = tcm_generate_qr($cert);
$logo = TCM_URL.'assets/logo.svg';
$signature = TCM_URL.'assets/signature.png';
$bg = TCM_URL.'assets/certificate-bg.png';

return "
<html>
  <head>
    
    <style>
      @page {
        size: A4 landscape;
        margin: 0;
      }

      body {
        margin: 0;
        padding: 0;
        font-family:
          DejaVu Sans,
          sans-serif;
      }

      .wrapper{
        width:290mm;
        height:200mm;
        display:flex;
        align-items:center;
        justify-content:center;
        margin: auto;
    }

      .certificate {
        background-image: url('$bg');
        background-size: cover;
        background-repeat: no-repeat;

        width: 260mm;
        height: 180mm;
        margin: auto;
        box-sizing: border-box;
        padding: 10mm;
        border: 8px solid #0e1579;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        overflow: hidden;
      }

      .logo {
        width: 350px;
        margin-top: -120px;
      }

      .title {
        font-size: 42px;
        font-weight: bold;
        margin-bottom: 10px;
      }

      .subtitle {
        font-size: 18px;
        margin-bottom: 12px;
      }

      .name {
        font-size: 36px;
        font-weight: bold;
        margin: 12px 0;
      }

      .course {
        font-size: 20px;
        margin-top: 10px;
      }

      .qr {
        margin-top: 20px;
      }

      .qr img {
        width: 94px;
        height: 94px;
        object-fit: contain;
      }

      .cert-number {
        font-size: 12px;
        margin-bottom: 15px;
      }

      .signatures {
        display: flex;
        justify-content: space-between;
      }

      .sig-box img {
        width: 120px;
      }

      .sig-box span {
        display: block;
        margin-top: 5px;
        font-size: 14px;
        font-weight: bold;
        font-style: italic;
      }

      .sig {
        text-align: center;
      }
    </style>
  </head>
  <body>
    <div class='wrapper'>
        <div class='certificate'>
        <img class='logo' src='$logo' />

        <div class='title'>Certificate of Completion</div>

        <div class='subtitle'>This certificate is proudly presented to</div>

        <div class='name'>$name</div>

        <div class='course'>
            For successfully completing the course(s):
            <br />
            <strong>$course_list</strong>
        </div>

        <div class='qr'>
            <img src='$qr' />
        </div>

        <div class='cert-number'>Certificate Number: $cert</div>

        <div class='signature'>
            <div class='sig-box'>
            <img src='$signature' />
            <span>Director</span>
            </div>
        </div>
        </div>
    </div>
  </body>
</html>";

}