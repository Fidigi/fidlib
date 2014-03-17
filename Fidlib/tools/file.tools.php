<?php
/**
 * Classe utilitaire pour manipulation de fichier
 * @package Tools
 */
class FileToolsCore {

	 /** 
	 * Cree un tableau 3D d'une matrice 2D
	 * @param SplFileObject $files
	 * @return bool
	 */
	public static function loadMatriceCsv($file) {
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