<?php
defined('IN_IA') or die('Access Denied');
require IA_ROOT . '/addons/tyzm_diamondvote/defines.php';
require TYZM_MODEL_FUNC . '/function.php';
class tyzm_diamondvoteModuleSite extends WeModuleSite
{
	public $tablereply = "tyzm_diamondvote_reply";
	public $tablevoteuser = "tyzm_diamondvote_voteuser";
	public $tablevotedata = "tyzm_diamondvote_votedata";
	public $tablegift = "tyzm_diamondvote_gift";
	public $tablecount = "tyzm_diamondvote_count";
	public $table_fans = "tyzm_diamondvote_fansdata";
	public $tableredpack = "tyzm_diamondvote_redpack";
	public $tablefriendship = "tyzm_diamondvote_friendship";
	public $tablelooklist = "tyzm_diamondvote_looklist";
	public $tableviporder = "tyzm_diamondvote_viporder";
	public $tableblacklist = "tyzm_diamondvote_blacklist";
	public $tabledomainlist = "tyzm_diamondvote_domainlist";
	public $tablesetmeal = "tyzm_diamondvote_setmeal";
	public $tablefeedback = "tyzm_diamondvote_feedback";
	public $tablemlog = "tyzm_diamondvote_mlog";
	public $tablerobots = "tyzm_diamondvote_robots";
	public $addnum = "0";
	public function __construct()
	{
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if (!(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false)) {
			$oauthuser = m('user')->Get_checkoauth();
			$this->oauthuser = $oauthuser;
		}
	}
	public function payResult($params)
	{
		global $_W, $_GPC;
		if ($params['result'] == 'success' && $params['from'] == 'notify') {
			$tycode = substr($params['tid'], 0, 4);
			if ($tycode == '8888') {
				$viporder = pdo_fetch('SELECT * FROM ' . tablename($this->tableviporder) . " WHERE ptid = :ptid", array(":ptid" => $params["tid"]));
				if ($params['fee'] == $viporder['fee'] && $viporder['ispay'] == 0) {
					$reviporder = pdo_update($this->tableviporder, array("ispay" => "1", "paytype" => $params["type"], "uniontid" => $params["uniontid"]), array("ptid" => $params["tid"]));
				}
				die;
			}
			$order = pdo_fetch('SELECT * FROM ' . tablename($this->tablegift) . " WHERE ptid = :ptid", array(":ptid" => $params["tid"]));
			if ($params['fee'] == $order['fee'] && $order['ispay'] == 0) {
				$reupvote = pdo_update($this->tablegift, array("ispay" => "1", "isdeal" => "1", "paytype" => $params["type"], "uniontid" => $params["uniontid"]), array("ptid" => $params["tid"], "oauth_openid" => $params["user"]));
				if (!empty($reupvote)) {
					$setvotesql = 'update ' . tablename($this->tablevoteuser) . " set votenum=votenum+" . $order["giftvote"] . ",giftcount=giftcount+" . $order["fee"] . ",lastvotetime=" . time() . "  where id = " . $order["tid"];
					$resetvote = pdo_query($setvotesql);
					if (empty($resetvote)) {
						pdo_update($this->{$tablegift}, array('isdeal' => 0), array('ptid' => $params['tid']));
					} else {
						$reply = pdo_fetch('SELECT config FROM ' . tablename($this->tablereply) . " WHERE rid = :rid ", array(":rid" => $order["rid"]));
						$reply = array_merge($reply, unserialize($reply['config']));
						unset($reply['config']);
						if (empty($reply['isvotemsg']) || !empty($reply['awardgive_num'])) {
							$votedata = pdo_fetch('SELECT * FROM ' . tablename($this->tablevoteuser) . " WHERE id = :id ", array(":id" => $order["tid"]));
						}
						if (!empty($reply['giftgive_num'])) {
							m('present')->upcredit($order["openid"], $reply["giftgive_type"], $reply["giftgive_num"] * $params["fee"], "tyzm_diamondvote");
						}
						if (!empty($reply['awardgive_num'])) {
							m('present')->upcredit($votedata["openid"], $reply["awardgive_type"], $reply["awardgive_num"] * $params["fee"], "tyzm_diamondvote");
						}
						if (empty($reply['isvotemsg'])) {
							$uservoteurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl("view", array("rid" => $order["rid"], "id" => $votedata["id"]));
							$content = '您的好友【' . $order['nickname'] . '】给你' . $votedata['noid'] . '号【' . $votedata['name'] . '】送【' . $order['gifttitle'] . '】作为礼物！目前礼物共￥' . $votedata['giftcount'] . '，目前共' . $votedata['votenum'] . '票。<a href=\\"' . $uservoteurl . '\\">点击查看详情<\\/a>';
							m('user')->sendkfinfo($votedata["openid"], $content);
						}
					}
				}
			} else {
				die('用户支付的金额与订单金额不符合或已修改状态。');
			}
		}
		if ($params['from'] == 'return') {
			if ($params['result'] == 'success') {
				$tycode = substr($params['tid'], 0, 4);
				if ($tycode == '8888') {
					$order = pdo_fetch('SELECT rid,tid,uniacid FROM ' . tablename($this->tableviporder) . " WHERE ptid = :ptid", array(":ptid" => $params["tid"]));
					$url = murl('entry/module/payresult', array('m' => 'tyzm_diamondvote', 'ty' => 'user', 'rid' => $order['rid'], 'id' => $order['tid'], 'i' => $order['uniacid']));
				} else {
					$order = pdo_fetch('SELECT id,tid,rid,uniacid FROM ' . tablename($this->tablegift) . " WHERE  ptid = :ptid ", array(":ptid" => $params["tid"]));
					$url = murl('entry/module/payresult', array('m' => 'tyzm_diamondvote', 'rid' => $order['rid'], 'id' => $order['tid'], 'i' => $order['uniacid']));
				}
				header('location: ' . $_W['siteroot'] . 'app/' . $url);
			} else {
				message('抱歉，支付失败，请刷新后再试！', 'referer', 'error');
			}
		}
	}
	public function authorization()
	{
	}
	public function doMobileGetrobots()
	{
		global $_W, $_GPC;
		$list = pdo_fetchall("SELECT * FROM " . tablename($this->tablerobots) . " WHERE 1 ORDER BY `id` DESC", array(':time' => time()));
		foreach($list as &$val){
			$val['users'] = explode(',', $val['voteuser']);
			$val['starttime_cn'] = date('Y-m-d H:i:s',$val['starttime']);
			$val['endtime_cn'] = date('Y-m-d H:i:s',$val['endtime']);
			$val['createtime_cn'] = date('Y-m-d H:i:s',$val['createtime']);
			$val['endtime']<time()?($val['status_cn']="已结束").($val['status_style']='default'):($val['starttime']>time()?($val['status_cn']="未开始").($val['status_style']='warning'):($val['status_cn']="等待中").($val['status_style']='success'));
			$val['votename'] = pdo_fetchcolumn('SELECT title FROM ' . tablename($this->tablereply) . " WHERE uniacid = '{$_W['uniacid']}' AND rid = '".$val['voterid']."' AND status = 1 ");
			!$val['votename'] && $val['votename']="活动不存在或已停止或已删除！";
			
			
		}
		$this->json_exit(1, "获取机器人列表成功！", $list);
	}

	/*
	public function doMobileRobotsrun()
	{
		ignore_user_abort();
		set_time_limit(0);
		global $_W, $_GPC;
		$_GPC['pwd']!='666666' && $this->json_exit(0, "机器人密码错误！", $list);
		$robotid = $_GPC['robotid']?intval($_GPC['robotid']):0;
		$rid = $_GPC['rid']?intval($_GPC['rid']):0;
		$noid = $_GPC['noid']?intval($_GPC['noid']):0;
		$res = pdo_update($this->tablevoteuser, array('votenum +='=>1), array('noid' => $noid, 'rid' => $rid));
		if(!empty($res)){
			pdo_update($this->tablerobots, array('sumadd +='=>1), array('id' => $robotid));
			
			$tid = pdo_fetchcolumn("SELECT id FROM " . tablename($this->tablevoteuser) . " WHERE noid = :noid AND rid = :rid ", array(':noid' => $noid, ':rid' => $rid));
			$setpv = 'update ' . tablename($this->tablecount) . ' set pv_total=pv_total+1 where tid = '.$tid.' AND rid='.$rid.' AND uniacid='.$_W['uniacid'];
			if(!pdo_query($setpv)){
				$count=pdo_fetch("SELECT * FROM " . tablename($this->tablecount) . " WHERE tid = :tid AND rid = :rid ", array(':tid' => $tid,':rid' => $rid));
				if(empty($count)){
					$indata=array(
						'tid'=>$tid,
						'rid'=>$rid,
						'uniacid'=>$_W['uniacid'],
						'pv_total'=>1,
					);
					pdo_insert($this->tablecount, $indata); 
				}
				
			}
			$this->json_exit(1, "加票成功！");
		}else{
			$this->json_exit(0, "加票用户不存在！活动ID：".$rid.",用户编号：".$noid);
		}
	}*/

/*	//随机加票，参考上面函数
    public function doMobileRobotsrun()
    {
        ignore_user_abort();
        set_time_limit(0);
        global $_W, $_GPC;
        //$_GPC['pwd']!='666666' && $this->json_exit(0, "机器人密码错误！", $list);
        $robotid = $_GPC['robotid']?intval($_GPC['robotid']):0;
        $rid = $_GPC['rid']?intval($_GPC['rid']):0;
        $noid = $_GPC['noid']?intval($_GPC['noid']):0;
        $vote_num = rand(0,5);
        $res = pdo_update($this->tablevoteuser, array('votenum +='=>$vote_num), array('noid' => $noid, 'rid' => $rid));
        if(!empty($res)){
            pdo_update($this->tablerobots, array('sumadd +='=>$vote_num), array('id' => $robotid));

            $tid = pdo_fetchcolumn("SELECT id FROM " . tablename($this->tablevoteuser) . " WHERE noid = :noid AND rid = :rid ", array(':noid' => $noid, ':rid' => $rid));
            $setpv = 'update ' . tablename($this->tablecount) . ' set pv_total=pv_total+'.$vote_num.' where tid = '.$tid.' AND rid='.$rid.' AND uniacid='.$_W['uniacid'];
            if(!pdo_query($setpv)){
                $count=pdo_fetch("SELECT * FROM " . tablename($this->tablecount) . " WHERE tid = :tid AND rid = :rid ", array(':tid' => $tid,':rid' => $rid));
                if(empty($count)){
                    $indata=array(
                        'tid'=>$tid,
                        'rid'=>$rid,
                        'uniacid'=>$_W['uniacid'],
                        'pv_total'=>$vote_num,
                    );
                    pdo_insert($this->tablecount, $indata);
                }

            }
            $this->json_exit(1, "加票成功！");
        }else{
            $this->json_exit(0, "加票用户不存在！活动ID：".$rid.",用户编号：".$noid);
        }
    }*/
	
	/*占用大量服务器资源 - 弃用*/
	/*public function doMobileRobotsrun()
	{
		ignore_user_abort();
		set_time_limit(0);
		global $_W, $_GPC;
		$_GPC['pwd']!='666666' && exit("机器人密码错误！\n\r");
		
		$list = pdo_fetchall("SELECT * FROM " . tablename($this->tablerobots) . " WHERE starttime < :time AND endtime > :time ORDER BY `id` DESC", array(':time' => time()));
		foreach($list as &$val){
			$users = explode(',', $val['voteuser']);
			foreach($users as &$user){
				$num += $this->addrun($user, $val['voterid'], $val['randomlow'], $val['randomhigh'], $val['endtime'], $val['id']);
			}
		}
		echo "机器人加票成功，一共增加".$this->addnum."票！\n\r";
	}
	
	public function addrun($noid, $rid = 0, $l = 0, $h = 10, $endtime, $robotid=0)
	{
		global $_W, $_GPC;
		$t = time();
		$i = 0;
		
		while ($t + 20 > time() && $endtime > time()) {
			sleep(rand($l, $h));
			$res = pdo_update($this->tablevoteuser, array('votenum +='=>1), array('noid' => $noid, 'rid' => $rid));
			if(!empty($res)){
				
				pdo_update($this->tablerobots, array('sumadd +='=>1), array('id' => $robotid));
				
				$tid = pdo_fetchcolumn("SELECT id FROM " . tablename($this->tablevoteuser) . " WHERE noid = :noid AND rid = :rid ", array(':noid' => $noid, ':rid' => $rid));
				$setpv = 'update ' . tablename($this->tablecount) . ' set pv_total=pv_total+1 where tid = '.$tid.' AND rid='.$rid.' AND uniacid='.$_W['uniacid'];
				if(!pdo_query($setpv)){
					$count=pdo_fetch("SELECT * FROM " . tablename($this->tablecount) . " WHERE tid = :tid AND rid = :rid ", array(':tid' => $tid,':rid' => $rid));
					if(empty($count)){
						$indata=array(
							'tid'=>$tid,
							'rid'=>$rid,
							'uniacid'=>$_W['uniacid'],
							'pv_total'=>1,
						);
						pdo_insert($this->tablecount, $indata); 
					}
				}
				
				++$this->addnum;
				++$i;
			}
		}
		return $i;
	}*/

	//随机加票，参考上面的函数
    public function doMobileRobotsrun()
{
    ignore_user_abort();
    set_time_limit(0);
    global $_W, $_GPC;
    //$_GPC['pwd']!='666666' && exit("机器人密码错误！\n\r");

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->tablerobots) . " WHERE starttime < :time AND endtime > :time ORDER BY `id` DESC", array(':time' => time()));
    foreach($list as &$val){
        $users = explode(',', $val['voteuser']);
        foreach($users as &$user){
            $num += $this->addrun($user, $val['voterid'], $val['randomlow'], $val['randomhigh'], $val['endtime'], $val['id']);
        }
    }
    echo "机器人加票成功，一共增加".$this->addnum."票！\n\r";
}

public function addrun($noid, $rid = 0, $l = 0, $h = 10, $endtime, $robotid=0)
{
    global $_W, $_GPC;
    $t = time();
    $i = 0;
    $vote_num = rand(0,5);

    while ($t + 20 > time() && $endtime > time()) {
        sleep(rand($l, $h));
        $res = pdo_update($this->tablevoteuser, array('votenum +='=>$vote_num), array('noid' => $noid, 'rid' => $rid));
        if(!empty($res)){

            pdo_update($this->tablerobots, array('sumadd +='=>$vote_num), array('id' => $robotid));

            $tid = pdo_fetchcolumn("SELECT id FROM " . tablename($this->tablevoteuser) . " WHERE noid = :noid AND rid = :rid ", array(':noid' => $noid, ':rid' => $rid));
            $setpv = 'update ' . tablename($this->tablecount) . ' set pv_total=pv_total+'.$vote_num1.' where tid = '.$tid.' AND rid='.$rid.' AND uniacid='.$_W['uniacid'];
            if(!pdo_query($setpv)){
                $count=pdo_fetch("SELECT * FROM " . tablename($this->tablecount) . " WHERE tid = :tid AND rid = :rid ", array(':tid' => $tid,':rid' => $rid));
                if(empty($count)){
                    $indata=array(
                        'tid'=>$tid,
                        'rid'=>$rid,
                        'uniacid'=>$_W['uniacid'],
                        'pv_total'=>$vote_num,
                    );
                    pdo_insert($this->tablecount, $indata);
                }
            }

            $this->addnum += $vote_num;
            ++$i;
        }
    }
    return $i;
}

	
	public function doMobileRrcodeurl()
	{
		global $_W, $_GPC;
		$url = $_GPC['url'];
		require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
		$errorCorrectionLevel = 'L';
		$matrixPointSize = '6';
		QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize);
		die;
	}
	public function template_footer($name)
	{
	}
	public function oauth_uniacid()
	{
		global $_W, $_GPC;
		if ($_W['account']['level'] == 4) {
			$uniacid = $_W['uniacid'];
		} else {
			if ($_W['oauth_account']['level'] == 4) {
				$oauth_acid = $_W['oauth_account']['acid'];
				$account_wechats = pdo_fetch('SELECT uniacid FROM ' . tablename('account_wechats') . ' WHERE acid = :acid ', array(':acid' => $oauth_acid));
				$uniacid = $account_wechats['uniacid'];
			} else {
				$uniacid = $_W['uniacid'];
			}
		}
		return $uniacid;
	}
	public function get_resource($pic_path)
	{
		$pathInfo = pathinfo($pic_path);
		switch (strtolower($pathInfo["extension"])) {
			case 'jpg':
			case 'jpeg':
				$imagecreatefromjpeg = 'imagecreatefromjpeg';
				goto k5xn0;
			case 'png':
				$imagecreatefromjpeg = 'imagecreatefrompng';
				goto k5xn0;
			case 'gif':
			default:
				$imagecreatefromjpeg = 'imagecreatefromstring';
				$pic_path = file_get_contents($pic_path);
				goto k5xn0;
		}
		k5xn0:
		$resource = $imagecreatefromjpeg($pic_path);
		return $resource;
	}
	public function json_exit($status, $msg, $data="")
	{
		die(json_encode(array('status' => $status, 'msg' => $msg, 'data'=>$data)));
	}
}
function getDomain2(){
        return str_replace('www.', '', strtolower($_SERVER['SERVER_NAME']));
}
function getVerifyCode2(){
	$path = __DIR__.'/inc/web/#diamondvote#.cer';
	if (file_exists($path)) {
		return trim(file_get_contents($path));
	}
}