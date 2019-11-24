<?php

namespace onair\lib;

/**
 * Undocumented class
 */
class DBInstance {
    static $instance = [];

    /**
     * Undocumented function
     *
     * @param string $dbType
     * @return void
     */
    static function getInstance(string $dbType = 'mongo') {
        if (\array_key_exists($dbType, static::$instance)) {
            return static::$instance[$dbType];
        }

        switch ($dbType) {
            case 'mongo':
                static::$instance[$dbType] = new \MongoDB\Driver\Manager();
                break;
            default:
        }
        
        return static::$instance[$dbType];
    }
}

