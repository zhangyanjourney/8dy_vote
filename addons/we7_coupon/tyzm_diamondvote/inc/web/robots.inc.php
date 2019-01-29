<?php
defined('IN_IA') or exit('Access Denied');

global $_W,$_GPC;
$uniacid = intval($_W['uniacid']);

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($op=='display'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition="";
	
	if (!empty($_GPC['rname'])) {
		$condition .= " AND CONCAT(`rname`) LIKE '%{$_GPC['rname']}%'";
	}
	
	$list = pdo_fetchall("SELECT * FROM " . tablename($this->tablerobots) . " WHERE uniacid = :uniacid $condition ORDER BY `id` DESC", array(':uniacid' => $uniacid));
	
	if(!empty($list)){
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tablerobots) . " WHERE uniacid = '{$uniacid}' $condition");
		$pager = pagination($total, $pindex, $psize);
		foreach($list as &$val){
			$val['endtime']<time()?($val['status_cn']="已结束").($val['status_style']='default'):($val['starttime']>time()?($val['status_cn']="未开始").($val['status_style']='warning'):($val['status_cn']="等待中").($val['status_style']='success'));
			$val['votename'] = pdo_fetchcolumn('SELECT title FROM ' . tablename($this->tablereply) . " WHERE uniacid = '{$uniacid}' AND rid = '".$val['voterid']."' AND status = 1 ");
			!$val['votename'] && $val['votename']="活动不存在或已停止或已删除！";
		}
	}
	
	include $this->template('robots');
}elseif($op=='add' || $op=='edit'){
	if($_W['ispost']){
		if (empty($_GPC['rname'])) {
			message('机器人名字不能为空');
		}
		if (empty($_GPC['voterid'])) {
			message('活动ID不能为空');
		}
		$vote = pdo_fetch('SELECT * FROM ' . tablename($this->tablereply) . " WHERE uniacid = '{$uniacid}' AND rid = '".$_GPC['voterid']."' AND status = 1 ");
		!$vote && message('活动不存在或已停止或已删除！');
		
		$instdata = array(
			'uniacid'=>$uniacid,
			'rname'=>trim($_GPC['rname']),
			'voterid'=>intval($_GPC['voterid']),
			'voteuser'=>trim($_GPC['voteuser']),
			'randomlow'=>intval($_GPC['randomlow']),
			'randomhigh'=>intval($_GPC['randomhigh']),
			'starttime'=>strtotime($_GPC['starttime']),
			'endtime'=>strtotime($_GPC['endtime']),
		);
		
		$instdata['starttime']>$instdata['endtime'] && message('机器人运行时间段有误！', '', 'error');
		$instdata['randomlow']>$instdata['randomhigh'] && message('机器人随机时间段有误！', '', 'error');
		$instdata['randomhigh']>1800 && message('最高秒数最好不超过1800秒！', '', 'error');
		
		if (!empty($_GPC['id'])) {
			pdo_update($this->tablerobots, $instdata, array('id' => $_GPC['id']));
			message('机器人修改成功！', $this->createWebUrl('robots', array('op' => 'display')), 'success');
		} else {
			$instdata['createtime']=time();
			pdo_insert($this->tablerobots, $instdata);
			message('机器人添加成功！', $this->createWebUrl('robots', array('op' => 'display')), 'success');
		}
	}
	
	$_GPC['id'] && $data = pdo_fetch("SELECT * FROM " . tablename($this->tablerobots) . " WHERE uniacid = :uniacid AND id = :id ORDER BY `id` DESC", array(':uniacid' => $uniacid, ':id' => $_GPC['id']));
	
	include $this->template('robotsedit');
}elseif($op=='del'){
	empty($_GPC['id']) && message('未找到机器人，请重试！', $this->createWebUrl('robots', array('op' => 'display')), 'error');
	
	$res = pdo_delete($this->tablerobots,array('id'=>$_GPC['id']));
	if(!empty($res)){
		message('机器人删除成功！', $this->createWebUrl('robots', array('op' => 'display')), 'success');
	}else{
		message('机器人删除失败，请重试！', $this->createWebUrl('robots', array('op' => 'display')), 'error');
	}
}
