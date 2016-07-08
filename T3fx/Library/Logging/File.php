<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 19/06/16
 * Time: 14:10
 */

namespace T3fx\Library\Logging;

class File {



	public static function log($string) {
		$date = date('Ymd');
		$time = date('H:i:s');
		$path = preg_replace('/T3fx.*/', 'Logs/',  __DIR__);
		file_put_contents(
			$path.$date.'.log',
			$time. '   ' . $string . PHP_EOL,
			FILE_APPEND | LOCK_EX
		);
	}
}