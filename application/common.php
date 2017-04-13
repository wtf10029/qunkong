<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


/**
 * 获得用户IP地址
 */
function getIp(){
    global $ip; 

    if (getenv("HTTP_CLIENT_IP")) 
    $ip = getenv("HTTP_CLIENT_IP"); 
    else if(getenv("HTTP_X_FORWARDED_FOR")) 
    $ip = getenv("HTTP_X_FORWARDED_FOR"); 
    else if(getenv("REMOTE_ADDR")) 
    $ip = getenv("REMOTE_ADDR"); 
    else 
    $ip = "Unknow"; 

    return $ip; 
}

/**
 * 自定义的删除函数,可以删除文件和递归删除文件夹
 */
function delCatalogue($path){
    if(is_dir($path)){
        $file_list= scandir($path);
        foreach ($file_list as $file){
            if( $file!='.' && $file!='..'){
            delCatalogue($path.'/'.$file);
            }
        }
    @rmdir($path);
    } else {
        @unlink($path);
    }
}


/**
 * 递归计算目录大小
 */
function directory_size($directory) {  
      $directorySize=0;  
      if ($dh = @opendir($directory)) {  
         while (($filename = readdir ($dh))) {  
           if ($filename != "." && $filename != "..") {  
             if (is_file($directory."/".$filename)){  
                $directorySize += filesize($directory."/".$filename);  
             }     
             if (is_dir($directory."/".$filename)){  
                $directorySize += directory_size($directory."/".$filename);  
             }  
           }  
        }  
      }  
      @closedir($dh);  
      return $directorySize;  
}  

/**
 * 将文件大小转换成相应的单位
 */
function getsize($size)
{
    if ($size >= pow(1024,3)) {
        //值 >= 1024M 就是GB
        $res = round($size/pow(1024,3),2);
        $ext = 'GB';
    }elseif($size >= pow(1024,2)){
        //值 >= 1024KB 就是MB
        $res = round($size/pow(1024,2),2);
        $ext = 'MB';
    }elseif($size >= pow(1024,1)){
        //值 >= 1024b 就是KB
        $res = round($size/pow(1024,1),2);
        $ext = 'KB';
    }else{
        //值< 1024 就是字节单位
        $res = $size;
        $ext = 'Byte';
    }

    return $res.$ext;
}