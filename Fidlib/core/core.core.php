<?php
/**
 * Classe Core
 * @package Core
 */
require("core/fidbase.core.php");
require("core/autoload.core.php");
require("core/plugin.core.php");
require("tools/string.tools.php");
require("tools/file.tools.php");
require("tools/array.tools.php");

class Core extends FidObject {

	/**-----  He Is Alive! -----**/
	public function __construct() {
		//recup matrice de conf d'architecture fichiers
		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/repconfig.matrice')){
			//autoload
			$this->loadConfigRep();
			new AutoloadCore($this->pathLoader());
			//chargement des plugins
			$this->pluginsConfig($this->pathConf());
		}
		else{
			//autoload
			new AutoloadCore();
		}
	}
	
	public function __call($name, $arguments = array()) {
		//repconfig
		if (preg_match('/^path([a-zA-Z0-9]+)/', $name, $matches)) {
			$refname = StringToolsCore::toLowerCamelCase($matches[1]);
			$result = $this->getRepconfig();
			if(count($result[$refname]) == 1){
				foreach($result[$refname] as $key => $value){
					if(is_int($key) &&  $key == 0){
						return $result[$refname][0];
					}
					else{
						return $result[$refname];
					}
				}
			}
			return $result[$refname];
		}
		//plugins
		elseif (preg_match('/^plugin([a-zA-Z0-9]+)/', $name, $matches)) {
			try {
				return $this->loadPlugin($matches, $arguments);
			} catch (Exception $e) {
				throw $e;
			}
		}
		else {
			return parent::__call($name, $arguments);
		}
	}
	
	private function loadConfigRep(){
		$file = new SplFileObject($_SERVER['DOCUMENT_ROOT'].'/repconfig.matrice');
		$file->setCsvControl(';','"');
		$this->setRepconfig(FileToolsCore::loadMatriceCsv($file));
	}
	
	//chargement des plugins
	private function pluginsConfig($folder){
		try {
			$file = new SplFileObject($_SERVER['DOCUMENT_ROOT'].'/'.$folder.'/pluginsconfig.conf');
			$file->setCsvControl(';','"');
			$this->setPluginsconfig(FileToolsCore::loadMatriceCsv($file));
		} catch (Exception $e) {
			echo "Fichier configuration de plugins manquant!";
		}
	}
	
	private function loadPlugin($matches, $arguments = array()){
		$attr = 'plugin'.$matches[1];
		if(isset($this->$attr)){
			return $this->$attr;
		}
		else{
			$refname = StringToolsCore::unCamelCase($matches[1]);
			$result = $this->getPluginsconfig();
			if($result[$refname][0] == "1"){
				include 'plugins/'.$refname.'/var/index.php';
				if(isset($this->$attr)){
					return $this->$attr;
				}
			}
			else{
				throw new Exception($refname." plugin forbidden");
			}
		}
	}
	
}