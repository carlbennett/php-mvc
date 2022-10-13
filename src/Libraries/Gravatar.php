<?php
/**
 *  php-mvc, a PHP micro-framework for use as a frontend and/or backend
 *  Copyright (C) 2015-2022  Carl Bennett
 *  This file is part of php-mvc.
 *
 *  php-mvc is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  php-mvc is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with php-mvc.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace CarlBennett\MVC\Libraries;

use \JsonSerializable;
use \UnexpectedValueException;

class Gravatar implements JsonSerializable
{
  public const GRAVATAR_BASE_URL = '//www.gravatar.com/avatar/';

  protected string $email;

  public function __construct(string $email)
  {
    $this->setEmail($email);
  }

  public function getEmail() : string
  {
    return $this->email;
  }

  public function getHash() : string
  {
    return \hash('md5', \strtolower(\trim($this->email)));
  }

  public function getUrl($size = null, $default = null, $forcedefault = null, $rating = null) : string
  {
    $url = self::GRAVATAR_BASE_URL . $this->getHash();
    $args = [];
    if (!is_null($size))         $args['s'] = $size;
    if (!is_null($default))      $args['d'] = $default;
    if (!is_null($forcedefault)) $args['f'] = $forcedefault;
    if (!is_null($rating))       $args['r'] = $rating;
    $query = \http_build_query($args);
    if ($query) $url .= '?' . $query;
    return $url;
  }

  public function jsonSerialize() : mixed
  {
    return [
      'email' => $this->getEmail(),
      'hash' => $this->getHash(),
      'url' => $this->getUrl(),
    ];
  }

  public function setEmail(string $value) : void
  {
    if (!\filter_var($value, \FILTER_VALIDATE_EMAIL))
    {
      throw new UnexpectedValueException();
    }

    $this->email = $value;
  }

  public function __serialize() : array
  {
    return $this->jsonSerialize();
  }

  public function __unserialize(array $value) : void
  {
    $this->setEmail($value[0] ?? '');
  }
}
