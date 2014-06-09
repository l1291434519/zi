<?php

$m=memory_get_usage();

//$cs = require "config.php";
$cs = include 'config.php';

require 'base.php';
appliction::create('WebAppliction')->run();

echo (memory_get_usage()-$m)/(1024*1024);

?>
