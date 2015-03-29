<?php

class App {
	
	public $locale;
	public $store;
	public $engine;
	
	public function __construct() {
		$this->locale = new Locale;
		$this->store = new Store;
	}
	
}
