<?php

namespace CarlBennett\MVC\Libraries\Exceptions;

use \CarlBennett\MVC\Libraries\Exceptions\BaseException;
use \CarlBennett\MVC\Libraries\Logger;
use \CarlBennett\MVC\Libraries\Template;
use \Exception;

class TemplateNotFoundException extends BaseException {

  public function __construct(Template &$template, Exception &$prev_ex = null) {
    parent::__construct(
      "Unable to locate template required to load this view", 4, $prev_ex
    );
  }

}
