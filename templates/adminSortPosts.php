<?php
/**
 * @var  $products
 * @var  $userProducts
 * @var  $ids
 * @var  $gridY
 * @var  $gridX
 * @package banner-generator-for-woocommerce
 *
 */
?>
<h3><?php esc_html_e('Choice items to appear on banner', 'wbg_plugin') ?></h3>
<ul id="sortable1" class="wbgSortable">
    <?php /**
     * @var $p WC_Product
     */ ?>
    <?php foreach ($products as $p): ?>
        <li class="ui-state-default wbg-post-sort-thumb" data-wbg-woo-item="<?php echo esc_attr($p->get_id()) ?>"
            title="<?php echo esc_attr($p->get_title()) ?>">
            <?php echo wp_kses($p->get_image('thumbnail'), array('img' => array('src' => array(), 'title' => array(), 'alt' => array()))) ?>
        </li>
    <?php endforeach; ?>
</ul>
<div class="centered"><h4><span
            class="dashicons dashicons-arrow-down-alt"></span><?php esc_html_e('Drag items below', 'wbg_plugin') ?><span
            class="dashicons dashicons-arrow-down-alt"></span</h4></div>
<ul id="sortable2"
    class="wbgSortable">
    <?php foreach ($userProducts as $p): ?>
        <li class="ui-state-default wbg-post-sort-thumb" data-wbg-woo-item="<?php echo esc_attr($p->get_id()) ?>"
            title="<?php echo esc_attr($p->get_title()) ?>">
            <?php echo wp_kses($p->get_image('thumbnail'), array('img' => array('src' => array(), 'title' => array(), 'alt' => array()))) ?>
        </li>
    <?php endforeach; ?>
</ul>

<input type="hidden" name="woo_grid_effect_ids" value="<?php if ($json = json_encode($ids)):
    echo esc_js($json) ?><?php endif ?>" id="wbg-woo-items">