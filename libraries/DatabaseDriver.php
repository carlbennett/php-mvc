<?php

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
                    self::$timeout
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
