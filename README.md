# php-mvc
[![Build Status](https://github.com/carlbennett/php-mvc/workflows/php-mvc/badge.svg)](https://github.com/carlbennett/php-mvc/actions?query=workflow%3Aphp-mvc)

**php-mvc** is a PHP standard library used with [@carlbennett](https://githhub.com/carlbennett)'s projects. The aspirations of this library are for a project website to include it as middleware.

## Installation
This library is available via [composer](https://getcomposer.org) from [packagist](https://packagist.org/packages/carlbennett/php-mvc).

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
use \CarlBennett\MVC\Libraries\Router;
use \CarlBennett\MVC\Libraries\Template;
use \RuntimeException;

// Can be used to route requests.

$router = new Router(
    "\\MySuperAwesomeProject\\Controllers\\",
    "\\MySuperAwesomeProject\\Views\\"
);
$router->addRoute( // URLs: /home, /home.htm, /home.html
    // pattern, model, view
    '#^/home(?:\.html?)?$#', 'Home', 'HomeHtml'
);
$router->route();
$router->send();

// Custom template engine powered by pure PHP, utilizes include() and output buffers.

$context = null; // empty context, used to pass state to template
(new Template($context, 'HelloWorld'))->render(); // prints ./src/Templates/HelloWorld.phtml to the client.

// A dynamic error handler. Prints JSON if display_errors is ON, a friendly html page if OFF.

GlobalErrorHandler::createOverrides();
throw new RuntimeException('test');
```
