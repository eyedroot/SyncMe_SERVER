<?php
/**
 * Simple autoloader that follow the PHP Standards Recommendation #0 (PSR-0)
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md for more informations.
 *
 * Code inspired from the SplClassLoader RFC
 * @see https://wiki.php.net/rfc/splclassloader#example_implementation
 */
spl_autoload_register(function ($className) {
    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';

    $className = ROOT_PATH . str_replace('\\', DIRECTORY_SEPARATOR, $className);

    foreach (['.class.php'] as $ext) {
        if (file_exists($className . $ext)) {
            require $className . $ext;
            return true;
        }
    }

    return false;
});