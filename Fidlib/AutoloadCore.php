<?php
namespace Fidlib;
/**
 * Classe AutoloadCore
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

use Fidlib\ObjectType\FidBase;
use Fidlib\Tools\StringToolsCore;

class AutoloadCore extends FidBase
{
    
    protected static $paths = null;
    
    /**
     * Construit l'autoload
     * @param $other_path Array("name"=>"rep")
     */
    public function __construct($other_path = array()) {
        if($other_path != null){
            $this->setOtherPath($other_path);
        }
        spl_autoload_register(array($this, 'loader'));
    }
    
    public static function registerPsr4($path,$namespace) {
        static::$paths[$namespace.'\\'] = trim($path,DIRECTORY_SEPARATOR);
    }
    
    private function loader($class) {
        $matches = null;
        //PSR4
        if(! $this->loaderPsr4($class)){
            $splitClassName = explode('\\', $class);
            $className = $splitClassName[count($splitClassName) - 1];
            //Other Path for autoload function
            if($this->getOtherPath()){
                foreach($this->getOtherPath() as $key => $value){
                    if (preg_match('/^([a-zA-Z0-9]+)'.StringToolsCore::toUpperCamelCase($key).'$/', $className, $matches)) {
                        $name = StringToolsCore::unCamelCase($matches[1]);
                        include $_SERVER['DOCUMENT_ROOT'].'/'.$value[0]. '/' .$name. '.' .$key. '.php';
                    }
                }
            }
        }
    }
    
    private function loaderPsr4($class) {
        // the current namespace prefix
        $prefix = $class;
        
        // work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name
        while (false !== $pos = strrpos($prefix, '\\')) {
            
            // retain the trailing namespace separator in the prefix
            $prefix = substr($class, 0, $pos + 1);
            
            // the rest is the relative class name
            $relative_class = substr($class, $pos + 1);
            
            // try to load a mapped file for the prefix and relative class
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }
            
            // remove the trailing namespace separator for the next iteration
            // of strrpos()
            $prefix = rtrim($prefix, '\\');
        }
        
        // never found a mapped file
        return false;
    }
    
    /**
     * Load the mapped file for a namespace prefix and relative class.
     *
     * @param string $prefix The namespace prefix.
     * @param string $relative_class The relative class name.
     * @return mixed Boolean false if no mapped file can be loaded, or the
     * name of the mapped file that was loaded.
     */
    protected function loadMappedFile($prefix, $relative_class)	{
        // are there any base directories for this namespace prefix?
        if (isset(static::$paths[$prefix]) === false) {
            return false;
        } else {
            // replace the namespace prefix with the base directory,
            // replace namespace separators with directory separators
            // in the relative class name, append with .php
            $file = '/'.static::$paths[$prefix].'/'
                . str_replace('\\', '/', $relative_class)
                . '.php';
                
                // if the mapped file exists, require it
                if ($this->requireFile($file)) {
                    // yes, we're done
                    return $file;
                }
        }
        
        // never found it
        return false;
    }
    
    /**
     * If a file exists, require it from the file system.
     *
     * @param string $file The file to require.
     * @return bool True if the file exists, false if not.
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}

