<?php
/**
 *  php-mvc, a PHP micro-framework for use as a frontend and/or backend
 *  Copyright (C) 2015-2016  Carl Bennett
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

use \UnexpectedValueException;

class IP {

    public static function checkCIDR($ip, $cidr) {
        $cidr_ip = substr($cidr, 0, strpos($cidr, "/"));
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
            && filter_var($cidr_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return self::checkCIDRv4($ip, $cidr);
        } else if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
            && filter_var($cidr_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return self::checkCIDRv6($ip, $cidr);
        } else {
            throw new UnexpectedValueException(
                "IP and CIDR do not match IP version"
            );
        }
    }

    public static function checkCIDRArray($ip, &$cidr_array) {
        $result = false;
        foreach ($cidr_array as $cidr) {
            $cidr_ip = substr($cidr, 0, strpos($cidr, "/"));
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) &&
                filter_var($cidr_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $result = self::checkCIDRv4($ip, $cidr);
            } else if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) &&
                filter_var($cidr_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $result = self::checkCIDRv6($ip, $cidr);
            }
            if ($result) {
                return $result;
            }
        }
        return $result;
    }

  public static function checkCIDRv4($ip, $cidr) {
      // <http://stackoverflow.com/a/594134/2367146>
      list ($subnet, $bits) = explode("/", $cidr);
      $ip      = ip2long($ip);
      $subnet  = ip2long($subnet);
      $mask    = -1 << (32 - $bits);
      $subnet &= $mask;
      return ($ip & $mask) == $subnet;
  }

  public static function checkCIDRv6($ip, $cidr) {
      // <http://stackoverflow.com/a/7951507/2367146>
      $ip       = inet_pton($ip);
      $binaryip = self::inet_to_bits($ip);

      list($net, $maskbits) = explode("/", $cidr);
      $net       = inet_pton($net);
      $binarynet = self::inet_to_bits($net);

      $ip_net_bits = substr($binaryip , 0, $maskbits);
      $net_bits    = substr($binarynet, 0, $maskbits);

      return ($ip_net_bits === $net_bits);
  }

  private static function inet_to_bits($inet) {
      // <http://stackoverflow.com/a/7951507/2367146>
      $unpacked = unpack("A16", $inet);
      $unpacked = str_split($unpacked[1]);
      $binaryip = "";
      foreach ($unpacked as $chr) {
          $binaryip .= str_pad(decbin(ord($chr)), 8, "0", STR_PAD_LEFT);
      }
      return $binaryip;
  }

}
