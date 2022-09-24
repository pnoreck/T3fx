<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 03/07/16
 * Time: 20:49
 */

namespace T3fx\Application\DynDNS\Controller;

use T3fx\Core\Controller\AbstractActionController;

class DynDNSController extends AbstractActionController
{

    /**
     * Check Action is called by cronjob that we have always the current ip from home
     */
    public function CheckAction()
    {

        [$lastIPv4, $currentIPv4] = $this->checkAndUpdate('v4', 'https://ipecho.net/plain');
        [$lastIPv6, $currentIPv6] = $this->checkAndUpdate('v6', 'https://tools.t3x.ch/currentip.php');

        return [
            'old_ip_v4' => $lastIPv4,
            'new_ip_v4' => $currentIPv4,
            'old_ip_v6' => $lastIPv6,
            'new_ip_v6' => $currentIPv6,
            'status'    => 'OK',
            'code'      => 200,
        ];
    }

    protected function checkAndUpdate(string $version, string $checkUrl)
    {
        $lastIPFile = DOCUMENT_ROOT . 'Temp/lastIP' . $version . '.txt';
        $lastIP     = file_exists($lastIPFile) ? trim(file_get_contents($lastIPFile)) : '';
        $currentIP  = \T3fx\Library\Connector\Http\Curl::Get('https://ipecho.net/plain');

        if ($lastIP !== $currentIP && preg_match('/^[0-9]{1,3}(\.[0-9]{1,3}){3}$/i', $currentIP)) {
            file_put_contents($lastIPFile, $currentIP, LOCK_EX);

            $updateUrl = \T3fx\Config::getInstance()->getApplicationConfig('DynDNS', 'UpdateUrlT3x');
            $updateUrl = str_replace('{new_ip}', $currentIP, $updateUrl);
            \T3fx\Library\Connector\Http\Curl::Get($updateUrl);

            $updateUrl = \T3fx\Config::getInstance()->getApplicationConfig('DynDNS', 'UpdateUrlHastaedt');
            $updateUrl = str_replace('{new_ip}', $currentIP, $updateUrl);
            \T3fx\Library\Connector\Http\Curl::Get($updateUrl);
        }

        return [$lastIP, $currentIP];
    }
}
