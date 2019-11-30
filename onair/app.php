<?php
/**
 * autoload
 */
include_once 'autoload.php';

/**
 * constant
 */
include_once 'constant.php';

/**
 * Closure 체크 함수
 */
if (! function_exists('is_closure')) {
    function is_closure($t) : bool {
        return !! ($t instanceof \Closure);
    }
}

/**
 * 앱의 설정 파일 로드
 */
if (! function_exists('app')) {
    function app(string $key = '') {
        return \onair\lib\AppFacade::getApp($key);
    }
}

/**
 * 앱의 설정 파일 로드
 */
if (! function_exists('user')) {
    function user() {
        return \onair\lib\AppFacade::getUser();
    }
}


/**
 * handleDB
 * 
 * 모든 DB 인스턴트는 해당 메서드를 통해 처리한다
 */
if (! function_exists('handleDB')) {
    function handleDB(string $dbType = 'mongo') {
        return \onair\lib\AppFacade::getDBInstance($dbType);
    }
}

/**
 * handleRequest
 * 
 * 모든 $_POST 또는 $_GET의 변수값을 해당 메서드를 통해 처리한다
 */
if (! function_exists('handleRequest')) {
    function handleRequest(string $key) {
        return \onair\lib\AppFacade::getSecurityRequest('POST', $key);
    }
}

/**
 * handleHeader
 * 
 * $_SERVER 헤더에 담긴 키를 찾아 리턴한다
 */
if (! function_exists('handleHeader')) {
    function handleHeader(string $key) {
        $key = strtoupper($key);

        if (\array_key_exists($key, $_SERVER)) {
            return $_SERVER[ $key ];
        }

        return false;
    }
}

/**
 * toObject
 */
if (! function_exists('toObject')) {
    function toObject(...$args) {
        $count = count($args);

        if ($count === 0) {
            return new \stdClass;
        } 
        else if ($count === 1) {
            if ( is_array($args[0]) ) {
                return (object) $args[0];
            } 
            else {
                return $args[0];
            }
        } 
        else {
            $i = 0;
            $bulk = [];

            foreach ($args as $key => $v) {
                if ($v instanceof \onair\lib\InjectionSecurity) {
                    $bulk[ $v->getKey() ] = $v->get();
                } else {
                    $bulk[ $key ] = $v;
                }

                $i += 1;
            }

            return (object) $bulk;
        }
    }
}

/**
 * Easy 디버그 도구
 */
if (! function_exists('dd')) {
    function dd($data) {
        highlight_string("\n<?php\n\$data =\n" . var_export($data, true) . ";\n?>\n\n");
    }
}

if (! function_exists('jsonEnd')) {
    function jsonEnd(string $message, int $code) : void {
        header('Content-Type: application/json');
        echo json_encode([ 'code' => $code, 'explain' => $message ]);
        exit();
    }
}

/**
 * 컨트롤러 
 */
if (! function_exists('controller')) {
    function controller(string $ctrl) : \Closure {
        $file = CONTROLLER_PATH . $ctrl . '.php';

        if ( \file_exists($file) ) {
            return include $file;
        }

        throw new \ErrorException("{$ctrl} :: 해당 컨트롤러를 찾을 수 없습니다");
    }
}

/**
 * 미들웨어
 */
if (! function_exists('middleware')) {
    function middleware(string $middle) : \Closure {
        $file = MIDDLEWARE_PATH . $middle . '.php';

        if ( \file_exists($file) ) {
            $middlewareClosure = include $file;

            if ( is_closure($middlewareClosure) ) {
                return $middlewareClosure;
            }
        }

        throw new \ErrorException("{$middle} :: 해당 미들웨어를 찾을 수 없습니다");
    }
}