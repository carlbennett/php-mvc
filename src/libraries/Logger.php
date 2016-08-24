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

use \Exception;

class Logger {

    protected static $newrelic_available = false;

    /**
     * This constructor is private because Logger is entirely static.
     *
     * This will cause errors if instantiation is attempted.
     */
    private function __construct() {}

    public static function getTimingHeader($tags = true) {
        // If $tags is true, then <script> will be included
        if (extension_loaded("newrelic")) {
            return newrelic_get_browser_timing_header($tags);
        } else {
            return "";
        }
    }

    public static function getTimingFooter($tags = true) {
        // If $tags is true, then <script> will be included
        if (extension_loaded("newrelic")) {
            return newrelic_get_browser_timing_footer($tags);
        } else {
            return "";
        }
    }

    public static function getTraceString() {
        ob_start();
        debug_print_backtrace();
        return ob_get_clean();
    }

    public static function initialize() {
        if (extension_loaded("newrelic")) {
            newrelic_disable_autorum();
            self::$newrelic_available = true;
            self::setTransactionName("null");
            self::logMetric("REMOTE_ADDR", getenv("REMOTE_ADDR"));
        }
    }

    public static function logError($no, $str, $file, $line, $obj) {
        if (self::$newrelic_available) {
            newrelic_notice_error($no, $str, $file, $line, $obj);
        }
    }

    public static function logException(Exception $exception) {
        if (self::$newrelic_available) {
            newrelic_notice_error($exception->getMessage(), $exception);
        }
    }

    public static function logMetric($key, $val) {
        if (self::$newrelic_available) {
            newrelic_add_custom_parameter($key, $val);
        }
    }

    public static function setTransactionName($name) {
        if (self::$newrelic_available) {
            newrelic_name_transaction($name);
        }
    }

}
