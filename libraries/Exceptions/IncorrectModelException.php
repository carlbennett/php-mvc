<?php

namespace CarlBennett\MVC\Libraries\Exceptions;

use \CarlBennett\MVC\Libraries\Exceptions\BaseException;
use \Exception;

class IncorrectModelException extends BaseException {

  public function __construct(Exception $prev_ex = null) {
    parent::__construct(
      "Incorrect model provided to view",
      BaseException::BASE_CODE + 4,
      $prev_ex
    );
  }

}
