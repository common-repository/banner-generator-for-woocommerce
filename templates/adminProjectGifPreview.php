<?php
/**
 * @package banner-generator-for-woocommerce
 * @var $gifSrc
 */

?>
<div id="wbg-gif">
    <div class="postbox">
        <h3><span>First frame</span></h3>
        <div class="inside">
            <div class="wrap">
                <img src="<?php echo esc_url($gifSrc) ?>">
            </div>
        </div> <!-- .inside -->
    </div>
</div>


<script type="text/javascript">
    window.onload = function () {
        jQuery("html, body").animate({scrollTop: jQuery('#wbg-gif').offset().top}, 1000);
    }
</script>