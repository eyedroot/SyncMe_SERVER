<?php

namespace onair\lib;

/**
 * 앱을 관리하는 총괄 클래스
 */
class App
{
    /**
     * 설정 파일이 담기는 배열
     *
     * @var array
     */
    private $configuration = [];

    /**
     * 현재의 requestUri
     *
     * @var string
     */
    static $requestUri;

    /**
     * Http 리스트 목록
     */
    static $supportHttpMethod = ['GET', 'POST', 'HEAD', 'OPTIONS', 'PUT', 'DELETE', 'TRACE', 'CONNECT'];

    /**
     * App 클래스의 생성자
     *
     * @param string $key
     */
    function __construct(string $key) {
        // 루트 홈 디렉토리의 상위 디렉토리안 app.cfg.php 파일을 생성해준다
        // 환경변수를 다루기 위함이며, 노출되서는 안되는 값들을 넣어서 사용할 수 있다
        // 예를 들어 구글API의 키 값이라던지 ..
        // 호출은 \app('KEY_NAME'); 으로 할 수 있다
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

        // 모든 URL의 끝은 '/'로 끝나야 한다
        // 예를 들어 `localhost/a`의 주소는 `localhost/a/`로 이동되어야 한다
        static::$requestUri = rtrim($_SERVER['REQUEST_URI'], '\/') . '/';
    }

    /**
     * __capturePath
     *
     * @return bool
     */
    private static function __capturePath(string $path) : bool {
        $path = rtrim($path, '\/') . '/';

        if (static::$requestUri == $path) {
            return true;
        }

        return false;
    }

    /**
     * Http 요청을 실행하기 위한 메서드 
     *
     * @param string GET|POST|HEAD|OPTIONS|PUT|DELETE|TRACE|CONNECT
     * @param \Closure $router
     * @return void
     */
    private static function __http(string $method, string $path, \Closure $router) : void {
        if ($_SERVER['REQUEST_METHOD'] === $method && static::__capturePath($path)) {
            if (is_callable($router)) {
                $entityBody = file_get_contents('php://input');

                $router( $entityBody );
            }
        }
    }

    /**
     * Method POST
     *
     * @param string $path
     * @param \Closure $router
     * @return void
     */
    static function POST(string $path = '/', \Closure $router) : void {
        static::__http('POST', $path, $router);
    }

    /**
     * Method GET
     *
     * @param string $path
     * @param \Closure $router
     * @return void
     */
    static function GET(string $path = '/', \Closure $router) : void {
        static::__http('GET', $path, $router);
    }

    /**
     * Method PUT
     *
     * @param string $path
     * @param \Closure $router
     * @return void
     */
    static function PUT(string $path = '/', \Closure $router) : void {
        static::__http('PUT', $path, $router);
    }

    /**
     * Method DELETE
     *
     * @param string $path
     * @param \Closure $router
     * @return void
     */
    static function DELETE(string $path = '/', \Closure $router) : void {
        static::__http('DELETE', $path, $router);
    }

    /**
     * Method HEAD
     *
     * @param string $path
     * @param \Closure $router
     * @return void
     */
    static function HEAD(string $path = '/', \Closure $router) : void {
        static::__http('HEAD', $path, $router);
    }

    /**
     * Method OPTIONS
     *
     * @param string $path
     * @param \Closure $router
     * @return void
     */
    static function OPTIONS(string $path = '/', \Closure $router) : void {
        static::__http('OPTIONS', $path, $router);
    }

    /**
     * TRACE
     *
     * @param string $path
     * @param \Closure $router
     * @return void
     */
    static function TRACE(string $path = '/', \Closure $router) : void {
        static::__http('TRACE', $path, $router);
    }

    /**
     * Method CONNECT
     *
     * @param string $path
     * @param \Closure $router
     * @return void
     */
    static function CONNECT(string $path = '/', \Closure $router) : void {
        static::__http('CONNECT', $path, $router);
    }
}