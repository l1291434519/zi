<?php


class gou{
	private static $_models=array();
	
	public static function model($className=__CLASS__)
	{
	echo $className;
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

class cc extends gou{
	public $aa;
	public static function model($className=__CLASS__){
		
		return parent::model($className);
	}
	public static function hh(){
		echo 'hu haha';
	}


}
class bb extends gou{

	public static function hh(){
		echo 'wwwwwwwwwwwwwwww';
	}
}
$c = new gou;

$c->aa=11;
echo $c->aa;


echo cc::model()->aa='22'.'<br>';
//cc::model()->hh();
function ee(){

echo cc::model()->aa;
}
ee();

//echo '--'.bb::model()->aa.'---';
//bb::model()->hh();
$dayBegin = strtotime(date('Y-d-m'));
echo $dayBegin;

echo strtotime(date('Y-m-d 00:00:00')).'<br>';
//echo date('Y-m-d H:I:s',strtotime('today'));
echo date('Y-m-d H:I:s',strtotime(date('Y-m-d 00:00:00'))).'<br>';

	