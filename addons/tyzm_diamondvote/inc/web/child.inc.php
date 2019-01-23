<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
$this->authorization();

/*if($_W['username'] != '金鼎文化传播') {
    message('只有管理员账号才能访问！', '','error');
}*/

$dos = array('display', 'check_display', 'check_pass', 'recycle_display', 'recycle_delete','recycle_restore', 'recycle');
$do = in_array($do, $dos) ? $do: 'display';

$_W['page']['title'] = '子母账号 - 用户管理';

//获取管理员账号下所拥有的公众号
$pindex = max(1, intval($_GPC['page']));
$psize = 15;

$condition = ' WHERE u.status = 2 and u.uniacid = '.$_W['uniacid'];
$params = array();

$sql = 'SELECT u.*, a.name FROM ' . tablename('users'). ' AS u LEFT JOIN '.tablename('uni_account'). ' a ON u.uniacid = a.uniacid '. $condition. " LIMIT " . ($pindex - 1) * $psize .',' .$psize;
$users = pdo_fetchall($sql, $params);
$pager = pagination($total, $pindex, $psize);

include $this->template('child');