<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
require IA_ROOT . '/addons/tyzm_diamondvote/defines.php';
global $_W, $_GPC;
define('TG_PATH', IA_ROOT.'/addons/'.$_GPC['m'].'/');
require TG_PATH.'version.php';
load ()->func ( 'tpl' );
load()->func('file');
load()->func('communication');
$status = hasVerify();
$version = TG_VERSION;
$versionfile = TG_PATH.'version.php';
$release = date('YmdHis', filemtime($versionfile));
$domain = trim( preg_replace( "/http(s)?:\/\//", "", rtrim($_W['siteroot'],"/") )  );
$ip = getUserIP();
$code = getVerifyCode();
$resp = ihttp_post(HOSTAPI,array('type' => 'check','module' => $_GPC['m'],'ip' => $ip,'domain' => $domain,'code' => $code,'version' => $version));
$upgrade = json_decode($resp['content'], true);
if (is_array($upgrade)) {
	if ($upgrade['result'] == 1) {
		$files = [];
		if ( ! empty($upgrade['files'])) {
			foreach ($upgrade['files'] as $file) {
				$entry = IA_ROOT.'/addons/'.$_GPC['m'].'/'.$file['path'];
				if ( ! is_file($entry) || md5_file($entry) != $file['md5']) {
					$files[] = [
					'path'     => $file['path'],
					'download' => 0,
					'entry'    => $entry,
					];
				}
            }
        }
		if ( ! empty($files)) {
			$upgrade['files'] = $files;
			$tmpdir = IA_ROOT.'/addons/'.$_GPC['m'].'/temp';
			if ( ! is_dir($tmpdir)) {
				mkdirs($tmpdir);
			}
			file_put_contents($tmpdir.'/file.txt', json_encode($upgrade));
		} else {
				unset($upgrade);
				}
    }
}
if (checksubmit()) {
	if ($upgrade['result'] != 1) {
		message($upgrade['message'], $this -> createWebUrl('upgrade'), 'success');
	}
}
include $this -> template('upgrade');
function hasVerify(){
	global $_GPC;
	$result = ihttp_request(HOSTAPI,array('type' => 'checkauth','module' => $_GPC['m'],'website' => 201900,'domain' => getDomain(),'code' => getVerifyCode()),null,5);
	$result = @json_decode($result['content'], true);
	if ($result['status'] === '1') {
		return getVerifyCode();
	}
		return false;
}
function getUserIP(){
	global $_GPC;
	$resp = ihttp_request(HOSTAPI,array('type'    => 'user','module'  => $_GPC['m'],'website' => 201900,'domain'  => getDomain()),null,1);
	$resp = @json_decode($resp['content'], true);
	$ip   = $resp['ip'];
	return $ip;
}
function getDomain(){
        return str_replace('www.', '', strtolower($_SERVER['SERVER_NAME']));
}
function getVerifyCode(){
	$path = __DIR__.'/#diamondvote#.cer';
	if (file_exists($path)) {
		return trim(file_get_contents($path));
	}
}
function setVerifyCode($code){
	$path = __DIR__.'/#diamondvote#.cer';
	file_put_contents($path, trim($code));
}