<?php
/**
 * @package banner-generator-for-woocommerce
 * @var $project wbgProject
 */
?>
<div class="wbg-submit-gif-generate">
    <table class="form-table">
        <?php if ($project->getGifSrc()): ?>

            <?php if (wbgAdmin::isCheckboxChecked(wbgAdmin::getInstance()->getOption('confirm_overwriting_banner'))): ?>
                <span
                    style="float:left"><?php submit_button(esc_html__('Generate banner'), $type = 'primary wbg_confirm', SUBMIT_GENERATE, true, array('data-confirmation' => sprintf(esc_html__('Warning! existing banner: "%s" and shortcode will be overwritten! Continue? (If you do not want to lose the banner, use the "clone" in the list of banners)', 'wbg_plugin'), $project->getName()))); ?></span>
            <?php else: ?>
                <span
                    style="float:left"><?php submit_button(esc_html__('Generate banner'), $type = 'primary', SUBMIT_GENERATE, true); ?></span>
            <?php endif ?>

        <?php else: ?>
            <span
                style="float:left"><?php submit_button(esc_html__('Generate banner'), $type = 'primary', SUBMIT_GENERATE, true); ?></span>
        <?php endif; ?>

        <span
            style="float: left; padding-left: 10px"><?php submit_button(esc_html__('Preview first frame'), $type = 'default', SUBMIT_GENERATE_FIRST_FRAME, true); ?></span>

    </table>
</div>
