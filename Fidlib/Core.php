<?php
namespace Fidlib;
/**
 * Classe Core
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

use Fidlib\ObjectType\FidObject;
use Fidlib\Tools\FileToolsCore;
use Fidlib\Tools\StringToolsCore;
use Exception;
use SplFileObject;

class Core extends FidObject
{
    
    /**-----  He Is Alive! -----**/
    public function __construct() {
        //recup matrice de conf d'architecture fichiers
        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/repconfig.matrice')){
            $this->loadConfigRep();
            //chargement des plugins
            $this->plgConfig($this->pathConf());
        }
        //autoload
        if($this->pathLoader() != null) {
            new AutoloadCore($this->pathLoader());
        }
        else{
            //autoload
            new AutoloadCore();
        }
    }
    
    public function __call($name, $arguments = array()) {
        $matches = null;
        //repconfig
        if (preg_match('/^path([a-zA-Z0-9]+)/', $name, $matches)) {
            $refname = StringToolsCore::toLowerCamelCase($matches[1]);
            $result = $this->getRepconfig();
            if(isset($result[$refname])){
                foreach(array_keys($result[$refname]) as $key){
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
        try {
            $file = new SplFileObject($_SERVER['DOCUMENT_ROOT'].'/repconfig.matrice');
            $file->setCsvControl(';','"');
            $this->setRepconfig(FileToolsCore::loadMatriceCsv($file));
        } catch (Exception $e) {
            echo "FATAL ERROR : Fichier '".$_SERVER['DOCUMENT_ROOT']."/repconfig.matrice' manquant";
        }
    }
    
    //chargement des plugins
    private function plgConfig($folder){
        try {
            $file = new SplFileObject($_SERVER['DOCUMENT_ROOT'].'/'.$folder.'/pluginsconfig.conf');
            $file->setCsvControl(';','"');
            $this->setPluginsconfig(FileToolsCore::loadMatriceCsv($file));
        } catch (Exception $e) {
            echo "FATAL ERROR : Fichier '".$_SERVER['DOCUMENT_ROOT'].'/'.$folder."/pluginsconfig.conf' manquant";
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

