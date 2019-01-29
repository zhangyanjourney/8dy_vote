<?php

global $_W,$_GPC;
$uniacid=$_W['uniacid'];

//操作标识
$op =  isset($_GPC['op']) ? trim($_GPC['op']) : "display";

if($op=='display'){

	
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition="";
	if (!empty($_GPC['title'])) {
		$condition .= " AND CONCAT(`title`) LIKE '%{$_GPC['title']}%'";
	}
	
	if (!empty($_GPC['rtitle'])) {
		$condition .= " AND CONCAT(`rtitle`) LIKE '%{$_GPC['rtitle']}%'";
	}
	
    $list = pdo_fetchall("SELECT * FROM " . tablename($this->tablefeedback) . " WHERE uniacid =:uniacid $condition ORDER BY `id` DESC LIMIT ".($pindex - 1) * $psize.",{$psize} ", array(':uniacid' => $uniacid));

	if(!empty($list)){
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tablefeedback) . " WHERE uniacid = '{$uniacid}' $condition");
		$pager = pagination($total, $pindex, $psize); 
	}
}else if($op=='change'){
	$res = pdo_update($this->tablefeedback,array('status'=>1),array('id'=>$_GPC['id']));
	if(!empty($res)){
		message('投诉处理成功',$this->createWebUrl('feedback'),'success');
	}else{
		message('投诉处理失败',$this->createWebUrl('feedback'),'error');
	}
}else if($op=='del'){
	$res = pdo_delete($this->tablefeedback,array('id'=>$_GPC['id']));
	if(!empty($res)){
		message('投诉删除成功',$this->createWebUrl('feedback'),'success');
	}else{
		message('投诉删除失败',$this->createWebUrl('feedback'),'error');
	}
}else{
	message('链接错误，请重试！',$this->createWebUrl('feedback'),'error');
}

include $this->template('feedback');