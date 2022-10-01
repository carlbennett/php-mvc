<?php

namespace CarlBennett\MVC\Libraries;

use \DateTime as PHPDateTime;
use \DateTimeZone;
use \JsonSerializable;
use \Stringable;

class DateTime extends PHPDateTime implements JsonSerializable, Stringable
{
  public const STRING_FORMAT = 'r';

  /**
   * format()
   * Extends the native format() function of PHP's DateTime class.
   * The second parameter allows converting to another timezone on the fly.
   *
   * @param string $format The date format, passed into PHP's DateTime.format()
   * @param DateTimeZone $tz Optional timezone to convert to before formatting.
   *
   * @return string The formatted string optionally converted to a timezone.
   */
  public function format(string $format, DateTimeZone $tz = null) : string
  {
    if (!$tz)
    {
      return parent::format($format);
    }
    else
    {
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
   * @return mixed The data to be returned to json_encode().
   */
  public function jsonSerialize() : mixed
  {
    return [
      'iso'  =>       $this->format(\DATE_RFC2822),
      'sql'  =>       $this->format('Y-m-d H:i:s.u'),
      'tz'   =>       $this->format('T'),
      'unix' => (int) $this->format('U'),
    ];
  }

  public function __toString() : string
  {
    return $this->format(self::STRING_FORMAT);
  }
}
