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

namespace T3fx\Library\Database\Doctrine;

use Doctrine\DBAL\DriverManager;
use T3fx\Config;
use T3fx\Library\Logging\File;
use T3fx\Library\Pattern\Singleton;

/**
 * Class DbConnection
 *
 * @package T3fx\Library\Database\Doctrine
 */
class DbConnection extends Singleton
{

    /**
     * @var
     */
    protected static $conn;

    /**
     * Initialize Database connection
     *
     * @return void
     */
    protected function init()
    {
        $config           = new \Doctrine\DBAL\Configuration();
        $connectionParams = Config::getInstance()->getDatabaseConfig();
        try {
            self::$conn       = DriverManager::getConnection($connectionParams, $config);
        } catch (\Exception $e) {
            File::log('Could not connect to database');
            die();
        }
    }


    /**
     * Return the current database connection
     *
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection() {
        return (self::$conn instanceof \Doctrine\DBAL\Connection) ? self::$conn : null;
    }
}
