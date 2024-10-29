<?php

/**
 * @package banner-generator-for-woocommerce
 */
class wbgWooGridEffect implements wbgEffectInterface
{
    /**
     *
     */
    const SUBMIT_SAVE = 'woo_grid_effect_save';

    /**
     *
     */
    const EFFECT_NAME = 'Woocommerce Grid';
    /**
     *
     */
    const EFFECT_VER = '1.0.0';
    /**
     *
     */
    const EFFECT_AUTHOR = 'Patrycjusz Marciniak';
    /**
     *
     */
    const EFFECT_DESCRIPTION = 'Opis';
    /**
     *
     */
    const EFFECT_ID = 'woo_grid_effect';

    /**
     *
     */
    const DEFAULT_LIMIT = 10;
    /**
     *
     */
    const DEFAULT_GRID_X = 3;
    /**
     *
     */
    const DEFAULT_GRID_Y = 1;
    /**
     *
     */
    const DEFAULT_PADDING = 3;
    /**
     *
     */
    const DEFAULT_BG_COLOR = '#00000';
    /**
     *
     */
    const DEFAULT_FONT_SIZE = 12;
    /**
     *
     */
    const DEFAULT_FONT_COLOR = '#ffffff';
    /**
     *
     */
    const DEFAULT_TEXT_BG_COLOR = '#000000';
    /**
     *
     */
    const DEFAULT_BG_PADDING_H = 3;
    /**
     *
     */
    const DEFAULT_BG_PADDING_V = 3;
    /**
     *
     */
    const DEFAULT_TEXT_POS_AUTO = 'center_bottom';
    /**
     *
     */
    const DEFAULT_FONT_SLUG = '';
    /**
     *
     */
    const DEFAULT_FRAMES_INTERVAL = 100;
    /**
     *
     */
    const DEFAULT_DIRECTION = 'right';
    /**
     *
     */
    const DEFAULT_RANDOM = FALSE;


    /**
     *
     */
    const DEFAULT_TEXT = '%name%';
    /**
     *
     */
    const LIMIT_INPUT_NAME = 'woo_grid_effect_limit';
    /**
     *
     */
    const RANDOM_INPUT_NAME = 'woo_grid_effect_random';
    /**
     *
     */
    const FRAMES_INTERVAL_INPUT_NAME = 'woo_grid_effect_interval';
    /**
     *
     */
    const GRID_X_INPUT_NAME = 'woo_grid_effect_gridx';
    /**
     *
     */
    const GRID_Y_INPUT_NAME = 'woo_grid_effect_gridy';
    /**
     *
     */
    const PADDING_INPUT_NAME = 'woo_grid_effect_padding';
    /**
     *
     */
    const BG_COLOR_INPUT_NAME = 'woo_grid_effect_bgcolor';
    /**
     *
     */
    const TEXT_INPUT_NAME = 'woo_grid_effect_text';
    /**
     *
     */
    const FONT_SIZE_INPUT_NAME = 'woo_grid_effect_fontsize';
    /**
     *
     */
    const TEXT_BG_COLOR_INPUT_NAME = 'woo_grid_effect_text_bg_color';
    /**
     *
     */
    const TEXT_BG_PADDING_H_INPUT_NAME = 'woo_grid_effect_bg_padding_h';
    /**
     *
     */
    const TEXT_BG_PADDING_V_INPUT_NAME = 'woo_grid_effect_bg_padding_v';
    /**
     *
     */
    const TEXT_POS_AUTO_INPUT_NAME = 'woo_grid_effect_text_pos_auto';
    /**
     *
     */
    const FONT_SLUG_INPUT_NAME = 'woo_grid_effect_font_slug';
    /**
     *
     */
    const FONT_COLOR_INPUT_NAME = 'woo_grid_effect_font_color';
    /**
     *
     */
    const DIRECTION_INPUT_NAME = 'woo_grid_effect_direction';
    /**
     *
     */
    const USER_IDS_INPUT_NAME = 'woo_grid_effect_ids';


    /**
     * @var null
     */
    private $maxTiles = NULL;
    /**
     * @var null
     */
    private $gridX = NULL;
    /**
     * @var null
     */
    private $gridY = NULL;
    /**
     * @var null
     */
    private $padding = NULL;
    /**
     * @var null
     */
    private $bgColor = NULL;

    /**
     * @var array
     */
    private $products = array();

    /**
     * @var
     */
    private $fontSize;
    /**
     * @var
     */
    private $textBgColor;
    /**
     * @var
     */
    private $textBgPaddingH;
    /**
     * @var
     */
    private $textBgPaddingV;
    /**
     * @var
     */
    private $textPosAuto;
    /**
     * @var
     */
    private $fontSlug;
    /**
     * @var
     */
    private $fontColor;
    /**
     * @var
     */
    private $framesInterval;

    /**
     * @var
     */
    private $direction;
    /**
     * @var
     */
    private $isRandom;

    /**
     * @var
     */
    private $userIds;

    /**
     * @var
     */
    private $text;


    /**
     * @var null
     */
    public static $path = NULL;


    /**
     *
     */
    public static function setPath()
    {
        if (NULL === self::$path) {
            self::$path = __DIR__;
        }
    }


    /**
     * wbgWooGridEffect constructor.
     */
    public function __construct()
    {
        self::setPath();
    }


    /**
     *
     */
    public function handle()
    {
        if (!$this->doSubmits()) {
            // only reload
            if (NULL === $this->getMaxTiles()) {//if sumbit is empty
                $limit = wbgProject::getActiveProjectInstance()->getEffectDataByKey('limit');
                $this->setMaxTiles($limit !== NULL ? $limit : self::DEFAULT_LIMIT);
            }
            if (NULL === $this->getFramesInterval()) {//if sumbit is empty
                $interval = wbgProject::getActiveProjectInstance()->getEffectDataByKey('frames_interval');
                $this->setFramesInterval($interval !== NULL ? $interval : self::DEFAULT_FRAMES_INTERVAL);
            }
            if (NULL === $this->getGridX()) {
                $gridX = wbgProject::getActiveProjectInstance()->getEffectDataByKey('grid_x');
                $this->setGridX($gridX !== NULL ? $gridX : self::DEFAULT_GRID_X);
            }
            if (NULL === $this->getGridY()) {
                $gridY = wbgProject::getActiveProjectInstance()->getEffectDataByKey('grid_y');
                $this->setGridY($gridY !== NULL ? $gridY : self::DEFAULT_GRID_Y);
            }
            if (NULL === $this->getPadding()) {
                $padding = wbgProject::getActiveProjectInstance()->getEffectDataByKey('padding');
                $this->setPadding($padding !== NULL ? $padding : self::DEFAULT_PADDING);
            }
            if (NULL === $this->getBgColor()) {
                $bgColor = wbgProject::getActiveProjectInstance()->getEffectDataByKey('bg_color');
                $this->setBgColor($bgColor !== NULL ? $bgColor : self::DEFAULT_BG_COLOR);
            }
            if (NULL === $this->getFontColor()) {
                $fontColor = wbgProject::getActiveProjectInstance()->getEffectDataByKey('font_color');
                $this->setFontColor($fontColor !== NULL ? $fontColor : self::DEFAULT_FONT_COLOR);
            }
            if (NULL === $this->getFontSize()) {
                $fsize = wbgProject::getActiveProjectInstance()->getEffectDataByKey('font_size');
                $this->setFontSize($fsize !== NULL ? $fsize : self::DEFAULT_FONT_SIZE);
            }
            if (NULL === $this->getTextBgColor()) {
                $c = wbgProject::getActiveProjectInstance()->getEffectDataByKey('text_bg_color');
                $this->setTextBgColor($c !== NULL ? $c : self::DEFAULT_TEXT_BG_COLOR);
            }
            if (NULL === $this->getTextBgPaddingH()) {
                $padding = wbgProject::getActiveProjectInstance()->getEffectDataByKey('bg_padding_h');
                $this->setTextBgPaddingH($padding !== NULL ? $padding : self::DEFAULT_BG_PADDING_H);
            }
            if (NULL === $this->getTextBgPaddingV()) {
                $padding = wbgProject::getActiveProjectInstance()->getEffectDataByKey('bg_padding_v');
                $this->setTextBgPaddingV($padding !== NULL ? $padding : self::DEFAULT_BG_PADDING_V);
            }
            if (NULL === $this->getTextPosAuto()) {
                $textPostAuto = wbgProject::getActiveProjectInstance()->getEffectDataByKey('text_pos_auto');
                $this->setTextPosAuto($textPostAuto !== NULL ? $textPostAuto : self::DEFAULT_TEXT_POS_AUTO);
            }
            if (NULL === $this->getFontSlug()) {
                $fpath = wbgProject::getActiveProjectInstance()->getEffectDataByKey('font_slug');
                $this->setFontSlug($fpath !== NULL ? $fpath : self::DEFAULT_FONT_SLUG);
            }
            if (NULL === $this->getDirection()) {
                $direction = wbgProject::getActiveProjectInstance()->getEffectDataByKey('direction');
                $this->setDirection($direction !== NULL ? $direction : self::DEFAULT_DIRECTION);
            }
            if (NULL === $this->getUserIds()) {
                $userIds = wbgProject::getActiveProjectInstance()->getEffectDataByKey('user_ids');
                $this->setUserIds($userIds !== NULL ? $userIds : array());
            }
            if (NULL === $this->getText()) {
                $text = wbgProject::getActiveProjectInstance()->getEffectDataByKey('text');
                $this->setText($text !== NULL ? $text : self::DEFAULT_TEXT);
            }
        }

    }

    /**
     * @return bool
     */
    private function doSubmits()
    {
        $nonce = 'wbg_nonce_banner_edit';
        if (!wbgAdmin::getFromRequest(self::SUBMIT_SAVE,$nonce, $nonce) && !wbgAdmin::getFromRequest(SUBMIT_GENERATE,$nonce, $nonce) && !wbgAdmin::getFromRequest(SUBMIT_GENERATE_FIRST_FRAME,$nonce, $nonce)) {
            return FALSE;//no requests
        }

        $validated = TRUE;
        $limit = wbgAdmin::getFromRequest(self::LIMIT_INPUT_NAME,$nonce, $nonce);
        if (!(is_numeric($limit) && $limit > 0 && $limit <= 200)) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('The value of "Limit" field is incorrect! Please enter a numeric value (1 to 200)', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $limit = NULL;
            $validated = FALSE;
        } else {
            $this->setMaxTiles($limit);
        }


        $interval = (int)wbgAdmin::getFromRequest(self::FRAMES_INTERVAL_INPUT_NAME, $nonce, $nonce);
        if (!(($interval) && $interval > 0 && $interval < 10000)) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('The value of "Frame interval" field is incorrect!Please enter a numeric value (1 to 10000)', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $interval = NULL;
            $validated = FALSE;
        } else {
            $this->setFramesInterval($interval);
        }

        $gridX = (int)wbgAdmin::getFromRequest(self::GRID_X_INPUT_NAME,$nonce, $nonce);
        if (!($gridX > 0 && $gridX <= 10)) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('The value of "Grid X" field is incorrect! 
Please enter a numeric value (1 to 10)', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $gridX = NULL;
            $validated = FALSE;
        } else {
            $this->setGridX($gridX);
        }


        $gridY = (int)wbgAdmin::getFromRequest(self::GRID_Y_INPUT_NAME,$nonce, $nonce);
        if (!($gridY > 0 && $gridY <= 10)) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('The value of "Grid Y" field is incorrect! 
Please enter a numeric value (1 to 10)', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $gridY = NULL;
            $validated = FALSE;
        } else {
            $this->setGridY($gridY);
        }

        $padding = (int)wbgAdmin::getFromRequest(self::PADDING_INPUT_NAME,$nonce, $nonce);
        if (!($padding > -1 && $padding < 100)) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('The value of "Padding" field is incorrect! 
Please enter a numeric value (1 to 100)', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $padding = NULL;
            $validated = FALSE;
        } else {
            $this->setPadding($padding);
        }

        $bgColor = wbgAdmin::getFromRequest(self::BG_COLOR_INPUT_NAME,$nonce, $nonce);
        if (!(wbg_check_valid_colorhex($bgColor))) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('The value of "Background color" field is incorrect! 
Please enter a hex color value', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $bgColor = NULL;
            $validated = FALSE;
        } else {
            $this->setBgColor($bgColor);
        }

        $fontSize = (int)wbgAdmin::getFromRequest(self::FONT_SIZE_INPUT_NAME,$nonce, $nonce);
        if (!($fontSize > 1 && $fontSize < 60)) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('The value of "Font size" field is incorrect! 
Please enter a numeric value (1 to 60)', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $fontSize = NULL;
            $validated = FALSE;
        } else {
            $this->setFontSize($fontSize);
        }

        $textBgColor = wbgAdmin::getFromRequest(self::TEXT_BG_COLOR_INPUT_NAME,$nonce, $nonce);
        if (!(wbg_check_valid_colorhex($textBgColor))) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('The value of "Text background color" field is incorrect! 
Please enter a hex color value', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $textBgColor = NULL;
            $validated = FALSE;
        } else {
            $this->setTextBgColor($textBgColor);
        }


        $bgPaddingH = (int)wbgAdmin::getFromRequest(self::TEXT_BG_PADDING_H_INPUT_NAME,$nonce, $nonce);
        if (!($bgPaddingH >= 1 && $bgPaddingH <= 100)) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('The value of "Background text padding horizontal" field is incorrect! 
Please enter a numeric value (1 to 30)', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $bgPaddingH = NULL;
            $validated = FALSE;
        } else {
            $this->setTextBgPaddingH($bgPaddingH);
        }

        $bgPaddingV = (int)wbgAdmin::getFromRequest(self::TEXT_BG_PADDING_V_INPUT_NAME,$nonce, $nonce);
        if (!($bgPaddingV >= 1 && $bgPaddingV <= 100)) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('The value of "Background text padding vertical" field is incorrect! 
Please enter a numeric value (1 to 30)', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $bgPaddingV = NULL;
            $validated = FALSE;
        } else {
            $this->setTextBgPaddingV($bgPaddingV);
        }

        $textPosAuto = wbgAdmin::getFromRequest(self::TEXT_POS_AUTO_INPUT_NAME,$nonce, $nonce);
        if (!(is_string($textPosAuto) && strlen($textPosAuto) <= 30)) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('The value of "Text position auto" field is incorrect!', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $textPosAuto = NULL;
            $validated = FALSE;
        } else {
            $this->setTextPosAuto($textPosAuto);
        }


        $fontSlug = wbgAdmin::getFromRequest(self::FONT_SLUG_INPUT_NAME,$nonce, $nonce);
        if (!(is_string($fontSlug))) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('The value of "Font path" field is incorrect!', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $fontSlug = NULL;
            $validated = FALSE;
        } else {
            $this->setFontSlug($fontSlug);
        }

        $fontColor = wbgAdmin::getFromRequest(self::FONT_COLOR_INPUT_NAME,$nonce, $nonce);
        if (!(wbg_check_valid_colorhex($fontColor))) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('The value of "Font color" field is incorrect! 
Please enter a hex color value', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $fontColor = NULL;
            $validated = FALSE;
        } else {
            $this->setFontColor($fontColor);
        }

        $direction = wbgAdmin::getFromRequest(self::DIRECTION_INPUT_NAME,$nonce, $nonce);
        if (!is_string($direction) || !('left' === $direction || 'right' === $direction)) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('The value of "Direction" field is incorrect!', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $direction = NULL;
            $validated = FALSE;
        } else {
            $this->setDirection($direction);
        }

        $userIds = wbgAdmin::getFromRequest(self::USER_IDS_INPUT_NAME,$nonce, $nonce);
        $userIds = json_decode($userIds);
        if (!('true' === wbgAdmin::getFromRequest(self::RANDOM_INPUT_NAME,$nonce, $nonce) || ($userIds && count($userIds) > 1))) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('Please select at least two products', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $userIds = array();
            $validated = FALSE;
        } else {
            $this->setUserIds($userIds);
        }



        $text = esc_sql(wbgAdmin::getFromRequest(self::TEXT_INPUT_NAME,$nonce, $nonce));
        if (!(is_string($text))) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('Field "text" must be a string', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            $text = NULL;
            $validated = FALSE;
        } else {
            $this->setText($text);
        }


        if (wbgAdmin::getFromRequest(self::SUBMIT_SAVE,$nonce, $nonce)) {
            if (!$validated) {
                wbgAlerts::getInstance()->addAlertSimple(esc_html__('Settings has not saved!', 'wbg_plugin'), 'danger', FALSE, TRUE, '', '', '');
                return FALSE;
            }
        }

        /*
         * save effect data to project
         */
        $limit = wbgAdmin::getFromRequest(self::LIMIT_INPUT_NAME,$nonce, $nonce);
        $this->setMaxTiles($limit);
        wbgProject::getActiveProjectInstance()->updateEffectData('limit', $limit);//update limit
        wbgProject::getActiveProjectInstance()->updateEffectData('frames_interval', $interval);//update frames interval
        wbgProject::getActiveProjectInstance()->updateEffectData('grid_x', $gridX);//update gridX
        wbgProject::getActiveProjectInstance()->updateEffectData('grid_y', $gridY);//update gridY
        wbgProject::getActiveProjectInstance()->updateEffectData('padding', $padding);//update padding
        wbgProject::getActiveProjectInstance()->updateEffectData('bg_color', $bgColor);//update bg color
        wbgProject::getActiveProjectInstance()->updateEffectData('font_size', $fontSize);//update font size
        wbgProject::getActiveProjectInstance()->updateEffectData('text_bg_color', $textBgColor);//update text bg color
        wbgProject::getActiveProjectInstance()->updateEffectData('bg_padding_h', $bgPaddingH);//update text bg padding
        wbgProject::getActiveProjectInstance()->updateEffectData('bg_padding_v', $bgPaddingV);//update text bg padding
        wbgProject::getActiveProjectInstance()->updateEffectData('text_pos_auto', $textPosAuto);//update text position template
        wbgProject::getActiveProjectInstance()->updateEffectData('font_slug', $fontSlug);//update text position template
        wbgProject::getActiveProjectInstance()->updateEffectData('font_color', $fontColor);//update font color
        wbgProject::getActiveProjectInstance()->updateEffectData('direction', $direction);//update direction
        wbgProject::getActiveProjectInstance()->updateEffectData('user_ids', $userIds);//update direction
        wbgProject::getActiveProjectInstance()->updateEffectData('text', $text);//update direction
        wbgProject::getActiveProjectInstance()->updateProjectDB();
        if (wbgAdmin::getFromRequest(self::SUBMIT_SAVE,$nonce, $nonce)){
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('Effect properties has been saved correctly', 'wbg_plugin'), 'success', FALSE, TRUE, '', '', '');
        }



        /*
         * generate banner
         */
        $firstFrame = FALSE;
        if (wbgAdmin::getFromRequest(SUBMIT_GENERATE,$nonce, $nonce) || $firstFrame = wbgAdmin::getFromRequest(SUBMIT_GENERATE_FIRST_FRAME,$nonce, $nonce)) {
            if (!$validated) {
                wbgAlerts::getInstance()->addAlertSimple(esc_html__('Can\'t generate banner!', 'wbg_plugin'), 'danger', FALSE, TRUE, '', '', '');
                return FALSE;
            }
            if (!wbgAdmin::getFromRequest(self::RANDOM_INPUT_NAME,$nonce, $nonce)) {
                $this->setFramesInterval($interval);
                $this->setGridX($gridX);
                $this->setGridY($gridY);
                $this->setPadding($padding);
                $this->setBgColor($bgColor);
                $this->setFontSize($fontSize);
                $this->setTextBgColor($textBgColor);
                $this->setTextBgPaddingH($bgPaddingH);
                $this->setTextBgPaddingV($bgPaddingV);
                $this->setTextPosAuto($textPosAuto);
                $this->setFontSlug($fontSlug);
                $this->setFontColor($fontColor);
                $this->setDirection($direction);
                $this->setUserIds($userIds);
                $this->setProducts(wbgWoocommerce::getProductsByIDs($userIds));
                $this->setMaxTiles(count($userIds));
                $this->setText($text);
                $this->generate($firstFrame ? TRUE : FALSE);
            } else {
                $positions = array('bottom_right', 'bottom_left', 'top_right', 'top_left', 'center_bottom', 'center_top', 'center');
                $fonts = wbg_get_fonts_instance()->getSlugs();
                $this->setMaxTiles($limit);
                $this->setFramesInterval(rand(10, 100));
                $this->setGridX($x = rand(1, 6));
                $this->setGridY(rand(1, 6 - $x));
                $this->setPadding(rand(1, 15));
                $this->setBgColor(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
                $this->setFontSize(rand(6, 12));
                $this->setTextBgColor(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
                $this->setTextBgPaddingH(rand(1, 50));
                $this->setTextBgPaddingV(rand(1, 10));
                $this->setTextPosAuto($positions[rand(0, 6)]);
                $this->setFontSlug($fonts[rand(0, count($fonts) - 1)]);
                $this->setFontColor(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
                $this->setDirection(array('left', 'right')[rand(0, 1)]);
                $this->setText($text);
                $this->setProducts(wbgWoocommerce::getRandomProducts($limit));
                $this->setUserIds($this->getIuserIdsByProducts($this->getProducts()));
                wbgProject::getActiveProjectInstance()->setWidth(100 * $this->getGridX());
                wbgProject::getActiveProjectInstance()->setHeight(100 * $this->getGridY());
                wbgProject::getActiveProjectInstance()->updateProjectDB();
                $this->generate($firstFrame ? TRUE : FALSE);
            }
            return TRUE;
        }
    }

    /**
     * @param $products
     * @return array
     */
    private function getIuserIdsByProducts($products)
    {
        $ids = array();
        foreach ($products as $p) {
            $ids[] = $p->get_id();
        }
        return $ids;
    }

    /**
     * @param bool $outputOnlyFirstFrame
     */
    private function generate($outputOnlyFirstFrame = FALSE)
    {

        //init manager
        $manager = new wbgProjectToGifManager(wbgProject::getActiveProjectInstance(), $this);

        //get animator
        $animator = $manager->getAnimatorInstance();

        if ($outputOnlyFirstFrame) {
            $totalFrames = 1;
            $manager->setOutputOnlyFirstFrame(TRUE);
        } else {
            $totalFrames = $animator->getTotalFrames();
        }

        $products = $this->getProducts();
        $numberOfCells = $this->getGridX() * $this->getGridY();
        //generate frames
        if ($totalFrames <= 0) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('Please increase the number of products, or reduce the number of cells (x/y)', 'wbg_plugin'), 'danger', FALSE, TRUE, '', '', '');
            return;
        }

        for ($i = 0; $i < $totalFrames; $i++) {//frames loop
            $animator->setInterval($this->getFramesInterval());
            for ($j = 0; $j < $numberOfCells; $j++) {//cell loop
                if (!isset($products[$j + $i])) {
                    break;
                }
                /**
                 * @var $product WC_Product
                 */
                $product = $products[$j + $i];//move products to right for each frame
                if (!$product->get_image()) {
                    continue;
                }
                $src = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'medium');
                $animator->setImageSrc($src[0]);
                $animator->setfontFile(wbg_get_font_path_by_slug($this->getFontSlug()));
                $animator->setFontColor($this->getFontColor());
                $animator->setFontSize($this->getFontSize());
                $animator->setTextBackgroundColor($this->getTextBgColor());
                $animator->setText(wbgWoocommerce::prepareProductLabel($product, $this->getText()));
                $animator->setTextBackgroundPaddingHorizontal($this->getTextBgPaddingH());
                $animator->setTextBackgroundPaddingVertical($this->getTextBgPaddingV());
                $animator->setTextPosAuto($this->getTextPosAuto());
                $animator->mextCell();
            }
            $animator->nextFrame();
        }

        $manager->generateGif();


    }


    /**
     * @return array
     */
    private function getProducts()
    {
        return $this->products;
    }


    /**
     * @return string
     */
    public
    function getName()
    {
        return self::EFFECT_NAME;
    }

    /**
     * @return string
     */
    public function getID()
    {
        return self::EFFECT_ID;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return esc_html__('This built-in effect creates a grid-based animation products', 'wbg_plugin');
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return self::EFFECT_VER;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return self::EFFECT_AUTHOR;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return WBG_EFFECTS_URI . '/wooGrid/assets/images/thumb.jpg';
    }

    /**
     *
     */
    public
    function loadTemplate()
    {
        $products = wbgWoocommerce::getProducts();
        if (empty($products)){
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('You don\'t have any products in your woocommerce store. Please add some products to create banners.', 'wbg_plugin'), 'danger', FALSE, TRUE, '', '', '');
            return;
        }
        wbg_get_template(self::$path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'wbgWooGridEffectAdmin.php',
            array('effect' => $this, 'products' => $products, 'userProducts' => wbgWoocommerce::getProductsByIDs($this->getUserIds()), 'ids' => $this->getUserIds()), TRUE);
    }

    /**
     * @return null
     */
    public function getMaxTiles()
    {
        return $this->maxTiles;
    }

    /**
     * @param null $maxTiles
     */
    public function setMaxTiles($maxTiles)
    {
        $this->maxTiles = $maxTiles;
    }


    /**
     * @return null
     */
    public function getGridX()
    {
        return $this->gridX;
    }

    /**
     * @param null $gridX
     */
    public function setGridX($gridX)
    {
        $this->gridX = $gridX;
    }

    /**
     * @return null
     */
    public function getGridY()
    {
        return $this->gridY;
    }

    /**
     * @param null $gridY
     */
    public function setGridY($gridY)
    {
        $this->gridY = $gridY;
    }

    /**
     * @return null
     */
    public function getPadding()
    {
        return $this->padding;
    }

    /**
     * @param null $padding
     */
    public function setPadding($padding)
    {
        $this->padding = $padding;
    }

    /**
     * @return null
     */
    public function getBgColor()
    {
        return $this->bgColor;
    }

    /**
     * @param null $bgColor
     */
    public function setBgColor($bgColor)
    {
        $this->bgColor = $bgColor;
    }


    /**
     * @param array $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @return mixed
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * @param mixed $fontSize
     */
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
    }

    /**
     * @return mixed
     */
    public function getTextBgColor()
    {
        return $this->textBgColor;
    }

    /**
     * @param mixed $textBgColor
     */
    public function setTextBgColor($textBgColor)
    {
        $this->textBgColor = $textBgColor;
    }

    /**
     * @return mixed
     */
    public function getTextBgPaddingH()
    {
        return $this->textBgPaddingH;
    }

    /**
     * @param mixed $textBgPaddingH
     */
    public function setTextBgPaddingH($textBgPaddingH)
    {
        $this->textBgPaddingH = $textBgPaddingH;
    }

    /**
     * @return mixed
     */
    public function getTextBgPaddingV()
    {
        return $this->textBgPaddingV;
    }

    /**
     * @param mixed $textBgPaddingV
     */
    public function setTextBgPaddingV($textBgPaddingV)
    {
        $this->textBgPaddingV = $textBgPaddingV;
    }

    /**
     * @return mixed
     */
    public function getTextPosAuto()
    {
        return $this->textPosAuto;
    }

    /**
     * @param mixed $textPosAuto
     */
    public function setTextPosAuto($textPosAuto)
    {
        $this->textPosAuto = $textPosAuto;
    }

    /**
     * @return mixed
     */
    public function getFontSlug()
    {
        return $this->fontSlug;
    }

    /**
     * @param mixed $fontSlug
     */
    public function setFontSlug($fontSlug)
    {
        $this->fontSlug = $fontSlug;
    }

    /**
     * @return mixed
     */
    public function getFontColor()
    {
        return $this->fontColor;
    }

    /**
     * @param mixed $fontColor
     */
    public function setFontColor($fontColor)
    {
        $this->fontColor = $fontColor;
    }

    /**
     * @return mixed
     */
    public function getFramesInterval()
    {
        return $this->framesInterval;
    }

    /**
     * @param mixed $framesInterval
     */
    public function setFramesInterval($framesInterval)
    {
        $this->framesInterval = $framesInterval;
    }

    /**
     * @return mixed
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param mixed $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    /**
     * @return bool
     */
    public function getIsRandom()
    {
        return $this->isRandom;
    }

    /**
     * @param mixed $isRandom
     */
    public function setIsRandom($isRandom)
    {
        $this->isRandom = $isRandom;
    }

    /**
     * @return mixed
     */
    public function getUserIds()
    {
        return $this->userIds;
    }

    /**
     * @param mixed $userIds
     */
    public function setUserIds($userIds)
    {
        $this->userIds = $userIds;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }





}

