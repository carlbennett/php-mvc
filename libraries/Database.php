<?php

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
