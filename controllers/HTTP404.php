<?php

namespace CarlBennett\MVC\Controllers;

use \CarlBennett\MVC\Libraries\Controller;
use \CarlBennett\MVC\Libraries\Router;
use \CarlBennett\MVC\Models\HTTP404 as HTTP404Model;
use \CarlBennett\MVC\Views\HTTP404Html as HTTP404HtmlView;
use \CarlBennett\MVC\Views\HTTP404JSON as HTTP404JSONView;
use \CarlBennett\MVC\Views\HTTP404Plain as HTTP404PlainView;

class HTTP404 extends Controller {

  public function run(Router &$router) {
    switch ($router->getRequestPathExtension()) {
      case "htm": case "html": case "":
        $view = new HTTP404HtmlView();
      break;
      case "json":
        $view = new HTTP404JSONView();
      break;
      case "txt":
        $view = new HTTP404PlainView();
      break;
      default:
        $view = new HTTP404HtmlView();
    }
    $model = new HTTP404Model();
    ob_start();
    $view->render($model);
    $router->setResponseCode(404);
    $router->setResponseTTL(0);
    $router->setResponseHeader("Content-Type", $view->getMimeType());
    $router->setResponseContent(ob_get_contents());
    ob_end_clean();
  }

}
