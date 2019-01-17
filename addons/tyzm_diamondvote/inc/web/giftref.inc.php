<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/17 0017
 * Time: 上午 2:13
 */

defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W;

$modulename = 'tyzm_diamondvote';

function saveSettings($dat, $modulename) {
    global $_W;
    $pars = array('module' => $modulename, 'uniacid' => $_W['uniacid']);

    $settings = pdo_fetchcolumn("SELECT settings FROM ".tablename('uni_account_modules')." WHERE module = :module AND uniacid = :uniacid", array(':module' => $modulename, ':uniacid' => $_W['uniacid']));
    $settings = iunserializer($settings);
    $settings['giftref'] = $dat['giftref'];

    $row = array();
    $row['settings'] = iserializer($settings);

    cache_build_module_info($modulename);
    if (pdo_fetchcolumn("SELECT module FROM ".tablename('uni_account_modules')." WHERE module = :module AND uniacid = :uniacid", array(':module' => $modulename, ':uniacid' => $_W['uniacid']))) {
        return pdo_update('uni_account_modules', $row, $pars) !== false;
    } else {
        return pdo_insert('uni_account_modules', array('settings' => iserializer($settings), 'module' => $modulename ,'uniacid' => $_W['uniacid'], 'enabled' => 1)) !== false;
    }
}

$dat = array('giftref' => trim($_GPC['giftref']));
saveSettings($dat, $modulename);
header('location:' . url('site/entry/manage', array('m'=>$modulename)));

