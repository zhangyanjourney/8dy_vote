<?php
/**
 * 钻石投票-报名
 *
 * @author baduyu
 * @url http://www.8dfish.com
 */

defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
is_weixin();

$uniacid = intval($_W['uniacid']);
$rid=intval($_GPC['rid']);
$reply = pdo_fetch("SELECT * FROM " . tablename($this->tablereply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
$reply['style']=@unserialize($reply['style']);
$reply=array_merge ($reply,unserialize($reply['config']));unset($reply['config']);
if(empty($reply['status'])){json_exit('0', "活动已禁用");}
$addata=@unserialize($reply['addata']);


 if($_W['ispost']){
	if($reply['endtime']<time()){
		$this->json_exit('0', "活动已经结束");
	}

	//活动未开始
	if(empty($reply['status'])){
		$this->json_exit('0', "活动已禁用");
	}

	//是否关注
	if($this->oauthuser['follow']!=1 && $reply['isfollow']>=2 || empty($this->oauthuser['openid'])){
		$this->json_exit('0', "没有关注");
	}
	$feedback = array(
		'rid'=>$rid,
		'uniacid'=>$_W['uniacid'],
		'rtitle'=>$_GPC['rtitle'],
		'title'=>$_GPC['title'],
		'feedbackmark'=>$_GPC['feedbackmark'],
		'feedbacktel'=>$_GPC['feedbacktel'],
		'time'=>time()
	);
	
	pdo_insert($this->tablefeedback,$feedback);
	$insertid=pdo_insertid();

	if($insertid){
		$this->json_exit('1', "成功");
	}else{
		$this->json_exit('0', "发生错误，请重试！");
	}
}else{
	$this->json_exit('0', "参数错误，请重试");
}

