<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 19/06/16
 * Time: 14:10
 */

namespace T3fx\Library\Logging;

class File
{

    const ERROR = '-error';
    const WARNING = '-warning';
    const NOTICE = '';

    /**
     * Log a string to a logfile
     *
     * @param $string
     *
     * @return void
     */
    public static function log($string, $level = self::NOTICE)
    {
        $date = date('Ymd');
        $time = date('H:i:s');
        $path = preg_replace('/T3fx.*$/', 'Logs/', __DIR__);
        file_put_contents(
            $path . $date . $level . '.log',
            $time . '   ' . $string . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }
}