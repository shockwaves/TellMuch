<?php

class Yandex extends Engine {

    public function init() {
        $this->params['lang'] = sprintf('%s-%s', parent::$origin, parent::$target);
        $this->params['text'] = parent::$text;
    }

    public function getText($response = '') {     
        $data = json_decode($response);
        if($data->code != 200) {
            return self::$text;
//            die('Yandex::' . $data->message. ' from '.self::$from.' to '.self::$to);
        }
        return urldecode($data->text[0]);
    }

}
