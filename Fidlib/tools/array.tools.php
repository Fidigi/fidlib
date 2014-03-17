<?php
/**
 * Classe utilitaire pour manipulation de tableau
 * @package Tools
 */
class ArrayToolsCore {

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