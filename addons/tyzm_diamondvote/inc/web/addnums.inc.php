<?php
defined('IN_IA') or exit('Access Denied');

load()->func('file');
load()->func('tpl');
global $_W,$_GPC;

$uniacid = intval($_W['uniacid']);
$rid = intval($_GPC['rid']);
/*$addnum = $_GPC['addnum']?intval($_GPC['addnum']):1;

if ($_W['ispost']) {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = "";
	if (!empty($_GPC['keyword'])) {
		if(preg_match("/^1[34578]{1}\d{9}$/",$_GPC['keyword'])){
			$condition .= " AND CONCAT(`name`,`joindata`) LIKE '%{$_GPC['keyword']}%'";
		}
		$condition .= " AND CONCAT(`noid`,`name`) LIKE '%{$_GPC['keyword']}%'";
	}
	if($_GPC['ty']==2){	
		$condition .= " AND status!=1";
	}elseif($_GPC['ty']==1){
		$condition .= " AND status=1";
	}
	if($_GPC['ranking']==""){
		$condition .= " ORDER BY id DESC ";
	}elseif($_GPC['ranking']==1){
		$condition .= " ORDER BY giftcount DESC,votenum DESC,id DESC ";
	}elseif($_GPC['ranking']==0){
		$condition .= " ORDER BY votenum DESC,giftcount DESC,id DESC ";
	}
	$ids = pdo_fetchall("SELECT id FROM ".tablename($this->tablevoteuser)." WHERE uniacid = '{$_W['uniacid']} ' AND rid = '{$_GPC['rid']} ' $condition   LIMIT ".($pindex - 1) * $psize.",{$psize}");
	
	foreach($ids as $id){
		$instdata = array(
			'votenum +='=>$addnum,
		);
		if(!empty($_GPC['addnum']) && !empty($id['id'])){
			$log = array(
				'rid'=>$rid,
				'uniacid'=>$uniacid,
				'uid'=>$id['id'],
				'muser'=>$_W['username'],
				'ip'=>$_W['clientip'],
				'time'=>time(),
				'data'=>"票数变化 ".$addnum,
			);
			$result = pdo_insert($this->tablemlog, $log);
		}
		
		$setpv = 'update ' . tablename($this->tablecount) . ' set pv_total=pv_total+'.$addnum.' where tid = '.$id['id'].' AND rid='.$rid.' AND uniacid='.$uniacid;
		if(!pdo_query($setpv)){
			$count=pdo_fetch("SELECT * FROM " . tablename($this->tablecount) . " WHERE tid = :tid AND rid = :rid ", array(':tid' => $id['id'],':rid' => $rid));
			if(empty($count)){
				$indata=array(
					'tid'=>$id['id'],
					'rid'=>$rid,
					'uniacid'=>$_W['uniacid'],
					'pv_total'=>$addnum,
				);
				pdo_insert($this->tablecount, $indata); 
			}
		}
		
		
		
		if (!empty($result)) {
			pdo_update($this->tablevoteuser, $instdata, array('id' => $id['id']));
			
			$votenum = pdo_fetchcolumn('SELECT votenum FROM ' . tablename($this->tablevoteuser) . " WHERE   rid = :rid AND id=:id " , array(':rid' => $rid,':id' => $id['id']));
			$pv_total = pdo_fetchcolumn('SELECT pv_total FROM ' . tablename($this->tablecount) . " WHERE   rid = :rid AND tid=:tid " , array(':rid' => $rid,':tid' => $id['id']));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>'后台记录失败，请联系管理员！')));
		}
	}
	exit(json_encode(array('status'=>1,'msg'=>'加票成功！', 'data'=>array('votenum'=>$votenum, 'pv_total'=>$pv_total))));

}else{
	exit(json_encode(array('status'=>0,'msg'=>'请提交正确数据！')));
}*/

$addnum_array = $_GPC['addnum_array'] ? $_GPC['addnum_array'] : array();
$id_array = $_GPC['id_array'] ? $_GPC['id_array'] : array();

if ($_W['ispost']) {
	foreach($id_array as $idx=>$uid){
	    $addnum = $addnum_array[$idx];
		$instdata = array(
			'votenum +='=>$addnum,
		);
		if(!empty($addnum) && !empty($uid)){
			$log = array(
				'rid'=>$rid,
				'uniacid'=>$uniacid,
				'uid'=>$uid,
				'muser'=>$_W['username'],
				'ip'=>$_W['clientip'],
				'time'=>time(),
				'data'=>"票数变化 ".$addnum,
			);
			$result = pdo_insert($this->tablemlog, $log);
		}

		$setpv = 'update ' . tablename($this->tablecount) . ' set pv_total=pv_total+'.$addnum.' where tid = '.$uid.' AND rid='.$rid.' AND uniacid='.$uniacid;
		if(!pdo_query($setpv)){
			$count=pdo_fetch("SELECT * FROM " . tablename($this->tablecount) . " WHERE tid = :tid AND rid = :rid ", array(':tid' => $uid,':rid' => $rid));
			if(empty($count)){
				$indata=array(
					'tid'=>$uid,
					'rid'=>$rid,
					'uniacid'=>$_W['uniacid'],
					'pv_total'=>$addnum,
				);
				pdo_insert($this->tablecount, $indata);
			}
		}

		if (!empty($result)) {
			pdo_update($this->tablevoteuser, $instdata, array('id' => $uid));

			$votenum = pdo_fetchcolumn('SELECT votenum FROM ' . tablename($this->tablevoteuser) . " WHERE   rid = :rid AND id=:id " , array(':rid' => $rid,':id' => $uid));
			$pv_total = pdo_fetchcolumn('SELECT pv_total FROM ' . tablename($this->tablecount) . " WHERE   rid = :rid AND tid=:tid " , array(':rid' => $rid,':tid' => $uid));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>'后台记录失败，请联系管理员！')));
		}
	}
	exit(json_encode(array('status'=>1,'msg'=>'加票成功！', 'data'=>array('votenum'=>$votenum, 'pv_total'=>$pv_total))));

}else{
	exit(json_encode(array('status'=>0,'msg'=>'请提交正确数据！')));
}





