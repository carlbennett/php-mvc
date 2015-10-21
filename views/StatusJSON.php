<?php

namespace CarlBennett\MVC\Views;

use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\View;
use \CarlBennett\MVC\Models\Status as StatusModel;
use \ReflectionExtension;

class StatusJSON extends View {

  public function getMimeType() {
    return "application/json;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof StatusModel) {
      throw new IncorrectModelException();
    }
    echo json_encode([
      "remote_address" => $model->remote_address,
      "remote_geoinfo" => $model->remote_geoinfo,
      "timestamp"      => $model->timestamp->format("r"),
      "version_info"   => $model->version_info,
    ], Common::prettyJSONIfBrowser());
  }

}
