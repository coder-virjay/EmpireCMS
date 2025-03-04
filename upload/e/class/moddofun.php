<?php
//************************************ 数据表 ************************************

//建立初始表
function AddTableDefault($tbname,$tid){
	global $empire,$dbtbpre,$ecms_config;
	//include("db/DefaultTable.php");
	//pgsql
	if($ecms_config['db']['usedb']=='pgsql')
	{
		include(ECMS_PATH.'e/class/dbpg/mytopgsqltb.php');
		include(ECMS_PATH.'e/class/dbpg/DefaultTable.php');
	}
	else
	{
		include(ECMS_PATH.'e/class/db/DefaultTable.php');
	}
	//未审核表
	$otb=$dbtbpre."ecms_".$tbname;
	$tb=$otb."_check";
	CopyEcmsTb($otb,$tb);
	$odtb=$dbtbpre."ecms_".$tbname."_data_1";
	$dtb=$tb."_data";
	CopyEcmsTb($odtb,$dtb);
	//复制存档表
	$otb=$dbtbpre."ecms_".$tbname;
	$tb=$otb."_doc";
	CopyEcmsTb($otb,$tb);
	$odtb=$dbtbpre."ecms_".$tbname."_data_1";
	$dtb=$tb."_data";
	CopyEcmsTb($odtb,$dtb);
	$optb=$dbtbpre."ecms_".$tbname."_index";
	$ptb=$tb."_index";
	CopyEcmsTb($optb,$ptb);
}

//复制数据表
function CopyNewTable($add,$userid,$username){
	global $empire,$dbtbpre;
	$tid=(int)$add['tid'];
	$newtbname=RepPostVar(strtolower(trim($add['newtbname'])));
	if(!$tid||empty($newtbname)||!$add['tname'])
	{
		printerror("EmptyTbname","");
	}
	CheckLevel($userid,$username,$classid,"table");//操作权限
	$add['yhid']=(int)$add['yhid'];
	$add['tname']=hRepPostStr($add['tname'],1);
	$add['tsay']=hRepPostStr($add['tsay'],1);
	$tr=$empire->fetch1("select tbname,intb from {$dbtbpre}enewstable where tid='$tid'");
	if(!$tr['tbname'])
	{
		printerror("EmptyTbname","");
	}
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewstable where tbname='$newtbname'".do_dblimit_cone());
	if($num)
	{
		printerror("ReTbname","history.go(-1)");
	}
	$sql=$empire->updatesql("insert into {$dbtbpre}enewstable(tbname,tname,tsay,isdefault,datatbs,deftb,yhid,mid,intb) values('".$newtbname."','".$add['tname']."','".$add['tsay']."',0,',1,','1','".$add['yhid']."',0,'".$tr['intb']."');","ins");
	$newtid=$empire->lastid($dbtbpre.'enewstable','tid');
	//复制表
	CopyEcmsTb($dbtbpre."ecms_".$tr['tbname'],$dbtbpre."ecms_".$newtbname);	//内容表
	CopyEcmsTb($dbtbpre."ecms_".$tr['tbname']."_data_1",$dbtbpre."ecms_".$newtbname."_data_1");	//内容副表
	CopyEcmsTb($dbtbpre."ecms_".$tr['tbname']."_index",$dbtbpre."ecms_".$newtbname."_index");	//内容索引表
	CopyEcmsTb($dbtbpre."ecms_".$tr['tbname']."_doc",$dbtbpre."ecms_".$newtbname."_doc");	//归档表
	CopyEcmsTb($dbtbpre."ecms_".$tr['tbname']."_doc_data",$dbtbpre."ecms_".$newtbname."_doc_data");	//归档副表
	CopyEcmsTb($dbtbpre."ecms_".$tr['tbname']."_doc_index",$dbtbpre."ecms_".$newtbname."_doc_index");	//归档索引表
	CopyEcmsTb($dbtbpre."ecms_".$tr['tbname']."_check",$dbtbpre."ecms_".$newtbname."_check");	//审核表
	CopyEcmsTb($dbtbpre."ecms_".$tr['tbname']."_check_data",$dbtbpre."ecms_".$newtbname."_check_data");	//审核副表
	CopyEcmsTb($dbtbpre."ecms_infoclass_".$tr['tbname'],$dbtbpre."ecms_infoclass_".$newtbname);	//采集节点附加表
	CopyEcmsTb($dbtbpre."ecms_infotmp_".$tr['tbname'],$dbtbpre."ecms_infotmp_".$newtbname);	//采集数据临时表
	//字段数据
	$fsql=$empire->query("select * from {$dbtbpre}enewsf where tid='$tid' order by fid");
	while($fr=$empire->fetch($fsql))
	{
		$usql=$empire->query("insert into {$dbtbpre}enewsf(f,fname,fform,fhtml,fzs,isadd,isshow,iscj,cjhtml,myorder,ftype,flen,dotemp,tid,tbname,savetxt,fvalue,iskey,tobr,dohtml,qfhtml,isonly,linkfieldval,samedata,fformsize,tbdataf,ispage,adddofun,editdofun,qadddofun,qeditdofun,linkfieldtb,linkfieldshow,editorys,issmalltext,fmvnum) values('".$fr['f']."','".$fr['fname']."','".$fr['fform']."','".addslashes(addslashes(stripSlashes($fr['fhtml'])))."','".addslashes(stripSlashes($fr['fzs']))."','".$fr['isadd']."','".$fr['isshow']."','".$fr['iscj']."','".addslashes(addslashes(stripSlashes($fr['cjhtml'])))."','".$fr['myorder']."','".$fr['ftype']."','".$fr['flen']."','".$fr['dotemp']."','".$newtid."','".$newtbname."','".$fr['savetxt']."','".addslashes(addslashes(stripSlashes($fr['fvalue'])))."','".$fr['iskey']."','".$fr['tobr']."','".$fr['dohtml']."','".addslashes(addslashes(stripSlashes($fr['qfhtml'])))."','".$fr['isonly']."','".addslashes(stripSlashes($fr['linkfieldval']))."','".$fr['samedata']."','".addslashes(stripSlashes($fr['fformsize']))."','".$fr['tbdataf']."','".$fr['ispage']."','".addslashes(stripSlashes($fr['adddofun']))."','".addslashes(stripSlashes($fr['editdofun']))."','".addslashes(stripSlashes($fr['qadddofun']))."','".addslashes(stripSlashes($fr['qeditdofun']))."','".addslashes(stripSlashes($fr['linkfieldtb']))."','".addslashes(stripSlashes($fr['linkfieldshow']))."','".$fr['editorys']."','".$fr['issmalltext']."','".addslashes(stripSlashes($fr['fmvnum']))."');");
	}
	TogSaveTxtF(1);//公共变量
	GetConfig(1);//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("tid=".$tid."<br>tb=".$tr['tbname']."<br>newtid=".$newtid."<br>newtb=".$newtbname);
		printerror("CopyTbSuccess","db/ListTable.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","");
	}
}

//建立数据表
function AddTable($add,$userid,$username){
	global $empire,$dbtbpre;
	$add['tbname']=RepPostVar(strtolower(trim($add['tbname'])));
	if(!$add['tbname']||!$add['tname'])
	{
		printerror("EmptyTbname","history.go(-1)");
    }
	//操作权限
	CheckLevel($userid,$username,$classid,"table");
	$add['yhid']=(int)$add['yhid'];
	$add['intb']=(int)$add['intb'];
	$add['tname']=hRepPostStr($add['tname'],1);
	$add['tsay']=hRepPostStr($add['tsay'],1);
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewstable where tbname='".$add['tbname']."'".do_dblimit_cone());
	if($num)
	{
		printerror("ReTbname","history.go(-1)");
	}
	$sql=$empire->updatesql("insert into {$dbtbpre}enewstable(tbname,tname,tsay,isdefault,datatbs,deftb,yhid,intb) values('".$add['tbname']."','".$add['tname']."','".$add['tsay']."',0,',1,','1','".$add['yhid']."','".$add['intb']."');","ins");
	$tid=$empire->lastid($dbtbpre.'enewstable','tid');
	//初使化表
	AddTableDefault($add['tbname'],$tid);
	GetConfig(1);//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("tid=".$tid."<br>tbname=".$add['tbname']);
		printerror("AddTbSuccess","db/AddTable.php?enews=AddTable".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改数据表
function EditTable($add,$userid,$username){
	global $empire,$dbtbpre;
	$add['tbname']=RepPostVar(strtolower(trim($add['tbname'])));
	$tid=(int)$add['tid'];
	if(!$add['tbname']||!$add['tname']||!$tid)
	{
		printerror("EmptyTbname","history.go(-1)");
    }
	//操作权限
	CheckLevel($userid,$username,$classid,"table");
	$add['yhid']=(int)$add['yhid'];
	$add['intb']=(int)$add['intb'];
	$add['tname']=hRepPostStr($add['tname'],1);
	$add['tsay']=hRepPostStr($add['tsay'],1);
	//改变数据表名
	if($add['tbname']!=$add['oldtbname'])
	{
		$add['oldtbname']=RepPostVar($add['oldtbname']);
		$tbnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewstable where tbname='".$add['tbname']."' and tid<>$tid".do_dblimit_cone());
		if($tbnum)
		{
			printerror("ReTbname","history.go(-1)");
		}
		$tbr=$empire->fetch1("select tid,isdefault,datatbs,deftb from {$dbtbpre}enewstable where tid='$tid'".do_dblimit_one());
		if(!$tbr['tid'])
		{
			printerror("EmptyTbname","history.go(-1)");
		}
		//主表
		do_dbTableEditTbname($dbtbpre.'ecms_'.$add['tbname'],$dbtbpre.'ecms_'.$add['oldtbname']);//pgsql
		//索引表
		do_dbTableEditTbname($dbtbpre.'ecms_'.$add['tbname'].'_index',$dbtbpre.'ecms_'.$add['oldtbname'].'_index');//pgsql
		//副表
		if($tbr['datatbs'])
		{
			$dtbr=explode(',',$tbr['datatbs']);
			$count=count($dtbr);
			for($i=1;$i<$count-1;$i++)
			{
				do_dbTableEditTbname($dbtbpre.'ecms_'.$add['tbname'].'_data_'.$dtbr[$i],$dbtbpre.'ecms_'.$add['oldtbname'].'_data_'.$dtbr[$i]);//pgsql
			}
		}
		//归档表
		do_dbTableEditTbname($dbtbpre.'ecms_'.$add['tbname'].'_doc',$dbtbpre.'ecms_'.$add['oldtbname'].'_doc');//pgsql
		do_dbTableEditTbname($dbtbpre.'ecms_'.$add['tbname'].'_doc_data',$dbtbpre.'ecms_'.$add['oldtbname'].'_doc_data');//pgsql
		do_dbTableEditTbname($dbtbpre.'ecms_'.$add['tbname'].'_doc_index',$dbtbpre.'ecms_'.$add['oldtbname'].'_doc_index');//pgsql
		//审核表
		do_dbTableEditTbname($dbtbpre.'ecms_'.$add['tbname'].'_check',$dbtbpre.'ecms_'.$add['oldtbname'].'_check');//pgsql
		do_dbTableEditTbname($dbtbpre.'ecms_'.$add['tbname'].'_check_data',$dbtbpre.'ecms_'.$add['oldtbname'].'_check_data');//pgsql
		//采集
		do_dbTableEditTbname($dbtbpre.'ecms_infoclass_'.$add['tbname'],$dbtbpre.'ecms_infoclass_'.$add['oldtbname']);//pgsql
		do_dbTableEditTbname($dbtbpre.'ecms_infotmp_'.$add['tbname'],$dbtbpre.'ecms_infotmp_'.$add['oldtbname']);//pgsql
		//字段
		$empire->query("update {$dbtbpre}enewsf set tbname='".$add['tbname']."' where tid='$tid'");
		//栏目
		$empire->query("update {$dbtbpre}enewsclass set tbname='".$add['tbname']."' where tid='$tid'");
		//$empire->query("update {$dbtbpre}enewszt set tbname='".$add['tbname']."' where tid='$tid'");
		$empire->query("update {$dbtbpre}enewsinfoclass set tbname='".$add['tbname']."' where tid='$tid'");
		$empire->query("update {$dbtbpre}enewsmod set tbname='".$add['tbname']."' where tid='$tid'");
		$empire->query("update {$dbtbpre}enewsinfotype set tbname='".$add['tbname']."' where tid='$tid'");
		//搜索
		$empire->query("update {$dbtbpre}enewssearch set tbname='".$add['tbname']."' where tbname='".$add['oldtbname']."'");
		$empire->query("update {$dbtbpre}enewssearchall_load set tbname='".$add['tbname']."' where tbname='".$add['oldtbname']."'");
		//默认表
		if($tbr['isdefault'])
		{
			$empire->query("update {$dbtbpre}enewspublic set tbname='".$add['tbname']."',tid='$tid'");
		}
		//文本型
		TogSaveTxtF(1);
	}
	$sql=$empire->query("update {$dbtbpre}enewstable set tbname='".$add['tbname']."',tname='".$add['tname']."',tsay='".$add['tsay']."',yhid='".$add['yhid']."',intb='".$add['intb']."' where tid='$tid'");
	GetConfig(1);//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("tid=".$tid."<br>tbname=".$add['tbname']);
		printerror("EditTbSuccess","db/ListTable.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除数据表
function DelTable($tid,$userid,$username){
	global $empire,$dbtbpre;
	$tid=(int)$tid;
	if(!$tid)
	{
		printerror("NotChangeTb","");
    }
	//操作权限
	CheckLevel($userid,$username,$classid,"deltable");
	$r=$empire->fetch1("select tid,tbname,isdefault,datatbs,deftb from {$dbtbpre}enewstable where tid='$tid'");
	if(empty($r['tid']))
	{
		printerror("NotChangeTb","");
	}
	//默认表
	if($r['isdefault'])
	{
		printerror("NotDelDefaultTb","");
	}
	$sql=$empire->query("delete from {$dbtbpre}enewstable where tid='$tid'");
	//删除数据表
	do_dbTableDelTb($dbtbpre.'ecms_'.$r['tbname']);//pgsql
	do_dbTableDelTb($dbtbpre.'ecms_'.$r['tbname'].'_index');//pgsql
	if($r['datatbs'])
	{
		$dtbr=explode(',',$r['datatbs']);
		$count=count($dtbr);
		for($i=1;$i<$count-1;$i++)
		{
			do_dbTableDelTb($dbtbpre.'ecms_'.$r['tbname'].'_data_'.$dtbr[$i]);//pgsql
		}
	}
	//删除采集表
	do_dbTableDelTb($dbtbpre.'ecms_infoclass_'.$r['tbname']);//pgsql
	do_dbTableDelTb($dbtbpre.'ecms_infotmp_'.$r['tbname']);//pgsql
	//删除归档表
	do_dbTableDelTb($dbtbpre.'ecms_'.$r['tbname'].'_doc');//pgsql
	do_dbTableDelTb($dbtbpre.'ecms_'.$r['tbname'].'_doc_data');//pgsql
	do_dbTableDelTb($dbtbpre.'ecms_'.$r['tbname'].'_doc_index');//pgsql
	//删除审核表
	do_dbTableDelTb($dbtbpre.'ecms_'.$r['tbname'].'_check');//pgsql
	do_dbTableDelTb($dbtbpre.'ecms_'.$r['tbname'].'_check_data');//pgsql
	//删除数据
	$empire->query("delete from {$dbtbpre}enewsf where tid='$tid'");
	$empire->query("delete from {$dbtbpre}enewsmod where tid='$tid'");
	$empire->query("delete from {$dbtbpre}enewsinfoclass where tid='$tid'");
	//文本型
	TogSaveTxtF(1);
	GetConfig(1);//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("tid=".$tid."<br>tbname=".$r['tbname']);
		printerror("DelTbSuccess","db/ListTable.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//默认数据表
function DefaultTable($tid,$userid,$username){
	global $empire,$dbtbpre;
	$tid=(int)$tid;
	if(!$tid)
	{
		printerror("NotChangeDefaultTb","history.go(-1)");
    }
	//操作权限
	CheckLevel($userid,$username,$classid,"table");
	$r=$empire->fetch1("select tid,tbname from {$dbtbpre}enewstable where tid='$tid'");
	if(empty($r['tid']))
	{
		printerror("NotChangeDefaultTb","history.go(-1)");
	}
	$usql=$empire->query("update {$dbtbpre}enewstable set isdefault=0");
	$sql=$empire->query("update {$dbtbpre}enewstable set isdefault=1 where tid='$tid'");
	$upsql=$empire->query("update {$dbtbpre}enewspublic set tbname='".$r['tbname']."',tid='$tid'");
	GetConfig(1);//更新缓存
	if($sql&&$usql&&$upsql)
	{
		//操作日志
		insert_dolog("tid=".$tid."<br>tbname=".$r['tbname']);
		printerror("DefaultTableSuccess","db/ListTable.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//增加副表分表
function AddDataTable($add,$userid,$username){
	global $empire,$dbtbpre;
	//操作权限
	CheckLevel($userid,$username,$classid,"table");
	$tid=(int)$add['tid'];
	$tbname=RepPostVar($add['tbname']);
	$datatb=(int)$add['datatb'];
	if(!$tid||!$tbname||!$datatb)
	{
		printerror("EmptyDataTable","history.go(-1)");
	}
	$tr=$empire->fetch1("select tid,datatbs from {$dbtbpre}enewstable where tid='$tid'");
	if(!$tr['tid'])
	{
		printerror("EmptyDataTable","history.go(-1)");
	}
	if(strstr($tr['datatbs'],','.$datatb.','))
	{
		printerror("ReDataTable","history.go(-1)");
	}
	if(empty($tr['datatbs']))
	{
		$tr['datatbs']=',';
	}
	$newdatatbs=$tr['datatbs'].$datatb.',';
	//建表
	$odtb=$dbtbpre."ecms_".$tbname."_data_1";
	$dtb=$dbtbpre."ecms_".$tbname."_data_".$datatb;
	CopyEcmsTb($odtb,$dtb);
	$sql=$empire->query("update {$dbtbpre}enewstable set datatbs='$newdatatbs' where tid='$tid'");
	GetConfig(1);//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("tid=".$tid."<br>tbname=".$tbname."&datatb=$datatb");
		printerror("AddDataTableSuccess","db/ListDataTable.php?tid=$tid&tbname=$tbname".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//默认副表存放表
function DefDataTable($add,$userid,$username){
	global $empire,$dbtbpre;
	//操作权限
	CheckLevel($userid,$username,$classid,"table");
	$tid=(int)$add['tid'];
	$tbname=RepPostVar($add['tbname']);
	$datatb=(int)$add['datatb'];
	if(!$tid||!$tbname||!$datatb)
	{
		printerror("NotChangeDataTable","history.go(-1)");
	}
	$tr=$empire->fetch1("select tid,datatbs from {$dbtbpre}enewstable where tid='$tid'");
	if(!$tr['tid'])
	{
		printerror("NotChangeDataTable","history.go(-1)");
	}
	if(!strstr($tr['datatbs'],','.$datatb.','))
	{
		printerror("NotChangeDataTable","history.go(-1)");
	}
	$sql=$empire->query("update {$dbtbpre}enewstable set deftb='$datatb' where tid='$tid'");
	GetConfig(1);//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("tid=".$tid."<br>tbname=".$tbname."&datatb=$datatb");
		printerror("DefDataTableSuccess","db/ListDataTable.php?tid=$tid&tbname=$tbname".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除副表分表
function DelDataTable($add,$userid,$username){
	global $empire,$dbtbpre,$emod_r,$class_r;
	//操作权限
	CheckLevel($userid,$username,$classid,"deltable");
	$tid=(int)$add['tid'];
	$tbname=RepPostVar($add['tbname']);
	$datatb=(int)$add['datatb'];
	if(!$tid||!$tbname||!$datatb)
	{
		printerror("NotChangeDataTable","history.go(-1)");
	}
	$tr=$empire->fetch1("select tid,tbname,datatbs,deftb from {$dbtbpre}enewstable where tid='$tid'");
	if(!$tr['tid'])
	{
		printerror("NotChangeDataTable","history.go(-1)");
	}
	if(!strstr($tr['datatbs'],','.$datatb.','))
	{
		printerror("NotChangeDataTable","history.go(-1)");
	}
	if($tr['deftb']==$datatb||$datatb==1)
	{
		printerror("NotDelDefDataTable","history.go(-1)");
	}
	$newdatatbs=str_replace(','.$datatb.',',',',$tr['datatbs']);
	$sql=$empire->query("update {$dbtbpre}enewstable set datatbs='$newdatatbs' where tid='$tid'");
	//删除信息
	$infosql=$empire->query("select * from {$dbtbpre}ecms_".$tr['tbname']." where stb='$datatb'");
	while($infor=$empire->fetch($infosql))
	{
		$mid=$class_r[$infor['classid']]['modid'];
		$pf=$emod_r[$mid]['pagef'];
		$stf=$emod_r[$mid]['savetxtf'];
		//分页字段
		if($pf)
		{
			if(strstr($emod_r[$mid]['tbdataf'],','.$pf.','))
			{
				$finfor=$empire->fetch1("select ".$pf." from {$dbtbpre}ecms_".$tr['tbname']."_data_".$datatb." where id='".$infor['id']."'");
				$infor[$pf]=$finfor[$pf];
			}
		}
		//存文本
		if($stf)
		{
			$newstextfile=$infor[$stf];
			$infor[$stf]=GetTxtFieldText($infor[$stf]);
			DelTxtFieldText($newstextfile);//删除文件
		}
		//efz
		DelInfoForFzData($tr['tbname'],$infor['classid'],$infor['id'],1,$datatb,$infor['efz'],'');
		//删除信息文件
		DelNewsFile($infor['filename'],$infor['newspath'],$infor['classid'],$infor[$pf],$infor['groupid']);
		//删除主表信息
		$empire->query("delete from {$dbtbpre}ecms_".$tr['tbname']."_index where id='".$infor['id']."'");
		//删除其它表记录和附件
		DelSingleInfoOtherData($infor['classid'],$infor['id'],$infor,0,0);
	}
	$deltb=$empire->query("delete from {$dbtbpre}ecms_".$tr['tbname']." where stb='$datatb'");
	//删除归档信息
	$docinfosql=$empire->query("select * from {$dbtbpre}ecms_".$tr['tbname']."_doc where stb='$datatb'");
	while($infor=$empire->fetch($docinfosql))
	{
		$mid=$class_r[$infor['classid']]['modid'];
		$pf=$emod_r[$mid]['pagef'];
		$stf=$emod_r[$mid]['savetxtf'];
		//分页字段
		if($pf)
		{
			if(strstr($emod_r[$mid]['tbdataf'],','.$pf.','))
			{
				$finfor=$empire->fetch1("select ".$pf." from {$dbtbpre}ecms_".$tr['tbname']."_doc_data where id='".$infor['id']."'");
				$infor[$pf]=$finfor[$pf];
			}
		}
		//存文本
		if($stf)
		{
			$newstextfile=$infor[$stf];
			$infor[$stf]=GetTxtFieldText($infor[$stf]);
			DelTxtFieldText($newstextfile);//删除文件
		}
		//efz
		DelInfoForFzData($tr['tbname'],$infor['classid'],$infor['id'],2,$datatb,$infor['efz'],'');
		//删除信息文件
		DelNewsFile($infor['filename'],$infor['newspath'],$infor['classid'],$infor[$pf],$infor['groupid']);
		//删除主表信息
		$empire->query("delete from {$dbtbpre}ecms_".$tr['tbname']."_doc_index where id='".$infor['id']."'");
		//删除副表信息
		$empire->query("delete from {$dbtbpre}ecms_".$tr['tbname']."_doc_data where id='".$infor['id']."'");
		//删除其它表记录与附件
		DelSingleInfoOtherData($infor['classid'],$infor['id'],$infor,0,0);
	}
	$deltb=$empire->query("delete from {$dbtbpre}ecms_".$tr['tbname']."_doc where stb='$datatb'");
	//删除审核信息
	$bakinfosql=$empire->query("select * from {$dbtbpre}ecms_".$tr['tbname']."_check where stb='$datatb'");
	while($infor=$empire->fetch($bakinfosql))
	{
		$mid=$class_r[$infor['classid']]['modid'];
		$pf=$emod_r[$mid]['pagef'];
		$stf=$emod_r[$mid]['savetxtf'];
		//分页字段
		if($pf)
		{
			if(strstr($emod_r[$mid]['tbdataf'],','.$pf.','))
			{
				$finfor=$empire->fetch1("select ".$pf." from {$dbtbpre}ecms_".$tr['tbname']."_check_data where id='".$infor['id']."'");
				$infor[$pf]=$finfor[$pf];
			}
		}
		//存文本
		if($stf)
		{
			$newstextfile=$infor[$stf];
			$infor[$stf]=GetTxtFieldText($infor[$stf]);
			DelTxtFieldText($newstextfile);//删除文件
		}
		//efz
		DelInfoForFzData($tr['tbname'],$infor['classid'],$infor['id'],0,$datatb,$infor['efz'],'');
		//删除信息文件
		DelNewsFile($infor['filename'],$infor['newspath'],$infor['classid'],$infor[$pf],$infor['groupid']);
		//删除主表信息
		$empire->query("delete from {$dbtbpre}ecms_".$tr['tbname']."_index where id='".$infor['id']."'");
		//删除副表信息
		$empire->query("delete from {$dbtbpre}ecms_".$tr['tbname']."_check_data where id='".$infor['id']."'");
		//删除其它表记录和附件
		DelSingleInfoOtherData($infor['classid'],$infor['id'],$infor,0,0);
	}
	$deltb=$empire->query("delete from {$dbtbpre}ecms_".$tr['tbname']."_check where stb='$datatb'");
	//删除表
	$deltb=do_dbTableDelTb($dbtbpre.'ecms_'.$tr['tbname'].'_data_'.$datatb);//pgsql
	GetConfig(1);//更新缓存
	if($sql)
	{
		//操作日志
		insert_dolog("tid=".$tid."<br>tbname=".$tr['tbname']."&datatb=$datatb");
		printerror("DelDataTableSuccess","db/ListDataTable.php?tid=$tid&tbname=".$tr['tbname'].hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}


//************************************ 字段 ************************************

//返回字段值
function ReturnFvalue($value){
	$value=str_replace("\r\n","|",$value);
	return $value;
}

//取得表单元素html代码
function GetFform($type,$f,$fvalue,$linkfieldval,$fformsize='',$add=null){
	if($type=="select"||$type=="radio"||$type=="checkbox")
	{
		return GetFformSelect($type,$f,$fvalue,$fformsize);
	}
	$file="../../e/data/html/fhtml.txt";
	$data=ReadFiletext($file);
	//特殊字段
	if($f=="newstext"||$f=="writer"||$f=="befrom"||$f=="downpath"||$f=="onlinepath"||$f=="morepic"||$f=="playerid")
	{
		$type=$f;
	}
	$exp="[!--".$type."--]";
	$r=explode($exp,$data);
	$string=str_replace("[!--enews.var--]",$f,$r[1]);
	$string=str_replace("[!--enews.def.val--]",$fvalue,$string);
	if($type=='linkfield')//选择外表关联字段
	{
		$string=str_replace("[!--enews.cfield.var--]",$add['linkfieldval'],$string);
		$string=str_replace("[!--enews.vfield.var--]",$add['linkfieldshow'],$string);
		$string=str_replace("[!--enews.ctbname--]",$add['linkfieldtb'],$string);
	}
	elseif($type=='linkfieldselect')//下拉外表关联字段
	{
		$selectf=$add['linkfieldval']==$add['linkfieldshow']?$add['linkfieldval']:$add['linkfieldval'].','.$add['linkfieldshow'];
		$string=str_replace("[!--enews.cfield.var--]",$add['linkfieldval'],$string);
		$string=str_replace("[!--enews.vfield.var--]",$add['linkfieldshow'],$string);
		$string=str_replace("[!--enews.ctbname--]",$add['linkfieldtb'],$string);
		$string=str_replace("[!--enews.selectf--]",$selectf,$string);
	}
	elseif($type=='editor'||$type=='newstext')//编辑器
	{
		$editortype=$add['editorys']==0?'Default':'Basic';
		$string=str_replace("[!--editor.type--]",$editortype,$string);
		$string=str_replace("[!--editor.basepath--]",'',$string);
	}
	elseif($type=='fieldchid')//ID列表字段
	{
		$string=str_replace("[!--enews.ctbname--]",$add['linkfieldtb'],$string);
	}
	elseif($type=='morevaluefield')//多值字段
	{
		$mvr=explode(',',$add['fmvnum']);
		$mv_var=ReturnMoreValueFieldHtmlVar($f,$mvr[0],$mvr[1],$mvr[2]);
		$string=str_replace("[!--enews.jstr--]",$mv_var['jstr'],$string);
		$string=str_replace("[!--enews.saytr--]",$mv_var['saytr'],$string);
		$string=str_replace("[!--enews.deftr--]",$mv_var['deftr'],$string);
		$string=str_replace("[!--enews.edittr--]",$mv_var['edittr'],$string);
		$string=str_replace("[!--enews.mvline--]",$mvr[1],$string);
		$string=str_replace("[!--enews.mvnum--]",$mvr[0],$string);
		$string=str_replace("[!--enews.mvmust--]",$mvr[2],$string);
	}
	$string=RepFformSize($f,$string,$type,$fformsize);
	return fAddAddsData($string);
}

//返回系统字段采集html代码
function GetSysFCjform($f){
	$sysfdata=ReadFiletext('../../e/data/html/sysfhtml.txt');
	$expt='[!--sys.qf.exp--]';
	if($f=='title')
	{
		$expf='[!--sys.title--]';
	}
	elseif($f=='titlepic')
	{
		$expf='[!--sys.titlepic--]';
	}
	elseif($f=='special.field')
	{
		$expf='[!--sys.special.field--]';
	}
	elseif($f=='newstime')
	{
		$expf='[!--sys.newstime--]';
	}
	else
	{
		$expf='[!--sys.newstime--]';
	}
	$sysf_titler=explode($expf,$sysfdata);
	$sysf_titletr=explode($expt,$sysf_titler[1]);
	return $sysf_titletr;
}

//取得采集表单元素html代码
function GetCjform($type,$f){
	$file="../../e/data/html/cjhtml.txt";
	$data=ReadFiletext($file);
	//特殊字段
	if($f=="downpath"||$f=="onlinepath"||$f=="morepic"||$f=="playerid")
	{
		$type=$f;
	}
	if($type=="password"||$type=="select"||$type=="radio"||$type=="checkbox"||$type=="date"||$type=="color"||$type=="linkfield"||$type=="editor"||$type=="ubbeditor"||$type=="linkfieldselect"||$type=="morevaluefield"||$type=="datetime"||$type=="fieldchid")
	{
		$type="text";
	}
	$exp="[!--".$type."--]";
	$r=explode($exp,$data);
	$string=str_replace("[!--enews.var--]",$f,$r[1]);
	return fAddAddsData($string);
}

//取得投稿表单元素html代码
function GetQFform($type,$f,$fvalue,$fformsize='',$add=null){
	if($type=="select"||$type=="radio"||$type=="checkbox")
	{
		return GetFformSelect($type,$f,$fvalue,$fformsize);
	}
	$file="../../e/data/html/qfhtml.txt";
	$data=ReadFiletext($file);
	//特殊字段
	if($f=="newstext"||$f=="downpath"||$f=="onlinepath"||$f=="morepic"||$f=="playerid")
	{
		$type=$f;
	}
	$exp="[!--".$type."--]";
	$r=explode($exp,$data);
	$string=str_replace("[!--enews.var--]",$f,$r[1]);
	$string=str_replace("[!--enews.def.val--]",$fvalue,$string);
	if($type=='linkfield')//选择外表关联字段
	{
		$string=str_replace("[!--enews.cfield.var--]",$add['linkfieldval'],$string);
		$string=str_replace("[!--enews.vfield.var--]",$add['linkfieldshow'],$string);
		$string=str_replace("[!--enews.ctbname--]",$add['linkfieldtb'],$string);
	}
	elseif($type=='linkfieldselect')//下拉外表关联字段
	{
		$selectf=$add['linkfieldval']==$add['linkfieldshow']?$add['linkfieldval']:$add['linkfieldval'].','.$add['linkfieldshow'];
		$string=str_replace("[!--enews.cfield.var--]",$add['linkfieldval'],$string);
		$string=str_replace("[!--enews.vfield.var--]",$add['linkfieldshow'],$string);
		$string=str_replace("[!--enews.ctbname--]",$add['linkfieldtb'],$string);
		$string=str_replace("[!--enews.selectf--]",$selectf,$string);
	}
	elseif($type=='editor'||$type=='newstext')//编辑器
	{
		$editortype=$add['editorys']==0?'Default':'Basic';
		$string=str_replace("[!--editor.type--]",$editortype,$string);
		$string=str_replace("[!--editor.basepath--]",'',$string);
	}
	elseif($type=='fieldchid')//ID列表字段
	{
		$string=str_replace("[!--enews.ctbname--]",$add['linkfieldtb'],$string);
	}
	elseif($type=='morevaluefield')//多值字段
	{
		$mvr=explode(',',$add['fmvnum']);
		$mv_var=ReturnMoreValueFieldHtmlVar($f,$mvr[0],$mvr[1],$mvr[2]);
		$string=str_replace("[!--enews.jstr--]",$mv_var['jstr'],$string);
		$string=str_replace("[!--enews.saytr--]",$mv_var['saytr'],$string);
		$string=str_replace("[!--enews.deftr--]",$mv_var['deftr'],$string);
		$string=str_replace("[!--enews.edittr--]",$mv_var['edittr'],$string);
		$string=str_replace("[!--enews.mvline--]",$mvr[1],$string);
		$string=str_replace("[!--enews.mvnum--]",$mvr[0],$string);
		$string=str_replace("[!--enews.mvmust--]",$mvr[2],$string);
	}
	$string=RepFformSize($f,$string,$type,$fformsize);
	return fAddAddsData($string);
}

//取得select/radio元素代码
function GetFformSelect($type,$f,$fvalue,$fformsize=''){
	$vr=explode('|',$fvalue);
	$count=count($vr);
	$change='';
	$def=':default';
	for($i=0;$i<$count;$i++)
	{
		$isdef='';
		if(strstr($vr[$i],$def))
		{
			$dr=explode($def,$vr[$i]);
			$vr[$i]=$dr[0];
			$isdef="||\$ecmsfirstpost==1";
		}
		$selectvalr=explode('==',$vr[$i]);
		$val=$selectvalr[0];
		$valname=$selectvalr[1]?$selectvalr[1]:$selectvalr[0];
		if($type=='select')
		{
			$change.="<option value=\"".$val."\"<?=\$r['".$f."']==\"".$val."\"".$isdef."?' selected':''?>>".$valname."</option>";
		}
		elseif($type=='checkbox')
		{
			$change.="<input name=\"".$f."[]\" type=\"checkbox\" value=\"".$val."\"<?=strstr(\$r['".$f."'],\"|".$val."|\")".$isdef."?' checked':''?>>".$valname;
		}
		else
		{
			$change.="<input name=\"".$f."\" type=\"radio\" value=\"".$val."\"<?=\$r['".$f."']==\"".$val."\"".$isdef."?' checked':''?>>".$valname;
		}
	}
	if($type=="select")
	{
		if($fformsize)
		{
			$addsize=' style="width:'.$fformsize.'"';
		}
		$change="<select name=\"".$f."\" id=\"".$f."\"".$addsize.">".$change."</select>";
	}
	return $change;
}

//替换表单元素长度
function RepFformSize($f,$string,$type,$fformsize=''){
	$fformsize=ReturnDefFformSize($f,$type,$fformsize);
	if($type=='textarea'||$type=='editor'||$type=='ubbeditor'||$type=='newstext')
	{
		$r=explode(',',$fformsize);
		$string=str_replace('[!--fsize.w--]',$r[0],$string);
		$string=str_replace('[!--fsize.h--]',$r[1],$string);
	}
	else
	{
		$string=str_replace('[!--fsize.w--]',$fformsize,$string);
	}
	return $string;
}

//返回默认长度
function ReturnDefFformSize($f,$type,$fformsize){
	if(empty($fformsize))
	{
		if($type=='textarea')
		{
			$fformsize='60,10';
		}
		elseif($type=='img')
		{
			$fformsize='45';
		}
		elseif($type=='file')
		{
			$fformsize='45';
		}
		elseif($type=='flash')
		{
			$fformsize='45';
		}
		elseif($type=='date')
		{
			$fformsize='12';
		}
		elseif($type=='datetime')
		{
			$fformsize='28';
		}
		elseif($type=='color')
		{
			$fformsize='10';
		}
		elseif($type=='linkfield')
		{
			$fformsize='45';
		}
		elseif($type=='downpath')
		{
			$fformsize='45';
		}
		elseif($type=='onlinepath')
		{
			$fformsize='45';
		}
		elseif($type=='fieldchid')
		{
			$fformsize='45';
		}
		elseif($type=='editor'||$type=='newstext')
		{
			$fformsize='100%,300';
		}
		elseif($type=='ubbeditor')
		{
			$fformsize='60,10';
		}
	}
	return $fformsize;
}

//返回多值字段录入项html代码变量
function ReturnMoreValueFieldHtmlVar($f,$mvnum,$mvline,$mvmust){
	global $fun_r;
	$del=' <input type="hidden" name="'.$f.'_mvid[]" id="'.$f.'_mvid_<?=$j?>" value="<?=$j?>"><input type="checkbox" name="'.$f.'_mvdelid[]" id="'.$f.'_mvdelid_<?=$j?>" value="<?=$j?>">'.$fun_r['FSingleDel'];
	$saytr='<tr>';
	$jstr='<tr>';
	$deftr='<tr>';
	$edittr='<tr>';
	for($i=0;$i<$mvnum;$i++)
	{
		$j=$i+1;
		//描述
		$saytr.='<td align="center">'.$fun_r['FSetting'].$j.'</td>';
		//JS
		$jstr.='<td align="center"><input type="text" name="'.$f.'_'.$j.'[]" id="'.$f.'_'.$j.'_\'+j+\'" value=""></td>';
		//默认
		$deftr.='<td align="center"><input type="text" name="'.$f.'_'.$j.'[]" id="'.$f.'_'.$j.'_<?=$i?>" value=""></td>';
		//修改
		$edittr.='<td align="center"><input type="text" name="'.$f.'_'.$j.'[]" id="'.$f.'_'.$j.'_<?=$j?>" value="<?=$mvf_field['.$i.']?>">'.($i==0?$del:'').'</td>';
	}
	$saytr.='</tr>';
	$jstr.='</tr>';
	$deftr.='</tr>';
	$edittr.='</tr>';
	$r['saytr']=$saytr;
	$r['jstr']=$jstr;
	$r['deftr']=$deftr;
	$r['edittr']=$edittr;
	return $r;
}

//返回字段变量
function DoPostFVar($add){
	$add['tid']=(int)$add['tid'];
	$add['tbname']=RepPostVar($add['tbname']);
	$add['f']=RepPostVar($add['f']);
	//处理变量
	$add['iscj']=(int)$add['iscj'];
	$add['myorder']=(int)$add['myorder'];
	$add['savetxt']=(int)$add['savetxt'];
	$add['iskey']=(int)$add['iskey'];
	$add['tobr']=(int)$add['tobr'];
	$add['dohtml']=(int)$add['dohtml'];
	$add['isonly']=(int)$add['isonly'];
	$add['samedata']=(int)$add['samedata'];
	$add['tbdataf']=(int)$add['tbdataf'];
	$add['ispage']=(int)$add['ispage'];
	$add['editorys']=(int)$add['editorys'];
	$add['issmalltext']=(int)$add['issmalltext'];
	if($add['fform']=='textarea'||$add['fform']=='editor')
	{
		if($add['fformwidth']||$add['fformheight'])
		{
			$add['fformsize']=$add['fformwidth'].','.$add['fformheight'];
		}
	}
	if($add['fform']=='morevaluefield')
	{
		$add['fmvnum']=intval($add['fmvnum']).','.intval($add['fmvline']).','.intval($add['fmvmust']);
	}
	else
	{
		$add['fmvnum']='';
	}
	$add['fname']=hRepPostStr($add['fname'],1);
	$add['fform']=hRepPostStr($add['fform'],1);
	$add['fzs']=hRepPostStr($add['fzs'],1);
	$add['ftype']=hRepPostStr($add['ftype'],1);
	$add['flen']=hRepPostStr($add['flen'],1);
	$add['linkfieldval']=hRepPostStr($add['linkfieldval'],1);
	$add['fformsize']=hRepPostStr($add['fformsize'],1);
	$add['adddofun']=hRepPostStr($add['adddofun'],0);
	$add['editdofun']=hRepPostStr($add['editdofun'],0);
	$add['qadddofun']=hRepPostStr($add['qadddofun'],0);
	$add['qeditdofun']=hRepPostStr($add['qeditdofun'],0);
	$add['linkfieldtb']=hRepPostStr($add['linkfieldtb'],1);
	$add['linkfieldshow']=hRepPostStr($add['linkfieldshow'],1);
	$add['fmvnum']=hRepPostStr($add['fmvnum'],1);
	return $add;
}

//验证字段是否重复
function CheckReTbF($add,$ecms=0){
	global $empire,$dbtbpre;
	$specialf=',oldurl,tmptime,smallurl,newsurl,titlepicl,';
	if(stristr($specialf,','.$add['f'].','))
	{
		printerror("ReF","history.go(-1)");
	}
	//修改
	if($ecms==1&&$add['f']==$add['oldf'])
	{
		return '';
	}
	//主表
	$s=do_dbFieldRowList($dbtbpre.'ecms_'.$add['tbname']);
	$b=0;
	while($r=$empire->fetch($s))
	{
		if($r['Field']==$add['f'])
		{
			$b=1;
			break;
		}
    }
	if($b)
	{
		printerror("ReF","history.go(-1)");
	}
	//副表
	$s=do_dbFieldRowList($dbtbpre.'ecms_'.$add['tbname'].'_data_1');
	$b=0;
	while($r=$empire->fetch($s))
	{
		if($r['Field']==$add['f'])
		{
			$b=1;
			break;
		}
    }
	if($b)
	{
		printerror("ReF","history.go(-1)");
	}
	//索引表
	$s=do_dbFieldRowList($dbtbpre.'ecms_'.$add['tbname'].'_index');
	$b=0;
	while($r=$empire->fetch($s))
	{
		if($r['Field']==$add['f'])
		{
			$b=1;
			break;
		}
    }
	if($b)
	{
		printerror("ReF","history.go(-1)");
	}
}

//返回字段类型
function ReturnTbFtype($add){
	if($add['ftype']=="TINYINT"||$add['ftype']=="SMALLINT"||$add['ftype']=="INT"||$add['ftype']=="BIGINT"||$add['ftype']=="FLOAT"||$add['ftype']=="DOUBLE")
	{
		$def=" default '0'";
	}
	elseif($add['ftype']=="VARCHAR"||$add['ftype']=="CHAR")
	{
		$def=" default ''";
	}
	elseif($add['ftype']=="DATE")
	{
		$def=" default '0000-00-00'";
	}
	elseif($add['ftype']=="DATETIME")
	{
		$def=" default '0000-00-00 00:00:00'";
	}
	else
	{
		$def='';
	}
	$type=$add['ftype'];
	//VARCHAR
	if(($add['ftype']=='VARCHAR'||$add['ftype']=='CHAR')&&empty($add['flen']))
	{
		$add['flen']='255';
	}
	//字段长度
	if($add['flen'])
	{
		if($add['ftype']!="TEXT"&&$add['ftype']!="MEDIUMTEXT"&&$add['ftype']!="LONGTEXT"&&$add['ftype']!="DATE"&&$add['ftype']!="DATETIME")
		{
			$type.="(".$add['flen'].")";
		}
	}
	$field=$add['f']." ".$type." NOT NULL".$def;
	return $field;
}

//增加字段
function AddF($add,$userid,$username){
	global $empire,$dbtbpre;
	$add=DoPostFVar($add);
	$tid=$add['tid'];
	$tbname=$add['tbname'];
	if(empty($add['f'])||empty($add['fname'])||!$add['tid']||!$add['tbname'])
	{
		printerror("EmptyF","history.go(-1)");
	}
	CheckLevel($userid,$username,$classid,"f");//验证权限
	CheckReTbF($add,0);//字段是否重复
	//存文本
	if($add['savetxt']==1)
	{
		$txtnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsf where savetxt=1 and tid='$tid'");
		if($txtnum)
		{
			printerror('ReTxtF','');
		}
	}
	//分页
	if($add['ispage']==1)
	{
		$pagenum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsf where ispage=1 and tid='$tid'");
		if($pagenum)
		{
			printerror('RePageF','');
		}
	}
	$add['fvalue']=ReturnFvalue($add['fvalue']);//初始化值
	$field=ReturnTbFtype($add);//返回字段
	//信息表新增字段
	if($add['tbdataf']==1)//附加表
	{
		$tbr=$empire->fetch1("select datatbs from {$dbtbpre}enewstable where tid='$tid'");
		if($tbr['datatbs'])
		{
			$dtbr=explode(',',$tbr['datatbs']);
			$count=count($dtbr);
			for($i=1;$i<$count-1;$i++)
			{
				do_dbTableAddF($dbtbpre.'ecms_'.$tbname.'_data_'.$dtbr[$i],$add['f'],$add['ftype'],$add['flen'],'');//pgsql
				if($add['iskey']==1)//索引
				{
					do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_data_'.$dtbr[$i],$add['f'],'');//pgsql
				}
			}
		}
		//归档副表
		$asql=do_dbTableAddF($dbtbpre.'ecms_'.$tbname.'_doc_data',$add['f'],$add['ftype'],$add['flen'],'');//pgsql
		if($add['iskey']==1)//索引
		{
			$keysql=do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_doc_data',$add['f'],'');//pgsql
		}
		//审核副表
		$asql=do_dbTableAddF($dbtbpre.'ecms_'.$tbname.'_check_data',$add['f'],$add['ftype'],$add['flen'],'');//pgsql
		if($add['iskey']==1)//索引
		{
			$keysql=do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_check_data',$add['f'],'');//pgsql
		}
	}
	else//主表
	{
		$asql=do_dbTableAddF($dbtbpre.'ecms_'.$tbname,$add['f'],$add['ftype'],$add['flen'],'');//pgsql
		if($add['iskey']==1)//索引
		{
			$keysql=do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname,$add['f'],'');//pgsql
		}
		//归档主表
		$asql=do_dbTableAddF($dbtbpre.'ecms_'.$tbname.'_doc',$add['f'],$add['ftype'],$add['flen'],'');//pgsql
		if($add['iskey']==1)//索引
		{
			$keysql=do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_doc',$add['f'],'');//pgsql
		}
		//审核主表
		$asql=do_dbTableAddF($dbtbpre.'ecms_'.$tbname.'_check',$add['f'],$add['ftype'],$add['flen'],'');//pgsql
		if($add['iskey']==1)//索引
		{
			$keysql=do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_check',$add['f'],'');//pgsql
		}
	}
	//采集表新增字段
	if($add['iscj']==1)
	{
		$asql=do_dbTableAddF($dbtbpre.'ecms_infoclass_'.$tbname,'zz_'.$add['f'],'TEXT','','');//pgsql
		$asql=do_dbTableAddF($dbtbpre.'ecms_infoclass_'.$tbname,'z_'.$add['f'],'VARCHAR','255','');//pgsql
		$asql=do_dbTableAddF($dbtbpre.'ecms_infoclass_'.$tbname,'qz_'.$add['f'],'VARCHAR','255','');//pgsql
		$asql=do_dbTableAddF($dbtbpre.'ecms_infoclass_'.$tbname,'save_'.$add['f'],'VARCHAR','10','');//pgsql
		$asql=do_dbTableAddF($dbtbpre.'ecms_infotmp_'.$tbname,$add['f'],$add['ftype'],$add['flen'],'');//pgsql
	}
	//替换代码
	$fhtml=GetFform($add['fform'],$add['f'],$add['fvalue'],$add['linkfieldval'],$add['fformsize'],$add);
	$cjhtml=GetCjform($add['fform'],$add['f']);
	$qfhtml=GetQFform($add['fform'],$add['f'],$add['fvalue'],$add['fformsize'],$add);
	$sql=$empire->updatesql("insert into {$dbtbpre}enewsf(f,fname,fform,fhtml,fzs,isadd,isshow,iscj,cjhtml,myorder,ftype,flen,dotemp,tid,tbname,savetxt,fvalue,iskey,tobr,dohtml,qfhtml,isonly,linkfieldval,samedata,fformsize,tbdataf,ispage,adddofun,editdofun,qadddofun,qeditdofun,linkfieldtb,linkfieldshow,editorys,issmalltext,fmvnum) values('".$add['f']."','".$add['fname']."','".$add['fform']."','".eaddslashes2($fhtml)."','".eaddslashes($add['fzs'])."',1,1,'".$add['iscj']."','".eaddslashes2($cjhtml)."','".$add['myorder']."','".$add['ftype']."','".$add['flen']."',1,'".$tid."','".$tbname."','".$add['savetxt']."','".eaddslashes2($add['fvalue'])."','".$add['iskey']."','".$add['tobr']."','".$add['dohtml']."','".eaddslashes2($qfhtml)."','".$add['isonly']."','".eaddslashes($add['linkfieldval'])."','".$add['samedata']."','".$add['fformsize']."','".$add['tbdataf']."','".$add['ispage']."','".$add['adddofun']."','".$add['editdofun']."','".$add['qadddofun']."','".$add['qeditdofun']."','".$add['linkfieldtb']."','".$add['linkfieldshow']."','".$add['editorys']."','".$add['issmalltext']."','".$add['fmvnum']."');","ins");
	$lastid=$empire->lastid($dbtbpre.'enewsf','fid');
	TogSaveTxtF(1);//公共变量
	if($add['savetxt']==1&&$add['iscj']==1)//存放文本
	{
		$tmpsql=do_dbTableEditF($dbtbpre.'ecms_infotmp_'.$tbname,$add['f'],$add['f'],'MEDIUMTEXT','','');//pgsql
	}
	GetConfig(1);//更新缓存
	if($sql)
	{
		insert_dolog("fid=".$lastid."<br>f=".$add['f']);//操作日志
		printerror("AddFSuccess","db/AddF.php?enews=AddF&tid=$tid&tbname=$tbname".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改数据库字段
function EditF($add,$userid,$username){
	global $empire,$dbtbpre;
	$add=DoPostFVar($add);
	$tid=$add['tid'];
	$tbname=$add['tbname'];
	$add['fid']=(int)$add['fid'];
	if(empty($add['f'])||empty($add['fname'])||empty($add['fid'])||!$tid||!$tbname)
	{
		printerror("EmptyF","history.go(-1)");
	}
	CheckLevel($userid,$username,$classid,"f");//验证权限
	//是否系统内部字段
	$cr=$empire->fetch1("select * from {$dbtbpre}enewsf where fid='".$add['fid']."'");
	if(empty($cr['isadd']))
	{
		printerror("NotIsAdd","history.go(-1)");
	}
	CheckReTbF($add,1);//字段是否重复
	//存文本
	if($add['savetxt']==1&&!$cr['savetxt'])
	{
		$txtnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsf where savetxt=1 and fid<>".$add['fid']." and tid='$tid'");
		if($txtnum)
		{
			printerror('ReTxtF','');
		}
	}
	//分页
	if($add['ispage']==1&&!$cr['ispage'])
	{
		$pagenum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsf where ispage=1 and fid<>".$add['fid']." and tid='$tid'");
		if($pagenum)
		{
			printerror('RePageF','');
		}
	}
	$add['fvalue']=ReturnFvalue($add['fvalue']);//初始化值
	//改变字段
	if($cr['f']<>$add['f']||$add['iskey']<>$cr['iskey']||$cr['iscj']<>$add['iscj']||$cr['ftype']<>$add['ftype']||$cr['flen']<>$add['flen'])
	{
		$field=ReturnTbFtype($add);//返回字段
		//信息表
		if($cr['tbdataf']==1)//附加表
		{
			$tbr=$empire->fetch1("select datatbs from {$dbtbpre}enewstable where tid='$tid'");
			if($tbr['datatbs'])
			{
				$dtbr=explode(',',$tbr['datatbs']);
				$count=count($dtbr);
				for($i=1;$i<$count-1;$i++)
				{
					do_dbTableEditF($dbtbpre.'ecms_'.$tbname.'_data_'.$dtbr[$i],$add['f'],$add['oldf'],$add['ftype'],$add['flen'],'');//pgsql
					if($add['iskey']==1)//索引
					{
						if($cr['iskey']==0)
						{
							do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_data_'.$dtbr[$i],$add['f'],'');//pgsql
						}
					}
					elseif($cr['iskey']==1&&$add['iskey']==0)//删除索引
					{
						do_dbTableDelFIndex($dbtbpre.'ecms_'.$tbname.'_data_'.$dtbr[$i],$add['oldf'],'');//pgsql
					}
				}
			}
			//归档副表
			$usql=do_dbTableEditF($dbtbpre.'ecms_'.$tbname.'_doc_data',$add['f'],$add['oldf'],$add['ftype'],$add['flen'],'');//pgsql
			if($add['iskey']==1)//索引
			{
				if($cr['iskey']==0)
				{
					$keysql=do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_doc_data',$add['f'],'');//pgsql
				}
			}
			elseif($cr['iskey']==1&&$add['iskey']==0)//删除索引
			{
				$keysql=do_dbTableDelFIndex($dbtbpre.'ecms_'.$tbname.'_doc_data',$add['oldf'],'');//pgsql
			}
			//审核副表
			$usql=do_dbTableEditF($dbtbpre.'ecms_'.$tbname.'_check_data',$add['f'],$add['oldf'],$add['ftype'],$add['flen'],'');//pgsql
			if($add['iskey']==1)//索引
			{
				if($cr['iskey']==0)
				{
					$keysql=do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_check_data',$add['f'],'');//pgsql
				}
			}
			elseif($cr['iskey']==1&&$add['iskey']==0)//删除索引
			{
				$keysql=do_dbTableDelFIndex($dbtbpre.'ecms_'.$tbname.'_check_data',$add['oldf'],'');//pgsql
			}
		}
		else//主表
		{
			$usql=do_dbTableEditF($dbtbpre.'ecms_'.$tbname,$add['f'],$add['oldf'],$add['ftype'],$add['flen'],'');//pgsql
			if($add['iskey']==1)//索引
			{
				if($cr['iskey']==0)
				{
					$keysql=do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname,$add['f'],'');//pgsql
				}
			}
			elseif($cr['iskey']==1&&$add['iskey']==0)//删除索引
			{
				$keysql=do_dbTableDelFIndex($dbtbpre.'ecms_'.$tbname,$add['oldf'],'');//pgsql
			}
			//归档主表
			$usql=do_dbTableEditF($dbtbpre.'ecms_'.$tbname.'_doc',$add['f'],$add['oldf'],$add['ftype'],$add['flen'],'');//pgsql
			if($add['iskey']==1)//索引
			{
				if($cr['iskey']==0)
				{
					$keysql=do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_doc',$add['f'],'');//pgsql
				}
			}
			elseif($cr['iskey']==1&&$add['iskey']==0)//删除索引
			{
				$keysql=do_dbTableDelFIndex($dbtbpre.'ecms_'.$tbname.'_doc',$add['oldf'],'');//pgsql
			}
			//审核主表
			$usql=do_dbTableEditF($dbtbpre.'ecms_'.$tbname.'_check',$add['f'],$add['oldf'],$add['ftype'],$add['flen'],'');//pgsql
			if($add['iskey']==1)//索引
			{
				if($cr['iskey']==0)
				{
					$keysql=do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_check',$add['f'],'');//pgsql
				}
			}
			elseif($cr['iskey']==1&&$add['iskey']==0)//删除索引
			{
				$keysql=do_dbTableDelFIndex($dbtbpre.'ecms_'.$tbname.'_check',$add['oldf'],'');//pgsql
			}
		}
		//采集表
		if($add['iscj']==1)
		{
			if($cr['iscj']==1)
			{
				do_dbTableEditF($dbtbpre.'ecms_infotmp_'.$tbname,$add['f'],$add['oldf'],$add['ftype'],$add['flen'],'');//pgsql
				do_dbTableEditF($dbtbpre.'ecms_infoclass_'.$tbname,'zz_'.$add['f'],'zz_'.$add['oldf'],'TEXT','','');//pgsql
				do_dbTableEditF($dbtbpre.'ecms_infoclass_'.$tbname,'z_'.$add['f'],'z_'.$add['oldf'],'VARCHAR','255','');//pgsql
				do_dbTableEditF($dbtbpre.'ecms_infoclass_'.$tbname,'qz_'.$add['f'],'qz_'.$add['oldf'],'VARCHAR','255','');//pgsql
				do_dbTableEditF($dbtbpre.'ecms_infoclass_'.$tbname,'save_'.$add['f'],'save_'.$add['oldf'],'VARCHAR','10','');//pgsql
			}
			else
			{
				do_dbTableAddF($dbtbpre.'ecms_infoclass_'.$tbname,'zz_'.$add['f'],'TEXT','','');//pgsql
				do_dbTableAddF($dbtbpre.'ecms_infoclass_'.$tbname,'z_'.$add['f'],'VARCHAR','255','');//pgsql
				do_dbTableAddF($dbtbpre.'ecms_infoclass_'.$tbname,'qz_'.$add['f'],'VARCHAR','255','');//pgsql
				do_dbTableAddF($dbtbpre.'ecms_infoclass_'.$tbname,'save_'.$add['f'],'VARCHAR','10','');//pgsql
				do_dbTableAddF($dbtbpre.'ecms_infotmp_'.$tbname,$add['f'],$add['ftype'],$add['flen'],'');//pgsql
			}
		}
		elseif($add['iscj']==0&&$cr['iscj']==1)
		{
			do_dbTableDelF($dbtbpre.'ecms_infotmp_'.$tbname,$cr['f']);//pgsql
			do_dbTableDelF($dbtbpre.'ecms_infoclass_'.$tbname,'zz_'.$cr['f']);//pgsql
			do_dbTableDelF($dbtbpre.'ecms_infoclass_'.$tbname,'z_'.$cr['f']);//pgsql
			do_dbTableDelF($dbtbpre.'ecms_infoclass_'.$tbname,'qz_'.$cr['f']);//pgsql
			do_dbTableDelF($dbtbpre.'ecms_infoclass_'.$tbname,'save_'.$cr['f']);//pgsql
		}
	}
	//替换代码
	if($add['f']<>$cr['f']||$add['fform']<>$cr['fform']||$add['fvalue']<>$add['oldfvalue']||$cr['linkfieldtb']<>$add['linkfieldtb']||$cr['linkfieldshow']<>$add['linkfieldshow']||$cr['editorys']<>$add['editorys']||$add['linkfieldval']<>$cr['linkfieldval']||$add['fformsize']<>$cr['fformsize']||$add['fmvnum']<>$cr['fmvnum'])
	{
		$fhtml=GetFform($add['fform'],$add['f'],$add['fvalue'],$add['linkfieldval'],$add['fformsize'],$add);
	}
	else
	{
		$fhtml=$add['fhtml'];
	}
	$cjhtml=GetCjform($add['fform'],$add['f']);
	if($add['f']<>$cr['f']||$add['fform']<>$cr['fform']||$add['fvalue']<>$add['oldfvalue']||$cr['linkfieldtb']<>$add['linkfieldtb']||$cr['linkfieldshow']<>$add['linkfieldshow']||$cr['editorys']<>$add['editorys']||$add['linkfieldval']<>$cr['linkfieldval']||$add['fformsize']<>$cr['fformsize']||$add['fmvnum']<>$cr['fmvnum'])
	{
		$qfhtml=GetQFform($add['fform'],$add['f'],$add['fvalue'],$add['fformsize'],$add);
	}
	else
	{
		$qfhtml=$add['qfhtml'];
	}
	$sql=$empire->query("update {$dbtbpre}enewsf set f='".$add['f']."',fname='".$add['fname']."',fform='".$add['fform']."',fhtml='".eaddslashes2($fhtml)."',fzs='".eaddslashes($add['fzs'])."',iscj='".$add['iscj']."',cjhtml='".eaddslashes2($cjhtml)."',myorder='".$add['myorder']."',ftype='".$add['ftype']."',flen='".$add['flen']."',fvalue='".eaddslashes2($add['fvalue'])."',iskey='".$add['iskey']."',tobr='".$add['tobr']."',dohtml='".$add['dohtml']."',qfhtml='".eaddslashes2($qfhtml)."',isonly='".$add['isonly']."',linkfieldval='".$add['linkfieldval']."',samedata='".$add['samedata']."',fformsize='".$add['fformsize']."',ispage='".$add['ispage']."',adddofun='".$add['adddofun']."',editdofun='".$add['editdofun']."',qadddofun='".$add['qadddofun']."',qeditdofun='".$add['qeditdofun']."',linkfieldtb='".$add['linkfieldtb']."',linkfieldshow='".$add['linkfieldshow']."',editorys='".$add['editorys']."',issmalltext='".$add['issmalltext']."',fmvnum='".$add['fmvnum']."' where fid='".$add['fid']."'");
	TogSaveTxtF(1);//公共变量
	if($add['savetxt']==1&&$add['iscj']==1)
	{
		$tmpsql=do_dbTableEditF($dbtbpre.'ecms_infotmp_'.$tbname,$add['f'],$add['f'],'MEDIUMTEXT','','');//pgsql
	}
	//更新表单
	$record="<!--record-->";
    $field="<!--field--->";
	$like=$field.$add['oldf'].$record;
	$newlike=$field.$add['f'].$record;
	$slike=",".$add['oldf'].",";
	$newslike=",".$add['f'].",";
	$fsql=$empire->query("select mid,mtemp,cj,enter,tempvar,searchvar,tid,qenter,mustqenterf,qmtemp,listandf,listtempvar,canaddf,caneditf,orderf from {$dbtbpre}enewsmod where tid='$tid'");
	while($fr=$empire->fetch($fsql))
	{
		$and="";
		$enter=$fr['enter'];
		if($add['f']<>$add['oldf'])
		{
			//采集项
			if(strstr($fr['cj'],$like))
			{
				$cj=str_replace($like,$newlike,$fr['cj']);
				$and=",cj='$cj'";
				ChangeMCj($fr['mid'],$fr['tid'],$cj);
			}
			//录入项
			if(strstr($fr['enter'],$like))
			{
				$enter=str_replace($like,$newlike,$fr['enter']);
				$and.=",enter='$enter'";
			}
			//投稿项
			if(strstr($fr['qenter'],$like))
			{
				$qenter=str_replace($like,$newlike,$fr['qenter']);
				$and.=",qenter='$qenter'";
			}
			//内容模板项
			if(strstr($fr['tempvar'],$like))
			{
				$tempvar=str_replace($like,$newlike,$fr['tempvar']);
				$and.=",tempvar='$tempvar'";
			}
			//列表模板项
			if(strstr($fr['listtempvar'],$like))
			{
				$listtempvar=str_replace($like,$newlike,$fr['listtempvar']);
				$and.=",listtempvar='$listtempvar'";
			}
			//搜索项
			if(strstr($fr['searchvar'],$slike))
			{
				$searchvar=str_replace($slike,$newslike,$fr['searchvar']);
				$and.=",searchvar='$searchvar'";
			}
			//必填项
			if(strstr($fr['mustqenterf'],$slike))
			{
				$mustqenterf=str_replace($slike,$newslike,$fr['mustqenterf']);
				$and.=",mustqenterf='$mustqenterf'";
			}
			//结合项
			if(strstr($fr['listandf'],$slike))
			{
				$listandf=str_replace($slike,$newslike,$fr['listandf']);
				$and.=",listandf='$listandf'";
			}
			//排序项
			if(strstr($fr['orderf'],$slike))
			{
				$orderf=str_replace($slike,$newslike,$fr['orderf']);
				$and.=",orderf='$orderf'";
			}
			//可修改
			if(strstr($fr['caneditf'],$slike))
			{
				$caneditf=str_replace($slike,$newslike,$fr['caneditf']);
				$and.=",caneditf='$caneditf'";
			}
			//可增加
			if(strstr($fr['canaddf'],$slike))
			{
				$canaddf=str_replace($slike,$newslike,$fr['canaddf']);
				$and.=",canaddf='$canaddf'";
			}
			//表单模板
			if(strstr($fr['mtemp'],'[!--'.$add['oldf'].'--]'))
			{
				$fr['mtemp']=str_replace('[!--'.$add['oldf'].'--]','[!--'.$add['f'].'--]',$fr['mtemp']);
				$and.=",mtemp='".addslashes(stripSlashes($fr['mtemp']))."'";
			}
			//投稿表单模板
			if(strstr($fr['qmtemp'],'[!--'.$add['oldf'].'--]'))
			{
				$fr['qmtemp']=str_replace('[!--'.$add['oldf'].'--]','[!--'.$add['f'].'--]',$fr['qmtemp']);
				$and.=",qmtemp='".addslashes(stripSlashes($fr['qmtemp']))."'";
			}
			if($and)
			{
				$empire->query("update {$dbtbpre}enewsmod set mid='".$fr['mid']."'".$and." where mid='".$fr['mid']."'");
			}
		}
		ChangeMForm($fr['mid'],$fr['tid'],$fr['mtemp']);
		ChangeQmForm($fr['mid'],$fr['tid'],$fr['qmtemp']);
	}
	GetConfig(1);//更新缓存
	if($sql)
	{
		insert_dolog("fid=".$add['fid']."<br>f=".$add['f']);//操作日志
		printerror("EditFSuccess","db/ListF.php?tid=$tid&tbname=$tbname".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改数据表系统字段
function EditSysF($add,$userid,$username){
	global $empire,$dbtbpre;
	$tid=(int)$add['tid'];
	$tbname=RepPostVar($add['tbname']);
	$fid=(int)$add['fid'];
	$f=RepPostVar($add['f']);
	if(!$fid||!$tid||!$tbname||!$f||!$add['fname'])
	{
		printerror("EmptyF","history.go(-1)");
	}
	CheckLevel($userid,$username,$classid,"f");//验证权限
	$add['fname']=hRepPostStr($add['fname'],1);
	$add['fform']=hRepPostStr($add['fform'],1);
	$add['fzs']=hRepPostStr($add['fzs'],1);
	$add['ftype']=hRepPostStr($add['ftype'],1);
	$add['flen']=hRepPostStr($add['flen'],1);
	$add['linkfieldval']=hRepPostStr($add['linkfieldval'],1);
	$add['fformsize']=hRepPostStr($add['fformsize'],1);
	$add['adddofun']=hRepPostStr($add['adddofun'],0);
	$add['editdofun']=hRepPostStr($add['editdofun'],0);
	$add['qadddofun']=hRepPostStr($add['qadddofun'],0);
	$add['qeditdofun']=hRepPostStr($add['qeditdofun'],0);
	$add['linkfieldtb']=hRepPostStr($add['linkfieldtb'],1);
	$add['linkfieldshow']=hRepPostStr($add['linkfieldshow'],1);
	$add['fmvnum']=hRepPostStr($add['fmvnum'],1);
	$add['samedata']=(int)$add['samedata'];
	$doeditcjhtml=(int)$add['doeditcjhtml'];
	//字段
	$addupdate='';
	if($f=='title'||$f=='titlepic')
	{
		if(!empty($add['flen']))
		{
			$field=$f." ".$add['ftype']."(".$add['flen'].") NOT NULL default ''";
			//信息表
			do_dbTableEditF($dbtbpre.'ecms_'.$tbname,$f,$f,$add['ftype'],$add['flen'],'');//pgsql
			//归档表
			do_dbTableEditF($dbtbpre.'ecms_'.$tbname.'_doc',$f,$f,$add['ftype'],$add['flen'],'');//pgsql
			//审核表
			do_dbTableEditF($dbtbpre.'ecms_'.$tbname.'_check',$f,$f,$add['ftype'],$add['flen'],'');//pgsql
			//采集临时表
			do_dbTableEditF($dbtbpre.'ecms_infotmp_'.$tbname,$f,$f,$add['ftype'],$add['flen'],'');//pgsql
		}
		$addupdate=",ftype='".$add['ftype']."'";
	}
	//索引
	$iskey=(int)$add['iskey'];
	if($f=='title'||$f=='titlepic')
	{
		if($iskey==1)//索引
		{
			if($add['oldiskey']==0)
			{
				do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname,$f,'');//pgsql
				do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_doc',$f,'');//pgsql
				do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_check',$f,'');//pgsql
			}
		}
		elseif($add['oldiskey']==1&&$iskey==0)//删除索引
		{
			do_dbTableDelFIndex($dbtbpre.'ecms_'.$tbname,$f,'');//pgsql
			do_dbTableDelFIndex($dbtbpre.'ecms_'.$tbname.'_doc',$f,'');//pgsql
			do_dbTableDelFIndex($dbtbpre.'ecms_'.$tbname.'_check',$f,'');//pgsql
		}
	}
	//处理变量
	$add['isonly']=(int)$add['isonly'];
	$add['myorder']=(int)$add['myorder'];
	//替换代码
	if($add['fform']<>$add['oldfform']||$add['fvalue']<>$add['oldfvalue']||$add['oldlinkfieldtb']<>$add['linkfieldtb']||$add['oldlinkfieldshow']<>$add['linkfieldshow']||$add['linkfieldval']<>$add['oldlinkfieldval']||$add['fformsize']<>$add['oldfformsize'])
	{
		$fhtml=GetFform($add['fform'],$add['f'],$add['fvalue'],$add['linkfieldval'],$add['fformsize'],$add);
	}
	else
	{
		$fhtml=$add['fhtml'];
	}
	if($add['fform']<>$add['oldfform']||$add['fvalue']<>$add['oldfvalue']||$add['oldlinkfieldtb']<>$add['linkfieldtb']||$add['oldlinkfieldshow']<>$add['linkfieldshow']||$add['linkfieldval']<>$add['oldlinkfieldval']||$add['fformsize']<>$add['oldfformsize'])
	{
		$qfhtml=GetQFform($add['fform'],$add['f'],$add['fvalue'],$add['fformsize'],$add);
	}
	else
	{
		$qfhtml=$add['qfhtml'];
	}
	if($doeditcjhtml==1)
	{
		$sysfhtmlr=GetSysFCjform($f);
		$syscjhtml=$sysfhtmlr[2];
		$addupdate.=",cjhtml='".addslashes($syscjhtml)."'";
	}
	$sql=$empire->query("update {$dbtbpre}enewsf set fname='".$add['fname']."',fform='".$add['fform']."',fhtml='".eaddslashes2($fhtml)."',fzs='".eaddslashes($add['fzs'])."',myorder='".$add['myorder']."',flen='".$add['flen']."',fvalue='".eaddslashes2($add['fvalue'])."',iskey='".$iskey."',qfhtml='".eaddslashes2($qfhtml)."',isonly='".$add['isonly']."',linkfieldval='".$add['linkfieldval']."',samedata='".$add['samedata']."',fformsize='".$add['fformsize']."',adddofun='".$add['adddofun']."',editdofun='".$add['editdofun']."',qadddofun='".$add['qadddofun']."',qeditdofun='".$add['qeditdofun']."',linkfieldtb='".$add['linkfieldtb']."',linkfieldshow='".$add['linkfieldshow']."'".$addupdate." where fid='$fid'");
	TogSaveTxtF(1);//公共变量
	//更新表单
	$fsql=$empire->query("select mid,mtemp,tid,qmtemp from {$dbtbpre}enewsmod where tid='$tid'");
	while($fr=$empire->fetch($fsql))
	{
		ChangeMForm($fr['mid'],$fr['tid'],$fr['mtemp']);
		ChangeQmForm($fr['mid'],$fr['tid'],$fr['qmtemp']);
	}
	GetConfig(1);//更新缓存
	if($sql)
	{
		insert_dolog("fid=".$fid."<br>f=".$f);//操作日志
		printerror("EditFSuccess","db/EditSysF.php?tid=$tid&tbname=$tbname&fid=$fid".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除数据库字段
function DelF($fid,$tid,$tbname,$userid,$username){
	global $empire,$dbtbpre;
	$tid=(int)$tid;
	$tbname=RepPostVar($tbname);
	$fid=(int)$fid;
	if(empty($fid)||!$tid||!$tbname)
	{
		printerror("EmptyFid","history.go(-1)");
	}
	CheckLevel($userid,$username,$classid,"f");//验证权限
	//是否系统内部字段
	$cr=$empire->fetch1("select isadd,f,tbdataf,iscj from {$dbtbpre}enewsf where fid='$fid'");
	if(empty($cr['isadd']))
	{
		printerror("NotIsAdd","history.go(-1)");
	}
	//删除表字段
	if($cr['tbdataf']==1)
	{
		$tbr=$empire->fetch1("select datatbs from {$dbtbpre}enewstable where tid='$tid'");
		if($tbr['datatbs'])
		{
			$dtbr=explode(',',$tbr['datatbs']);
			$count=count($dtbr);
			for($i=1;$i<$count-1;$i++)
			{
				do_dbTableDelF($dbtbpre.'ecms_'.$tbname.'_data_'.$dtbr[$i],$cr['f']);//pgsql
			}
		}
		//归档副表
		do_dbTableDelF($dbtbpre.'ecms_'.$tbname.'_doc_data',$cr['f']);//pgsql
		//审核副表
		do_dbTableDelF($dbtbpre.'ecms_'.$tbname.'_check_data',$cr['f']);//pgsql
	}
	else
	{
		do_dbTableDelF($dbtbpre.'ecms_'.$tbname,$cr['f']);//pgsql
		do_dbTableDelF($dbtbpre.'ecms_'.$tbname.'_doc',$cr['f']);//pgsql
		do_dbTableDelF($dbtbpre.'ecms_'.$tbname.'_check',$cr['f']);//pgsql
	}
	//采集表字段
	if($cr['iscj']==1)
	{
		do_dbTableDelF($dbtbpre.'ecms_infotmp_'.$tbname,$cr['f']);//pgsql
		do_dbTableDelF($dbtbpre.'ecms_infoclass_'.$tbname,'zz_'.$cr['f']);//pgsql
		do_dbTableDelF($dbtbpre.'ecms_infoclass_'.$tbname,'z_'.$cr['f']);//pgsql
		do_dbTableDelF($dbtbpre.'ecms_infoclass_'.$tbname,'qz_'.$cr['f']);//pgsql
		do_dbTableDelF($dbtbpre.'ecms_infoclass_'.$tbname,'save_'.$cr['f']);//pgsql
	}
	$sql=$empire->query("delete from {$dbtbpre}enewsf where fid='$fid'");
	TogSaveTxtF(1);//公共变量
	//删除模型中字段项
	$record="<!--record-->";
	$field="<!--field--->";
	$like=$field.$cr['f'].$record;
	$slike=",".$cr['f'].",";
	$dsql=$empire->query("select mid,cj,enter,tempvar,searchvar,tid,qenter,mustqenterf,listandf,listtempvar,canaddf,caneditf,orderf from {$dbtbpre}enewsmod where tid='$tid' and (cj like '%".$like."%' or enter like '%".$like."%' or searchvar like '%".$slike."%' or tempvar like '%".$like."%' or listtempvar like '%".$like."%' or qenter like '%".$like."%' or mustqenterf like '%".$slike."%' or listandf like '%".$slike."%' or canaddf like '%".$slike."%' or caneditf like '%".$slike."%' or orderf like '%".$slike."%')");
	while($r=$empire->fetch($dsql))
	{
		$cj="";
		$enter="";
		$tempvar="";
		$listtempvar="";
		$searchvar="";
		$qenter="";
		$mustqenterf="";
		$listandf="";
		$orderf="";
		$canaddf="";
		$caneditf="";
		$re="";
		$re1="";
		$and="";
		$dh="";
		//采集
		if(strstr($r['cj'],$like))
		{
			$re=explode($record,$r['cj']);
			for($i=0;$i<count($re)-1;$i++)
			{
				if(strstr($re[$i].$record,$like))
				{continue;}
				$cj.=$re[$i].$record;
			}
			//更新采集表单
			ChangeMCj($r['mid'],$r['tid'],$cj);
			$and="cj='$cj'";
		}
		$dh="";
		//录入表单
		if(strstr($r['enter'],$like))
		{
			$re1=explode($record,$r['enter']);
			for($i=0;$i<count($re1)-1;$i++)
			{
				if(strstr($re1[$i].$record,$like))
				{continue;}
				$enter.=$re1[$i].$record;
			}
			if(!empty($and))
			{$dh=",";}
			$and.=$dh."enter='$enter'";
	    }
		$dh="";
		//投稿表单
		if(strstr($r['qenter'],$like))
		{
			$re1=explode($record,$r['qenter']);
			for($i=0;$i<count($re1)-1;$i++)
			{
				if(strstr($re1[$i].$record,$like))
				{continue;}
				$qenter.=$re1[$i].$record;
			}
			if(!empty($and))
			{$dh=",";}
			$and.=$dh."qenter='$qenter'";
	    }
		$dh="";
		//内容模板变量
		if(strstr($r['tempvar'],$like))
		{
			$re1=explode($record,$r['tempvar']);
			for($i=0;$i<count($re1)-1;$i++)
			{
				if(strstr($re1[$i].$record,$like))
				{continue;}
				$tempvar.=$re1[$i].$record;
			}
			if(!empty($and))
			{$dh=",";}
			$and.=$dh."tempvar='$tempvar'";
	    }
		$dh="";
		//列表模板变量
		if(strstr($r['listtempvar'],$like))
		{
			$re1=explode($record,$r['listtempvar']);
			for($i=0;$i<count($re1)-1;$i++)
			{
				if(strstr($re1[$i].$record,$like))
				{continue;}
				$listtempvar.=$re1[$i].$record;
			}
			if(!empty($and))
			{$dh=",";}
			$and.=$dh."listtempvar='$listtempvar'";
	    }
		$dh="";
		//搜索变量
		if(strstr($r['searchvar'],$slike))
		{
			if(!empty($and))
			{$dh=",";}
			$searchvar=str_replace($slike,",",$r['searchvar']);
		    $and.=$dh."searchvar='$searchvar'";
		}
		//必填项
		$dh="";
		if(strstr($r['mustqenterf'],$slike))
		{
			if(!empty($and))
			{$dh=",";}
			$mustqenterf=str_replace($slike,",",$r['mustqenterf']);
		    $and.=$dh."mustqenterf='$mustqenterf'";
		}
		//可增可修改项
		$dh="";
		if(strstr($r['canaddf'],$slike))
		{
			if(!empty($and))
			{$dh=",";}
			$canaddf=str_replace($slike,",",$r['canaddf']);
		    $and.=$dh."canaddf='$canaddf'";
		}
		$dh="";
		if(strstr($r['caneditf'],$slike))
		{
			if(!empty($and))
			{$dh=",";}
			$caneditf=str_replace($slike,",",$r['caneditf']);
		    $and.=$dh."caneditf='$caneditf'";
		}
		//结合项
		$dh="";
		if(strstr($r['listandf'],$slike))
		{
			if(!empty($and))
			{$dh=",";}
			$listandf=str_replace($slike,",",$r['listandf']);
		    $and.=$dh."listandf='$listandf'";
		}
		//排序项
		$dh="";
		if(strstr($r['orderf'],$slike))
		{
			if(!empty($and))
			{$dh=",";}
			$orderf=str_replace($slike,",",$r['orderf']);
		    $and.=$dh."orderf='$orderf'";
		}
		if($and)
		{
			$empire->query("update {$dbtbpre}enewsmod set ".$and." where mid='".$r['mid']."'");
		}
    }
	GetConfig(1);//更新缓存
    if($sql)
	{
		//操作日志
		insert_dolog("fid=".$fid."<br>f=".$cr['f']);
		printerror("DelFSuccess","db/ListF.php?tid=$tid&tbname=$tbname".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改字段顺序
function EditFOrder($fid,$myorder,$tid,$tbname,$userid,$username){
	global $empire,$dbtbpre;
	$tid=(int)$tid;
	$tbname=RepPostVar($tbname);
	//验证权限
	CheckLevel($userid,$username,$classid,"f");
	$myorder=eCheckEmptyArray($myorder);
	for($i=0;$i<count($myorder);$i++)
	{
		$newmyorder=(int)$myorder[$i];
		$fid[$i]=(int)$fid[$i];
		$usql=$empire->query("update {$dbtbpre}enewsf set myorder='$newmyorder' where fid='".$fid[$i]."'");
    }
	printerror("EditFOrderSuccess","db/ListF.php?tid=$tid&tbname=$tbname".hReturnEcmsHashStrHref2(0));
}

//转移字段
function ChangeDataTableF($add,$userid,$username){
	global $empire,$dbtbpre,$public_r,$fun_r;
	//验证权限
	CheckLevel($userid,$username,$classid,"f");
	$fid=(int)$add['fid'];
	$tid=(int)$add['tid'];
	$tbname=RepPostVar($add['tbname']);
	$line=(int)$add['line'];
	$start=(int)$add['start'];
	if(!$fid||!$tid||!$tbname)
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	if(empty($line))
	{
		$line=200;
	}
	$fr=$empire->fetch1("select * from {$dbtbpre}enewsf where fid='$fid'");
	if(!$fr['fid'])
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	if(empty($fr['isadd']))
	{
		printerror("NotIsAdd","history.go(-1)");
	}
	$tid=$fr['tid'];
	$tbname=$fr['tbname'];
	$f=$fr['f'];
	//建字段
	if(empty($start))
	{
		$field=ReturnTbFtype($fr);//返回字段
		if($fr['tbdataf'])//转移到主表
		{
			do_dbTableAddF($dbtbpre.'ecms_'.$tbname,$fr['f'],$fr['ftype'],$fr['flen'],'');//pgsql
			if($fr['iskey']==1)//索引
			{
				do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname,$fr['f'],'');//pgsql
			}
			//归档主表
			do_dbTableAddF($dbtbpre.'ecms_'.$tbname.'_doc',$fr['f'],$fr['ftype'],$fr['flen'],'');//pgsql
			if($fr['iskey']==1)//索引
			{
				do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_doc',$fr['f'],'');//pgsql
			}
			//审核主表
			do_dbTableAddF($dbtbpre.'ecms_'.$tbname.'_check',$fr['f'],$fr['ftype'],$fr['flen'],'');//pgsql
			if($fr['iskey']==1)//索引
			{
				do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_check',$fr['f'],'');//pgsql
			}
		}
		else//转移到副表
		{
			$tbr=$empire->fetch1("select datatbs from {$dbtbpre}enewstable where tid='$tid'");
			if($tbr['datatbs'])
			{
				$dtbr=explode(',',$tbr['datatbs']);
				$count=count($dtbr);
				for($i=1;$i<$count-1;$i++)
				{
					do_dbTableAddF($dbtbpre.'ecms_'.$tbname.'_data_'.$dtbr[$i],$fr['f'],$fr['ftype'],$fr['flen'],'');//pgsql
					if($fr['iskey']==1)//索引
					{
						do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_data_'.$dtbr[$i],$fr['f'],'');//pgsql
					}
				}
			}
			//归档副表
			do_dbTableAddF($dbtbpre.'ecms_'.$tbname.'_doc_data',$fr['f'],$fr['ftype'],$fr['flen'],'');//pgsql
			if($fr['iskey']==1)//索引
			{
				do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_doc_data',$fr['f'],'');//pgsql
			}
			//审核副表
			do_dbTableAddF($dbtbpre.'ecms_'.$tbname.'_check_data',$fr['f'],$fr['ftype'],$fr['flen'],'');//pgsql
			if($fr['iskey']==1)//索引
			{
				do_dbTableAddFIndex($dbtbpre.'ecms_'.$tbname.'_check_data',$fr['f'],'');//pgsql
			}
		}
	}
	$selectf='';
	if(empty($fr['tbdataf']))
	{
		$selectf=','.$fr['f'];
	}
	$b=0;
	$sql=$empire->query("select id,checked from {$dbtbpre}ecms_".$tbname."_index where id>$start order by id".do_dblimit($line));
	while($r=$empire->fetch($sql))
	{
		$b=1;
		$newstart=$r['id'];
		//表名
		$infotb=ReturnInfoMainTbname($tbname,$r['checked']);
		$infor=$empire->fetch1("select stb".$selectf." from ".$infotb." where id='".$r['id']."'");
		$infodatatb=ReturnInfoDataTbname($tbname,$r['checked'],$infor['stb']);
		if($fr['tbdataf'])//副表
		{
			$finfor=$empire->fetch1("select ".$f." from ".$infodatatb." where id='".$r['id']."'");
			$value=$finfor[$f];
			$empire->query("update ".$infotb." set ".$f."='".StripAddsData($value)."' where id='".$r['id']."'");
		}
		else//主表
		{
			$value=$infor[$f];
			$empire->query("update ".$infodatatb." set ".$f."='".StripAddsData($value)."' where id='".$r['id']."'");
		}
	}
	if(empty($b))
	{
		echo"<link rel=\"stylesheet\" href=\"../data/images/css.css\" type=\"text/css\"><meta http-equiv=\"refresh\" content=\"".$public_r['realltime'].";url=ecmsmod.php?enews=ChangeDocDataTableF&tid=$tid&tbname=$tbname&fid=$fid&line=$line".hReturnEcmsHashStrHref(0).heformhash_get('ChangeDocDataTableF',1)."\">".$fun_r['AllChangeDataTableFSuccess'];
		exit();
	}
	echo"<link rel=\"stylesheet\" href=\"../data/images/css.css\" type=\"text/css\"><meta http-equiv=\"refresh\" content=\"".$public_r['realltime'].";url=ecmsmod.php?enews=ChangeDataTableF&tid=$tid&tbname=$tbname&fid=$fid&line=$line&start=$newstart".hReturnEcmsHashStrHref(0).heformhash_get('ChangeDataTableF',1)."\">".$fun_r['OneChangeDataTableFSuccess']."(ID:<font color=red><b>".$newstart."</b></font>)";
	exit();
}

//转移字段(归档)
function ChangeDocDataTableF($add,$userid,$username){
	global $empire,$dbtbpre,$public_r,$fun_r;
	//验证权限
	CheckLevel($userid,$username,$classid,"f");
	$fid=(int)$add['fid'];
	$tid=(int)$add['tid'];
	$tbname=RepPostVar($add['tbname']);
	$line=(int)$add['line'];
	$start=(int)$add['start'];
	if(!$fid||!$tid||!$tbname)
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	if(empty($line))
	{
		$line=200;
	}
	$fr=$empire->fetch1("select * from {$dbtbpre}enewsf where fid='$fid'");
	if(!$fr['fid'])
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	if(empty($fr['isadd']))
	{
		printerror("NotIsAdd","history.go(-1)");
	}
	$tid=$fr['tid'];
	$tbname=$fr['tbname'];
	$f=$fr['f'];
	$selectf='';
	if(empty($fr['tbdataf']))
	{
		$selectf=','.$fr['f'];
	}
	$b=0;
	$sql=$empire->query("select id,stb".$selectf." from {$dbtbpre}ecms_".$tbname."_doc where id>$start order by id".do_dblimit($line));
	while($r=$empire->fetch($sql))
	{
		$b=1;
		$newstart=$r['id'];
		if($fr['tbdataf'])//副表
		{
			$finfor=$empire->fetch1("select ".$f." from {$dbtbpre}ecms_".$tbname."_doc_data where id='".$r['id']."'");
			$value=$finfor[$f];
			$empire->query("update {$dbtbpre}ecms_".$tbname."_doc set ".$f."='".StripAddsData($value)."' where id='".$r['id']."'");
		}
		else//主表
		{
			$value=$r[$f];
			$empire->query("update {$dbtbpre}ecms_".$tbname."_doc_data set ".$f."='".StripAddsData($value)."' where id='".$r['id']."'");
		}
	}
	if(empty($b))
	{
		//删除字段
		if($fr['tbdataf'])//转移到主表
		{
			$tbr=$empire->fetch1("select datatbs from {$dbtbpre}enewstable where tid='$tid'");
			if($tbr['datatbs'])
			{
				$dtbr=explode(',',$tbr['datatbs']);
				$count=count($dtbr);
				for($i=1;$i<$count-1;$i++)
				{
					do_dbTableDelF($dbtbpre.'ecms_'.$tbname.'_data_'.$dtbr[$i],$fr['f']);//pgsql
				}
			}
			//归档副表
			do_dbTableDelF($dbtbpre.'ecms_'.$tbname.'_doc_data',$fr['f']);//pgsql
			//审核副表
			do_dbTableDelF($dbtbpre.'ecms_'.$tbname.'_check_data',$fr['f']);//pgsql
		}
		else//转移到副表
		{
			do_dbTableDelF($dbtbpre.'ecms_'.$tbname,$fr['f']);//pgsql
			do_dbTableDelF($dbtbpre.'ecms_'.$tbname.'_doc',$fr['f']);//pgsql
			do_dbTableDelF($dbtbpre.'ecms_'.$tbname.'_check',$fr['f']);//pgsql
		}
		$newtbdataf=$fr['tbdataf']?0:1;
		$empire->query("update {$dbtbpre}enewsf set tbdataf='$newtbdataf' where fid='$fid'");
		//删除模型中字段项
		if(empty($tbr['datatbs']))
		{
			$record="<!--record-->";
			$field="<!--field--->";
			$like=$field.$fr['f'].$record;
			$slike=",".$fr['f'].",";
			$dsql=$empire->query("select mid,searchvar,listandf,listtempvar,orderf from {$dbtbpre}enewsmod where tid='$tid' and (searchvar like '%".$slike."%' or listtempvar like '%".$like."%' or listandf like '%".$slike."%' or orderf like '%".$slike."%')");
			while($r=$empire->fetch($dsql))
			{
				$listtempvar="";
				$searchvar="";
				$listandf="";
				$orderf="";
				$re="";
				$re1="";
				$and="";
				$dh="";
				//列表模板变量
				if(strstr($r['listtempvar'],$like))
				{
					$re1=explode($record,$r['listtempvar']);
					for($i=0;$i<count($re1)-1;$i++)
					{
						if(strstr($re1[$i].$record,$like))
						{continue;}
						$listtempvar.=$re1[$i].$record;
					}
					$and.=$dh."listtempvar='$listtempvar'";
				}
				$dh="";
				//搜索变量
				if(strstr($r['searchvar'],$slike))
				{
					if(!empty($and))
					{$dh=",";}
					$searchvar=str_replace($slike,",",$r['searchvar']);
					$and.=$dh."searchvar='$searchvar'";
				}
				//结合项
				$dh="";
				if(strstr($r['listandf'],$slike))
				{
					if(!empty($and))
					{$dh=",";}
					$listandf=str_replace($slike,",",$r['listandf']);
					$and.=$dh."listandf='$listandf'";
				}
				//排序项
				$dh="";
				if(strstr($r['orderf'],$slike))
				{
					if(!empty($and))
					{$dh=",";}
					$orderf=str_replace($slike,",",$r['orderf']);
					$and.=$dh."orderf='$orderf'";
				}
				if($and)
				{
					$empire->query("update {$dbtbpre}enewsmod set ".$and." where mid='".$r['mid']."'");
				}
			}
		}
		GetConfig(1);//更新缓存
		insert_dolog("tid=$tid&tbname=$tbname<br>fid=$fid&field=$f&tbdataf=".$newtbdataf);//操作日志
		printerror("ChangeDataTableFSuccess","db/ListF.php?tid=$tid&tbname=$tbname".hReturnEcmsHashStrHref2(0));
	}
	echo"<link rel=\"stylesheet\" href=\"../data/images/css.css\" type=\"text/css\"><meta http-equiv=\"refresh\" content=\"".$public_r['realltime'].";url=ecmsmod.php?enews=ChangeDocDataTableF&tid=$tid&tbname=$tbname&fid=$fid&line=$line&start=$newstart".hReturnEcmsHashStrHref(0).heformhash_get('ChangeDocDataTableF',1)."\">".$fun_r['OneChangeDocDataTableFSuccess']."(ID:<font color=red><b>".$newstart."</b></font>)";
	exit();
}


//************************** 模型 **************************

//更新默认系统模型
function UpdateTbDefMod($tid,$tbname,$mid){
	global $empire,$dbtbpre;
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsmod where tid='$tid'");
	if($num==1)
	{
		$empire->query("update {$dbtbpre}enewstable set mid='$mid' where tid='$tid'");
		$empire->query("update {$dbtbpre}enewsmod set isdefault=1 where mid='$mid'");
	}
}

//更新模型表单
function ChangeMForm($mid,$tid,$mtemp){
	global $empire,$dbtbpre;
	$mid=(int)$mid;
	$tid=(int)$tid;
	$file=eReturnTrueEcmsPath()."c/ecachemod/emodform/".$mid.".php";
	$sql=$empire->query("select f,fhtml from {$dbtbpre}enewsf where tid='$tid'");
	while($r=$empire->fetch($sql))
	{
		$mtemp=str_replace("[!--".$r['f']."--]",$r['fhtml'],$mtemp);
    }
	$mtemp=AddCheckViewTempCode().$mtemp;
	WriteFiletext($file,$mtemp);
}

//更新投稿表单
function ChangeQmForm($mid,$tid,$mtemp){
	global $empire,$dbtbpre;
	$mid=(int)$mid;
	$tid=(int)$tid;
	$file=eReturnTrueEcmsPath()."c/ecachemod/emodform/q".$mid.".php";
	$sql=$empire->query("select f,qfhtml from {$dbtbpre}enewsf where tid='$tid'");
	while($r=$empire->fetch($sql))
	{
		$mtemp=str_replace("[!--".$r['f']."--]",$r['qfhtml'],$mtemp);
    }
	$mtemp=AddCheckViewTempCode().$mtemp;
	WriteFiletext($file,$mtemp);
}

//更新采集
function ChangeMCj($mid,$tid,$cj){
	global $empire,$dbtbpre;
	$mid=(int)$mid;
	$tid=(int)$tid;
	$record="<!--record-->";
	$field="<!--field--->";
	//读取修改采集表单
	$data="<tr><td bgcolor=ffffff>[!--enews.name--]</td><td bgcolor=ffffff>[!--enews.var--]</td></tr>";
	$file1=eReturnTrueEcmsPath()."c/ecachemod/emodform/editcj".$mid.".php";
	$file=eReturnTrueEcmsPath()."c/ecachemod/emodform/cj".$mid.".php";
	$r=explode($record,$cj);
	for($i=0;$i<count($r)-1;$i++)
	{
		$r1=explode($field,$r[$i]);
		$fr=$empire->fetch1("select cjhtml,fhtml from {$dbtbpre}enewsf where f='".$r1[1]."' and tid='$tid'".do_dblimit_one());
		$cjtemp=str_replace("[!--enews.name--]",$r1[0],$fr['cjhtml']);
		$str.=$cjtemp;
		$editcjtemp=str_replace("[!--enews.name--]",$r1[0],$data);
		$editcjtemp=str_replace("[!--enews.var--]",$fr['fhtml'],$editcjtemp);
		$editcj.=$editcjtemp;
	}
	WriteFiletext($file,AddCheckViewTempCode().$str);
	WriteFiletext($file1,AddCheckViewTempCode().$editcj);
}

//组合采集项
function TogMCj($cname,$cchange){
	$cchange=eCheckEmptyArray($cchange);
	$record="<!--record-->";
	$field="<!--field--->";
	$c="";
	for($i=0;$i<count($cchange);$i++)
	{
		$v=$cchange[$i];
		$name=str_replace($field,"",$cname[$v]);
		$name=str_replace($record,"",$name);
		$c.=$name.$field.$v.$record;
	}
	return $c;
}

//组合投稿项
function TogMqenter($cname,$cqenter){
	$cqenter=eCheckEmptyArray($cqenter);
	$record="<!--record-->";
	$field="<!--field--->";
	$c="";
	for($i=0;$i<count($cqenter);$i++)
	{
		$v=$cqenter[$i];
		$name=str_replace($field,"",$cname[$v]);
		$name=str_replace($record,"",$name);
		$c.=$name.$field.$v.$record;
	}
	return $c;
}

//组合搜索项
function TogMSearch($cname,$schange){
	$schange=eCheckEmptyArray($schange);
	$c="";
	for($i=0;$i<count($schange);$i++)
	{
		$v=$schange[$i];
		$c.=$v.",";
	}
	if($c)
	{
		$c=",".$c;
	}
	return $c;
}

//组合必填项
function TogMustf($cname,$menter){
	$menter=eCheckEmptyArray($menter);
	$c="";
	for($i=0;$i<count($menter);$i++)
	{
		$v=$menter[$i];
		$c.=$v.",";
	}
	if($c)
	{
		$c=",".$c;
	}
	return $c;
}

//组合录入项
function TogMEnter($cname,$center,$ltempf,$ptempf,$tid){
	global $empire;
	$center=eCheckEmptyArray($center);
	$ltempf=eCheckEmptyArray($ltempf);
	$ptempf=eCheckEmptyArray($ptempf);
	$f=1;
	$record="<!--record-->";
	$field="<!--field--->";
	$c="";
	$lt="";
	$pt="";
	for($i=0;$i<count($center);$i++)
	{
		$v=$center[$i];
		$name=str_replace($field,"",$cname[$v]);
		$name=str_replace($record,"",$name);
		$c.=$name.$field.$v.$record;
	}
	for($i=0;$i<count($ltempf);$i++)
	{
		$v=$ltempf[$i];
		$name=str_replace($field,"",$cname[$v]);
		$name=str_replace($record,"",$name);
		$lt.=$name.$field.$v.$record;
	}
	for($i=0;$i<count($ptempf);$i++)
	{
		$v=$ptempf[$i];
		$name=str_replace($field,"",$cname[$v]);
		$name=str_replace($record,"",$name);
		$pt.=$name.$field.$v.$record;
	}
	$r[0]=$c;
	$r[1]=$lt;
	$r[2]=$pt;
	return $r;
}

//返回自动生成录入表单模板
function ReturnMtemp($cname,$center){
	$center=eCheckEmptyArray($center);
	$temp="<tr><td width='16%' height=25 bgcolor='ffffff'>enews.name</td><td bgcolor='ffffff'>[!--enews.var--]</td></tr>";
	$ntemp="<tr><td height=25 colspan=2 bgcolor='ffffff'><div align=left>enews.name</div></td></tr></table><div style='background-color:#D0D0D0'>[!--enews.var--]</div><table width='100%' align=center cellpadding=3 cellspacing=1 bgcolor='#DBEAF5'>";
	for($i=0;$i<count($center);$i++)
	{
		$v=$center[$i];
		if($v=="newstext")
		{
			$data.=str_replace("enews.var",$v,str_replace("enews.name",$cname[$v],$ntemp));
			continue;
		}
		$data.=str_replace("enews.var",$v,str_replace("enews.name",$cname[$v],$temp));
    }
	return "<table width='100%' align=center cellpadding=3 cellspacing=1 bgcolor='#DBEAF5'>".$data."</table>";
}

//返回自动生成投稿表单模板
function ReturnQmtemp($cname,$cqenter){
	$cqenter=eCheckEmptyArray($cqenter);
	$temp="<tr><td width='16%' height=25 bgcolor='ffffff'>enews.name</td><td bgcolor='ffffff'>[!--enews.var--]</td></tr>";
	$ntemp="<tr><td height=25 colspan=2 bgcolor='ffffff'><div align=left>enews.name</div></td></tr></table><div style='background-color:#D0D0D0'>[!--enews.var--]</div><table width='100%' align=center cellpadding=3 cellspacing=1 bgcolor='#DBEAF5'>";
	for($i=0;$i<count($cqenter);$i++)
	{
		$v=$cqenter[$i];
		if($v=="newstext")
		{
			$data.=str_replace("enews.var",$v,str_replace("enews.name",$cname[$v],$ntemp));
			continue;
		}
		$data.=str_replace("enews.var",$v,str_replace("enews.name",$cname[$v],$temp));
    }
	return "<table width=100% align=center cellpadding=3 cellspacing=1 bgcolor=#DBEAF5>".$data."</table>";
}

//返回br项
function ReturnMTobrF($enter,$tid,$dof="tobr"){
	global $empire,$dbtbpre;
	$record="<!--record-->";
	$field="<!--field--->";
	$f=",";
	$sql=$empire->query("select f from {$dbtbpre}enewsf where ".$dof."=0 and tid='$tid'");
	while($r=$empire->fetch($sql))
	{
		if(strstr($enter,$field.$r['f'].$record))
		{
			$f.=$r['f'].",";
		}
	}
	return $f;
}

//增加模型
function AddM($add,$cname,$cchange,$schange,$center,$cqenter,$menter,$listand,$ltempf,$ptempf,$canadd,$canedit,$listorder,$userid,$username){
	global $empire,$dbtbpre;
	$tid=(int)$add['tid'];
	$tbname=RepPostVar($add['tbname']);
	if(empty($add['mname'])||!$tid||!$tbname)
	{
		printerror("EmptyM","history.go(-1)");
	}
	$add['listfile']=(int)$add['listfile'];
	if(!$add['listfile'])
	{
		$add['listfile']='';
	}
	$listfile=eReturnCPath(str_replace('.','',$add['listfile']),'');
	CheckLevel($userid,$username,$classid,"m");//验证权限
	//组合采集项
	$cj=TogMCj($cname,$cchange);
	//组合搜索项
    $searchvar=TogMSearch($cname,$schange);
	//组合必填项
	$mustqenterf=TogMustf($cname,$menter);
	//组合结合项
	$listandf=TogMustf($cname,$listand);
	//组合排序项
	$orderf=TogMustf($cname,$listorder);
	//组合投稿项
	$qenter=TogMqenter($cname,$cqenter);
	//组合可增加项
	$canaddf=TogMustf($cname,$canadd);
	//组合可修改项
	$caneditf=TogMustf($cname,$canedit);
	//组合录入项
    $er=TogMEnter($cname,$center,$ltempf,$ptempf,$tid);
    $enter=$er[0];	//录入项
	$listtempvar=$er[1];	//列表模板项
	$tempvar=$er[2];	//内容模板项
	//自动生成表单
	if($add['mtype'])
	{
		$add['mtemp']=ReturnMtemp($cname,$center);
	}
	if($add['qmtype'])
	{
		$add['qmtemp']=ReturnQmtemp($cname,$cqenter);
	}
	$setandf=(int)$add['setandf'];
	$add['definfovoteid']=(int)$add['definfovoteid'];
	$showmod=(int)$add['showmod'];
	$usemod=(int)$add['usemod'];
	$myorder=(int)$add['myorder'];
	$add['printtempid']=(int)$add['printtempid'];
	$add['mname']=hRepPostStr($add['mname'],1);
	$add['mzs']=hRepPostStr($add['mzs'],1);
	$cj=AddAddsData($cj);
	$enter=AddAddsData($enter);
	$tempvar=AddAddsData($tempvar);
	$searchvar=AddAddsData($searchvar);
	$qenter=AddAddsData($qenter);
	$mustqenterf=AddAddsData($mustqenterf);
	$listtempvar=AddAddsData($listtempvar);
	$add['qmname']=hRepPostStr($add['qmname'],0);
	$canaddf=AddAddsData($canaddf);
	$caneditf=AddAddsData($caneditf);
	$orderf=AddAddsData($orderf);
	$listfile=AddAddsData($listfile);
	$listfile=(int)$listfile;
	$listfile=$listfile?$listfile:'';
	$add['maddfun']=hRepPostStr($add['maddfun'],0);
	$add['meditfun']=hRepPostStr($add['meditfun'],0);
	$add['qmaddfun']=hRepPostStr($add['qmaddfun'],0);
	$add['qmeditfun']=hRepPostStr($add['qmeditfun'],0);
	$sql=$empire->updatesql("insert into {$dbtbpre}enewsmod(mname,mtemp,mzs,cj,enter,tempvar,sonclass,searchvar,tid,tbname,qenter,mustqenterf,qmtemp,listandf,setandf,listtempvar,qmname,canaddf,caneditf,definfovoteid,showmod,usemod,myorder,orderf,isdefault,listfile,printtempid,maddfun,meditfun,qmaddfun,qmeditfun) values('".$add['mname']."','".eaddslashes2($add['mtemp'])."','".$add['mzs']."','".$cj."','".$enter."','".$tempvar."','','".$searchvar."','".$tid."','".$tbname."','".$qenter."','".$mustqenterf."','".eaddslashes2($add['qmtemp'])."','".addslashes($listandf)."','".$setandf."','".$listtempvar."','".$add['qmname']."','".$canaddf."','".$caneditf."','".$add['definfovoteid']."','".$showmod."','".$usemod."','".$myorder."','".$orderf."',0,'".$listfile."','".$add['printtempid']."','".$add['maddfun']."','".$add['meditfun']."','".$add['qmaddfun']."','".$add['qmeditfun']."');","ins");
	$mid=$empire->lastid($dbtbpre.'enewsmod','mid');
	UpdateTbDefMod($tid,$tbname,$mid);
	//更新表单
	ChangeMForm($mid,$tid,$add['mtemp']);
	ChangeQmForm($mid,$tid,$add['qmtemp']);
	//采集文件
	ChangeMCj($mid,$tid,$cj);
	GetConfig(1);//更新缓存
    if($sql)
	{
	    insert_dolog("mid=".$mid."<br>m=".$add['mname']);//操作日志
		printerror("AddMSuccess","db/ListM.php?tid=$tid&tbname=$tbname".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改模型
function EditM($add,$cname,$cchange,$schange,$center,$cqenter,$menter,$listand,$ltempf,$ptempf,$canadd,$canedit,$listorder,$userid,$username){
	global $empire,$dbtbpre;
	$tid=(int)$add['tid'];
	$tbname=RepPostVar($add['tbname']);
	$add['mid']=(int)$add['mid'];
	if(empty($add['mname'])||empty($add['mid'])||!$tid||!$tbname)
	{
		printerror("EmptyM","history.go(-1)");
	}
	$add['listfile']=(int)$add['listfile'];
	if(!$add['listfile'])
	{
		$add['listfile']='';
	}
	$listfile=eReturnCPath(str_replace('.','',$add['listfile']),'');
	//验证权限
	CheckLevel($userid,$username,$classid,"m");
	//组合采集项
	$cj=TogMCj($cname,$cchange);
	//组合搜索项
    $searchvar=TogMSearch($cname,$schange);
	//组合必填项
	$mustqenterf=TogMustf($cname,$menter);
	//组合结合项
	$listandf=TogMustf($cname,$listand);
	//组合排序项
	$orderf=TogMustf($cname,$listorder);
	//组合投稿项
	$qenter=TogMqenter($cname,$cqenter);
	//组合可增加项
	$canaddf=TogMustf($cname,$canadd);
	//组合可修改项
	$caneditf=TogMustf($cname,$canedit);
	//组合录入项
	$er=TogMEnter($cname,$center,$ltempf,$ptempf,$tid);
    $enter=$er[0];	//录入项
	$listtempvar=$er[1];	//列表模板项
	$tempvar=$er[2];	//内容模板项
	//自动生成表单
	if($add['mtype'])
	{
		$add['mtemp']=ReturnMtemp($cname,$center);
	}
	if($add['qmtype'])
	{
		$add['qmtemp']=ReturnQmtemp($cname,$cqenter);
	}
	$setandf=(int)$add['setandf'];
	$add['definfovoteid']=(int)$add['definfovoteid'];
	$showmod=(int)$add['showmod'];
	$usemod=(int)$add['usemod'];
	$myorder=(int)$add['myorder'];
	$add['printtempid']=(int)$add['printtempid'];
	$add['mname']=hRepPostStr($add['mname'],1);
	$add['mzs']=hRepPostStr($add['mzs'],1);
	$cj=AddAddsData($cj);
	$enter=AddAddsData($enter);
	$tempvar=AddAddsData($tempvar);
	$searchvar=AddAddsData($searchvar);
	$qenter=AddAddsData($qenter);
	$mustqenterf=AddAddsData($mustqenterf);
	$listtempvar=AddAddsData($listtempvar);
	$add['qmname']=hRepPostStr($add['qmname'],0);
	$canaddf=AddAddsData($canaddf);
	$caneditf=AddAddsData($caneditf);
	$orderf=AddAddsData($orderf);
	$listfile=AddAddsData($listfile);
	$listfile=(int)$listfile;
	$listfile=$listfile?$listfile:'';
	$add['maddfun']=hRepPostStr($add['maddfun'],0);
	$add['meditfun']=hRepPostStr($add['meditfun'],0);
	$add['qmaddfun']=hRepPostStr($add['qmaddfun'],0);
	$add['qmeditfun']=hRepPostStr($add['qmeditfun'],0);
	$sql=$empire->query("update {$dbtbpre}enewsmod set mname='".$add['mname']."',mtemp='".eaddslashes2($add['mtemp'])."',mzs='".$add['mzs']."',cj='".$cj."',enter='".$enter."',tempvar='".$tempvar."',searchvar='".$searchvar."',qenter='".$qenter."',mustqenterf='".$mustqenterf."',qmtemp='".eaddslashes2($add['qmtemp'])."',listandf='".addslashes($listandf)."',setandf='".$setandf."',listtempvar='".$listtempvar."',qmname='".$add['qmname']."',canaddf='".$canaddf."',caneditf='".$caneditf."',definfovoteid='".$add['definfovoteid']."',showmod='".$showmod."',usemod='".$usemod."',myorder='".$myorder."',orderf='".$orderf."',listfile='".$listfile."',printtempid='".$add['printtempid']."',maddfun='".$add['maddfun']."',meditfun='".$add['meditfun']."',qmaddfun='".$add['qmaddfun']."',qmeditfun='".$add['qmeditfun']."' where mid='".$add['mid']."'");
	//更新表单
	ChangeMForm($add['mid'],$tid,$add['mtemp']);
	ChangeQmForm($add['mid'],$tid,$add['qmtemp']);
	//采集文件
	ChangeMCj($add['mid'],$tid,$cj);
	GetConfig(1);//更新缓存
    if($sql)
	{
		//操作日志
	    insert_dolog("mid=".$add['mid']."<br>m=".$add['mname']);
		printerror("EditMSuccess","db/ListM.php?tid=$tid&tbname=$tbname".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除模型
function DelM($mid,$tid,$tbname,$userid,$username){
	global $empire,$dbtbpre;
	$tid=(int)$tid;
	$tbname=RepPostVar($tbname);
	$mid=(int)$mid;
	if(empty($mid)||!$tid||!$tbname)
	{
		printerror("EmptyMid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"m");
	$r=$empire->fetch1("select mname,isdefault from {$dbtbpre}enewsmod where mid='$mid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsmod where mid='$mid'");
	$empire->query("delete from {$dbtbpre}enewsinfotype where mid='$mid'");//删除主题分类
	DelFiletext("../../c/ecachemod/emodform/".$mid.".php");
	DelFiletext("../../c/ecachemod/emodform/q".$mid.".php");
	DelFiletext("../../c/ecachemod/emodform/cj".$mid.".php");
	DelFiletext("../../c/ecachemod/emodform/editcj".$mid.".php");
	//表默认模型
	if($r['isdefault'])
	{
		$modr=$empire->fetch1("select mid from {$dbtbpre}enewsmod where tid='$tid' order by mid");
		if($modr['mid'])
		{
			$empire->query("update {$dbtbpre}enewstable set mid='".$modr['mid']."' where tid='$tid'");
			$empire->query("update {$dbtbpre}enewsmod set isdefault=1 where mid='".$modr['mid']."'");
		}
	}
	GetConfig(1);//更新缓存
    if($sql)
	{
	    insert_dolog("mid=".$mid."<br>m=".$r['mname']);//操作日志
		printerror("DelMSuccess","db/ListM.php?tid=$tid&tbname=$tbname".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//默认模型
function DefM($mid,$tid,$tbname,$userid,$username){
	global $empire,$dbtbpre;
	$tid=(int)$tid;
	$tbname=RepPostVar($tbname);
	$mid=(int)$mid;
	if(empty($mid)||!$tid||!$tbname)
	{
		printerror("EmptyDefMid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"m");
	$r=$empire->fetch1("select mname from {$dbtbpre}enewsmod where mid='$mid'");
	$empire->query("update {$dbtbpre}enewsmod set isdefault=0 where tid='$tid'");
	$sql=$empire->query("update {$dbtbpre}enewsmod set isdefault=1 where mid='$mid'");
	$empire->query("update {$dbtbpre}enewstable set mid='$mid' where tid='$tid'");
	GetConfig(1);//更新缓存
	if($sql)
	{
	    insert_dolog("mid=".$mid."<br>m=".$r['mname']);//操作日志
		printerror("DefMSuccess","db/ListM.php?tid=$tid&tbname=$tbname".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//更新模型表单文件
function ChangeAllModForm($add,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"changedata");
	$sql=$empire->query("select mid,tid,mtemp,qmtemp,cj from {$dbtbpre}enewsmod");
	while($r=$empire->fetch($sql))
	{
		ChangeMForm($r['mid'],$r['tid'],$r['mtemp']);//更新表单
		ChangeQmForm($r['mid'],$r['tid'],$r['qmtemp']);//更新前台表单
		ChangeMCj($r['mid'],$r['tid'],$r['cj']);//采集表单
		//更新栏目导航
		if($add['ChangeClass']==1)
		{
			GetSearch($r['mid']);
		}
	}
	//操作日志
	insert_dolog("ChangeClass=".$add['ChangeClass']);
	printerror("ChangeAllModFormSuccess","history.go(-1)");
}

//导入系统模型
function LoadInMod($add,$file,$file_name,$file_type,$file_size,$userid,$username){
	global $empire,$dbtbpre,$ecms_config;
	ehCheckCloseMods('loadinmod');
	//验证权限
	CheckLevel($userid,$username,$classid,"table");
	$tbname=RepPostVar(trim($add['tbname']));
	if(!$file_name||!$file_size||!$tbname)
	{
		printerror("EmptyLoadInMod","");
	}
	//扩展名
	$filetype=GetFiletype($file_name);
	if($filetype!=".mod")
	{
		printerror("LoadInModMustmod","");
	}
	//表名是否已存在
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewstable where tbname='$tbname'".do_dblimit_cone());
	if($num)
	{
		printerror("HaveLoadInTb","");
	}
	//上传文件
	$path=ECMS_PATH."c/ecachetmp/etmploadfile/elmod/uploadm".time().make_password(10).".php";
	if(!eCkIsTranFile($file))
	{
		printerror("EmptyLoadInMod","");
	}
	$cp=@move_uploaded_file($file,$path);
	if(!$cp)
	{
		printerror("EmptyLoadInMod","");
	}
	DoChmodFileTwo($path);
	@include($path);
	UpdateTbDefMod($tid,$tbname,$mid);
	//公共变量
	TogSaveTxtF(1);
	GetConfig(1);//更新缓存
	//生成模型表单文件
	$modr=$empire->fetch1("select mtemp,qmtemp,cj from {$dbtbpre}enewsmod where mid='$mid'");
	ChangeMForm($mid,$tid,$modr['mtemp']);//更新表单
	ChangeQmForm($mid,$tid,$modr['qmtemp']);//更新前台表单
	ChangeMCj($mid,$tid,$modr['cj']);//采集表单
	//删除文件
	DelFiletext($path);
	//操作日志
	insert_dolog("tid=$tid&tb=$tbname<br>mid=$mid");
	printerror("LoadInModSuccess","db/ListTable.php".hReturnEcmsHashStrHref2(1));
}

//导出系统模型
function LoadOutMod($add,$userid,$username){
	global $empire,$dbtbpre;
	//pgsql
	if(!do_dbCanUseSctb())
	{
		echo'"SHOW CREATE TABLE" is unable.';
		exit();
	}
	
	$tid=(int)$add['tid'];
	$tbname=RepPostVar($add['tbname']);
	$mid=(int)$add['mid'];
	if(!$tid||!$tbname||!$mid)
	{
		printerror("EmptyLoadMod","");
	}
	$mr=$empire->fetch1("select * from {$dbtbpre}enewsmod where mid='$mid' and tid='$tid'");
	if(!$mr['mid'])
	{
		printerror("EmptyLoadMod","");
	}
	$tr=$empire->fetch1("select tbname,tname,tsay,intb from {$dbtbpre}enewstable where tid='$tid'");
	if(!$tr['tbname'])
	{
		printerror("EmptyLoadMod","");
	}
	//数据表结构
	$loadmod="<?php
".LoadModReturnstru($dbtbpre."ecms_".$mr['tbname'],$mr['tbname'],0)."\r\n";
	$loadmod.=LoadModReturnstru($dbtbpre."ecms_".$mr['tbname']."_data_1",$mr['tbname'],5)."\r\n";
	$loadmod.=LoadModReturnstru($dbtbpre."ecms_".$mr['tbname']."_index",$mr['tbname'],6)."\r\n";
	$loadmod.=LoadModReturnstru($dbtbpre."ecms_".$mr['tbname']."_doc",$mr['tbname'],1)."\r\n";
	$loadmod.=LoadModReturnstru($dbtbpre."ecms_".$mr['tbname']."_doc_data",$mr['tbname'],4)."\r\n";
	$loadmod.=LoadModReturnstru($dbtbpre."ecms_".$mr['tbname']."_doc_index",$mr['tbname'],7)."\r\n";
	$loadmod.=LoadModReturnstru($dbtbpre."ecms_".$mr['tbname']."_check",$mr['tbname'],8)."\r\n";
	$loadmod.=LoadModReturnstru($dbtbpre."ecms_".$mr['tbname']."_check_data",$mr['tbname'],9)."\r\n";
	$loadmod.=LoadModReturnstru($dbtbpre."ecms_infoclass_".$mr['tbname'],$mr['tbname'],2)."\r\n";
	$loadmod.=LoadModReturnstru($dbtbpre."ecms_infotmp_".$mr['tbname'],$mr['tbname'],3)."\r\n";
	//数据表
	$loadmod.="\$empire->query(\"insert into \".\$dbtbpre.\"enewstable(tbname,tname,tsay,isdefault,datatbs,deftb,yhid,mid,intb) values('\$tbname','".$tr['tname']."','".LMEscape_str($tr['tsay'])."',0,',1,','1',0,0,'".$tr['intb']."');\");
\$tid=\$empire->lastid(\$dbtbpre.'enewstable','tid');
";
	//字段
	$fsql=$empire->query("select * from {$dbtbpre}enewsf where tid='$tid' order by fid");
	while($fr=$empire->fetch($fsql))
	{
		$loadmod.="\$empire->query(\"insert into \".\$dbtbpre.\"enewsf(f,fname,fform,fhtml,fzs,isadd,isshow,iscj,cjhtml,myorder,ftype,flen,dotemp,tid,tbname,savetxt,fvalue,iskey,tobr,dohtml,qfhtml,isonly,linkfieldval,samedata,fformsize,tbdataf,ispage,adddofun,editdofun,qadddofun,qeditdofun,linkfieldtb,linkfieldshow,editorys,issmalltext,fmvnum) values('".$fr['f']."','".$fr['fname']."','".$fr['fform']."','".LMEscape_str($fr['fhtml'])."','".LMEscape_str($fr['fzs'])."',".$fr['isadd'].",".$fr['isshow'].",".$fr['iscj'].",'".LMEscape_str($fr['cjhtml'])."',".$fr['myorder'].",'".$fr['ftype']."','".$fr['flen']."',".$fr['dotemp'].",\$tid,'\$tbname',".$fr['savetxt'].",'".LMEscape_str($fr['fvalue'])."',".$fr['iskey'].",".$fr['tobr'].",".$fr['dohtml'].",'".LMEscape_str($fr['qfhtml'])."',".$fr['isonly'].",'".LMEscape_str($fr['linkfieldval'])."',".$fr['samedata'].",'".$fr['fformsize']."','".$fr['tbdataf']."','".$fr['ispage']."','".LMEscape_str($fr['adddofun'])."','".LMEscape_str($fr['editdofun'])."','".LMEscape_str($fr['qadddofun'])."','".LMEscape_str($fr['qeditdofun'])."','".LMEscape_str($fr['linkfieldtb'])."','".LMEscape_str($fr['linkfieldshow'])."','".$fr['editorys']."','".$fr['issmalltext']."','".LMEscape_str($fr['fmvnum'])."');\");
";
	}
	//模型
	$loadmod.="\$empire->query(\"insert into \".\$dbtbpre.\"enewsmod(mname,mtemp,mzs,cj,enter,tempvar,sonclass,searchvar,tid,tbname,qenter,mustqenterf,qmtemp,listandf,setandf,listtempvar,qmname,canaddf,caneditf,definfovoteid,showmod,usemod,myorder,orderf,isdefault,listfile,printtempid,maddfun,meditfun,qmaddfun,qmeditfun) values('".$mr['mname']."','".LMEscape_str($mr['mtemp'])."','".LMEscape_str($mr['mzs'])."','".LMEscape_str($mr['cj'])."','".LMEscape_str($mr['enter'])."','".LMEscape_str($mr['tempvar'])."','','".LMEscape_str($mr['searchvar'])."',\$tid,'\$tbname','".LMEscape_str($mr['qenter'])."','".LMEscape_str($mr['mustqenterf'])."','".LMEscape_str($mr['qmtemp'])."','".LMEscape_str($mr['listandf'])."',".$mr['setandf'].",'".LMEscape_str($mr['listtempvar'])."','".LMEscape_str($mr['qmname'])."','".LMEscape_str($mr['canaddf'])."','".LMEscape_str($mr['caneditf'])."',0,0,0,0,'".LMEscape_str($mr['orderf'])."',0,'',0,'".LMEscape_str($mr['maddfun'])."','".LMEscape_str($mr['meditfun'])."','".LMEscape_str($mr['qmaddfun'])."','".LMEscape_str($mr['qmeditfun'])."');\");
\$mid=\$empire->lastid(\$dbtbpre.'enewsmod','mid');
?>";
	$file=$tr['tbname'].time().".mod";
	$filepath=ECMS_PATH."c/ecachetmp/etmploadfile/elmod/".$file;
	WriteFiletext_n($filepath,AddCheckViewTempCode().$loadmod);
	DownLoadFile($file,$filepath,1);
	//操作日志
	insert_dolog("tid=$tid&tb=".$tr['tbname']."<br>mid=$mid&m=".$mr['mname']);
	exit();
}

//返回数据表结构
function LoadModReturnstru($table,$tb,$ecms=0){
	global $empire;
	$create=do_dbTableStruSql($table);//pgsql
	$create=LoadModToMysqlFour($create);
	//替换表
	if($ecms==1)
	{
		$reptb="\$dbtbpre.\"ecms_\".\$tbname.\"_doc\"";
	}
	elseif($ecms==2)
	{
		$reptb="\$dbtbpre.\"ecms_infoclass_\".\$tbname";
	}
	elseif($ecms==3)
	{
		$reptb="\$dbtbpre.\"ecms_infotmp_\".\$tbname";
	}
	elseif($ecms==4)
	{
		$reptb="\$dbtbpre.\"ecms_\".\$tbname.\"_doc_data\"";
	}
	elseif($ecms==5)
	{
		$reptb="\$dbtbpre.\"ecms_\".\$tbname.\"_data_1\"";
	}
	elseif($ecms==6)
	{
		$reptb="\$dbtbpre.\"ecms_\".\$tbname.\"_index\"";
	}
	elseif($ecms==7)
	{
		$reptb="\$dbtbpre.\"ecms_\".\$tbname.\"_doc_index\"";
	}
	elseif($ecms==8)
	{
		$reptb="\$dbtbpre.\"ecms_\".\$tbname.\"_check\"";
	}
	elseif($ecms==9)
	{
		$reptb="\$dbtbpre.\"ecms_\".\$tbname.\"_check_data\"";
	}
	else
	{
		$reptb="\$dbtbpre.\"ecms_\".\$tbname";
	}
	$dumpsql.="\$empire->query(str_replace(\"".$table."\",$reptb,SetCreateTable(\"".$create."\",\$ecms_config['db']['dbchar'])));\r\n";
	return $dumpsql;
}

//转为Mysql4.0格式
function LoadModToMysqlFour($query){
	global $ecms_config;
	if($ecms_config['db']['usedb']=='pgsql')//pgsql
	{
		return $query;
	}
	$exp="ENGINE=";
	if(!strstr($query,$exp))
	{
		return $query;
	}
	$exp1=" ";
	$r=explode($exp,$query);
	//取得表类型
	$r1=explode($exp1,$r[1]);
	$returnquery=$r[0]."TYPE=".$r1[0];
	return $returnquery;
}

//字符过虑
function LMEscape_str($str){
	global $empire,$dbtbpre,$public_r;
	if($public_r['bakescapetype']==2)//real_escape_string
	{
		$str=$empire->EDbEscapeStr($str);

	}
	else//addslashes
	{
		$str=addslashes($str);
	}
	$str=str_replace('\\\'','\'\'',$str);
	$str=str_replace("\\\\","\\\\\\\\",$str);
	$str=str_replace('$','\$',$str);
	return $str;
}

//读取系统模型列表模板文件
function ReadModListTempFile($tempname){
	$deflistpn='../../c/ecachemod/emodlist/def';
	$deflistfn=$deflistpn.'/'.$tempname.'.txt';
	$sysmodlist=ReadFiletext($deflistfn);
	return $sysmodlist;
}

//系统模型列表模板默认
function ReturnModListTempDeftext($tempname){
	$expstr='[!--empirenews.listtemp--]';
	$str=ReadModListTempFile($tempname);
	$tr=GetModListTempTextr($str,'');
	$ret_r['alltext']=$tr[0].$tr[1].$tr[2].$tr[3].$tr[4].$tr[5].$tr[6];
	$ret_r['temptext']=$tr[1].$expstr.$tr[3].$expstr.$tr[5];
	return $ret_r;
}

//系统模型列表模板自定义
function ReturnModListTempDiytext($tempname,$af,$temptext){
	$expstr='[!--empirenews.listtemp--]';
	//temp
	$temptextr=explode($expstr,$temptext);
	//file
	$str=ReadModListTempFile($tempname);
	$tr=GetModListTempTextr($str,$af);
	$alltext=$tr[0].$temptextr[0].$tr[2].$temptextr[1].$tr[4].$temptextr[2].$tr[6];
	return $alltext;
}

//系统模型列表模板自定义
function GetModListTempTextr($str,$af=''){
	$str=str_replace('<!--[empirecms.sys.modlist.af]-->',$af,$str);
	$tr=explode('<!--[empirecms.sys.modlist.listinfo]-->',$str);
	return $tr;
}

//增加系统模型列表
function AddModList($add,$userid,$username){
	global $empire,$dbtbpre;
	$lname=hRepPostStr($add['lname'],1);
	$lsay=hRepPostStr($add['lsay'],1);
	if(!$lname)
	{
		printerror("EmptyModList","history.go(-1)");
    }
	//操作权限
	CheckLevel($userid,$username,$classid,"table");
	$sql=$empire->updatesql("insert into {$dbtbpre}enewsmodlist(lname,lsay,ldoup) values('$lname','$lsay',1);","ins");
	$lid=$empire->lastid($dbtbpre.'enewsmodlist','lid');
	$lid=(int)$lid;
	$lpath=$lid;
	if(!$lpath)
	{
		$lpath=1;
	}
	$usql=$empire->query("update {$dbtbpre}enewsmodlist set lpath='$lpath' where lid='$lid'");
	//建目录
	$listpn='../../c/ecachemod/emodlist/'.$lpath;
	if(!file_exists($listpn))
	{
		$mk=DoMkdir($listpn);
	}
	//ali
	$mlr=ReturnModListTempDeftext('alllistinfo');
	$temptext_ali=addslashes(addslashes($mlr['temptext']));
	$tofile=$listpn.'/alllistinfo.php';
	if($lpath>1)
	{
		WriteFiletext_n($tofile,$mlr['alltext']);
	}
	//li
	$mlr=ReturnModListTempDeftext('listinfo');
	$temptext_li=addslashes(addslashes($mlr['temptext']));
	$tofile=$listpn.'/listinfo.php';
	if($lpath>1)
	{
		WriteFiletext_n($tofile,$mlr['alltext']);
	}
	//dli
	$mlr=ReturnModListTempDeftext('doclistinfo');
	$temptext_dli=addslashes(addslashes($mlr['temptext']));
	$tofile=$listpn.'/doclistinfo.php';
	if($lpath>1)
	{
		WriteFiletext_n($tofile,$mlr['alltext']);
	}
	//qli
	$mlr=ReturnModListTempDeftext('qlistinfo');
	$temptext_qli=addslashes(addslashes($mlr['temptext']));
	$tofile=$listpn.'/qlistinfo.php';
	if($lpath>1)
	{
		WriteFiletext_n($tofile,$mlr['alltext']);
	}
	//模板
	$usql2=$empire->query("update {$dbtbpre}enewsmodlist set tempall='$temptext_ali',tempclass='$temptext_li',tempqinfo='$temptext_qli',tempdoc='$temptext_dli' where lid='$lid'");
	if($sql)
	{
		//操作日志
		insert_dolog("lid=".$lid."<br>lname=".$lname);
		printerror("AddModListSuccess","db/ListModList.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改系统模型列表
function EditModList($add,$userid,$username){
	global $empire,$dbtbpre;
	$lid=(int)$add['lid'];
	$ecms=(int)$add['ecms'];
	$ldoup=(int)$add['ldoup'];
	$lname=hRepPostStr($add['lname'],1);
	$lsay=hRepPostStr($add['lsay'],1);
	$doautofile=(int)$add['doautofile'];
	$aflist=RepPostVar($add['aflist']);
	$temptext=$add['temptext'];
	$filetext=ClearAddsData($add['filetext']);
	if(!$lid)
	{
		printerror("EmptyModList","history.go(-1)");
    }
	//操作权限
	CheckLevel($userid,$username,$classid,"table");
	
	$lr=$empire->fetch1("select lid,lname,lpath,ldoup from {$dbtbpre}enewsmodlist where lid='$lid'");
	if(!$lr['lid']||!$lr['lpath'])
	{
		printerror("EmptyModList","");
	}
	//建目录
	$lr['lpath']=(int)$lr['lpath'];
	$listpn='../../c/ecachemod/emodlist/'.$lr['lpath'];
	if(!file_exists($listpn))
	{
		$mk=DoMkdir($listpn);
	}
	$listfn='';
	//后台按表管理信息列表
	if($ecms==1)
	{
		$listfn=$listpn.'/alllistinfo.php';
		$sql=$empire->query("update {$dbtbpre}enewsmodlist set qafall='$aflist',tempall='".eaddslashes2($temptext)."' where lid='$lid'");
		if($doautofile)
		{
			$filetext=ReturnModListTempDiytext('alllistinfo',$aflist,ClearAddsData($temptext));
		}
	}
	elseif($ecms==2)//后台按栏目管理信息列表
	{
		$listfn=$listpn.'/listinfo.php';
		$sql=$empire->query("update {$dbtbpre}enewsmodlist set qafclass='$aflist',tempclass='".eaddslashes2($temptext)."' where lid='$lid'");
		if($doautofile)
		{
			$filetext=ReturnModListTempDiytext('listinfo',$aflist,ClearAddsData($temptext));
		}
	}
	elseif($ecms==3)//后台管理归档信息列表
	{
		$listfn=$listpn.'/doclistinfo.php';
		$sql=$empire->query("update {$dbtbpre}enewsmodlist set qafdoc='$aflist',tempdoc='".eaddslashes2($temptext)."' where lid='$lid'");
		if($doautofile)
		{
			$filetext=ReturnModListTempDiytext('doclistinfo',$aflist,ClearAddsData($temptext));
		}
	}
	elseif($ecms==4)//前台会员管理信息列表
	{
		$listfn=$listpn.'/qlistinfo.php';
		$sql=$empire->query("update {$dbtbpre}enewsmodlist set qafqinfo='$aflist',tempqinfo='".eaddslashes2($temptext)."' where lid='$lid'");
		if($doautofile)
		{
			$filetext=ReturnModListTempDiytext('qlistinfo',$aflist,ClearAddsData($temptext));
		}
	}
	else//基本信息
	{
		if(!$lname)
		{
			printerror("EmptyModList","history.go(-1)");
		}
		$sql=$empire->query("update {$dbtbpre}enewsmodlist set lname='$lname',lsay='$lsay',ldoup='$ldoup' where lid='$lid'");
	}
	//改文件
	if($listfn&&$lr['ldoup'])
	{
		WriteFiletext_n($listfn,$filetext);
	}
	if($sql)
	{
		//操作日志
		insert_dolog("lid=".$lid."<br>lname=".$lr['lname']);
		printerror("EditModListSuccess","db/ListModList.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除系统模型列表
function DelModList($lid,$userid,$username){
	global $empire,$dbtbpre;
	$lid=(int)$lid;
	if(!$lid)
	{
		printerror("NotChangeModList","");
    }
	//操作权限
	CheckLevel($userid,$username,$classid,"table");
	$lr=$empire->fetch1("select lid,lname,lpath,ldoup from {$dbtbpre}enewsmodlist where lid='$lid'");
	if(!$lr['lid']||!$lr['lpath'])
	{
		printerror("NotChangeModList","");
	}
	//默认
	if($lr['lid']==1||$lr['lpath']==1)
	{
		printerror("NotDelDefaultModList","");
	}
	$lr['lpath']=(int)$lr['lpath'];
	$sql=$empire->query("delete from {$dbtbpre}enewsmodlist where lid='$lid'");
	//模型表
	$usql=$empire->query("update {$dbtbpre}enewsmod set listfile='' where listfile='".$lr['lpath']."'");
	//目录
	$listpn='../../c/ecachemod/emodlist/'.$lr['lpath'];
	$del=DelPath($listpn);
	if($sql)
	{
		//操作日志
		insert_dolog("lid=".$lid."<br>lname=".$lr['lname']);
		printerror("DelModListSuccess","db/ListModList.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}
?>