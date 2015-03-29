<?php

class Engine {

    private static $current;
    public static $text;
    protected $url;
    protected $options;
    protected $params;
    
    public static function init() {
        global $config;
        foreach ($config['engine'] as $name => $settings) {
            if ($settings['enable']) {
                require_once sprintf('engines/%s.php', $name);
                $engine = new $name;
                $engine->setUrl($settings['url'])
                        ->setOptions($settings['options'])
                        ->setParams($settings['params']);
                self::$current = $engine;
//                self::setUrl($settings['url']);
//                self::setOptions($settings['options']);
//                self::setParams($settings['params']);
                return self::$current;
            }
        }
    }

    public static function setText($text = '') {
        self::$text = $text;
        return $this;
    }

    public function setUrl($url = '') {
        $this->url = $url;
        return $this;
    }

    public function setOptions($options = array()) {
        $this->options = $options;
        return $this;
    }

    public function setParams($params = array()) {
        $this->params = $params;
        return $this;
    }

}
