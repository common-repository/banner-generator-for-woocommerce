<?php
/**
 * @package banner-generator-for-woocommerce
 */
?>
<?php
add_filter(WBG_PREFIX . WBG_SLUG . 'option_fields', function () {
    return array(
        'projects_per_page' => array(
            'name' => esc_html__('Projects per page'),
            'default' => '5',
            'label' => '',
            'desc' => esc_html__('', 'wbg_plugin'),
            'sanitizeCallback' => WBG_SANITIZE_NUMBER_FUNCTION,
            'validateArgs' => array('min' => 1),
        ),
        'frames_limit' => array(
            'name' => esc_html__('Frames limit', 'wbg_plugin'),
            'default' => WBG_DEFAULT_FRAMES_LIMIT,
            'label' => '',
            'desc' => '',
            'sanitizeCallback' => WBG_SANITIZE_NUMBER_FUNCTION,
            'validateArgs' => array('min' => 2, 'max'=>200),
        ),
        'open_projects_in_background' => array(
            'type' => 'checkbox',
            'default' => WBG_CHECKBOX_CHECKED,
            'label' => esc_html__('Open new banners in background', 'wbg_plugin'),
            'desc' => '',
            'sanitizeCallback' => false,
        ),
        'confirm_overwriting_banner' => array(
            'type' => 'checkbox',
            'default' => WBG_CHECKBOX_CHECKED,
            'label' => esc_html__('Always confirm overwriting banner', 'wbg_plugin'),
            'desc' => '',
            'sanitizeCallback' => false,
        ),
    );
});

