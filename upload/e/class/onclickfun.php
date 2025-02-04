<?php
//信息统计
function InfoOnclick($classid,$id){
	global $empire,$dbtbpre,$public_r,$emod_pubr;
	$classid=(int)$classid;
	$id=(int)$id;
	if(!$classid||!$id)
	{
		return '';
	}
	$var='ecookieinforecord';
	$val=$classid.'-'.$id;
	if(!eCheckOnclickCookie($var,$val))
	{
		return '';
	}
	$r=$empire->fetch1("select tbname,tid from {$dbtbpre}enewsclass where classid='$classid'");
	if(empty($r['tbname'])||InfoIsInTable($r['tbname']))
	{
		return '';
	}
	if($public_r['onclicktype']==0)
	{
		//年月日统计
		$ymdtotaleo='';
		$ymdtid=(int)$r['tid'];
		if(strstr($emod_pubr['tbidseo'],','.$ymdtid.','))
		{
			$ymdinfor=$empire->fetch1("select * from {$dbtbpre}ecms_".$r['tbname']." where id='$id'".do_dblimit_one());
			$ymdtotaleo=eReturnYmdTotalf($ymdinfor,0);
		}
		$empire->query("update {$dbtbpre}ecms_".$r['tbname']." set onclick=onclick+1".$ymdtotaleo." where id='$id'");
	}
	elseif($public_r['onclicktype']==1)
	{
		$filename=ECMS_PATH.'c/ecachepub/efilecache/econclick/ocinfo'.$r['tid'].'.log';
		eAddUpdateOnclick($id,$filename);
		eDoUpdateOnclick($dbtbpre.'ecms_'.$r['tbname'],'id','onclick',$filename);
	}
}

//栏目统计
function ClassOnclick($classid){
	global $empire,$dbtbpre,$public_r;
	$classid=(int)$classid;
	if(!$classid)
	{
		return '';
	}
	$var='ecookieclassrecord';
	$val=$classid;
	if(!eCheckOnclickCookie($var,$val))
	{
		return '';
	}
	if($public_r['onclicktype']==0)
	{
		$empire->query("update {$dbtbpre}enewsclass set onclick=onclick+1 where classid='$classid'");
	}
	elseif($public_r['onclicktype']==1)
	{
		$filename=ECMS_PATH.'c/ecachepub/efilecache/econclick/occlass.log';
		eAddUpdateOnclick($classid,$filename);
		eDoUpdateOnclick($dbtbpre.'enewsclass','classid','onclick',$filename);
	}
}

//专题统计
function ZtOnclick($ztid){
	global $empire,$dbtbpre,$public_r;
	$ztid=(int)$ztid;
	if(!$ztid)
	{
		return '';
	}
	$var='ecookieztrecord';
	$val=$ztid;
	if(!eCheckOnclickCookie($var,$val))
	{
		return '';
	}
	if($public_r['onclicktype']==0)
	{
		$empire->query("update {$dbtbpre}enewszt set onclick=onclick+1 where ztid='$ztid'");
	}
	elseif($public_r['onclicktype']==1)
	{
		$filename=ECMS_PATH.'c/ecachepub/efilecache/econclick/oczt.log';
		eAddUpdateOnclick($ztid,$filename);
		eDoUpdateOnclick($dbtbpre.'enewszt','ztid','onclick',$filename);
	}
}

//加入点击缓存
function eAddUpdateOnclick($id,$filename){
	if(@$fp=fopen($filename,'ab'))
	{
		fwrite($fp,"$id\n");
		fclose($fp);
	}
}

//更新点击缓存
function eDoUpdateOnclick($table,$idf,$onclickf,$filename){
	global $empire,$dbtbpre,$public_r;
	if(!file_exists($filename))
	{
		return '';
	}
	if(filesize($filename)>=$public_r['onclickfilesize']*1024||time()-filectime($filename)>=$public_r['onclickfiletime']*60)
	{
		$lr=$ocr=array();
		if(@$lr=file($filename))
		{
			if(!@unlink($filename))
			{
				if($fp=@fopen($filename,'wb'))
				{
					fwrite($fp,'');
					fclose($fp);
				}
			}
			$lr=array_count_values($lr);
			foreach($lr as $id => $oc)
			{
				$ocr[$oc].=($id>0)?','.intval($id):'';
			}
			foreach($ocr as $oc => $ids)
			{
				$empire->query("UPDATE ".do_dbupsqllow()."$table SET $onclickf=$onclickf+'".intval($oc)."' WHERE $idf IN (0$ids)");
			}
		}
	}
}

//COOKIE点击验证
function eCheckOnclickCookie($var,$val){
	$doupdate=1;
	$onclickrecord=getcvar($var);
	if(strstr($onclickrecord,','.$val.','))
	{
		$doupdate=0;
	}
	else
	{
		$newval=empty($onclickrecord)?','.$val.',':$onclickrecord.$val.',';
		esetcookie($var,$newval);
	}
	if(empty($_COOKIE))
	{
		$doupdate=0;
	}
	return $doupdate;
}
?>