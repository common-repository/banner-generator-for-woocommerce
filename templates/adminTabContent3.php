<?php
/**
 * @package banner-generator-for-woocommerce
 */
?>
<div id="post-body">
    <div id="post-body-content">

        <div class="postbox">
            <h3><span><?php esc_html_e('Settings') ?></span></h3>
            <div class="inside">
                <div class="wrap">
                    <form method="post" action="">
                        <?php wp_nonce_field('wbg_reset_nonce','wbg_reset_nonce'); ?>
                        <?php wp_nonce_field('wbg_options_nonce','wbg_options_nonce'); ?>

                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php esc_html_e('Max projects on page') ?></th>
                                <td>
                                    <fieldset>
                                        <p>
                                            <?php wbg_text('projects_per_page') ?>
                                        </p>
                                    </fieldset>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><?php esc_html_e('The maximum number of frames') ?></th>
                                <td>
                                    <fieldset>
                                        <p>
                                            <?php wbg_text('frames_limit') ?>
                                        </p>
                                        <span class="wbg-settings-caution">Important! Setting a large limit, it can cause that your server stops responding.</span>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e('Tabs behaviour') ?></th>
                                <td>
                                    <fieldset>
                                        <p>
                                            <?php wbg_checkbox('open_projects_in_background') ?>
                                        </p>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e('Generator settings') ?></th>
                                <td>
                                    <fieldset>
                                        <p>
                                            <?php wbg_checkbox('confirm_overwriting_banner') ?>
                                        </p>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                        <?php
                        // This prints out all hidden setting fields
                        settings_fields(WBG_PREFIX . WBG_SLUG . 'option_group');
                        do_settings_sections(WBG_PREFIX . WBG_SLUG . 'settings_admin');
                        submit_button(esc_html__('Save settings', 'wbg_plugin'), $type = 'primary', $name = WBG_PREFIX . WBG_SLUG . '_submit');
                        submit_button(esc_html__('Reset everything', 'wbg_plugin'), $type = 'default wbg_prompt', $name = WBG_PREFIX . WBG_SLUG . '_submit_r', true,
                            array(
                                'data-message' => esc_html__('Warning! All banners will be deleted! Type "reset" and press enter to continue.', 'wbg_plugin'),
                                'data-condition_key' => 'reset',
                                'data-default' => ''
                            ));
                        ?>
                    </form>
                </div>
            </div> <!-- .inside -->
        </div>

    </div> <!-- #post-body-content -->
</div> <!-- #post-body -->