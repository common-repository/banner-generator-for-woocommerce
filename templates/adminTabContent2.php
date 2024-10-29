<?php
/**
 * @package banner-generator-for-woocommerce
 */
?>
<?php $modules = wbgAdmin::getInstance()->getEffects() ?>

<div id="post-body">
    <div id="post-body-content">

        <div class="postbox">
            <h3><span><?php esc_html_e('Available animation modulesWidth(px)', 'wbg_plugin') ?></span></h3>
            <div class="inside">
                <div class="wrap">
                    <table class="wbg-projects-table">
                        <thead>
                        <tr>
                            <td><span class="wbg-bold"></span>
                            </td>
                            <td class="centered"><span class="wbg-bold"><?php esc_html_e('Name', 'wbg_plugin') ?></span></td>
                            <td class="centered"><span class="wbg-bold"><?php esc_html_e('Version', 'wbg_plugin') ?></span>
                            </td>
                            <td class="centered"><span class="wbg-bold"><?php esc_html_e('Description', 'wbg_plugin') ?></span></td>
                        </tr>
                        </thead>

                        <?php foreach ($modules as $module): ?>
                            <form method="post" action="">
                                <tr>
                                    <td><img src="<?php echo esc_url($module->getThumbnail()) ?>"></td>
                                    <td><?php echo esc_html($module->getName()) ?></td>
                                    <td><?php echo esc_html($module->getVersion()) ?></td>
                                    <td><?php echo esc_html($module->getDescription()) ?></td>
                                </tr>
                            </form>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div> <!-- .inside -->
        </div>

    </div> <!-- #post-body-content -->
</div> <!-- #post-body -->