<?php
require('../class/connect.php');
require('../class/functions.php');
require('../class/t_functions.php');
require('../data/dbcache/class.php');
require LoadLang('pub/fun.php');
$link=db_connect();
$empire=new mysqlquery();
if(!$public_r['openfz'])
{
	printerror('CloseFzPage','',1);
}
if(!$public_r['openfzpage'])
{
	printerror('CloseFzPage','',1);
}

//cs

$get_evr['fztid']=(int)$_GET['fztid'];
$get_evr['fzid']=(int)$_GET['fzid'];
$get_evr['cid']=(int)$_GET['cid'];

//cs

$defaddorder='newstime desc';
$defline=(int)$public_r['tagslistnum'];
//参数
$ftbname=$etable_t[$get_evr['fztid']]['tbname'];
if(!$get_evr['fztid']||!$get_evr['fzid']||!$ftbname||InfoIsInTable($ftbname))
{
	printerror('ErrorUrl','',1);
}
if(!$get_evr['cid'])
{
	printerror('ErrorUrl','',1);
}
//父信息
$bpubid=ReturnInfoPubid(0,$get_evr['fzid'],$get_evr['fztid']);
$fzinfor=$empire->fetch1("select * from {$dbtbpre}enewsfz_info where pubid='".$bpubid."'".do_dblimit_one());
if(!$fzinfor['id']||!$fzinfor['fzstb'])
{
	printerror('ErrorUrl','',1);
}
$infor=$empire->fetch1("select * from {$dbtbpre}ecms_".$ftbname." where id='".$fzinfor['id']."'".do_dblimit_one());
if(!$infor['id'])
{
	printerror('ErrorUrl','');
}
//分类
$fzdatacr=$empire->fetch1("select * from {$dbtbpre}enewsfz_class where cid='".$get_evr['cid']."' and pubid='".$fzinfor['pubid']."'".do_dblimit_one());
if(!$fzdatacr['cid'])
{
	printerror('ErrorUrl','',1);
}
if(!$fzdatacr['isopen'])
{
	printerror('CloseFzPageC','',1);
}
$defaddorder=$fzdatacr['reorder'];
$defline=$fzdatacr['lencord'];
$deflisttempid=$fzdatacr['listtempid'];
//缓存
if($public_r['ctimeopen'])
{
	$public_r['usetotalnum']=0;
}
$ecms_tofunr=array();
$ecms_tofunr['cacheuse']=0;
$ecms_tofunr['cachepath']='empirecms';
//缓存
//初始
$defadd='';
$add='';
$search='';
$GLOBALS['navclassid']=$fzdatacr['cid'];

$pagetitle=ehtmlspecialchars($fzdatacr['cname']);
$pagekey=$pagetitle;
$pagedes=$pagetitle;
$classimg=$public_r['newsurl'].'e/data/images/notimg.gif';
$url=ReturnClassLink($infor['classid']).'&nbsp;'.$public_r['navfh'].'&nbsp;'.$infor['title'].'&nbsp;'.$public_r['navfh'].'&nbsp;'.$fzdatacr['cname'];
$pageecms=1;
$pageclassid=0;
$have_class=1;
$search.='&fztid='.$get_evr['fztid'].'&fzid='.$get_evr['fzid'].'&cid='.$get_evr['cid'];
//页面模式
if($fzdatacr['islist']!=1)
{
	//绑定信息
	if($fzdatacr['islist']==2)
	{
		if(!$fzdatacr['bdinfoid'])
		{
			printerror('ErrorUrl','',1);
		}
		$bdinfo=explode(',',$fzdatacr['bdinfoid']);
		$bdinfo[0]=(int)$bdinfo[0];
		$bdinfo[1]=(int)$bdinfo[1];
		if(!$bdinfo[0]||!$bdinfo[1]||!$class_r[$bdinfo[0]]['tbname'])
		{
			printerror('ErrorUrl','',1);
		}
		$bdinfor=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$bdinfo[0]]['tbname']." where id='".$bdinfo[1]."'".do_dblimit_one());
		$titleurl=sys_ReturnBqTitleLink($bdinfor);
		Header("Location:$titleurl");
		exit();
	}
	//封面式
	if(!$fzdatacr['classtempid'])
	{
		printerror('ErrorUrl','',1);
	}

	//封面：缓存
	$ecms_tofunr['cachetype']='fzpage';
	$ecms_tofunr['cacheids']=$fzdatacr['cid'];
	$ecms_tofunr['cachedatepath']='cfzinfo/'.$fzdatacr['cid'];
	$ecms_tofunr['cachetime']=$public_r['ctimefz'];
	$ecms_tofunr['cachelasttime']=$public_r['ctimelast'];
	$ecms_tofunr['cachelastedit']=$fzinfor['fclast'];
	$ecms_tofunr['cacheopen']=Ecms_eCacheCheckOpen($ecms_tofunr['cachetime']);
	if($ecms_tofunr['cacheopen']==1)
	{
		$ecms_tofunr['cacheuse']=Ecms_eCacheOut($ecms_tofunr,0);
	}
	//封面：缓存
	$classtemp=GetClassTemp($fzdatacr['classtempid']);
	$dttempname='classtemp'.$fzdatacr['classtempid'];

	$string=DtNewsBq($dttempname,$classtemp,0);
	$string=str_replace('[!--newsnav--]',$url,$string);//位置导航
	$string=Class_ReplaceSvars($string,$url,$fzdatacr['cid'],$pagetitle,$pagekey,$pagedes,$classimg,$addr,$pageecms);
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

if(!$fzdatacr['listtempid'])
{
	printerror('ErrorUrl','',1);
}
//分类
if($fzdatacr['bcid'])
{
	$isbcid=0;
	$defadd.="cid='$cid'";
}
else
{
	$isbcid=1;
	$defadd.="bcid='$bcid'";
}
//模型ID
$mid=(int)$_GET['mid'];
if($mid)
{
	if(empty($emod_r[$mid]['tbname']))
	{
		printerror('ErrorUrl','',1);
	}
	$add.=" and mid='$mid'";
	$search.='&mid='.$mid;
}
//栏目
$trueclassid=0;
$classid=$_GET['classid'];
if($classid)
{
	$classid=RepPostVar($classid);
	if(strstr($classid,','))//多栏目
	{
		$son_r=sys_ReturnMoreClass($classid,1);
		$trueclassid=$son_r[0];
		$add.=' and ('.$son_r[1].')';
	}
	else
	{
		$trueclassid=intval($classid);
		if($class_r[$trueclassid]['islast'])//终极栏目
		{
			$add.=" and classid='$trueclassid'";
			$have_class=0;
		}
		else
		{
			$add.=' and '.ReturnClass($class_r[$trueclassid]['sonclass']);
		}
		$pageclassid=$trueclassid;
	}
	if(empty($class_r[$trueclassid]['tbname']))
	{
		printerror('ErrorUrl','',1);
	}
	$search.='&classid='.$classid;
}
//头条
if($_GET['firsttitle'])
{
	$firsttitle=(int)$_GET['firsttitle'];
	if($firsttitle==-1)
	{
		$add.=" and firsttitle>0";
	}
	else
	{
		$add.=" and firsttitle='".$firsttitle."'";
	}
	$search.='&firsttitle='.$firsttitle;
}
//推荐
if($_GET['isgood'])
{
	$isgood=(int)$_GET['isgood'];
	if($isgood==-1)
	{
		$add.=" and isgood>0";
	}
	else
	{
		$add.=" and isgood='".$isgood."'";
	}
	$search.='&isgood='.$isgood;
}
//时间
if($_GET['endtime'])
{
	$starttime=RepPostVar($_GET['starttime']);
	if(empty($starttime))
	{
		$starttime='0000-00-00';
	}
	$endtime=RepPostVar($_GET['endtime']);
	if(empty($endtime))
	{
		$endtime='0000-00-00';
	}
	if($endtime!='0000-00-00')
	{
		$add.=" and (newstime BETWEEN '".to_time($starttime." 00:00:00")."' and '".to_time($endtime." 23:59:59")."')";
		$search.='&starttime='.$starttime.'&endtime='.$endtime;
	}
}
//每页显示记录数
$line=(int)$defline;
if(empty($line))
{
	printerror('ErrorUrl','',1);
}
//排序
$addorder=$defaddorder;
$myorder=0;
/*
$myorder=(int)$_GET['myorder'];
if($myorder)
{
	$addorder=$myorder==1?'newstime asc':'newstime desc';
	$search.='&myorder='.$myorder;
}
*/

//列表模板
$tempid=(int)$deflisttempid;
if(empty($tempid))
{
	printerror('ErrorUrl','',1);
}
$tempr=$empire->fetch1("select tempid,temptext,subnews,listvar,rownum,showdate,modid,subtitle,docode from ".GetTemptb("enewslisttemp")." where tempid='$tempid'");
if(empty($tempr['tempid']))
{
	printerror('ErrorUrl','',1);
}

if(empty($mid))
{
	$mid=$tempr['modid'];
}
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$page_line=10;//每页显示链接数
$offset=$page*$line;//总偏移量
//缓存
$ecms_tofunr['cachetype']='fzpage';
$ecms_tofunr['cacheids']=$fzdatacr['cid'].','.$page.','.$tempid;
$ecms_tofunr['cachedatepath']='cfzinfo/'.$fzdatacr['cid'];
$ecms_tofunr['cachetime']=$public_r['ctimefz'];
$ecms_tofunr['cachelasttime']=$public_r['ctimelast'];
$ecms_tofunr['cachelastedit']=$fzinfor['fclast'];
$ecms_tofunr['cacheopen']=Ecms_eCacheCheckOpen($ecms_tofunr['cachetime']);
$ecms_tofunr['cachehavedo']=0;
if($ecms_tofunr['cacheopen']==1&&empty($add)&&$line==$defline&&!$myorder)
{
	$ecms_tofunr['cacheuse']=Ecms_eCacheOut($ecms_tofunr,0);
	$ecms_tofunr['cachehavedo']=1;
}
//缓存
//系统模型
$ret_r=ReturnReplaceListF($mid);
//总数
$totalnum=(int)$_GET['totalnum'];
if(!$public_r['usetotalnum'])
{
	$totalnum=0;
}
if($totalnum<1)
{
	$totalquery="select count(*) as total from {$dbtbpre}enewsfz_data_".$fzinfor['fzstb']." where ".$defadd.$add;
	$num=$empire->gettotal($totalquery);
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
$query="select id,classid from {$dbtbpre}enewsfz_data_".$fzinfor['fzstb']." where ".$defadd.$add;
$query.=" order by ".$addorder."".do_dblimit($line,$offset);
$sql=$empire->query($query);
if(!empty($public_r['rewritefz'])&&empty($add)&&($search=='&fztid='.$get_evr['fztid'].'&fzid='.$get_evr['fzid'].'&cid='.$get_evr['cid'])&&!$myorder)
{
	//伪静态
	$pagefunr=eReturnRewriteFzUrl($get_evr['fztid'],$get_evr['fzid'],$get_evr['cid'],0);
	$pagefunr['repagenum']=0;
	//分页
	if($pagefunr['rewrite']==1)
	{
		$listpage=InfoUsePage($num,$line,$page_line,$start,$page,$search,$pagefunr);
	}
	else
	{
		$listpage=page1($num,$line,$page_line,$start,$page,$search);
	}
}
else
{
	$listpage=page1($num,$line,$page_line,$start,$page,$search);//分页
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
$listtemp=Class_ReplaceSvars($listtemp,$url,$fzdatacr['cid'],$pagetitle,$pagekey,$pagedes,$classimg,$addr,$pageecms);
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
	if(empty($class_r[$r['classid']]['tbname']))
	{
		continue;
	}
	$infor=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$r['classid']]['tbname']." where id='".$r['id']."'".do_dblimit_one());
	if(empty($infor['id']))
	{
		continue;
	}
	//替换列表变量
	$repvar=ReplaceListVars($no,$listvar,$subnews,$subtitle,$formatdate,$url,$have_class,$infor,$ret_r,$docode);
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
//缓存
if($ecms_tofunr['cacheopen']==1&&$ecms_tofunr['cachehavedo']==1)
{
	Ecms_eCacheIn($ecms_tofunr,stripSlashes($string));
}
else
{
	echo stripSlashes($string);
}
//缓存
db_close();
$empire=null;
?>