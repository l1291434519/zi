<?php 

class Model{

    function getData($sql=''){
        if(!empty($sql)){
            $DB=new mysql_class;
            $DB->connect(); // 连接数据库\
            //$sql = "select title, create_time, id from news where recommend = 1 order by create_time desc limit 0,5";
            $data = $DB->fetch_array($sql);
            $DB->close();
        }
        return $data;
    }

}


?>