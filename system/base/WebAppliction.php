<?php

class WebAppliction{
    
    public $baseUrl = 'iiiiiii';
    public $components;
    public $user;
    public $modules;
    public $config;
    public $appPath;
    
    public function getUser(){
        return self::$user;
    }
    public function setUser($userClass){
        if(!isset(self::$components['user']))
            self::$user = new $userClass;
        else
            self::$user = new $userClass;
    }

    public function getComponent(){
        return self::$modules;
    }
    public  function setComponent($class){
        if(!isset(self::$components[$class]))
            self::$components[$class] = new $class;
    }
    
	public function run($config){
        $this->appPath = dirname(dirname($config));
        $cs = include $config;
        $this->config = $cs;


		$c='';
		$a='';
/*
        if($_POST)
            throw new CException('111',500);
    */    
        /**
         *urlmanger
         */

        $urlmanger = new UrlManger();
        $urlmanger->router();
        //var_dump($u);
		if(!empty($urlmanger->c)){
			$con = $urlmanger->c.'Controller';
			$c = new $con;
		}else{
            if(isset($this->config['defaultController']))
			 $c = new defaultController;
		}
		/**
         *  controller initialization
         */
        if (!empty($c)) {
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
}

?>