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
			'params'     => NULL,
		],

		'applications' => [
			'DynDNS' => [
				'UpdateUrl' => '',
			]
		]
	],

	// Configuration for specific domain
	'my.domain.com' => [

	],
];