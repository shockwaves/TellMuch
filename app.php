<?php

class App {

    private static $instance;
    public $isLoad;
    public $text;
    public $context;
    public $engine;

    public static function setup() {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public static function load() {
        if(self::$instance->isLoad) {
            return FALSE;
        }
        
        Locale::init();
        FileStore::init();
        self::$instance->isLoad = true;
    }

    public function setLocales($range = array()) {
        Locale::setRange($range);
        return $this;
    }

    public function setTo($locale = '') {
        Locale::setTo($locale);
        return $this;
    }

    public function setFrom($locale = '') {
        Locale::setFrom($locale);
        return $this;
    }

    public function setEngine($engine = '') {
        $this->engine = $engine;
        return $this;
    }

    public function setText($text = '') {
        $this->text = $text;
        return $this;
    }

    public function setContext($context = '') {
        $this->context = $context;
        return $this;
    }

    public function run() {
        return;
    }

}

