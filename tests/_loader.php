<?php

namespace CarlBennett\MVC {

  spl_autoload_register(function($className){
    $path = $className;
    if (substr($path, 0, 15) == "CarlBennett\\MVC") $path = substr($path, 16);
    $cursor = strpos($path, "\\");
    if ($cursor !== false) {
      $path = strtolower(substr($path, 0, $cursor)) . substr($path, $cursor);
    }
    $path = str_replace("\\", "/", $path);
    $classShortName = $path;
    $path = "../src/" . $path . ".php";
    if (!file_exists($path)) {
      trigger_error("Class not found: " .$classShortName, E_USER_ERROR);
    }
    require_once($path);
  });

}
