<?php

class UrlManger{
    public $c;
    public $a;

    function init(){
        
    }
    
    function router(){
        if(!empty($_GET['r'])){
            $u = explode('/', $_GET['r']);
            $this->c = $u[0];
            $this->a = $u[1];
        }else{
			if(isset($_GET['c']))
				$this->c = $_GET['c'];
			if(isset($_GET['a']))
				$this->a = $_GET['a'];
		}
    
    }
	public static function createUrl($route,$params=array(),$ampersand='&'){
		foreach($params as $i=>$param){
			$url.= $i.'='.$param.$ampersand;
		}
		$url = trim($url,$ampersand);
		return $route.'?'.$url;
	}

}



?>