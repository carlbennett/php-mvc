<?php

namespace CarlBennett\MVC\Libraries;

use \CarlBennett\MVC\Libraries\Logger;
use \CarlBennett\MVC\Libraries\Model;

abstract class View {

  public function __construct() {
    Logger::logMetric("view", get_class($this));
  }

  public abstract function getMimeType();
  public abstract function render(Model &$model);

}
