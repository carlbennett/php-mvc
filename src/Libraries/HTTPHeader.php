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

use \CarlBennett\MVC\Libraries\Pair;

class HTTPHeader extends Pair
{
    public function getName() : string
    {
        return $this->getKey();
    }

    public function __toString() : string
    {
        return $this->key . ': ' . $this->value . \PHP_EOL;
    }
}
