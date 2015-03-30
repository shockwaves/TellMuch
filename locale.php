<?php

class Locale {

    public static $to;
    public static $from;
    public static $range;
    public static $sessionToName = 'locale';

    protected static function getSettings() {
        global $config;
        return (object) $config['locale'];
    }

    public static function init() {
        $config = self::getSettings();
        if (!self::$to) {
            self::setTo($config->to);
        }
        if (!self::$from) {
            self::setFrom($config->from);
        }
        if (!self::$range) {
            self::setRange($config->range);
        }

//        if ($config->saveToInSession) {
//            session_start();
//            $name = self::$sessionTargetName;
//            if (!isset($_SESSION[$name])) {
//                $_SESSION[$name] = self::$to;
//            }
//            $locale = $_SESSION[$name];
//            self::setTo($locale);
//        }
    }

    public static function setTo($locale = '') {
        self::$to = $locale;
    }

    public static function setFrom($locale = '') {
        self::$from = $locale;
    }

    public static function setRange($range = '') {
        self::$range = $range;
    }

    public static function getTo() {
        return self::$to;
    }

}
