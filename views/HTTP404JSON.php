<?php

namespace CarlBennett\MVC\Views;

use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\Template;
use \CarlBennett\MVC\Libraries\View;
use \CarlBennett\MVC\Models\HTTP404 as HTTP404Model;

class HTTP404JSON extends View {

  public function getMimeType() {
    return "application/json;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof HTTP404Model) {
      throw new IncorrectModelException();
    }
    echo json_encode("Object Not Found\n"
      . "The requested resource does not exist or could not be found.\n"
    , Common::prettyJSONIfBrowser());
  }

}
