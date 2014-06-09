<?php

//$m=memory_get_usage();

//$cs = require "config.php";
//$cs = include 'config.php';
$config=dirname(__FILE__).'/config/main.php';
require dirname(__FILE__).'/system/z.php';
z::create('WebAppliction')->run($config);


//echo (memory_get_usage()-$m)/(1024*1024);

?>
