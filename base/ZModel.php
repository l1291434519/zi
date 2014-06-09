<?php 

class ZModel{
	private static $_models=array();
	public $errors=array();
	
	public static function model($className=__CLASS__)
	{
		if(isset(self::$_models[$className])){
			return self::$_models[$className];
		}else{
			$model=self::$_models[$className]=new $className(null);
			return $model;
		}
	}

	public function __get($property_name)
	{
		if(isset($this->$property_name)){
			return($this->$property_name);
		}else{
			return(NULL);
		}
	}

	public function __set($property_name, $value)
	{
		$this->$property_name = $value;
	}
	

}


?>