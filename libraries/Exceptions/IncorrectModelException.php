<?php

namespace CarlBennett\MVC\Libraries\Exceptions;

use \CarlBennett\MVC\Libraries\Exceptions\BaseException;
use \Exception;

class IncorrectModelException extends BaseException {

  public function __construct(Exception $prev_ex = null) {
    parent::__construct("Incorrect model provided to view", 3, $prev_ex);
  }

}
