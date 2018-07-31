<?php
namespace Fidlib\ObjectType;
/**
 * Class CollectionFidObjectManager
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

use ArrayObject;

class CollectionFidObjectManager extends ArrayObject
{
    
    /**
     * Renvoi le premier element de la collection
     * @return FidObject Renvoi null si rien n'est trouve
     */
    public function first() {
        return ($this->count()) ? reset($this) : null;
    }
    
    /**
     * Renvoi le dernier element de la collection
     * @return FidObject Renvoi null si rien n'est trouve
     */
    public function last() {
        return ($this->count()) ? end($this) : null;
    }
    
    /**
     * Renvoi les elements qui matches de la collection
     * @param array $array $key = property && $value = property value
     * @return FidObject Renvoi null si rien n'est trouve
     */
    public function find(array $array) {
        $filtre = count($array);
        $class = get_called_class();
        $collection = new $class();
        foreach($this as $object){
            $val = 0;
            foreach($array as $key => $value){
                if($object->$key == $value){
                    $val++;
                }
            }
            if($val == $filtre){
                $collection->append($object);
            }
        }
        return ($collection->count()) ? $collection : null;
    }
    
    /**
     * Renvoi une collection triee
     * @param string $filtre
     * @param ASC | DESC $way defaukt 'ASC'
     * @return FidObject Renvoi null si rien n'est trouve
     */
    public function order($filtre,$way = 'ASC') {
        $first = "";
        $class = get_called_class();
        $array=array();
        $collection = new $class();
        foreach($this as $object){
            if($first == ""){
                if($object->$filtre != '0' && intval($object->$filtre) == 0){
                    $first = "string";
                }
                else{
                    $first = "integer";
                }
            }
            $array[]=$object;
        }
        usort($array, self::comparer($filtre,$way,$first));
        foreach($array as $object){
            $collection->append($object);
        }
        return ($collection->count()) ? $collection : null;
    }
    
    private static function comparer($key,$way,$type='integer') {
        return function ($a, $b) use ($key,$way,$type) {
            if ($a->$key == $b->$key) {
                return 0;
            }
            if($way == 'DESC'){
                if($type == 'string'){
                    return strcmp($a->$key, $b->$key) ? -1 : +1;
                }
                elseif($type == 'integer'){
                    return ($a->$key > $b->$key) ? -1 : +1;
                }
            }
            else{
                if($type == 'string'){
                    return strcmp($a->$key, $b->$key) ? +1 : -1;
                }
                elseif($type == 'integer'){
                    return ($a->$key > $b->$key) ? +1 : -1;
                }
            }
        };
    }
    
    /**
     * Montre l'objet sous forme d'Array
     */
    public function openEachBox() {
        $objectCollection=Array();
        $i=0;
        if($this != null){
            foreach ($this as $object){
                $objectCollection[$i]=Array();
                foreach ($object as $attr => $value){
                    $objectCollection[$i][$attr] = $value;
                }
                $i++;
            }
        }
        return $objectCollection;
    }
}

