# php-mvc
[![Build Status](https://travis-ci.org/carlbennett/php-mvc.svg?branch=master)](https://travis-ci.org/carlbennett/php-mvc)

**php-mvc** is a PHP library with its intended purpose to act as a backend and
frontend framework for a web content management system (CMS) or REST API.

## Installation
This library is available via [composer](https://getcomposer.org).

```sh
composer require carlbennett/php-mvc
composer install
```

## Usage
The following is an example of including this library in your project. This
assumes you have already installed the library via composer.

```php
<?php

namespace MySuperAwesomeProject;

use \CarlBennett\MVC\Libraries\GlobalErrorHandler;

GlobalErrorHandler::createOverrides();

trigger_error("This library generates detailed error messages!", E_USER_ERROR);
```
