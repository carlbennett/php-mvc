<?php

namespace CarlBennett\MVC\Models;

use \CarlBennett\MVC\Libraries\Model;

class Redirect extends Model {

  public $redirect_code;
  public $redirect_to;

  public function __construct($redirect_code, $redirect_to) {
    parent::__construct();
    $this->redirect_code = $redirect_code;
    $this->redirect_to   = $redirect_to;
  }

}
