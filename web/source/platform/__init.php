<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

$account_api = WeAccount::create();
if (is_error($account_api)) {
	itoast('', url('account/display'));
}

if (!($action == 'material' && $do == 'delete') && empty($_GPC['version_id'])) {
	$check_manange = $account_api->checkIntoManage();
	if (is_error($check_manange)) {
		$account_display_url = $account_api->accountDisplayUrl();
		itoast('', $account_display_url);
	}
}

if ($action != 'material-post' && $_GPC['uniacid'] != FILE_NO_UNIACID) {
	define('FRAME', 'account');
}
if ($action == 'qr') {
	$platform_qr_permission = permission_check_account_user('platform_qr', false);
	if ($platform_qr_permission ===  false) {
		header("Location: ". url('platform/url2qr'));
	}
}

if ($action == 'url2qr') {
	define('ACTIVE_FRAME_URL', url('platform/qr'));
}