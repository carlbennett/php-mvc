<?php

namespace CarlBennett\MVC\Models;

use \CarlBennett\MVC\Libraries\Model;

class Maintenance extends Model {

  public $message;

  public function __construct() {
    parent::__construct();
    $this->message = null;
  }

}
