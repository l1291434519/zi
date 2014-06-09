<?php

$m=memory_get_usage();

//$cs = require "config.php";
$cs = include 'config.php';

require 'z.php';
z::create('WebAppliction')->run($cs);

echo (memory_get_usage()-$m)/(1024*1024);

?>
