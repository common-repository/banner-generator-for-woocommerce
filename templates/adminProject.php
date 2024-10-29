<?php
/**
 * @package banner-generator-for-woocommerce
 */

?>
<div id="post-body">
    <div id="post-body-content">

        <div class="postbox">
            <h3><span><?php echo esc_html__('Banner name: ','wbg_plugin'). $project->getName(); ?></span></h3>
            <div class="inside">
                <div class="wrap">
                    <form method="post" action="">
                        <?php wp_nonce_field('wbg_nonce_project_prop','wbg_nonce_project_prop'); ?>
                        <fieldset>
                            <legend><?php echo esc_html__('Banner general settings:','wbg_plugin')?></legend>
                            <table>
                                <tr>
                                    <td>
                                        <table class="wbgBannerProperties">
                                            <tr>
                                                <td><label for="<?php echo WBG_INPUT_FRAMES_BANNER_WIDTH ?>"><?php esc_html_e('Width (px)','wbg_plugin')?></label></td>
                                                <td><input required name="<?php echo WBG_INPUT_FRAMES_BANNER_WIDTH ?>"
                                                           id="<?php echo WBG_INPUT_FRAMES_BANNER_WIDTH ?>"
                                                           value="<?php echo esc_attr($project->getWidth()) ?>"></td>

                                            </tr>
                                            <tr>
                                                <td><label for="<?php echo WBG_INPUT_FRAMES_BANNER_HEIGHT ?>"><?php esc_html_e('Height (px)','wbg_plugin')?></label>
                                                </td>
                                                <td><input required name="<?php echo WBG_INPUT_FRAMES_BANNER_HEIGHT ?>"
                                                           id="<?php echo WBG_INPUT_FRAMES_BANNER_HEIGHT ?>"
                                                           value="<?php echo esc_attr($project->getHeight()) ?>"></td>
                                            </tr>

                                            <tr>
                                                <td><label for="<?php echo WBG_INPUT_BANNER_ALT ?>"><?php esc_html_e('ALT attribute','wbg_plugin')?></label>
                                                </td>
                                                <td><input name="<?php echo WBG_INPUT_BANNER_ALT ?>"
                                                           id="<?php echo WBG_INPUT_BANNER_ALT ?>"
                                                           value="<?php echo esc_attr($project->getAlt()) ?>"></td>
                                            </tr>
                                        </table>
                                    </td>

                                    <?php if ($project->getGifSrc()): ?>
                                        <td class="wbg-admin-project-effect-logo">
                                            <table class="wbgShTable">
                                                <tr>
                                                    <td>
                                                        <?php esc_html_e('Open GIF in new tab','wbg_plugin')?>
                                                    </td>
                                                    <td>
                                                        <span class="wbgNewTabLink"><a
                                                                href="<?php echo esc_url(str_replace('\\', '/', $project->getGifSrc())) ?>"
                                                                target="_blank">( link )</a></span>
                                                    </td>
                                                <tr>
                                                    <td>
                                                        <?php esc_html_e('Image HTML tag:','wbg_plugin')?>
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                               value="<img id=&quot;wgbImage&quot; src=&quot;<?php echo esc_url(str_replace('\\', '/', $project->getGifSrc())) ?>&quot; alt=&quot;<?php echo esc_attr($project->getAlt()) ?>&quot;>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?php esc_html_e('Generated banner:','wbg_plugin') ?>
                                                    </td>
                                                    <td>
                                                        <img
                                                            src="<?php echo esc_url(str_replace('\\', '/', $project->getGifSrc())) ?>?<?php echo hash('md2', time()) ?>"
                                                            alt="<?php echo esc_attr($project->getAlt()) ?>">
                                                    </td>
                                                </tr>

                                                </tr>
                                            </table>
                                        </td>
                                    <?php endif ?>
                                </tr>
                            </table>
                        </fieldset>
                        <?php submit_button(esc_html__('Save banner properties'), $type = 'primary', $name = WBG_SUBMIT_PROJECT_PROP); ?>
                    </form>
                </div>


            </div> <!-- .inside -->
        </div>

        <div class="postbox">
            <h3><span><?php esc_html_e('Select effect', 'wbg_plugin') ?></span></h3>
            <div class="inside">
                <div class="wrap">
                    <table>
                        <tr>
                            <td>
                                <form method="post" action="">
                                    <?php wp_nonce_field('wbg_nonce_select_effect','wbg_nonce_select_effect'); ?>
                                    <?php
                                    $options = array();
                                    $currentEffectObj = NULL;
                                    foreach ($effects as $effectObj) {
                                        $options[$effectObj->getID()] = $effectObj->getName();
                                        //get current effect object
                                        if ($project->getEffect() === $effectObj::EFFECT_ID) {
                                            $currentEffectObj = $effectObj;
                                        }
                                    }

                                    $args = array(
                                        'options' => $options,//from admin class
                                        'serialized' => false,
                                        'default' => '',
                                        'label' => esc_html__('Select effect', 'wbg_plugin'),
                                        'desc' => '',
                                        'sanitizeCallback' => false,
                                        'validateArgs' => array());
                                    wbg_select(WBG_INPUT_EFFECT, $args, $project->getEffect()) ?>
                                    <input class="xxx" name="<?php echo WBG_HIDDEN_INPUT_PROJECT ?>" type="hidden"
                                           value="<?php echo esc_attr($project->getID()) ?>">
                                    <?php submit_button(esc_html__('Select', 'wbg_plugin'), $type = 'default', WBG_SUBMIT_SELECT_EFFECT, true); ?>
                                </form>
                            </td>
                            <?php if (is_object($currentEffectObj) && $currentEffectObj->getThumbnail()): ?>
                                <td class="wbg-admin-project-effect-logo">
                                    <img src="<?php echo esc_url($currentEffectObj->getThumbnail()) ?>">
                                </td>
                            <?php endif ?>
                        </tr>
                    </table>
                </div>
            </div> <!-- .inside -->
        </div>
        <?php do_action('wbg_project_content') ?>
    </div> <!-- #post-body-content -->
    <?php do_action('wbg_effect_content_gif') ?>
</div> <!-- #post-body -->