<?php
/**
 * Classe PluginCore
 * @package Core
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