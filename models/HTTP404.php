<?php

namespace CarlBennett\MVC\Models;

use \CarlBennett\MVC\Libraries\Model;

class HTTP404 extends Model {

  public $user_session;

  public function __construct() {
    parent::__construct();
    $this->user_session = null;
  }

}
