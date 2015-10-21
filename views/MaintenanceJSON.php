<?php

namespace CarlBennett\MVC\Views;

use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\Template;
use \CarlBennett\MVC\Libraries\View;
use \CarlBennett\MVC\Models\Maintenance as MaintenanceModel;

class MaintenanceJSON extends View {

  public function getMimeType() {
    return "application/json;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof MaintenanceModel) {
      throw new IncorrectModelException();
    }
    echo json_encode([
      "title"   => "Maintenance",
      "message" => $model->message
    ], Common::prettyJSONIfBrowser());
  }

}
