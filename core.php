<?php

require_once 'config.php';
require_once 'locale.php';
require_once 'fileStore.php';
require_once 'engine.php';

//~ require_once 'app.php';

class TellMuch {

    private static $instance;
    public $isInit = true;
    public $text;
    public $context;
    public $engine;

    public static function create() {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public static function init() {
        if(self::$instance->isInit) {
            return FALSE;
        }
        
        Locale::init();
        FileStore::init(Locale::$target);
    }

    public function setLocales($range = array()) {
        Locale::setRange($range);
        return $this;
    }

    public function setTargetLocale($locale = '') {
        Locale::setTarget($locale);
        return $this;
    }

    public function setOriginLocale($locale = '') {
        Locale::setOrigin($locale);
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

function txt($text = '') {
    TellMuch::init();
    print_r(Locale::getTarget());
    $hash = crc32($text);
    $result = FileStore::getByHash($hash);

    if (!$result) {
        $engine = Engine::init()
                ->setText($text);
//        print_r($engine::$text);
    }
//    echo $result;
}
