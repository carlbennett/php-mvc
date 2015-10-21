<?php

namespace CarlBennett\MVC\Views;

use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\Template;
use \CarlBennett\MVC\Libraries\View;
use \CarlBennett\MVC\Models\HTTP404 as HTTP404Model;

class HTTP404Html extends View {

  public function getMimeType() {
    return "text/html;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof HTTP404Model) {
      throw new IncorrectModelException();
    }
    (new Template($model, "HTTP404"))->render();
  }

}
