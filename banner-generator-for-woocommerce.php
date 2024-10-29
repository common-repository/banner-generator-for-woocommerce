<?php
/*
Plugin Name: Banner Generator for Woocommerce
Plugin URI: https://wordpress.org/plugins/banner-generator-for-woocommerce/
Description: By this plugin, you can build and manage animated banners with your woocommerce products.
Author: Patrycjusz Marciniak
Version: 1.0.2
Author URI: https://wordpress.org/plugins/banner-generator-for-woocommerce/
License: GPLv2 or later
Text Domain: wbg_plugin
 */


/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
final class wbg
{
    /**
     * @var null
     */
    static private $instance = NULL;
    /**
     * @var null
     */
    private $tempDirPath = NULL;


    /**
     * @return null|wbg
     */
    public static function getInstance()
    {
        if (NULL === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    /**
     * wbg constructor.
     */
    private function __construct()
    {
        $this->setupGlobals();
        $this->includes();
        $this->setupTempDir();
    }


    /**
     *
     */
    private function includes()
    {
        include_once('const.php');
        include_once(WBG_INC_SHARED_DIR . 'wbgHelpers.php');
        include_once(WBG_INC_SHARED_DIR . 'wbgTemplateLoader.php');
        if (is_admin()) {
            include_once(WBG_INC_ADMIN_DIR . 'wbg.alerts.class.php');
            require_once(WBG_INC_ADMIN_DIR . 'wbgAdmin.class.php');
            require_once(WBG_CONFIG_DIR . 'fonts.class.php');
            require_once(WBG_CONFIG_DIR . 'settings.php');
            require_once(WBG_INC_ADMIN_DIR . 'wbgProject.class.php');
            require_once(WBG_INC_ADMIN_DIR . 'wbgEffect.Interface.php');
            require_once(WBG_INC_ADMIN_DIR . 'wbgAnimator.class.php');
            require_once(WBG_INC_ADMIN_DIR . 'wbgProjectToGifManager.class.php');
            require_once(WBG_INC_ADMIN_DIR . 'wbgGifEncoderManager.class.php');
            require_once(WBG_VENDOR_DIR_PATH . DIRECTORY_SEPARATOR . 'gifencoder' . DIRECTORY_SEPARATOR . 'gifencoder.class.php');
            require_once(WBG_INC_ADMIN_DIR . 'wbgJob.class.php');
            require_once(WBG_INC_ADMIN_DIR . 'wbgWoocommerce.class.php');
        }
    }

    /**
     *
     */
    private function setupGlobals()
    {
        global $wbg_template;
        $wbg_template = NULL;
    }

    /**
     * Setup temp dir
     */
    private function setupTempDir()
    {
        if (!file_exists(WBG_TEMP_DIR) || !is_writable(WBG_TEMP_DIR)) {
            $this->setTempDirPath(NULL);
        } else {
            $this->setTempDirPath(WBG_TEMP_DIR);
        }
    }

    /**
     * @return null
     */
    public function getTempDirPath()
    {
        return $this->tempDirPath;
    }

    /**
     * @param null $tempDirPath
     */
    public function setTempDirPath($tempDirPath)
    {
        $this->tempDirPath = $tempDirPath;
    }


}

wbg::getInstance();