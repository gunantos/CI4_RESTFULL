<?php

namespace Config;

use App\Models\RouterModel;
use CodeIgniter\Config\BaseService;

class Rest extends BaseService
{
	private static $JWT_KEY = null;

    private static $config = 'database';
    private static $setting = [
        'db_group'  => 'api',
        'table'     => 'router'
    ];
    private static $url = [
        [
            'path' => '',
            'method'=> '',
            'controller'=>'',
            'filter'=>'',
            'authentication'=>''
        ]
    ];

	public static function getSecretKey(){
		return empty(Rest::$JWT_KEY) ? getenv('JWT_SECRET_KEY') : Rest::$JWT_KEY;
	}

    public static function config() {
        return empty(Rest::$config) ? getenv('REST_CONFIG') : Rest::$config;
    }

    private static function url_file($path = null, $method = null) {
        if (!empty($path)) {
            if (empty($method)) {
                $method = 'get';
            }
            foreach(Rest::$url as $key) {
               if (\strtolower($key->path) == \strtolower($path) && \strtolower($key->method) == \strtolower($method)) {
                   return $key;
               }   
            }
            return null;
        } else {
            return Rest::$url;
        }
    }

    private static function url_db($path = null, $method = null) {
        
        $routerModel = new RouterModel();
        if (!empty($path)) {
            if (empty($method)) {
                $method = 'get';
            }
            return $routerModel->where('LOWER(path)', \strtolower($path))->where('LOWER(method)', \strtolower($method))->first();
        } else {
            return $routerModel->findAll();
        }
    }

    public static function url($path=null, $method = null) {
        return (Rest::config() == 'database' ? Rest::url_db($path, $method) : Rest::url_file($path, $method));
    }
}
