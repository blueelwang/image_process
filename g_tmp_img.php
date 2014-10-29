<?php
define('GETPIC',true);
require './config.php';

header("Content-Type:image/jpeg");

$conn = mysql_connect(HOST,USER,PASSWD) or die('Database connect fail');
$db_se = mysql_select_db(DATABASE);
mysql_query("set names 'utf8'");
$url = strtolower(trim($_GET['key']));
if(strlen($url)!=32){
    return false;
}
$q = mysql_query("select * from tmp_img where hash_url = '$url'",$conn);
$row = mysql_fetch_array($q);
$rst = $row['data_image'];
mysql_close($conn);
if ($rst){
    echo $rst;
}
