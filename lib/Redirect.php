<?php
class Redirect{

	public static function showmessage($message='成功', $status='success',$time=3, $url=false )
    {
      
        $back_color ='#ff0000';
          
        if($status =='success')
        {
            $back_color= 'blue';
        }

        if ($url)
        {
            $url = "window.location.href='{$url}'"; 
        }
        else
        {
            $url = "history.back();"; 
        }
		
		//$url = "history.back();";
        echo <<<HTML
        <div>
        <div style="background:#C9F1FF; margin:0 auto; height:100px; width:600px; text-align:center;">
                    <div style="margin-top:50px;">
                    <h5 style="color:{$back_color};font-size:14px; padding-top:20px;" >{$message}</h5>
                    页面正在跳转请等待<span id="sec" style="color:blue;">{$time}</span>秒
                    </div>
        </div>
        </div>
                    <script type="text/javascript">
                    function run(){
                        var s = document.getElementById("sec");
                        if(s.innerHTML == 0){
                        {$url}
                            return false;
                        }
                        s.innerHTML = s.innerHTML * 1 - 1;
                    }
                    window.setInterval("run();", 1000);
                    </script>
HTML;
		die;
    }
	
	
	public static function alert($msg){
		echo '<script>alert("'.$msg.'");</script>';
		
	}


}


?>