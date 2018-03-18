<?php

namespace CarlBennett\MVC\Libraries;

use \RuntimeException;

class Session {

    /**
     * Block instantiation of this class.
     */
    private function __construct() {}

    public static function initialize($server_string, $name, $ttl = 0) {

        // Storage medium for sessions
        ini_set('session.save_handler', 'memcached');
        ini_set('session.save_path', $server_string);

        // Set cookie lifetime using requested $ttl value
        ini_set('session.cookie_lifetime', $ttl);

        // Use HTTP cookies instead of GET/POST variables
        ini_set('session.use_cookies', 'On');

        // Ignore GET/POST variables for session ID retrieval
        ini_set('session.use_only_cookies', 'On');

        // Only accept server-generated IDs, reject client-generated/injections
        ini_set('session.use_strict_mode', 'On');

        // JavaScript not allowed to access session cookie
        ini_set('session.cookie_httponly', 'On');

        // Only HTTPS connections can use session cookies
        ini_set('session.cookie_secure', 'On');

        // Name for the session in cookies
        if (empty($name)) { $name = ini_get('session.name'); }
        session_name($name);

        // Start the session
        if (!session_start()) {
            throw new RuntimeException('Failed to start session');
        }

    }

}
