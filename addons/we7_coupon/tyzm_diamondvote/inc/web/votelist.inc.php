<?php
/**
 * 八度鱼投票模块-投票列表
 *
 * @author 八度鱼开发
 * @url http://www.8dfish.com
 */

defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W;
$this->authorization();
$uservote=pdo_get('tyzm_diamondvote_voteuser', array('uniacid' => $_W['uniacid'],'rid'=>$_GPC['rid']), array('id','locktime'));
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition="";
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

$order = (empty($_GPC['sort_order']) || $_GPC['sort_order'] == "desc") ? "DESC" : "ASC";
switch ($_GPC['ranking']) {
    case "0":
        $condition .= " ORDER BY votenum ".$order . ", id DESC";
        break;
    case "1":
        $condition .= " ORDER BY giftcount ".$order. ", id DESC";
        break;
    default:
        $condition .= " ORDER BY id ".$order;
        break;
}

$list = pdo_fetchall("SELECT * FROM ".tablename($this->tablevoteuser)." WHERE uniacid = '{$_W['uniacid']} ' AND rid = '{$_GPC['rid']} ' $condition   LIMIT ".($pindex - 1) * $psize.",{$psize}");
if (!empty($list)){
	 $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tablevoteuser) . " WHERE uniacid = '{$_W['uniacid']}' AND rid = '{$_GPC['rid']} ' $condition");
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

if ($_GPC['ranking'] == '2' || $_GPC['ranking'] == '3') {
    if ($_GPC['ranking'] == '2') {
        $sort_key = array_column($list, 'pvtotal');
    } else {
        $sort_key = array_column($list, 'sharetotal');
    }
    if ($order == 'DESC') {
        array_multisort($sort_key,SORT_DESC,$list);
    } else {
        array_multisort($sort_key,SORT_ASC,$list);
    }
}
	 
include $this->template('votelist');
