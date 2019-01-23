<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W;
$_W['page']['title'] = '添加用户 - 用户管理 - 用户管理';
$this->authorization();
$op = $_GPC{'op'} ? $_GPC{'op'} : "";
if(checksubmit()) {
    load()->model('user');
    $user = array();
    $user['username'] = trim($_GPC['username']);
    if(!preg_match(REGULAR_USERNAME, $user['username'])) {
        message('必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。');
    }
    if($op != 'edit') {
        if(user_check(array('username' => $user['username']))) {
            message('非常抱歉，此用户名已经被注册，你需要更换注册名称！');
        }
    }
    $user['password'] = $_GPC['password'];
    if(istrlen($user['password']) < 8) {
        message('必须输入密码，且密码长度不得低于8位。');
    }
    $user['salt'] = random(8);
    $user['password'] = user_hash($user['password'], $user['salt']);
    $user['joinip'] = CLIENT_IP;
    $user['joindate'] = TIMESTAMP;
    $user['lastip'] = CLIENT_IP;
    $user['lastvisit'] = TIMESTAMP;
    if (empty($user['status'])) {
        $user['status'] = 2;
    }
    if (empty($user['type'])) {
        $user['type'] = USER_TYPE_COMMON;
    }

    /*$user['uniacid'] = intval($_GPC['uniacid']) ? intval($_GPC['uniacid']) : message('请选择所属公众号');*/
    $user['uniacid'] = $_W['uniacid'];
    $user['starttime'] = TIMESTAMP;


    if ($op == 'edit') {
        $uid = pdo_update('users', $user, array('uid'=>$_GPC['uid']));
        message('用户更新成功！', $this->createWebUrl('child', array()));
    } else {
        $uid = pdo_insert('users', $user);
        if($uid > 0) {
            unset($user['password']);

            $account_data = array();
            $account_data['uniacid'] = $_W['uniacid'];
            $account_data['role'] = 'owner';
            $account_data['uid'] = pdo_insertid();
            $account_uid = pdo_insert('uni_account_users', $account_data);

            if($account_uid > 0) {
                message('用户增加成功！', $this->createWebUrl('child', array()));
            } else {
                pdo_delete('users', array('uid'=>$account_data['uid']));
                message('增加用户失败，请稍候重试或联系网站管理员解决！');
            }
        } else {
            message('增加用户失败，请稍候重试或联系网站管理员解决！');
        }
    }
}

if ($op == 'edit') {
    $condition = ' WHERE uid = '.$_GPC['uid'];
    $user_edit = pdo_fetch("SELECT * FROM ".tablename('users'). $condition);

} else if ($op == 'del') {
    pdo_delete(users, array('uid'=>$_GPC['uid']));
    pdo_delete(uni_account_users, array('uid'=>$_GPC['uid']));
    header('Location:' . $this->createWeburl('child'));
    exit;
}
include $this->template('childadd');
