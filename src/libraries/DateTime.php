<?php

namespace CarlBennett\MVC\Libraries;

use \LogicException;
use \OutOfBoundsException;

class DateTime extends \DateTime implements \JsonSerializable, \ArrayAccess {

  public function jsonSerialize() {
    return [
      'iso'  =>       $this->format(DATE_RFC2822),
      'tz'   =>       $this->format('T'),
      'sql'  =>       $this->format('Y-m-d H:i:s.u'),
      'unix' => (int) $this->format('U'),
    ];
  }

  public function offsetExists($offset) {
    return array_key_exists($offset, [
      'iso'  => null,
      'tz'   => null,
      'sql'  => null,
      'unix' => null,
    ]);
  }

  public function offsetGet($offset) {
    switch ($offset) {
      case 'iso':  return       $this->format(DATE_RFC2822);
      case 'tz':   return       $this->format('T');
      case 'sql':  return       $this->format('Y-m-d H:i:s.u');
      case 'unix': return (int) $this->format('U');
    }
    throw new OutOfBoundsException();
  }

  public function offsetSet($offset, $value) {
    throw new LogicException(
  }

}
