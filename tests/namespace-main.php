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
    assert_options(ASSERT_CALLBACK, function(){
      echo "Unit test failed: " . __FILE__ . "\n";
      exit(1);
    });

    ob_start();
    $c = new \CarlBennett\MVC\Tests\Controllers\TestController();
    $c->run();
    assert(ob_get_clean() === "The test controller is being tested.\n");

    ob_start();
    $c = new \CarlBennett\MVC\Tests\Controllers\HTTP404();
    $c->run();
    assert(ob_get_clean() === "404 Not Found\n");

    return 0;
  }

  exit(main($argc, $argv));

}
