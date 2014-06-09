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
        }
    
    }

}



?>