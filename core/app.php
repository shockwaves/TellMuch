<?php

class App {

    private static $instance;
    public $isLoad;
    public static $text;
    public static $from;
    public static $to;

    public static function setup() {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public static function load() {
        if (self::$instance->isLoad) {
            return FALSE;
        }
        Locale::init();
        Store::init();
        self::$instance->isLoad = true;
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

}
