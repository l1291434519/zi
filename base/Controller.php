<?php 

class Controller implements inController{
	public $user;
	//public $controller;
	public $model;
	public $view;
    public $theme;
	
	public function app(){
		require "user.php";
		$this->user = new user();
	}
    public function init(){
    
    }
	public function createUrl($url,$data=array()){
		if(!empty($data)){
            $url = $url.'?';
            foreach($data as $k=>$v){
                $url.=$k.'='.$v;
            }
        }
		return $url;
	}
	
	public function show($tmp="",$data=""){
		$data;
        ob_start();

        if(!empty($this->theme))
            require 'themes/'.$this->theme.'/view/'.$tmp.'.php';
        else
            require 'view/'.$tmp.'.php';
            
        ob_end_flush();
	}
	function run(){
	
		echo $this->out()->info;
		echo "<br>";
		$this->user->cc();
	
	}
    function index(){
        //echo WebAppliction::$baseUrl;
        echo appliction::app()->baseUrl;
        echo "you ar success and this is index page";
        $this->show('index');
    }

}

?>