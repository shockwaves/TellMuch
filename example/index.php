<?php

require_once '../loader.php';

TellMuch::init()
	->setLocales(array('en', 'ru'))
	->setTargetLocale('ru')
	->setOriginLocale('ru')
	->setEngine('yandex');
	
header('Content-Type: text/html; charset=utf-8');

txt('Первые попытки создания кодов с избыточной информацией начались задолго до появления современных ПК.');

