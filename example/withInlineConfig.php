<?php

require_once '../loader.php';

App::setup()
        ->setTo('uk')
        ->setFrom('ru');

header('Content-Type: text/html; charset=utf-8');

txt('Первые попытки создания кодов с избыточной информацией начались задолго до появления современных  ПК.');
txt('Это первая рабочая версия супер переводчика.');

