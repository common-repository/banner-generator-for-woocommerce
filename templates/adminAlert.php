<?php
/**
 * @package banner-generator-for-woocommerce
 */
?>

<div id="wbg-alert" class="wbg-hidden">
    <div class="postbox wbg-hidden" id="wbg-alert-postbox">
        <div class="inside">
            <div class="wrap">
                <?php wbgAlerts::alertsHtml() ?>
            </div>
        </div> <!-- .inside -->
    </div>
</div>


<script type="text/javascript">
    window.onload = function () {
        var $wbgAlert = jQuery('#wbg-alert');
        var $postBody = jQuery('#post-body-content');
        $postBody.prepend($wbgAlert.html());
        $wbgAlert.remove();
        jQuery('#wbg-alert-postbox').removeClass('wbg-hidden');
    }
</script>