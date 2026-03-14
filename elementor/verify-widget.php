<?php

use Elementor\Widget_Base;

class TCM_Verify_Widget extends Widget_Base {

public function get_name(){
return 'certificate_verify';
}

public function get_title(){
return 'Certificate Verification';
}

public function get_icon(){
return 'eicon-check';
}

public function get_categories(){
return ['general'];
}

protected function render(){

echo do_shortcode('[certificate_verify]');

}

}