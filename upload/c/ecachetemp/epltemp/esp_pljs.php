<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
document.write("");
<?php
while($r=$empire->fetch($sql))
{
	$plusername=$r['username'];
	if(empty($r['username']))
	{
		$plusername='匿名';
	}
	if($r['userid'])
	{
		$plusername="<a href='".$public_r['newsurl']."e/space/?userid=".$r['userid']."' target='_blank'>".$r['username']."</a>";
	}
	$saytime=date('Y-m-d H:i:s',$r['saytime']);
	//ip
	$sayip=ToReturnXhIp($r['sayip']);
	$saytext=str_replace("\r\n","",$r['saytext']);
	$saytext=addslashes(RepPltextFace(stripSlashes($saytext)));//替换表情
?>
document.write("       <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"1\" style=\"word-break:break-all; word-wrap:break-all;\">        <tr>           <td height=\"30\"><span class=\"name\">本站网友 <?=$plusername?></span> <font color=\"#666666\">ip:<?=$sayip?></font></td>          <td><div align=\"right\"><font color=\"#666666\"><?=$saytime?> 发表</font></div></td>        </tr>        <tr valign=\"top\">           <td height=\"50\" colspan=\"2\" class=\"text\"><?=$saytext?></td>        </tr>        <tr>           <td height=\"30\">&nbsp;</td>          <td><div align=\"right\" class=\"re\">               <a href=\"JavaScript:makeRequest(\'/ecms80/e/pl/doaction.php?enews=DoForPl&plid=<?=$r['plid']?>&classid=<?=$classid?>&id=<?=$id?>&dopl=1&doajax=1&ajaxarea=zcpldiv<?=$r['plid']?>\',\'EchoReturnedText\',\'GET\',\'\');\">支持</a>[<span id=\"zcpldiv<?=$r['plid']?>\"><?=$r['zcnum']?></span>]&nbsp;               <a href=\"JavaScript:makeRequest(\'/ecms80/e/pl/doaction.php?enews=DoForPl&plid=<?=$r['plid']?>&classid=<?=$classid?>&id=<?=$id?>&dopl=0&doajax=1&ajaxarea=fdpldiv<?=$r['plid']?>\',\'EchoReturnedText\',\'GET\',\'\');\">反对</a>[<span id=\"fdpldiv<?=$r['plid']?>\"><?=$r['fdnum']?></span>]            </div></td>        </tr>      </table>      <table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">        <tr>          <td background=\"/ecms80/skin/default/images/plhrbg.gif\"></td>        </tr>      </table>");
<?php
}
?>
document.write("");