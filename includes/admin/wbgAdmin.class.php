<?php

/**
 * @package banner-generator-for-woocommerce
 */
class wbgAdmin
{

    /**
     * @var array
     */
    private $effects = array();

    /**
     * @var null
     */
    static private $instance = NULL;


    /**
     * @var array
     */
    private $options = array();


    /**
     * @return wbgAdmin|null
     */
    public static function getInstance()
    {
        if (NULL === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    /**
     * @return bool
     */
    private function checkWoocommerce()
    {
        if (!wbg_is_woocommerce_active()) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('Please install and enable Woocommerce to use this plugin', 'wbg_plugin'), 'danger', FALSE, TRUE, '', '', '');
            return FALSE;
        }
        return TRUE;
    }


    /**
     * @return mixed|void
     */
    private function getControls()
    {
        return wbg_get_settings();
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getControl($name)
    {
        $controlsArr = $this->getControls();
        if (!isset($controlsArr[$name])) {
            wbgAlerts::getInstance()->addAlertSimple(sprintf(esc_html__('The "%s" field does not exist!', 'wbg_plugin'), $name), 'danger', FALSE, TRUE, '', '', '');
            return NULL;
        }

        return $controlsArr[$name];
    }

    /**
     * @var string
     */
    private $settingsPageHandle = 'wbg_options_handle';

    /**
     * @return mixed|void
     */
    private function getTabs()
    {
        return apply_filters('wbg_settings_page_tabs', array(
                esc_html__('Manage banners', 'wbg_plugin'),
                esc_html__('Effects', 'wbg_plugin'),
                esc_html__('Settings', 'wbg_plugin'),
                esc_html__('About / Help', 'wbg_plugin')
            )
        );
    }


    /**
     * wbgAdmin constructor.
     */
    private function __construct()
    {
        if (!is_admin()) return;//is admin screen?
        $this->initHooks();
    }


    /**
     *
     */
    public function wbgAdminInit()
    {
        if (is_admin() && current_user_can('administrator')) {
            do_action('wbg_admin');
        }

    }

    /**
     *
     */
    public function initHooks()
    {
        add_action('init', array($this, 'wbgAdminInit'), 101);//wp hook
        add_action('wbg_admin', array($this, 'setOptionsFromDB'), 50);//get all settings from db
        add_action('wbg_admin', array($this, 'configure'), 60);//additional configs
        add_action('wbg_admin', array($this, 'updateOptions'), 100);//save options from request
        add_action('wbg_admin', array($this, 'loadEffects'), 150);//find and load effects
        add_action('wbg_admin', array($this, 'doSubmits'), 200);//other submits
        add_action('wbg_admin', array('wbgProject', 'closeProject'), 250);//close project
        add_action('admin_menu', array($this, 'addTopMenuEntry'), 100);//wp hook
        add_filter('wbg_settings_page_tabs', array($this, 'getProjectTabs'), 500);//add project tabs
        add_action('wbg_admin_page_tab_content', array($this, 'includeTabContent'), 10);//add tab content
        add_action('wbg_admin_after_tabs', array($this, 'includeAlerts'), 20);//add alerts
        add_action('admin_enqueue_scripts', array($this, 'addAdminScripts'));//wp hook
    }



    /**
     *
     */
    public function doSubmits()
    {

        //pagination
        add_filter('wbg_projects_page', function () {
            return wbgAdmin::getFromRequest('p', FALSE, FALSE);
        });


        //add new
        if (wbgAdmin::getFromRequest(WBG_SUBMIT_NEW_PROJECT, 'wbg_nonce_new', 'wbg_nonce_new')) {
            $name = self::getFromRequest(WBG_INPUT_NEW_PROJECT, 'wbg_nonce_new', 'wbg_nonce_new');
            if (empty(trim($name)) || is_numeric($name)) {
                wbgAlerts::getInstance()->addAlertSimple(esc_html__('Incorrect banner name', 'wbg_plugin'), 'danger', FALSE, TRUE, '', '', '');
                return;
            }
            new wbgProject(NULL, self::getFromRequest(WBG_INPUT_NEW_PROJECT, 'wbg_nonce_new', 'wbg_nonce_new'));
        }


        /*
         * Actions: open, clone, rename, remove
         */
        if (isset($_POST[WBG_HIDDEN_INPUT_PROJECT])) {
            $actionsProjectID = sanitize_text_field($_POST[WBG_HIDDEN_INPUT_PROJECT]);
        } else {
            $actionsProjectID = '';
        }

        //clone
        if (wbgAdmin::getFromRequest(WBG_SUBMIT_CLONE_PROJECT, 'wbg_nonce_operation_' . $actionsProjectID, 'wbg_nonce_operation_' . $actionsProjectID)) {
            wbgProject::cloneProject(self::getFromRequest(WBG_HIDDEN_INPUT_PROJECT, 'wbg_nonce_operation_' . $actionsProjectID, 'wbg_nonce_operation_' . $actionsProjectID));
        }

        //remove
        if (wbgAdmin::getFromRequest(WBG_SUBMIT_REMOVE_PROJECT, 'wbg_nonce_operation_' . $actionsProjectID, 'wbg_nonce_operation_' . $actionsProjectID)) {
            wbgProject::removeProject(self::getFromRequest(WBG_HIDDEN_INPUT_PROJECT, 'wbg_nonce_operation_' . $actionsProjectID, 'wbg_nonce_operation_' . $actionsProjectID));
        }

        //open
        if (wbgAdmin::getFromRequest(WBG_SUBMIT_OPEN_PROJECT, 'wbg_nonce_operation_' . $actionsProjectID, 'wbg_nonce_operation_' . $actionsProjectID)) {
            wbgProject::openProjectOnTab(self::getFromRequest(WBG_HIDDEN_INPUT_PROJECT, 'wbg_nonce_operation_' . $actionsProjectID, 'wbg_nonce_operation_' . $actionsProjectID));
        }

        //rename project - open template
        if (wbgAdmin::getFromRequest(WBG_SUBMIT_RENAME_PROJECT, 'wbg_nonce_operation_' . $actionsProjectID, 'wbg_nonce_operation_' . $actionsProjectID)) {
            $projectID = self::getFromRequest(WBG_HIDDEN_INPUT_PROJECT, 'wbg_nonce_operation_' . $actionsProjectID, 'wbg_nonce_operation_' . $actionsProjectID);
            add_filter('wbg_admin_custom_content_tab_1', '__return_true');
            add_action('wbg_admin_custom_tab_content_1', function () use ($projectID) {//include template
                wbg_get_template('adminRenameProject.php', array('project_ID' => $projectID));
            }
            );
        }

        //donate  project
        if (wbgAdmin::getFromRequest('donate', 'wbg_nonce_donate', 'wbg_nonce_donate')) {
            add_filter('wbg_admin_custom_content_tab_1', '__return_true');
            add_action('wbg_admin_custom_tab_content_1', function () {//include template
                wbg_get_template('adminDonate.php', array());
            }
            );
        }


        //rename - sumbit
        if (self::getFromRequest(WBG_SUBMIT_NEW_PROJECT_NAME, 'wbg_nonce_rename', 'wbg_nonce_rename')) {
            $newName = (self::getFromRequest(WBG_INPUT_RENAME_PROJECT_NAME, 'wbg_nonce_rename', 'wbg_nonce_rename'));
            $newName = trim($newName);
            if (!empty($newName) || !is_numeric($newName)) {
                wbgProject::renameProject($projectID = self::getFromRequest(WBG_HIDDEN_INPUT_PROJECT, 'wbg_nonce_rename', 'wbg_nonce_rename'), self::getFromRequest(WBG_INPUT_RENAME_PROJECT_NAME, 'wbg_nonce_rename', 'wbg_nonce_rename'));
            } else {
                wbgAlerts::getInstance()->addAlertSimple(esc_html__('Incorrect banner name', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            }
        }


        //select effect
        if (self::getFromRequest(WBG_SUBMIT_SELECT_EFFECT, 'wbg_nonce_select_effect', 'wbg_nonce_select_effect')) {
            $effect = self::getFromRequest(WBG_INPUT_EFFECT, 'wbg_nonce_select_effect', 'wbg_nonce_select_effect');//get effect from submit
            add_action('wbg_project_' . wbgAdmin::getFromRequest(WBG_HIDDEN_INPUT_PROJECT, 'wbg_nonce_select_effect', 'wbg_nonce_select_effect') . '_created',//connect hook in project constructor
                function ($project) use ($effect) {
                    $project->setEffect($effect);//set effect from submit
                    $project->updateProjectDB();//save selected effect
                }
            );
        }


        //project properties
        if (self::getFromRequest(WBG_SUBMIT_PROJECT_PROP, 'wbg_nonce_project_prop', 'wbg_nonce_project_prop')) {
            $width = self::getFromRequest(WBG_INPUT_FRAMES_BANNER_WIDTH, 'wbg_nonce_project_prop', 'wbg_nonce_project_prop');
            $height = self::getFromRequest(WBG_INPUT_FRAMES_BANNER_HEIGHT, 'wbg_nonce_project_prop', 'wbg_nonce_project_prop');
            $alt = esc_attr(self::getFromRequest(WBG_INPUT_BANNER_ALT, 'wbg_nonce_project_prop', 'wbg_nonce_project_prop'));
            if (is_numeric($width) && is_numeric($height)) {
                add_action('wbg_active_project',//connect hook in project constructor
                    function ($project) use ($width, $height, $alt) {
                        $project->setWidth($width);
                        $project->setHeight($height);
                        $project->setAlt($alt);
                        $project->updateProjectDB();//save selected effect
                        wbgAlerts::getInstance()->addAlertSimple(esc_html__('Banner properties has been saved correctly', 'wbg_plugin'), 'success', FALSE, TRUE, '', '', '');
                    }
                );
            } else {
                if (!is_numeric($width)) {
                    wbgAlerts::getInstance()->addAlertSimple(WBG_ALERT_VALIDATE_BANNER_WIDTH, 'warning', FALSE, TRUE, '', '', '');
                }

                if (!is_numeric($height)) {
                    wbgAlerts::getInstance()->addAlertSimple(WBG_ALERT_VALIDATE_BANNER_HEIGHT, 'warning', FALSE, TRUE, '', '', '');
                }

            }


        }


    }


    /**
     *
     */
    public function addAdminScripts()
    {
        wp_enqueue_style('wbg-admin-css', WBG_PLUGIN_ASSETS_URI . '/css/wbg-admin.css');
        wp_enqueue_script('wbg-admin-js', WBG_PLUGIN_ASSETS_URI . '/js/wbg-admin.js');
        wp_enqueue_script('wbg-jquery-ui-css', WBG_PLUGIN_ASSETS_URI . '/plugins/jquery-ui-1.12.1.custom/jquery-ui.min.css');
        wp_enqueue_script('wbg-jquery-ui-js', WBG_PLUGIN_ASSETS_URI . '/plugins/jquery-ui-1.12.1.custom/jquery-ui.min.js');
    }


    /**
     * @param $key
     * @param $val
     * @return bool
     */
    public function updateOption($key, $val)
    {
        $opts = $this->getOptionsDB();
        if (!isset($opts[$key]) || $val !== $opts[$key]) {
            $this->setOption($key, $val);
            $this->updateOptionsDB();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * update class property
     * @param $opts
     */
    private function setOptions($opts)
    {
        $this->options = $opts;
    }

    /**
     *
     */
    public function setOptionsFromDB()
    {
        $this->setOptions($this->getOptionsDB());
    }

    /**
     *
     */
    public function configure()
    {
        wbgProject::setOpenProjects(wbgAdmin::getInstance()->getOption('open_projects', array()));
    }

    /**
     * @param $key
     * @param $val
     */
    public function setOption($key, $val)
    {
        $this->options[$key] = $val;
    }


    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function getOption($key, $default = NULL)
    {
        $opts = $this->getOptionsDB();
        if (isset($opts[$key])) {
            return $opts[$key];
        } else {
            //try get from config
            $settings = wbg_get_settings();
            if (isset($settings[$key]['default'])) {
                return $settings[$key]['default'];
            }
            return $default;
        }
    }

    /**
     * @return array|bool
     */
    public function getOptionsDB()
    {
        $opts = get_option(WBG_OPTS_KEY);
        if (($unserialized = @unserialize($opts)) === false || NULL === $unserialized) {
            return $this->resetOptions();
        } else {
            return $unserialized;
        }
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * validate and update settings from request
     * @return bool
     */
    public function updateOptions()
    {
        global $pagenow;
        //var_dump($pagenow);die;

        if (wbgAdmin::getFromRequest(WBG_PREFIX . WBG_SLUG . '_submit_r', 'wbg_reset_nonce', 'wbg_reset_nonce')) {
            $this->resetOptions();
            return;
        }

        $fields = $this->getControls();
        $success = TRUE;
        $atLeastOneOptionSubmitted = FALSE;
        $validated = array();
        foreach ($fields as $k => $v) {
            if (NULL !== self::getFromRequest($k, FALSE, FALSE)) {
                $val = self::getFromRequest($k, 'wbg_options_nonce', 'wbg_options_nonce');
                $atLeastOneOptionSubmitted = TRUE;
                //sanitize and validate
                $v['sanitizeCallback'] = apply_filters(WBG_PREFIX . WBG_SLUG . 'sanitize_callback', $v['sanitizeCallback'], $val, $k);
                $validateResult = $v['sanitizeCallback'] !== FALSE ? $v['sanitizeCallback']($val, $v['validateArgs']) : TRUE;
                if (false === ($validateResult [0])) {//validator callback
                    $success = FALSE;
                    wbgAlerts::getInstance()->addAlertSimple(
                        '<span class="wbg-validate-alert">[' . esc_html__('Field: ', 'wbg_plugin') . '<span class="wbg-validate-alert-field">' . $v['name'] . '</span>]</span> ' . $validateResult[1], 'warning', FALSE, TRUE, '', '', '');
                    continue;
                } else {
                    $validated[$k] = $val;
                }
            } elseif (isset($v['type']) && 'checkbox' === $v['type']) {
                $validated[$k] = WBG_CHECKBOX_UNCHECKED;
            }
        }
        if (empty($validated)) {
            return false;
        }
        if ($atLeastOneOptionSubmitted){
            $updatedOpts = array_merge($this->getOptionsDB(), $validated);
            $this->setOptions($updatedOpts);
            $this->updateOptionsDB();
        }
        if ($success && $atLeastOneOptionSubmitted) {
            wbgAlerts::getInstance()->addAlertSimple(WBG_MSG_SUCCESS_SAVE_SETTINGS, 'success', FALSE, TRUE, '', '', '');
        }

        return TRUE;
    }

    /**
     *
     */
    public function updateOptionsDB()
    {
        if (current_user_can('administrator')) {
            update_option(WBG_OPTS_KEY, serialize($this->getOptions()));
        } else {
            wp_die(WBG_ADMIN_FAIL_MSG);
        }
    }

    /**
     * @return array
     */
    private function resetOptions()
    {
        $fields = $this->getControls();
        $newOpts = array();
        foreach ($fields as $k => $v) {
            $newOpts[$k] = $v['default'];
        }
        update_option(WBG_OPTS_KEY, serialize($newOpts));
        $this->setOptions($newOpts);
        return $newOpts;
    }


    /**
     * @var null
     */
    private $currentTab = null;

    /**
     *
     */
    private function setCurrentTab()
    {
        $this->currentTab = wbgAdmin::getFromRequest('tab', FALSE, FALSE) ? wbgAdmin::getFromRequest('tab', FALSE, FALSE) : 1;
    }

    /**
     * @return mixed|void
     */
    public function getCurrentTab()
    {
        if (null === $this->currentTab) {
            $this->setCurrentTab();
        }
        return apply_filters('wbg_admin_current_tab', $this->currentTab);
    }

    /**
     * @return string
     */
    private function getTabContentFilename()
    {
        return sprintf('adminTabContent%d.php', $this->getCurrentTab());
    }

    /**
     *
     */
    public function addTopMenuEntry()
    {
        add_menu_page('WBG Plugin', 'Banner Generator', 'manage_options', $this->settingsPageHandle, array($this, 'addSettingsPage'), 'dashicons-images-alt');
    }

    /**
     * @throws Exception
     */
    public function addSettingsPage()
    {
        $tab = $this->getCurrentTab();
        wbg_get_template('adminTabs.php', array('active_tab' => $tab, 'tab_names' => $this->getTabs(), 'settings_page_handle' => self::getInstance()->settingsPageHandle));
    }


    /**
     * @throws Exception
     */
    public function includeTabContent()
    {
        if (!$this->checkWoocommerce()) {
            wbgAlerts::alertsHtml();
            return;
        }
        $tab = $this->getCurrentTab();
        if (apply_filters('wbg_admin_custom_content_tab_' . $tab, false)) {
            do_action('wbg_admin_custom_tab_content_' . $tab);
        } else {
            wbg_get_template($this->getTabContentFilename(), array('wbgAdmin' => self::getInstance()));
        }
    }


    /**
     * Add project tabs
     * @param $tabs
     * @return array
     */
    public function getProjectTabs($tabs)
    {
        $openProjects = self::getInstance()->getOption('open_projects', array());
        $projectTabs = array();
        $i = 0;
        $tabsCount = count($tabs);
        foreach ($openProjects as $ID) {
            $project = new wbgProject($ID);
            if ($projectToOpen = apply_filters('wbg_tab_redirect_to_project', NULL)) {
                //open project from banners table
                if ($project->getID() === $projectToOpen) {
                    $currentRealTabID = $tabsCount + $i + 1;
                    wbg_js_redirect(self::getInstance()->settingsPageHandle, $currentRealTabID);
                }
            }

            $projectTabs[] = $project->getName();
            add_filter('wbg_tab_link_class_' . ($tabsCount + $i), function ($class) {
                return $class . ' wbg-project-tab';
            });//add class to project tab
            add_action('wbg_tab_link_inside_' . ($tabsCount + $i), function () use ($ID) {
                echo '<button name="' . esc_attr(WBG_SUBMIT_CLOSE_PROJECT) . '_' . esc_attr($ID) . '">X</button>';
                wp_nonce_field('wbg_nonce_close', 'wbg_nonce_close');
            });//add close button to project tabs
            add_filter('wbg_admin_custom_content_tab_' . ($tabsCount + $i + 1), '__return_true');//use custom template for tab
            //add project content general template
            add_action('wbg_admin_custom_tab_content_' . ($tabsCount + $i + 1), function () use ($project) {//include custom template
                $project::setActiveProjectInstance($project);
                $project->configureActiveEffect();
                if ($project->hasEffect()) {
                    $project->getEffectInstance()->handle();//handle generate banner request
                }
                do_action('wbg_active_project', $project);//opened project hook
                add_action('wbg_project_content', function () use ($project) {//include effect content

                    if (NULL !== $project->getEffectInstance()) {
                        //$project->getEffectInstance()->handle();//handle effect code
                        $project->getEffectInstance()->loadTemplate();//include selected effect template
                    } else {
                        wbg_get_template('adminProjectNoeffect.php', array());//load empty effect template
                    }
                }, 1);
                wbg_get_template('adminProject.php', array('project' => $project, 'effects' => $this->getEffects()));
            });


            $i++;
        }
        return array_merge($tabs, $projectTabs);
    }


    /**
     *
     */
    public function loadEffects()
    {
        $directories = glob(WBG_EFFECTS_DIR . '/*', GLOB_ONLYDIR);
        foreach ($directories as $dir) {
            include_once($dir . DIRECTORY_SEPARATOR . wbg_get_effect_filename($dir));
            $className = wbg_get_effect_class_name($dir);
            if (class_exists($className)) {
                $obj = new $className();
                $this->setEffect($obj);
            }
        }
        //allow third party plugins to set own effects
        $this->setEffects(apply_filters('wbg_effects', $this->getEffects()));
    }

    /**
     *
     */
    public function getEffectContent()
    {

    }

    /**
     * @return array
     */
    public function getEffects()
    {
        return $this->effects;
    }

    /**
     * @param array $effects
     */
    public function setEffects($effects)
    {
        $this->effects = $effects;
    }


    /**
     * @param $object
     */
    public function setEffect($object)
    {
        $e = $this->getEffects();
        $e[] = $object;
        $this->setEffects($e);
    }

    /**
     * @return string
     */
    public function getSettingsPageHandle()
    {
        return $this->settingsPageHandle;
    }

    /**
     *
     */
    public function includeAlerts()
    {
        if (wbgAlerts::hasAlert()) {
            wbg_get_template('adminAlert.php');
        }

    }

    /**
     * @param string $settingsPageHandle
     */
    private function setSettingsPageHandle($settingsPageHandle)
    {
        $this->settingsPageHandle = $settingsPageHandle;
    }

    /**
     * @param $key
     * @return null
     */
    public static function getFromRequest($key, $nonceAction, $nonceField)
    {
        if (isset($_GET[$key])) {
            if ($nonceAction !== FALSE && $nonceField !== FALSE) {
                if (!check_admin_referer($nonceAction, $nonceField)) {
                    wp_die(WBG_SECURITY_FAIL_MSG);
                }
            }
            return sanitize_text_field($_GET[$key]);
        }

        if (isset($_POST[$key])) {
            if ($nonceAction !== FALSE && $nonceField !== FALSE) {
                if (!check_admin_referer($nonceAction, $nonceField)) {
                    wp_die(WBG_SECURITY_FAIL_MSG);
                }
            }
            return sanitize_text_field($_POST[$key]);
        }
        return NULL;
    }


    /**
     * @param array $fields
     * @param bool $allowEmptyStrings
     * @return bool
     */
    public static function validateRequest($fields = array(), $allowEmptyStrings = TRUE)
    {
        foreach ($fields as $field) {
            $value = self::getFromRequest($field);
            if (NULL === self::getFromRequest($field)) {
                return FALSE;
            }
            if (!$allowEmptyStrings && '' === $value) return FALSE;
        }
        return TRUE;
    }

    /**
     * @param $value
     * @return bool
     */
    public static function isCheckboxChecked($value)
    {
        return WBG_CHECKBOX_CHECKED === $value;
    }

}

wbgAdmin::getInstance();