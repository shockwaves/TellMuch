<?php

class App {

    private static $instance;
    private static $settings;
    public $isLoad;
    public static $text;
    public static $from;
    public static $to;

    protected static function loadSettings() {
        global $_config;
        self::$settings = (object) $_config['app'];
    }

    protected static function getSettings($item = '') {
        return ($item) ? (object) self::$settings->$item : (object) self::$settings;
    }

//    public static function init() {
//        return self::setup();
//    }

    public static function setup() {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public static function load() {
        if (self::$instance->isLoad) {
            return self::$instance;
        }

        self::loadSettings();
        Locale::init();
        Store::init();
        self::$instance->isLoad = true;
        return self::$instance;
    }

    public function envLocaleKey($name = '') {
        $locale = Locale::getEnvLocaleByKey($name);
        self::$to = $locale;
        return $this;
    }

    public function setTo($locale = '') {
        self::$to = $locale;
        return $this;
    }

    public function setFrom($locale = '') {
        self::$from = $locale;
        return $this;
    }

    public function setText($text = '') {
        self::$text = $text;
        return $this;
    }

    public function setEngine($name = '') {
        Engine::mount($name);
        return $this;
    }

    public function run() {
        return Engine::load()->run();
    }

    public function getResult($text = '') {
        if (TRUE === self::getSettings('enableForceTranslate')) {
            return FALSE;
        }
        $hash = Store::getHashByText($text);
        return Store::getTextByHash($hash);
    }

}
