<?php

namespace CarlBennett\MVC;

use \CarlBennett\MVC\Libraries\Cache;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Exceptions\BaseException;
use \CarlBennett\MVC\Libraries\Exceptions\ClassNotFoundException;
use \CarlBennett\MVC\Libraries\Logger;
use \CarlBennett\MVC\Libraries\Router;
use \ReflectionClass;

function main() {

  spl_autoload_register(function($className){
    $path = $className;
    if (substr($path, 0, 15) == "CarlBennett\\MVC") $path = substr($path, 16);
    $cursor = strpos($path, "\\");
    if ($cursor !== false) {
      $path = strtolower(substr($path, 0, $cursor)) . substr($path, $cursor);
    }
    $path = str_replace("\\", "/", $path);
    $classShortName = $path;
    $docroot = getenv("DOCUMENT_ROOT");
    if (empty($docroot)) $docroot = ".";
    $path = $docroot . "/" . $path . ".php";
    if (!file_exists($path)) {
      trigger_error("Class not found: " . $classShortName, E_USER_ERROR);
    }
    require_once($path);
  });

  set_exception_handler(function($e){
    while (ob_get_level()) ob_end_clean();
    if ($e instanceof BaseException) {
      http_response_code($e->getHTTPResponseCode());
    } else {
      http_response_code(500);
    }
    header("Cache-Control: max-age=0,must-revalidate,no-cache,no-store");
    header("Content-Type: application/json;charset=utf-8");
    header("Expires: 0");
    header("Pragma: max-age=0");
    if ($e instanceof BaseException) {
      $additional_headers = $e->getHTTPResponseHeaders();
      foreach ($additional_headers as $key => $val) {
        header($key . ": " . $val);
      }
    }
    $json = [
      "error" => [
        "exception" => (new ReflectionClass($e))->getShortName(),
        "code" => $e->getCode(),
        "message" => $e->getMessage(),
      ],
    ];
    if (ini_get("display_errors")) {
      $json["error"]["file"] = Common::stripLeftPattern(
        $e->getFile(), "/home/nginx/php-mvc"
      );
      $json["error"]["line"] = $e->getLine();
    }
    Logger::logMetric("error_data", json_encode($json, JSON_PRETTY_PRINT));
    Logger::logException($e);
    exit(json_encode($json, Common::prettyJSONIfBrowser()));
  });

  set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext){
    if (!(error_reporting() & $errno)) return false;
    while (ob_get_level()) ob_end_clean();
    http_response_code(500);
    header("Cache-Control: max-age=0,must-revalidate,no-cache,no-store");
    header("Content-Type: application/json;charset=utf-8");
    header("Expires: 0");
    header("Pragma: max-age=0");
    $json = [
      "error" => [
        "exception" => null,
        "code" => Common::phpErrorName($errno),
      ],
    ];
    if (ini_get("display_errors")) {
      $json["error"]["message"] = $errstr;
      $json["error"]["file"] = Common::stripLeftPattern(
        $errfile, "/home/nginx/php-mvc"
      );
      $json["error"]["line"] = $errline;
    }
    Logger::logMetric("error_data", json_encode($json, JSON_PRETTY_PRINT));
    Logger::logError($errno, $errstr, $errfile, $errline, $errcontext);
    exit(json_encode($json, Common::prettyJSONIfBrowser()));
  });

  Logger::initialize();

  Common::$config   = json_decode(file_get_contents("./config.json"));
  Common::$cache    = new Cache();
  Common::$database = null;

  $router = new Router();
  $router->route();
  $router->send();

}

main();
