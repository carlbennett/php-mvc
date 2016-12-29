<?php
/**
 *  php-mvc, a PHP micro-framework for use as a frontend and/or backend
 *  Copyright (C) 2015-2016  Carl Bennett
 *  This file is part of php-mvc.
 *
 *  php-mvc is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  php-mvc is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with php-mvc.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace CarlBennett\MVC\Libraries;

use \Exception;
use \StdClass;

final class GlobalErrorHandler {

  private static $overridden_error_handler;
  private static $overridden_exception_handler;

  public static $continue_chain = false;

  /**
   * Do not allow creating an object of this class. It's meant to be 100%
   * static. The way we enforce this rule is by declaring our constructor
   * as private.
   */
  private function __construct() {}

  public static function createOverrides() {
    self::$overridden_error_handler = set_error_handler(
      "\\CarlBennett\\MVC\\Libraries\\GlobalErrorHandler::errorHandler"
    );
    self::$overridden_exception_handler = set_exception_handler(
      "\\CarlBennett\\MVC\\Libraries\\GlobalErrorHandler::exceptionHandler"
    );
  }

  public static function errorHandler(
    $errno, $errstr, $errfile, $errline, $errcontext
  ) {
    // Don't handle this error if it's turned off administratively:
    if (!(error_reporting() & $errno)) return false;

    // Back out of any output buffers:
    while (ob_get_level()) ob_end_clean();

    // Determine error name from $errno:
    $_errno = self::phpErrorName($errno);

    // Create a context object:
    $context             = new StdClass();
    $context->errno      = $_errno;
    $context->errstr     = $errstr;
    $context->errfile    = $errfile;
    $context->errline    = $errline;
    $context->errcontext = $errcontext;
    $context->stacktrace = debug_backtrace();

    if (is_object($errcontext)) {
      $context->errcontext = get_class($context->errcontext);
    }

    // Remove our handler from the stack if present:
    if ($context->stacktrace[0]["function"] == "errorHandler"
      && $context->stacktrace[0]["type"] == "::"
      && $context->stacktrace[0]["class"]
        == "CarlBennett\\MVC\\Libraries\\GlobalErrorHandler") {
      array_shift($context->stacktrace);
    }

    // Gracefully back out of the user's request:
    self::gracefulExit($context);

    // Report this to the local web server's error log:
    // Ex: E_WARNING: something happened in file.php on line 123
    error_log($_errno . ": " . $errstr
      . " in " . $errfile . " on line " . $errline);

    // Call the previous handler:
    if (
      self::$continue_chain && is_callable(self::$overridden_error_handler)
    ) {
      call_user_func_array(self::$overridden_error_handler, func_get_args());
    }

    // Stop processing the rest of the application:
    exit();
  }

  public static function exceptionHandler($e) {
    if (PHP_VERSION >= 7.0 && !($e instanceof \Throwable)) {
      throw new Exception("Argument must inherit Throwable");
    } else if (PHP_VERSION < 7.0 && !($e instanceof Exception)) {
      throw new Exception("Argument must be an Exception or a child thereof");
    }

    // Back out of any output buffers:
    while (ob_get_level()) ob_end_clean();

    // Create a context object:
    $context             = new StdClass();
    $context->exception  = get_class($e);
    $context->code       = $e->getCode();
    $context->file       = $e->getFile();
    $context->line       = $e->getLine();
    $context->message    = $e->getMessage();
    $context->stacktrace = $e->getTrace();

    // Remove our handler from the stack if present:
    if ($context->stacktrace[0]["function"] == "exceptionHandler"
      && $context->stacktrace[0]["type"] == "::"
      && $context->stacktrace[0]["class"]
        == "CarlBennett\\MVC\\Libraries\\GlobalErrorHandler") {
      array_shift($context->stacktrace);
    }

    // Gracefully back out of the user's request:
    self::gracefulExit($context);

    // Report this to the local web server's error log:
    // Ex: Exception #123: something happened in file.php on line 123
    error_log(
      $context->exception
      . ($context->code !== 0 ? " #" . $context->code : "") . ": "
      . $context->message
      . " in " . $context->file
      . " on line " . $context->line
    );
    error_log(var_export($context->stacktrace, true));

    // Call the previous handler:
    if (
      self::$continue_chain && is_callable(self::$overridden_exception_handler)
    ) {
      call_user_func_array(
        self::$overridden_exception_handler, func_get_args()
      );
    }

    // Stop processing the rest of the application:
    exit();
  }

  private static function gracefulExit(StdClass &$context) {
    // Return with a 500 Internal Server Error:
    http_response_code(500);

    // Tell the browser not to cache this response:
    header("Cache-Control: max-age=0,must-revalidate,no-cache,no-store");
    header("Expires: 0");
    header("Pragma: max-age=0");

    // Respond with some content about the problem (don't whitepage):
    if (!ini_get("display_errors")) {
      header("Content-Type: text/html;charset=utf-8");
      include(getenv("DOCUMENT_ROOT") . "/templates/GlobalErrorHandler.phtml");
    } else {
      if (PHP_VERSION >= 5.4) {
        header("Content-Type: application/json;charset=utf-8");
        echo json_encode($context, JSON_PRETTY_PRINT) . "\n";
      } else {
        header("Content-Type: text/plain;charset=utf-8");
        var_dump($context);
      }
    }
  }

  public static function phpErrorName($errno) {
    switch ($errno) {
      case E_ERROR:             return "E_ERROR";             /* 1     */
      case E_WARNING:           return "E_WARNING";           /* 2     */
      case E_PARSE:             return "E_PARSE";             /* 4     */
      case E_NOTICE:            return "E_NOTICE";            /* 8     */
      case E_CORE_ERROR:        return "E_CORE_ERROR";        /* 16    */
      case E_CORE_WARNING:      return "E_CORE_WARNING";      /* 32    */
      case E_COMPILE_ERROR:     return "E_COMPILE_ERROR";     /* 64    */
      case E_COMPILE_WARNING:   return "E_COMPILE_WARNING";   /* 128   */
      case E_USER_ERROR:        return "E_USER_ERROR";        /* 256   */
      case E_USER_WARNING:      return "E_USER_WARNING";      /* 512   */
      case E_USER_NOTICE:       return "E_USER_NOTICE";       /* 1024  */
      case E_STRICT:            return "E_STRICT";            /* 2048  */
      case E_RECOVERABLE_ERROR: return "E_RECOVERABLE_ERROR"; /* 4096  */
      case E_DEPRECATED:        return "E_DEPRECATED";        /* 8192  */
      case E_USER_DEPRECATED:   return "E_USER_DEPRECATED";   /* 16384 */
      case E_ALL:               return "E_ALL";               /* 32767 */
      default:                  return "E_UNKNOWN";           /* ????? */
    }
  }

}
