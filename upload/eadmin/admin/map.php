<?php
define('EmpireCMSAdmin','1');
require("../../e/class/connect.php");
require("../../e/class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
//验证用户
$lur=is_login();
$logininid=(int)$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=(int)$lur['groupid'];
$loginadminstyleid=(int)$lur['adminstyleid'];
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>后台地图</title>
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function GoToUrl(url,totarget){
	if(totarget=='')
	{
		totarget='main';
	}
	opener.document.getElementById(totarget).src=url;
	window.close();
}
</script>
</head>

<body leftmargin="0" topmargin="0">
<table width="100%" height="100%" border="0" cellspacing="1" cellpadding="3" class="tableborder">
  <tr class="header">
    <td width="9%" height="25">系统设置</td>
    <td width="6%">信息管理</td>
    <td width="21%">栏目管理</td>
    <td width="34%">模板管理</td>
    <td width="9%">用户面板</td>
    <td width="11%">插件管理</td>
    <td width="10%">其他管理</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#EBF3FC'"> 
      <table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><strong>系统设置</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('SetEnews.php<?=$ecms_hashur['whehref']?>','');">系统参数设置</a></td>
        </tr>
		<tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('pub/SetMod.php<?=$ecms_hashur['whehref']?>','');">模型参数设置</a></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('pub/SetEcmsMore.php<?=$ecms_hashur['whehref']?>','');">更多系统参数设置</a></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('pub/SetRewrite.php<?=$ecms_hashur['whehref']?>','');">伪静态参数设置</a></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('pub/SetPageCache.php<?=$ecms_hashur['whehref']?>','');">动态页缓存设置</a></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" title="Not for free version.">页面同步设置</a></td>
        </tr>
		<tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('pub/SetDigg.php<?=$ecms_hashur['whehref']?>','');">DIGG顶设置</a></td>
        </tr>
		<tr>
		  <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('pub/SetPhm.php<?=$ecms_hashur['whehref']?>','');">手机短信设置</a></td>
	    </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('pub/ListPubVar.php<?=$ecms_hashur['whehref']?>','');">扩展变量</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('pub/SetSafe.php<?=$ecms_hashur['whehref']?>','');">安全参数配置</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('pub/SetFirewall.php<?=$ecms_hashur['whehref']?>','');">网站防火墙</a></td>
        </tr>
        <tr> 
          <td><strong>数据更新</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('ReHtml/ChangeData.php<?=$ecms_hashur['whehref']?>','');">数据更新中心</a></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('ReHtml/ReInfoUrl.php<?=$ecms_hashur['whehref']?>','');">更新信息页地址</a></td>
        </tr>
		<tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('ReHtml/ChangePageCache.php<?=$ecms_hashur['whehref']?>','');">更新动态页缓存</a></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('ReHtml/DoUpdgxData.php<?=$ecms_hashur['whehref']?>','');">数据整理</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('PostUrlData.php<?=$ecms_hashur['whehref']?>','');">远程发布</a></td>
        </tr>
        <tr> 
          <td><strong>数据表与模型</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('db/AddTable.php?enews=AddTable<?=$ecms_hashur['ehref']?>','');">新建数据表</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('db/ListTable.php<?=$ecms_hashur['whehref']?>','');">管理数据表</a></td>
        </tr>
        <tr> 
          <td><strong>计划任务</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('ListDo.php<?=$ecms_hashur['whehref']?>','');">管理刷新任务</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('other/ListTask.php<?=$ecms_hashur['whehref']?>','');">管理计划任务</a></td>
        </tr>
        <tr> 
          <td><strong>工作流</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('workflow/AddWf.php?enews=AddWorkflow<?=$ecms_hashur['ehref']?>','');">增加工作流</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('workflow/ListWf.php<?=$ecms_hashur['whehref']?>','');">管理工作流</a></td>
        </tr>
        <tr> 
          <td><strong>优化方案</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('db/ListYh.php<?=$ecms_hashur['whehref']?>','');">管理优化方案</a></td>
        </tr>
		<tr> 
          <td><strong>网站多访问端</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('moreport/ListMoreport.php<?=$ecms_hashur['whehref']?>','');">管理网站访问端</a></td>
        </tr>
		<tr> 
          <td><strong>扩展菜单</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('other/MenuClass.php<?=$ecms_hashur['whehref']?>','');">管理菜单</a></td>
        </tr>
        <tr> 
          <td><strong>备份/恢复数据</strong></td>
        </tr>
		<?php
		if($ecms_config['db']['usedb']!='pgsql')
		{
		?>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('ebak/ChangeDb.php<?=$ecms_hashur['whehref']?>','');">备份数据</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('ebak/ReData.php<?=$ecms_hashur['whehref']?>','');">恢复数据</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('ebak/ChangePath.php<?=$ecms_hashur['whehref']?>','');">管理备份目录</a></td>
        </tr>
		<?php
		}
		?>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('db/DoSql.php<?=$ecms_hashur['whehref']?>','');">执行SQL语句</a></td>
        </tr>
      </table></td>
    <td valign="top" bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#EBF3FC'"><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><a href="#ecms" onClick="GoToUrl('AddInfoChClass.php<?=$ecms_hashur['whehref']?>','');">增加信息</a></td>
        </tr>
        <tr> 
          <td><a href="#ecms" onClick="GoToUrl('ListAllInfo.php<?=$ecms_hashur['whehref']?>','');">管理信息</a></td>
        </tr>
        <tr> 
          <td><a href="#ecms" onClick="GoToUrl('ListAllInfo.php?ecmscheck=1<?=$ecms_hashur['ehref']?>','');">审核信息</a></td>
        </tr>
        <tr> 
          <td><a href="#ecms" onClick="GoToUrl('workflow/ListWfInfo.php<?=$ecms_hashur['whehref']?>','');">签发信息</a></td>
        </tr>
        <tr>
          <td><a href="#ecms" onClick="GoToUrl('sp/UpdgxSp.php<?=$ecms_hashur['whehref']?>','');">更新碎片</a></td>
        </tr>
        <tr>
          <td><a href="#ecms" onClick="GoToUrl('special/UpdgxZt.php<?=$ecms_hashur['whehref']?>','');">更新专题</a></td>
        </tr>
        <tr> 
          <td><a href="#ecms" onClick="GoToUrl('openpage/AdminPage.php?efileid=3<?=$ecms_hashur['ehref']?>','');">管理评论</a></td>
        </tr>
        <tr>
          <td><a href="#ecms" onClick="GoToUrl('info/InfoMain.php<?=$ecms_hashur['whehref']?>','');">数据统计</a></td>
        </tr>
        <tr>
          <td><a href="#ecms" onClick="GoToUrl('infotop.php<?=$ecms_hashur['whehref']?>','');">排行统计</a></td>
        </tr>
      </table></td>
    <td valign="top" bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#EBF3FC'"><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td width="50%"><strong>栏目管理</strong></td>
          <td><strong>自定义页面</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('ListClass.php<?=$ecms_hashur['whehref']?>','');">管理栏目</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/PageClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理自定义页面分类</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('ListPageClass.php<?=$ecms_hashur['whehref']?>','');">管理栏目(分页)</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ListPage.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理自定义页面</a></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" title="Not for free version.">栏目访问排行</a></td>
          <td><strong>自定义列表</strong></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" title="Not for free version.">设置访问统计参数</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('other/UserlistClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理自定义列表分类 </a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('info/ListClassF.php<?=$ecms_hashur['whehref']?>','');">栏目自定义字段</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('other/ListUserlist.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理自定义列表</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('SetMoreClass.php<?=$ecms_hashur['whehref']?>','');">批量设置栏目属性</a></td>
          <td><strong>自定义JS</strong></td>
        </tr>
        <tr> 
          <td><strong>专题管理</strong></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('other/UserjsClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理自定义JS分类</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('special/ListZtClass.php<?=$ecms_hashur['whehref']?>','');">管理专题分类</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('other/ListUserjs.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理自定义JS</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('special/ListZt.php<?=$ecms_hashur['whehref']?>','');">管理专题</a></td>
          <td><strong>采集管理</strong></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('special/ListZtF.php<?=$ecms_hashur['whehref']?>','');">专题自定义字段 
            </a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('AddInfoC.php<?=$ecms_hashur['whehref']?>','');">增加采集节点</a></td>
        </tr>
        <tr>
          <td><strong>标题分类管理</strong></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('ListInfoClass.php<?=$ecms_hashur['whehref']?>','');">管理采集节点</a></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('info/InfoType.php<?=$ecms_hashur['whehref']?>','');">管理标题分类</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('ListPageInfoClass.php<?=$ecms_hashur['whehref']?>','');">管理采集节点(分页)</a></td>
        </tr>
        <tr>
          <td><strong>父子信息管理</strong></td>
          <td><strong>WAP管理</strong></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('fzinfo/ListFzinfo.php<?=$ecms_hashur['whehref']?>','');">管理父信息</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('other/SetWap.php<?=$ecms_hashur['whehref']?>','');">WAP设置</a></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('fzinfo/FzinfoClass.php<?=$ecms_hashur['whehref']?>','');">管理父信息分类</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('other/WapStyle.php<?=$ecms_hashur['whehref']?>','');">管理WAP模板</a></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" title="Not for free version.">管理子信息分表</a></td>
          <td><strong>其他管理</strong></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('fzinfo/ClearFzinfo.php<?=$ecms_hashur['whehref']?>','');">整理父子信息</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('TotalData.php<?=$ecms_hashur['whehref']?>','');">统计信息数据</a></td>
        </tr>
        <tr> 
          <td><strong>碎片管理</strong></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('user/UserTotal.php<?=$ecms_hashur['whehref']?>','');">用户发布统计</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('sp/ListSpClass.php<?=$ecms_hashur['whehref']?>','');">管理碎片分类</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('SearchKey.php<?=$ecms_hashur['whehref']?>','');">管理搜索关键字</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('sp/ListSp.php<?=$ecms_hashur['whehref']?>','');">管理碎片</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('other/ListSearchUrl.php<?=$ecms_hashur['whehref']?>','');">管理搜索转发</a></td>
        </tr>
        <tr> 
          <td><strong>TAGS管理</strong></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('db/RepNewstext.php<?=$ecms_hashur['whehref']?>','');">批量替换字段值</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tags/SetTags.php<?=$ecms_hashur['whehref']?>','');">设置TAGS参数</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('MoveClassNews.php<?=$ecms_hashur['whehref']?>','');">批量转移信息</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tags/TagsClass.php<?=$ecms_hashur['whehref']?>','');">管理TAGS分类</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('InfoDoc.php<?=$ecms_hashur['whehref']?>','');">信息批量归档</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tags/ListTags.php<?=$ecms_hashur['whehref']?>','');">管理TAGS</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('db/DelData.php<?=$ecms_hashur['whehref']?>','');">批量删除信息</a></td>
        </tr>
        <tr>
          <td><strong>头条/推荐级别</strong></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('other/ListVoteMod.php<?=$ecms_hashur['whehref']?>','');">管理预设投票</a></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('info/ListGoodType.php?ttype=1<?=$ecms_hashur['ehref']?>','');">管理头条级别</a></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('info/ListGoodType.php?ttype=0<?=$ecms_hashur['ehref']?>','');">管理推荐级别</a></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td><strong>附件管理</strong></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('openpage/AdminPage.php?efileid=4<?=$ecms_hashur['ehref']?>','');">管理附件</a></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td><strong>全站全文搜索</strong></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('searchall/SetSearchAll.php<?=$ecms_hashur['whehref']?>','');">全站搜索设置</a></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('searchall/ListSearchLoadTb.php<?=$ecms_hashur['whehref']?>','');">管理搜索数据源</a></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('searchall/ClearSearchAll.php<?=$ecms_hashur['whehref']?>','');">清理搜索数据</a></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    <td valign="top" bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#EBF3FC'"><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td width="32%"><a href="#ecms" onClick="window.open('template/EnewsBq.php<?=$ecms_hashur['whehref']?>','','width=600,height=600,scrollbars=yes,resizable=yes');window.close();"><strong>查看标签语法</strong></a></td>
          <td width="36%"><strong>公共模板</strong></td>
          <td width="32%"><strong>自定义页面模板</strong></td>
        </tr>
        <tr> 
          <td><a href="#ecms" onClick="window.open('template/MakeBq.php<?=$ecms_hashur['whehref']?>','','width=600,height=600,scrollbars=yes,resizable=yes');window.close();"><strong>自动生成标签</strong></a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=indextemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改首页模板</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/AddPagetemp.php?enews=AddPagetemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">增加自定义页面模板</a></td>
        </tr>
        <tr>
          <td><a href="#ecms" onClick="window.open('openpage/AdminPage.php?efileid=5<?=$ecms_hashur['ehref']?>','dttemppage','');window.close();"><strong>动态页面模板管理</strong></a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=cptemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改控制面板模板</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ListPagetemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理自定义页面模板</a></td>
        </tr>
        <tr> 
          <td><strong>栏目封面模板</strong></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=schalltemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改全站搜索模板</a></td>
          <td><strong>投票模板</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ClassTempClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理封面模板分类</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=searchformtemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改高级搜索表单模板</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/AddVotetemp.php?enews=AddVoteTemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">增加投票模板</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ListClasstemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理封面模板</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=searchformjs&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改横向搜索JS模板</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ListVotetemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理投票模板</a></td>
        </tr>
        <tr> 
          <td><strong>列表模板</strong></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=searchformjs1&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改纵向搜索JS模板</a></td>
          <td><strong>标签管理</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ListtempClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理列表模板分类</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=otherlinktemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改相关信息模板</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/BqClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理标签分类</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ListListtemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理列表模板</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=gbooktemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改留言板模板</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ListBq.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理标签</a></td>
        </tr>
        <tr> 
          <td><strong>内容模板</strong></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=pljstemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改评论JS调用模板</a></td>
          <td><strong>其他管理</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/NewstempClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理内容模板分类</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=downpagetemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改最终下载页模板</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/LoadTemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">批量导入栏目模板</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ListNewstemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理内容模板</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=downsofttemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改下载地址模板</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ChangeListTemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">批量更换列表模板</a></td>
        </tr>
        <tr> 
          <td><strong>标签模板</strong></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=onlinemovietemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改在线播放地址模板</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/RepTemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">批量替换模板字符</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/BqtempClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理标签模板分类</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=listpagetemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改列表分页模板</a></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ListBqtemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理标签模板</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=loginiframe&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改登录状态模板</a></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td><strong>公共模板变量</strong></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/EditPublicTemp.php?tname=loginjstemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">修改JS调用登录模板</a></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/TempvarClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理模板变量分类</a></td>
          <td><strong>打印模板</strong></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ListTempvar.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理模板变量</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/AddPrinttemp.php?enews=AddPrintTemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">增加打印模板</a></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td><strong>JS模板</strong></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ListPrinttemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理打印模板</a></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/JsTempClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理JS模板分类</a></td>
          <td><strong>搜索模板</strong></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ListJstemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理JS模板</a></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/SearchtempClass.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理搜索模板分类</a></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td><strong>评论列表模板</strong></td>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ListSearchtemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理搜索模板</a></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/AddPltemp.php?enews=AddPlTemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">增加评论模板</a></td>
          <td><a href="#ecms" onClick="GoToUrl('template/TempGroup.php<?=$ecms_hashur['whehref']?>','');"><strong>模板组管理</strong></a></td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/ListPltemp.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');">管理评论模板</a></td>
          <td><a href="#ecms" onClick="GoToUrl('template/EditTempid.php?gid=<?=$gid?><?=$ecms_hashur['ehref']?>','');"><strong>修改模板ID</strong></a></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    <td valign="top" bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#EBF3FC'"><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><strong>用户管理</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('user/EditPassword.php<?=$ecms_hashur['whehref']?>','');">修改个人资料</a></td>
        </tr>
		<tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('user/ListLgacUser.php<?=$ecms_hashur['whehref']?>','');">后台登录激活</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('user/ListGroup.php<?=$ecms_hashur['whehref']?>','');">管理用户组</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('user/UserClass.php<?=$ecms_hashur['whehref']?>','');">管理部门</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('user/ListUser.php<?=$ecms_hashur['whehref']?>','');">管理用户</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('user/ListLog.php<?=$ecms_hashur['whehref']?>','');">管理登录日志</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('user/ListDolog.php<?=$ecms_hashur['whehref']?>','');">管理操作日志</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('template/AdminStyle.php<?=$ecms_hashur['whehref']?>','');">管理后台风格</a></td>
        </tr>
        <tr> 
          <td><strong>会员管理</strong></td>
        </tr>
        <tr> 
          <td> &nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/ListMember.php<?=$ecms_hashur['whehref']?>','');">管理会员</a></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/ListMemberMore.php<?=$ecms_hashur['whehref']?>','');">管理会员(详细)</a></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/ClearMember.php<?=$ecms_hashur['whehref']?>','');">批量清理会员</a></td>
        </tr>
		<tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/ListMemberGroup.php<?=$ecms_hashur['whehref']?>','');">会员组</a></td>
        </tr>
		<tr>
		  <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/ListInGroup.php<?=$ecms_hashur['whehref']?>','');">会员内部组</a></td>
	    </tr>
		<tr>
		  <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/ListViewGroup.php<?=$ecms_hashur['whehref']?>','');">会员访问组</a></td>
	    </tr>
		<tr>
		  <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/ListMAdminGroup.php<?=$ecms_hashur['whehref']?>','');">会员管理组</a></td>
	    </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/ListMemberF.php<?=$ecms_hashur['whehref']?>','');">管理会员字段</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/ListMemberForm.php<?=$ecms_hashur['whehref']?>','');">管理会员表单</a></td>
        </tr>
        <tr> 
          <td><strong>会员空间管理</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/ListSpaceStyle.php<?=$ecms_hashur['whehref']?>','');">管理空间模板</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/MemberGbook.php<?=$ecms_hashur['whehref']?>','');">管理空间留言</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/MemberFeedback.php<?=$ecms_hashur['whehref']?>','');">管理空间反馈</a></td>
        </tr>
        <tr>
          <td><strong>外部接口</strong></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/MemberConnect.php<?=$ecms_hashur['whehref']?>','');">外部登录接口</a></td>
        </tr>
        <tr> 
          <td><strong>其他管理</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/ListBuyGroup.php<?=$ecms_hashur['whehref']?>','');">管理充值类型</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/ListCard.php<?=$ecms_hashur['whehref']?>','');">管理点卡</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/GetFen.php<?=$ecms_hashur['whehref']?>','');">批量赠送点数</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/SendEmail.php<?=$ecms_hashur['whehref']?>','');">批量发送邮件</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/SendMsg.php<?=$ecms_hashur['whehref']?>','');">批量发送短消息</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('member/DelMoreMsg.php<?=$ecms_hashur['whehref']?>','');">批量删除短消息</a></td>
        </tr>
      </table></td>
    <td valign="top" bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#EBF3FC'"><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><strong>API接口</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('eapi/ListDtUserpage.php<?=$ecms_hashur['whehref']?>','');">管理自定义动态页面</a></td>
        </tr>
        <tr> 
          <td><strong>广告系统</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tool/AdClass.php<?=$ecms_hashur['whehref']?>','');">管理广告分类</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tool/ListAd.php<?=$ecms_hashur['whehref']?>','');">管理广告</a></td>
        </tr>
        <tr> 
          <td><strong>投票系统</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tool/AddVote.php?enews=AddVote<?=$ecms_hashur['ehref']?>','');">增加投票</a></td>
        </tr>
        <tr> 
          <td> &nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tool/ListVote.php<?=$ecms_hashur['whehref']?>','');">管理投票</a></td>
        </tr>
        <tr> 
          <td><strong>友情链接管理</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tool/LinkClass.php<?=$ecms_hashur['whehref']?>','');">管理友情链接分类</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tool/ListLink.php<?=$ecms_hashur['whehref']?>','');">管理友情链接</a></td>
        </tr>
        <tr> 
          <td><strong>留言板管理</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tool/GbookClass.php<?=$ecms_hashur['whehref']?>','');">管理留言分类</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tool/gbook.php<?=$ecms_hashur['whehref']?>','');">管理留言</a></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tool/DelMoreGbook.php<?=$ecms_hashur['whehref']?>','');">批量删除留言</a></td>
        </tr>
        <tr> 
          <td><strong>信息反馈管理</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tool/FeedbackClass.php<?=$ecms_hashur['whehref']?>','');">管理反馈分类</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tool/ListFeedbackF.php<?=$ecms_hashur['whehref']?>','');">管理反馈字段</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('tool/feedback.php<?=$ecms_hashur['whehref']?>','');">管理信息反馈</a></td>
        </tr>
        <tr> 
          <td><a href="#ecms" onClick="GoToUrl('template/NotCj.php<?=$ecms_hashur['whehref']?>','');"><strong>管理防采集随机字符</strong></a></td>
        </tr>
      </table></td>
    <td valign="top" bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#EBF3FC'"><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><strong>新闻模型相关</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('NewsSys/BeFrom.php<?=$ecms_hashur['whehref']?>','');">管理信息来源</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('NewsSys/writer.php<?=$ecms_hashur['whehref']?>','');">管理作者</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('NewsSys/key.php<?=$ecms_hashur['whehref']?>','');">管理内容关键字</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('NewsSys/word.php<?=$ecms_hashur['whehref']?>','');">管理过滤字符</a></td>
        </tr>
        <tr> 
          <td><strong>下载模型相关</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('DownSys/url.php<?=$ecms_hashur['whehref']?>','');">管理地址前缀</a></td>
        </tr>
        <tr> 
          <td> &nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('DownSys/DelDownRecord.php<?=$ecms_hashur['whehref']?>','');">删除下载记录</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('DownSys/ListError.php<?=$ecms_hashur['whehref']?>','');">管理错误报告</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('DownSys/RepDownLevel.php<?=$ecms_hashur['whehref']?>','');">批量替换地址权限</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('DownSys/player.php<?=$ecms_hashur['whehref']?>','');">播放器管理</a></td>
        </tr>
        <tr> 
          <td><strong>商城模型相关</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="window.open('openpage/AdminPage.php?efileid=2<?=$ecms_hashur['ehref']?>','AdminShopSys','');window.close();">管理商城</a></td>
        </tr>
        <tr> 
          <td><strong>在线支付</strong></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('pay/SetPayFen.php<?=$ecms_hashur['whehref']?>','');">支付参数配置</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('pay/PayApi.php<?=$ecms_hashur['whehref']?>','');">管理支付接口</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('pay/ListPayRecord.php<?=$ecms_hashur['whehref']?>','');">管理支付记录</a></td>
        </tr>
        <tr> 
          <td><strong>图片信息管理</strong></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('NewsSys/PicClass.php<?=$ecms_hashur['whehref']?>','');">管理图片信息分类</a></td>
        </tr>
        <tr> 
          <td>&nbsp;&nbsp;<a href="#ecms" onClick="GoToUrl('NewsSys/ListPicNews.php<?=$ecms_hashur['whehref']?>','');">管理图片信息</a></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
<?php
db_close();
$empire=null;
?>