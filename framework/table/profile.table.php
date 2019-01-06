<?php
/**
 */

defined('IN_IA') or exit('Access Denied');

class ProfileTable extends We7Table {
	protected $profileFields = 'profile_fields';

	public function getProfileFields() {
		return $this->query->from($this->profileFields)->getall('field');
	}
}