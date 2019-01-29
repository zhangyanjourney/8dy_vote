<?php

defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;

is_weixin();
$rid=intval($_GPC['rid']);
$id=intval($_GPC['id']);
$sourceid=intval($_GPC['sourceid']);
$id=empty($id)?$sourceid:$id;
$ty=$_GPC['ty'];
$count=intval($_GPC['count']);
$count=empty($count) ? 1 : $count;

$userinfo=$this->oauthuser;
$oauth_openid=$userinfo['oauth_openid'];
m('domain')->randdomain($rid,1);
if(empty($oauth_openid)){
	message("无法获取OPNEID，请查看是否借权或配置好公众号！(0101)"); 
}
$reply = pdo_fetch("SELECT rid,title,sharetitle,shareimg,sharedesc,config,style,addata,prizemsg,giftdata,starttime,endtime,apstarttime,apendtime,votestarttime,voteendtime,status,description  FROM " . tablename($this->tablereply) . " WHERE rid = :rid ", array(':rid' => $rid));
$addata=@unserialize($reply['addata']);	
$reply['style']=@unserialize($reply['style']);
$reply=array_merge ($reply,unserialize($reply['config']));unset($reply['config']);
if(empty($reply['status'])){message("活动已禁用");}
if(empty($reply)){
	message("参数错误"); 
}

if($reply['starttime']>time()){
	message("活动还没有开始"); 
}
 
//活动未开始
if($reply['endtime']<time()){
	message("活动已经结束"); 
}

//活动未开始
if(empty($reply['status'])){
	message("活动已禁用"); 
}

//投票时间
if($reply['votestarttime']> time()){
	message("未开始投票！"); 
}elseif($reply['voteendtime']<time()){
	message("已结束投票！");
}
$giftdata=@unserialize($reply['giftdata']);	

$noid = pdo_fetch("SELECT noid FROM " . tablename($this->tablevoteuser) . " WHERE rid = :rid ",array(':rid' => $rid));

$voteuser = pdo_fetch("SELECT * FROM " . tablename($this->tablevoteuser) . " WHERE rid = :rid AND  id = :id ", array(':rid' => $rid,':id' => $id));

$voteuser['avatar']=!empty($voteuser['avatar'])?$voteuser['avatar']:tomedia($voteuser["img1"]); 

$_share['title'] =!empty($reply['sharetitle'])?$reply['sharetitle']:$reply['title'];
$_share['imgUrl'] =!empty($reply['shareimg'])?tomedia($reply['shareimg']):tomedia($reply['thumb']);
$_share['desc'] =!empty($reply['sharedesc'])?$reply['sharedesc']:$reply['description'];
$_W['page']['sitename']=$reply['title'];


include $this->template(m('tpl')->style('vote_success',$reply['style']['template']));






