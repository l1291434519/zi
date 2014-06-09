<?php

class appliction{
    public static $_app;
    
    /**
	 * Creates an application of the specified class.
	 * @param string $class the application class name
	 * to the constructor of the application class.
	 * @return mixed the application instance
	 */
    public static function create($class){
        self::$_app = self::createAppliction($class);
        return self::$_app;
    }
    public static function createAppliction($class){
        return new $class();
    }
    public static function app(){
        return self::$_app;
    }
    public static function autoload($class_name) {
            if (class_exists($class_name)) {
                return;
            }
            $directorys = array(
                'base/',
                'lib/',
            );
            $AppPath = dirname(__FILE__).'/';
            if (is_array(z::app()->config['import'])) {
                $directorys = array_merge($directorys,z::app()->config['import']);
            }
           
            //for each directory
            foreach($directorys as $directory)
            {
                //see if the file exsists
                if(file_exists($AppPath.$directory.$class_name . '.php'))
                {
                    require($AppPath.$directory.$class_name . '.php');
                    //only require the class once, so quit after to save effort (if you got more, then name them something else 
                    return;
                }

                if (isset(z::app()->appPath)) {
                    if (file_exists(z::app()->appPath.'/'.$directory.$class_name . '.php')) {
                        require(z::app()->appPath.'/'.$directory.$class_name . '.php');
                        //only require the class once, so quit after to save effort (if you got more, then name them something else 
                        return;
                    }

                }else{
                     throw new CException($class_name.' not have',500);
                }            
            }
    }
}


spl_autoload_register(array('appliction', 'autoload'));  

?>