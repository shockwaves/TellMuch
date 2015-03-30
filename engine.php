<?php

class Engine {

    private static $current;
    private static $settings;
//    common
    public static $to;
    public static $from;
    public static $text;
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
        if(ucwords($name) == get_class(self::$current)) {
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
        return $this->run();
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
        $this->params = $params;
        return $this;
    }

    public function send_through_curl($url = '', $params = FALSE, $secure = FALSE) {
        $curl = curl_init();
        // склеиваем урл если это массив
        if (is_array($url)) {
            $url = implode($url);
        }

        // уcтанавливаем проверку SSL если secure=true
        if (!$secure) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        // уcтанавливаем заголовки если такие нужны
        if (isset($params['headers'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $params['headers']);
        }
        // передаем данные по методу post    
        if (isset($params['post'])) {
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params['post']);
        }
        // уcтанавливаем урл, к которому обратимся
        curl_setopt($curl, CURLOPT_URL, $url);
        // максимальное время выполнения скрипта
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        // теперь curl вернет нам ответ, а не выведет
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

}
