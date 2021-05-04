<?php

namespace Appkita;

class Auth {
    private $path = APPPATH  .'Appkita\Auth';
    private $class = null;
    protected $JWT_KEY = '12MlopSLMnouxbqzK&%';
	protected $JWT_AUD = 'https://app-kita.net';
	protected $JWT_ISS = 'https://app-kita.com';
	protected $JWT_TIMEOUT = 3600;
	protected $JWT_USERNAME = 'email';
	protected $COLOUMN_USERNAME = 'email';
	protected $COLOUMN_PASSWORD = 'password';
	protected $COLOUMN_KEY = 'key';


	protected $API_KEY = 'X-API-KEY';
    function __construct($config = null) {
        if (is_array($config)) {
            $config = (object) $config;
        }
        if (!empty($config)){
            if (is_object($config)) {
                foreach($config as $key => $value) {
                    if (isset($this->{$key})) {
                        $this->{$key} = $value;
                    }
                }
            }
        }
        $this->cls = (object)[];
        $this->autoload();
    }

    private function ceklogin($class) {
        $class = strtolower($class);
        $class = ucwords($class);
        if (!class_exists($class)) {
            set_error_handler(function ($servirity, $class) {
                 throw new \ErrorException('Class not found or '. $class, 0, $servirity, __DIR__.DIRECTORY_SEPARATOR.'Auth.php', 22);
            });
        }
        $cls = new $class();
        $cls->init((object)[
            'JWT_KEY' => $this->JWT_KEY,
            'JWT_AUD' => $this->JWT_AUD,
            'JWT_ISS' => $this->JWT_ISS,
            'JWT_TIMEOUT' => $this->JWT_TIMEOUT,
            'JWT_USERNAME' => $this->JWT_USERNAME,
            'COLOUMN_USERNAME' => $this->COLOUMN_USERNAME,
            'COLOUMN_PASSWORD' => $this->COLOUMN_PASSWORD,
            'COLOUMN_KEY' => $this->COLOUMN_KEY,
            'API_KEY' => $this->API_KEY,
            'userMdl' => $this->userMdl
        ]);
        $user = $cls->decode();
        if (!$user) {
            return false;
        }
        $this->user = $user;
    }

    private function authentication($auth) {
        if (!$auth || empty($auth) || (is_array($auth) && sizeof($auth) < 1)) return true;
        if (is_array($auth)) {
            $user = null;
            for($i = 0; $i < sizeof($auth); $i++) {
                if ($user = $this->ceklogin($auth[$i])) {
                    return $user;
                    break;
                }
            }
            if (!empty($user)) {
                return $user;
            }else {
                return $this->failerror();
            }
        } else {
            if (!empty(auth)){
                if ($user = $this->ceklogin($auth)) {
                    return $user;
                } else {
                    return $this->failerror();
                }
            }
        }
    }

    private function autoload() {
        spl_autoload_register(function ($class) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $this->path . DIRECTORY_SEPARATOR.$class).'.php';
            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
}