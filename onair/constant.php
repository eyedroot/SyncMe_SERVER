<?php
/**
 * const: ROOT_PATH
 */
if (! defined('ROOT_PATH')) {
    define('ROOT_PATH', '/var/www/SyncMe_SERVER/');
}

if (! defined('CONTROLLER_PATH')) {
    define('CONTROLLER_PATH', ROOT_PATH . 'onair/lib/controller/');
}

if (! defined('MIDDLEWARE_PATH')) {
    define('MIDDLEWARE_PATH', ROOT_PATH . 'onair/lib/middleware/');
}