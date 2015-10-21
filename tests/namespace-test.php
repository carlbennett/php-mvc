#!/usr/bin/php
<?php

namespace CarlBennett\MVC\Tests\Controllers {

  class TestController extends \CarlBennett\MVC\Tests\Libraries\Controller {

    public function run() {
      echo "The test controller is being tested.\n";
    }

  }

  class HTTP404 extends \CarlBennett\MVC\Tests\Libraries\Controller {

    public function run() {
      echo "404 Not Found\n";
    }

  }

}

namespace CarlBennett\MVC\Tests\Libraries {

  abstract class Controller {

    public abstract function run();

  }

}

namespace CarlBennett\MVC\Tests {

  function main($argc, $argv) {
    $c = new \CarlBennett\MVC\Tests\Controllers\TestController();
    $c->run();

    $c = new \CarlBennett\MVC\Tests\Controllers\HTTP404();
    $c->run();

    return 0;
  }

  exit(main($argc, $argv));

}
