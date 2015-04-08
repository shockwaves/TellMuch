<?php

class Engine extends App {

    private static $current;
    private static $settings;
    protected static $requestUrl;
    public static $path = 'engines';
    protected $url = '';
    protected $options = array();
    protected $params = array();
//    additional
    protected $map;
    public static $origin;
    public static $target;

    protected static function getSettings($item = '') {
        global $_config;
        self::$settings = (object) $_config['engine'];
        return ($item) ? (object) self::$settings->$item : (object) self::$settings;
    }

    public static function load() {
        return self::$current;
    }

    public function init() {
        return self::$current;
    }

    public static function create($name) {
        require_once sprintf('%s/%s/%s.php', dirname(__FILE__) . '/..', self::$path, $name);
        return new $name;
    }

    public static function setup() {
        foreach (self::getSettings() as $name => $settings) {
            if ($settings['enable']) {
                return self::mount($name);
            }
        }

        self::$current = new self;
        return self::$current;
    }

    public static function mount($name = '') {
        if (ucwords($name) == get_class(self::$current)) {
            return self::$current;
        }
              
        $config = self::getSettings($name);
        $engine = self::create($name)
                ->setUrl($config->url)
                ->setOptions($config->options)
                ->setParams($config->params);
        
        if(isset($config->map)) {
            $engine->setMap($config->map);
        }
        
        self::$current = $engine;
        return self::$current;
    }

    public function getText($response = '') {
        return self::$text;
    }

    public function run() {
        $this->setDirections();
        $this->init();
//        $this->hasMapDecode();
        $this->prepareRequestUrl();
        $result = self::sendRequest();
        return $this->getText($result);
    }
    
    public function setDirections() {     
        self::$origin = self::$from;  
        self::$target = self::$to;
        if($this->map) {
            if(isset($this->map[self::$to])) {
                self::$target = $this->map[self::$to];
            }
        }
        
    }

    protected function setUrl($url = '') {
        $this->url = $url;
        return $this;
    }

    protected function setMap($map = '') {
        $this->map = $map;
        return $this;
    }

    protected function setOptions($options = array()) {
        $this->options = $options;
        return $this;
    }

    protected function setParams($params = array()) {
        $this->params = array_flip($params);
        return $this;
    }

    protected function prepareRequestUrl() {
        $data = $this->options + $this->params;
        $uri = http_build_query($data);
        $url = $this->url . $uri;
        self::$requestUrl = $url;
    }

    public function sendRequest() {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_URL, self::$requestUrl);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

}
