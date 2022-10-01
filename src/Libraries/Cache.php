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

use \Memcached;

class Cache {

    const DEFAULT_TTL = 60;

    protected $memcache;

    public function __construct($servers, $timeout = 1, $tcp_nodelay = true) {
        $this->memcache = new Memcached();
        $this->memcache->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
        $this->memcache->setOption(Memcached::OPT_TCP_NODELAY, $tcp_nodelay);
        $this->memcache->setOption(
            Memcached::OPT_CONNECT_TIMEOUT, $timeout * 1000
        );
        if (is_string($servers)) {
            $server = explode(":", $servers);
            $this->memcache->addServer($server[0], (int) $server[1]);
        } else if ($servers instanceof StdClass) {
            $this->memcache->addServer($server->hostname, $server->port);
        } else {
            foreach ($servers as $server) {
                $this->memcache->addServer($server->hostname, $server->port);
            }
        }
    }

    public function delete($key, $wait = 0) {
        return $this->memcache->delete($key, $wait);
    }

    public function get($key) {
        return $this->memcache->get($key);
    }

    public function set($key, $value, $ttl = self::DEFAULT_TTL) {
        if ($ttl < 1) {
            return $this->memcache->set($key, $value, 0);
        } else {
            return $this->memcache->set($key, $value, time() + $ttl);
        }
    }

}
