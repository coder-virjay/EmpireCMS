<?php
require("../../class/connect.php");
require("../../member/class/user.php");
require("../../data/dbcache/MemberLevel.php");
require "../".LoadLang("pub/fun.php");
$link=db_connect();
$empire=new mysqlquery();

//返回SQL
function UserSearchDoKeyboard($f,$hh,$keyboard){
	$keyboard=RepPostVar2($keyboard);
	if(empty($keyboard))
	{
		return '';
	}
	if($hh=='EQ')
	{
		$where=$f."='".$keyboard."'";
	}
	else
	{
		$where=$f." LIKE '%".$keyboard."%'";
	}
	return $where;
}

$editor=1;
eCheckCloseMods('member');//关闭模块
eCheckCloseMods('mlist');//关闭模块
if($public_r['memberlistlevel'])
{
	$user=islogin();
	$mvgresult=eMember_ReturnCheckMVGroup($user,$public_r['memberlistlevel']);
	if($mvgresult<>'empire.cms')
	{
		printerror("NotLevelMemberList","",1);
	}
}
$search='';
$add='';
$where=' where ';
$query='';
$totalquery='';
//用户组
$truegroupid=0;
$formid=0;
$groupid=RepPostVar($_GET['groupid']);
if($groupid)
{
	if(strstr($groupid,','))//多用户组
	{
		$gids='';
		$dh='';
		$gr=explode(',',$groupid);
		$truegroupid=intval($gr[0]);
		$gcount=count($gr);
		for($i=0;$i<$gcount;$i++)
		{
			$gid=(int)$gr[$i];
			$gids.=$dh.$gid;
			$dh=',';
		}
		$add.=' where u.'.egetmf('groupid').' in ('.$gids.')';
	}
	else
	{
		$groupid=(int)$groupid;
		$truegroupid=$groupid;
		$add.=" where u.".egetmf('groupid')."='$groupid'";
	}
	$where=' and ';
	$search.='&groupid='.$groupid;
}
//搜索
$sear=$_GET['sear'];
if($sear)
{
	$search.='&sear=1';
	if($truegroupid)
	{
		$formid=GetMemberFormId($truegroupid);
	}
	$searchf='';
	$show=$_GET['show'];
	$show=eCheckEmptyArray($show);
	$hh=$_GET['hh'];
	$keyboard=$_GET['keyboard'];
	if($formid)
	{
		$uswhere='';
		$andor=$_GET['andor'];
		$andor=$andor=='and'?'and':'or';
		$search.='&andor='.$andor;
		$formr=$empire->fetch1("select searchvar from {$dbtbpre}enewsmemberform where fid='$formid'");
		if(empty($formr['searchvar']))
		{
			$formr['searchvar']=',';
		}
		$formr['searchvar'].='username,';
		$count=count($show);
		for($i=0;$i<$count;$i++)
		{
			if(empty($show[$i]))
			{
				continue;
			}
			$show[$i]=str_replace(',','',$show[$i]);
			if(!strstr($formr['searchvar'],','.$show[$i].','))
			{
				continue;
			}
			$show[$i]=RepPostVar($show[$i]);
			if(stristr(','.$searchf.',',','.$show[$i].','))
			{
				continue;
			}
			$dh=empty($searchf)?'':',';
			$searchf.=$dh.$show[$i];
			if($show[$i]=='username')
			{
				$f='u.'.egetmf('username').'';
			}
			else
			{
				$f='ui.'.$show[$i].'';
			}
			if(strlen($keyboard[$i])>$public_r['max_keyboard'])
			{
				printerror("MinKeyboard","",1);
			}
			$onewhere=UserSearchDoKeyboard($f,$hh[$i],$keyboard[$i]);
			if($onewhere)
			{
				$or=empty($uswhere)?'':' '.$andor.' ';
				$uswhere.=$or.'('.$onewhere.')';
				$search.='&show[]='.$show[$i].'&hh[]='.RepPostStr($hh[$i],1).'&keyboard[]='.RepPostStr($keyboard[$i],1);
			}
		}
		if($uswhere)
		{
			$add.=$where.'('.$uswhere.')';
		}
	}
	else
	{
		$searchf='username';
		if($keyboard[0])
		{
			if(strlen($keyboard[0])>$public_r['max_keyboard'])
			{
				printerror("MinKeyboard","",1);
			}
			$onewhere=UserSearchDoKeyboard('u.'.egetmf('username'),$hh[0],$keyboard[0]);
			if($onewhere)
			{
				$add.=$where.$onewhere;
			}
		}
		$search.='&hh[]='.RepPostStr($hh[0],1).'&keyboard[]='.RepPostStr($keyboard[0],1);
	}
}
$add=" LEFT JOIN {$dbtbpre}enewsmemberadd ui ON u.".egetmf('userid')."=ui.userid".$add;
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=(int)$public_r['member_num'];//每页显示条数
$page_line=10;//每页显示链接数
$offset=$page*$line;//总偏移量
$totalnum=(int)$_GET['totalnum'];
if(!$public_r['usetotalnum'])
{
	$totalnum=0;
}
if($totalnum<1)
{
	$totalquery="select count(*) as total from ".eReturnMemberTable()." u".$add;
	$num=$empire->gettotal($totalquery);//取得总条数
}
else
{
	$num=$totalnum;
}
if($public_r['usetotalnum'])
{
	$search.='&totalnum='.$num;
}
//checkpageno
eCheckListPageNo($page,$line,$num);
//模板
$tempid=(int)$_GET['tempid'];
if(empty($tempid))
{
	$tempid=1;
}
else
{
	$search.='&tempid='.$tempid;
}
$tempfile=ECMS_PATH.'e/template/member/memberlist/'.$tempid.'.php';
if(!file_exists($tempfile))
{
	$tempfile=ECMS_PATH.'e/template/member/memberlist/1.php';
}
$returnpage=page1($num,$line,$page_line,$start,$page,$search);
@include($tempfile);
db_close();
$empire=null;
?>