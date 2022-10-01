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
use \SplObjectStorage;

final class Template {

    public const TEMPLATE_DIRECTORY = "templates";
    public const TEMPLATE_EXTENSION = ".phtml";

    protected $additional_css;
    protected $context;
    protected $opengraph;
    protected $template;

    public function __construct(&$context, $template) {
        $this->additional_css = [];
        $this->opengraph      = new SplObjectStorage();
        $this->setContext($context);
        $this->setTemplate($template);
    }

    public function getContext() {
        return $this->context;
    }

    public function getTemplate() {
        return $this->template;
    }

    public function render() {
        $cwd = getcwd();
        try {
            chdir($cwd . DIRECTORY_SEPARATOR . self::TEMPLATE_DIRECTORY);
            if (!file_exists($this->template)) {
                throw new TemplateNotFoundException($this);
            }
            require($this->template);
        } finally {
            chdir($cwd);
        }
    }

    public function setContext(&$context) {
        $this->context = $context;
    }

    public function setTemplate($template) {
        $this->template = "." . DIRECTORY_SEPARATOR
            . str_replace("/", DIRECTORY_SEPARATOR, $template)
            . self::TEMPLATE_EXTENSION;
        Logger::logMetric("template", $template);
    }

}
