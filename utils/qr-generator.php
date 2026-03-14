<?php

function tcm_generate_qr($cert){

$url=site_url('/verify-certificate?cert='.$cert);

return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=".$url;

}