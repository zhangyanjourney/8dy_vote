<?php
/**
 */
defined('IN_IA') or exit('Access Denied');

class CoresettingTable extends We7Table  {
	protected $tableName = 'core_settings';
	protected $field = array('key', 'value');

	public function getSettingList() {
		return $this->query->from($this->tableName)->getall('key');
	}

	public function settingSave($key, $data) {
		$is_exists = $this->query->from($this->tableName)->where('key', $key)->get();
		if (!empty($is_exists)) {
			$return = table('coresetting')->fillValue(iserializer($data))->whereKey($key)->save();
		} else {
			$return = table('coresetting')->fill(array('key'=> $key, 'value' => iserializer($data)))->save();
		}

		return $return;
	}
}