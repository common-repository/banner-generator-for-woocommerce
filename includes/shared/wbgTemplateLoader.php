<?php
/**@package banner-generator-for-woocommerce
 * @param $templateName
 * @param array $args
 * @throws Exception
 */

function wbg_get_template($templateName, $args = array(), $directpath = FALSE)
{

    if ($args && is_array($args)) {
        extract($args);
    }


    if (!$directpath) {
        $template = WBG_PLUGIN_TEMPLATES_PATH . DIRECTORY_SEPARATOR . $templateName;
    } else {
        $template = $templateName;
    }


    if (!file_exists($template)) {
        throw new Exception(sprintf('%s: %s does not exist.', __FUNCTION__, $template));
        return;
    }

    global $wbg_template;
    $wbg_template = $template;
    include($template);

    do_action('wbg_after_content');
}