<?php

function tcm_verify_shortcode(){

    $cert = '';

    if (isset($_GET['cert'])) {
        $cert = sanitize_text_field($_GET['cert']);
    }

    ob_start();

?>

<div class="tcm-verify">

<input name="cert" type="text" id="tcm-cert" placeholder="Enter certificate number"  value="<?php echo esc_attr($cert); ?>">

<button id="tcm-verify-btn">Verify Certificate</button>

<div id="tcm-result"></div>

</div>

<?php

return ob_get_clean();

}

add_shortcode('certificate_verify','tcm_verify_shortcode');