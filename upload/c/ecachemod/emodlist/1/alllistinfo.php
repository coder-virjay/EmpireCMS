<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<?php
//增加查询自定义字段(必须是主表的字段)，格式：',字段1,字段2'(多个字段用半角逗号隔开)，例子：',ftitle'
$queryaddf='';

//查询SQL
$query="select id,classid,isurl,titleurl,isqf,havehtml,istop,isgood,firsttitle,ismember,userid,username,eckuid,plnum,totaldown,onclick,newstime,truetime,lastdotime,titlepic,title,efz".$queryaddf." from ".$infotb.$where." order by ".$doorder."".do_dblimit($line,$offset);
$sql=$empire->query($query);
//返回头条和推荐级别名称
$ftnr=ReturnFirsttitleNameList(0,0);
$ftnamer=$ftnr['ftr'];
$ignamer=$ftnr['igr'];
$searchisgoods='';
$searchfirsttitles='';
if($showisgood>0)
{
	$searchisgoods=str_replace('<option value="'.$showisgood.'">','<option value="'.$showisgood.'" selected>',$ftnr['igname']);
}
else
{
	$searchisgoods=$ftnr['igname'];
}
if($showfirsttitle>0)
{
	$searchfirsttitles=str_replace('<option value="'.$showfirsttitle.'">','<option value="'.$showfirsttitle.'" selected>',$ftnr['ftname']);
}
else
{
	$searchfirsttitles=$ftnr['ftname'];
}

//formhash
$efhr=heformhash_getr('DelNews_all');
$efhr1=heformhash_getr('CheckNews_all');
$efhr2=heformhash_getr('NoCheckNews_all');
$efhr3=heformhash_getr('ReSingleInfo');
$efhr4=heformhash_getr('GoodInfo_all');
$efhr5=heformhash_getr('TopNews_all');
$efhr6=heformhash_getr('EditMoreInfoTime');
$efhr7=heformhash_getr('MoveNews_all');
$efhr8=heformhash_getr('CopyNews_all');
if($efhr['vname']!=$efhr1['vname'])
{
	$efhr['vform'].=$efhr1['vform'].$efhr2['vform'].$efhr3['vform'].$efhr4['vform'].$efhr5['vform'].$efhr6['vform'].$efhr7['vform'].$efhr8['vform'];
}
$efh=heformhash_get('ReIndex',1);
$efh1=heformhash_get('ReAllNewsJs',1);
$efh2=heformhash_get('ReSingleInfo',1);
$efh3=heformhash_get('DelNews',1);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" type="text/css">
<title>管理信息</title>
<script>
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }

function GetSelectId(form)
{
  var ids='';
  var dh='';
  for (var i=0;i<form.elements.length;i++)
  {
	var e = form.elements[i];
	if (e.name == 'id[]')
	{
	   if(e.checked==true)
	   {
       		ids+=dh+e.value;
			dh=',';
	   }
	}
  }
  return ids;
}

function PushInfoToSp(form)
{
	var id='';
	id=GetSelectId(form);
	if(id=='')
	{
		alert('请选择要推送的信息');
		return false;
	}
	window.open('sp/PushToSp.php?<?=$ecms_hashur['ehref']?>&tid=<?=$tid?>&id='+id,'PushToSp','width=360,height=500,scrollbars=yes,left=300,top=150,resizable=yes');
}

function PushInfoToZt(form)
{
	var id='';
	id=GetSelectId(form);
	if(id=='')
	{
		alert('请选择要推送的信息');
		return false;
	}
	window.open('special/PushToZt.php?<?=$ecms_hashur['ehref']?>&tid=<?=$tid?>&id='+id,'PushToZt','width=360,height=500,scrollbars=yes,left=300,top=150,resizable=yes');
}


function eonsubmitform()
{
	document.listform.to_classid.value=document.getElementById('esnav_to_classid').value;
	return confirm('确认要执行此操作？');
}

</script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
<form name="AddNewsForm" id="AddNewsForm" method="get">
  <tr> 
    <td width="24%" height="32">位置： 
      <?=$url?>    </td>
    <td width="76%"><div align="right" class="emenubutton">
		  <span id="showaddclassnav"></span>
          <input type="button" name="Submit" value="增加信息" onClick="if(document.getElementById('esnav_addclassid').value!=0){window.open('AddNews.php?<?=$ecms_hashur['ehref']?>&enews=AddNews<?=$addecmscheck?>&classid='+document.getElementById('esnav_addclassid').value,'','');}else{alert('请选择要增加信息的栏目');document.getElementById('esnav_addclassid').focus();}">
		  &nbsp; 
          <input type="button" name="Submit4" value="刷新首页" onClick="self.location.href='ecmschtml.php?enews=ReIndex<?=$ecms_hashur['href'].$efh?>'">
          &nbsp; 
          <input type="button" name="Submit4" value="刷新所有信息JS" onClick="window.open('ecmschtml.php?enews=ReAllNewsJs&from=<?=$phpmyself?><?=$ecms_hashur['href'].$efh1?>','','');">
        </div></td>
  </tr>
</form>
</table>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="SearchForm" id="SearchForm" method="GET" action="ListAllInfo.php" onSubmit="document.SearchForm.classid.value=document.getElementById('esnav_classid').value;">
  <?=$ecms_hashur['eform']?>
    <tr> 
      <td width="100%"> <div align="right">&nbsp;搜索： 
          <select name="showspecial" id="showspecial">
            <option value="0"<?=$showspecial==0?' selected':''?>>不限属性</option>
			<option value="1"<?=$showspecial==1?' selected':''?>>置顶</option>
            <option value="2"<?=$showspecial==2?' selected':''?>>推荐</option>
            <option value="3"<?=$showspecial==3?' selected':''?>>头条</option>
			<option value="7"<?=$showspecial==7?' selected':''?>>投稿</option>
            <option value="5"<?=$showspecial==5?' selected':''?>>签发</option>
			<option value="8"<?=$showspecial==8?' selected':''?>>我的信息</option>
          </select>
		  <select name="showisgood" id="showisgood">
            <option value="0"<?=$showisgood==0?' selected':''?>>不限推荐</option>
	    	<option value="-1"<?=$showisgood==-1?' selected':''?>>所有推荐</option>
	    	<?=$searchisgoods?>
          </select>
          <select name="showfirsttitle" id="showfirsttitle">
            <option value="0"<?=$showfirsttitle==0?' selected':''?>>不限头条</option>
	    	<option value="-1"<?=$showfirsttitle==-1?' selected':''?>>所有头条</option>
	    	<?=$searchfirsttitles?>
          </select>
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
          <select name="show">
            <option value="0"<?=$show==0?' selected':''?>>不限字段</option>
            <option value="1"<?=$show==1?' selected':''?>>标题</option>
            <option value="2"<?=$show==2?' selected':''?>>发布者</option>
			<option value="3"<?=$show==3?' selected':''?>>ID</option>
			<option value="4"<?=$show==4?' selected':''?>>关键字</option>
          </select>
		  <?=$stts?>
		  <span id="searchclassnav"></span>
          <select name="myorder" id="myorder">
            <option value="1"<?=$myorder==1?' selected':''?>>按信息ID</option>
            <option value="2"<?=$myorder==2?' selected':''?>>按发布时间</option>
            <option value="3"<?=$myorder==3?' selected':''?>>按点击率</option>
            <option value="4"<?=$myorder==4?' selected':''?>>按下载数</option>
            <option value="5"<?=$myorder==5?' selected':''?>>按评论数</option>
          </select>
          <select name="orderby" id="orderby">
            <option value="0"<?=$orderby==0?' selected':''?>>降序排序</option>
            <option value="1"<?=$orderby==1?' selected':''?>>升序排序</option>
          </select>
          <select name="infolday" id="infolday">
            <option value="1"<?=$infolday==1?' selected':''?>>全部时间</option>
            <option value="86400"<?=$infolday==86400?' selected':''?>>1 天</option>
            <option value="172800"<?=$infolday==172800?' selected':''?>>2 天</option>
            <option value="604800"<?=$infolday==604800?' selected':''?>>一周</option>
            <option value="2592000"<?=$infolday==2592000?' selected':''?>>1 个月</option>
            <option value="7948800"<?=$infolday==7948800?' selected':''?>>3 个月</option>
            <option value="15897600"<?=$infolday==15897600?' selected':''?>>6 
            个月</option>
            <option value="31536000"<?=$infolday==31536000?' selected':''?>>1 
            年</option>
          </select>
          <input type="submit" name="Submit2" value="搜索">
          <input name="tbname" type="hidden" value="<?=$tbname?>">
          <input name="ecmscheck" type="hidden" id="ecmscheck" value="<?=$ecmscheck?>">
          <input name="sear" type="hidden" value="1">
		  <input type="hidden" name="classid" id="classid" value="0">
        </div></td>
    </tr>
  </form>
</table>
<br>
<form name="listform" id="listform" method="post" action="ecmsinfo.php" onSubmit="return eonsubmitform();">
<?=$ecms_hashur['form']?>
<?=$efhr['vform']?>
  <input type=hidden name=enews value=DelNews_all>
  <input name=mid type=hidden id="mid" value=<?=$mid?>>
  <input type=hidden name=doing value=0>
  <input name="ecmscheck" type="hidden" id="ecmscheck" value="<?=$ecmscheck?>">
  <input type="hidden" name="to_classid" id="to_classid" value="0">
  <table width="100%" border="0" cellspacing="1" cellpadding="0">
    <tr>
      <td width="10%" height="25"<?=$indexchecked==1?' class="header"':' bgcolor="#C9F1FF"'?>><div align="center"><a href="ListAllInfo.php?tbname=<?=$tbname?><?=$ecms_hashur['ehref']?>" title="已发布信息总数：<?=$tbinfos?>">已发布 (<?=$tbinfos?>)</a></div></td>
      <td width="10%"<?=$indexchecked==0?' class="header"':' bgcolor="#C9F1FF"'?> title="待审核信息总数：<?=$tbckinfos?>"><div align="center"><a href="ListAllInfo.php?tbname=<?=$tbname?>&ecmscheck=1<?=$ecms_hashur['ehref']?>">待审核 (<?=$tbckinfos?>)</a></div></td>
      <td width="10%">&nbsp;</td>
      <td width="58%">&nbsp;</td>
      <td width="6%">&nbsp;</td>
      <td width="6%">&nbsp;</td>
    </tr>
  </table>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25" colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="25%"><select name="tbname" onChange="if(this.options[this.selectedIndex].value!=0){self.location.href='ListAllInfo.php?<?=str_replace('&tbname=','&',$search1)?>&tbname='+this.options[this.selectedIndex].value;}">
                <?=$changetbs?>
              </select> </td>
            <td width="75%"> <div align="right"><font color="#ffffff"><a href="ListAllInfo.php?tbname=<?=$tbname?><?=$addecmscheck?>&sear=1&showspecial=8<?=$ecms_hashur['ehref']?>">我的信息</a> 
                | <a href="ListAllInfo.php?tbname=<?=$tbname?><?=$addecmscheck?>&sear=1&showspecial=5<?=$ecms_hashur['ehref']?>">签发信息</a> 
                | <a href="ListAllInfo.php?tbname=<?=$tbname?><?=$addecmscheck?>&sear=1&showspecial=7<?=$ecms_hashur['ehref']?>">投稿信息</a> 
				<?php
				if($openshowretitle==1)
				{
				?>
                | <a href="ListAllInfo.php?tbname=<?=$tbname?><?=$addecmscheck?>&showretitle=1&srt=1<?=$ecms_hashur['ehref']?>" title="查询重复标题，并保留一条信息">查询重复标题A</a> 
                | <a href="ListAllInfo.php?tbname=<?=$tbname?><?=$addecmscheck?>&showretitle=1&srt=0<?=$ecms_hashur['ehref']?>" title="查询重复标题的信息(不保留信息)">查询重复标题B</a> 
				<?php
				}
				?>
                | <a href="ReHtml/ChangeData.php<?=$ecms_hashur['whehref']?>" target=_blank>更新数据</a> | <a href="../../" target=_blank>预览首页</a></font></div></td>
          </tr>
        </table></td>
    </tr>
	</table>
	<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr> 
      <td width="3%"><div align="center"></div></td>
      <td width="6%" height="25"><div align="center">ID</div></td>
      <td width="36%" height="25"><div align="center">标题</div></td>
      <td width="12%" height="25"><div align="center">发布者</div></td>
      <td width="16%" height="25"> <div align="center">发布时间</div></td>
	  <td width="7%" height="25"><div align="center">点击</div></td>
      <td width="6%"><div align="center">评论</div></td>
      <td width="14%" height="25"> <div align="center">操作</div></td>
    </tr>
    <?php
	while($r=$empire->fetch($sql))
	{
		//状态
		$st='';
		if($r['istop'])//置顶
		{
			$st.="<font color=red>[顶".$r['istop']."]</font>";
		}
		if($r['isgood'])//推荐
		{
			$st.="<font color=red>[".$ignamer[$r['isgood']]."]</font>";
		}
		if($r['firsttitle'])//头条
		{
			$st.="<font color=red>[".$ftnamer[$r['firsttitle']]."]</font>";
		}
		$oldtitle=$r['title'];
		$r['title']=stripSlashes(sub($r['title'],0,36,false));
		//时间
		$truetime=date("Y-m-d H:i:s",$r['truetime']);
		$lastdotime=date("Y-m-d H:i:s",$r['lastdotime']);
		//审核
		if(empty($indexchecked))
		{
			$checked=" title='未审核' style='background:#99C4E3'";
			$titleurl="ShowInfo.php?classid=".$r['classid']."&id=".$r['id'].$addecmscheck.$ecms_hashur['ehref'];
			$eagotourl=$r['ismember']?$titleurl:eapage_hGetGotoUrl($titleurl,'',$r['classid'],$r['id'],'eaShowInfo',0);
		}
		else
		{
			$checked="";
			$titleurl=sys_ReturnBqTitleLink($r);
			$eagotourl=eapage_hGetGotoUrl($titleurl,'',$r['classid'],$r['id'],'eaShowInfoUrl',0);
		}
		$eagotourl_onclick='';
		if($eagotourl!=$titleurl)
		{
			$eagotourl_onclick=' onclick="window.open(\''.$eagotourl.'\');return false;"';
		}
		//会员投稿
		if($r['ismember'])
		{
			$r['username']="<a href='member/AddMember.php?enews=EditMember&userid=".$r['userid'].$ecms_hashur['ehref']."' target='_blank'><font color=red>".$r['username']."</font></a>";
		}
		//取得类别名
		$do=$r['classid'];
		$dob=$class_r[$r['classid']]['bclassid'];
		//签发
		$qf="";
		if($r['isqf'])
		{
			$qfr=$empire->fetch1("select checktno,tstatus from {$dbtbpre}enewswfinfo where id='".$r['id']."' and classid='".$r['classid']."'".do_dblimit_one());
			if($qfr['checktno']=='100')
			{
				$qf="(<font color='red'>已通过</font>)";
			}
			elseif($qfr['checktno']=='101')
			{
				$qf="(<font color='red'>返工</font>)";
			}
			elseif($qfr['checktno']=='102')
			{
				$qf="(<font color='red'>已否决</font>)";
			}
			else
			{
				$qf="(<font color='red'>".$qfr['tstatus']."</font>)";
			}
			$qf="<a href='#ecms' onclick=\"window.open('workflow/DoWfInfo.php?classid=".$r['classid']."&id=".$r['id'].$addecmscheck.$ecms_hashur['ehref']."','','width=600,height=520,scrollbars=yes');\">".$qf."</a>";
		}
		//标题图片
		$showtitlepic="";
		if($r['titlepic'])
		{
			$showtitlepic="<a href='".$r['titlepic']."' title='预览标题图片' target=_blank><img src='../../e/data/images/showimg.gif' border=0></a>";
		}
		//未生成
		$myid="<a href='ecmschtml.php?enews=ReSingleInfo&classid=".$r['classid']."&id[]=".$r['id'].$ecms_hashur['href'].$efh2."'>".$r['id']."</a>";
		if(empty($r['havehtml']))
		{
			$myid="<a href='ecmschtml.php?enews=ReSingleInfo&classid=".$r['classid']."&id[]=".$r['id'].$ecms_hashur['href'].$efh2."' title='未生成'><b>".$r['id']."</b></a>";
		}
		$liurl_class='ListNews.php?bclassid='.$class_r[$r['classid']]['bclassid'].'&classid='.$r['classid'].$addecmscheck.$ecms_hashur['ehref'];
		$liurl_pl='pl/ListPl.php?id='.$r['id'].'&classid='.$r['classid'].'&bclassid='.$class_r[$r['classid']]['bclassid'].$addecmscheck.$ecms_hashur['ehref'];
		$liurl_edit='AddNews.php?enews=EditNews&id='.$r['id'].'&classid='.$r['classid'].'&bclassid='.$class_r[$r['classid']]['bclassid'].$addecmscheck.$ecms_hashur['ehref'];
		$liurl_edits='info/EditInfoSimple.php?enews=EditNews&id='.$r['id'].'&classid='.$r['classid'].'&bclassid='.$class_r[$r['classid']]['bclassid'].$addecmscheck.$ecms_hashur['ehref'];
		$liurl_del='ecmsinfo.php?enews=DelNews&id='.$r['id'].'&classid='.$r['classid'].'&bclassid='.$class_r[$r['classid']]['bclassid'].$addecmscheck.$ecms_hashur['href'].$efh3;
		//父子信息
		if($r['efz'])
		{
			$liurl_fzhref='<a href="fzinfo/ListFzData.php?fzid='.$r['id'].'&fzclassid='.$r['classid'].$ecms_hashur['ehref'].'" target=_blank title="管理子信息">父</a>';
		}
		else
		{
			$liurl_fzhref='<a href="fzinfo/AddFzinfo.php?enews=AddFzinfo&id='.$r['id'].'&classid='.$r['classid'].$ecms_hashur['ehref'].'" target=_blank title="加入父信息">--</a>';
		}
	?>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td><div align="center"> 
          <input name="id[]" type="checkbox" id="id[]" value="<?=$r['id']?>"<?=$checked?>>
		  <input name="infoid[]" type="hidden" value="<?=$r['id']?>">
        </div></td>
      <td height="42"> <div align="center"> 
          <?=$myid?>
        </div></td>
      <td height="25"> <div align="left"> 
          <?=$st?>
          <?=$showtitlepic?>
          <a href="<?=$titleurl?>"<?=$eagotourl_onclick?> target=_blank title="<?=$oldtitle?>">
          <?=$r['title']?>
          </a> 
          <?=$qf?>
          <br>
          <font color="#574D5C">栏目:<a href='<?=$liurl_class?>'> 
          <font color="#574D5C">
          <?=$class_r[$dob]['classname']?>
          </font> </a> > <a href='<?=$liurl_class?>'> 
          <font color="#574D5C">
          <?=$class_r[$r['classid']]['classname']?>
          </font> </a></font></div></td>
      <td height="25"<?=$r['eckuid']?' title="审核人UID：'.$r['eckuid'].'"':''?>> <div align="center"> 
          <?=$r['username']?>
        </div></td>
      <td height="25" title="<?php echo"增加时间：".$truetime."\r\n最后修改：".$lastdotime;?>"> <div align="center">
          <input name="newstime[]" type="text" value="<?=date("Y-m-d H:i:s",$r['newstime'])?>" size="20">
        </div></td>
      <td height="25"> <div align="center"><a title="下载次数:<?=$r['totaldown']?>"> 
          <?=$r['onclick']?>
          </a></div></td>
      <td><div align="center"><a href="<?=$liurl_pl?>" target="_blank" title="管理评论"><u><?=$r['plnum']?></u></a></div></td>
      <td height="25"> <div align="center"><a href="<?=$liurl_edit?>" target="_blank">修改</a> | <a href="#empirecms" onClick="window.open('<?=$liurl_edits?>','EditInfoSimple','width=600,height=360,scrollbars=yes,resizable=yes');">简改</a> | <?=$liurl_fzhref?> | <a href="<?=$liurl_del?>" onClick="return confirm('确认要删除？');">删除</a> 
        </div></td>
    </tr>
    <?php
	}
	?>
	</table>
	<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <input type=hidden name=classid value=<?=$do?>>
    <input type=hidden name=bclassid value=<?=$dob?>>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" width="3%"> <div align="center"> 
          <input type=checkbox name=chkall value=on onclick=CheckAll(this.form)>
        </div></td>
      <td height="25" colspan="7"><div align="right"> 
          <input type="submit" name="Submit3" value="删除" onClick="document.listform.enews.value='DelNews_all';document.listform.action='ecmsinfo.php';document.listform.<?=$efhr['vname']?>.value='<?=$efhr['vval']?>';">
		  <?php
		  if($ecmscheck)
		  {
		  ?>
		  <input type="submit" name="Submit8" value="审核" onClick="document.listform.enews.value='CheckNews_all';document.listform.action='ecmsinfo.php';document.listform.<?=$efhr1['vname']?>.value='<?=$efhr1['vval']?>';">
		  <?php
		  }
		  else
		  {
		  ?>
		  <input type="submit" name="Submit9" value="取消审核" onClick="document.listform.enews.value='NoCheckNews_all';document.listform.action='ecmsinfo.php';document.listform.<?=$efhr2['vname']?>.value='<?=$efhr2['vval']?>';">
          <input type="submit" name="Submit8" value="刷新" onClick="document.listform.enews.value='ReSingleInfo';document.listform.action='ecmschtml.php';document.listform.<?=$efhr3['vname']?>.value='<?=$efhr3['vval']?>';">
          <input type="button" name="Submit112" value="推送" onClick="PushInfoToSp(this.form);">
		  <?php
		  }
		  ?> 
          <select name="isgood" id="isgood">
            <option value="0">不推荐</option>
			<?=$ftnr['igname']?>
          </select>
          <input type="submit" name="Submit82" value="推荐" onClick="document.listform.enews.value='GoodInfo_all';document.listform.doing.value='0';document.listform.action='ecmsinfo.php';document.listform.<?=$efhr4['vname']?>.value='<?=$efhr4['vval']?>';">
          <select name="firsttitle" id="firsttitle">
            <option value="0">取消头条</option>
			<?=$ftnr['ftname']?>
          </select>
          <input type="submit" name="Submit823" value="头条" onClick="document.listform.enews.value='GoodInfo_all';document.listform.doing.value='1';document.listform.action='ecmsinfo.php';document.listform.<?=$efhr4['vname']?>.value='<?=$efhr4['vval']?>';">
          <select name="istop" id="istop">
            <option value="0">不置顶</option>
            <option value="1">一级置顶</option>
            <option value="2">二级置顶</option>
            <option value="3">三级置顶</option>
            <option value="4">四级置顶</option>
            <option value="5">五级置顶</option>
            <option value="6">六级置顶</option>
            <option value="7">七级置顶</option>
            <option value="8">八级置顶</option>
			<option value="9">九级置顶</option>
          </select>
          <input type="submit" name="Submit7" value="置顶" onClick="document.listform.enews.value='TopNews_all';document.listform.action='ecmsinfo.php';document.listform.<?=$efhr5['vname']?>.value='<?=$efhr5['vval']?>';">
		  <input type="submit" name="Submit7" value="修改时间" onClick="document.listform.enews.value='EditMoreInfoTime';document.listform.action='ecmsinfo.php';document.listform.<?=$efhr6['vname']?>.value='<?=$efhr6['vval']?>';">
		  <input type="button" name="Submit1122" value="推送至专题" onClick="PushInfoToZt(this.form);">
          <span id="moveclassnav"></span> 
          <input type="submit" name="Submit5" value="移动" onClick="document.listform.enews.value='MoveNews_all';document.listform.action='ecmsinfo.php';document.listform.<?=$efhr7['vname']?>.value='<?=$efhr7['vval']?>';">
          <input type="submit" name="Submit6" value="复制" onClick="document.listform.enews.value='CopyNews_all';document.listform.action='ecmsinfo.php';document.listform.<?=$efhr8['vname']?>.value='<?=$efhr8['vval']?>';">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="8"> 
        <?=$returnpage?>
      　 </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="8"> <font color="#666666">备注：多选框蓝色为未审核信息；发布者红色为会员投稿；信息ID粗体为未生成,点击ID可刷新页面.</font></td>
    </tr>
  </table>
</form>
<br>
<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="ShowClassNav.php?ecms=2&classid=<?=$classid?><?=$ecms_hashur['ehref']?>" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
</body>
</html>