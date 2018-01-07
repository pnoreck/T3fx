<?php
/**
 * Created by PhpStorm.
 * User: Steffen Hastädt
 * Date: 03/07/16
 * Time: 14:13
 */

return [

    // Default configuration
    'default'       => [

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
            'DynDNS'         => [
                'UpdateUrl' => '',
            ],
            'OpenWeatherMap' => [
                'ApiKey' => '',
            ],
        ],
    ],

    // Configuration for specific domain
    'my.domain.com' => [
        'database' => [
            'dbname'   => '',
            'user'     => '',
            'password' => '',
            'host'     => 'localhost',
            'driver'   => 'pdo_mysql',
        ],

        'applications' => [
            'MailScanner' => [
                'MailBoxes' => [
                    [
                        'host'     => '',
                        'user'     => '',
                        'password' => '',
                    ],
                ],
                'SpamBoxes' => [
                    [
                        'host'     => '',
                        'user'     => '',
                        'password' => '',
                    ]
                ],
                'DNSBL'     => [
                    'sbl-xbl.spamhaus.org',
                    'all.rbl.webiron.net',
                    'bl.spamcop.net',
                ],
            ],
        ],
    ],
];