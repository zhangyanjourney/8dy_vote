<?php
/**
 */

defined('IN_IA') or exit('Access Denied');

class RefundTable extends We7Table {
	protected $field = array('uniontid', 'uniacid', 'fee', 'status', 'refund_uniontid', 'reason');
	protected $tableName = 'refund';
	public function createRefundLog($moduleid, $module_name, $tid, $fee, $reason) {
		global $_W;
		$refund_uniontid = date('YmdHis') . $moduleid . random(8,1);
		$paylog = $this->query->from('core_paylog')->where('uniacid', $_W['uniacid'])->where('tid', $tid)->where('module', $module_name)->get();
		$refund = array (
			'uniacid' => $_W['uniacid'],
			'uniontid' => $paylog['uniontid'],
			'fee' => empty($fee) ? $paylog['card_fee'] : $fee,
			'status' => 0,
			'refund_uniontid' => $refund_uniontid,
			'reason' => $reason,
		);
		pdo_insert('core_refundlog', $refund);
		return pdo_insertid();
	}
}