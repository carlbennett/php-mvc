<?php

namespace CarlBennett\MVC\Libraries;

use \CarlBennett\MVC\Libraries\Logger;
use \CarlBennett\MVC\Libraries\Router;

abstract class Controller {

  public function __construct() {
    Logger::logMetric("controller", get_class($this));
  }

  public abstract function run(Router &$router);

}
