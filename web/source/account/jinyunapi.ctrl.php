<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
load()->model('user');
load()->func('file');
load()->classs('oauth2/oauth2client');
load()->model('message');
load()->model('setting');

$dos = array('jy_api');
$do = in_array($do, $dos) ? $do : 'jy_api';
$_W['page']['title'] = '第三方接口';
if ($do == 'jy_api') {
	load()->model('wxapp');
	load()->model('account');
	load()->model('system');
	if(empty($_W['setting']['apiinfo']['jy_token'])) {
		if($_W['isfounder']){
			itoast('没有设置第三方平台token,前往设置', url('system/apisite'),'error');
		}else{
			itoast('没有设置第三方平台token,联系管理员设置！');
		}
		
	}
	if(empty($_W['setting']['apiinfo']['jy_url'])) {
		if($_W['isfounder']){
			itoast('没有设置第三方平台URL，前往设置', url('system/apisite'),'error');
		}else{
			itoast('没有设置第三方平台URL,联系管理员设置！');
		}
	}
	if(empty($_W['setting']['apiinfo']['jy_secret'])) {
		if($_W['isfounder']){
			itoast('没有设置第三方平台secret，前往设置', url('system/apisite'),'error');
		}else{
			itoast('没有设置第三方平台secret,联系管理员设置！');
		}
	}
	if(empty($_W['uniacid'])) {
		itoast('请先选择一个公众号！然后再点第三方！',url('account/display'),'error');
	}
	//获取公众号id
	$cache_key = cache_system_key(CACHE_KEY_ACCOUNT_SWITCH, $_GPC['__switch']);
	$cache_lastaccount = (array)cache_load($cache_key);
	$last_uniacid = $cache_lastaccount['account'];
	if (empty($last_uniacid)) {
		itoast('请先选择一个公众号！然后再点第三方！',url('account/display'),'error');
	}
	if (!empty($last_uniacid) && $last_uniacid != $_W['uniacid']) {
		$_W['uniacid'] = $last_uniacid;
	}
	$access=array('plugin'=>'core','action'=>'basic.welcome','op'=>'','query'=>'');
	$access=base64_encode(json_encode($access));
	$params=array(
		'plugin'=>'core',
		'action'=>'open_login',
		'uid'=>$_W['uniacid'],
		'token'=>$_W['setting']['apiinfo']['jy_token'],//进云平台添加第三方平台时获取的token
		'timestamp'=>time(),
		'access'=>$access,
	);
	$sign_str='';
	ksort($params);
	foreach($params as $key=>$value){
	$sign_str.=$key.$value;
	}
	$sign_str.= $_W['setting']['apiinfo']['jy_secret'];//进云平台添加第三方平台时获取的secret
	$jy_sign = md5($sign_str);
	$params['sign'] = $jy_sign;
	$query=http_build_query($params);
	$url= $_W['setting']['apiinfo']['jy_url'];//进云程序地址
	$url=$url.'/manage/index.php?'.$query;
	header('Location:'.$url);
	exit;
}