<?php

namespace CarlBennett\MVC\Views;

use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\Template;
use \CarlBennett\MVC\Libraries\View;
use \CarlBennett\MVC\Models\HTTP404 as HTTP404Model;

class HTTP404Plain extends View {

  public function getMimeType() {
    return "text/plain;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof HTTP404Model) {
      throw new IncorrectModelException();
    }
    echo "Document Not Found\n"
      . "The requested resource does not exist or could not be found.\n";
  }

}
