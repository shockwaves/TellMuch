<?php

require_once '../loader.php';

App::setup()
        ->setLocales(array('en', 'ru'))
        ->setTo('en')
        ->setFrom('ru')
        ->setEngine('yandex');

header('Content-Type: text/html; charset=utf-8');

txt('Первые попытки создания кодов с избыточной информацией начались задолго до появления современных  ПК.');

