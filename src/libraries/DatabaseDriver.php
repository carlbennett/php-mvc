<?php
/**
 *  php-mvc, a PHP micro-framework for use as a frontend and/or backend
 *  Copyright (C) 2015-2016  Carl Bennett
 *  This file is part of php-mvc.
 *
 *  php-mvc is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  php-mvc is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with php-mvc.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace CarlBennett\MVC\Libraries;

use \CarlBennett\MVC\Libraries\Cache;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Database;
use \CarlBennett\MVC\Libraries\Exceptions\DatabaseUnavailableException;
use \CarlBennett\MVC\Libraries\Logger;
use \InvalidArgumentException;
use \PDOException;

class DatabaseDriver {

    public static $character_set = null;
    public static $database_name = null;
    public static $password      = null;
    public static $servers       = null;
    public static $timeout       = null;
    public static $timezone      = null;
    public static $username      = null;

    public static function getDatabaseObject() {
        if (!self::initialized()) {
            throw new InvalidArgumentException(
                "DatabaseDriver has not been initialized"
            );
        }
        $last_exception = null;
        foreach (self::$servers as $server) {
            try {
                $connection = new Database(
                    $server->hostname, $server->port,
                    self::$username, self::$password,
                    self::$database_name, self::$character_set,
                    self::$timezone, self::$timeout
                );
                return $connection;
            } catch (PDOException $exception) {
                Logger::logMetric("dbhost", $server->hostname);
                Logger::logMetric("dbport", $server->port);
                Logger::logException($exception);
                $last_exception = $exception;
            }
        }
        throw new DatabaseUnavailableException($last_exception);
    }

    private static function initialized() {
        if (!is_string(self::$character_set)) return false;
        if (!is_string(self::$database_name)) return false;
        if (!is_string(self::$password)) return false;
        if (!is_array(self::$servers)) return false;
        if (!is_numeric(self::$timeout)) return false;
        if (!is_string(self::$username)) return false;

        return true;
    }

}
