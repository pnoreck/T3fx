<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 03/07/16
 * Time: 14:13
 */

return [

    // Default configuration
    'default'   => [

        'database' => [
            'dbname'   => '',
            'user'     => '',
            'password' => '',
            'host'     => '',
            'driver'   => 'pdo_mysql',
        ],

        'application' => [
            'controller' => '\\T3fx\\Controller\\ApplicationController',
            'action'     => 'StandardAction',
            'params'     => null,
        ],

        'applications' => [

            'DynDNS' => [
                'UpdateUrlT3x'      => 'https://home.t3x.ch:1234test@www.udmedia.de/nic/update?myip={new_ip}',
                'UpdateUrlHastaedt' => 'https://home.hastaedt.de:1234test@www.udmedia.de/nic/update?myip={new_ip}',
            ],

            'OpenWeatherMap' => [
                'ApiKey' => '',
            ],

            'MailScanner' => [
                // Scan your inbox and move the mails to the defined location
                'MailBoxes' => [
                    [
                        'host'     => 'mail.yourdomain.ch',
                        'user'     => 'your_username',
                        'password' => 'yourPassW0rd!',
                        'folder'   => 'INBOX',
                        'target'   => 'INBOX/Trash',
                    ],
                ],
                'SpamBoxes' => [
                    // Scan a mailbox which is only getting spam
                    [
                        'host'     => 'mail.yourdomain.ch',
                        'user'     => 'your_spambox_username',
                        'password' => 'yourPassW0rd!',
                        'folder'   => 'INBOX',
                        'target'   => 'INBOX/Trash'
                    ],
                    // ..or scan your spam folder and feed the content filter with it
                    [
                        'host'     => 'mail.yourdomain.ch',
                        'user'     => 'your_username',
                        'password' => 'yourPassW0rd!',
                        'folder'   => 'INBOX/Junk',
                        'target'   => 'INBOX/Trash'
                    ],
                ],
                'DNSBL'     => [
                    'blacklists' => [
                        'sbl-xbl.spamhaus.org',
                        'all.rbl.webiron.net',
                        'bl.spamcop.net',
                    ],
                ],
            ],
        ],
    ],

    // Configuration for specific domain
    // You only have to set the fields which are different to the default configuration
    't3fx.test' => [
        // ..or your testserver
        'database' => [
            'user'     => 'root',
            'password' => '',
            'host'     => 'localhost',
        ],
    ],
];
