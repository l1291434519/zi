<?php

class defaultController extends Controller{

	function index(){
        //echo WebAppliction::$baseUrl;
        echo appliction::app()->baseUrl;
        var_dump(appliction::app()->config);
        $this->show('index');
    }

}
?>