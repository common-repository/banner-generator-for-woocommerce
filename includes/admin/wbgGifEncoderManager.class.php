<?php

/**
 * @package banner-generator-for-woocommerce
 */
class wbgGifEncoderManager
{
    /**
     * @var array
     */
    private $frames = array();
    /**
     * @var array
     */
    private $intervals = array();
    /**
     * @var
     */
    private $encoder;


    /**
     * @return string
     */
    public function getGif()
    {
        $gif = new GIFEncoder($this->getFrames(), $this->getIntervals(), 0, 2, 0, 0, 0, 0, "bin");
        return $gif->GetAnimation();
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
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * @param mixed $encoder
     */
    public function setEncoder($encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @return array
     */
    public function getIntervals()
    {
        return $this->intervals;
    }

    /**
     * @param array $intervals
     */
    public function setIntervals($intervals)
    {
        $this->intervals = $intervals;
    }


}