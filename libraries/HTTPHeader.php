<?php

namespace CarlBennett\MVC\Libraries;

use \CarlBennett\MVC\Libraries\Pair;

class HTTPHeader extends Pair {

  public function getName() {
    return $this->getKey();
  }

  public function __tostring() {
    return $this->key . ": " . $this->value . "\n";
  }

}
