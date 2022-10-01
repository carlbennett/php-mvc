<?php

namespace CarlBennett\MVC;

\spl_autoload_register(function($className)
{
    $path = $className;
    if (\substr($path, 0, 15) == "CarlBennett\\MVC") $path = \substr($path, 16);
    $path = \str_replace("\\", \DIRECTORY_SEPARATOR, $path);
    $classShortName = $path;
    $path = '../src/' . $path . '.php';
    if (!\file_exists($path))
    {
        \trigger_error('Class not found: ' . $classShortName, \E_USER_ERROR);
    }
    require_once($path);
});
