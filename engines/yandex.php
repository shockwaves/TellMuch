<?php

class Yandex extends Engine {

    public function init() {
        $this->params['lang'] = sprintf('%s-%s', parent::$from, parent::$to);
        $this->params['text'] = parent::$text;
    }

    public function getText($response) {     
        $data = json_decode($response);
        if($data->code != 200) {
            die('Yandex::' . $data->message);
        }
        return urldecode($data->text[0]);
    }

}
