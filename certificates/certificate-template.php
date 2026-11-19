<?php

/**
 * Certificate HTML for Dompdf (A4 landscape, 297mm x 210mm).
 *
 * The background artwork (assets/certificate-bg.png) carries the Mayovest
 * logo, seal and ribbon, so the template only places text, the QR code and
 * the signature inside the artwork's white area. Dompdf does not support
 * flexbox, so every block is absolutely positioned in millimetres.
 */
function tcm_certificate_html($name, $courses, $cert)
{
    $name        = esc_html($name);
    $course_list = esc_html(implode(', ', $courses));
    $cert_label  = esc_html($cert);

    // Long names wrap onto two lines; shrink them so the block stays above the QR row.
    $name_len  = function_exists('mb_strlen') ? mb_strlen($name) : strlen($name);
    $name_size = $name_len > 34 ? 22 : ($name_len > 26 ? 26 : 30);

    $qr        = esc_url(tcm_generate_qr($cert));
    $bg        = TCM_PATH . 'assets/certificate-bg.png';
    $signature = TCM_PATH . 'assets/signature.png';

    return "
<html>
  <head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
    <style>
      @page {
        size: A4 landscape;
        margin: 0;
      }

      html, body {
        margin: 0;
        padding: 0;
        font-family: 'DejaVu Sans', sans-serif;
        color: #2b2b2b;
      }

      .page {
        position: relative;
        width: 297mm;
        height: 209.5mm;
        overflow: hidden;
      }

      .bg {
        position: absolute;
        left: 0;
        top: 0;
        width: 297mm;
        height: 210mm;
      }

      /* Text column, kept clear of the top band and the seal/ribbon on the right */
      .content {
        position: absolute;
        left: 22mm;
        top: 64mm;
        width: 212mm;
        text-align: center;
      }

      .title {
        font-size: 36px;
        font-weight: bold;
        color: #13207a;
        letter-spacing: 0.5px;
      }

      .subtitle {
        font-size: 15px;
        margin-top: 4mm;
      }

      .name {
        font-size: {$name_size}px;
        font-weight: bold;
        color: #13207a;
        margin-top: 4mm;
      }

      .rule {
        width: 70mm;
        height: 0;
        margin: 3mm auto 0 auto;
        border-top: 1.2mm solid #f47b20;
      }

      .course {
        font-size: 15px;
        margin-top: 5mm;
        line-height: 1.5;
      }

      .course strong {
        font-size: 17px;
        color: #13207a;
      }

      /* Bottom row: QR + certificate number on the left, signature on the right */
      .verify {
        position: absolute;
        left: 52mm;
        top: 151mm;
        width: 60mm;
        text-align: center;
      }

      .verify img {
        width: 26mm;
        height: 26mm;
      }

      .cert-number {
        font-size: 10px;
        margin-top: 1.5mm;
      }

      .sig {
        position: absolute;
        left: 146mm;
        top: 155mm;
        width: 60mm;
        text-align: center;
      }

      .sig img {
        width: 34mm;
      }

      .sig .line {
        width: 50mm;
        height: 0;
        margin: 1mm auto 0 auto;
        border-top: 0.3mm solid #13207a;
      }

      .sig span {
        display: block;
        margin-top: 1.5mm;
        font-size: 13px;
        font-weight: bold;
        font-style: italic;
        color: #13207a;
      }
    </style>
  </head>
  <body>
    <div class='page'>
      <img class='bg' src='$bg' />

      <div class='content'>
        <div class='title'>Certificate of Completion</div>
        <div class='subtitle'>This certificate is proudly presented to</div>
        <div class='name'>$name</div>
        <div class='rule'></div>
        <div class='course'>
          For successfully completing the course(s):<br />
          <strong>$course_list</strong>
        </div>
      </div>

      <div class='verify'>
        <img src='$qr' />
        <div class='cert-number'>Certificate Number: $cert_label</div>
      </div>

      <div class='sig'>
        <img src='$signature' />
        <div class='line'></div>
        <span>Director</span>
      </div>
    </div>
  </body>
</html>";
}
