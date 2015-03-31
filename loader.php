<?php

require_once 'core/config.php';
require_once 'core/app.php';
require_once 'core/locale.php';
require_once 'core/store.php';
require_once 'core/engine.php';

App::setup();

function txt($text = '') {
    App::load();    
    $hash = Store::getHashByText($text);
    $result = Store::getTextByHash($hash);
    if (!$result) {
        $result = Engine::setup()
                ->setText($text)
                ->run();
        
        Store::update($hash, $result);      
    }
    echo $result;
}
