<?php

namespace CarlBennett\MVC\Libraries\Exceptions;

use \CarlBennett\MVC\Libraries\Exceptions\BaseException;
use \Exception;

class QueryException extends BaseException {

  public function __construct($message, Exception &$prev_ex = null) {
    parent::__construct($message, 6, $prev_ex);
  }

}
