<?php
/**
 * Classe utilitaire pour manipulation de string
 * @package Tools
 */
class StringToolsCore {
	
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
