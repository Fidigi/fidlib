<?php
/**
 * Classe AutoloadCore
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
class AutoloadCore extends FidBase {
	
	/**
	 * Construit l'autoload
	 * @param $other_path Array("name"=>"rep")
	 */
	public function __construct($other_path = array()) {
		$this->setOtherPath($other_path);
		spl_autoload_register(array($this, 'loader'));
	}
	
	private function loader($class) {
		//Other Path for autoload function
		if(count($this->getOtherPath()) > 0){
			foreach($this->getOtherPath() as $key => $value){
				if (preg_match('/^([a-zA-Z0-9]+)'.StringToolsCore::toUpperCamelCase($key).'$/', $class, $matches)) {
					$name = StringToolsCore::unCamelCase($matches[1]);
					include $_SERVER['DOCUMENT_ROOT'].'/'.$value[0]. '/' .$name. '.' .$key. '.php';
				}
			}
		}
	}
	
	
}
