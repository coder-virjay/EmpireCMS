<?php
require('../../class/connect.php');
require('../../data/dbcache/class.php');
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
$classid=(int)$_GET['classid'];
$id=(int)$_GET['id'];
if(empty($id)||empty($classid)||!$class_r[$classid]['tbname']||InfoIsInTable($class_r[$classid]['tbname']))
{
	printerror("NotVote","history.go(-1)",9);
}
$infor=$empire->fetch1("select id,classid,title,isurl,titleurl from {$dbtbpre}ecms_".$class_r[$classid]['tbname']." where id='$id'".do_dblimit_one());
if(empty($infor['id'])||$infor['classid']!=$classid)
{
	printerror("NotVote","history.go(-1)",9);
}
$pubid=ReturnInfoPubid($classid,$id);
$r=$empire->fetch1("select id,title,votetext,voteclass,votenum from {$dbtbpre}enewsinfovote where pubid='$pubid'".do_dblimit_one());
if(empty($r['id'])||empty($r['votetext']))
{
	printerror("NotVote","history.go(-1)",9);
}
if(empty($r['title']))
{
	$r['title']=$infor['title'];
}
$r_exp="\r\n";
$f_exp="::::::";
if($r['voteclass'])
{
	$voteclass="多选";
}
else
{
	$voteclass="单选";
}
$titleurl=sys_ReturnBqTitleLink($infor);
$infor['title']=stripSlashes($infor['title']);
//导入模板
include(ECMS_PATH.'e/template/public/vote.php');
db_close();
$empire=null;
?>