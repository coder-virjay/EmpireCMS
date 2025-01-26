<?php
require('../../class/connect.php');
require('../../class/functions.php');
require('../../class/t_functions.php');
require('../../data/dbcache/class.php');
require '../'.LoadLang('pub/fun.php');
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
$classid=(int)$_GET['classid'];
$chtempid=(int)$_GET['tempid'];
$chctempid=(int)$_GET['ctempid'];
if(empty($classid))
{
	printerror("ErrorUrl","history.go(-1)",1);
}
$search='&classid='.$classid;
$tbname=$class_r[$classid]['tbname'];
$mid=$class_r[$classid]['modid'];
if(empty($tbname)||empty($mid)||InfoIsInTable($tbname))
{
	printerror("ErrorUrl","history.go(-1)",1);
}
$cr=$empire->fetch1("select classid,classpagekey,intro,classimg,cgroupid,islist,classtempid,listdt,bdinfoid,repagenum,islast,infos,addsql,fclast,ecmsvpf from {$dbtbpre}enewsclass where classid='$classid'");
if(empty($cr['classid']))
{
	printerror("ErrorUrl","history.go(-1)",1);
}
//viewpass
if($cr['ecmsvpf'])
{
	define('EMPIRECMSVP','empirecms');
	define('EMPIRECMSVPPATH','../../../');
	$ecms_tofunr['viewpassecms']=0;
	$ecms_tofunr['viewpassckvar']='ecmsvpc'.$cr['classid'];
	$ecms_tofunr['viewpassckpass']=$cr['ecmsvpf'];
	$ecms_tofunr['viewpasstitle']=stripSlashes($class_r[$cr['classid']]['classname']);
	$ecms_tofunr['viewpassaddvn']=2;
	@include("../../class/checkviewpass.php");
}
if($class_r[$classid]['islast']&&$cr['bdinfoid'])
{
	printerror("ErrorUrl","history.go(-1)",1);
}
//moreport
if(Moreport_ReturnMustDt())
{
	$class_r[$classid]['listdt']=1;
	$cr['repagenum']=0;
	$cr['listdt']=1;
}
//moretemp
if($public_r['chclasstemp']&&$chctempid)
{
	$class_r[$classid]['listdt']=1;
	$cr['repagenum']=0;
	$cr['listdt']=1;
}
//moretemp
if($public_r['chlisttemp']&&$chtempid)
{
	$class_r[$classid]['listdt']=1;
	$cr['repagenum']=0;
	$cr['listdt']=1;
}
//是否支持动态页
if(empty($class_r[$classid]['listdt'])&&!$cr['repagenum'])
{
	$classurl=sys_ReturnBqClassname($cr,9);
	Header("Location:$classurl");
	exit();
}
//权限
if($cr['cgroupid'])
{
	include('../../member/class/user.php');
	@include("../../data/dbcache/MemberLevel.php");
	$user=islogin();
	$mvgresult=eMember_ReturnCheckMVGroup($user,$cr['cgroupid']);
	if($mvgresult<>'empire.cms')
	{
		printerror('NotLevelToClass','history.go(-1)',1);
	}
}
//缓存
if($public_r['ctimeopen'])
{
	$public_r['usetotalnum']=0;
}
$ecms_tofunr=array();
$ecms_tofunr['cacheids_add']='';

//多列表模板
$tempr=array();
$tempr['tempid']=0;
$ecms_tofunr['eusechtemp']=0;

$ecms_tofunr['cacheuse']=0;
$ecms_tofunr['cacheselfcid']=$classid;
$ecms_tofunr['cachepath']='empirecms';
//缓存
$GLOBALS['navclassid']=$classid;
$url=ReturnClassLink($classid);
$pagetitle=$class_r[$classid]['classname'];
$pagekey=$cr['classpagekey'];
$pagedes=$cr['intro'];
$classimg=$cr['classimg']?$cr['classimg']:$public_r['newsurl'].'e/data/images/notimg.gif';
//---封面式---
if(!$class_r[$classid]['islast']&&$cr['islist']!=1)
{
	if(empty($cr['listdt'])||$cr['islist']==3)
	{
		printerror("ErrorUrl","history.go(-1)",1);
	}
	
	//多封面模板
	if($cr['islist']==0)
	{
		if($public_r['chclasstemp']&&$chctempid)
		{
			if(strstr($public_r['nchclasstemp'],','.$chctempid.','))
			{
				printerror("ErrorUrl","history.go(-1)",1);
			}
			$tempr=$empire->fetch1("select tempid,temptext from ".GetTemptb("enewsclasstemp")." where tempid='$chctempid'");
			if(!$tempr['tempid'])
			{
				printerror("ErrorUrl","history.go(-1)",1);
			}
			$ecms_tofunr['cacheids_add']=','.$chctempid;
			$ecms_tofunr['eusechtemp']=$chctempid;
			$search.="&ctempid=$chctempid";
		}
	}
	
	//封面：缓存
	$ecms_tofunr['cachetype']='classpage';
	$ecms_tofunr['cacheids']=$classid.$ecms_tofunr['cacheids_add'];
	$ecms_tofunr['cachedatepath']='cpage';
	$ecms_tofunr['cachetime']=$public_r['ctimeclass'];
	$ecms_tofunr['cachelasttime']=$public_r['ctimelast'];
	$ecms_tofunr['cachelastedit']=$cr['fclast'];
	$ecms_tofunr['cacheopen']=Ecms_eCacheCheckOpen($ecms_tofunr['cachetime']);
	if($ecms_tofunr['cacheopen']==1)
	{
		$ecms_tofunr['cacheuse']=Ecms_eCacheOut($ecms_tofunr,0);
	}
	//封面：缓存
	if($cr['islist']==2)
	{
		$classtemp=GetClassText($classid);
		$dttempname='classpage'.$classid;
	}
	else
	{
		if(empty($cr['classtempid']))
		{
			printerror('ErrorUrl','',1);
		}
		if($tempr['tempid'])
		{
			$classtemp=$tempr['temptext'];
			$dttempname='classtemp'.$tempr['tempid'];
		}
		else
		{
			$classtemp=GetClassTemp($cr['classtempid']);
			$dttempname='classtemp'.$cr['classtempid'];
		}
	}
	$string=DtNewsBq($dttempname,$classtemp,0);
	$string=str_replace('[!--newsnav--]',$url,$string);//位置导航
	$string=Class_ReplaceSvars($string,$url,$classid,$pagetitle,$pagekey,$pagedes,$classimg,$addr,0);
	$string=str_replace('[!--page.stats--]','',$string);
	//封面：缓存
	if($ecms_tofunr['cacheopen']==1)
	{
		Ecms_eCacheIn($ecms_tofunr,stripSlashes($string));
	}
	else
	{
		echo stripSlashes($string);
	}
	//封面：缓存
	exit();
}
//---列表式---

//多列表模板
if($public_r['chlisttemp']&&$chtempid)
{
	if(strstr($public_r['nchlisttemp'],','.$chtempid.','))
	{
		printerror("ErrorUrl","history.go(-1)",1);
	}
	$tempr=$empire->fetch1("select tempid,temptext,subnews,listvar,rownum,showdate,modid,subtitle,docode from ".GetTemptb("enewslisttemp")." where tempid='$chtempid'");
	if(!$tempr['tempid'])
	{
		printerror("ErrorUrl","history.go(-1)",1);
	}
	if($public_r['chlisttemp']==2)//模型
	{
		if($mid!=$tempr['modid'])
		{
			printerror("ErrorUrl","history.go(-1)",1);
		}
	}
	elseif($public_r['chlisttemp']==3)
	{
		if($tbname!=$emod_r[$tempr['modid']]['tbname'])
		{
			printerror("ErrorUrl","history.go(-1)",1);
		}
	}
	else
	{}
	$ecms_tofunr['cacheids_add']=','.$chtempid;
	$ecms_tofunr['eusechtemp']=$chtempid;
	$search.="&tempid=$chtempid";
}

$add='';
//栏目
if($class_r[$classid]['islast'])//终极栏目
{
	$add.="classid='$classid'";
	$have_class=0;
}
else
{
	$add.=ReturnClass($class_r[$classid]['sonclass']);
	$have_class=1;
}
if($cr['addsql'])
{
	$add.=' and ('.$cr['addsql'].')';
}
//排序
if(empty($class_r[$classid]['reorder']))
{
	$addorder="newstime desc";
}
else
{
	$addorder=$class_r[$classid]['reorder'];
}
//列表模板
$tempid=(int)$tempr['tempid'];
if(!$tempr['tempid'])
{
	$tempid=$class_r[$classid]['dtlisttempid']?$class_r[$classid]['dtlisttempid']:$class_r[$classid]['listtempid'];
	$tempid=(int)$tempid;
	if(empty($tempid))
	{
		printerror('ErrorUrl','',1);
	}
	$tempr=$empire->fetch1("select tempid,temptext,subnews,listvar,rownum,showdate,modid,subtitle,docode from ".GetTemptb("enewslisttemp")." where tempid='$tempid'");
	if(empty($tempr['tempid']))
	{
		printerror('ErrorUrl','',1);
	}
}
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=$class_r[$classid]['lencord'];//每页显示记录数
$page_line=10;//每页显示链接数
$offset=$page*$line;//总偏移量
//列表：缓存
$ecms_tofunr['cachetype']='classlist';
$ecms_tofunr['cacheids']=$classid.$ecms_tofunr['cacheids_add'].','.$page;
$ecms_tofunr['cachedatepath']='clist/'.$classid;
$ecms_tofunr['cachetime']=$public_r['ctimelist'];
$ecms_tofunr['cachelasttime']=$public_r['ctimelast'];
$ecms_tofunr['cachelastedit']=$cr['fclast'];
$ecms_tofunr['cacheopen']=Ecms_eCacheCheckOpen($ecms_tofunr['cachetime']);
if($ecms_tofunr['cacheopen']==1)
{
	$ecms_tofunr['cacheuse']=Ecms_eCacheOut($ecms_tofunr,0);
}
//列表：缓存
//系统模型
$ret_r=ReturnReplaceListF($mid);
//优化
$yhadd='';
$yhid=$class_r[$classid]['yhid'];
$yhvar='qlist';
if($yhid)
{
	$yhadd=ReturnYhSql($yhid,$yhvar,1);
}
//总数
$totalnum=(int)$_GET['totalnum'];
if(!$public_r['usetotalnum'])
{
	$totalnum=0;
}
if($totalnum<1)
{
	if($yhadd||$cr['addsql'])
	{
		$totalquery="select count(*) as total from {$dbtbpre}ecms_".$tbname." where ".$yhadd.$add;
		$num=$empire->gettotal($totalquery);
	}
	else
	{
		$num=ReturnClassInfoNum($cr,0);
	}
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
$query="select ".ReturnSqlListF($mid)." from {$dbtbpre}ecms_".$tbname." where ".$yhadd.$add;
$query.=" order by ".ReturnSetTopSql('list').$addorder."".do_dblimit($line,$offset);
$sql=$empire->query($query);
//伪静态
$pagefunr=eReturnRewriteClassUrl($classid,0,$ecms_tofunr['eusechtemp']);
$pagefunr['repagenum']=$cr['repagenum'];
$pagefunr['dolink']=empty($class_r[$classid]['classurl'])?$public_r['newsurl'].$class_r[$classid]['classpath'].'/':$class_r[$classid]['classurl'].'/';
$pagefunr['dofile']='index';
$pagefunr['dotype']=$class_r[$classid]['classtype'];
//分页
if($pagefunr['rewrite']==1||$pagefunr['repagenum'])
{
	$listpage=InfoUsePage($num,$line,$page_line,$start,$page,$search,$pagefunr);
}
else
{
	$listpage=page1($num,$line,$page_line,$start,$page,$search);
}
//页面支持标签
if($public_r['dtcanbq'])
{
	$tempr['temptext']=DtNewsBq('list'.$tempid,$tempr['temptext'],0);
}
else
{
	if($public_r['searchtempvar'])
	{
		$tempr['temptext']=ReplaceTempvar($tempr['temptext']);
	}
}
$listtemp=$tempr['temptext'];
$rownum=$tempr['rownum'];
if(empty($rownum))
{$rownum=1;}
$formatdate=$tempr['showdate'];
$subnews=$tempr['subnews'];
$subtitle=$tempr['subtitle'];
$docode=$tempr['docode'];
$modid=$tempr['modid'];
$listvar=str_replace('[!--news.url--]',$public_r['newsurl'],$tempr['listvar']);
//公共
$listtemp=str_replace('[!--newsnav--]',$url,$listtemp);//位置导航
$listtemp=Class_ReplaceSvars($listtemp,$url,$classid,$pagetitle,$pagekey,$pagedes,$classimg,$addr,0);
$listtemp=str_replace('[!--page.stats--]','',$listtemp);
$listtemp=str_replace('[!--show.page--]',$listpage,$listtemp);
$listtemp=str_replace('[!--show.listpage--]',$listpage,$listtemp);
$listtemp=str_replace('[!--list.pageno--]',$page+1,$listtemp);
//取得列表模板
$list_exp="[!--empirenews.listtemp--]";
$list_r=explode($list_exp,$listtemp);
$listtext=$list_r[1];
$no=$offset+1;
$changerow=1;
while($r=$empire->fetch($sql))
{
	//替换列表变量
	$repvar=ReplaceListVars($no,$listvar,$subnews,$subtitle,$formatdate,$url,$have_class,$r,$ret_r,$docode);
	$listtext=str_replace("<!--list.var".$changerow."-->",$repvar,$listtext);
	$changerow+=1;
	//超过行数
	if($changerow>$rownum)
	{
		$changerow=1;
		$string.=$listtext;
		$listtext=$list_r[1];
	}
	$no++;
}
//多余数据
if($changerow<=$rownum&&$listtext<>$list_r[1])
{
	$string.=$listtext;
}
$string=$list_r[0].$string.$list_r[2];
//列表：缓存
if($ecms_tofunr['cacheopen']==1)
{
	Ecms_eCacheIn($ecms_tofunr,stripSlashes($string));
}
else
{
	echo stripSlashes($string);
}
//列表：缓存
db_close();
$empire=null;
?>