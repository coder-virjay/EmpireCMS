<?php
require("../class/connect.php");
require("../class/q_functions.php");
require("../member/class/user.php");
eCheckCloseMods('pay');//关闭模块
$link=db_connect();
$empire=new mysqlquery();
//支付平台
$paytype=RepPostVar($_GET['paytype']);
if(!$paytype)
{
	printerror('请选择支付平台','',1,0,1);
}
$payr=$empire->fetch1("select * from {$dbtbpre}enewspayapi where paytype='$paytype' and isclose=0".do_dblimit_one());
if(!$payr['payid'])
{
	printerror('请选择支付平台','',1,0,1);
}
$ddno='';
$productname='';
$productsay='';

include('payfun.php');

//订单信息
$ddid=(int)getcvar('paymoneyddid');
$ddr=PayApiShopDdMoney($ddid);
$money=$ddr['tmoney'];
if($money<=0)
{
	printerror('订单金额有误','',1,0,1);
}
//支付参数
$prdr=array();
$prdr['userid']=0;
$prdr['username']='';
$prdr['orderid']='';
$prdr['money']=$money;
$prdr['posttime']='';
$prdr['paybz']='';
$prdr['paytype']=$payr['paytype'];
$prdr['payip']='';
$prdr['ispay']=0;
$prdr['paydo']='ShopPay';
$prdr['payfor']=$ddid;
$prdr['payckcode']='';
$prdr['endtime']='';
//支付参数
//会员
$ckuserid=(int)getcvar('mluserid');
$ckusername=RepPostVar(getcvar('mlusername'));
$user=array();
if($ckuserid)
{
	$user=islogin();//是否登录
	$prdr['userid']=$user['userid'];
	$prdr['username']=$user['username'];
}
//支付参数
$ddno=$ddr['ddno'];
$prdr['pname']="购物,支付订单号:".$ddno;
$prdr['psay']="购物,订单号:".$ddno;
$prdr['payddno']=epayapi_ReturnDdno($prdr['userid']);
$ddno=$prdr['payddno'];

$productname=$prdr['pname'];
$productsay=$prdr['psay'];

esetcookie("payphome","ShopPay",0);
//返回地址前缀
$PayReturnUrlQz=$public_r['newsurl'];
if(!stristr($public_r['newsurl'],'://'))
{
	$PayReturnUrlQz=eReturnDomain().$public_r['newsurl'];
}
//char
$iconv='';
$char='';
$targetchar='';
/*
if($ecms_config['sets']['pagechar']!='gb2312')
{
	$char=$ecms_config['sets']['pagechar']=='big5'?'BIG5':'UTF8';
	$targetchar='GB2312';
	$productname=DoIconvVal($char,$targetchar,$productname);
	$productsay=DoIconvVal($char,$targetchar,$productsay);
	@header('Content-Type: text/html; charset=gb2312');
}
*/
//记录
$re_prdr=epayapi_AddPayRecord($prdr);
$prdr['id']=$re_prdr['id'];
$prdr['payckcode']=$re_prdr['payckcode'];
$prdr['posttime']=$re_prdr['posttime'];
$prdr['payip']=$re_prdr['payip'];

$file=$payr['paytype'].'/to_pay.php';
@include($file);
db_close();
$empire=null;
?>