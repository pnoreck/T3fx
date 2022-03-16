<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 03/07/16
 * Time: 20:49
 */

namespace T3fx\Application\DynDNS\Controller;

use T3fx\Core\Controller\AbstractActionController;

class DyndnsController extends AbstractActionController
{

    /**
     * Check Action is called by cronjob that we have always the current ip from home
     */
    public function CheckAction()
    {

        $file      = DOCUMENT_ROOT . 'Temp/currentip.txt';
        $currentIP = $_SERVER["REMOTE_ADDR"];
        $lastIP    = file_exists($file) ? trim(file_get_contents($file)): '';

        if ($lastIP != $currentIP) {

            file_put_contents($file, $currentIP);

            $updateUrl = \T3fx\Config::getInstance()->getApplicationConfig('DynDNS', 'UpdateUrl');
            $updateUrl = str_replace('{new_ip}', $currentIP, $updateUrl);

            \T3fx\Library\Connector\Http\Curl::Get($updateUrl);

            return [
                'new_ip' => $currentIP,
                'old_ip' => $lastIP,
                'status' => 'NEW',
                'code'   => 300,
            ];
        } else {
            return [
                'old_ip' => $lastIP,
                'status' => 'OK',
                'code'   => 200,
            ];
        }
    }
}
