<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 02/07/16
 * Time: 12:19
 */

namespace T3fx\Library\Connector\Http;

class Curl
{
    /**
     * Current implementation is https://github.com/anlutro/php-curl
     */

    /**
     * Call an URL without getting an answer
     *
     * @param $url string
     * @return string
     */
    public static function Get(string $url)
    {
        $curl = new \anlutro\cURL\cURL();
        return $curl->get($url);
    }
}
