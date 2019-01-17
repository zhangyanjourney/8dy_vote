<?php
global $_W,$_GPC;
$uniacid=$_W['uniacid'];
load()->func('tpl');
//操作标识
$today = date("Ymd");
$last30_day = date("Ymd",strtotime("-130 day"));

$condition = "";
$condition .= ' AND day >=' . $last30_day;
$condition .= ' AND day <=' . $today;
$condition .= ' GROUP BY day ORDER BY day ASC';
$result = pdo_fetchall('SELECT day, sum(fee) FROM ' . tablename($this->tablegift) . " WHERE   uniacid = :uniacid AND ispay=1 " . $condition, array(":uniacid" => $_W["uniacid"]));
if (!empty($result)) {
    $this->json_exit(1, "获取礼物金额成功！", $result);
} else {
    $this->json_exit(0, "获取礼物金额失败！", $result);
}