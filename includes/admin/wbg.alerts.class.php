<?php

/**
 * @package banner-generator-for-woocommerce
 */
class wbgAlerts
{
    /**
     * @var array
     */
    private static $queue = array();

    /**
     * @var
     */
    private static $instance;


    /**
     *
     */
    private function __construct()
    {
    }

    /**
     * @param $data
     */
    private static function updateQueue($data)
    {
        $data = apply_filters('wbg_alerts_update_queue', $data);
        self::$queue[] = $data;
    }


    /**
     * @param array $args
     * @return mixed|string|void
     */
    public static function getAlertsJson($args = array())
    {
        return json_encode(self::$queue);
    }


    /**
     * @param array $args
     * @return string
     */
    public static function getAlertsHtml($args = array())
    {
        return implode("\n", self::$queue);
    }

    /**
     * @param array $args
     */
    public static function alertsHtml($args = array())
    {
        $output = implode("\n", self::$queue);
        echo wp_kses($output, array(
            'a' => array(
                'href' => array(),
                'title' => array()
            ),
            'button' => array(
                'type' => array(),
                'class' => array(),
                'data-dismiss' => array(),
                'aria-hidden' => array(),
            ),
            'div' => array(
                'class' => array()
            )
        ));

    }

    /**
     * @return wbgAlerts
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    public function getAnimatedClassSuccess()
    {
        return 'fadeIn';
    }

    /**
     * @return string
     */
    public function getAnimatedClassInfo()
    {
        return 'fadeIn';
    }

    /**
     * @return string
     */
    public function getAnimatedClassWarning()
    {
        return 'shake';
    }

    /**
     * @return string
     */
    public function getAnimatedClassDanger()
    {
        return 'shake';
    }

    /**
     * @param $message
     * @param string $type
     * @param bool|false $dismissable
     * @param string $class
     * @param $link
     * @param $linkName
     */
    public function addAlertSimple($message, $type = '', $dismissable = false, $animated = true, $link, $linkName, $class = '')
    {
        $message = apply_filters('wbg_alerts_add_alert_simple_msg', $message);
        $class = apply_filters('wbg_alerts_add_alert_simple_class', $class);
        if (in_array($type, array('success', 'info', 'danger', 'warning'))) {
            self::updateQueue($this->getSimpleTemplate($message, $type, $dismissable, $animated, $link, $linkName, $class));
        }
    }


    /**
     * @param $message
     * @param $type
     * @param $dismissable
     * @param string $link
     * @param $linkName
     * @param string $class
     * @return mixed
     */
    private function getSimpleTemplate($message, $type, $dismissable, $animated, $link = '', $linkName, $class = '')
    {
        if ($animated === TRUE) {
            $class = 'animated';

            switch ($type) {
                case 'success':
                    $class .= ' ' . $this->getAnimatedClassSuccess();
                    break;

                case 'info':
                    $class .= ' ' . $this->getAnimatedClassInfo();
                    break;
                case 'warning':
                    $class .= ' ' . $this->getAnimatedClassWarning();
                    break;

                case 'danger':
                    $class .= ' ' . $this->getAnimatedClassDanger();
                    break;
            }

        }
        $linkHtml = $link !== '' ? wbg_replace(array('%link%'), array($link, $linkName), '<a href="%link%">%link_name%</a>') : '';
        $message = str_replace(array('%link%'), array($linkHtml), $message);
        $class = $class !== '' ? ' ' . $class : '';
        $dismissableHtml = $dismissable ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' : '';


        return str_replace(
            array('%type%', '%class%', '%msg%', '%dis%', '%fade%'),
            array($type, $class, $message, $dismissableHtml),
            '<div class="wbg-alert wbg-alert-%type%%class%">%dis%%msg%</div>');

    }


    /**
     * @return bool
     */
    public static function hasAlert()
    {
        return !empty(self::$queue);
    }


}