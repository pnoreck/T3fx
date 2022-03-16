<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 03/07/16
 * Time: 13:55
 */

namespace T3fx\Library\Connector\Http;

class Info extends \T3fx\Library\Pattern\Singleton
{
    /**
     * Includes all HTTP Headers of the current request
     *
     * @var array $headers
     */
    protected $headers = [];

    /**
     * Returns the value of an HTTP header.
     *
     * @param string $name
     *
     * @return string|void
     */
    public function getHeaderValue($name)
    {
        $name = strtolower($name);
        if (isset($this->headers[$name]) && !empty($this->headers[$name])) {
            return $this->headers[$name]["value"];
        }
    }

    /**
     * Chechs the path
     *
     * @return array
     */
    public function getPathInfo()
    {
        global $argv;
        if (is_array($argv) && $argv) {
            $pathInfo = $argv;
            unset($pathInfo[0]);
            return $pathInfo;
        }

        $pathInfo = current(explode('?', $_SERVER['REQUEST_URI']));

        if (
            preg_match('/^[a-zA-Z0-9\-_\/]+\.(php|html).*$/', $pathInfo) ||
            !preg_match('/^[a-zA-Z0-9\-_\/]+$/', $pathInfo)
        ) {
            return [];
        }

        $pathInfo = explode("/", $pathInfo);
        $pathInfo = array_filter($pathInfo);
        if (is_array($pathInfo) && !empty($pathInfo)) {
            return $pathInfo;
        }

        return [];
    }

    /**
     * Initialization of the header variables
     */
    protected function init()
    {
        if (function_exists('apache_request_headers')) {
            $headers = \apache_request_headers();
            foreach ($headers as $header => $value) {
                $this->headers[strtolower($header)] = [
                    'name'  => $headers,
                    'value' => $value,
                ];
            }
        }
    }
}
