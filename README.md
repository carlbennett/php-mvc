php-mvc
=======

[![Build Status](https://travis-ci.org/carlbennett/php-mvc.svg?branch=master)]
(https://travis-ci.org/carlbennett/php-mvc)

Preface
-------
**php-mvc** is a web content management system (CMS) developed by
[@carlbennett](https://github.com/carlbennett) for use as a frontend and
backend.

Installation
------------
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

Disclaimer
----------
This library is currently undergoing a revamp of restructure in preparation for
making it compatible with _composer_. For a stable version, please see release
[1.0.0](https://github.com/carlbennett/php-mvc/releases/tag/1.0.0).
