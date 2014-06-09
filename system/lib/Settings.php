<?php   
class Settings {   
    var $_settings = array ();   
       
    function get($var) {   
        $var = explode ( '.', $var );      
        $result = $this->_settings;   
        foreach ( $var as $key ) {   
            if (! isset ( $result [$key] )) {   
                return false;   
            }          
            $result = $result [$key];   
        }          
        return $result;   
    }   
       
    function load() {   
        trigger_error ( 'Not yet implemented', E_USER_ERROR );   
    }   
}   
  
class Settings_PHP extends Settings {   
    function load($file) {   
        if (file_exists ( $file ) == false) {   
            return false;   
        }   
           
        // Include file   
        include ($file);   
        unset ( $file );   
           
        // Get declared variables   
        $vars = get_defined_vars ();   
           
        // Add to settings array   
        foreach ( $vars as $key => $val ) {   
            if ($key == 'this')   
                continue;              
            $this->_settings [$key] = $val;   
        }   
       
    }   
}   
  
class Settings_INI extends Settings {   
    function load($file) {   
        if (file_exists ( $file ) == false) {   
            return false;   
        }   
        $this->_settings = parse_ini_file ( $file, true );   
    }   
}   
  
class Settings_YAML extends Settings {   
    function load($file) {   
        if (file_exists ( $file ) == false) {   
            return false;   
        }   
           
        include ('spyc.php');   
        $this->_settings = Spyc::YAMLLoad ( $file );   
    }   
}   
  
class Settings_XML extends Settings {   
    function load($file) {   
        if (file_exists ( $file ) == false) {   
            return false;   
        }   
           
        include ('xmllib.php');   
        $xml = file_get_contents ( $file );   
        $data = XML_unserialize ( $xml );   
           
        $this->_settings = $data ['settings'];   
    }   
}   

?>