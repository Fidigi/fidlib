<?php
namespace Fidlib\Tools;
/**
 * Classe utilitaire pour manipulation de fichier
 * @package Tools
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

use SplFileObject;

class FileToolsCore
{
    
    /**
     * Cree un tableau 3D d'une matrice 2D
     * @param SplFileObject $files
     * @return bool
     */
    public static function loadMatriceCsv(SplFileObject $file) {
        $matrice = array();
        while (!$file->eof()) {
            $data = $file->fgetcsv();
            if($data[0][0] != '#')
                $matrice = array_merge_recursive($matrice,ArrayToolsCore::loadMatrice($data));
        }
        return $matrice;
    }
    
    /**
     * Cree un tableau d�arborescence d'un dossier
     * @param string $Directory
     * @return array
     */
    public static function ScanDirectory($Directory){
        $MyDirectory = opendir($Directory) or die('Erreur');
        $array_return = Array();
        while($Entry = @readdir($MyDirectory)) {
            if(is_dir($Directory.'/'.$Entry)&& $Entry != '.' && $Entry != '..') {
                $array_return[$Entry] = Self::ScanDirectory($Directory.'/'.$Entry);
            }
            elseif($Entry != '.' && $Entry != '..') {
                $array_return[]=$Entry;
            }
        }
        closedir($MyDirectory);
        return $array_return;
    }
}

