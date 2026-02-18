<?php

// Model.php 
// Actualizado para compatibilidad con PHP 8.x - Windows 11
// @brief Clase Model optimizada para evitar errores fatales en consultas fallidas

class Model {

	public static function exists($modelname){
		$fullpath = self::getFullpath($modelname);
		$found = false;
		if(file_exists($fullpath)){
			$found = true;
		}
		return $found;
	}

	public static function getFullpath($modelname){
		return "core/app/model/".$modelname.".php";
	}

	public static function many($query, $aclass){
		$cnt = 0;
		$array = array();
		
		// Validamos que el query sea un objeto válido y no un booleano (false)
		if($query && !is_bool($query)){
			while($r = $query->fetch_array()){
				$array[$cnt] = new $aclass;
				$cnt2 = 1;
				foreach ($r as $key => $v) {
					if($cnt2 > 0 && $cnt2 % 2 == 0){ 
						$array[$cnt]->$key = $v;
					}
					$cnt2++;
				}
				$cnt++;
			}
		}
		return $array;
	}

	public static function one($query, $aclass){
		$cnt = 0;
		$found = null;
		$data = new $aclass;

		// Validamos que el query sea un objeto válido antes de procesar
		if($query && !is_bool($query)){
			while($r = $query->fetch_array()){
				$cnt = 1;
				foreach ($r as $key => $v) {
					if($cnt > 0 && $cnt % 2 == 0){ 
						$data->$key = $v;
					}
					$cnt++;
				}
				$found = $data;
				break;
			}
		}
		return $found;
	}
}

?>