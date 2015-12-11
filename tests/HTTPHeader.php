#!/usr/bin/php
<?php

namespace CarlBennett\MVC\Tests\HTTPHeader {

  use \CarlBennett\MVC\Libraries\HTTPHeader;
  use \CarlBennett\MVC\Libraries\Pair;

  function main($argc, $argv) {
    require_once("./_loader.php");
    assert_options(ASSERT_CALLBACK, function(){
      echo "Unit test failed: " . __FILE__ . "\n";
      exit(1);
    });

    $obj = new HTTPHeader("foo", "bar");
    assert($obj instanceof Pair);
    assert($obj->getName()  === "foo");
    assert($obj->getValue() === "bar");
    assert((string) $obj    === "foo: bar\n");
    unset($obj);

    return 0;
  }

  exit(main($argc, $argv));
}
