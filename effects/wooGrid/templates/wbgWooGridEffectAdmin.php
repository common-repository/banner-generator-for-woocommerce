<?php
/**
 * @package banner-generator-for-woocommerce
 * @var $effect wbgWooGridEffect
 * @var $products
 * @var $userProducts
 * @var $ids
 *
 */
?>
<div class="postbox">
    <h3><span><?php echo esc_html(esc_html($effect::EFFECT_NAME)) ?> (<?php esc_html_e('Version:','wbg_plugin')?> <?php echo esc_html($effect::EFFECT_VER) ?>)</span></h3>
    <div class="inside">
        <form action="" method="post" ENCTYPE="multipart/form-data">
            <?php wp_nonce_field('wbg_nonce_banner_edit','wbg_nonce_banner_edit'); ?>
            <table>
                <tr>
                    <td>
                        <table style="border-collapse: collapse;">
                            <tr>
                                <td><label for="<?php echo esc_attr($effect::GRID_X_INPUT_NAME) ?>"><?php esc_html_e('GridX', 'wbg_plugin') ?></label></td>
                                <td><input required name="<?php echo esc_attr($effect::GRID_X_INPUT_NAME) ?>"
                                           id="<?php echo esc_attr($effect::GRID_X_INPUT_NAME) ?>"
                                           value="<?php echo esc_attr($effect->getGridX()) ?>"></td>
                            </tr>
                            <tr>
                                <td><label for="<?php echo esc_attr($effect::GRID_Y_INPUT_NAME) ?>"><?php esc_html_e('GridY', 'wbg_plugin') ?></label></td>
                                <td><input required name="<?php echo esc_attr($effect::GRID_Y_INPUT_NAME) ?>"
                                           id="<?php echo esc_attr($effect::GRID_Y_INPUT_NAME) ?>"
                                           value="<?php echo esc_attr( $effect->getGridY()) ?>"></td>
                            </tr>
                            <tr>
                                <td><label for="<?php echo esc_attr($effect::PADDING_INPUT_NAME) ?>"><?php esc_html_e('Padding (px)', 'wbg_plugin') ?></label></td>
                                <td><input required name="<?php echo esc_attr($effect::PADDING_INPUT_NAME) ?>"
                                           id="<?php echo esc_attr($effect::PADDING_INPUT_NAME) ?>"
                                           value="<?php echo esc_attr( $effect->getPadding()) ?>"></td>
                            </tr>

                            <tr>
                                <td><label for="<?php echo esc_attr($effect::BG_COLOR_INPUT_NAME) ?>"><?php esc_html_e('Background color', 'wbg_plugin') ?></label>
                                </td>
                                <td><input required type="color" name="<?php echo esc_attr($effect::BG_COLOR_INPUT_NAME) ?>"
                                           id="<?php echo esc_attr($effect::BG_COLOR_INPUT_NAME) ?>"
                                           value="<?php echo esc_attr( $effect->getBgColor()) ?>"></td>
                            </tr>




                            <tr>
                                <td><label for="<?php echo esc_attr($effect::FRAMES_INTERVAL_INPUT_NAME) ?>"><?php esc_html_e('Frame interval:', 'wbg_plugin') ?></label></td>
                                <td><input required name="<?php echo esc_attr($effect::FRAMES_INTERVAL_INPUT_NAME) ?>"
                                           id="<?php echo esc_attr($effect::FRAMES_INTERVAL_INPUT_NAME) ?>"
                                           value="<?php echo esc_attr( $effect->getFramesInterval()) ?>"></td>
                            </tr>

                            <tr>
                                <td><label for="<?php echo esc_attr($effect::TEXT_INPUT_NAME) ?>"><?php esc_html_e('Text', 'wbg_plugin') ?></label>

                                </td>
                                <td><input required name="<?php echo esc_attr($effect::TEXT_INPUT_NAME) ?>"
                                           id="<?php echo esc_attr($effect::TEXT_INPUT_NAME) ?>"
                                           value="<?php echo esc_attr( $effect->getText()) ?>">

                                    <br><span
                                        style="font-style: italic;"><?php esc_html_e('Avaiable placeholders:', 'wbg_plugin') ?><br> %name%, %price%',<br> %sale_price%',<br> %regular_price%',<br> %price_suffix%', '%currency%'</span>
                                </td>
                            </tr>


                            <tr>
                                <td><label for="<?php echo esc_attr($effect::TEXT_BG_COLOR_INPUT_NAME) ?>"><?php esc_html_e('Text background color', 'wbg_plugin') ?></label></td>
                                <td><input required type="color" name="<?php echo esc_attr($effect::TEXT_BG_COLOR_INPUT_NAME) ?>"
                                           id="<?php echo esc_attr($effect::TEXT_BG_COLOR_INPUT_NAME) ?>"
                                           value="<?php echo esc_attr($effect->getTextBgColor()) ?>"></td>
                            </tr>

                            <tr>
                                <td><label for="<?php echo esc_attr($effect::TEXT_BG_PADDING_H_INPUT_NAME) ?>"><?php esc_html_e('Text background padding horizontal', 'wbg_plugin') ?></label></td>
                                <td><input required type="text"
                                           name="<?php echo esc_attr($effect::TEXT_BG_PADDING_H_INPUT_NAME) ?>"
                                           id="<?php echo esc_attr($effect::TEXT_BG_PADDING_H_INPUT_NAME) ?>"
                                           value="<?php echo esc_attr($effect->getTextBgPaddingH()) ?>"></td>
                            </tr>

                            <tr>
                                <td><label for="<?php echo esc_attr($effect::TEXT_BG_PADDING_V_INPUT_NAME) ?>"><?php esc_html_e('Text background padding vertical', 'wbg_plugin') ?></label></td>
                                <td><input required type="text"
                                           name="<?php echo esc_attr($effect::TEXT_BG_PADDING_V_INPUT_NAME) ?>"
                                           id="<?php echo esc_attr($effect::TEXT_BG_PADDING_V_INPUT_NAME) ?>"
                                           value="<?php echo esc_attr($effect->getTextBgPaddingV()) ?>"></td>
                            </tr>

                            <tr>
                                <td><label for="<?php echo esc_attr($effect::TEXT_POS_AUTO_INPUT_NAME) ?>"><?php esc_html_e('Text position', 'wbg_plugin') ?></label></td>
                                <td>
                                    <select id="<?php echo esc_attr($effect::TEXT_POS_AUTO_INPUT_NAME) ?>"
                                            name="<?php echo esc_attr($effect::TEXT_POS_AUTO_INPUT_NAME) ?>">
                                        <?php
                                        $value = $effect->getTextPosAuto();
                                        $templates = array(
                                            'center' => 'Center',
                                            'center_top' => 'Center top',
                                            'center_bottom' => 'Center bottom',
                                            'top_left' => 'Top left',
                                            'top_right' => 'Top right',
                                            'bottom_left' => 'Bottom left',
                                            'bottom_right' => 'Bottom right',
                                        );


                                        $return = '';
                                        if (!isset($templates) || !is_array($templates) || empty($templates)) return '';
                                        $i = 1;
                                        foreach ($templates as $selectVal => $selectText) {
                                            if ($value == $selectVal) {
                                                $selected = 'selected';
                                            } else if ($value === NULL && $selectVal) {
                                                $selected = 'selected';
                                            } else {
                                                $selected = '';
                                            }
                                            printf('<option value="%s" %s/>%s</option>', esc_attr($selectVal), esc_attr($selected), esc_attr($selectText));
                                            $i++;
                                        } ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td><label
                                        for="<?php echo esc_attr($effect::FONT_SLUG_INPUT_NAME) ?>"><?php esc_html_e('Font', 'wbg_plugin') ?></label>
                                </td>
                                <td>
                                    <?php wbg_fonts_select($effect::FONT_SLUG_INPUT_NAME, esc_attr($effect->getFontSlug())); ?>
                                </td>
                            </tr>

                            <tr>
                                <td><label for="<?php echo esc_attr($effect::FONT_SIZE_INPUT_NAME) ?>"><?php esc_html_e('Font size (px)', 'wbg_plugin') ?></label>
                                </td>
                                <td><input required name="<?php echo esc_attr($effect::FONT_SIZE_INPUT_NAME) ?>"
                                           id="<?php echo esc_attr($effect::FONT_SIZE_INPUT_NAME) ?>"
                                           value="<?php echo esc_attr($effect->getFontSize()) ?>"></td>
                            </tr>

                            <tr>
                                <td><label for="<?php echo esc_attr($effect::FONT_COLOR_INPUT_NAME) ?>"><?php esc_html_e('Font color', 'wbg_plugin') ?></label></td>
                                <td><input required type="color" name="<?php echo esc_attr($effect::FONT_COLOR_INPUT_NAME) ?>"
                                           id="<?php echo esc_attr($effect::FONT_COLOR_INPUT_NAME) ?>"
                                           value="<?php echo esc_attr($effect->getFontColor()) ?>"></td>
                            </tr>

                            <tr>
                                <td class="padding-bottom-10px"><label
                                        for="<?php echo esc_attr($effect::DIRECTION_INPUT_NAME) ?>"><?php esc_html_e('Direction','wbg_plugin')?></label></td>
                                <td class="padding-bottom-10px">
                                    <?php $value = $effect->getDirection() ?>
                                    <select id="<?php echo esc_attr($effect::DIRECTION_INPUT_NAME) ?>"
                                            name="<?php echo esc_attr($effect::DIRECTION_INPUT_NAME) ?>">
                                        <option value="right" <?php selected($value, 'right', true )?>><?php esc_html_e('Right', 'wbg_plugin') ?>
                                        </option>
                                        <option value="left" <?php selected($value,'left', true )?>><?php esc_html_e('Left', 'wbg_plugin') ?>
                                        </option>
                                    </select>
                                </td>
                            </tr>


                            <tr>

                                <td><label for="<?php echo esc_attr($effect::RANDOM_INPUT_NAME) ?>"><?php esc_html_e('Generate random banner?','wbg_plugin')?></label></td>
                                <td><input type="checkbox"
                                           name="<?php echo esc_attr($effect::RANDOM_INPUT_NAME) ?>"
                                           id="<?php echo esc_attr($effect::RANDOM_INPUT_NAME) ?>"
                                           value="true"></td>
                            </tr>
                            <tr>
                                <td><label for="<?php echo esc_attr($effect::LIMIT_INPUT_NAME) ?>"><?php esc_html_e('Products limit for random banner','wbg_plugin')?></label>
                                </td>
                                <td><input required name="<?php echo esc_attr($effect::LIMIT_INPUT_NAME) ?>"
                                           id="<?php echo esc_attr($effect::LIMIT_INPUT_NAME) ?>"
                                           value="<?php echo esc_attr($effect->getMaxTiles()) ?>"></td>
                            </tr>


                        </table>
                    </td>
                    <td class="wbg-admin-prods-sort">
                        <?php wbg_get_template('adminSortPosts.php', array('products' => $products, 'userProducts' => $userProducts, 'ids' => $ids, 'gridX'=>$effect->getGridX(), 'gridY'=>$effect->getGridY())); ?>
                    </td>

                </tr>
            </table>
            <?php submit_button(esc_html__('Save settings', 'wbg_plugin'), $type = 'default', $effect::SUBMIT_SAVE, true); ?>
            <?php wbg_submit_gif_generate_section() ?>

        </form>


    </div> <!-- .inside -->
