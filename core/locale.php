<?php

class Locale extends App {

    private static $settings;

    protected static function getSettings($item = '') {
        global $_config;
        self::$settings = (object) $_config['locale'];
        return ($item) ? self::$settings->{$item} : self::$settings;
    }

    public static function init() {
        $config = self::getSettings();
        if (!parent::$to) {
            if (isset($config->envLocaleKey)) {             
                self::$to = self::getEnvLocaleByKey($config->envLocaleKey);
            } else {
                self::$to = $config->to;
            }
        }
        if (!parent::$from) {
            parent::$from = $config->from;
        }
    }

    public static function getEnvLocaleByKey($name = '') {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        } elseif (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        } elseif (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            return self::getSettings('to');
        }
    }

}
