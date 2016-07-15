<?php

namespace CarlBennett\MVC\Libraries;

use \CarlBennett\MVC\Libraries\Cache;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Database;
use \CarlBennett\MVC\Libraries\Exceptions\DatabaseUnavailableException;
use \CarlBennett\MVC\Libraries\Logger;
use \PDOException;

class DatabaseDriver {

    public static function getDatabaseObject() {
        $last_exception = null;
        $servers        = self::getServers();
        foreach ($servers as $server) {
            try {
                $connection = new Database($server->hostname, $server->port);
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

    public static function getServers() {
        // This function should be redeclared by the project.
        return [];
    }

}
