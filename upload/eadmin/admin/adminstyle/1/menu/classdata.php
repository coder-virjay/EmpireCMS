<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>菜单</title>
<link href="../../../../e/data/menu/menu.css" rel="stylesheet" type="text/css">
<script src="../../../../e/data/menu/menu.js" type="text/javascript"></script>
<SCRIPT lanuage="JScript">
function tourl(url){
	parent.main.location.href=url;
}
</SCRIPT>
</head>
<body onLoad="initialize()">
<table border='0' cellspacing='0' cellpadding='0'>
	<tr height=20>
			<td id="home"><img src="../../../../e/data/images/homepage.gif" border=0></td>
			<td><b>栏目管理</b></td>
	</tr>
</table>

<table border='0' cellspacing='0' cellpadding='0'>
  <tr> 
    <td id="prcinfo" class="menu1" onClick="chengstate('cinfo')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">信息相关管理</a>
	</td>
  </tr>
  <tr id="itemcinfo" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
          <td class="file">
			<a href="../../ListAllInfo.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理信息</a>
          </td>
        </tr>
        <tr> 
          <td class="file">
			<a href="../../ListAllInfo.php?ecmscheck=1<?=$ecms_hashur['ehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">审核信息</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../workflow/ListWfInfo.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">签发信息</a>
          </td>
        </tr>
		<?php
		if($r['dopl'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../openpage/AdminPage.php?efileid=3<?=$ecms_hashur['ehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理评论</a>
          </td>
        </tr>
		<?php
		}
		?>
		<tr> 
          <td class="file">
			<a href="../../sp/UpdgxSp.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">更新碎片</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../special/UpdgxZt.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">更新专题</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../info/InfoMain.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">数据统计</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../infotop.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">排行统计</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>

<?php
if($r['doclass']||$r['dosetmclass']||$r['doclassf'])
{
?>
  <tr> 
    <td id="prclassdata" class="menu1" onClick="chengstate('classdata')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">栏目管理</a>
	</td>
  </tr>
  <tr id="itemclassdata" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
		<?php
		if($r['doclass'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../ListClass.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理栏目</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../ListPageClass.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理栏目(分页)</a>
          </td>
        </tr>
		<?php
		}
		if($r['doclassf'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../info/ListClassF.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">栏目自定义字段</a>
          </td>
        </tr>
		<?php
		}
		if($r['dosetmclass'])
		{
		?>
		<tr> 
          <td class="file1">
			<a href="../../SetMoreClass.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">批量设置栏目属性</a>
          </td>
        </tr>
		<?php
		}
		?>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['dozt']||$r['doztf'])
{
?>
  <tr> 
    <td id="przt" class="menu1" onClick="chengstate('zt')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">专题管理</a>
	</td>
  </tr>
  <tr id="itemzt" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
		<?php
		if($r['dozt'])
		{
		?>
        <tr> 
          <td class="file">
			<a href="../../special/ListZtClass.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理专题分类</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../special/ListZt.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理专题</a>
          </td>
        </tr>
		<?php
		}
		if($r['doztf'])
		{
		?>
		<tr> 
          <td class="file1">
			<a href="../../special/ListZtF.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">专题自定义字段</a>
          </td>
        </tr>
		<?php
		}
		?>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['doinfotype'])
{
?>
  <tr> 
    <td id="prinfotype" class="menu1" onClick="chengstate('infotype')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">标题分类管理</a>
	</td>
  </tr>
  <tr id="iteminfotype" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
          <td class="file">
			<a href="../../info/AddInfoType.php?enews=AddInfoType<?=$ecms_hashur['ehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">增加标题分类</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../info/InfoType.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理标题分类</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['dofzinfo']||$r['dofztable']||$r['dofzinfocl'])
{
?>
  <tr> 
    <td id="prfz" class="menu1" onClick="chengstate('fz')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">父子信息管理</a>
	</td>
  </tr>
  <tr id="itemfz" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
		<?php
		if($r['dofzinfo'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../fzinfo/ListFzinfo.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理父信息</a>
          </td>
        </tr>
        <tr> 
          <td class="file">
			<a href="../../fzinfo/FzinfoClass.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理父信息分类</a>
          </td>
        </tr>
		<?php
		}
		if($r['dofzinfocl'])
		{
		?>
		<tr> 
          <td class="file1">
			<a href="../../fzinfo/ClearFzinfo.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">整理父子信息</a>
          </td>
        </tr>
		<?php
		}
		?>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['dosp'])
{
?>
  <tr> 
    <td id="prsp" class="menu1" onClick="chengstate('sp')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">碎片管理</a>
	</td>
  </tr>
  <tr id="itemsp" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
          <td class="file">
			<a href="../../sp/ListSpClass.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理碎片分类</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../sp/ListSp.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理碎片</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['douserpage'])
{
?>
  <tr> 
    <td id="pruserpage" class="menu1" onClick="chengstate('userpage')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">自定义页面</a>
	</td>
  </tr>
  <tr id="itemuserpage" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
          <td class="file">
			<a href="../../template/PageClass.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理自定义页面分类</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../template/AddPage.php?enews=AddUserpage<?=$ecms_hashur['ehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">增加自定义页面</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../template/ListPage.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理自定义页面</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['douserlist'])
{
?>
  <tr> 
    <td id="pruserlist" class="menu1" onClick="chengstate('userlist')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">自定义列表</a>
	</td>
  </tr>
  <tr id="itemuserlist" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
		<tr> 
          <td class="file">
			<a href="../../other/UserlistClass.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理自定义列表分类</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../other/AddUserlist.php?enews=AddUserlist<?=$ecms_hashur['ehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">增加自定义列表</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../other/ListUserlist.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理自定义列表</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['douserjs'])
{
?>
  <tr> 
    <td id="pruserjs" class="menu1" onClick="chengstate('userjs')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">自定义JS</a>
	</td>
  </tr>
  <tr id="itemuserjs" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
		<tr> 
          <td class="file">
			<a href="../../other/UserjsClass.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理自定义JS分类</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../other/AddUserjs.php?enews=AddUserjs<?=$ecms_hashur['ehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">增加自定义JS</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../other/ListUserjs.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理自定义JS</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['dotags'])
{
?>
  <tr> 
    <td id="prtags" class="menu1" onClick="chengstate('tags')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">TAGS管理</a>
	</td>
  </tr>
  <tr id="itemtags" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
          <td class="file">
			<a href="../../tags/SetTags.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">设置TAGS参数</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../tags/TagsClass.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理TAGS分类</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../tags/ListTags.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理TAGS</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['doclass'])
{
?>
  <tr> 
    <td id="prgoodtype" class="menu1" onClick="chengstate('goodtype')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">头条/推荐级别</a>
	</td>
  </tr>
  <tr id="itemgoodtype" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
          <td class="file">
			<a href="../../info/ListGoodType.php?ttype=1<?=$ecms_hashur['ehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理头条级别</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../info/ListGoodType.php?ttype=0<?=$ecms_hashur['ehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理推荐级别</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['dofile'])
{
?>
  <tr> 
    <td id="prfile" class="menu1" onClick="chengstate('file')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">附件管理</a>
	</td>
  </tr>
  <tr id="itemfile" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
		<tr> 
          <td class="file1">
			<a href="../../openpage/AdminPage.php?efileid=4<?=$ecms_hashur['ehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理附件</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['docj'])
{
?>
  <tr> 
    <td id="prcj" class="menu1" onClick="chengstate('cj')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">采集管理</a>
	</td>
  </tr>
  <tr id="itemcj" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
		<tr> 
          <td class="file">
			<a href="../../AddInfoC.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">增加采集节点</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../ListInfoClass.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理采集节点</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../ListPageInfoClass.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理采集节点(分页)</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['dosearchall'])
{
?>
  <tr> 
    <td id="prsearchall" class="menu1" onClick="chengstate('searchall')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">全站全文搜索</a>
	</td>
  </tr>
  <tr id="itemsearchall" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
		<tr> 
          <td class="file">
			<a href="../../searchall/SetSearchAll.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">全站搜索设置</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../searchall/ListSearchLoadTb.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理搜索数据源</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../searchall/ClearSearchAll.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">清理搜索数据</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['dowap'])
{
?>
  <tr> 
    <td id="prwap" class="menu1" onClick="chengstate('wap')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">WAP管理</a>
	</td>
  </tr>
  <tr id="itemwap" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
          <td class="file">
			<a href="../../other/SetWap.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">WAP设置</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../other/WapStyle.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理WAP模板</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['domovenews']||$r['doinfodoc']||$r['dodelinfodata']||$r['dorepnewstext']||$r['dototaldata']||$r['dosearchkey']||$r['dovotemod'])
{
?>
  <tr> 
    <td id="prcother" class="menu3" onClick="chengstate('cother')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">其他相关</a>
	</td>
  </tr>
  <tr id="itemcother" style="display:none"> 
    <td class="list1">
		<table border='0' cellspacing='0' cellpadding='0'>
		<?php
		if($r['dototaldata'])
		{
		?>
        <tr> 
          <td class="file">
			<a href="../../TotalData.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">统计信息数据</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../user/UserTotal.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">用户发布统计</a>
          </td>
        </tr>
		<?php
		}
		if($r['dosearchkey'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../SearchKey.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理搜索关键字</a>
          </td>
        </tr>
		<?php
		}
		if($r['dosearchurl'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../other/ListSearchUrl.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理搜索转发</a>
          </td>
        </tr>
		<?php
		}
		if($r['dorepnewstext'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../db/RepNewstext.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">批量替换字段值</a>
          </td>
        </tr>
		<?php
		}
		if($r['domovenews'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../MoveClassNews.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">批量转移信息</a>
          </td>
        </tr>
		<?php
		}
		if($r['doinfodoc'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../InfoDoc.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">信息批量归档</a>
          </td>
        </tr>
		<?php
		}
		if($r['dodelinfodata'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../db/DelData.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">批量删除信息</a>
          </td>
        </tr>
		<?php
		}
		if($r['dovotemod'])
		{
		?>
		<tr> 
          <td class="file1">
			<a href="../../other/ListVoteMod.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理预设投票</a>
          </td>
        </tr>
		<?php
		}
		?>
      </table>
	</td>
  </tr>
<?php
}
?>
</table>
<br>
</body>
</html>