<?php
global $_W,$_GPC;
$uniacid=$_W['uniacid'];
load()->func('tpl');
//操作标识
$op =  isset($_GPC['op']) ? trim($_GPC['op']) : "display";

if (!empty($_GPC['datelimit'])) {
	$starttime = strtotime($_GPC['datelimit']['start']);
	$endtime = strtotime($_GPC['datelimit']['end']) + 86399;
	$condition .= " AND createtime > :start AND createtime < :end";
	$params[':start'] = $starttime;
	$params[':end'] = $endtime;
}
if($op == 'profit') {
    global $_W, $_GPC;
    $dailystarttime = mktime(0, 0, 0);
    $dailyendtime = mktime(23, 59, 59);
    $dailytimes = '';
    $dailytimes .= ' AND createtime >=' . $dailystarttime;
    $dailytimes .= ' AND createtime <=' . $dailyendtime;
    $item['dailyjointotal'] = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename($this->tablevoteuser) . " WHERE   uniacid = :uniacid  " . $dailytimes, array(":uniacid" => $_W["uniacid"]));
    $item['dailyvotetotal'] = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename($this->tablevotedata) . " WHERE   uniacid = :uniacid AND votetype=0 " . $dailytimes, array(":uniacid" => $_W["uniacid"]));
    $item['dailygiftcount'] = pdo_fetchcolumn('SELECT sum(fee) FROM ' . tablename($this->tablegift) . " WHERE   uniacid = :uniacid AND ispay=1 " . $dailytimes, array(":uniacid" => $_W["uniacid"]));
    $item['dailygiftnum'] = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename($this->tablegift) . " WHERE   uniacid = :uniacid AND ispay=1 " . $dailytimes, array(":uniacid" => $_W["uniacid"]));
    $item['dailygiftcount'] = !empty($item['dailygiftcount']) ? $item['dailygiftcount'] : 0;
    $item['jointotal'] = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename($this->tablevoteuser) . " WHERE   uniacid = :uniacid  ", array(":uniacid" => $_W["uniacid"]));
    $item['votetotal'] = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename($this->tablevotedata) . " WHERE   uniacid = :uniacid AND votetype=0 ", array(":uniacid" => $_W["uniacid"]));
    $item['giftcount'] = pdo_fetchcolumn('SELECT sum(fee) FROM ' . tablename($this->tablegift) . " WHERE uniacid = :uniacid AND ispay=1 ", array(":uniacid" => $_W["uniacid"]));
    $item['pvtotal'] = pdo_fetchcolumn('SELECT sum(pv_total) FROM ' . tablename($this->tablecount) . " WHERE uniacid = :uniacid ", array(":uniacid" => $_W["uniacid"]));
    $item['giftcount'] = !empty($item['giftcount']) ? $item['giftcount'] : 0;
/*    if (IMS_VERSION >= 0.8) {
        $bucket_datacenter = attachment_alioss_datacenters();
    }*/
    //include $this->template("setting");
} elseif ($op=='display'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition="";
	if (!empty($_GPC['username'])) {
		$condition .= " AND CONCAT(u.`name`,u.`nickname`,u.`openid`,u.`user_ip`) LIKE '%{$_GPC['username']}%'";
	}
	if (!empty($_GPC['datelimit'])) {
		$starttime = strtotime($_GPC['datelimit']['start']);
		$endtime = strtotime($_GPC['datelimit']['end']) + 86399;
		$condition .= " AND d.createtime > '{$starttime}' AND d.createtime < '{$endtime}'";
	}
	$condition .=" ORDER BY d.id DESC ";
	
	$list = pdo_fetchall("SELECT d.*,u.name,u.id as uid FROM ".tablename($this->tablevotedata)." d LEFT JOIN ".tablename($this->tablevoteuser)." u ON u.id=d.tid WHERE d.uniacid = '{$uniacid}' $condition LIMIT ".($pindex - 1) * $psize.",{$psize}");
	
	foreach ($list as $key => &$item) {
		$item['ipaddress']=ip2address($item['user_ip']);
	}
	
	if(!empty($list)){
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tablevotedata)." d LEFT JOIN ".tablename($this->tablevoteuser)." u ON u.id=d.tid WHERE d.uniacid = '{$uniacid}' $condition");
		$pager = pagination($total, $pindex, $psize); 
	}
}else if($op=='giftlog'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition="";
	if (!empty($_GPC['username'])) {
		$condition .= " AND CONCAT(u.`name`,u.`nickname`,u.`openid`,u.`user_ip`) LIKE '%{$_GPC['username']}%'";
	}
	if (!empty($_GPC['datelimit'])) {
		$starttime = strtotime($_GPC['datelimit']['start']);
		$endtime = strtotime($_GPC['datelimit']['end']) + 86399;
		$condition .= " AND g.createtime > '{$starttime}' AND g.createtime < '{$endtime}'";
	}
	if($_GPC['ispay']>1){
		if($_GPC['ispay'] == 2){
			$condition .=" AND g.ispay = 1";
		}else{
			$condition .=" AND g.ispay = 0";
		}
	}
	$condition .=" ORDER BY g.id DESC ";
	
	$ytotal = pdo_fetchcolumn('SELECT SUM(fee) FROM ' . tablename($this->tablegift)." g LEFT JOIN ".tablename($this->tablevoteuser)." u ON u.id=g.tid WHERE g.uniacid = '{$uniacid}' AND g.ispay = 1 $condition");
	$wtotal = pdo_fetchcolumn('SELECT SUM(fee) FROM ' . tablename($this->tablegift)." g LEFT JOIN ".tablename($this->tablevoteuser)." u ON u.id=g.tid WHERE g.uniacid = '{$uniacid}' AND g.ispay = 0 $condition");
	
	$list = pdo_fetchall("SELECT g.*,u.name,u.id as uid FROM ".tablename($this->tablegift)." g LEFT JOIN ".tablename($this->tablevoteuser)." u ON u.id=g.tid WHERE g.uniacid = '{$uniacid}' $condition LIMIT ".($pindex - 1) * $psize.",{$psize}");
	if(!empty($list)){
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tablegift)." g LEFT JOIN ".tablename($this->tablevoteuser)." u ON u.id=g.tid WHERE g.uniacid = '{$uniacid}' $condition");
		$pager = pagination($total, $pindex, $psize); 
	}
}else if($op=='votelog'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition="";
	if (!empty($_GPC['username'])) {
		$condition .= " AND CONCAT(`name`,`nickname`,`openid`,`user_ip`) LIKE '%{$_GPC['username']}%'";
	}
	if (!empty($_GPC['datelimit'])) {
		$starttime = strtotime($_GPC['datelimit']['start']);
		$endtime = strtotime($_GPC['datelimit']['end']) + 86399;
		$condition .= " AND createtime > '{$starttime}' AND createtime < '{$endtime}'";
	}
	$condition .=" ORDER BY id DESC ";
	
	$list = pdo_fetchall("SELECT * FROM ".tablename($this->tablevoteuser)." WHERE uniacid = '{$uniacid}' $condition LIMIT ".($pindex - 1) * $psize.",{$psize}");
	
	//print_R($list);
	if(!empty($list)){
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tablevoteuser)." WHERE uniacid = '{$uniacid}' $condition");
		$pager = pagination($total, $pindex, $psize);
		foreach ($list as $key =>&$item) {   			
			$pvtotal=pdo_fetchcolumn("SELECT pv_total FROM ".tablename($this->tablecount)." WHERE rid = :rid AND tid=:tid ", array(':rid' => $item['rid'],':tid' => $item['id']));
			$item['pvtotal']=empty($pvtotal)?0:$pvtotal;
			$sharetotal=pdo_fetchcolumn("SELECT share_total FROM ".tablename($this->tablecount)." WHERE rid = :rid AND tid=:tid ", array(':rid' => $item['rid'],':tid' => $item['id']));
			$item['sharetotal']=empty($sharetotal)?0:$sharetotal;
			$item['joindata']=@unserialize($item['joindata']);
			/*array_pop($item['joindata']);*/
		}
	}
	
	
}else{
	message('链接错误，请重试！',$this->createWebUrl('datastates'),'error');
}
include $this->template('datastates');