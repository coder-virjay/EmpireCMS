<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?><table width=100% align=center cellpadding=3 cellspacing=1 class=tableborder>
  <tr> 
    <td width=16% height=25 bgcolor=ffffff>作品名(*)</td>
    <td bgcolor=ffffff><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#DBEAF5">
<tr> 
  <td height="25" bgcolor="#FFFFFF">
	<?=$tts?"<select name='ttid'><option value='0'>标题分类</option>$tts</select>":""?>
	<input type=text name=title value="<?=ehtmlspecialchars(stripSlashes($r['title']))?>" size="60"> 
	<input type="button" name="button" value="图文" onclick="document.add.title.value=document.add.title.value+'(图文)';"> 
  </td>
</tr>
<tr> 
  <td height="25" bgcolor="#FFFFFF">属性: 
	<input name="titlefont[b]" type="checkbox" value="b"<?=$titlefontb?>>粗体
	<input name="titlefont[i]" type="checkbox" value="i"<?=$titlefonti?>>斜体
	<input name="titlefont[s]" type="checkbox" value="s"<?=$titlefonts?>>删除线
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;颜色: <input name="titlecolor" type="text" value="<?=ehtmlspecialchars(stripSlashes($r['titlecolor']))?>" size="10" class="color">
  </td>
</tr>
</table>
</td>
  </tr>
  <tr>
    <td width='16%' height=25 bgcolor='ffffff'>特殊属性</td>
    <td bgcolor='ffffff'><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#DBEAF5">
  <tr>
    <td height="25" bgcolor="#FFFFFF">信息属性: 
      <input name="checked" type="checkbox" value="1"<?=$r['checked']?' checked':''?>>
      审核 &nbsp;&nbsp; 推荐 
      <select name="isgood" id="isgood">
        <option value="0">不推荐</option>
	<?=$ftnr['igname']?>
      </select>
      &nbsp;&nbsp; 头条 
      <select name="firsttitle" id="firsttitle">
        <option value="0">非头条</option>
	<?=$ftnr['ftname']?>
      </select></td>
  </tr>
  <tr> 
    <td height="25" bgcolor="#FFFFFF">关键字&nbsp;&nbsp;&nbsp;: 
      <input name="keyboard" type="text" size="52" value="<?=ehtmlspecialchars(stripSlashes($r['keyboard']))?>">
      <font color="#666666">(多个请用&quot;,&quot;隔开)</font></td>
  </tr>
  <tr> 
    <td height="25" bgcolor="#FFFFFF">外部链接: 
      <input name="titleurl" type="text" value="<?=stripSlashes($r['titleurl'])?>" size="52">
      <font color="#666666">(填写后信息连接地址将为此链接)</font></td>
  </tr>
</table>
</td>
  </tr>
  <tr> 
    <td width=16% height=25 bgcolor=ffffff>发布时间</td>
    <td bgcolor=ffffff><input name="newstime" type="text" value="<?=$r['newstime']?>" size="28" class="Wdate" onClick="WdatePicker({skin:'default',dateFmt:'yyyy-MM-dd HH:mm:ss'})"><input type=button name=button value="设为当前时间" onclick="document.add.newstime.value='<?=$todaytime?>'">
</td>
  </tr>
  <tr> 
    <td width=16% height=25 bgcolor=ffffff>作品预览图</td>
    <td bgcolor=ffffff><input name="titlepic" type="text" id="titlepic" value="<?=$ecmsfirstpost==1?"":ehtmlspecialchars(stripSlashes($r['titlepic']))?>" size="45">
<a onclick="window.open('ecmseditor/FileMain.php?type=1&classid=<?=$classid?>&infoid=<?=$id?>&filepass=<?=$filepass?>&sinfo=1&doing=1&field=titlepic<?=$ecms_hashur['ehref']?>','','width=700,height=550,scrollbars=yes');" title="选择已上传的图片"><img src="../../e/data/images/changeimg.gif" border="0" align="absbottom"></a> 
</td>
  </tr>
  <tr> 
    <td width=16% height=25 bgcolor=ffffff>作者</td>
    <td bgcolor=ffffff><input name="flashwriter" type="text" id="flashwriter" value="<?=$ecmsfirstpost==1?"":DoRehValue($modid,'flashwriter',stripSlashes($r['flashwriter']))?>" size="60">
</td>
  </tr>
  <tr> 
    <td width=16% height=25 bgcolor=ffffff>作者邮箱</td>
    <td bgcolor=ffffff><input name="email" type="text" id="email" value="<?=$ecmsfirstpost==1?"":DoRehValue($modid,'email',stripSlashes($r['email']))?>" size="60">
</td>
  </tr>
  <tr> 
    <td width=16% height=25 bgcolor=ffffff>作品评价</td>
    <td bgcolor=ffffff><select name="star" id="star"><option value="1"<?=$r['star']=="1"?' selected':''?>>1星</option><option value="2"<?=$r['star']=="2"||$ecmsfirstpost==1?' selected':''?>>2星</option><option value="3"<?=$r['star']=="3"?' selected':''?>>3星</option><option value="4"<?=$r['star']=="4"?' selected':''?>>4星</option><option value="5"<?=$r['star']=="5"?' selected':''?>>5星</option></select></td>
  </tr>
  <tr> 
    <td width=16% height=25 bgcolor=ffffff>文件大小</td>
    <td bgcolor=ffffff><input name="filesize" type="text" id="filesize" value="<?=$ecmsfirstpost==1?"":DoRehValue($modid,'filesize',stripSlashes($r['filesize']))?>" size="60">
<select name="select" onchange="document.add.filesize.value+=this.value">
        <option value="">单位</option>
        <option value=" MB">MB</option>
        <option value=" KB">KB</option>
        <option value=" GB">GB</option>
        <option value=" BYTES">BYTES</option>
      </select></td>
  </tr>
  <tr> 
    <td width=16% height=25 bgcolor=ffffff>Flash地址(*)</td>
    <td bgcolor=ffffff><input name="flashurl" type="text" id="flashurl" value="<?=$ecmsfirstpost==1?"":DoRehValue($modid,'flashurl',stripSlashes($r['flashurl']))?>" size="45">
<a onclick="window.open('ecmseditor/FileMain.php?type=2&classid=<?=$classid?>&infoid=<?=$id?>&filepass=<?=$filepass?>&sinfo=1&doing=1&field=flashurl<?=$ecms_hashur['ehref']?>','','width=700,height=550,scrollbars=yes');" title="选择已上传的FLASH"><img src="../../e/data/images/changeflash.gif" border="0" align="absbottom"></a>
</td>
  </tr>
  <tr> 
    <td width=16% height=25 bgcolor=ffffff>Flash规格</td>
    <td bgcolor=ffffff><input name="width" type="text" id="width" value="<?=$ecmsfirstpost==1?"600":DoRehValue($modid,'width',stripSlashes($r['width']))?>" size="6">
*<input name="height" type="text" id="height" value="<?=$ecmsfirstpost==1?"450":DoRehValue($modid,'height',stripSlashes($r['height']))?>" size="6">
<font color="#666666">(宽度*高度)</font></td>
  </tr>
  <tr> 
    <td width=16% height=25 bgcolor=ffffff>作品简介(*)</td>
    <td bgcolor=ffffff><textarea name="flashsay" cols="80" rows="10" id="flashsay"><?=$ecmsfirstpost==1?"":DoRehValue($modid,'flashsay',stripSlashes($r['flashsay']))?></textarea>
</td>
  </tr>
</table>