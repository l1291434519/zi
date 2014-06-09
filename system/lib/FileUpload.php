<?php
class FileUpload{
        protected $path;                                                   //文件保存路径
        protected $size = 2000000;                                         //上传文件限制大小，默认为2M
        protected $allowtype = array('jpg','gif','png','bmp','tif');       //可上传文件格式
        protected $israndname = true;                                      //是否修改文件名
        protected $tempName;                                               //临时文件名
 
        protected $originName;                                             //源文件名
        protected $fileType;                                               //上传文件类型
        protected $filesize;                                               //上传文件大小
        protected $newName;                                                //新文件名
        protected $errorNum = 0;                                           //错误代码
        protected $errorMass;                                              //错误信息
        //初始化各项设置
        function __construct($option = array()){
            foreach($option as $key=>$value){
                $key = strtolower($key);
                //检测各个配置项是否存在
                if(!in_array($key,get_class_vars(get_class($this)))){
                    continue;
                }
                $this->setOption($key,$value);
            }
        }
        //上传函数
        public function uploadFile($fileField){
            $return = true;
            if(!$this->checkPath()){
                $this->errorMess = $this->getError();
                return false;  
            }
            
            $name = $_FILES[$fileField]['name'];
            $error = $_FILES[$fileField]['error'];
            $size = $_FILES[$fileField]['size'];
            $tmp_name = $_FILES[$fileField]['tmp_name'];
            
            if(is_array($name)){
                $errors = array();
                for($i=0;$i<count($name);$i++){
                    if(empty($name[$i])){
                        continue;
                    }
                    if($this->setFiles($name[$i],$error[$i],$size[$i],$tmp_name[$i])){
                        if(!$this->checkSize() || !$this->checkType()){
                            $errors[] = $this->getError();
                            $return = false;
                        }
                    }else{
                        $errors[] = $this->getError();
                        $return = false;
                    }
                }
                
                if($return){
                    $fileName = array();
                    for($i=0;$i<count($name);$i++){
                        if($this->setFiles($name[$i],$error[$i],$size[$i],$tmp_name[$i])){
                            $this->setNewFileName();
                            if(!$this->copyFile()){
                                $errors[] = $this->getError();
                                $return = false;
                            }else{
                                $fileName[] = $this->newName;
                            }
                        }
                    }
                    $this->newName = $fileName;
                }
                $this->errorMess = $errors;
                return $return;
            }else{
                if($this->setFiles($name,$error,$size,$tmp_name)){
                    if($this->checkSize() && $this->checkType()){
                        $this->setNewFileName();
                        if($this->copyFile()){
                            return true;
                        }else{
                            $return = false;
                        }
                    }else{
                        $return = false;
                    }
                }else{
                    $return = false;
                }
                
                if(!$return){
                    $this->errorMess = $this->getError();
                }
                return $return;
            }
        }
        //获取错误信息
        public function getErrorMesg(){
            return $this->errorMess;
        }
        //获取上传后文件名
        public function getNewName(){
            return $this->newName;
        }
        //复制保存文件
        protected function copyFile(){
            if(!$this->errorNum){
                $filepath = rtrim($this->path,'/').'/';
                $filepath .= $this->newName;
                if(@move_uploaded_file($this->tempName,$filepath)){
                    return true;
                }else{
                    $this->setOption('errorNum',-3);
                    return false;
                }
            }
        }
        //设置新文件名
        protected function setNewFileName(){
            if($this->israndname){
                $this->setOption('newName',$this->randName());
            }
            else{
                $this->setOption('newName',$this->originName);
            }
        }
        //产生随机文件名
        private function randName(){
            $str = date('YmdHis').rand(1000,9999);
            return $str.'.'.$this->fileType;
        }
        //检查文件大小是否超出允许上传文件大小
        protected function checkSize(){
            if($this->size<$this->filesize){
                $this->setOption('errorNum',-1);
                return false;
            }else{
                return true;
            }
        }
        //检查文件类型
        protected function checkType(){
            if(!in_array($this->fileType,$this->allowtype)){
                $this->setOption('errorNum',-2);
                return false;
            }else{
                return true;
            }
        }
        //设置_FILES相关信息
        protected function setFiles($name='',$error=0,$size=0,$tmp_name=''){
            $this->setOption('errorNum',$error);
            if($error){
                return false;
            }
            $this->setOption('originName',$name);
            $this->setOption('tempName',$tmp_name);
            $type = explode('.',$name);
            $this->setOption('fileType',strtolower($type[count($type)-1]));
            $this->setOption('filesize',$size);
            return true;
        }
        //获取错误信息
        protected function getError(){
            $str = '文件上<span style="color:red;">'.$this->originName.'</span>传出错：';
            switch($this->errorNum){
                case 1:
                    $str .= '上传文件超过了php.ini中upload_max_filesize选项的值';
                    break;
                case 2:
                    $str .= '上传文件超过了HTML表单中MAX_FILE_SIZE选项指定的值';
                    break;
                case 3:
                    $str .= '文件只被部分上传';
                    break;
                case 4:
                    $str .= '没有文件被上传';
                    break;
                case -1:
                    $str .= '文件大小超出允许上传文件大小';
                    break;
                case -2:
                    $str .= '上传的文件类型不是被允许的类型';
                    break;
                case -3:
                    $str .= '上传失败';
                    break;
                case -4:
                    $str .= '目录创建失败，请重新设置上传目录';
                    break;
                case -5:
                    $str .= '上传目标地址不能为空';
                    break;
                default:
                    $str .= '上传失败，发现未知错误';
            }
            return $str.'<br>';
        }
        //上传路径检测
        protected function checkPath(){
            //路径为空
            if(empty($this->path)){
                $this->setOption('errorNum',-5);
                return false;
            }
            //路径不存在或不可写
            if(!file_exists($this->path) || !is_writable($this->path)){
                if(!@mkdir($this->path,0755)){
                    $this->setOption('errorNum',-4);
                    return false;
                }
            }
            return true;
        }
        //初始化数据，赋值函数
        private function setOption($key,$value){
            $this->$key = $value;
        }
    }
?>