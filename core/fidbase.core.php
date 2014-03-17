<?php
/**
 * Class FidBase (magic method)
 * Allow setter and getter easily
 * ->setNameCamelCase($value) return object
 * ->getNameCamelCase() return $this->name_camel_case
 * @package Core
 * 
 * 	LICENCE :
  	Copyright 2011, 2013, 2014 Arnaud LAURENT 
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
abstract class FidBase {

	/**-----  Box -----**/
	
	public function __call($name, $arguments = array()) {
		//setter
		if (preg_match('/^set([a-zA-Z0-9]+)/', $name, $matches)) {
			$name = StringToolsCore::toLowerCamelCase($matches[1]);
			$this->$name = $arguments[0];
			return $this;
		}
		//getter
		elseif (preg_match('/^get([a-zA-Z0-9]+)/', $name, $matches)) {
			$name = StringToolsCore::toLowerCamelCase($matches[1]);
			return $this->$name;
		}
	}

	/**-----  Factories -----**/

	/**
	 * Renvoi un objet rempli avec les donn�es fournies
	 * @param object $object
	 * @param mixed $data Optionel
	 * @return $object
	 */
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

class FidObject extends FidBase {

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

/**
 * Gestion collection d'objets
 **/
class CollectionFidObjectManager extends ArrayObject {
	
	/**
	 * Renvoi le premier element de la collection
	 * @return Collection Renvoi null si rien n'est trouve
	 */
	public function first() {
		return ($this->count()) ? reset($this) : null;
	}
	
	/**
	 * Renvoi le dernier element de la collection
	 * @return Collection Renvoi null si rien n'est trouve
	 */
	public function last() {
		$index = $this->count() - 1;
		return ($this->count()) ? $this[$index] : null;
	}
	
	/**
	 * Renvoi les elements qui matches de la collection
	 * @param array $array $key = property && $value = property value
	 * @return Collection Renvoi null si rien n'est trouve
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
	 * Renvoi une collection tri�e
	 * @param string $filtre
	 * @param ASC | DESC $way defaukt 'ASC'
	 * @return Collection Renvoi null si rien n'est trouv�
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

	/**----- Polymorphe -----**/

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