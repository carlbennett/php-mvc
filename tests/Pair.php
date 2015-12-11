#!/usr/bin/php
<?php

namespace CarlBennett\MVC\Tests\HTTPHeader {

  use \CarlBennett\MVC\Libraries\Pair;

  function main($argc, $argv) {
    require_once("./_loader.php");
    assert_options(ASSERT_CALLBACK, function(){
      echo "Unit test failed: " . __FILE__ . "\n";
      exit(1);
    });

    $obj = new Pair("foo", "bar");
    assert($obj->getKey()   === "foo");
    assert($obj->getValue() === "bar");
    unset($obj);

    echo "Unit test succeeded: " . __FILE__ . "\n";
    return 0;
  }

  exit(main($argc, $argv));
}
