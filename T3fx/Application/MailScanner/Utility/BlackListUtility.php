<?php
/*
 * Copyright 2018 - Steffen Hastädt
 *
 * t3fx@t3x.ch | www.t3x.ch
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace T3fx\Application\MailScanner\Utility;

use T3fx\Config;
use T3fx\Library\Pattern\Singleton;

/**
 * Class BlackListUtility
 *
 * @package T3fx\Application\MailScanner\Utility
 */
class BlackListUtility extends Singleton
{

    /**
     * @var array
     */
    protected $rplIPs = [];

    /*
     * @param array $mailHeader
     *
     * @return bool
     */
    public function checkAgainstPublicBlacklist($mailHeader)
    {
        if (!is_array($mailHeader) || !$mailHeader) {
            return false;
        }

        if (preg_match('/\([^[:space:]]+ ([a-z0-9\-\.]+)\)/i', $mailHeader[0]["Received"], $hits)) {
            $ipv4 = gethostbyname($hits[1]);

        } elseif (preg_match('/[0-9]{1,3}(\.[0-9]{1,3}){3}/i', $mailHeader[0]["Received"], $hits)) {
            $ipv4 = $hits[0];

        } else {
            // TODO: implement logging
            // var_dump($headerInfo[0]["Received"]);
        }

        if (
            preg_match('/^[0-9]{1,3}(\.[0-9]{1,3}){3}$/i', $ipv4) &&
            $this->isIPv4Blacklisted($ipv4)
        ) {
            return true;
        }

        return false;
    }


    /**
     * Check the given IPv4 against the configured blacklists
     *
     * @param $ipv4
     *
     * @return bool
     * @throws \Exception
     */
    protected function isIPv4Blacklisted($ipv4)
    {
        static $rbls;
        if (!$rbls) {
            /** @var \T3fx\Config $config */
            $config = Config::getInstance();
            $rbls   = $config->getApplicationConfig('MailScanner', 'DNSBL');

            if(
                !is_array($rbls) ||
                !array_key_exists('blacklists', $rbls) || !is_array($rbls['blacklists']) || !$rbls['blacklists']
            ) {
                throw new \Exception('DNSBL blacklists are not configured.');
            }

        }

        $dnsbl = new \DNSBL\DNSBL($rbls);
        return $dnsbl->isListed($ipv4);
    }
}
