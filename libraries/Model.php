<?php

namespace CarlBennett\MVC\Libraries;

use \CarlBennett\MVC\Libraries\Logger;

abstract class Model {

    public $_responseCode;
    public $_responseHeaders;
    public $_responseTTL;

    public function __construct() {
        Logger::logMetric("model", get_class($this));

        $this->_responseCode    = 500;
        $this->_responseHeaders = [
            "X-Frame-Options"   => "DENY",
        ];
        $this->_responseTTL     = 0;
    }

}
