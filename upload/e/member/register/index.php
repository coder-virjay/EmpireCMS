<?php
require("../../class/connect.php");
require("../class/user.php");
require("../class/member_registerfun.php");
require('../../data/dbcache/MemberLevel.php');
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
eCheckCloseMods('member');//关闭模块
//关闭
if($public_r['register_ok'])
{
	printerror("CloseRegister","history.go(-1)",1);
}
//验证时间段允许操作
eCheckTimeCloseDo('reg');
//验证IP
eCheckAccessDoIp('register');
$tobind=(int)$_GET['tobind'];
//转向注册
if(!empty($ecms_config['member']['registerurl']))
{
	Header("Location:".$ecms_config['member']['registerurl']);
	exit();
}
//已经登陆不能注册
if(getcvar('mluserid'))
{
	printerror("LoginToRegister","history.go(-1)",1);
}
if(!empty($ecms_config['member']['changeregisterurl'])&&!$_GET['groupid'])
{
	$changeregisterurl=$ecms_config['member']['changeregisterurl'];
	if($tobind)
	{
		$changeregisterurl.='?tobind=1';
	}
	Header("Location:".$changeregisterurl);
	exit();
}

$groupid=(int)$_GET['groupid'];
$groupid=$groupid?$groupid:eReturnMemberDefGroupid();
CheckMemberGroupCanReg($groupid);
$formid=GetMemberFormId($groupid);
if(empty($formid))
{
	printerror('ErrorUrl','',1);
}
$ecmsfirstpost=1;
$formid=(int)$formid;
$formfile='../../../c/ecachemod/emodpub/memberform'.$formid.'.php';
if(eDoCheckHvFile($formfile)==0)
{
	exit();
}
//导入模板
include(ECMS_PATH.'e/template/member/register.php');
db_close();
$empire=null;
?>