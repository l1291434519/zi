<?php

class FHtml{
	private static $code="utf-8";
	
	function fen($tag="",$string=""){
		$string = explode($tag,$string);
		if (is_array($string)){
			$string = preg_replace('/<img[^>]*>/isU', '',$string['0']);
		}else {
			$string = preg_replace('/<img[^>]*>/isU', '',$string);
		}
		return $string;
	}
	function view($string=""){
		$string = preg_replace('/&lt;!--简介--&gt;/isU', '',$string);
		return $string;
	}
	function substr($string,$mun){
		$string = mb_substr(strip_tags($string),0,$mun,self::$code);
		return $string;
	}
	
	public static function truncate($string, $length, $etc = '...')
	{
		$result = '';
		$string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
		$strlen = strlen($string);
		for ($i = 0; (($i < $strlen) && ($length > 0)); $i++){
			if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')){
					if ($length < 1.0){
						break;
					}
					$result .= substr($string, $i, $number);
					$length -= 1.0;
					$i += $number - 1;
			}else{
				$result .= substr($string, $i, 1);
				$length -= 0.5;
			}
		}
		$result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
		if ($i < $strlen){
			$result .= $etc;
		}
		return $result;
	}
    
    public static function getpic($str_img){
    
        preg_match ("<img.*src=[\"](.*?)[\"].*?>",$str_img,$match);
        
        return $match[1];
  /*       
  echo "$match[1]";
  var_dump($str_img);
  die;
   */
  /*
        preg_match_all("/<img.*>/isU",$str_img,$ereg);//正则表达式把图片的整个都获取出来了 
        $img=$ereg[0][0];//图片 
        $p="#src=('\")(.*)('\")#isU";//正则表达式
        preg_match_all ($p, $img, $ereg); 
        
        var_dump($img1);die;
        $img_path =$img1[2][0];//获取第一张图片路径  
        return $img_path; 
        */
    }


      //根据经纬度计算距离
    public static function getdistance($lng1,$lat1,$lng2,$lat2)//根据经纬度计算距离
    {
        
        //将角度转为狐度 
        $radLat1=deg2rad($lat1);
        $radLat2=deg2rad($lat2);
        $radLng1=deg2rad($lng1);
        $radLng2=deg2rad($lng2);
        $a=$radLat1-$radLat2;//两纬度之差,纬度<90
        $b=$radLng1-$radLng2;//两经度之差纬度<180
        $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137;
        return $s;
    }
		
}

?>