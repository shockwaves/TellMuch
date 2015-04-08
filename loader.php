<?php

require_once 'core/config.php';
require_once 'core/app.php';
require_once 'core/locale.php';
require_once 'core/store.php';
require_once 'core/engine.php';

App::setup();

function txt($text = '') { 
    $result = App::load()->getResult($text);
    if (!$result) {
        $result = Engine::setup()
                ->setText($text)
                ->run();     
        Store::updateByOrigin($text, $result);      
    }
    return $result;
}
