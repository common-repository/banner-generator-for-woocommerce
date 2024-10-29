<?php
/**
 * @package banner-generator-for-woocommerce
 */

$uploadDir = wp_upload_dir();
$wbgPluginPath = plugin_dir_path(__FILE__);

if (is_dir($wbgPluginPath)) {
    /**
     *
     */
    define('WBG_PLUGIN_PATH', $wbgPluginPath);
} else {
    wp_die(esc_html__('Sorry. Something is wrong. Please reinstall plugin.', 'wbg_plugin'));
}

/**
 *
 */
define('WBG_SLUG', 'banner-generator-for-woocommerce');
/**
 *
 */
define('WBG_PREFIX', 'wbg_');

/**
 *
 */
define('WBG_UPLOADS_BASE_DIRECTORY', 'wbg_plugin');
/**
 *
 */
define('WBG_BANNERS_OUTPUT_DIRECTORY', 'wbg' . DIRECTORY_SEPARATOR . 'gifs');

/**
 *
 */
define('WBG_BANNERS_OUTPUT_DIRECTORY_URI', 'wbg/gifs');

/**
 *
 */
define('WBG_BANNERS_OUTPUT_DIRECTORY_PATH', $uploadDir['basedir'] . DIRECTORY_SEPARATOR . WBG_BANNERS_OUTPUT_DIRECTORY);
/**
 *
 */
define('WBG_BANNERS_OUTPUT_URI', $uploadDir['baseurl'] . '/' . WBG_BANNERS_OUTPUT_DIRECTORY_URI);

/**
 *
 */
define('WBG_INC_DIR', WBG_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR);
/**
 *
 */
define('WBG_URI', plugins_url() . '/' . WBG_SLUG);

/**
 *
 */
define('WBG_FRONT_DIR', WBG_INC_DIR . DIRECTORY_SEPARATOR . 'front' . DIRECTORY_SEPARATOR);
/**
 *
 */
define('WBG_INC_ADMIN_DIR', WBG_INC_DIR . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR);
/**
 *
 */
define('WBG_INC_SHARED_DIR', WBG_INC_DIR . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR);
/**
 *
 */
define('WBG_INC_FRONT_DIR', WBG_INC_DIR . DIRECTORY_SEPARATOR . 'front' . DIRECTORY_SEPARATOR);

/**
 *
 */
define('WBG_EFFECTS_DIR', WBG_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'effects' . DIRECTORY_SEPARATOR);
/**
 *
 */
define('WBG_CONFIG_DIR', WBG_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR);
/**
 *
 */
define('WBG_EFFECTS_URI', WBG_URI . '/' . 'effects' . '/');
/**
 *
 */
define('WBG_TEMP_DIR', WBG_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'wbgTemp');
/**
 *
 */
define('WBG_TEMP_DIR_URI', WBG_URI . '/wbgTemp');
/**
 *
 */
define('WBG_VENDOR_DIR_PATH', WBG_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'vendor');
/**
 *
 */
define('WBG_VENDOR_DIR_URI', WBG_URI . '/' . 'vendor');

/**
 *
 */
define('WBG_FONTS_PATH', WBG_VENDOR_DIR_PATH . DIRECTORY_SEPARATOR . 'fonts');

/**
 *
 */
define('WBG_OPTS_KEY', WBG_PREFIX . 'opts');
/**
 *
 */
define('WBG_PLUGIN_ASSETS_PATH', WBG_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets');
/**
 *
 */
define('WBG_PLUGIN_TEMP_PATH', WBG_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'temp');

/**
 *
 */
define('WBG_DEFAULT_FONT_FILE', WBG_PLUGIN_ASSETS_PATH . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'Ubuntu-C.ttf');
/**
 *
 */
define('WBG_PLUGIN_ASSETS_URI', plugins_url(WBG_SLUG . '/assets'), __FILE__);
/**
 *
 */
define('WBG_PLUGIN_TEMPLATES_PATH', WBG_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'templates');
/**
 *
 */
define('WBG_ALERT_VALIDATE_MAX_LENGHT', esc_html__('Too mutch characters! Maximum allowed characters is', 'wbg_plugin'));
/**
 *
 */
define('WBG_ALERT_VALIDATE_IS_NUMERIC', esc_html__('Value must be a number.', 'wbg_plugin'));
/**
 *
 */
define('WBG_ALERT_VALIDATE_BANNER_WIDTH', esc_html__('The value of "Width" field is incorrect. Value must be a number.', 'wbg_plugin'));
/**
 *
 */
define('WBG_ALERT_VALIDATE_BANNER_HEIGHT', esc_html__('The value of "Height" field is incorrect. Value must be a number.', 'wbg_plugin'));
/**
 *
 */
define('WBG_ALERT_VALIDATE_MIN', esc_html__('Validate error. The minimum value is', 'wbg_plugin'));
/**
 *
 */
define('WBG_ALERT_VALIDATE_MAX', esc_html__('Validate error. The maximum value is', 'wbg_plugin'));

/**
 *
 */
define('WBG_SANITIZE_STRING_FUNCTION', WBG_PREFIX . 'sanitize_string');
/**
 *
 */
define('WBG_SANITIZE_NUMBER_FUNCTION', WBG_PREFIX . 'sanitize_number');
/**
 *
 */
define('WBG_CHECKBOX_UNCHECKED', '0');
/**
 *
 */
define('WBG_CHECKBOX_CHECKED', '1');
/**
 *
 */
define('WBG_MSG_SUCCESS_ADD_NEW_PROJECT', esc_html__('project was created', 'wbg-plugin'));
/**
 *
 */
define('WBG_MSG_SUCCESS_CLONE', esc_html__('Project has been cloned', 'wbg-plugin'));
/**
 *
 */
define('WBG_MSG_SUCCESS_REMOVED', esc_html__('Project has been removed', 'wbg-plugin'));
/**
 *
 */
define('WBG_MSG_DANGER_REMOVED', esc_html__('Project remove failed', 'wbg-plugin'));
/**
 *
 */
define('WBG_MSG_SUCCESS_SAVE_SETTINGS', esc_html__('Settings saved successfully', 'wbg-plugin'));
/**
 *
 */
define('WBG_SECURITY_FAIL_MSG', esc_html__('Security check fail', 'wbg-plugin'));
define('WBG_ADMIN_FAIL_MSG', esc_html__('You must have administrator privileges to perform this operation', 'wbg-plugin'));

/**
 *
 */
define('WGB_THUMBNAIL_WIDTH_DEFAULT', 100);
/**
 *
 */
define('WBG_DEFAULT_FRAMES_LIMIT', 100);
/**
 *
 */
define('WBG_LOG_FILE', WBG_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'log.txt');
//post types:

/*
 * $bannerWidth, $bannerHeight, $gridX, $gridY, $backgroundColor, $padding
 */
//submits
/**
 *
 */

const SUBMIT_GENERATE = 'woo_grid_effect_generate';

/**
 *
 */
const SUBMIT_GENERATE_FIRST_FRAME = 'woo_grid_effect_generate_ff';


/**
 *
 */
define('WBG_SUBMIT_NEW_PROJECT', WBG_PREFIX . 'submit_n');
/**
 *
 */
define('WBG_SUBMIT_PROJECT_PROP', WBG_PREFIX . 'submit_pprop');
/**
 *
 */
define('WBG_SUBMIT_CLONE_PROJECT', WBG_PREFIX . 'submit_c');
/**
 *
 */
define('WBG_SUBMIT_RENAME_PROJECT', WBG_PREFIX . 'submit_ren');
/**
 *
 */
define('WBG_SUBMIT_REMOVE_PROJECT', WBG_PREFIX . 'submit_r');
/**
 *
 */
define('WBG_SUBMIT_OPEN_PROJECT', WBG_PREFIX . 'submit_o');
/**
 *
 */
define('WBG_SUBMIT_CLOSE_PROJECT', WBG_PREFIX . 'submit_cls');
/**
 *
 */
define('WBG_SUBMIT_SELECT_EFFECT', WBG_PREFIX . 'submit_sef');

/**
 *
 */
define('WBG_SUBMIT_NEW_PROJECT_NAME', WBG_PREFIX . 'submit_new_project_name');


/**
 *
 */
define('WBG_INPUT_NEW_PROJECT', WBG_PREFIX . 'new_proj_name');
/**
 *
 */
define('WBG_INPUT_RENAME_PROJECT_NAME', WBG_PREFIX . 'ren_proj_name');
/**
 *
 */
define('WBG_INPUT_FRAMES_BANNER_WIDTH', WBG_PREFIX . 'frames_banner_w');
/**
 *
 */
define('WBG_INPUT_FRAMES_BANNER_HEIGHT', WBG_PREFIX . 'frames_banner_h');
/**
 *
 */
define('WBG_INPUT_BANNER_ALT', WBG_PREFIX . 'banner_alt');

/**
 *
 */
define('WBG_INPUT_EFFECT', WBG_PREFIX . 'selected_effect');
/**
 *
 */
define('WBG_HIDDEN_INPUT_PROJECT', WBG_PREFIX . 'proj_id');
