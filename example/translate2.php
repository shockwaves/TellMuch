<?php

class Translate {

	public $limit = 995;
	public $range = 0;
	public $current;
	public $translate;
	public $service = 'yandex';
	public $from = 'ru';
	public $to = 'en';

	public $code_map = array(
		'ua'=>'uk',
		'gr'=>'el',
		'cn'=>'zh-CN',
		'ae'=>'ar',
		'cz'=>'cs'
	);

	public $locales = array(
		'en',
		'ru',
		'ua'
	);
	
	private $yandex = array(
		'url' => 'https://translate.yandex.net/api/v1.5/tr.json/translate?',
		'key' => '&key=trnsl.1.1.20140516T140150Z.44787891172415b0.9826e039a94b3dbee01c686d828a2e06d41db73f'
	);
	private $google = array(
		'url' => 'http://translate.google.ru/translate_a/t?'
	);
		
	static function create() {
		return new self;
	}

	private function inCodeMap($code)
	{
		if(isset($this->code_map[$code]))
			return $this->code_map[$code];
		return $code;
	}

	function __construct() {}
	
	function setLocales($langs) {
		$this->locales = $langs;
		return $this;
	}
	
	function setService($name) {
		$this->service = $name;
		return $this;
	}
	
	function from($from = 'ru') {
		$this->from = $from;
		return $this;
	}
	
	function to($to = 'en') {
		$this->to = $to;
		return $this;
	}

    function gets($text = '') {
        if (empty($text)) {
			return false;
		}
		
		$text = trim(preg_replace('/\s+/', ' ', $text));
        $store = array();
        
        if (is_array($this->to)) {
            if (!empty($this->to)) {
				$locales = $this->to;
			} else {
				$locales = $this->locales;		
			}
                                      
            foreach ($locales as $index => $locale) {
				$code = $this->inCodeMap($locale);
                $store[$locale] = $this->{$this->service}($text, $code);
			}
        } else {
			$store[$this->to] = $this->{$this->service}($text, $this->to);
		}
        return $store;
    }
        
    function get($text = '')
    {
		$range = 0;
		$translate = '';
		do {
			$current = mb_substr($text, $range, $this->limit);	
			$limit = (strripos($current, '. ')) ? strripos($current, '. ') : $this->limit;
			$current = mb_substr($current, 0, $limit);			
			$translate .= $this->{$this->service}($current, $this->to);		
			$range += $limit + 1;
		} 
		while(strlen($text) > $range);
		return $translate;
	}
	
    function google($text = '', $to = 'en') {
        $query = array(
            'url' 		=> $this->google['url'],
            'details' 	=> '&client=x&ie=UTF-8&oe=UTF-8&format=html',
            'params' 	=> '&text='.urlencode($text).'&sl='.$this->from.'&tl='.$to,
        );
        $response = $this->send_through_curl($query);
        $response = json_decode($response);
		$response = $response->sentences[0]->trans;
		$response = str_replace('% s', '%s', $response);
        return $response;
    }

    function yandex($text = '', $to = 'en') {
        $query = array(
            'url' 		=> $this->yandex['url'],
            'details' 	=> '&format=html&key='.$this->yandex['key'],
            'params' 	=> '&text='.urlencode($text).'&lang='.$this->from.'-'.$to,
        );
				
        $response = $this->send_through_curl($query);
        $response = json_decode($response);      
        if($response->code == 403) die('YandexTranslate::'.$response->message);		  
		return $response->text[0];
    }
    
    function send_through_curl($url = '', $params = FALSE, $secure = FALSE) {
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
		// уcтанавливаем урл, к которому обратимс€
		curl_setopt($curl, CURLOPT_URL, $url);    
		// максимальное врем€ выполнени€ скрипта
		curl_setopt($curl, CURLOPT_TIMEOUT, 20);   
		// теперь curl вернет нам ответ, а не выведет
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);      
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}
}


//~ $translate = Translate::create()
	//~ ->get('фальшивые герои зерна не пр€чут');

/*$translate = Translate::create()
	->from('ru')
	->to(array('ru', 'ua', 'de', 'es', 'cn', 'fr', 'it', 'ae', 'th', 'id', 'my', 'tr', 'pl', 'cz', 'gr'))
	->setService('google')
	->gets('фальшивые герои зерна не пр€чут');*/
	
//print_r($translate);
