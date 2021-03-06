<?php

class Key 
{
    
    private $API_KEY = 'X-API-KEY';
    private $COLOUMN_KEY = 'key';
    private $userMdl= null;

    public function init(object $config) {
        foreach($config as $key => $value) {
            if (isset($this->{$key})) {
                $this->{$key} = $value;
            }
        }
    }

    public function decode() {
        $keyname = str_replace(' ', '', $this->API_KEY);
        $keyname = strtoupper(str_replace('-', '_', $keyname));
        if (!isset($_SERVER['HTTP_'. $keyname])) {
            return false;
        }
        if (empty($_SERVER['HTTP_'. $keyname])) {
            return false;
        }
        $key = $_SERVER['HTTP_'. $keyname];
        if ($cek = $this->userMdl->asObject()->where($this->COLOUMN_KEY, $key)->get()) {
            return $cek;
        } else {
             return $this->failerror();
        }
    }
}