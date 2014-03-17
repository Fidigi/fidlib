<?php
/**
 * Classe AutoloadCore
 * @package Core
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
