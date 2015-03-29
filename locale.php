<?php

class Locale {

    public static $target;
    public static $origin;
    public static $range;
    public static $sessionTargetName = 'locale';

    public static function init() {
        global $config;
        $settings = $config['locale'];
        if(!self::$target) {
            self::setTarget($settings['target']);
        }
        if(!self::$origin) {
            self::setOrigin($settings['origin']);
        }
        if(!self::$range) {
            self::setRange($settings['range']);
        }
        
//        if ($settings['saveTargetInSession']) {
//            session_start();
//            if (!isset($_SESSION[self::$sessionTargetName])) {
//                $_SESSION[self::$sessionTargetName] = self::$target;
//            }
//            $target = $_SESSION[self::$sessionTargetName];
//            self::setTarget($target);
//        }
    }

    public static function setTarget($locale = '') {
        self::$target = $locale;
    }

    public static function setOrigin($locale = '') {
        self::$origin = $locale;
    }

    public static function setRange($range = '') {
        self::$range = $range;
    }
    
    public function getTarget() {
        return self::$target;
    }

}
