<?php
require("../class/connect.php");
require("../data/dbcache/class.php");
require("../member/class/user.php");
require("../data/dbcache/MemberLevel.php");
require LoadLang("pub/fun.php");
$link=db_connect();
$empire=new mysqlquery();
$enews=$_POST['enews'];
if(empty($enews))
{
	$enews=$_GET['enews'];
}
//导入文件
if($enews=='DownSoft'||$enews=='GetSofturl')
{
	$enews=='DownSoft'?eCheckCloseMods('down'):eCheckCloseMods('movie');//关闭模块
	include('class/DownSysFun.php');
}
if($enews=="DownSoft")//下载软件
{
	$classid=$_GET['classid'];
	$id=$_GET['id'];
	$pathid=$_GET['pathid'];
	$p=$_GET['p'];
	$pass=$_GET['pass'];
	DownSoft($classid,$id,$pathid,$p,$pass);
}
elseif($enews=="GetSofturl")//取得软件地址
{
	$classid=$_GET['classid'];
	$id=$_GET['id'];
	$pathid=$_GET['pathid'];
	$p=$_GET['p'];
	$pass=$_GET['pass'];
	$onlinetime=$_GET['onlinetime'];
	$onlinepass=$_GET['onlinepass'];
	$movtime=20;
	GetSofturl($classid,$id,$pathid,$p,$pass,$onlinetime,$onlinepass);
}
else
{printerror("ErrorUrl","history.go(-1)",1);}
db_close();
$empire=null;
?>