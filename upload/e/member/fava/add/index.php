<?php
require("../../../class/connect.php");
require("../../../class/q_functions.php");
require("../../../data/dbcache/class.php");
require("../../class/user.php");
require('../../class/favfun.php');
$link=db_connect();
$empire=new mysqlquery();
$editor=2;
eCheckCloseMods('member');//关闭模块
$user=islogin();
$id=(int)$_GET['id'];
$classid=(int)$_GET['classid'];
if(!$id||!$classid||!$class_r[$classid]['tbname']||InfoIsInTable($class_r[$classid]['tbname']))
{
	printerror("ErrorUrl","",1);
}
//链接
$r=$empire->fetch1("select isurl,titleurl,classid,id,title from {$dbtbpre}ecms_".$class_r[$classid]['tbname']." where id='$id'".do_dblimit_one());
if(empty($r['id'])||$r['classid']!=$classid)
{
	printerror("ErrorUrl","",1);
}
$titleurl=sys_ReturnBqTitleLink($r);
//返回分类
$cid=(int)$_GET['cid'];
$select=ReturnFavaClass($user['userid'],$cid);
$from=EcmsGetReturnUrl();
//导入模板
include(ECMS_PATH.'e/template/member/AddFava.php');
db_close();
$empire=null;
?>