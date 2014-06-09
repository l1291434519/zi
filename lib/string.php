<?php

$str1 = "artetetwabc";
$str2 = "abcdeffsdfwefefewwe";

//$num = count_chars($str1);
//$num = str_word_count($str1);

/*
$num = strlen($str2);

var_dump($num);

for($i=0;$i<$num;$i++){
	$s1 = substr($str1,$i,1);
	$s2 = substr($str2,$i,1);
	if($s1 != $s2){
		echo $s1;
	}

}



echo strcspn($str1,$str2);
*/

str($str1,$str2);



function str($str1,$str2){
	$num1 = strlen($str1);
	$num2 = strlen($str2);
	
	if($num1>$num2)
		$num = $num1;
	else
		$num = $num2;

	//var_dump($num);

	for($i=0;$i<$num;$i++){
		$s1 = substr($str1,$i,1);
		$s2 = substr($str2,$i,1);
		if($s1 != $s2){
			echo $s1;
		}

	}

}




?>