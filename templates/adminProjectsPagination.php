<?php
/**
 * @package banner-generator-for-woocommerce
 * @var $projects_per_page
 * @var $current_page
 * @var $pages_total
 * @var $count_current_page_projects
 *
 */
?>
<?php

$settings_page = wbgAdmin::getInstance()->getSettingsPageHandle();
$projects_total = apply_filters('wbg_total_projects', 0);

?>
<?php if ($projects_total <= $count_current_page_projects) return ''; ?>

<div class="tablenav">
    <div class="tablenav-pages">
<span class="pagination-links">
    <span class="displaying-num"><?php echo esc_html($projects_total) ?> banners</span>
    <?php if (1 === $current_page): ?>
        <span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>
        <span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>
    <?php else: ?>
        <a class="prev-page"
           href="<?php echo admin_url(sprintf('options-general.php?page=%s&tab=1&p=1', $settings_page)) ?>"><span
                aria-hidden="true">&laquo;</span></a>
        <a class="first-page"
           href="<?php echo admin_url(sprintf('options-general.php?page=%s&tab=1&p=%d', $settings_page, $current_page - 1)) ?>"><span
                aria-hidden="true">&lsaquo;</span></a>
    <?php endif ?>

    <span class="screen-reader-text">Current Page</span><span id="table-paging" class="paging-input"><span
            class="tablenav-paging-text"><?php echo esc_html($current_page) ?> <?php esc_html_e('of', 'wbg_plugin')?> <span
                class="total-pages"><?php echo esc_html($pages_total) ?></span></span></span>

    <?php if ($pages_total === $current_page): ?>
        <span class="pagination-links"><span class="tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>
        <span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>
    <?php else: ?>
        <a class="last-page"
           href="<?php echo admin_url(sprintf('options-general.php?page=%s&tab=1&p=%d', esc_attr($settings_page), (int)$current_page + 1)) ?>"><span
                aria-hidden="true">&rsaquo;</span></a></span>
        <a class="next-page"
           href="<?php echo admin_url(sprintf('options-general.php?page=%s&tab=1&p=%d', esc_attr($settings_page), (int)$pages_total)) ?>"><span
                aria-hidden="true">&raquo;</span></a>

    <?php endif ?>
    </span>
    </div>
</div>







