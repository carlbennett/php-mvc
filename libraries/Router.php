<?php

namespace CarlBennett\MVC\Libraries;

use \CarlBennett\MVC\Libraries\Exceptions\ControllerNotFoundException;
use \CarlBennett\MVC\Libraries\HTTPHeader;
use \DateTime;
use \DateTimeZone;
use \SplObjectStorage;
use \UnexpectedValueException;
use \http\Cookie;

class Router {

  protected $hostname;
  protected $pathArray;
  protected $pathString;
  protected $queryArray;
  protected $queryString;
  protected $requestBodyArray;
  protected $requestBodyMimeType;
  protected $requestBodyString;
  protected $requestCookies;
  protected $requestMethod;
  protected $requestURI;
  protected $responseCode;
  protected $responseContent;
  protected $responseCookies;
  protected $responseHeaders;
  protected $routes;

  public function __construct() {
    $this->hostname = getenv("HTTP_HOST");

    if (empty($this->hostname)) {
      $this->hostname = getenv("SERVER_NAME");
    }

    $this->requestMethod = getenv("REQUEST_METHOD");
    $this->requestURI    = getenv("REQUEST_URI");

    $cursor = strpos($this->requestURI, "?");
    if ($cursor !== false) {
      $this->pathString  = substr($this->requestURI, 0, $cursor);
      $this->queryString = substr($this->requestURI, $cursor + 1);
    } else {
      $this->pathString  = $this->requestURI;
      $this->queryString = "";
    }

    $this->pathArray = explode("/", $this->pathString);

    parse_str($this->queryString, $this->queryArray);

    $this->requestBodyArray    = $this->_getRequestBodyArray();
    $this->requestBodyMimeType = getenv("CONTENT_TYPE");
    $this->requestBodyString   = $this->_getRequestBodyString();
    $this->requestCookies      = new Cookie(getenv("HTTP_COOKIE"));
    $this->responseCode        = 500;
    $this->responseContent     = "";
    $this->responseCookies     = new SplObjectStorage();
    $this->responseHeaders     = new SplObjectStorage();
    $this->routes              = [];
  }

  private function _getRequestBodyString() {
    $buffer = "";
    $len    = getenv("CONTENT_LENGTH");

    if ($len === false) {

      $stdin  = fopen("php://input", "rb");
      $buffer = stream_get_contents($stdin);

    } else {

      $i          = 0;
      $len        = (int) $len;
      $chunk_size = 8192; // default is 8192 according to PHP documentation
      $stdin      = fopen("php://input", "r");

      while (!feof($stdin) && $i < $len) {
        $buffer .= fread($stdin, $chunk_size);
      }

    }

    fclose($stdin);

    return $buffer;
  }

  private function _getRequestBodyArray() {
    $json = (stripos($this->requestBodyMimeType, "application/json") !== false
      || stripos($this->requestBodyMimeType, "text/json") !== false);

    $enc = (stripos($this->requestBodyMimeType,
      "application/x-www-form-urlencoded") !== false);

    if ($enc) {

      $buffer = null;
      parse_str($this->requestBodyString, $buffer);
      return $buffer;

    } else if ($json) {
      return json_decode($this->requestBodyString);
    } else {
      return null;
    }
  }

  public function addResponseContent($buffer) {
    $this->responseContent .= $buffer;
  }

  public function addRoute($pattern, $controller) {
    $this->routes[$pattern] = $controller;
  }

  public function deleteRoute($pattern) {
    unset($this->routers[$pattern]);
  }

  public function getHostname() {
    return $this->hostname;
  }

  public function getRequestCookie($name) {
    return $this->requestCookies->getCookie($name);
  }

  public function getRequestCookies() {
    return $this->requestCookies;
  }

  public function getRequestHeader($name) {
    return getenv("HTTP_" . str_replace("-", "_", strtoupper($name)));
  }

  public function getRequestMethod() {
    return $this->requestMethod;
  }

  public function getRequestPathArray() {
    return $this->pathArray;
  }

  public function getRequestPathExtension() {
    return pathinfo($this->pathString, PATHINFO_EXTENSION);
  }

  public function getRequestPathString($with_extension = true) {
    if ($with_extension || strpos($this->pathString, ".") === false) {
      return $this->pathString;
    } else {
      return substr($this->pathString, 0, strrpos($this->pathString, "."));
    }
  }

  public function getRequestBodyArray() {
    return $this->requestBodyArray;
  }

  public function getRequestBodyString() {
    return $this->requestBodyString;
  }

  public function getRequestQueryArray() {
    return $this->queryArray;
  }

  public function getRequestQueryString() {
    return $this->queryString;
  }

  public function getRequestURI() {
    return $this->requestURI;
  }

  public function route() {
    $path   = $this->getRequestPathString(true);
    $target = null;

    foreach ($this->routes as $route => $controller) {
      if (preg_match($route, $path) === 1) {
        $target = $controller;
        break;
      }
    }

    var_dump($target);die();
  }

  public function routeOld(Pair &$redirect = null) {
    $pathArray = $this->getRequestPathArray();
    $path      = (isset($pathArray[1]) ? $pathArray[1] : null);
    $subpath   = (isset($pathArray[2]) ? $pathArray[2] : null);
    Logger::setTransactionName(
      $path . (isset($subpath) ? "/" . $subpath : "")
    );

    ob_start();

    if (Common::$config->phpmvc->maintenance[0]) {
      $controller = new MaintenanceController(
        Common::$config->phpmvc->maintenance[1]
      );
    } else if (isset($redirect)) {
      $controller = new RedirectController(
        $redirect->getKey(), $redirect->getValue()
      );
    } else {
      try {
        switch ($path) {
          case "status": case "status.json": case "status.txt":
            $controller = new StatusController();
          break;
          default:
            throw new ControllerNotFoundException($path);
        }
      } catch (ControllerNotFoundException $e) {
        $controller = new HTTP404Controller();
      }
    }

    // Prevent clickjacking globally:
    $this->setResponseHeader("X-Frame-Options", "DENY");

    $controller->run($this);
    $this->addResponseContent(ob_get_contents());

    ob_end_clean();
  }

  public function send() {
    http_response_code($this->responseCode);

    foreach ($this->responseHeaders as $header) {
      header($header->getName() . ": " . $header->getValue());
    }

    foreach ($this->responseCookies as $cookie) {
      header("Set-Cookie: " . $cookie->__toString());
    }

    echo $this->responseContent;
  }

  public function setResponseCode($code) {
    $this->responseCode = $code;
  }

  public function setResponseContent($buffer) {
    $this->responseContent = $buffer;
  }

  public function setResponseCookie($k, $v, $ttl, $httpOnly, $s, $dns, $p) {
    $flags = 0;
    if ($httpOnly) $flags |= Cookie::HTTPONLY;
    if ($s)        $flags |= Cookie::SECURE;

    $domain = (empty($dns) ? $this->getHostname() : $dns);

    $path = (empty($p) ? "/" : $p);

    $cookie = new Cookie();
    $cookie->setCookie($k, $v);
    $cookie->setDomain($domain);
    $cookie->setFlags($flags);
    $cookie->setMaxAge($ttl);
    $cookie->setPath($path);

    $this->responseCookies->attach($cookie);
  }

  public function setResponseHeader($arg1, $arg2 = null) {
    if ($arg1 instanceof HTTPHeader) {
      $this->responseHeaders->attach($arg1);
    } else if (is_string($arg1) && is_string($arg2)) {
      $this->responseHeaders->attach(new HTTPHeader($arg1, $arg2));
    } else {
      throw new UnexpectedValueException(
        "Arguments given must be two strings or an HTTPHeader object", -1
      );
    }
  }

  public function setResponseTTL($ttl) {
    $ttl = (int) $ttl;

    if ($ttl < 0) {
      throw new UnexpectedValueException(
        "Argument must be equal to or greater than zero", -1
      );
    }

    $dtz = new DateTimeZone("GMT");

    if ($ttl > 0) {
      $expires = new DateTime("+" . $ttl . " second");
    } else {
      $expires = new DateTime("@0");
    }
    $expires->setTimezone($dtz);

    $this->setResponseHeader("Cache-Control", "max-age=" . $ttl);
    $this->setResponseHeader("Expires", $expires->format("D, d M Y H:i:s e"));
    $this->setResponseHeader("Pragma", "max-age=" . $ttl);
  }

}
