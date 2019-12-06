<?php
/**
 * const: ROOT_PATH
 */
if (! defined('ROOT_PATH')) {
    define('ROOT_PATH', '/var/www/SyncMe_SERVER/');
}

/**
 * Controller Path
 */
if (! defined('CONTROLLER_PATH')) {
    define('CONTROLLER_PATH', ROOT_PATH . 'onair/lib/controller/');
}

/**
 * Middleware Path
 */
if (! defined('MIDDLEWARE_PATH')) {
    define('MIDDLEWARE_PATH', ROOT_PATH . 'onair/lib/middleware/');
}

/**
 * Session Name
 */
if (! defined('SESSION_NAME')) {
    define('SESSION_NAME', 'ONAIRSESS');
}