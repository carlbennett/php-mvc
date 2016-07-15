<?php

namespace CarlBennett\MVC\Libraries\Exceptions;

use \CarlBennett\MVC\Libraries\Exceptions\BaseException;
use \Exception;

class QueryException extends BaseException {

  public function __construct($message, Exception &$prev_ex = null) {
    parent::__construct($message, BaseException::BASE_CODE + 7, $prev_ex);
  }

}
