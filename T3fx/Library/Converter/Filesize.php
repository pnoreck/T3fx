<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 19/06/16
 * Time: 12:30
 */

namespace T3fx\Library\Converter;

class Filesize {


	/**
	 * Formats a byte value to a readable string like "123 MB".
	 *
	 * @param int $bytes
	 *
	 * @return string
	 */
	public static function makeReadable($bytes) {
		if ($bytes > 0)
		{
			$unit = intval(log($bytes, 1024));
			$units = array('B', 'KB', 'MB', 'GB', 'TB');

			if (array_key_exists($unit, $units) === true)
			{
				return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
			}
		}

		return $bytes;
	}

}