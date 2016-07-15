<?php

namespace CarlBennett\MVC\Libraries\Exceptions;

use \CarlBennett\MVC\Libraries\Exceptions\BaseException;
use \CarlBennett\MVC\Libraries\Logger;
use \Exception;

class ViewNotFoundException extends BaseException {

  public function __construct($viewName, Exception &$prev_ex = null) {
    parent::__construct(
      "Unable to find a suitable view given the path",
      BaseException::BASE_CODE + 3,
      $prev_ex
    );
    Logger::logMetric("viewName", $viewName);
  }

}
