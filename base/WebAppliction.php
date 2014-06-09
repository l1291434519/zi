<?php

class WebAppliction{
    
    public $baseUrl = 'iiiiiii';
    public $components = array();
    public $user;
    public $modules;
    
    public function getUser(){
        return self::$user;
    }
    public function setUser($userClass){
        if(!isset(self::$components['user']))
            self::$user = new $userClass;
        else
            self::$user = new $userClass;
    }
	
    public function getComponents($ComponentName){
		if(isset(self::$components[$ComponentName]))
			return self::$modules[$ComponentName];
		else
			return false;
    }
    public  function setComponents($ComponentClass){
            self::$components[$ComponentClass] = new $ComponentClass;
    }
	
    public function getModules($ModuleName){
		if(isset(self::$modules[$ModuleName]))
			return self::$modules[$ModuleName];
		else
			return false;
    }
    public  function setModules($ModuleClass){
            self::$modules[$ModuleClass] = new $ModuleClass;
    }
    
    
	public function run(){
		$c='';
		$a='';
        if($_POST)
            throw new CException('111',500);
        
        /**
         *urlmanger
         */
        $urlmanger = new UrlManger();
        $urlmanger->router();
		if(!empty($urlmanger->c)){
			$con = $urlmanger->c.'Controller';
			$c = new $con;
		}else{
			$c = new defaultController;
		}
		/**
         *  controller initialization
         */
        $c->init();
        //action
		if(!empty($urlmanger->a)){
			$a = $urlmanger->a;
			$c->$a();
		}else{
			$c->index();
		}

	}
}

?>