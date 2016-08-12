<?php

namespace CarlBennett\MVC\Libraries\Exceptions;

use \CarlBennett\MVC\Libraries\Exceptions\BaseException;
use \CarlBennett\MVC\Libraries\Logger;
use \Exception;

class ControllerNotFoundException extends BaseException {

  public function __construct($controllerName, Exception &$prev_ex = null) {
    parent::__construct(
      "Unable to find a suitable controller given the path",
      BaseException::BASE_CODE + 2,
      $prev_ex
    );
    Logger::logMetric("controllerName", $controllerName);
  }

}
