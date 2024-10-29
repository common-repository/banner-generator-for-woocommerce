<?php
/**
 * @package banner-generator-for-woocommerce
 */
?>
<div id="post-body">
    <?php
    $project = new wbgProject($project_ID);
    $name = $project->getName();
    ?>
    <div id="post-body-content">
        <div class="postbox">
            <h3><span><?php esc_html_e('Rename banner: ', 'wbg_plugin') ?><?php echo esc_html($project->getName()) ?></span></h3>
            <div class="inside">
                <div class="wrap">
                    <form method="post" action="">
                        <?php wp_nonce_field('wbg_nonce_rename','wbg_nonce_rename'); ?>
                        <table class="form-table">
                            <tr>
                                <td>
                                    <fieldset>
                                        <p><?php esc_html_e('Enter New name', 'wbg_plugin') ?></p>
                                        <p>
                                            <input required name="<?php echo WBG_HIDDEN_INPUT_PROJECT ?>" type="hidden"
                                                   value="<?php echo esc_attr($project_ID) ?>">
                                            <input required name="<?php echo WBG_INPUT_RENAME_PROJECT_NAME ?>" value="">
                                            <?php submit_button(esc_html__('Change name', 'wbg_plugin'), $type = 'primary', WBG_SUBMIT_NEW_PROJECT_NAME); ?>
                                            <?php esc_html_e('Cancel rename', 'wbg_plugin') ?>
                                            <a href="<?php echo admin_url(sprintf('options-general.php?page=%s&tab=1', wbgAdmin::getInstance()->getSettingsPageHandle())) ?>">
                                                <?php esc_html_e('take me back', 'wbg_plugin') ?>
                                            </a>
                                        </p><br><br>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div> <!-- .inside -->
        </div>
    </div> <!-- #post-body-content -->
</div> <!-- #post-body -->