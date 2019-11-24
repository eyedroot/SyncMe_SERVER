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
 */
if (!function_exists('getDBInstance')) {
    function getDBInstance(string $dbType = 'mongo') {
        return \onair\lib\DBInstance::getInstance($dbType);
    }
}
