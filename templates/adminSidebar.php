<?php
/**
 * @package banner-generator-for-woocommerce
 * @var $settings_page_handle
 */
?>
<div class="inner-sidebar">
    <div class="postbox">
        <div class="inside">
            <h4>Donate this plugin</h4>
            <?php
            $actionUrl = admin_url(sprintf('admin.php?page=%s&donate=1', $settings_page_handle));
            $url = wp_nonce_url($actionUrl, 'wbg_nonce_donate','wbg_nonce_donate' ); ?>
            <a href="<?php echo $url ?>">
                <img src="<?php echo WBG_PLUGIN_ASSETS_URI . '/images/btn_donateCC_LG.gif' ?>" alt="donate">
            </a>
        </div>
    </div>
</div>