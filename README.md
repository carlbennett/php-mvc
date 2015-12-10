php-mvc
=======

[![Build Status](https://travis-ci.org/carlbennett/php-mvc.svg?branch=master)](https://travis-ci.org/carlbennett/php-mvc)

Preface
-------
php-mvc is a web content management system (CMS) developed by Carl Bennett for
use in his PHP projects. It's compatible for use as a frontend and backend.

Installation
------------

1. Clone this repository to a local directory on your development environment.
 - Recommended location: `/home/nginx/php-mvc/`
2. Setup an nginx/php-fpm web server using
   [nginx-conf](https://github.com/carlbennett/nginx-conf) as the config.
 - Modify the example server config to use `local.carlbennett.me` instead.
 - Add the following to the `local.carlbennett.me` server config file:<br/>
   `include conf.d/php.conf;`
 - Add the following to the `local.carlbennett.me` server config file:<br/>
   `location / { try_files /static$uri /main.php$is_args$args; }`
3. Install additional php modules:
 - php-gmp
 - php-mbstring
 - php-mcrypt
 - php-memcache
 - php-memcached
 - php-mysqlnd
 - php-pdo
 - php-pecl-geoip
 - php-pecl-http
 - php-pecl-jsonc
4. Start nginx and php-fpm on your server and ensure they begin running.
5. Import and setup the sample database.
6. Copy `/config.sample.json` to `/config.json` and modify it to your
   environment.
7. Try accessing this endpoint:
   [local.carlbennett.me](https://local.carlbennett.me)
 - You may need to modify your `/etc/hosts` file if your development
   environment is not your localhost.

Without a Web server
--------------------
If you want to try this out without a web server, I've gotten the following
command to work successfully on my local development machine:

```sh
REMOTE_ADDR="8.8.8.8" REQUEST_URI="/status" HTTP_USER_AGENT="Chrome" \
  php -d display_errors=On -d date.timezone=UTC -f ./main.php;echo
```
