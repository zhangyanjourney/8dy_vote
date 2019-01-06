<?php
/**
 */

defined('IN_IA') or exit('Access Denied');

class MessageTable extends We7Table {

	protected $tableName = 'message_notice_log';
	protected $field = array('id', 'message', 'is_read', 'uid', 'sign', 'type', 'status', 'create_time', 'end_time');

	public function messageList($type = '') {
		global $_W;

		if (!user_is_founder($_W['uid']) || user_is_vice_founder($_W['uid'])) {
			$this->query->where('uid', $_W['uid']);
		}

		if (user_is_founder($_W['uid']) && !user_is_vice_founder() && empty($type)) {
			$this->query->where('type !=', array(MESSAGE_USER_EXPIRE_TYPE));
		}

		return $this->query->from($this->tableName)->orderby('id', 'DESC')->getall();

	}

	public function messageRecord() {
		return $this->query->from($this->tableName)->orderby('id', 'DESC')->get();
	}


	public function messageNoReadCount() {
		global $_W;
		if (!user_is_founder($_W['uid']) || user_is_vice_founder($_W['uid'])) {
			$this->query->where('uid', $_W['uid']);
		}
		if (user_is_founder($_W['uid']) && !user_is_vice_founder()) {
			$this->query->where('type !=', array(MESSAGE_USER_EXPIRE_TYPE));
		}
		$list =  $this->query->from($this->tableName)->orderby('id', 'DESC')->getall();
		return count($list);
	}

	public function messageExists($message) {
		return $this->query->from($this->tableName)->where($message)->get();
	}
}