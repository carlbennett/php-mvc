<?php

namespace CarlBennett\MVC\Controllers;

use \CarlBennett\MVC\Libraries\Controller;
use \CarlBennett\MVC\Libraries\Router;
use \CarlBennett\MVC\Models\Maintenance as MaintenanceModel;
use \CarlBennett\MVC\Views\MaintenanceHtml as MaintenanceHtmlView;
use \CarlBennett\MVC\Views\MaintenanceJSON as MaintenanceJSONView;
use \CarlBennett\MVC\Views\MaintenancePlain as MaintenancePlainView;

class Maintenance extends Controller {

  protected $message;

  public function __construct($message) {
    parent::__construct();
    $this->message = $message;
  }

  public function run(Router &$router) {
    switch ($router->getRequestPathExtension()) {
      case "htm": case "html": case "":
        $view = new MaintenanceHtmlView();
      break;
      case "json":
        $view = new MaintenanceJSONView();
      break;
      case "txt":
        $view = new MaintenancePlainView();
      break;
      default:
        $view = new MaintenanceHtmlView();
    }
    $model = new MaintenanceModel();
    $model->message = $this->message;
    ob_start();
    $view->render($model);
    $router->setResponseCode(503);
    $router->setResponseTTL(0);
    $router->setResponseHeader("Content-Type", $view->getMimeType());
    $router->setResponseContent(ob_get_contents());
    ob_end_clean();
  }

}
