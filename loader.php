<?php

require_once 'config.php';
require_once 'locale.php';
require_once 'fileStore.php';
require_once 'engine.php';
require_once 'app.php';
App::setup();

function txt($text = '') {
    App::load();
    $hash = FileStore::getHashByText($text);
    $result = FileStore::getTextByHash($hash);

    if (!$result) {
        $result = Engine::setup()
                ->mount('yandex')
                ->setText($text)
                ->setFrom(Locale::$from)
                ->setTo(Locale::$to)
                ->run();
        
        $store = FileStore::getAll();      
        $store[$hash] = $result;
        FileStore::rewrite($store);      
    }

    echo $result;
}
