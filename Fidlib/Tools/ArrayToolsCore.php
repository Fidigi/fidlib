<?php
namespace Fidlib\Tools;
/**
 * Classe utilitaire pour manipulation de tableau
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

class ArrayToolsCore
{
    
    /**
     * map un array 2d en array 3d
     * @param array $data (ex : Array([1]=>'toto',[2]=>'est',[3]=>'bete'))
     * @param array $size la profondeur estimee sinon elle sera calculee
     * @return array $array_map (ex : $array['toto']['est'][]='bete')
     */
    public static function loadMatrice($data, $size = 0) {
        $array_map = Array();
        if($size == 0){
            $size = count($data);
        }
        $size--;
        $array_map = self::newDimension($array_map,$data[$size],1);
        $size--;
        while($size >= 0){
            $array_map = self::newDimension($array_map,$data[$size]);
            $size--;
        }
        return $array_map;
    }
    
    /**
     * generation des dimensions pour loadMatrice
     */
    private static function newDimension($array, $data, $last = 0) {
        $array_map = Array();
        if($last == 0){
            $array_map[$data]=$array;
        }
        else{
            $array_map[]=$data;
        }
        return $array_map;
    }
}

