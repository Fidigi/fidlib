<?php
namespace Fidlib\ObjectType;
/**
 * Class FidObject 
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

class FidObject extends FidBase
{
    
    /**-----  Instanciation -----**/
    public function __construct() {
        
    }
    
    public static function getInstance() {
        static $instance = array();
        $class = get_called_class();
        
        if (isset($instance[$class]) === false){
            $instance[$class] = new $class();
        }
        
        return $instance[$class];
    }
    
    /**-----  Clonage -----**/
    
    public function __clone() {
        
    }
    
    /**----- View -----**/
    
    /**
     * Montre l'objet sous forme d'Array
     */
    public function openBox($filtre = array()){
        $object=Array();
        $masque = Array();
        if(count($filtre) > 0){
            foreach($filtre as $mask){
                if(is_array($mask)){
                    $masque[$mask[1]]=$mask[0];
                }
            }
        }
        foreach ($this as $attr => $value){
            if(count($filtre) == 0){
                $object[$attr] = $value;
            }
            elseif(in_array(StringToolsCore::unCamelCase($attr), $masque)){
                foreach($masque as $key => $val){
                    if(StringToolsCore::unCamelCase($attr) == $val){
                        $object[$key] = $value;
                    }
                }
            }
            elseif(in_array(StringToolsCore::unCamelCase($attr), $filtre)){
                $object[StringToolsCore::unCamelCase($attr)] = $value;
            }
        }
        return $object;
    }
}

