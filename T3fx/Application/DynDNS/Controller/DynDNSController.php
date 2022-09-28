<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 03/07/16
 * Time: 20:49
 */

namespace T3fx\Application\DynDNS\Controller;

use anlutro\cURL\Response;
use T3fx\Core\Controller\AbstractActionController;

class DynDNSController extends AbstractActionController
{

    /**
     * Check Action is called by cronjob that we have always the current ip from home
     */
    public function CheckAction()
    {
        [
            $lastIPv4,
            $currentIPv4,
            $updateStatusV4
        ] = $this->checkAndUpdate('v4', 'https://ipecho.net/plain');
        [
            $lastIPv6,
            $currentIPv6,
            $updateStatusV6
        ] = $this->checkAndUpdate('v6', 'https://tools.t3x.ch/currentip.php');

        return [
            'old_ip_v4'      => $lastIPv4,
            'new_ip_v4'      => $currentIPv4,
            'old_ip_v6'      => $lastIPv6,
            'new_ip_v6'      => $currentIPv6,
            'updateStatusV4' => $updateStatusV4,
            'updateStatusV6' => $updateStatusV6,
            'code'           => 200,
        ];
    }

    protected function checkAndUpdate(string $version, string $checkUrl)
    {
        $updateStatus = 'unchanged';
        $lastIPFile   = '/home/' . get_current_user() . '/Temp/lastIP' . $version . '.txt';
        $dir          = dirname($lastIPFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        if (!is_dir($dir)) {
            die("Can't create temp folder '" . $dir . "' with " . get_current_user());
        }

        if (!is_writable($lastIPFile)) {
            die("Can't write to temp folder '" . $lastIPFile . "' with " . get_current_user());
        }

        $lastIP = file_exists($lastIPFile) ? trim(file_get_contents($lastIPFile)) : 'xxx';
        /** @var Response $response */
        $response = \T3fx\Library\Connector\Http\Curl::Get($checkUrl);
        if ($response->statusCode >= 200 && $response->statusCode < 300) {
            $currentIP = $response->getBody();
        } else {
            return [
                $lastIP,
                $response->statusText,
                $updateStatus
            ];
        }

        $regEx = ($version === 'v6') ? '/^[0-9a-f:]{3,40}$/i' : '/^[0-9]{1,3}(\.[0-9]{1,3}){3}$/i';
        if ($lastIP !== $currentIP && preg_match($regEx, $currentIP)) {
            file_put_contents($lastIPFile, $currentIP, LOCK_EX);

            $updateUrl = \T3fx\Config::getInstance()->getApplicationConfig('DynDNS', 'UpdateUrlT3x');
            $updateUrl = str_replace('{new_ip}', $currentIP, $updateUrl);

            /** @var Response $response */
            $response = \T3fx\Library\Connector\Http\Curl::Get($updateUrl);
            if ($response->statusCode >= 200 && $response->statusCode < 300) {
                $updateStatus = 'T3x-Status: ' . $response->getBody();
            }
            $updateUrl = \T3fx\Config::getInstance()->getApplicationConfig('DynDNS', 'UpdateUrlHastaedt');
            $updateUrl = str_replace('{new_ip}', $currentIP, $updateUrl);
            /** @var Response $response */
            $response = \T3fx\Library\Connector\Http\Curl::Get($updateUrl);
            if ($response->statusCode >= 200 && $response->statusCode < 300) {
                $updateStatus .= ' - hastaedt-Status: ' . $response->getBody();
            }
        }

        return [
            $lastIP,
            $currentIP,
            $updateStatus
        ];
    }
}
