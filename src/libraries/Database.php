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

use \PDO;

class Database extends PDO {

    protected $character_set;
    protected $database;
    protected $hostname;
    protected $password;
    protected $port;
    protected $timeout;
    protected $username;

    public function __construct($host, $port, $user, $pw, $db, $c, $t) {
        $this->hostname      = $host;
        $this->port          = $port;
        $this->username      = $user;
        $this->password      = $pw;
        $this->database      = $db;
        $this->character_set = $c;
        $this->timeout       = $t;
        $dsn = "mysql:"
            . "host=" . $this->hostname . ";"
            . "port=" . $this->port . ";"
            . "dbname=" . $this->database . ";"
            . "charset=" . $this->character_set;
        parent::__construct($dsn, $this->username, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => $this->timeout
        ]);
    }

    public function getCharacterSet() {
        return $this->character_set;
    }

    public function getDatabase() {
        return $this->database;
    }

    public function getHostname() {
        return $this->hostname;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getPort() {
        return $this->port;
    }

    public function getTimeout() {
        return $this->timeout;
    }

    public function getUsername() {
        return $this->username;
    }

}
