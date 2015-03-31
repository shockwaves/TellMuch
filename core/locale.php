<?php

class Locale extends App {

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
    }
}
