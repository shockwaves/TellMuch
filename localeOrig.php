<?php

class Locale {
	
	public $target;
	public $origin;
	public $range;
	
	public function __construct() {
		$this->init();
	}
	
	public function setTarget($locale = '') {
		$this->target = $locale;
	}
	
	public function init() {
		global $config;
		$settings = $config['locale'];
		$this->target = $settings['target'];
		$this->origin = $settings['origin'];
		$this->range = $settings['range'];
		if($settings['saveTargetInSession']['enable']) {
			if(isset($_SESSION[$settings['saveTargetInSession']['name']])) {
				$target = $_SESSION[$settings['saveTargetInSession']['name']];
				$this->setTarget($target);
			}
		}
	}
	
}
