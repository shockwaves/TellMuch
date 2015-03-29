<?php

class Locale {
	
	public static $target;
	public static $origin;
	public static $range;
		
	public static function setTarget($locale = '') {
		self::$target = $locale;
	}
	
	public static function setOrigin($locale = '') {
		self::$origin = $locale;
	}
	
	public static function setRange($range = '') {
		self::$range = $range;
	}
	
	public static function init() {
		global $config;
		$settings = $config['locale'];
		self::setTarget($settings['target']);
		self::setOrigin($settings['origin']);
		self::setRange($settings['range']);
		if($settings['saveTargetInSession']['enable']) {
			if(isset($_SESSION[$settings['saveTargetInSession']['name']])) {
				$target = $_SESSION[$settings['saveTargetInSession']['name']];
				self::setTarget($target);
			}
		}
	}
	
}
