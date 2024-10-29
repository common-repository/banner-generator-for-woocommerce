<?php
/**
 * @package banner-generator-for-woocommerce
 */
?>
<div id="post-body">
    <div id="post-body-content">

        <div class="postbox">
            <?php $pluginData = get_plugin_data(WBG_PLUGIN_PATH . DIRECTORY_SEPARATOR . WBG_SLUG . '.php'); ?>
            <h2 style="padding-left:5px"><?php echo esc_html($pluginData['Name']) ?><?php esc_html_e('ver.', 'wbg_plugin') ?><?php echo esc_html($pluginData['Version']) ?></h2>
            <div class="inside">
                <h3 style="font-size: 18px"><?php esc_html_e('Quick Start', 'wbg_plugin') ?></h3>
            </div> <!-- .inside -->
        </div>


        <div class="postbox">
            <h3><?php esc_html_e('Create new banner', 'wbg_plugin') ?></h3>
            <div class="inside">
                <p><?php esc_html_e('Enter a name for the new banner and click: add new"', 'wbg_plugin'); ?></p>

                <img class="helpscreen" src="<?php echo WBG_PLUGIN_ASSETS_URI . '/images/help1.png' ?>">

            </div> <!-- .inside -->
        </div>

        <div class="postbox">
            <h3><?php esc_html_e('Operations', 'wbg_plugin') ?></h3>
            <div class="inside">
                <p><?php esc_html_e('Use operations for: duplicate, delete, or create a banner.', 'wbg_plugin'); ?></p>

                <img class="helpscreen" src="<?php echo WBG_PLUGIN_ASSETS_URI . '/images/help2.png' ?>">

            </div> <!-- .inside -->
        </div>

        <div class="postbox">
            <h3><?php esc_html_e('Open', 'wbg_plugin') ?></h3>
            <div class="inside">
                <p><?php esc_html_e('Click "open" to open a banner tab', 'wbg_plugin'); ?></p>
                <img class="helpscreen" src="<?php echo WBG_PLUGIN_ASSETS_URI . '/images/help3.png' ?>">
            </div> <!-- .inside -->
        </div>

        <div class="postbox">
            <h3><?php esc_html_e('Banner tab', 'wbg_plugin') ?></h3>
            <div class="inside">
                <p><?php esc_html_e('Click tab and open the banner', 'wbg_plugin'); ?></p>
                <img class="helpscreen" src="<?php echo WBG_PLUGIN_ASSETS_URI . '/images/help4.png' ?>">
            </div> <!-- .inside -->
        </div>

        <div class="postbox">
            <h3><?php esc_html_e('Properties', 'wbg_plugin') ?></h3>
            <div class="inside">
                <p><?php esc_html_e('Adjust the width and height of the banner, and the ALT tag and click "save banner properties"', 'wbg_plugin'); ?></p>
                <img class="helpscreen" src="<?php echo WBG_PLUGIN_ASSETS_URI . '/images/help5.png' ?>">
            </div> <!-- .inside -->
        </div>

        <div class="postbox">
            <h3><?php esc_html_e('Select animation module', 'wbg_plugin') ?></h3>
            <div class="inside">
                <p><?php esc_html_e('Choice effect from the select field and click select', 'wbg_plugin'); ?></p>
                <img class="helpscreen" src="<?php echo WBG_PLUGIN_ASSETS_URI . '/images/help6.png' ?>">
            </div> <!-- .inside -->
        </div>


        <div class="postbox">
            <h3><?php esc_html_e('Built-in Woocommerce Grid Effect help screen:', 'wbg_plugin') ?></h3>
            <div class="inside">
                <img class="helpscreen" src="<?php echo WBG_PLUGIN_ASSETS_URI . '/images/help7.png' ?>">
            </div> <!-- .inside -->
        </div>

        <div class="postbox">
            <h3><?php esc_html_e('Generate banner', 'wbg_plugin') ?></h3>
            <div class="inside">
                <p><?php esc_html_e('You can generate only the first frame for the preview of your banner, or
click "Generate" to generate a complete animation.', 'wbg_plugin'); ?></p>

                <img class="helpscreen" src="<?php echo WBG_PLUGIN_ASSETS_URI . '/images/help9.png' ?>">

            </div> <!-- .inside -->
        </div>

        <div class="postbox">
            <h3><?php esc_html_e('Copy banner tag', 'wbg_plugin') ?></h3>
            <div class="inside">
                <p><?php esc_html_e('You can copy banner HTML tag and paste to your site content.', 'wbg_plugin'); ?></p>

                <img class="helpscreen" src="<?php echo WBG_PLUGIN_ASSETS_URI . '/images/help10.png' ?>">

            </div> <!-- .inside -->
        </div>


    </div> <!-- #post-body-content -->
</div> <!-- #post-body -->