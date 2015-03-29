<?php

require_once 'config.php';
require_once 'locale.php';
require_once 'fileStore.php';
require_once 'engine.php';
//~ require_once 'app.php';

class TellMuch {
	
	public static $instance; 
	public $text;
	public $context;
	public $engine;
	
	public static function init() {
		if(!self::$instance) {
			self::$instance = new self;
			Locale::init();
			FileStore::init();
		}		
		return self::$instance;
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
	$hash = crc32($text);	
	echo FileStore::getByHash($hash);
}




