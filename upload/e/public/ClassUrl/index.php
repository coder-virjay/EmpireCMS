<?php
require("../../class/connect.php");
$classid=(int)$_GET['classid'];
if($classid)
{
	include("../../data/dbcache/class.php");
	$editor=1;
	if(empty($class_r[$classid]['tbname'])||InfoIsInTable($class_r[$classid]['tbname']))
	{
		printerror("ErrorUrl","",1);
    }
	$r['classid']=$classid;
	$classurl=sys_ReturnBqClassname($r,9);
	Header("Location:$classurl");
}
?>