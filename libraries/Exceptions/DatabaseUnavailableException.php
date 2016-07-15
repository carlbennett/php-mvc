<?php

namespace CarlBennett\MVC\Libraries\Exceptions;

use \CarlBennett\MVC\Libraries\Exceptions\BaseException;
use \Exception;

class DatabaseUnavailableException extends BaseException {

  public function __construct(Exception &$prev_ex = null) {
    parent::__construct(
      "All configured databases are unavailable",
      BaseException::BASE_CODE + 6,
      $prev_ex
    );
  }

}
