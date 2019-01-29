<?php
defined('IN_IA') or exit('Access Denied');
require IA_ROOT . '/addons/tyzm_diamondvote/defines.php';
global $_W, $_GPC;
define('TG_PATH', IA_ROOT.'/addons/'.$_GPC['m'].'/');
require TG_PATH.'version.php';
load ()->func ( 'tpl' );
load()->func('file');
load()->func('communication');
$domain = getDomain();
$ip     = getUserIP();
$code   = getVerifyCode();
$status = hasVerify();
if (checksubmit('submit')) {		
	$domain = $_GPC['domain'];
	$ip     = $_GPC['ip'];
	$code   = $_GPC['code'];
	$data = ['ip' => $ip,'domain' => $domain,'siteid' => 201900,'code' => $code,];
	setVerifyCode($code);
	$resp = ihttp_request(HOSTAPI,array('type'=> 'grant','module' => $_GPC['m'],'website' => 201900,'domain' => $domain,'code' => $code),null,1);
	$resp = @json_decode($resp['content'], true);
	if ($resp['errno'] === 0) {
		message($resp['message'], $this -> createWebUrl('auth'), 'success');
	}else{
		message($resp['message'], '', 'error');
		}
}
include $this -> template('auth');
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