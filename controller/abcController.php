<?php

class abcController extends Controller{

	public function con(){
        $sql = "select * from discuz_common_admincp_cmenu";
        //$model = new model;
        //$news = $model->getData($sql);
		$news = Mysql::model();
		$news->connect();
		$news->fetch_array($sql);
		var_dump($news);
		echo UrlManger::createUrl('aa.php',array('a'=>'c','c'=>1121));
               // var_dump($news);
               echo __FILE__;
               echo '<br>';
               echo __FUNCTION__;
               echo '<br>';
               echo __CLASS__;
               echo '<br>';
               echo __METHOD__;
               echo '<br>';
               echo __LINE__;
               
		//echo 'gouzao';
	}
	function too(){
		$this->show('index','aaaaaaaa');
		echo '<br>---------------------------';
	}
	function ser(){
		echo '<br>cheng gong la';
	}
    

}

?>