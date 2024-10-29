<?php
/**
 * @package banner-generator-for-woocommerce
 */


function wbg_u_to_c($string)
{
    if (is_string($string)) {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    } else {
        return '';
    }
}

/**
 * @param $value
 * @param array $args
 * @return array
 */
function wbg_sanitize_string($value, $args = array())
{
    if (isset($args['max_lenght']) && is_numeric($args['max_lenght'])) {
        if (strlen(utf8_decode($value)) > $args['max_lenght']) {
            return array(false, sprintf('%s %d', WBG_ALERT_VALIDATE_MAX_LENGHT, $args['max_lenght']));
        }
    }
    return array(true, trim($value));
}


/**
 * @param $value
 * @param array $args
 * @return array
 */
function wbg_sanitize_number($value, $args = array())
{
    if (!is_numeric($value)) {
        return array(false, WBG_ALERT_VALIDATE_IS_NUMERIC);
    }

    if (isset($args['min']) && is_numeric($args['min'])) {
        if ($value < $args['min']) {
            return array(false, sprintf('%s %d', WBG_ALERT_VALIDATE_MIN, $args['min']));
        }
    }

    if (isset($args['max']) && is_numeric($args['max'])) {
        if ($value > $args['max']) {
            return array(false, sprintf('%s %d', WBG_ALERT_VALIDATE_MAX, $args['max']));
        }
    }

    return array(true, $value);
}

/**
 * @param $name
 */
function wbg_field_value($name)
{
    echo esc_attr(wbgAdmin::getInstance()->getOption($name));
}

/**
 * @return string
 */
function wbg_get_admin_control_sec_open()
{
    return '<div class="suf-grouping-rhs">';
}

/**
 * @return string
 */
function wbg_get_admin_control_sec_close($desc = '')
{
    return sprintf('<p class="wbg-desc">%s</p></div>', $desc);
}

/**
 * @param $text
 * @return string
 */
function wbg_get_admin_desc($text)
{
    if ('' === $text) {
        return '';
    }
    return sprintf('<span class="wbg-desc">%s</span>', esc_html($text));
}


/**
 * @param $name
 */
function wbg_text($name)
{
    $control = wbgAdmin::getInstance()->getControl($name);
    if (NULL === $control) return;
    $value = wbgAdmin::getInstance()->getOption($name);
    $return = sprintf('<input name="%s" value="%s">%s', esc_html($name), $value, '<br><br>');
    echo wp_kses($return . PHP_EOL . wbg_get_admin_desc($control['desc']), array('input' => array('name' => array(), 'value' => array()), 'br' => array(), 'span' => array('class' => array())));
}


/**
 * @param $name
 * <label for="close_comments_for_old_posts">
 * <input name="close_comments_for_old_posts" id="close_comments_for_old_posts" value="1" type="checkbox">
 * Automatically close comments on articles older than </label>
 */
function wbg_checkbox($name)
{
    $control = wbgAdmin::getInstance()->getControl($name);
    if (NULL === $control) return;
    $value = wbgAdmin::getInstance()->getOption($name);
    if (NULL === $value) {
        $value = $control['default'];
    }

    if ($value === WBG_CHECKBOX_CHECKED) {
        $checked = 'checked';
    } else {
        $checked = '';
    }
    $return = sprintf('<label for="%s">%s<input id="%s" name="%s" value="%s" type="checkbox" %s></label>%s', esc_html($name), $control['label'], esc_html($name), esc_html($name), WBG_CHECKBOX_CHECKED, $checked, '<br><br>');
    echo wp_kses($return . PHP_EOL . wbg_get_admin_desc($control['desc'] . ('' === $control['desc'] ? '' : '<br><br>')), array('input' => array('checked' => array(), 'name' => array(), 'value' => array(), 'type' => array(), 'id' => array()), 'br' => array(), 'span' => array('class' => array()), 'label' => array()));
}


/**
 * @param $name
 * @return string
 */
function wbg_select($name, $custom = false, $customVal = '')
{
    if (is_array($custom)) {
        $control = $custom;
        $value = $customVal;
    } else {
        $control = wbgAdmin::getInstance()->getControl($name);
        $value = wbgAdmin::getInstance()->getOption($name);
    }
    if (NULL === $control) return '';
    $return = '';
    if (!isset($control['options']) || !is_array($control['options']) || empty($control['options'])) return '';
    $i = 1;
    foreach ($control['options'] as $selectVal => $selectText) {

        if ($value == $selectVal) {
            $selected = 'selected';
        } else if ($value === NULL && $selectVal == $control['default']) {
            $selected = 'selected';
        } else {
            $selected = '';
        }
        $return .= sprintf('<option value="%s" %s/>%s</option>', $selectVal, $selected, $selectText);
        $i++;
    }
    echo wp_kses(sprintf('<select name="%s">%s</select>%s', $name, $return, '<br><br>') . wbg_get_admin_desc($control['desc']),
        array('select' => array('name' => array()), 'option' => array('value' => array()), 'br' => array(), 'span' => array('class' => array())));
}


/**
 * @param $path
 * @return string
 */
function wbg_get_effect_filename($path)
{
    return 'wbg' . ucfirst(basename($path)) . 'Effect.class.php';

}

/**
 * @param $path
 * @return string
 */
function wbg_get_effect_class_name($path)
{
    return 'wbg' . ucfirst(basename($path)) . 'Effect';
}


/**
 * @param $dirname
 */
function wbg_remove_dir_with_files($dirname)
{
    array_map('unlink', glob("$dirname/*.*"));
    rmdir($dirname);
}


/**
 * @param $whole
 * @param $end
 * @return bool
 */
function wbg_string_ends_with($whole, $end)
{
    return (strpos($whole, $end, strlen($whole) - strlen($end)) !== false);
}

/**
 * @param $message
 */
function wbg_update_log_file($message)
{
    $text = sprintf("%s : %s", date("Y-m-d H:i:s", time()), $message);
    $file = fopen(WBG_LOG_FILE, "a") or wbgAlerts::getInstance()->addAlertSimple(esc_html__('Can\'t open log file to write!', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');;
    fwrite($file, $text . PHP_EOL);
    fclose($file);
}


/**
 * @param $width
 * @param $height
 * @param $minWidth
 * @param $maxWidth
 * @param $maxHeight
 * @return bool
 */
function wbg_calculate_image_height($width, $height, $minWidth, $maxWidth, $maxHeight)
{

    $aspect = (int)$width / (int)$height;

    for ($i = $maxWidth; $i > $minWidth; $i--) {
        if ($newHeight = $i / (float)$aspect > $maxHeight) {
            continue;
        } else {
            return $newHeight;
        }
    }
    return $height;
}


/**
 * @param $field_name
 */
function wbg_submit_gif_generate_section()
{
    if (is_object($project = wbgProject::getActiveProjectInstance())) {
        wbg_get_template('adminGifGenerate.php', array('project' => $project));//load generate submit template
    }
}


/**
 * @param $pageHandle
 * @param $numericTabID
 */
function wbg_js_redirect($pageHandle, $numericTabID)
{
    $url = admin_url(sprintf('options-general.php?page=%s&tab=%d', esc_attr($pageHandle), (int)$numericTabID));
    echo '<script type="text/javascript">window.location = "' . $url . '"</script>';
}

/**
 * @param $colorCode
 * @return bool
 */
function wbg_check_valid_colorhex($colorCode)
{
    // If user accidentally passed along the # sign, strip it off
    $colorCode = ltrim($colorCode, '#');

    if (
        ctype_xdigit($colorCode) &&
        (strlen($colorCode) == 6 || strlen($colorCode) == 3)
    )
        return true;

    else return false;
}


/**
 * @return mixed|void
 */
function wbg_get_settings()
{
    return apply_filters(WBG_PREFIX . WBG_SLUG . 'option_fields', array());
}

/**
 * @return wbgFonts
 */
function wbg_get_fonts_instance()
{
    return new wbgFonts();
}

/**
 * @param $fieldName
 * @param $fontSlug
 */
function wbg_fonts_select($fieldName, $fontSlug)
{
    echo wbgFonts::getInstance()->getSelect($fieldName, $fontSlug);
}


/**
 * @param $slug
 * @return string
 */
function wbg_get_font_path_by_slug($slug)
{
    $obj = new wbgFonts;
    $fontPath = $obj->getPathBySlug($slug);


    if (!$fontPath) {
        wbgAlerts::getInstance()->addAlertSimple(sprintf(esc_html__('The font "%s" field does not exist!', 'wbg_plugin'), $slug), 'danger', FALSE, TRUE, '', '', '');
        return WBG_DEFAULT_FONT_FILE;
    }

    return $fontPath;

}


/**
 * @return bool
 */
function wbg_is_woocommerce_active()
{
    return class_exists('WooCommerce');
}

/**
 * @param string $classes
 * @return string
 */
function wbg_sanitize_html_classes($classes = '')
{
    $arr = explode(' ', $classes);
    $return = '';
    foreach ($arr as $class) {
        $return .= sanitize_html_class($class);
        $return .= ' ';
    }
    return rtrim($return);
}
