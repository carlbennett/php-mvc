#!/usr/bin/php
<?php

namespace CarlBennett\MVC\Tests {

  function main($argc, $argv) {
    assert_options(ASSERT_CALLBACK, function(){
      echo "Unit test failed: " . __FILE__ . "\n";
      exit(1);
    });

    echo "Unit test succeeded: " . __FILE__ . "\n";
    return 0;
  }

  exit(main($argc, $argv));
}
