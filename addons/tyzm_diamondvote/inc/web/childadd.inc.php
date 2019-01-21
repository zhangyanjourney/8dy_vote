<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W;
$_W['page']['title'] = '添加用户 - 用户管理 - 用户管理';
$this->authorization();
if(checksubmit()) {
    load()->model('user');
    $user = array();
    $user['username'] = trim($_GPC['username']);
    if(!preg_match(REGULAR_USERNAME, $user['username'])) {
        message('必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。');
    }
    if(user_check(array('username' => $user['username']))) {
        message('非常抱歉，此用户名已经被注册，你需要更换注册名称！');
    }
    $user['password'] = $_GPC['password'];
    if(istrlen($user['password']) < 8) {
        message('必须输入密码，且密码长度不得低于8位。');
    }
    $user['uniacid'] = intval($_GPC['uniacid']) ? intval($_GPC['uniacid']) : message('请选择所属公众号');
    /*    $group = pdo_fetch("SELECT id,timelimit,price FROM ".tablename('users_group')." WHERE id = :id", array(':id' => $user['groupid']));
    if(empty($group)) {
        message('会员组不存在');
    }
    $timelimit = intval($group['timelimit']);
    $timeadd = 0;
    if($timelimit > 0) {
        $timeadd = strtotime($timelimit . ' days');
    }
    $now = time();
    if(empty($timeadd)){
        $timeadd = $now + 7 * 24 * 3600;
    }
    $user['starttime'] = TIMESTAMP;
    $user['endtime'] = $timeadd;
    */
    $user['starttime'] = TIMESTAMP;
/*    if(!$_W['isfounder']){
        $user['agentid']=$_W['uid'];
        $price =$group['price'];
        $credit =$_W['user']['credit2']-$price;
        if($credit < 0){message('账户余额不足，开通本用户组会员需花费'.$price,url('shop/member/member'));}
        pdo_update('users',array('credit2'=>$credit),array('uid'=>$_W['uid']));
        $data =array(
            'uid'=>$_W['uid'],
            'credittype'=>'credit2',
            'num'=>-$price,
            'createtime'=>TIMESTAMP,
            'remark'=>'添加用户'
        );
        pdo_insert('users_credits_record',$data);
    }*/
    $uid = user_register($user);
    if($uid > 0) {
        unset($user['password']);
/*        message('用户增加成功！', url('user/myxiaji', array('uid' => $uid)));*/
        message('用户增加成功！', $this->createWebUrl('child', array()));
    }
    message('增加用户失败，请稍候重试或联系网站管理员解决！');
}
//$groups = pdo_fetchall("SELECT id, name FROM ".tablename('users_group')." ORDER BY id ASC");
$op = $_GPC{'op'} ? $_GPC{'op'} : "";
$user_list = pdo_fetchall("SELECT * FROM".tablename('account_wechats'). "ORDER BY uniacid ASC");

if ($op == 'edit') {
    $condition = ' WHERE uid = '.$_GPC['uid'];
    $user_edit = pdo_fetchall("SELECT * FROM".tablename('users') .$condition);
}
include $this->template('childadd');