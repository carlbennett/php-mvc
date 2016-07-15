# php-mvc

[![Build Status](https://travis-ci.org/carlbennett/php-mvc.svg?branch=master)]
(https://travis-ci.org/carlbennett/php-mvc)

**php-mvc** is a PHP library with its intended purpose to act as a backend and
frontend framework for a web content management system (CMS) or REST API.

## Installation
This library is available via [composer](https://getcomposer.org). Although it
is currently not available on _Packagist_, you can manually add this library to
your project by adding the following JSON to your `composer.json` file.

```json
{
    "require": {
        "carlbennett/php-mvc": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/carlbennett/php-mvc"
        }
    ]
}
```

Once added to your composer as specified above, you should then be able to run
`composer install` to download and install this library to your project.
