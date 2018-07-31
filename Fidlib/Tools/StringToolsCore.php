<?php
namespace Fidlib\Tools;
/**
 * Classe utilitaire pour manipulation de string
 * @package Tools
 *
 * 	LICENCE :
 Copyright 2011, 2013, 2014, 2018 Arnaud LAURENT
 This file is part of FidLib.
 
 FidLib is free software: you can redistribute it and/or modify
 it under the terms of the GNU Lesser General Public License as published by
 the Free Software Foundation, either version 2.1 of the License, or
 (at your option) any later version.
 
 FidLib is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Lesser General Public License for more details.
 
 You should have received a copy of the GNU Lesser General Public License
 along with FidLib.  If not, see <http://www.gnu.org/licenses/>.
 */

class StringToolsCore
{
    /**
     * Convertit un nom en notation CamelCase
     * @param string $name
     * @return string
     * @example toUpperCamelCase("root_front") == "RootFront"
     */
    public static function toUpperCamelCase($name) {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
    }
    
    /**
     * Convertit un nom en notation camelCase
     * @param string $name
     * @return string
     * @example toLowerCamelCase("root_front") == "rootFront"
     */
    public static function toLowerCamelCase($name) {
        $name = self::toUpperCamelCase($name);
        $name[0] = strtolower($name[0]);
        return $name;
    }
    
    /**
     * Convertit un nom camelCase en notation unCamelCase
     * @param string $name
     * @return string
     * @example unCamelCase("rootFront") == "root_front"
     * @example unCamelCase("RootFront") == "root_front"
     */
    public static function unCamelCase($content) {
        $callback = function( $matches ) {
            return '_'.strtolower($matches[0]);
        };
        $content = preg_replace_callback('#(?<=[a-zA-Z])([A-Z])(?=[a-zA-Z])#',$callback,$content);
        $content{0} = strtolower($content{0});
        return $content;
    }
}

