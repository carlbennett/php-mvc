<?php

namespace CarlBennett\MVC\Libraries;

class DateTime extends \DateTime implements \JsonSerializable {

  public function jsonSerialize() {
    return [
      'iso'  =>       $this->format(DATE_RFC2822),
      'tz'   =>       $this->format('T'),
      'sql'  =>       $this->format('Y-m-d H:i:s.u'),
      'unix' => (int) $this->format('U'),
    ];
  }

}
