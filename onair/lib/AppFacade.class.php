<?php

namespace onair\lib;

/**
 * 모든 클래스의 인스턴트는 해당 클래스를 통해 생성된다
 * 중복으로 클래스를 생성할 수 없으면 모든 클래스들은 해당 클래스를 통해서만
 * 인스턴트를 생성해야 한다
 * 
 * TODO 다른 주소값을 가지는 인스턴트를 생성하는 것은 현재 고려 중이다
 */
class AppFacade extends \onair\lib\abstracts\FacadeAbstract
{
    /**
     * 인스턴트들을 공유하기 위한 static 변수
     * 모든 클래스의 인스턴트들은 여기서 관리하는 것을 원칙으로 한다
     */
    static $facades = [];

    /**
     * DB 연결 인스턴트를 관리하기 위한 메서드
     *
     * @param string $dbType [mongo]
     * @return 복합 인스턴트
     */
    static function getDBInstance(string $dbType = 'mongo') {
        $identifier = __FUNCTION__ . ':' . $dbType;

        if (\array_key_exists($identifier, static::$facades)) {
            return static::$facades[ $identifier ];
        }

        switch ($dbType) {
            case 'mongo':
                // TODO 라이브서버와 로컬 개발환경에 대한 구분 로직이 필요함
                // `app.cfg.php` 파일을 활용하여 호스트를 구분해보자
                // 서버 호스트(byzz.app)일 때는 라이브 서버이며, 그 밖에는 모두 로컬개발환경으로 간주한다
                $auth = '';

                if ( \isProd() ) {
                    $auth = (
                        app()->var('mongodb_id') . ':' . \safeDecrypt( app()->var('mongodb_password') ) . '@'
                    );
                }

                $host = "mongodb://" . $auth . app()->var('mongodb_host') . ':' . app()->var('mongodb_port') . '/' . app()->var('mongodb_dbname');

                static::$facades[ $identifier ] = new \MongoDB\Driver\Manager( $host );
                break;
            case 'redis':
                $redis = new \Redis();

                try {
                    $redis->connect(app()->var('redis_host'), app()->var('redis_port'), app()->var('redis_timeout'));
                } catch (\Exception $e) {
                    if (\isProd()) {
                        \endpoint("can't connect redis server!!", App()::CODE_GLOBAL_FAILURE);
                    } else {
                        exit($e->getMessage());
                    }
                }

                static::$facades[ $identifier ] = $redis;
            default:
        }
        
        return static::$facades[ $identifier ];
    }

    /**
     * App 인스턴트 리턴
     *
     * @param string $key
     * @return \onair\lib\App
     */
    static function getApp(string $key) : \onair\lib\App {
        $identifier = 'app';

        if (\array_key_exists($identifier, static::$facades)) {
            return static::$facades[ $identifier ];
        }

        static::$facades[ $identifier ] = new \onair\lib\App($key);

        return static::$facades[ $identifier ];
    }

    /**
     * User 인스턴트 리턴
     *
     * @return \onair\lib\User
     */
    static function getUser() : \onair\lib\User {
        $identifier = 'user';

        if (\array_key_exists($identifier, static::$facades)) {
            return static::$facades[ $identifier ];
        }

        static::$facades[ $identifier ] = new \onair\lib\User();

        return static::$facades[ $identifier ];
    }

    /**
     * UserProfile 인스턴트 리턴
     *
     * @return \onair\lib\User
     */
    static function getUserProfile() : \onair\lib\UserProfile {
        $identifier = 'userProfile';

        if (\array_key_exists($identifier, static::$facades)) {
            return static::$facades[ $identifier ];
        }

        static::$facades[ $identifier ] = new \onair\lib\UserProfile();

        return static::$facades[ $identifier ];
    }

    /**
     * Request 변수들을 컨트롤 하기 위한 메서드
     *
     * @param string $method [GET|POST]
     * @param string $key 가져올 변수 이름
     * @return \onair\lib\InjectionSecurity
     */
    static function getSecurityRequest(string $method = 'POST', string $key) : \onair\lib\InjectionSecurity {
        $identifier = __FUNCTION__ . ':' . $method;

        if (\array_key_exists($identifier, static::$facades)) {
            return static::$facades[ $identifier ];
        }

        static::$facades[ $identifier ] = new \onair\lib\InjectionSecurity(
            \strtoupper($method),
            $key
        );

        return static::$facades[ $identifier ];
    }

    /**
     * FileHandler 리턴
     *
     * @param string $key
     * @return \onair\lib\FileHandler
     */
    static function getFileHandler(array $hf) : \onair\lib\FileHandler {
        $identifier = 'filehandler';

        if (\array_key_exists($identifier, static::$facades)) {
            return static::$facades[ $identifier ];
        }

        static::$facades[ $identifier ] = new \onair\lib\FileHandler($hf);

        return static::$facades[ $identifier ];
    }

    /**
     * TagHandler 리턴
     *
     * @return \onair\lib\TagSystem
     */
    static function getTagHandler() : \onair\lib\TagSystem {
        $identifier = 'taghandler';

        if (\array_key_exists($identifier, static::$facades)) {
            return static::$facades[ $identifier ];
        }

        static::$facades[ $identifier ] = new \onair\lib\TagSystem();

        return static::$facades[ $identifier ];
    }

    /**
     * TagMatchSystem Handler
     *
     * @return \onair\lib\TagMatchSystem
     */
    static function getTagMatchHandler() : \onair\lib\TagMatchSystem {
        $identifier = 'tagmatchsystemhandler';

        if (\array_key_exists($identifier, static::$facades)) {
            return static::$facades[ $identifier ];
        }

        static::$facades[ $identifier ] = new \onair\lib\TagMatchSystem();

        return static::$facades[ $identifier ];
    }

    /**
     * static으로 선언한 핸들러를 파괴한다
     *
     * @return boolean
     */
    static function eliminateHandler(string $handlerName) : bool {
        switch ($handlerName) {
            case 'handleFile':
                unset(static::$facades['filehandler']);
                return true;
            default:
                return false;
        }
    }
}

