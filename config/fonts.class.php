<?php

/**
 * Class wbgFonts
 */
class wbgFonts
{
    /**
     * @var
     */
    private $fonts;

    /**
     * @param $slug
     * @return mixed
     */
    public function getPathBySlug($slug)
    {
        $fonts = $this->getFonts();
        foreach ($fonts as $k => $v) {
            if ($slug === $v['slug']) {
                return $v['path'];
            }
        }
        return FALSE;
    }


    /**
     * wbgFonts constructor.
     */
    public function __construct()
    {
        $this->configure_fonts();
    }

    /**
     * @return wbgFonts
     */
    public static function getInstance(){
        return new self;
    }

    /**
     *
     */
    function configure_fonts()
    {
        $this->setFonts(
            array(
			array("path"=>WBG_FONTS_PATH."/andika-basic-ttf/AndikaNewBasic-B.ttf","slug"=>"AndikaNewBasic-B","name"=>"Andika New Basic B", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/andika-basic-ttf/AndikaNewBasic-BI.ttf","slug"=>"AndikaNewBasic-BI","name"=>"Andika New Basic BI", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/andika-basic-ttf/AndikaNewBasic-I.ttf","slug"=>"AndikaNewBasic-I","name"=>"Andika New Basic I", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/andika-basic-ttf/AndikaNewBasic-R.ttf","slug"=>"AndikaNewBasic-R","name"=>"Andika New Basic R", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/antar/antar.ttf","slug"=>"antar","name"=>"antar", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/beefd/Beef'd.ttf","slug"=>"Beef_d","name"=>"Beef'd", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/cabin/Cabin-Bold.ttf","slug"=>"Cabin-Bold","name"=>"Cabin Bold", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/cabin/Cabin-BoldItalic.ttf","slug"=>"Cabin-BoldItalic","name"=>"Cabin Bold Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/cabin/Cabin-Italic.ttf","slug"=>"Cabin-Italic","name"=>"Cabin Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/cabin/Cabin-Medium.ttf","slug"=>"Cabin-Medium","name"=>"Cabin Medium", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/cabin/Cabin-MediumItalic.ttf","slug"=>"Cabin-MediumItalic","name"=>"Cabin Medium Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/cabin/Cabin-Regular.ttf","slug"=>"Cabin-Regular","name"=>"Cabin Regular", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/cabin/Cabin-SemiBold.ttf","slug"=>"Cabin-SemiBold","name"=>"Cabin Semi Bold", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/cabin/Cabin-SemiBoldItalic.ttf","slug"=>"Cabin-SemiBoldItalic","name"=>"Cabin Semi Bold Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/doulos_sil/DoulosSILR.ttf","slug"=>"DoulosSILR","name"=>"Doulos SILR", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/gamma1500/gamma1500.ttf","slug"=>"gamma1500","name"=>"gamma1500", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/gputeks-ttf/Gputeks-Bold.ttf","slug"=>"Gputeks-Bold","name"=>"Gputeks Bold", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/gputeks-ttf/Gputeks-Regular.ttf","slug"=>"Gputeks-Regular","name"=>"Gputeks Regular", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/liberation_sans/LiberationSans-Bold.ttf","slug"=>"LiberationSans-Bold","name"=>"Liberation Sans Bold", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/liberation_sans/LiberationSans-BoldItalic.ttf","slug"=>"LiberationSans-BoldItalic","name"=>"Liberation Sans Bold Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/liberation_sans/LiberationSans-Italic.ttf","slug"=>"LiberationSans-Italic","name"=>"Liberation Sans Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/liberation_sans/LiberationSans-Regular.ttf","slug"=>"LiberationSans-Regular","name"=>"Liberation Sans Regular", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/liberation_serif/LiberationSerif-Bold.ttf","slug"=>"LiberationSerif-Bold","name"=>"Liberation Serif Bold", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/liberation_serif/LiberationSerif-BoldItalic.ttf","slug"=>"LiberationSerif-BoldItalic","name"=>"Liberation Serif Bold Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/liberation_serif/LiberationSerif-Italic.ttf","slug"=>"LiberationSerif-Italic","name"=>"Liberation Serif Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/liberation_serif/LiberationSerif-Regular.ttf","slug"=>"LiberationSerif-Regular","name"=>"Liberation Serif Regular", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/nixie_one/NixieOne.ttf","slug"=>"NixieOne","name"=>"Nixie One", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/obti_sans/Obti Sans - Mac.ttf","slug"=>"Obti Sans - Mac","name"=>"Obti Sans   Mac", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/obti_sans/Obti Sans.ttf","slug"=>"Obti Sans","name"=>"Obti Sans", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-Bold.ttf","slug"=>"Oswald-Bold","name"=>"Oswald Bold", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-BoldItalic.ttf","slug"=>"Oswald-BoldItalic","name"=>"Oswald Bold Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-Demi-BoldItalic.ttf","slug"=>"Oswald-Demi-BoldItalic","name"=>"Oswald Demi Bold Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-DemiBold.ttf","slug"=>"Oswald-DemiBold","name"=>"Oswald Demi Bold", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-Extra-LightItalic.ttf","slug"=>"Oswald-Extra-LightItalic","name"=>"Oswald Extra Light Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-ExtraLight.ttf","slug"=>"Oswald-ExtraLight","name"=>"Oswald Extra Light", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-Heavy.ttf","slug"=>"Oswald-Heavy","name"=>"Oswald Heavy", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-HeavyItalic.ttf","slug"=>"Oswald-HeavyItalic","name"=>"Oswald Heavy Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-Light.ttf","slug"=>"Oswald-Light","name"=>"Oswald Light", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-LightItalic.ttf","slug"=>"Oswald-LightItalic","name"=>"Oswald Light Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-Medium.ttf","slug"=>"Oswald-Medium","name"=>"Oswald Medium", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-MediumItalic.ttf","slug"=>"Oswald-MediumItalic","name"=>"Oswald Medium Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-Regular.ttf","slug"=>"Oswald-Regular","name"=>"Oswald Regular", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-RegularItalic.ttf","slug"=>"Oswald-RegularItalic","name"=>"Oswald Regular Italic", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/oswald-ttf/Oswald-Stencil.ttf","slug"=>"Oswald-Stencil","name"=>"Oswald Stencil", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/porter_sans_block/porter-sans-inline-block-webfont.ttf","slug"=>"porter-sans-inline-block-webfont","name"=>"porter sans inline block webfont", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/porter_sans_block/porter-sans-inline-block.ttf","slug"=>"porter-sans-inline-block","name"=>"porter sans inline block", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/russo_one/Russo_One.ttf","slug"=>"Russo_One","name"=>"Russo One", "default"=>"0"),
			array("path"=>WBG_FONTS_PATH."/six-caps-ttf/SixCaps.ttf","slug"=>"SixCaps","name"=>"Six Caps", "default"=>"0"),

            ));
    }


    /**
     * @return mixed
     */
    public function getFonts()
    {
        return $this->fonts;
    }

    /**
     * @param mixed $fonts
     */
    public function setFonts($fonts)
    {
        $this->fonts = $fonts;
    }


    /**
     * @param $name
     * @param null $value
     * @return string
     */
    public function getSelect($name, $value = NULL)
    {
        $return = '';
        $options = $this->getFonts();
        $i = 1;
        foreach ($options as $k => $v) {

            if ($value == $v['slug']) {
                $selected = 'selected';
            } else if ($value === NULL && $v['default'] === '1') {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $return .= sprintf('<option value="%s" %s/>%s</option>', esc_attr($v['slug']), $selected, esc_attr($v['name']));
            $i++;
        }
        return  sprintf('<select name="%s">%s</select>', esc_attr($name), $return);
    }

    /**
     * @return array
     */
    public function getSlugs()
    {
        $return = array();
        $options = $this->getFonts();
        foreach ($options as $k => $v) {
            $return[] = $v['slug'];
        }
        return $return;
    }

}