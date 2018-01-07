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

        if (preg_match('/^[0-9]{1,3}(\.[0-9]{1,3}){3}$/i', $ipv4)) {

            if (
                $this->isBlacklistedIPv4($ipv4, 'sbl-xbl.spamhaus.org')
                || $this->isBlacklistedIPv4($ipv4, 'all.rbl.webiron.net')
                // || $this->isBlacklistedIPv4($ipv4, 'rbl.iprange.net')
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check the given IPv4 address against the given rbl
     *
     * @param $ip
     * @param $rbl
     *
     * @return bool
     */
    private function isBlacklistedIPv4($ip, $rbl)
    {
        if (!isset($this->rplIPs[$rbl])) {
            $this->rplIPs[$rbl] = gethostbyname($rbl);
        }

        $rev    = array_reverse(explode('.', $ip));
        $lookup = implode('.', $rev) . '.' . $rbl;
        $result = gethostbyname($lookup);

        return ($this->rplIPs[$rbl] != $result);
    }
}
