<?php

namespace CarlBennett\MVC\Libraries\Exceptions;

use \CarlBennett\MVC\Libraries\Exceptions\BaseException;
use \CarlBennett\MVC\Libraries\Template;
use \Exception;

class TemplateNotFoundException extends BaseException {

  public function __construct(Template &$template, Exception &$prev_ex = null) {
    parent::__construct(
      "Unable to locate template required to load this view",
      BaseException::BASE_CODE + 5,
      $prev_ex
    );
  }

}
