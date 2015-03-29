<?php

$config = array(
	'mode' => 'auto',
	'locale' => array(
		'origin' => 'ru',
		'target' => 'ru',
		'saveTargetInSession' => array(
			'enable' => false,
			'name' => 'locale'
		),
		'range' => array(
			'ru',
			'en'
		),
	),
	'engines' => array(
		'yandex' => array(
			'enable' => true,
			'url' => 'https://translate.yandex.net/api/v1.5/tr.json/translate?',
			'options' => array(
				'format' => 'html',
				'key' => 'trnsl.1.1.20140516T140150Z.44787891172415b0.9826e039a94b3dbee01c686d828a2e06d41db73f'
			),
			'params' => array(
				'text',
				'lang'
			)
		),
		'google' => array(
			'enable' => true,
			'url' => 'http://translate.google.ru/translate_a/t?',
			'options' => array(
				'format' => 'html',
				'client' => 'x',
				'oe' => 'UTF-8',
				'ie' => 'UTF-8',
			),
			'params' => array(
				'text',
				'sl',
				'tl',
			)
		),
	),
);
