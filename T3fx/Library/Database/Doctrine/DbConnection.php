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

use T3fx\Config;
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

    protected function init()
    {
        $config           = new \Doctrine\DBAL\Configuration();
        $connectionParams = Config::getInstance()->getDatabaseConfig();
        self::$conn       = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    }


}
