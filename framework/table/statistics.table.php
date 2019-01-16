<?php
/**
 */

defined('IN_IA') or exit('Access Denied');
class StatisticsTable extends We7Table {
	
	protected $stat_fans_table ='stat_fans';
	protected $stat_visit_table = 'stat_visit';
	protected $stat_gift_count = 'tyzm_diamondvote_gift';
	
	public function visitList($params, $type = 'more') {
		global $_W;
		$this->query->from($this->stat_visit_table);
		if (!empty($params['uniacid'])) {
			$this->query->where('uniacid', $params['uniacid']);
		}
		if (!empty($params['date'])) {
			$this->query->where('date', $params['date']);
		}
		if (!empty($params['date >='])) {
			$this->query->where('date >=', $params['date >=']);
		}
		if (!empty($params['date <='])) {
			$this->query->where('date <=', $params['date <=']);
		}
		if (!empty($params['module'])) {
			$this->query->where('module', $params['module']);
		}
		if (!empty($params['type'])) {
			$this->query->where('type', $params['type']);
		}

		if ($type == 'one') {
			return $this->query->get();
		} else {
			return $this->query->getall();
		}
	}

	public function visitListGiftCount($params, $type = 'more') {
        global $_W;
        $this->query->from($this->$stat_gift_count);
        if (!empty($params['uniacid'])) {
            $this->query->where('uniacid', $params['uniacid']);
        }
        if (!empty($params['date'])) {
            $this->query->where('date', $params['date']);
        }
        if (!empty($params['date >='])) {
            $this->query->where('date >=', $params['date >=']);
        }
        if (!empty($params['date <='])) {
            $this->query->where('date <=', $params['date <=']);
        }
        if (!empty($params['ispay'])) {
            $this->query->where('type', $params['ispay']);
        }

        if ($type == 'one') {
            return $this->query->get();
        } else {
            return $this->query->getall();
        }
    }
}