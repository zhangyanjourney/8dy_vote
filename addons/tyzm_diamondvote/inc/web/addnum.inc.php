<?php


defined('IN_IA') or exit('Access Denied');

load()->func('file');
load()->func('tpl');
global $_W,$_GPC;
$uniacid = intval($_W['uniacid']);
$id = intval($_GPC['id']);
$rid = intval($_GPC['rid']);
$addnum = $_GPC['addnum']?intval($_GPC['addnum']):1;

if ($_W['ispost']) {
	
	$instdata = array(
		'votenum +='=>$addnum,
	);
	if(!empty($_GPC['addnum']) && !empty($id)){
		$log = array(
			'rid'=>$rid,
			'uniacid'=>$uniacid,
			'uid'=>$id,
			'muser'=>$_W['username'],
			'ip'=>$_W['clientip'],
			'time'=>time(),
			'data'=>"票数变化 ".$addnum,
		);
		$result = pdo_insert($this->tablemlog, $log);
	}
	
	$setpv = 'update ' . tablename($this->tablecount) . ' set pv_total=pv_total+'.$addnum.' where tid = '.$id.' AND rid='.$rid.' AND uniacid='.$uniacid;
	if(!pdo_query($setpv)){
		$count=pdo_fetch("SELECT * FROM " . tablename($this->tablecount) . " WHERE tid = :tid AND rid = :rid ", array(':tid' => $id,':rid' => $rid));
		if(empty($count)){
			$indata=array(
				'tid'=>$id,
				'rid'=>$rid,
				'uniacid'=>$_W['uniacid'],
				'pv_total'=>$addnum,
			);
			pdo_insert($this->tablecount, $indata); 
		}
	}
	
	
	
	if (!empty($result)) {
		pdo_update($this->tablevoteuser, $instdata, array('id' => $id));
		
		$votenum = pdo_fetchcolumn('SELECT votenum FROM ' . tablename($this->tablevoteuser) . " WHERE   rid = :rid AND id=:id " , array(':rid' => $rid,':id' => $id));
		$pv_total = pdo_fetchcolumn('SELECT pv_total FROM ' . tablename($this->tablecount) . " WHERE   rid = :rid AND tid=:tid " , array(':rid' => $rid,':tid' => $id));
		
		exit(json_encode(array('status'=>1,'msg'=>'加票成功！', 'data'=>array('votenum'=>$votenum, 'pv_total'=>$pv_total))));
	}else{
		exit(json_encode(array('status'=>0,'msg'=>'后台记录失败，请联系管理员！')));
	}

}else{
	exit(json_encode(array('status'=>0,'msg'=>'请提交正确数据！')));
}



