<?php 
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

load()->model('system');

$dos = array('apiinfo');
$do = in_array($do, $dos) ? $do : 'apiinfo';
$_W['page']['title'] = '第三方设置 - 工具  - 系统管理';

$settings = $_W['setting']['apiinfo'];
if(empty($settings) || !is_array($settings)) {
	$settings = array();
}
$menu_db = pdo_get('core_menu', array('permission_name' => 'jinyunapi'));
if(empty($menu_db)){
	$menu1=array(
		'pid'=>0,
		'title'=>'第三方平台',
		'url'=>'./index.php?c=account&a=jinyunapi&',
		'type'=>'url',
		'is_display'=>1,
		'is_system'=>0,
		'permission_name'=>'jinyunapi',
		'group_name'=>'frame',
	);
	$menu2=array(
		'pid'=>0,
		'title'=>'第三方接口',
		'url'=>'./index.php?c=system&a=apisite&',
		'type'=>'url',
		'is_display'=>1,
		'is_system'=>0,
		'permission_name'=>'apisite',
		'group_name'=>'cloud',
	);
	pdo_insert('core_menu', $menu1);
	pdo_insert('core_menu', $menu2);
	cache_build_frame_menu();
	itoast('预添加第三方菜单成功！', url('system/apisite'), 'success');
}
if ($do == 'apiinfo') {
	$template_ch_name = system_template_ch_name();
	if (checksubmit('submit')) {
		$data = array(
				'jy_url' => trim($_GPC['jy_url']),
				'jy_token' => trim($_GPC['jy_token']),
				'jy_secret' => trim($_GPC['jy_secret'])
			);
		$test = setting_save($data, 'apiinfo');
		$template = trim($_GPC['template']);
		setting_save(array('template' => $template), 'basic');
		itoast('更新设置成功！', url('system/apisite'), 'success');
	}
}

template('system/apisite');