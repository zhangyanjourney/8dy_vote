<?php 
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
load()->model('cloud');
load()->func('communication');
$r = cloud_prepare();
if(is_error($r)) {
	message($r['message'], url('cloud/profile'), 'error');
}

$dos = array('upgrade','bak');
$do = in_array($do, $dos) ? $do : 'upgrade';

if ($do == 'bak') {
	$mdir=IA_ROOT .'/data/systembak/';
	if(!is_dir($mdir)){
		message('没有可回滚的系统版本，或者上次更新没有修改文件，无需回滚！');
	}
	$bak = 0;
	foreach(scandir($mdir) as $temp){
		if($temp=='.'||$temp=='..'){
			continue;
		} 
		if((int)$temp > $bak){
			$bak=$temp;
		}
	}
	if(!$bak){
		message('没有可回滚的系统版本，或者上次更新没有修改文件，无需回滚！');
	}
	$zip = new ZipArchive();
	$zip->open($mdir.'/'.$bak);
	$zip->extractTo(IA_ROOT);
	$zip->close();
	@unlink($mdir.'/'.$bak);
	message('成功将系统回滚至上一次更新前的版本！');
}
if($do == 'upgrade') {
	$_W['page']['title'] = '一键更新 - 云服务';
	if(checksubmit('submit')) {
		$upgrade = cloud_build();
		if (is_error($upgrade)) {
			message($upgrade['message'], '', 'error');
		}
		if($upgrade['upgrade']) {
			message("检测到新版本: <strong>{$upgrade['version']} (Release {$upgrade['release']})</strong>, 请立即更新.", 'refresh');
		} else {
			cache_delete('checkupgrade:system');
			message('检查结果: 恭喜, 你的数据已经是最新. ', 'refresh');
		}
	}
	cache_load('upgrade');
	if(!empty($_W['cache']['upgrade'])) {
		$upgrade = $_W['cache']['upgrade'];
	}
	if(empty($upgrade) ||  TIMESTAMP - $upgrade['lastupdate'] >= 3600 * 24 || $upgrade['upgrade']) {
		$upgrade = cloud_build();
	}
	if(!empty($upgrade['schemas'])) {
		$upgrade['database'] = array();
		foreach($upgrade['schemas'] as $remote) {
			$row = array();
			$row['tablename'] = $remote['tablename'];
			$name = substr($remote['tablename'], 4);
			$local = db_table_schema(pdo(), $name);
			unset($remote['increment']);
			unset($local['increment']);
			if(empty($local)) {
				$row['new'] = true;
			} else {
				$row['new'] = false;
				$row['fields'] = array();
				$row['indexes'] = array();
				$diffs = db_schema_compare($local, $remote);
				if(!empty($diffs['fields']['less'])) {
					$row['fields'] = array_merge($row['fields'], $diffs['fields']['less']);
				}
				if(!empty($diffs['fields']['diff'])) {
					$row['fields'] = array_merge($row['fields'], $diffs['fields']['diff']);
				}
				if(!empty($diffs['indexes']['less'])) {
					$row['indexes'] = array_merge($row['indexes'], $diffs['indexes']['less']);
				}
				if(!empty($diffs['indexes']['diff'])) {
					$row['indexes'] = array_merge($row['indexes'], $diffs['indexes']['diff']);
				}
				$row['fields'] = implode($row['fields'], ' ');
				$row['indexes'] = implode($row['indexes'], ' ');
			}
			$upgrade['database'][] = $row;
		}
	}
}
template('cloud/upgrade');
