<?php
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
$uniacid = intval($_W['uniacid']);

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if($op=='display'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition="";
	
	$list = pdo_fetchall("SELECT * FROM ".tablename($this->tablegift)." WHERE uniacid = '{$uniacid} ' ORDER BY id DESC LIMIT 20");
	
	
	foreach($list as &$val){
		$val['ispay']==1?($val['status_cn']="已支付").($val['status_style']='success'):($val['status_cn']="未支付").($val['status_style']='danger');
		$val['rtitle'] = pdo_fetchcolumn('SELECT title FROM ' . tablename($this->tablereply) . " WHERE uniacid = '{$uniacid}' AND rid = '".$val['rid']."'");
		$val['uname'] = pdo_fetchcolumn('SELECT name FROM ' . tablename($this->tablevoteuser) . " WHERE uniacid = '{$uniacid}' AND id = '".$val['tid']."' AND status = 1 ");
		!$val['rtitle'] && $val['rtitle']="活动异常";
		!$val['uname'] && $val['uname']="选手异常";
		
		$val['createtime'] = date('Y-m-d H:i:s',$val['createtime']);
	}
	
	if($_W['ispost']){
		$this->json_exit(1, "获取成功！", $list);
	}
	$giftref = $this->module['config']['giftref'];
}
include $this->template('floatgift');
