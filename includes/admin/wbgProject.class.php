<?php

/**
 * @package banner-generator-for-woocommerce
 */
class wbgProject
{

    /**
     * @var array
     */
    static $openProjects = array();


    /**
     * @var null
     */
    private static $activeProject = NULL;
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $name;

    /**
     * @var
     */
    private $slug;
    /**
     * @var
     */
    private $data;
    /**
     * @var
     */
    private $dateCreated;

    /**
     * @var string
     */
    private $effect = '';


    /**
     * @var string
     */
    private $width = '600';
    /**
     * @var string
     */
    private $height = '200';
    /**
     * @var string
     */
    private $gridX = '5';
    /**
     * @var string
     */
    private $gridY = '1';
    /**
     * @var string
     */
    private $padding = '0';
    /**
     * @var string
     */
    private $bgColor = '#ffffff';


    /**
     * @var
     */
    private $outputDir;

    /**
     * @var
     */
    private $gifPath;
    /**
     * @var
     */
    private $thumbPath;
    /**
     * @var
     */
    private $gifSrc;
    /**
     * @var
     */
    private $thumbSrc;
    /**
     * @var null
     */
    public $effectInstance = NULL;

    /**
     * @var
     */
    private $isOpen;

    /**
     * @var
     */
    private $alt;

    /**
     * @return wbgProject
     */
    public static function getActiveProjectInstance()
    {
        return self::$activeProject;
    }

    /**
     * @param $obj
     */
    public static function setActiveProjectInstance($obj)
    {
        self::$activeProject = $obj;
    }


    /**
     * @return mixed|null
     */
    public static function getAllProjectsDB()
    {
        return wbgAdmin::getInstance()->getOption('projects');
    }


    /**
     * @return wbgProject[]
     */
    public static function getProjectModelCollection($pagination = false)
    {
        $arr = wbgAdmin::getInstance()->getOption('projects');
        $countP = count($arr);
        add_filter('wbg_total_projects', function () use ($countP) {
            return $countP;
        });
        if (!$arr) return array();
        $collection = array_map(function ($arr) {
            return new wbgProject($arr['ID']);
        }, $arr);

        if ($pagination) {
            return self::applyPaginationFilterToProjects($collection);
        }
        return $collection;

    }


    /**
     * @param $projectsCollection
     * @return array
     */
    private static function applyPaginationFilterToProjects($projectsCollection)
    {
        $currentPage = !($currentPage = apply_filters('wbg_projects_page', NULL)) ? 1 : (int)$currentPage;
        $projectsPerPage = (int)wbgAdmin::getInstance()->getOption('projects_per_page');
        $chunks = array_chunk($projectsCollection, $projectsPerPage);
        add_filter('wbg_pages_total', function () use ($chunks) {
            return count($chunks);
        });

        if (1 === count($chunks)) {
            return $projectsCollection;
        }


        if (!isset($chunks[$currentPage - 1]) || $currentPage === 0) {
            wbgAlerts::getInstance()->addAlertSimple(sprintf(esc_html__('Page %d not found!', 'wbg_plugin'), $currentPage), 'warning', FALSE, TRUE, '', '', '');
            return array();
        }

        return $chunks[1 === $currentPage ? 0 : $currentPage - 1];
    }

    /*
     * Project tabs support START
     */
    /**
     * @param $ids
     */
    public static function setOpenProjects($ids)
    {
        self::$openProjects = $ids;
    }

    /**
     * @return array
     */
    public static function getOpenProjects()
    {
        return self::$openProjects;
    }

    /**
     *
     */
    public static function closeAllProjects()
    {
        self::setOpenProjects(array());
    }


    /**
     *
     */
    public static function closeProject()
    {

        //close project tab
        $ID = NULL;
        foreach ($_POST as $key => $value) {
            $key = sanitize_text_field($key);
            if (strpos($key, WBG_SUBMIT_CLOSE_PROJECT) === 0) {
                if (!check_admin_referer('wbg_nonce_close', 'wbg_nonce_close')){
                    wp_die(WBG_SECURITY_FAIL_MSG);
                }
                $ID = str_replace(WBG_SUBMIT_CLOSE_PROJECT . '_', '', $key);
                add_filter('wbg_admin_current_tab', function ($tab) {
                    return 1;
                });
                break;
            }
        }
        self::closeProjectDB($ID);
    }


    /**
     * @param $ID
     */
    public static function closeProjectDB($ID)
    {
        if (NULL === $ID) {
            return;
        }
        //remove item by value
        $activeProjects = wbgAdmin::getInstance()->getOption('open_projects', array());
        foreach ($activeProjects as $k => $v) {

            if ($v === $ID) {

                unset($activeProjects[$k]);
            }
        }
        self::setOpenProjects($activeProjects);
        wbgAdmin::getInstance()->setOption('open_projects', $activeProjects);
        wbgAdmin::getInstance()->updateOptionsDB();
    }

    /**
     * @param $id
     */
    public static function openProjectOnTab($id)
    {
        $activeProjects = self::getOpenProjects();
        if (in_array($id, $activeProjects)) {
            return;
        }
        $activeProjects[] = $id;
        self::setOpenProjects($activeProjects);


        if (WBG_CHECKBOX_CHECKED !== wbgAdmin::getInstance()->getOption('open_projects_in_background')) {
            //open project from banners table directly
            add_filter('wbg_tab_redirect_to_project', function () use ($id) {
                return $id;
            });
        }


        $fromOptions = wbgAdmin::getInstance()->getOption('open_projects', array());
        $fromOptions[] = $id;
        wbgAdmin::getInstance()->setOption('open_projects', $fromOptions);
        wbgAdmin::getInstance()->updateOptionsDB();
    }


    /**
     * @param $projectID
     */
    public static function renameProject($projectID, $newName)
    {
        if (!$project = new self($projectID)) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('Banner: ') . $projectID . esc_html__('Does\'t exist'), 'warning', FALSE, TRUE, '', '', '');
            return;
        }
        $project->setName($newName);
        $project->updateProjectDB();
        wbgAlerts::getInstance()->addAlertSimple(esc_html__('The name was changed correctly'), 'success', FALSE, TRUE, '', '', '');
    }

    /*
    * Project tabs support END
    */





    /**
     * wbgProject constructor.
     * @param null $id
     * @param string $name
     */
    public function __construct($id = NULL, $name = '')
    {

        if (NULL === $id) {
            $this->init($name);
        } else {
            if (is_string($id)) {
                $this->load($id);
            }
        }
        do_action('wbg_project_' . $this->getID() . '_created', $this);
    }

    /**
     * @param $name
     */
    public function init($name)
    {
        $f = WBG_SANITIZE_STRING_FUNCTION;
        $validateResult = $f($name);
        if (false === ($validateResult [0])) {
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('Name contains invalid characters', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            return;
        }
        $this->setID($this->generateNewID());
        $this->setDateCreated(time());
        $this->setSlug($newSlugName = $this->getID());
        $this->setName($name);
        $this->setGifSrc(NULL);
        $this->setThumbSrc(NULL);
        $this->setGifPath(NULL);
        $this->setThumbPath(NULL);
        $this->setOutputDir(NULL);
        $this->setAlt(NULL);
        $this->setData(array('effects' => ''));
        $this->save(sprintf(esc_html__('Banner "%s" was created', 'wbg-plugin'), $this->getName()));
    }


    /**
     * @return string
     */
    private function generateNewID()
    {
        return 'project_' . md5(uniqid(rand(), true) . time());
    }

    /**
     * @return array
     */
    private function wrapProject()
    {
        return array(
            'ID' => $this->getID(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'gif_src' => $this->getGifSrc(),
            'thumb_src' => $this->getThumbSrc(),
            'output_directory' => $this->getOutputDir(),
            'gif_path' => $this->getGifPath(),
            'thumb_path' => $this->getThumbPath(),
            'date_created' => $this->getDateCreated(),
            'data' => $this->getData(),
            'effect' => $this->getEffect(),
            'alt' => $this->getAlt(),
            'banner_width' => $this->getWidth(),
            'banner_height' => $this->getHeight(),
            'banner_grid_x' => $this->getGridX(),
            'banner_grid_y' => $this->getGridY(),
            'banner_bg_color' => $this->getBgColor(),
            'banner_padding' => $this->getPadding(),
        );
    }


    /**
     * @param string $successMessage
     * @return bool
     */
    public function save($successMessage = WBG_MSG_SUCCESS_ADD_NEW_PROJECT)
    {
        if (NULL === $projects = wbgAdmin::getInstance()->getOption('projects')) {
            $projects = array();
        }
        $projects[] = $this->wrapProject();
        $operation = wbgAdmin::getInstance()->updateOption('projects', $projects);
        if ($operation) {
            wbgAlerts::getInstance()->addAlertSimple($successMessage, 'success', FALSE, TRUE, '', '', '');
            return TRUE;
        } else {
            return FALSE;
        }

    }


    /**
     * @param $id
     * @return bool
     */
    public function load($id)
    {
        $projects = wbgAdmin::getInstance()->getOption('projects');
        $project = array();
        $exist = false;
        foreach ($projects as $k => $v) {
            if ($id === $v['ID']) {
                $exist = TRUE;
                $project = $v;
                break;//project found break!
            }
        }
        if (FALSE === $exist) {
            return FALSE;
        }


        //detect if project tab is open
        if (in_array($id, self::getOpenProjects())) {
            $this->setIsOpen(TRUE);
        } else {
            $this->setIsOpen(FALSE);
        }

        $this->setID($project['ID']);
        $this->setDateCreated($project['date_created']);
        $this->setName($project['name']);
        $this->setData($project['data']);
        $this->setEffect($project['effect']);
        $this->setWidth($project['banner_width']);
        $this->setHeight($project['banner_height']);
        $this->setGridX($project['banner_grid_x']);
        $this->setGridY($project['banner_grid_y']);
        $this->setBgColor($project['banner_bg_color']);
        $this->setPadding($project['banner_padding']);
        $this->setSlug($project['slug']);
        $this->setGifSrc($project['gif_src']);
        $this->setThumbSrc($project['thumb_src']);
        $this->setOutputDir($project['output_directory']);
        $this->setGifPath($project['gif_path']);
        $this->setThumbPath($project['thumb_path']);
        $this->setAlt($project['alt']);

        return TRUE;
    }


    /**
     * Remove project from tab
     * @return bool
     */
    public function remove($id = NULL)
    {
        $id = !$id ? $this->getID() : $id;
        if (NULL === $projects = wbgAdmin::getInstance()->getOption('projects')) {
            wbgAlerts::getInstance()->addAlertSimple(WBG_MSG_SUCCESS_REMOVED_ERR, 'danger', FALSE, TRUE, '', '', '');
            return FALSE;
        }
        foreach ($projects as $k => $v) {
            if ($id === $v['ID']) {
                unset($projects[$k]);
                break;
            }
        }
        wbgAdmin::getInstance()->updateOption('projects', $projects);//update db
        self::closeProjectDB($id);//remove project from tabs
        if ($this->getOutputDir()) {//only if files exist
            $this->removeProjectFiles();//remove all files (gif, thumb)
        }
        wbgAlerts::getInstance()->addAlertSimple(WBG_MSG_SUCCESS_REMOVED, 'success', FALSE, TRUE, '', '', '');
        return TRUE;
    }


    /**
     * @param $id
     * @return bool
     */
    public static function cloneProject($id)
    {
        $obj = new self($id);
        $obj->setID($obj->generateNewID());
        $obj->setDateCreated(time());
        $newSlug = $obj->getID();
        $oldName = $obj->getName();//for clone msg
        $obj->setName(wbg_string_ends_with($obj->getName(), 'copy') ? $obj->getName() : $obj->getName() . ' copy');
        $obj->setSlug($newSlug);
        if ($obj->getGifSrc()) {//has gif?
            $oldGifPath = $obj->getGifPath();
            $oldThumbPath = $obj->getThumbPath();
            $obj->setGifPath($obj->createGifPathFromSlug($newSlug));
            $obj->setThumbPath($obj->createThumbPathFromSlug($newSlug));
            $obj->setGifSrc($obj->createGifSrcFromSlug($newSlug));
            $obj->setThumbSrc($obj->createThumbSrcFromSlug($newSlug));
            $obj->setOutputDir($obj->createOutputDirectoryPathFromSlug($newSlug));
            try {
                mkdir($obj->getOutputDir());//make a direcotry
                copy($oldGifPath, $obj->getGifPath());//copy banner
                copy($oldThumbPath, $obj->getThumbPath());// copy thumb
            } catch (Exception $e) {
                wbg_update_log_file($e->getMessage());
                wbgAlerts::getInstance()->addAlertSimple($e->getMessage(), 'warning', FALSE, TRUE, '', '', '');
                return false;
            }
        } else {
            $obj->setGifSrc(NULL);
            $obj->setThumbSrc(NULL);
            $obj->setOutputDir(NULL);
        }

        return $obj->save(sprintf(esc_html__('Project "%s" has been cloned', 'wbg-plugin'), $oldName));
    }


    /**
     * @param $slug
     * @return string
     */
    private function createOutputDirectoryPathFromSlug($slug)
    {
        return WBG_BANNERS_OUTPUT_DIRECTORY_PATH . DIRECTORY_SEPARATOR . $slug;
    }

    /**
     * @param $slug
     * @return string
     */
    private function createGifPathFromSlug($slug)
    {
        return WBG_BANNERS_OUTPUT_DIRECTORY_PATH . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . $slug . '.gif';
    }

    /**
     * @param $slug
     * @return string
     */
    private function createThumbPathFromSlug($slug)
    {
        return WBG_BANNERS_OUTPUT_DIRECTORY_PATH . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . 'thumb.gif';
    }

    /**
     * @param $slug
     * @return string
     */
    private function createThumbSrcFromSlug($slug)
    {
        return WBG_BANNERS_OUTPUT_URI . '/' . $slug . '/' . 'thumb.gif';
    }

    /**
     * @param $slug
     * @return string
     */
    private function createGifSrcFromSlug($slug)
    {
        return WBG_BANNERS_OUTPUT_URI . '/' . $slug . '/' . $slug . '.gif';
    }

    /**
     * @param $id
     * @return bool
     */
    public static function removeProject($id)
    {
        $obj = new self($id);
        return $obj->remove();
    }

    /**
     * @return string
     */
    public function getEffect()
    {
        return $this->effect;
    }

    /**
     * @param string $effect
     */
    public function setEffect($effect)
    {
        $this->effect = $effect;
    }

    /**
     * @return null
     */
    public function getEffectInstance()
    {
        return $this->effectInstance;
    }

    /**
     * @param null $effectInstance
     */
    public function setEffectInstance($effectInstance)
    {
        $this->effectInstance = $effectInstance;
    }


    /**
     *Updata single project
     */
    public function updateProjectDB()
    {
        $a = $projects = self::getAllProjectsDB();//get project from db
        foreach ($a as $k => $v) {
            if ($v['ID'] === $this->getID()) {
                $projects[$k] = $this->wrapProject();//update current project
                break;
            }
        }
        //save
        wbgAdmin::getInstance()->setOption('projects', $projects);
        wbgAdmin::getInstance()->updateOptionsDB();
    }


    /**
     * @param $key
     * @param $value
     */
    public function updateEffectData($key, $value)
    {
        $data = $this->getData();
        $data['effects'][$this->getEffect()][$key] = $value;
        $this->setData($data);
    }

    /**
     * @return array
     */
    public function getEffectData()
    {
        $data = $this->getData();
        if (!isset($data['effects'][$this->getEffect()])) {
            return array();
        }
        return $data[$this->getEffect()];
    }

    /**
     * @param $key
     * @return array|string
     */
    public function getEffectDataByKey($key)
    {
        $data = $this->getData();
        if (!isset($data['effects'][$this->getEffect()])) {
            return NULL;
        }
        $data = $data['effects'][$this->getEffect()];

        return isset($data[$key]) ? $data[$key] : '';
    }

    /**
     *
     */
    public function removeEffectSettings()
    {
        $data = $this->getData();
        if (isset($data['effects'][$this->getEffect()])) {
            unset($data['effects'][$this->getEffect()]);
        }
    }

    /**
     * @return bool
     */
    private function removeProjectFiles()
    {
        try {
            wbg_remove_dir_with_files($this->getOutputDir());
        } catch (Exception $e) {
            wbg_update_log_file($e->getMessage());
            wbgAlerts::getInstance()->addAlertSimple($e->getMessage(), 'warning', FALSE, TRUE, '', '', '');
            return false;
        }
    }

    /**
     * @return false|string
     */
    public function getDateCreatedYYYYMMDD()
    {
        return date("Y-m-d", $this->getDateCreated());
    }


    /**
     *
     */
    public function configureActiveEffect()
    {
        foreach (wbgAdmin::getInstance()->getEffects() as $effectObj) {//set current effect to project
            if ($this->getEffect() === $effectObj::EFFECT_ID) {
                $this->setEffectInstance($effectObj);
                break;
            }
        }
    }

    /**
     * @return bool
     */
    public function hasEffect()
    {
        return is_object($this->getEffectInstance());
    }


    /*
     * Getters and setters
     */

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    private function setID($id)
    {
        $this->id = $id;
    }

    /**
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param $name
     */
    private function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $timestamp
     */
    private function setDateCreated($timestamp)
    {
        $this->dateCreated = $timestamp;
    }

    /**
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param string $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param string $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return string
     */
    public function getGridX()
    {
        return $this->gridX;
    }

    /**
     * @param string $gridX
     */
    public function setGridX($gridX)
    {
        $this->gridX = $gridX;
    }

    /**
     * @return string
     */
    public function getGridY()
    {
        return $this->gridY;
    }

    /**
     * @param string $gridY
     */
    public function setGridY($gridY)
    {
        $this->gridY = $gridY;
    }

    /**
     * @return string
     */
    public function getPadding()
    {
        return $this->padding;
    }

    /**
     * @param string $padding
     */
    public function setPadding($padding)
    {
        $this->padding = $padding;
    }

    /**
     * @return string
     */
    public function getBgColor()
    {
        return $this->bgColor;
    }

    /**
     * @param string $bgColor
     */
    public function setBgColor($bgColor)
    {
        $this->bgColor = $bgColor;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getOutputDir()
    {
        return $this->outputDir;
    }

    /**
     * @param mixed $outputDir
     */
    public function setOutputDir($outputDir)
    {
        $this->outputDir = $outputDir;
    }

    /**
     * @return mixed
     */
    public function getGifSrc()
    {
        return $this->gifSrc;
    }

    /**
     * @param mixed $gifSrc
     */
    public function setGifSrc($gifSrc)
    {
        $this->gifSrc = $gifSrc;
    }


    /**
     * @return mixed
     */
    public function getThumbSrc()
    {
        return $this->thumbSrc;
    }

    /**
     * @param mixed $thumbSrc
     */
    public function setThumbSrc($thumbSrc)
    {
        $this->thumbSrc = $thumbSrc;
    }

    /**
     * @return mixed
     */
    public function getGifPath()
    {
        return $this->gifPath;
    }

    /**
     * @param mixed $gifPath
     */
    public function setGifPath($gifPath)
    {
        $this->gifPath = $gifPath;
    }

    /**
     * @return mixed
     */
    public function getThumbPath()
    {
        return $this->thumbPath;
    }

    /**
     * @param mixed $thumbPath
     */
    public function setThumbPath($thumbPath)
    {
        $this->thumbPath = $thumbPath;
    }

    /**
     * @return mixed
     */
    public function getIsOpen()
    {
        return $this->isOpen;
    }

    /**
     * @param mixed $isOpen
     */
    public function setIsOpen($isOpen)
    {
        $this->isOpen = $isOpen;
    }

    /**
     * @return mixed
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param mixed $alt
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    }


}