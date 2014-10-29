<?php

define('GETPIC',true);
require './config.php';
$targetFolder = '/uploads'; // Relative to the root
 $handle = fopen('./log.txt','a+b');
fwrite($handle,'1');
fclose($handle);
if (!empty($_FILES)) {
    print_r($_FILES);exit;
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	$url = $_POST['url'];
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
    $fileParts = pathinfo($_FILES['Filedata']['name']);
    echo 'good work1';
    $handle = fopen('log.txt','a+b');
    fwrite($handle,'1');
    fclose($handle);
	if (in_array($fileParts['extension'],$fileTypes)) {
        //move_uploaded_file($tempFile,$targetFile);
    //    echo 'good work';
		$handle = fopen($tempFile,'rb');
		if (get_magic_quotes_gpc()){
			$data = fread($handle, filesize($tempFile));
		}else{
			$data = addslashes(fread($handle, filesize($tempFile)));
		}
	//	$conn = mysql_connect(HOST,USER,PASSWD) or die('Database connect fail');
	//	$db_se = mysql_select_db(DATABASE);
	//	mysql_query("set names 'utf8'");
	//	$insert = mysql_query("insert into tmp_img (hash_url,data_image) values ($url,'$data')",$conn);
	//	if($insert){
	//		echo 'http://s.v9.com/getpic/g_tmp_pic.php?key='.md5($url);
	//	}
	} else {
		echo 'Invalid file type.';
	}
}
?>
