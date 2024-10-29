<?php

/**
 * @package banner-generator-for-woocommerce
 */
class wbgProjectToGifManager
{

    /**
     *
     */
    const ENCODER_MANAGER_CLASS_NAME = 'wbgGifEncoderManager';
    /**
     * @var
     */
    private $jobInstance;
    /**
     * @var
     */
    private $projectInstance;
    /**
     * @var
     */
    private $animatorInstance;

    /**
     * @var
     */
    private $encoderManagerInstance;
    /**
     * @var wbgAnimator
     */
    private $effectInstance;

    /**
     * @var
     */
    private $useRAMtoStoreFrames;

    /**
     * @var
     */
    private $outputOnlyFirstFrame;


    /**
     * wbgProjectToGifManager constructor.
     * @param wbgProject $project
     * @param wbgEffectInterface $effect
     */
    public function __construct(wbgProject $project, wbgEffectInterface $effect, $opts = array())
    {
        $defaultOpts = array('use_ram' => 1);
        $opts = array_merge($defaultOpts, $opts);

        if (isset($opts['use_ram']) && 1 === $opts['use_ram']) {
            $this->setUseRAMtoStoreFrames(TRUE);
        } else {
            $this->setUseRAMtoStoreFrames(FALSE);
        }

        $this->setEffectInstance($effect);
        $this->setProjectInstance($project);
        $this->configureAnimator();
        $this->configureJob();
        $this->configureEncoder();

    }


    /**
     *
     */
    private function configureEncoder()
    {

        $className = self::ENCODER_MANAGER_CLASS_NAME;
        $this->setEncoderManagerInstance(new $className());
    }

    /**
     *
     */
    private function configureAnimator()
    {
        $animator = new wbgAnimator(
            $this->getProjectInstance()->getWidth(),
            $this->getProjectInstance()->getHeight(),
            (int)$this->getEffectInstance()->getGridX(),
            (int)$this->getEffectInstance()->getGridY(),
            $this->getEffectInstance()->getBgColor(),
            (int)$this->getEffectInstance()->getPadding(),
            $this->getEffectInstance()->getMaxTiles(),
            $this->getEffectInstance()->getDirection()
        );
        $animator->setUseRAM($this->getUseRAMtoStoreFrames());
        $animator->setProjectToGifManagerInstance($this);//set manager reference to Animator
        $this->setAnimatorInstance($animator);
    }

    /**
     *
     */
    private function configureJob()
    {
        $job = (new wbgJob(NULL, $this->getProjectInstance()->getSlug()));
        $job->setName($this->getProjectInstance()->getName());
        if ($this->getAnimatorInstance()->getUseRAM()) {
            $job->setCacheFrames(FALSE);
        } else {
            $job->setCacheFrames(TRUE);
        }
        $this->setJobInstance($job);
    }

    /**
     * @param $image
     */
    public function saveFrame($image)
    {
        //cache current frame
        imagegif($image, $this->getJobInstance()->getDirectory() . DIRECTORY_SEPARATOR . $this->getAnimatorInstance()->getCurrentFrameID() . '.png');
        imagedestroy($image);
    }

    /**
     *
     */
    public function generateGif()
    {

        if ($this->getOutputOnlyFirstFrame()) {
            $this->generateFirstFrame();
        } else {
            $this->generate();
        }
    }

    /**
     * @return bool
     */
    private function generate()
    {
        $this->getJobInstance()->setStatus(wbgJob::STATUS_FRAMES_COLLECTED);
        $encoderManager = $this->getEncoderManagerInstance();
        $encoderManager->setFrames($this->reBuildFramesArray($this->getAnimatorInstance()->getFrames()));
        $encoderManager->setIntervals($this->reBuildFramesArray($this->getAnimatorInstance()->getIntervals()));

        $this->getJobInstance()->setStatus(wbgJob::STATUS_ENCODER_EXECUTE);
        //$this->getJobInstance()->updateJobDB();
        try {
            $gif = $encoderManager->getGif();
            $this->getJobInstance()->setStatus(wbgJob::STATUS_ENCODER_GIF_CREATED);
            $this->writeGifFile($gif);
            $this->writeThumb($this->getAnimatorInstance()->getFrame(1));
        } catch (Exception $e) {
            wbg_update_log_file($e->getMessage());
            wbgAlerts::getInstance()->addAlertSimple($e->getMessage(), 'warning', FALSE, TRUE, '', '', '');

            return false;
        }
        /*
         * set paths and update project
         */
        $this->getJobInstance()->setStatus(wbgJob::STATUS_GIF_SAVED);
        $this->getProjectInstance()->setGifSrc($this->getJobInstance()->getGifSrc());
        $this->getProjectInstance()->setThumbSrc($this->getJobInstance()->getThumbSrc());
        $this->getProjectInstance()->setOutputDir($this->getJobInstance()->getOutputDirectory());
        $this->getProjectInstance()->setGifPath($this->getJobInstance()->getGifPath());
        $this->getProjectInstance()->setThumbPath($this->getJobInstance()->getThumbPath());
        $this->getProjectInstance()->updateProjectDB();
        wbgAlerts::getInstance()->addAlertSimple(esc_html__('Banner has been generated', 'wbg_plugin'), 'success', FALSE, TRUE, '', '', '');
        $this->getJobInstance()->remove();

    }

    /**
     * @return bool
     */
    private function generateFirstFrame()
    {
        $frames = ($this->reBuildFramesArray($this->getAnimatorInstance()->getFrames()));
        $gif = $frames[0];
        try {
            $this->writePreviewFile($gif);
        } catch (Exception $e) {
            wbg_update_log_file($e->getMessage());
            wbgAlerts::getInstance()->addAlertSimple($e->getMessage(), 'warning', FALSE, TRUE, '', '', '');
            return false;
        }


        /*
     * output firms frame preview
     */
        add_action('wbg_effect_content_gif', function () {
            wbg_get_template('adminProjectGifPreview.php', array('gifSrc' => $this->getJobInstance()->getGifSrc()));
        }, 10);
        $this->getJobInstance()->remove();//remove job from db


    }

    /**
     * @param $gif
     */
    private function writePreviewFile($gif)
    {
        /*
         * Write gif, set path, set src
         */
        $fullPath = WBG_TEMP_DIR . DIRECTORY_SEPARATOR . 'preview.gif';
        $fullSrc = WBG_TEMP_DIR_URI . '/' . 'preview.gif';
        $resource = fopen($fullPath, "w") or wp_die("Unable to open file!");
        fwrite($resource, $gif);
        fclose($resource);
        $this->getJobInstance()->setGifPath($fullPath);
        $this->getJobInstance()->setGifSrc($fullSrc);
    }

    /**
     * @param $gif
     */
    private function writeGifFile($gif)
    {
        /*
         * Write gif, set path, set src
         */
        $fullPath = $this->getJobInstance()->getOutputDirectory() . DIRECTORY_SEPARATOR . $this->getProjectInstance()->getSlug() . '.gif';
        $fullSrc = $this->getJobInstance()->getOutputUri() . $this->getProjectInstance()->getSlug() . '.gif';
        $resource = fopen($fullPath, "w") or wp_die("Unable to open file!");
        fwrite($resource, $gif);
        fclose($resource);
        $this->getJobInstance()->setGifPath($fullPath);
        $this->getJobInstance()->setGifSrc($fullSrc);
    }

    /**
     * @param $thumb
     */
    private function writeThumb($thumb)
    {

        /*
         * Write first frame as thumb
         */
        $fullPath = $this->getJobInstance()->getOutputDirectory() . DIRECTORY_SEPARATOR . 'thumb.gif';
        $fullSrc = $this->getJobInstance()->getOutputUri() . 'thumb.gif';
        $resource = fopen($fullPath, "w") or wp_die("Unable to open file!");
        fwrite($resource, $thumb);
        fclose($resource);
        $this->getJobInstance()->setThumbPath($fullPath);
        $this->getJobInstance()->setThumbSrc($fullSrc);
    }


    /**
     * @param $arr
     * @return array
     */
    private function reBuildFramesArray($arr)
    {
        $newArr = array();
        $i = 0;
        foreach ($arr as $k => $v) {
            $newArr[$i] = $v;
            $i++;
        }
        return $newArr;
    }


    /**
     * @return wbgJob
     */
    private function getJobInstance()
    {
        return $this->jobInstance;
    }

    /**
     * @param wbgJob $jobInstance
     */
    private function setJobInstance($jobInstance)
    {
        $this->jobInstance = $jobInstance;
    }

    /**
     * @return wbgProject
     */
    private function getProjectInstance()
    {
        return $this->projectInstance;
    }

    /**
     * @param wbgProject $projectInstance
     */
    private function setProjectInstance($projectInstance)
    {
        $this->projectInstance = $projectInstance;
    }

    /**
     * @return wbgAnimator
     */
    public function getAnimatorInstance()
    {
        return $this->animatorInstance;
    }

    /**
     * @param wbgAnimator $animatorInstance
     */
    private function setAnimatorInstance($animatorInstance)
    {
        $this->animatorInstance = $animatorInstance;
    }

    /**
     * @return mixed
     */
    private function getEffectInstance()
    {
        return $this->effectInstance;
    }

    /**
     * @param mixed $effectInstance
     */
    private function setEffectInstance($effectInstance)
    {
        $this->effectInstance = $effectInstance;
    }

    /**
     * @return mixed
     */
    private function getUseRAMtoStoreFrames()
    {
        return $this->useRAMtoStoreFrames;
    }

    /**
     * @param mixed $useRAMtoStoreFrames
     */
    private function setUseRAMtoStoreFrames($useRAMtoStoreFrames)
    {
        $this->useRAMtoStoreFrames = $useRAMtoStoreFrames;
    }

    /**
     * @return wbgGifEncoderManager
     */
    private function getEncoderManagerInstance()
    {
        return $this->encoderManagerInstance;
    }

    /**
     * @param mixed $encoderManagerInstance
     */
    private function setEncoderManagerInstance($encoderManagerInstance)
    {
        $this->encoderManagerInstance = $encoderManagerInstance;
    }

    /**
     * @return mixed
     */
    private function getOutputOnlyFirstFrame()
    {
        return $this->outputOnlyFirstFrame;
    }

    /**
     * @param mixed $outputOnlyFirstFrame
     */
    public function setOutputOnlyFirstFrame($outputOnlyFirstFrame)
    {
        $this->outputOnlyFirstFrame = $outputOnlyFirstFrame;
    }


}