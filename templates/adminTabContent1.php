<?php
/**
 * @package banner-generator-for-woocommerce
 */
?>
<div id="post-body">
    <div id="post-body-content">

        <div class="postbox">
            <h3><span><?php esc_html_e('Banners manager', 'wbg_plugin') ?></span></h3>
            <div class="inside">
                <div class="wrap">

                    <div class="wbg-table-container">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php esc_html_e('Add new banner', 'wbg_plugin') ?></th>
                                <td>
                                    <form method="post" action="">
                                        <?php wp_nonce_field('wbg_nonce_new','wbg_nonce_new'); ?>
                                        <fieldset>
                                            <p><?php esc_html_e('Banner name', 'wbg_plugin') ?></p>
                                            <p>
                                                <input required name="<?php echo WBG_INPUT_NEW_PROJECT ?>" value="">
                                                <?php submit_button(esc_html__('Add new', 'wbg_plugin'), $type = 'primary', $name = WBG_SUBMIT_NEW_PROJECT); ?>
                                            </p><br><br>
                                        </fieldset>
                                        <?php
                                        // This prints out all hidden setting fields
                                        settings_fields(WBG_PREFIX . WBG_SLUG . 'option_group');
                                        do_settings_sections(WBG_PREFIX . WBG_SLUG . 'settings_admin');
                                        ?>
                                    </form>
                                </td>
                            </tr>


                            <?php $projects = wbgProject::getProjectModelCollection(TRUE) ?>
                            <?php if (!empty($projects)): ?>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Your banners:', 'wbg_plugin') ?></th>
                                    <td>
                                        <fieldset>
                                            <table class="wbg-projets-table wp-list-table widefat striped">
                                                <thead>
                                                <tr>
                                                    <td><span
                                                            class="wbg-bold"><?php esc_html_e('Name', 'wbg_plugin') ?></span>
                                                    </td>
                                                    <td><span
                                                            class="wbg-bold"><?php esc_html_e('Date created', 'wbg_plugin') ?></span>
                                                    </td>
                                                    <td colspan="4" class="centered"><span
                                                            class="wbg-bold">Operations</span></td>
                                                </thead>

                                                <?php foreach ($projects as $project): ?>
                                                    <tr>
                                                        <form method="post" action="">
                                                            <?php wp_nonce_field('wbg_nonce_operation_'.$project->getID(),'wbg_nonce_operation_'.$project->getID()); ?>
                                                            <input name="<?php echo WBG_HIDDEN_INPUT_PROJECT ?>"
                                                                   type="hidden"
                                                                   value="<?php echo esc_attr($project->getID()) ?>">

                                                            <td data-slug="<?php echo esc_attr($project->getSlug()) ?>">
                                                                <?php echo esc_html($project->getName()) ?>
                                                                <?php if ($project->getThumbSrc()): ?>
                                                                    <div class="spacer10"></div>
                                                                    <img
                                                                        style="max-width:100%; max-height:100px; max-width: 100px; height:auto;"
                                                                        src="<?php echo esc_url($project->getThumbSrc()) ?>">
                                                                <?php endif ?>
                                                            </td>
                                                            <td><?php echo esc_html($project->getDateCreatedYYYYMMDD()) ?></td>
                                                            <td><?php submit_button(esc_html__("Rename", 'wbg-plugin') . '', $type = 'default wbg-rename-button', WBG_SUBMIT_RENAME_PROJECT, true); ?></td>
                                                            <td><?php submit_button(esc_html__('Clone', 'wbg-plugin'), $type = 'default', WBG_SUBMIT_CLONE_PROJECT); ?></td>
                                                            <td><?php submit_button(esc_html__('Remove', 'wbg-plugin'), $type = 'default wbg_confirm', WBG_SUBMIT_REMOVE_PROJECT, true,
                                                                    array('data-confirmation' => esc_html__('Banner and gif file will be deleted! This operation can not be undone! Continue?', 'wbg-plugin'))); ?>
                                                            </td>
                                                            <td>
                                                                <?php if (!$project->getIsOpen()): ?>
                                                                    <?php submit_button(esc_html__('Open', 'wbg_plugin'), $type = 'default', WBG_SUBMIT_OPEN_PROJECT, true); ?>
                                                                <?php endif; ?>
                                                            </td>
                                                    </tr>
                                                    </form>
                                                <?php endforeach; ?>
                                                <tr class="wbg-table-nostyle">
                                                    <td colspan="6" style="text-align: right">
                                                        <?php wbg_get_template('adminProjectsPagination.php',
                                                            array(
                                                                'current_page' => !($currentPage = apply_filters('wbg_projects_page', NULL)) ? 1 : (int)$currentPage,
                                                                'projects_per_page' => (int)wbgAdmin::getInstance()->getOption('projects_per_page'),
                                                                'pages_total' => apply_filters('wbg_pages_total', 1),
                                                                'count_current_page_projects' => count($projects)
                                                            )
                                                        ) ?>
                                                    </td>
                                                </tr>
                                            </table>


                                        </fieldset>
                                    </td>
                                </tr>
                            <?php endif ?>


                            <?php //wbg_select('my_select')?>

                        </table>

                    </div>
                </div> <!-- .inside -->
            </div>

        </div> <!-- #post-body-content -->
    </div> <!-- #post-body -->