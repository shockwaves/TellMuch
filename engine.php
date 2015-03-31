<?php

class Engine {

    private static $current;
    private static $settings;
//    common
    public static $to;
    public static $from;
    public static $text;
    protected static $requestUrl;
//    engines config
    protected $url;
    protected $options;
    protected $params;

    protected static function getSettings($item = '') {
        global $config;
        self::$settings = $config['engine'];
        return ($item) ? (object) self::$settings[$item] : self::$settings;
    }

    public static function setup() {
        foreach (self::getSettings() as $name => $settings) {
            if ($settings['enable']) {
                return self::mount($name);
            }
        }
    }

    public static function mount($name = '') {
        if (ucwords($name) == get_class(self::$current)) {
            return self::$current;
        }

        require_once sprintf('engines/%s.php', $name);
        $engine = new $name;
        $config = self::getSettings($name);
        $engine->setUrl($config->url)
                ->setOptions($config->options)
                ->setParams($config->params);
        self::$current = $engine;
        self::setTo(Locale::$to);
        return self::$current;
    }

    public function setText($text = '') {
        self::$text = $text;
        return self::$current;
    }

    public function setTo($locale = '') {
        self::$to = $locale;
        return self::$current;
    }

    public function setFrom($locale = '') {
        self::$from = $locale;
        return self::$current;
    }

    public function run() {
        $this->init();
        $this->prepareUrl();
        $result = self::sendRequest();
        return $this->getText($result);
    }

    protected function setUrl($url = '') {
        $this->url = $url;
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

    protected function prepareUrl() {
        $data = $this->options + $this->params;
        $uri = http_build_query($data);
        $url = $this->url.$uri;
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
