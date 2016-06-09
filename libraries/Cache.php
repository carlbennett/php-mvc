<?php

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
