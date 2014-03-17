<?php
/**
 * Classe PluginCore
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
class PluginCore extends FidBase {
	
	public static function dependencies(array $pluginList){
		$result = Core::getInstance()->getPluginsconfig();
		foreach($pluginList as $pluginName => $ver){
			if($result[$pluginName][0] == "0"){
				$result[$pluginName][0] = "1";
				Core::getInstance()->setPluginsconfig($result);
			}
			$method = 'plugin'.StringToolsCore::toUpperCamelCase($pluginName);
			$plugin = Core::getInstance()->$method();
			if(call_user_func(array( $plugin , 'PluginVersion')) != $ver){
				throw new Exception("probleme de version avec ".$pluginName);
			}
		}
	}
	
}