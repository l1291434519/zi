<?php

class abcController extends Controller{

	public function con(){
        $sql = "select title, create_time, id from news where recommend = 1 order by create_time desc limit 0,5";
        $model = new model;
        $news = $model->getData($sql);
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