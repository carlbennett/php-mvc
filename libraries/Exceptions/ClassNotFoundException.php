<?php

namespace CarlBennett\MVC\Libraries\Exceptions;

use \CarlBennett\MVC\Libraries\Exceptions\BaseException;
use \CarlBennett\MVC\Libraries\Logger;
use \Exception;

class ClassNotFoundException extends BaseException {

  public function __construct($className, Exception &$prev_ex = null) {
    parent::__construct("Required class '$className' not found", 1, $prev_ex);
    Logger::logMetric("className", $className);
  }

}
