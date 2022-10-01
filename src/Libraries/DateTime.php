<?php

namespace CarlBennett\MVC\Libraries;

use \DateTime as PHPDateTime;
use \DateTimeZone;
use \JsonSerializable;

class DateTime extends PHPDateTime implements JsonSerializable {

  const STRING_FORMAT = 'r';

  /**
   * format()
   * Extends the native format() function of PHP's DateTime class.
   * The second parameter allows converting to another timezone on the fly.
   *
   * @param $format The date format, passed into PHP's DateTime.format()
   * @param DateTimeZone $tz Optional timezone to convert to before formatting.
   *
   * @return string The formatted string optionally converted to a timezone.
   */
  public function format($format, DateTimeZone $tz = null) {
    if (!$tz) {
      return parent::format($format);
    } else {
      $dt = clone $this;
      $dt->setTimezone($tz);
      return $dt->format($format);
    }
  }

  /**
   * jsonSerialize()
   * Part of the JsonSerializable interface.
   * Changes the representation of this object when using json.
   *
   * @return string The data to be returned to json_encode().
   */
  public function jsonSerialize() {
    return array(
      'iso'  =>       $this->format(DATE_RFC2822),
      'sql'  =>       $this->format('Y-m-d H:i:s.u'),
      'tz'   =>       $this->format('T'),
      'unix' => (int) $this->format('U'),
    );
  }

  public function __toString() {
    return $this->format( self::STRING_FORMAT );
  }

}
