<?php
namespace Fidlib\ObjectType;
/**
 * Class FidBase (magic method)
 * Allow setter and getter easily
 * ->setNameCamelCase($value) return object
 * ->getNameCamelCase() return $this->name_camel_case
 * @package Core
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

use Fidlib\Tools\StringToolsCore;

abstract class FidBase
{
    public function __call($name, $arguments = array()) {
        $matches = null;
        //setter
        if (preg_match('/^set([a-zA-Z0-9]+)/', $name, $matches)) {
            $name = StringToolsCore::toLowerCamelCase($matches[1]);
            $this->$name = $arguments[0];
            return $this;
        }
        //getter
        elseif (preg_match('/^get([a-zA-Z0-9]+)/', $name, $matches)) {
            $name = StringToolsCore::toLowerCamelCase($matches[1]);
            if(isset($this->$name)){
                return $this->$name;
            } else {
                return null;
            }
            
        }
    }
    
    public static function factory($object, $data = null) {
        if (is_array($data) || is_object($data)) {
            foreach ($data as $property => $value){
                $setProperty = "set".StringToolsCore::toUpperCamelCase($property);
                call_user_func(array( $object , $setProperty), $value);
            }
        }
        return $object;
    }
}

