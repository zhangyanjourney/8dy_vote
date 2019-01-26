<?php
/**
 * 八度鱼投票模块-后台管理
 *
 * @author 八度鱼开发
 * @url http://www.8dfish.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
$this->authorization();
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
if (!empty($_GPC['keyword'])) {
	$keyword=$_GPC['keyword'];
	$condition .= " AND CONCAT(`title`) LIKE '%{$keyword}%'";
}
if (isset($_GPC['status']) && $_GPC['status'] != "") {
	if ($_GPC['status'] ==1) {
		$condition .= " AND status = '1' AND starttime < '".time()."' AND endtime < '".time()."'";
	}elseif ($_GPC['status'] ==3) {
		$condition .= " AND status = '1' AND starttime > '".time()."'";
	}elseif ($_GPC['status'] ==4) {
		$condition .= " AND status = '1' AND endtime < '".time()."'";
	}else{
		$condition .= " AND status = '{$_GPC['status']}'";
	}
}

if (!empty($_GPC['master'])) {
	$condition .= " AND master = '{$_GPC['master']}'";
} else {
    if ($_W['username'] != '金鼎文化传播') {
        $condition .= " AND master = '{$_W['username']}'";
    }
}

$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
$endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;

if ($_GPC['votestart'] == 1) {
	$condition .= " AND starttime > '{$beginToday}' AND starttime < '{$endToday}'";
}elseif ($_GPC['votestart'] == 2) {
	$condition .= " AND (starttime < '{$beginToday}' OR starttime > '{$endToday}')";
}

if ($_GPC['voteend']==1) {
	$condition .= " AND endtime > '{$beginToday}' AND endtime < '{$endToday}'";
}elseif ($_GPC['voteend'] == 2) {
	$condition .= " AND (endtime < '{$beginToday}' OR endtime > '{$endToday}')";
}

if (is_array($_GPC['createtime'])) {
	$condition .= " AND createtime > '".strtotime($_GPC['createtime']['start'])."' AND createtime < '".(strtotime($_GPC['createtime']['end'])+86399)."'";
}

if (is_array($_GPC['starttime'])) {
	$condition .= " AND votestarttime > '".strtotime($_GPC['starttime']['start'])."' AND votestarttime < '".(strtotime($_GPC['starttime']['end'])+86399)."'";
}

if (is_array($_GPC['endtime'])) {
	$condition .= " AND voteendtime > '".strtotime($_GPC['endtime']['start'])."' AND voteendtime < '".(strtotime($_GPC['endtime']['end'])+86399)."'";
}

$list = pdo_fetchall("SELECT * FROM ".tablename($this->tablereply)." WHERE uniacid = '{$_W['uniacid']}' $condition  ORDER BY status DESC,createtime DESC LIMIT ".($pindex - 1) * $psize.",{$psize}");

if (!empty($list)) {
	 $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tablereply) . " WHERE uniacid = '{$_W['uniacid']}' $condition");
	 $pager = pagination($total, $pindex, $psize); 

	 foreach ($list as &$item){             

		if ($item['status'] ==1) {
			if ($item['starttime'] > time()) {//未开始
				$item['status'] = 3;
			}elseif ($item['endtime'] < time()) {//已结束
				$item['status'] = 4;
			}
		}
		
		$item['jointotal'] = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename($this->tablevoteuser) . " WHERE   rid = :rid  ", array(':rid' => $item['rid']));
		$item['votetotal'] = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename($this->tablevotedata) . " WHERE   rid = :rid AND votetype=0 ", array(':rid' => $item['rid']));
		$item['giftcount'] = pdo_fetchcolumn('SELECT sum(fee) FROM ' . tablename($this->tablegift) . " WHERE   rid = :rid AND ispay=1 ", array(':rid' => $item['rid']));
		$item['giftcount']=!empty($item['giftcount'])?$item['giftcount']:0; 
		$item['virtualpvtotal']=pdo_fetchcolumn("SELECT sum(vheat) FROM ".tablename($this->tablevoteuser)." WHERE rid = :rid ", array(':rid' => $item['rid']));
		$item['pvtotal']=pdo_fetchcolumn("SELECT sum(pv_total) FROM ".tablename($this->tablecount)." WHERE rid = :rid ", array(':rid' => $item['rid']));
		$item['pvtotal']=$item['virtualpvtotal']+$item['pvtotal'];

		$item['sharetotal']=pdo_fetchcolumn("SELECT sum(share_total) FROM ".tablename($this->tablecount)." WHERE rid = :rid ", array(':rid' => $item['rid']));
		$item['sharetotal']=!empty($item['sharetotal'])?$item['sharetotal']:0; 
		
		$item['config']=@unserialize($item['config']);
	}

	 if (!empty($_GPC['order_field']) || !empty($_GPC['order_flag'])) {
         switch ($_GPC['order_field']) {
             case "1":
                 $sort_key = array_column($list, 'jointotal');
                 break;
             case "2":
                 $sort_key = array_column($list, 'votetotal');
                 break;
             case "3":
                 $sort_key = array_column($list, 'giftcount');
                 break;
             case "4":
                 $sort_key = array_column($list, 'pvtotal');
                 break;
             case "5":
                 $sort_key = array_column($list, 'sharetotal');
                 break;
             default:
                 $sort_key = array_column($list, 'rid');
                 break;
         }
         if (empty($_GPC['order_flag'])) {
             array_multisort($sort_key,SORT_DESC,$list);
         } else {
             array_multisort($sort_key,SORT_ASC,$list);
         }
     }

}

$master = pdo_fetchall("SELECT master FROM " . tablename($this->tablereply) . " WHERE uniacid = '{$_W['uniacid']}' AND master != '' GROUP BY master ");

!$_GPC['createtime']['start'] && $_GPC['createtime']['start'] = date("Y-m-d H:i:s", strtotime("-1 month"));
!$_GPC['createtime']['end'] && $_GPC['createtime']['end'] = date("Y-m-d H:i:s", time());
!$_GPC['starttime']['start'] && $_GPC['starttime']['start'] = date("Y-m-d H:i:s", strtotime("-1 month"));
!$_GPC['starttime']['end'] && $_GPC['starttime']['end'] = date("Y-m-d H:i:s", strtotime("1 month"));
!$_GPC['endtime']['start'] && $_GPC['endtime']['start'] = date("Y-m-d H:i:s", strtotime("-1 month"));
!$_GPC['endtime']['end'] && $_GPC['endtime']['end'] = date("Y-m-d H:i:s", strtotime("1 month"));

include $this->template('new_manage');

		