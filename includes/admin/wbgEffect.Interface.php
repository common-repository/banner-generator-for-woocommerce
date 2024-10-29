<?php

/**
 * @package banner-generator-for-woocommerce
 */
interface wbgEffectInterface
{
    /**
     * @return mixed
     */
    public function getMaxTiles();

    /**
     * @return mixed
     */
    public function getGridX();

    /**
     * @return mixed
     */
    public function getGridY();

    /**
     * @return mixed
     */
    public function getPadding();

    /**
     * @return mixed
     */
    public function getBgColor();

    /**
     * @return mixed
     */
    public function getFontSize();

    /**
     * @return mixed
     */
    public function getTextBgColor();

    /**
     * @return mixed
     */
    public function getTextBgPaddingH();

    /**
     * @return mixed
     */
    public function getTextBgPaddingV();

    /**
     * @return mixed
     */
    public function getTextPosAuto();

    /**
     * @return mixed
     */
    public function getFontSlug();

    /**
     * @return mixed
     */
    public function getFontColor();


}