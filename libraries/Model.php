<?php

namespace CarlBennett\MVC\Libraries;

use \CarlBennett\MVC\Libraries\Logger;

abstract class Model {

    public function __construct() {
        Logger::logMetric("model", get_class($this));
    }

}
