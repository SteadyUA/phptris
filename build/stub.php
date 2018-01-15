#!/usr/bin/env php
<?php
error_reporting(0);
ini_set('display_errors', 0);

mb_internal_encoding('UTF-8');

system("stty -icanon -iutf8 -echo");
stream_set_blocking(STDIN, false);

spl_autoload_register(function ($className) {
    $classPath = str_replace('\\', '/', $className);
    $lookAt = ['Tet\\' => 'src/'];
    foreach ($lookAt as $pref => $path) {
        if (strpos($className, $pref) === 0) {
            $classPath = substr($classPath, strlen($pref));
        }
        $filePath = 'phar://' . $path . $classPath . '.php';
        if (file_exists($filePath)) {
            include $filePath;
            return true;
        }
    }
    return false;
});

register_shutdown_function(function () {
    system("stty echo");
});

Phar::mapPhar('src');

try {
    (new \Tet\Phptris())->main($argv);
} catch (Throwable $exception) {
    echo 'Error: ', $exception->getMessage(), PHP_EOL;
    echo 'File: ', $exception->getFile(), ':', $exception->getLine(), PHP_EOL;
}

__halt_compiler(); ?>
