<?php
/**
 * 投票-首页
 *
 * @author 八度与开发
 * @url http://www.8dfish.com
 */

defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
is_weixin();
$uniacid = intval($_W['uniacid']);
$rid=intval($_GPC['rid']);



$reply = pdo_fetch("SELECT rid,title,topimg,eventrule,thumb,sharetitle,shareimg,sharedesc,addata,config,style,endtime,showendtime,apstarttime,apendtime,votestarttime,voteendtime,status,description FROM " . tablename($this->tablereply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
$reply['style']=@unserialize($reply['style']);
$reply=@array_merge($reply,unserialize($reply['config']));unset($reply['config']);
$addata=@unserialize($reply['addata']);	

if(empty($reply['status'])){message("活动已禁用");}

m('domain')->randdomain($rid);
//$unisetting = uni_setting_load();

if($reply['apstarttime']> time()){
	$aptime=1;//未开始报名
}elseif($reply['apendtime']<time()){
	$aptime=2;//报名已结束
}

$voteuser = pdo_fetch("SELECT id FROM " . tablename($this->tablevoteuser) . " WHERE rid = :rid AND  openid = :openid ORDER BY `id` DESC", array(':rid' => $rid,':openid' => $this->oauthuser['openid']));
if(!empty($voteuser)){
	$myvoteid=$voteuser['id'];
}
$condition.=" AND uniacid=".$uniacid;

$condition.=" AND status=1 ";

if($_W['ispost']){
	$keyword=$_GPC['keyword'];
	if(empty($keyword)){
		$nowpage=$_GPC['limit'];
	}else{
		$nowpage=1;
		if(is_numeric($keyword)){
			$condition .= " AND noid={$keyword} ";
		}else{
			$condition .= " AND CONCAT(`name`) LIKE '%{$keyword}%'";
		}
	}
	
	if($_GPC['type']=='new'){
		$indexorder .= " ORDER BY `noid` DESC LIMIT ";//最新倒序
	}else{
		switch ($reply['indexorder']) {
			case 11:
			$indexorder .= " ORDER BY `id` DESC LIMIT ";//最新倒叙
			break;
			case 12:
			$indexorder .= " ORDER BY `id` ASC LIMIT ";//最新顺序
			break;
			case 21:
			$indexorder .= " ORDER BY `noid` DESC LIMIT ";//最新倒序
			break;
			case 22:
			$indexorder .= " ORDER BY `noid` ASC LIMIT ";//最新顺序
			break;
			case 31:
			$indexorder .= " ORDER BY `votenum` DESC LIMIT ";//票数倒叙
			break;
			case 32:
			$indexorder .= " ORDER BY `votenum` ASC LIMIT ";//票数顺序
			break;
			case 41:
			$indexorder .= " ORDER BY `giftcount` DESC LIMIT ";//礼物倒叙
			break;
			case 42:
			$indexorder .= " ORDER BY `giftcount` ASC LIMIT ";//礼物顺序
			break;
			case 51:
			$indexorder .= " ORDER BY `lastvotetime` DESC LIMIT ";//最新投票倒叙
			break;
			case 52:
			$indexorder .= " ORDER BY `lastvotetime` ASC LIMIT ";//最新投票顺序
			break;
			default:
			$indexorder .= " ORDER BY `id` DESC LIMIT ";
		}
	}
	
	
	$pindex = max(1, intval($nowpage));
	$psize = $reply['listnum'];
	$list = pdo_fetchall("SELECT * FROM " . tablename($this->tablevoteuser) . " WHERE rid = :rid ".$condition.$indexorder.($pindex-1) * $psize.','.$psize,array(':rid' => $rid));
	if(!empty($keyword) && empty($list)){
	   exit(json_encode(array('status' => 301, 'content' => $list)));
	}
	if (!empty($list)){
		foreach ($list as $key => $value) {
			$list[$key]['img1']=tomedia($list[$key]['img1']);
			$list[$key]['url']=$this->createMobileUrl('view', array('rid' => $rid,'id' => $list[$key]['id']));  
			if($value['attestation']){
				$list[$key]['name']='<img class="jiavicon" src="'.MODULE_URL.'/template/static/images/jiavicon.png" height="16" width="16"/>'.$list[$key]['name'];
			}
			
		}
		$sta =200;
	}else{
		$sta =-103;
	}
	exit(json_encode(array('status' => $sta, 'content' => $list)));
}

$jointotal = pdo_fetchcolumn('SELECT COUNT(id) FROM ' . tablename($this->tablevoteuser) . " WHERE   rid = :rid  ".$condition , array(':rid' => $rid));


$votetotal=pdo_fetchcolumn("SELECT sum(votenum) FROM ".tablename($this->tablevoteuser)." WHERE rid = :rid ", array(':rid' => $rid));

$votetotal=!empty($votetotal)?$votetotal:0;
$vheat=pdo_fetchcolumn("SELECT sum(vheat) FROM ".tablename($this->tablevoteuser)." WHERE rid = :rid ", array(':rid' => $rid));

$pvtotal=pdo_fetchcolumn("SELECT sum(pv_total) FROM ".tablename($this->tablecount)." WHERE rid = :rid ", array(':rid' => $rid));
$pvtotal=$pvtotal+$reply['virtualpv']+$vheat;

$_share['title'] =!empty($reply['sharetitle'])?$reply['sharetitle']:$reply['title'];
$_share['imgUrl'] =!empty($reply['shareimg'])?tomedia($reply['shareimg']):tomedia($reply['thumb']);
$_share['desc'] =!empty($reply['sharedesc'])?$reply['sharedesc']:$reply['description'];
$_share['link'] = $_W['siteroot']."app/".$this->createMobileUrl('index', array('rid' => $rid));
$_W['page']['sitename']=$reply['title'];
include $this->template(m('tpl')->style('index',$reply['style']['template']));















