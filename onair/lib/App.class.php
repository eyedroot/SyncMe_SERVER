<?php

namespace onair\lib;

/**
 * 앱을 관리하는 총괄 클래스
 */
class App {

    /**
     * 설정 파일이 담기는 배열
     *
     * @var array
     */
    private $configuration = [];

    /**
     * App 클래스의 생성자
     *
     * @param string $key
     */
    function __construct(string $key) {
        $fileConfiguration = ROOT_PATH . '../app.cfg.php';

        if ( \file_exists($fileConfiguration) ) {
            $this->configuration = require $fileConfiguration;
        }

        $key = trim($key);

        if ($key) {
            if (\array_key_exists($key, $this->configuration)) {
                return $this->configuration[ $key ];
            }

            return false;
        }
    }

    /**
     * Method POST
     *
     * @param string $path
     * @param |\Closure $router
     * @return void
     */
    static function POST(string $path = '/', \Closure $router) {
        if ($_SERVER['HTTP_METHOD'] === 'POST' && static::__capture($path) && is_callable($router)) {
            $router();
        }
    }

    /**
     * __capturePath
     *
     * @return bool
     */
    static function __capture(string $path) : bool {
        return true;
    }
}