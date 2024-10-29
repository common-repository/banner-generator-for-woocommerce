<?php

/**
 * @package banner-generator-for-woocommerce
 */
class wbgJob
{

    /**
     *
     */
    const STATUS_CREATED = 1;
    /**
     *
     */
    const STATUS_FRAMES_COLLECTED = 5;
    /**
     *
     */
    const STATUS_ENCODER_EXECUTE = 7;
    /**
     *
     */
    const STATUS_ENCODER_GIF_CREATED = 8;
    /**
     *
     */
    const STATUS_GIF_SAVED = 15;


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
    private $status = '';

    /**
     * @var string
     */
    private $directory = '';

    /**
     * @var
     */
    private $outputDirectory;
    /**
     * @var
     */
    private $outputUri;

    /**
     * @var
     */
    private $gifPath;
    /**
     * @var
     */
    private $gifSrc;
    /**
     * @var
     */
    private $thumbPath;
    /**
     * @var
     */
    private $thumbSrc;
    /**
     * @var
     */
    private $cacheFrames;

    /**
     * @return mixed|null
     */
    public static function getAllJobsDB()
    {
        return wbgAdmin::getInstance()->getOption('jobs');
    }


    /**
     * wbgProject constructor.
     * @param null $id
     * @param string $name
     */
    public function __construct($id = NULL, $slug = '')
    {
        if (NULL === $id && $slug !== NULL) {
            $this->setSlug($slug);
            $this->init();
        } else {
            if (is_string($id)) {
                $this->load($id);
            }
        }
    }


    /**
     *
     */
    public function init()
    {
        $this->setID($this->generateNewID());
        $this->setDateCreated(time());
        $this->setData(array('opts' => ''));
        $this->setStatus(self::STATUS_CREATED);

        if (!$this->getCacheFrames()) {
            /*
         * create directory
         */

            $jobTempPath = WBG_TEMP_DIR . DIRECTORY_SEPARATOR . 'jobs' . DIRECTORY_SEPARATOR . $this->getID();
            $this->setOutputUri(WBG_BANNERS_OUTPUT_URI . '/' . $this->getSlug() . '/');
            $this->setOutputDirectory(WBG_BANNERS_OUTPUT_DIRECTORY_PATH . DIRECTORY_SEPARATOR . $this->getSlug());
            if (!file_exists($this->getOutputDirectory())) {
                mkdir($this->getOutputDirectory(), 0777, true);
            }
            /*$this->setDirectory($jobTempPath);
            if (!file_exists($jobTempPath)) {
                mkdir($jobTempPath, 0777, true);
            }*/
        }

        //$this->save();
    }


    /**
     * @return string
     */
    private function generateNewID()
    {
        return 'job_' . md5(uniqid(rand(), true) . time());
    }

    /**
     * @return array
     */
    private function wrapJob()
    {
        return array(
            'ID' => $this->getID(),
            'date_created' => $this->getDateCreated(),
            'name' => $this->getName(),
            'data' => $this->getData(),
            'directory' => $this->getDirectory(),
            'status' => $this->getStatus(),
        );
    }


    /**
     *
     */
    public function save()
    {
        if (NULL === $jobs = wbgAdmin::getInstance()->getOption('jobs')) {
            $jobs = array();
        }
        $jobs[] = $this->wrapJob();
        $success = wbgAdmin::getInstance()->updateOption('jobs', $jobs);
        if ($success) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    /**
     * @param $id
     * @return bool
     */
    private function load($id)
    {
        $jobs = wbgAdmin::getInstance()->getOption('jobs');
        $job = array();
        $exist = false;
        foreach ($jobs as $k => $v) {
            if ($id === $v['ID']) {
                $exist = TRUE;
                $job = $v;
                break;
            }
        }
        if (FALSE === $exist) {
            return FALSE;
        }


        $this->setID($job['ID']);
        $this->setDateCreated($job['date_created']);
        $this->setName($job['name']);
        $this->setData($job['data']);
        $this->setStatus($job['status']);
        $this->setStatus($job['directory']);


    }


    /**
     * Remove project from tab
     * @return bool
     */
    public function remove($id = NULL)
    {
        $id = !$id ? $this->getID() : $id;
        if (NULL === $jobs = wbgAdmin::getInstance()->getOption('jobs')) {
            return FALSE;
        }
        foreach ($jobs as $k => $v) {
            if ($id === $v['ID']) {
                unset($jobs[$k]);
                break;
            }
        }
        wbgAdmin::getInstance()->updateOption('jobs', $jobs);//update db
        wbgAdmin::getInstance()->updateOptionsDB();
        return TRUE;
    }


    /**
     *Updata single project
     */
    public function updateJobDB()
    {
        $a = $jobs = self::getAllJobsDB();//get project from db
        foreach ($a as $k => $v) {
            if ($v['ID'] === $this->getID()) {
                $jobs[$k] = $this->wrapJob();//update current project
                break;
            }
        }
        //save
        wbgAdmin::getInstance()->setOption('jobs', $jobs);
        wbgAdmin::getInstance()->updateOptionsDB();
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
    public function setName($name)
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
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     */
    private function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return mixed
     */
    public function getOutputDirectory()
    {
        return $this->outputDirectory;
    }

    /**
     * @param mixed $outputDirectory
     */
    public function setOutputDirectory($outputDirectory)
    {
        $this->outputDirectory = $outputDirectory;
    }


    /**
     * @return mixed
     */
    public function getOutputUri()
    {
        return $this->outputUri;
    }

    /**
     * @param mixed $outputUri
     */
    public function setOutputUri($outputUri)
    {
        $this->outputUri = $outputUri;
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
    public function getCacheFrames()
    {
        return $this->cacheFrames;
    }

    /**
     * @param mixed $cacheFrames
     */
    public function setCacheFrames($cacheFrames)
    {
        $this->cacheFrames = $cacheFrames;
    }


}