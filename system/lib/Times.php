<?php

class Times{

	private static $code="utf-8";

	public static function oneDayTime($d)
	{
		//$starttime=
		//$endtime=date("Y-m-d 14:00:00",time());
		date_default_timezone_set('PRC');
		return date("Y-m-d H:i:s",strtotime($d." day")); 
	/*	
		echo '<br>';
		echo strtotime($starttime);
		echo '<br>';
		echo $starttime;
		echo '<br>';
		echo $endtime;
		echo '<br>';
		echo strtotime($endtime);
	*/
	}


}

ob_start();


echo Times::oneDayTime('-1');



$data = ob_get_clean();

file_put_contents('aaa.text',$data);


		ob_clean(); 
		ob_end_clean();
echo 'kkkk';

?>