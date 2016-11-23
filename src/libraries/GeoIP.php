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

use \RuntimeException;
use \UnexpectedValueException;

class GeoIP {

  public static function get($hostname) {
    // Check for GeoIP extension
    if (!function_exists('geoip_record_by_name')) {
      throw new RuntimeException('GeoIP extension not installed');
    }

    // Check for invalid input
    if (!filter_var($hostname, FILTER_VALIDATE_IP)) {
      throw new UnexpectedValueException('Input must be IPv4 or IPv6');
    }

    // Worth noting is that private and reserved IPs could be validated
    // against filter_var() as well, but we let the GeoIP extension do its
    // thing with those types of IPs. It'll most likely always return false
    // for them.

    // Get GeoIP without throwing E_NOTICE error:
    $error_reporting = error_reporting();
    error_reporting($error_reporting & ~E_NOTICE);
    $geoinfo = geoip_record_by_name($ip);
    error_reporting($error_reporting);

    // Add region name and sort the array if successfully retrieved GeoIP info
    if ($geoinfo) {
      if (!empty($geoinfo['region'])) {
        $geoinfo['region_name'] = geoip_region_name_by_code(
          $geoinfo['country_code'], $geoinfo['region']
        );
      }

      ksort($geoinfo);
    }

    // Return the GeoIP information
    return $geoinfo;
  }

}
