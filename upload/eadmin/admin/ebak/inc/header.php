<?php
error_reporting(0);
if(!defined('INEMPIREBAKDT'))
{
	exit();
}
define('EmpireCMSAdmin','1');
define('InEmpireBak',TRUE);
define('InEmpireBakRe',TRUE);
@require("../../../../../e/class/connect.php");
if(!defined('InEmpireCMS'))
{
	exit();
}
$b_dbchar='';
$b_table='';
require("config.php");
require("../../../../../e/class/functions.php");
require("../../class/functions.php");
require "../../../../../e/data/".LoadLang("pub/fun.php");
ehCheckCloseMods('ebak');
$link=db_connect();
$empire=new mysqlquery();
//验证用户
$logininid=intval(getcvar('loginuserid',1));
$loginin=RepPostVar(getcvar('loginusername',1));
$loginrnd=RepPostVar(getcvar('loginrnd',1));
$loginlevel=intval(getcvar('loginlevel',1));
$editor=3;
is_login_ebak($logininid,$loginin,$loginrnd);
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();
//验证权限
//CheckLevel($logininid,$loginin,$classid,"dbdata");
$phome='ReDataF';
hCheckEcmsRHash();
$mydbname=RepPostVar($_GET['mydbname']);
$mypath=RepPostStr($_GET['mypath'],1);
if(empty($mydbname)||empty($mypath))
{
	printerror("ErrorUrl","history.go(-1)");
}
eCheckStrType(4,$mydbname,1);
eCheckStrType(5,$mypath,1);
//编码
DoSetDbChar($b_dbchar);
$usql=$empire->usequery("use $mydbname");
?>