<?php

/**
 * @package banner-generator-for-woocommerce
 */
class wbgAnimator
{


    /**
     * @var
     */
    private $frames;

    /**
     * @var
     */
    private $intervals;

    /**
     * @var
     */
    private $currentFrame;

    /**
     * @var
     */
    private $interval;
    /**
     * @var
     */
    private $currentFrameID;

    /**
     * @var
     */
    private $frameWidth;
    /**
     * @var
     */
    private $frameHeight;
    /**
     * @var
     */
    private $cellWidth;
    /**
     * @var
     */
    private $cellHeight;
    /**
     * @var
     */
    private $gridX;
    /**
     * @var
     */
    private $gridY;
    /**
     * @var
     */
    private $bannerWidth;
    /**
     * @var
     */
    private $bannerHeight;
    /**
     * @var
     */
    private $text;
    /**
     * @var
     */
    private $fontFile;
    /**
     * @var
     */
    private $fontColor;
    /**
     * @var
     */
    private $fontSize;
    /**
     * @var
     */
    private $fontStyle;
    /**
     * @var
     */
    private $textPosX;
    /**
     * @var
     */
    private $textPosY;
    /**
     * @var
     */
    private $CurrentCellID;
    /**
     * @var
     */
    private $imageSrc;
    /**
     * @var
     */
    private $image;
    /**
     * @var
     */
    private $imageExt;
    /**
     * @var
     */
    private $backgroundColor;

    /**
     * @var
     */
    private $filterTextPositionByCellPadding;


    /**
     * @var
     */
    private $padding;

    /**
     * @var
     */
    private $cellPosX;
    /**
     * @var
     */
    private $cellPosY;

    /**
     * @var
     */
    private $textPosAuto;

    /**
     * @var
     */
    private $textBackgroundColor;
    /**
     * @var
     */
    private $textBackgroundPaddingVertical;
    /**
     * @var
     */
    private $textBackgroundPaddingHorizontal;
    /**
     * @var
     */
    private $textBoxWidth;
    /**
     * @var
     */
    private $textBoxHeight;

    /**
     * @var
     */
    private $maxTiles;
    /**
     * @var
     */
    private $texPosAutoMarginPercent;

    /**
     * @var
     */
    private $totalFrames;

    /**
     * @var bool
     */
    private $useRAM;


    /**
     * @var
     */
    private $projectToGifManagerInstance;

    /**
     * @var
     */
    private $thumbnail;

    /**
     * @var
     */
    private $thumbnailFrameBase;

    /**
     * @var
     */
    private $framesLimit;

    /**
     * @var
     */
    private $framesLimitExceeded;

    /**
     * @var
     */
    private $direction;

    /**
     * @var
     */
    private $maxTextBoxHeight;
    /**
     * @var
     */
    private $maxTextBoxWidth;


    /**
     * wbgAnimator constructor.
     * @param $bannerWidth
     * @param $bannerHeight
     * @param $gridX
     * @param $gridY
     */
    public function __construct($bannerWidth, $bannerHeight, $gridX, $gridY, $backgroundColor, $padding, $maxTiles, $direction)
    {
        $this->setDefaults();
        $this->setDirection($direction);
        $this->setBannerWidth($bannerWidth);
        $this->setBannerHeight($bannerHeight);
        $this->setGridX($gridX);
        $this->setGridY($gridY);
        $this->setPadding($padding);
        $this->setCellWidth($this->calculateCellWidth());
        $this->setCellHeight($this->calculateCellHeight());
        $this->setBackgroundColor($backgroundColor);
        $this->setMaxTiles($maxTiles);
        $this->createBanner();
        $this->setCurrentFrameID(1);
        if ($this->getDirection() === 'right') {
            $this->setCurrentCellID($this->getGridX() * $this->getGridY());
        } else {
            $this->setCurrentCellID(1);
        }
        $this->calculateTotalFrames();
        $this->updateFramesLimitFromSettings();
    }

    /**
     *
     */
    private function calculateTotalFrames()
    {
        $numberOfCells = $this->getGridX() * $this->getGridY();
        $this->setTotalFrames($this->getMaxTiles() - $numberOfCells + 1);
    }

    /**
     *
     */
    private function setDefaults()
    {
        $this->setTexPosAutoMarginPercent(3);
        $this->setFilterTextPositionByCellPadding(FALSE);
        $this->setTextBackgroundColor(NULL);
        $this->setFramesLimitExceeded(FALSE);
        $this->setDirection('right');
    }

    /**
     *
     */
    private function updateFramesLimitFromSettings()
    {
        $this->setFramesLimit((int)wbgAdmin::getInstance()->getOption('frames_limit', WBG_DEFAULT_FRAMES_LIMIT));
    }

    /**
     * Renders image ([if set] and text [if set] on current cell
     * @return mixed
     */
    public function mextCell()
    {
        $this->calculateCellPos();
        $this->calculateTextBoxSize();
        $this->applyAutoTextPos();
        $this->renderCellImage();
        $this->applyPaddingToText();
        $this->renderCellText();
        if ($this->getDirection() === 'right') {
            $this->decreaseCellID();
        } else {
            $this->increaseCellID();
        }
        return $this->getCurrentCellID();
    }

    /**
     *
     */
    public function nextFrame()
    {
        if (!$this->getFramesLimitExceeded()) {


            if ($this->getUseRAM()) {
                ob_start();
                imagegif($this->getCurrentFrame());
                imagedestroy($this->getCurrentFrame());
                $frames = $this->getFrames();
                $frames[$this->currentFrameID] = ob_get_clean();
                $this->setFrames($frames);
            } else {
                $this->getProjectToGifManagerInstance()->saveFrame($this->getCurrentFrame());
            }
        }

        $this->setIntervalToCurrentFrame();
        $this->increaseFrameID();
        if ($this->getDirection() === 'right') {
            $this->setCurrentCellID($this->getGridX() * $this->getGridY());
        } else {
            $this->setCurrentCellID(1);
        }
        $this->createBanner();
    }

    /**
     *
     */
    private function setIntervalToCurrentFrame()
    {
        $intervals = $this->getIntervals();
        $intervals[$this->getCurrentFrameID()] = $this->getInterval();
        $this->setIntervals($intervals);
    }

    /**
     *
     */
    private function increaseCellID()
    {
        $this->setCurrentCellID($this->getCurrentCellID() + 1);
    }


    /**
     *
     */
    private function decreaseCellID()
    {
        $this->setCurrentCellID($this->getCurrentCellID() - 1);
    }


    /**
     *
     */
    private function increaseFrameID()
    {
        if ($this->getFramesLimit() === $this->getCurrentFrameID()) {
            $this->setFramesLimitExceeded(TRUE);
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('Frames limit has been exceeded. You can change the limit of maximum number of frames on the settings tab.', 'wbg_plugin'), 'warning', FALSE, TRUE, '', '', '');
            wbgAlerts::getInstance()->addAlertSimple(esc_html__('Banner is not generated correctly.', 'wbg_plugin'), 'danger', FALSE, TRUE, '', '', '');
        }
        $this->setCurrentFrameID($this->getCurrentFrameID() + 1);
    }

    /**
     * Renders image on current cell
     * @return bool
     */
    private function renderCellImage()
    {
        if ($this->getFramesLimitExceeded()) {
            return FALSE;
        }
        //create image resource
        if ($this->isPNG($this->getImageExt())) {
            $this->setImage(imagecreatefrompng($this->getImageSrc()));
        } elseif ($this->isJPG($this->getImageExt())) {
            $this->setImage(imagecreatefromjpeg($this->getImageSrc()));
        } else {
            return FALSE;//do nothing if image has non-supported format or if image is not set
        }


        //resize to cell size
        $newImage = imagescale($this->getImage(), $this->getCellWidth(), $this->getCellHeight());
        $this->setImage($newImage);

        //$this->exportFrameAsImage($this->getImage());

        $newCurrentFrame = $this->getCurrentFrame();
        //nerge image width current cell
        imagecopy($newCurrentFrame,
            $this->getImage(),
            $this->filterDestImagePosX($this->getCellPosX()),
            $this->filterDestImagePosY($this->getCellPosY()),
            0,
            0,
            $this->filterDestImgWidth($this->getCellWidth()),
            $this->filterDestImgHeight($this->getCellHeight())
        );


        $this->setCurrentFrame($newCurrentFrame);
        return TRUE;

    }


    /**
     * @param $res
     * @return bool|resource
     */
    public function resizeImage($res)
    {
        return imagescale($this->getImage(), $this->getCellWidth(), $this->getCellHeight());
    }


    /**
     * Renders text on current cell
     */
    private function renderCellText()
    {
        if ($this->getFramesLimitExceeded()) {
            return FALSE;
        }

        if (!is_string($this->getText())) {
            return FALSE;
        }
        $newCurrentFrame = $this->getCurrentFrame();

        //set text
        imagettftext($newCurrentFrame,
            $this->getFontSize(),
            0,
            $this->filterTextPosX($this->calculateTextPosX($this->getTextPosX())),
            $this->filterTextPosY($this->calculateTextPosY($this->getTextPosY())),
            $this->getFontColor(),
            $this->getfontFile(),
            $this->getText());

        $this->setCurrentFrame($newCurrentFrame);
        return TRUE;
    }


    /**
     * @param $x1
     * @param $y1
     * @param $x2
     * @param $y2
     * @param $color
     */
    private function drawFilledRectangle($x1, $y1, $x2, $y2, $color)
    {
        $image = $this->getCurrentFrame();
        imagefilledrectangle($image, $x1, $y1, $x2, $y2, $this->getColorAllocated($this->getCurrentFrame(), $color));
        $this->setCurrentFrame($image);
    }

    /**
     *
     */
    private function applyPaddingToText()
    {
        if ($this->getFramesLimitExceeded()) {
            return;
        }

        if (!$this->getTextBackgroundColor()) {
            return;
        }

        if (!$this->getTextBoxWidth() || !$this->getTextBoxHeight()) {
            $this->calculateTextBoxSize();
        }


        $textPosX = $this->getCellPosX() + (int)$this->getTextPosX();
        $textPosY = $this->getCellPosY() + (int)$this->getTextPosY();
        $textBoxWidth = (int)$this->getTextBoxWidth();
        $textBoxHeight = (int)$this->getTextBoxHeight();

        //vv($textBoxHeight);
        $this->drawFilledRectangle(//draw padding on current frame
            $textPosX - $this->getTextBackgroundPaddingHorizontal(),
            $textPosY - $textBoxHeight - $this->getTextBackgroundPaddingVertical(),
            $textPosX + $textBoxWidth + $this->getTextBackgroundPaddingHorizontal(),
            $textPosY + $this->getTextBackgroundPaddingVertical(),
            $this->getTextBackgroundColor()
        );

    }


    /**
     * @param $value
     * @return mixed
     */
    private function filterTextPosX($value)
    {
        if ($this->isFilterTextPositionByCellPadding()) {
            $value += $this->getPadding();
        }

        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    private function filterTextPosY($value)
    {
        if ($this->isFilterTextPositionByCellPadding()) {
            $value += $this->getPadding();
        }

        return $value;
    }


    /**
     * @param $value
     * @return mixed
     */
    private function filterDestImgWidth($value)
    {
        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    private function filterDestImgHeight($value)
    {
        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    private function filterDestImagePosX($value)
    {
        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    private function filterDestImagePosY($value)
    {
        return $value;
    }

    /**
     * @param $cellID
     */
    public function copyCelltoCell($cellID)
    {

    }


    /**
     * @param $ext
     * @return bool
     */
    private function isPNG($ext)
    {
        if ('PNG' === $ext || 'png' === $ext) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param $ext
     * @return bool
     */
    private function isJPG($ext)
    {
        if ('JPG' === $ext || 'jpg' === $ext) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param $im
     * @param $hex
     * @return int
     */
    private function getColorAllocated($im, $hex)
    {
        if ($this->getFramesLimitExceeded()) {
            return NULL;
        }
        $hex = str_replace('#', '', $hex);
        $a = hexdec(substr($hex, 0, 2));
        $b = hexdec(substr($hex, 2, 2));
        $c = hexdec(substr($hex, 4, 2));
        return imagecolorallocate($im, $a, $b, $c);
    }


    /**
     *
     */
    private function createBanner()
    {
        if ($this->getFramesLimitExceeded()) {
            return;
        }
        $newImage = imagecreatetruecolor($this->getBannerWidth(), $this->getBannerHeight());//create image
        $color = $this->getColorAllocated($newImage, $this->getBackgroundColor());
        imagefill($newImage, 0, 0, $color);//set bg color
        $this->setCurrentFrame($newImage);
    }

    /**
     * @return float
     */
    private function calculateCellWidth()
    {
        return ($this->getBannerWidth() / $this->getGridX()) - $this->getPadding() - ($this->getPadding() / $this->getGridX());
    }

    /**
     * @return float
     */
    private function calculateCellHeight()
    {


        return ($this->getBannerHeight() / $this->getGridY()) - $this->getPadding() - ($this->getPadding() / $this->getGridY());

    }

    /**
     *
     */
    private function calculateCellPos()
    {
        $currentCell = $this->getCurrentCellID();
        $gridY = $this->getGridY();
        $gridX = $this->getGridX();
        $cellWidth = $this->getCellWidth();
        $cellHeight = $this->getCellHeight();
        $cells = 1;
        $x = 1;
        for ($y = 1; $y < $gridY + 1; $y++) {//check current row
            for ($x = 1; $x < $gridX + 1; $x++) {
                if ($cells === $currentCell) {
                    break(2);
                }
                $cells++;
            }
        }

        $this->setCellPosX(($x - 1) * $cellWidth + ($this->getPadding() * $x));//padding fix
        $this->setCellPosY(($y - 1) * $cellHeight + ($this->getPadding() * $y));//padding fix

    }


    /**
     * sets text position to current cell position
     * @param $textPosX
     * @return mixed
     */
    private function calculateTextPosX($textPosX)
    {
        return $this->getCellPosX() + $textPosX;
    }


    /**
     * @param $textPosY
     * @return mixed
     */
    private function calculateTextPosY($textPosY)
    {
        return $this->getCellPosY() + $textPosY;
    }


    /**
     *
     */
    public function exportFramesAsImages()
    {
        $frames = $this->getFrames();
        $path = wbg::getInstance()->getTempDirPath();
        if (!$path) {
            return;
        }
        $dirName = wbgProject::getActiveProjectInstance()->getName() . '_' . md5(uniqid(rand(), true) . time());
        $fullPath = $path . DIRECTORY_SEPARATOR . $dirName;
        mkdir($fullPath, 0777, true);
        $i = 1;
        $files = array();
        foreach ($frames as $frame) {
            imagepng($frame, $fullPath . DIRECTORY_SEPARATOR . $i . '.png');
            $files[] = WBG_TEMP_DIR_URI . '/' . $dirName . '/' . $i . '.png';
            $i++;
            //imagedestroy ($frame);
        }
        return $files;

    }


    /**
     * @param $image
     */
    public function exportFrameAsImage($image)
    {
        $path = wbg::getInstance()->getTempDirPath();
        if (!$path) {
            return;
        }
        $dirName = 'singleExport';
        $fullPath = $path . DIRECTORY_SEPARATOR . $dirName;
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0777, true);
        }
        imagepng($image, $fullPath . DIRECTORY_SEPARATOR . md5(uniqid(rand(), true) . time()) . '.png');
    }


    /**
     *
     */
    private function calculateTextBoxSize()
    {
        $arr = imagettfbbox(
            $this->getFontSize(),
            0,
            $this->getfontFile(),
            $this->getText());


        //keep always the same text box width
        $newTextBoxWidth = $arr[4] - $arr[6];
        /*if ($newTextBoxWidth > (int)$this->getMaxTextBoxWidth()) {
            $this->setMaxTextBoxWidth($newTextBoxWidth);
        } else {
            $newTextBoxWidth = $this->getMaxTextBoxWidth();
        }*/

        $this->setTextBoxWidth($newTextBoxWidth);

        //keep always the same text box height
        $newTextBoxHeight = $arr[3] - $arr[5];
        if ($newTextBoxHeight > (int)$this->getMaxTextBoxHeight()) {
            $this->setMaxTextBoxHeight($newTextBoxHeight);
        } else {
            $newTextBoxHeight = $this->getMaxTextBoxHeight();
        }
        $this->setTextBoxHeight($newTextBoxHeight);

    }

    /**
     *
     */
    private function applyAutoTextPos()
    {
        $position = $this->getTextPosAuto();
        if (!$position || !$this->getText()) return;


        $textBoxWidth = $this->getTextBoxWidth();
        $textBoxHeight = $this->getTextBoxHeight();

        $margin = $this->getTexPosAutoMarginPercent();
        $this->setFilterTextPositionByCellPadding(FALSE);//disable default correction


        switch ($position) {
            case 'center':
                $this->setTextPosX($this->getCellWidth() / 2 - $textBoxWidth / 2);
                $this->setTextPosY($this->getCellHeight() / 2 + $textBoxHeight / 2);
                break;
            case 'center_top':
                $this->setTextPosX($this->getCellWidth() / 2 - $textBoxWidth / 2);
                $this->setTextPosY($textBoxHeight + ($margin / 100) * $this->getCellHeight());
                break;
            case 'center_bottom':
                $this->setTextPosX($this->getCellWidth() / 2 - $textBoxWidth / 2);
                $this->setTextPosY($this->getCellHeight() - ($margin / 100) * $this->getCellHeight());
                break;
            case 'top_left':
                $this->setTextPosX(($margin / 100) * $this->getCellHeight());
                $this->setTextPosY($textBoxHeight + ($margin / 100) * $this->getCellHeight());
                break;
            case 'top_right':
                $this->setTextPosX($this->getCellWidth() - $textBoxWidth - ($margin / 100) * $this->getCellWidth());
                $this->setTextPosY($textBoxHeight + ($margin / 100) * $this->getCellHeight());
                break;
            case 'bottom_left':
                $this->setTextPosX(($margin / 100) * $this->getCellWidth());
                $this->setTextPosY($this->getCellHeight() - ($margin / 100) * $this->getCellHeight());

                break;
            case 'bottom_right':
                $this->setTextPosX($this->getCellWidth() - $textBoxWidth - ($margin / 100) * $this->getCellWidth());
                $this->setTextPosY($this->getCellHeight() - ($margin / 100) * $this->getCellHeight());

        }
    }

    /**
     * @param $image
     * @param $width
     * @param $height
     * @param $newWidth
     * @return resource
     */
    public static function generateThumbnail($image, $width, $height, $newWidth)
    {

        $aspect = $width / $height;
        $newHeight = $newWidth / $aspect;
        $newImage = imagecreatetruecolor($newWidth, $newHeight);//create image
        imagecopy($newImage,
            $image,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight
        );

        ob_start();
        imagegif($newImage);
        $file = ob_get_clean();
        return $file;


    }




    /*
     * Getters and setters
     */

    /**
     * @return mixed
     */
    public function getFrameWidth()
    {
        return $this->frameWidth;
    }


    /**
     * @param mixed $frameWidth
     */
    private function setFrameWidth($frameWidth)
    {
        $this->frameWidth = $frameWidth;
    }

    /**
     * @return mixed
     */
    public function getFrameHeight()
    {
        return $this->frameHeight;
    }

    /**
     * @param mixed $frameHeight
     */
    private function setFrameHeight($frameHeight)
    {
        $this->frameHeight = $frameHeight;
    }

    /**
     * @return mixed
     */
    public function getCellWidth()
    {
        return $this->cellWidth;
    }

    /**
     * @param mixed $cellWidth
     */
    public function setCellWidth($cellWidth)
    {
        $this->cellWidth = $cellWidth;
    }

    /**
     * @return mixed
     */
    public function getCellHeight()
    {
        return $this->cellHeight;
    }

    /**
     * @param mixed $cellHeight
     */
    public function setCellHeight($cellHeight)
    {
        $this->cellHeight = $cellHeight;
    }


    /**
     * @return mixed
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param mixed $background
     */
    public function setBackgroundColor($color)
    {
        $this->backgroundColor = $color;
    }

    /**
     * @return mixed
     */
    public function getCurrentFrame()
    {
        return $this->currentFrame;
    }

    /**
     * @param mixed $frame
     */
    public function setCurrentFrame($frame)
    {
        $this->currentFrame = $frame;
    }


    /**
     * @return mixed
     */
    public function getCurrentFrameID()
    {
        return $this->currentFrameID;
    }

    /**
     * @param mixed $currentFrameID
     */
    public function setCurrentFrameID($currentFrameID)
    {
        $this->currentFrameID = $currentFrameID;
    }


    /**
     * @return mixed
     */
    public function getCurrentCellID()
    {
        return $this->CurrentCellID;
    }

    /**
     * @param mixed $CurrentCellID
     */
    public function setCurrentCellID($CurrentCellID)
    {
        $this->CurrentCellID = $CurrentCellID;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getImageExt()
    {
        return $this->imageExt;
    }

    /**
     * @param mixed $imageExt
     */
    public function setImageExt($imageExt)
    {
        $this->imageExt = $imageExt;
    }


    /**
     * @return mixed
     */
    public function getImageSrc()
    {
        return $this->imageSrc;
    }


    /**
     * @param $imageSrc
     */
    public function setImageSrc($imageSrc)
    {
        $parsedSrc = parse_url($imageSrc);
        $this->setImageExt(pathinfo($parsedSrc['path'], PATHINFO_EXTENSION));
        $this->imageSrc = $imageSrc;
    }


    /**
     * @param $frameID
     * @return mixed
     */
    public function getFrame($frameID)
    {
        $arr = $this->getFrames();
        return $arr[$frameID];
    }

    /**
     * @return mixed
     */
    public function getFrames()
    {
        return $this->frames;
    }

    /**
     * @param mixed $frames
     */
    public function setFrames($frames)
    {
        $this->frames = $frames;
    }

    /**
     * @return mixed
     */
    public function getGridX()
    {
        return (int)$this->gridX;
    }

    /**
     * @param mixed $gridX
     */
    public function setGridX($gridX)
    {
        $this->gridX = $gridX;
    }

    /**
     * @return mixed
     */
    public function getGridY()
    {
        return (int)$this->gridY;
    }

    /**
     * @param mixed $gridY
     */
    public function setGridY($gridY)
    {
        $this->gridY = $gridY;
    }

    /**
     * @return mixed
     */
    public function getBannerWidth()
    {
        return $this->bannerWidth;
    }

    /**
     * @param mixed $bannerWidth
     */
    public function setBannerWidth($bannerWidth)
    {
        if (!$this->bannerWidth) {
            $this->bannerWidth = $bannerWidth;
        }
    }

    /**
     * @return mixed
     */
    public function getBannerHeight()
    {
        return $this->bannerHeight;
    }

    /**
     * @param mixed $bannerHeight
     */
    public function setBannerHeight($bannerHeight)
    {
        if (!$this->bannerHeight)
            $this->bannerHeight = $bannerHeight;
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
        $this->fontColor = $this->getColorAllocated($this->getCurrentFrame(), $fontColor);
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


    /**
     * @return mixed
     */
    public function getfontFile()
    {
        return $this->fontFile;
    }

    /**
     * @param mixed $fontFile
     */
    public function setfontFile($fontFile)
    {
        $this->fontFile = $fontFile;
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
    public function getFontStyle()
    {
        return $this->fontStyle;
    }

    /**
     * @param mixed $fontStyle
     */
    public function setFontStyle($fontStyle)
    {
        $this->fontStyle = $fontStyle;
    }

    /**
     * @return mixed
     */
    public function getTextPosX()
    {
        return $this->textPosX;
    }

    /**
     * @param mixed $textPosX
     */
    public function setTextPosX($textPosX)
    {
        $this->textPosX = $textPosX;
    }

    /**
     * @return mixed
     */
    public function getTextPosY()
    {
        return $this->textPosY;
    }

    /**
     * @param mixed $textPosY
     */
    public function setTextPosY($textPosY)
    {
        $this->textPosY = $textPosY;
    }

    /**
     * @return mixed
     */
    public function getPadding()
    {
        return $this->padding;
    }

    /**
     * @param mixed $padding
     */
    public function setPadding($padding)
    {
        $this->padding = $padding;
    }

    /**
     * @return mixed
     */
    public function getCellPosX()
    {
        return $this->cellPosX;
    }

    /**
     * @param mixed $cellPosX
     */
    public function setCellPosX($cellPosX)
    {
        $this->cellPosX = $cellPosX;
    }

    /**
     * @return mixed
     */
    public function getCellPosY()
    {
        return $this->cellPosY;
    }

    /**
     * @param mixed $cellPosY
     */
    public function setCellPosY($cellPosY)
    {
        $this->cellPosY = $cellPosY;
    }

    /**
     *
     * @return boolean
     */
    public function isFilterTextPositionByCellPadding()
    {
        return $this->filterTextPositionByCellPadding;
    }

    /**
     * apply cell padding to text position
     * @param boolean $filterTextPosition
     */
    public function setFilterTextPositionByCellPadding($filterTextPosition)
    {
        $this->filterTextPositionByCellPadding = $filterTextPosition;
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
     * @return int
     */
    public function getTexPosAutoMarginPercent()
    {
        return $this->texPosAutoMarginPercent;
    }

    /**
     * @param int $texPosAutoMarginPercent
     */
    public function setTexPosAutoMarginPercent($texPosAutoMarginPercent)
    {
        $this->texPosAutoMarginPercent = $texPosAutoMarginPercent;
    }


    /**
     * @return mixed
     */
    public function getTextBackgroundColor()
    {
        return $this->textBackgroundColor;
    }

    /**
     * @param mixed $textBackgroundColor
     */
    public function setTextBackgroundColor($textBackgroundColor)
    {
        $this->textBackgroundColor = $textBackgroundColor;
    }


    /**
     * @return mixed
     */
    public function getTextBoxWidth()
    {
        return $this->textBoxWidth;
    }

    /**
     * @param mixed $textBoxWidth
     */
    public function setTextBoxWidth($textBoxWidth)
    {
        $this->textBoxWidth = $textBoxWidth;
    }

    /**
     * @return mixed
     */
    public function getTextBoxHeight()
    {
        return $this->textBoxHeight;
    }

    /**
     * @param mixed $textBoxHeight
     */
    public function setTextBoxHeight($textBoxHeight)
    {
        $this->textBoxHeight = $textBoxHeight;
    }

    /**
     * @return mixed
     */
    public function getTextBackgroundPaddingVertical()
    {
        return $this->textBackgroundPaddingVertical;
    }

    /**
     * @param mixed $textBackgroundPaddingVertical
     */
    public function setTextBackgroundPaddingVertical($textBackgroundPaddingVertical)
    {
        $this->textBackgroundPaddingVertical = $textBackgroundPaddingVertical;
    }

    /**
     * @return mixed
     */
    public function getTextBackgroundPaddingHorizontal()
    {
        return $this->textBackgroundPaddingHorizontal;
    }

    /**
     * @param mixed $textBackgroundPaddingHorizontal
     */
    public function setTextBackgroundPaddingHorizontal($textBackgroundPaddingHorizontal)
    {
        $this->textBackgroundPaddingHorizontal = $textBackgroundPaddingHorizontal;
    }

    /**
     * @return mixed
     */
    public function getTotalFrames()
    {
        return $this->totalFrames;
    }

    /**
     * @param mixed $totalFrames
     */
    private function setTotalFrames($totalFrames)
    {
        $this->totalFrames = $totalFrames;
    }

    /**
     * @return mixed
     */
    public function getMaxTiles()
    {
        return $this->maxTiles;
    }

    /**
     * @param mixed $maxTiles
     */
    public function setMaxTiles($maxTiles)
    {
        $this->maxTiles = $maxTiles;
    }

    /**
     * @return boolean
     */
    public function getUseRAM()
    {
        return $this->useRAM;
    }

    /**
     * @param boolean $useRAM
     */
    public function setUseRAM($useRAM)
    {
        $this->useRAM = $useRAM;
    }

    /**
     * @return wbgProjectToGifManager
     */
    public function getProjectToGifManagerInstance()
    {
        return $this->projectToGifManagerInstance;
    }

    /**
     * @param mixed $projectToGifManagerInstance
     */
    public function setProjectToGifManagerInstance($projectToGifManagerInstance)
    {
        $this->projectToGifManagerInstance = $projectToGifManagerInstance;
    }

    /**
     * @return mixed
     */
    private function getFramesLimit()
    {
        return $this->framesLimit;
    }

    /**
     * @param mixed $framesLimit
     */
    private function setFramesLimit($framesLimit)
    {
        $this->framesLimit = $framesLimit;
    }

    /**
     * @return mixed
     */
    private function getFramesLimitExceeded()
    {
        return $this->framesLimitExceeded;
    }

    /**
     * @param mixed $framesLimitExceeded
     */
    private function setFramesLimitExceeded($framesLimitExceeded)
    {
        $this->framesLimitExceeded = $framesLimitExceeded;
    }

    /**
     * @return mixed
     */
    public function getIntervals()
    {
        return $this->intervals;
    }

    /**
     * @param mixed $intervals
     */
    private function setIntervals($intervals)
    {
        $this->intervals = $intervals;
    }

    /**
     * @return mixed
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param mixed $interval
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
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
     * @return mixed
     */
    private function getMaxTextBoxHeight()
    {
        return $this->maxTextBoxHeight;
    }

    /**
     * @param mixed $maxTextBoxHeight
     */
    private function setMaxTextBoxHeight($maxTextBoxHeight)
    {
        $this->maxTextBoxHeight = $maxTextBoxHeight;
    }

    /**
     * @return mixed
     */
    public function getMaxTextBoxWidth()
    {
        return $this->maxTextBoxWidth;
    }

    /**
     * @param mixed $maxTextBoxWidth
     */
    public function setMaxTextBoxWidth($maxTextBoxWidth)
    {
        $this->maxTextBoxWidth = $maxTextBoxWidth;
    }


}