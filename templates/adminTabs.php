<?php
/**
 * @package banner-generator-for-woocommerce
 * @var $tab_names
 * @var $active_tab
 * @var $settings_page_handle
 */
?>
    <div class="wrap">
        <div id="icon-options-general" class="icon32"></div>
        <h2 class="nav-tab-wrapper">
            <form method="post" action="">
                <?php foreach ($tab_names as $k => $name): ?>
                    <?php $class = apply_filters('wbg_tab_link_class_' . $k, 'nav-tab' . (((int)$k + 1 === (int)$active_tab) ? ' nav-tab-active' : '')) ?>
                    <a href="<?php echo admin_url(sprintf('admin.php?page=%s&tab=%d', $settings_page_handle, $k + 1)) ?>"
                       class="<?php echo wbg_sanitize_html_classes($class) ?>">
                        <?php echo esc_html($name) ?>
                        <?php do_action('wbg_tab_link_inside_' . $k) ?>
                    </a>
                <?php endforeach ?>
            </form>
        </h2>
        <div class="metabox-holder has-right-sidebar" id="wbg-metabox-holder">
            <div id="wbg-allert-wrapper"></div>
            <?php
            do_action('wbg_admin_page_tab_content_before', $active_tab);
            wbg_get_template('adminSidebar.php', array('settings_page_handle'=>$settings_page_handle));//load empty effect template
            do_action('wbg_admin_page_tab_content', $active_tab);
            do_action('wbg_admin_page_tab_content_after', $active_tab);
            ?>
        </div> <!-- .metabox-holder -->
    </div> <!-- .wrap -->
<?php do_action('wbg_admin_after_tabs') ?>