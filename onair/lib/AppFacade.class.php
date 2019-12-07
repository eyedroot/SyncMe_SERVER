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
                $host = "mongodb://" . app()->var('mongodb_host') . ':' . 
                        app()->var('mongodb_port') . '/' . app()->var('mongodb_dbname');
                        
                static::$facades[ $identifier ] = new \MongoDB\Driver\Manager( $host );
                break;
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
     * App 인스턴트 리턴
     *
     * @param string $key
     * @return \onair\lib\App
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
}

