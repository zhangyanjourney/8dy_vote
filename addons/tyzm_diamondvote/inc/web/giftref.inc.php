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
function get_setting_list() {
    $settings = pdo_fetchcolumn("SELECT settings FROM ".tablename('uni_account_modules')." WHERE module = :module AND uniacid = :uniacid", array(':module' => $modulename, ':uniacid' => $_W['uniacid']));
    $settings = iunserializer($settings);
    return $settings;
}
if (!empty($_GPC['op']) && in_array($_GPC['op'] , array('start','stop'))) {
    $settings = get_setting_list();
    if ($_GPC['op'] == 'start') {
        $settings['giftref'] = 30;
    } else {
        $settings['giftref'] = 0;
    }
    $settings = iserializer($settings);
    pdo_update('uni_account_modules',array('settings'=>$settings),array('module'=>$modulename, 'uniacid'=>$_W['uniacid']));
    message('保存成功', 'refresh');
} else if ($_GPC['op'] == 'list'){
    $settings =  get_setting_list();
    if (empty($settings)) {
        return 0;
    } else {
        return $settings['giftref'];
    }
}
