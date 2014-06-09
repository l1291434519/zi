<?php

class appliction{
    private static $_app;
    
    /**
	 * Creates an application of the specified class.
	 * @param string $class the application class name
	 * to the constructor of the application class.
	 * @return mixed the application instance
	 */
    public static function create($class){
		if(isset(self::$_app)){
			return self::$_app;
		}else{
			self::$_app = self::createAppliction($class);
			return self::$_app;
		}
    }
    public static function createAppliction($class){
        return new $class();
    }
    public static function app(){
        return self::$_app;
    }
    public static function autoload($class_name) {
        //require_once 'base/'.$class_name . '.php';
        //class directories
			$config= array();
            $directorys = array(
                'base/',
                'controller/',
            );
			$directorys = array_merge($directorys, $config);
            $SysPath = dirname(__FILE__).'/';
            //for each directory
            foreach($directorys as $directory)
            {
                //see if the file exsists
                if(file_exists($SysPath.$directory.$class_name . '.php'))
                {
                    //require_once($directory.$class_name . '.php');
					if(!class_exists($class_name))
						require($directory.$class_name . '.php');
                    //only require the class once, so quit after to save effort (if you got more, then name them something else 
                    return;
                }            
            }
    }
}


spl_autoload_register(array('appliction', 'autoload'));  

?>