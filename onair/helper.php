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
 * getDBInstance
 * 
 * 모든 DB 인스턴트는 해당 메서드를 통해 처리한다
 */
if (! function_exists('getDBInstance')) {
    function getDBInstance(string $dbType = 'mongo') {
        return \onair\lib\InstanceFacade::getDBInstance($dbType);
    }
}

/**
 * handleRequest
 * 
 * 모든 $_POST 또는 $_GET의 변수값을 해당 메서드를 통해 처리한다
 */
if (! function_exists('handleRequest')) {
    function handleRequest(string $key) {
        return \onair\lib\InstanceFacade::getSecurityRequest('POST', $key);
    }
}

/**
 * TODO MongoDBBulkFactory
 */