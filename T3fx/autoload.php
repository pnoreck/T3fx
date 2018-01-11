<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 18/05/16
 * Time: 05:44
 */
chdir(__DIR__);
require_once '../vendor/autoload.php';

spl_autoload_register(
    function ($classname) {

        // If we are responsible for this class we have to search at more then one place
        if (preg_match('/^\/?T3fx.*/', $classname)) {
            $search   = [];
            $search[] = preg_replace('/^\/?T3fx\\\/', '', $classname);
            $search[] = preg_replace('/^\/?T3fx\\\/', 'Core/', $classname);
        } else {
            return false;
        }

        foreach ($search as $name) {
            $file_name = __DIR__ . '/' . str_replace('\\', '/', $name) . '.php';
            if (is_file($file_name) && is_readable($file_name)) {
                require_once($file_name);
            }
        }
    }
);
