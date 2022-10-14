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

use \CarlBennett\MVC\Libraries\Exceptions\TemplateNotFoundException;
use \CarlBennett\MVC\Libraries\Logger;

class Template
{
    protected mixed $context;
    protected bool $log_metric;
    protected string $template;

    protected static string $template_directory = 'Templates';
    protected static string $template_extension = '.phtml';

    public function __construct(mixed &$context, string $template, bool $log_metric = true)
    {
        $this->setContext($context);
        $this->setLogMetric($log_metric);
        $this->setTemplate($template);
    }

    public function getContext() : mixed
    {
        return $this->context;
    }

    public function getLogMetric() : bool
    {
        return $this->log_metric;
    }

    public function getTemplate() : string
    {
        return $this->template;
    }

    public static function getTemplateDirectory() : string
    {
        return self::$template_directory;
    }

    public static function getTemplateExtension() : string
    {
        return self::$template_extension;
    }

    public function render() : void
    {
        $cwd = \getcwd();
        try
        {
            \chdir($cwd . \DIRECTORY_SEPARATOR . self::$template_directory);
            if (!\file_exists($this->template))
            {
                throw new TemplateNotFoundException($this);
            }
            require($this->template);
        }
        finally
        {
            \chdir($cwd);
        }
    }

    public function setContext(mixed &$context) : void
    {
        $this->context = $context;
    }

    public function setLogMetric(bool $value) : void
    {
        $this->log_metric = $value;
    }

    public function setTemplate(string $template) : void
    {
        $this->template = \sprintf('.%s%s%s',
            \DIRECTORY_SEPARATOR,
            \str_replace('/', \DIRECTORY_SEPARATOR, $template),
            self::$template_extension
        );

        if ($this->log_metric)
        {
            Logger::logMetric('Template', $template);
        }
    }

    public static function setTemplateDirectory(string $value) : void
    {
        self::$template_directory = $value;
    }

    public static function setTemplateExtension(string $value) : void
    {
        self::$template_extension = $value;
    }
}
