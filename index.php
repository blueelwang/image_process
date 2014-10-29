<?php
define('GETPIC',true);
require './config.php';
require './thumb.php';
require 'log.php';
$cache_expire = 60*60*24*30;
header("Pragma: public");
header("Cache-Control: max-age=" . $cache_expire);
header('Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $cache_expire ) . ' GMT');
header("Content-Type:image/jpeg");

$conn = mysql_connect(HOST,USER,PASSWD) or die('Database connect fail');
$db_se = mysql_select_db(DATABASE);
mysql_query("set names 'utf8'");

if ($_GET && !empty($_GET['key'])){
    	
	//缩略图白名单
	$size_arr = array(200,230,280,305,480);
	
	$path = TMP_PATH.'imgCache/';
	
	$url = strtolower(trim($_GET['key']));
	if (strlen($url)==32){
		$p1 = $url[31];
		$p2 = $url[30];
    }else{
        dolog('Illegal url:#1111');
		//key非法，返回系统默认图
		//$picdata = file_get_contents('./v9.jpg');
		echo 'Illegal';
		exit;
	}
    
    if (empty($_GET['w'])){
        $width = 230;
        $tmb = true;
        $path .= $width.'/'.$p1.'/'.$p2.'/';
    }else{
        $width = trim($_GET['w']);
        if (is_numeric($width) && in_array($width, $size_arr)){
            $tmb = true;
            $path .= $width.'/'.$p1.'/'.$p2.'/';
        }elseif($width == 'default'){
            $path .= 'default/'.$p1.'/'.$p2.'/';
        }else{
            $path .= '230/'.$p1.'/'.$p2.'/';
        }
    } 

	//默认原图存放路径
	$opath = TMP_PATH.'imgCache/default/'.$p1.'/'.$p2.'/';
	//判断目录是否存在，如不存在则创建
	mkFolder($path);
	$filename = $path.$url.'.jpg';
	$ofilename = $opath.$url.'.jpg';
    //echo $filename;exit;	
	//缓存存在
	if(file_exists($ofilename)){
		//原图展示
		if (isset($tmb)){
			//缩略图缓存文件存在
			if (file_exists($filename)){
				$picdata = file_get_contents($filename);
			}else{
				$resizeimage = new resizeimage($ofilename, $width,$filename);
				$picdata = file_get_contents($filename);
			}
        }elseif($width == 'default'){
			$picdata = file_get_contents($ofilename);
        }else{
            $picdata = file_get_contents($filename);
        }
		
	}else{ //缓存不存在，从数据库取数据，先生成缓存，再进行图片展示
		$q = mysql_query("select * from bookmark_meta where hash_img_url = '$url'",$conn);
		$row = mysql_fetch_array($q);
        $rst = $row['data_image'];
        mysql_close($conn);
        if ($rst){
			//先缓存原始图
			mkFolder($opath);
		 	$fp = fopen($ofilename, 'w+b');
			fwrite($fp, $rst);
		 	fclose($fp);
		 	if (isset($tmb)){
		 		$resizeimage = new resizeimage($ofilename, $width, $filename);
		 		$picdata = file_get_contents($filename);
		 	}else{
		 		$picdata = file_get_contents($ofilename);
		 	}
        }else{
            dolog('Get pic from database error:#1112');
            //$picdata = file_get_contents('./v9.jpg');
            echo 'error';
		}
	}
	
	echo $picdata;

}

function mkFolder($path){
	if(!is_readable($path)){
		mkFolder( dirname($path) );
		if(!is_file($path)){
			mkdir($path,0777);
		}
	}
}
