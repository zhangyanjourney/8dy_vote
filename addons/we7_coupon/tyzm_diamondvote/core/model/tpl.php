<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Tyzm_Tpl{
	public function __construct() {
		global $_W;
	} 
	
	public function style($filename,$tmp="") {
		if(empty($tmp)){
			return $filename;
		}else{
			$source = TYZM_MODEL . "/template/mobile/".$tmp."/".$filename.".html";
			if(!is_file($source)) {
				return $filename;
			}else{
				return $tmp."/".$filename;
			}
		}
	} 
	
	
	function tpl_input($value = array()) {
		foreach ($value as $row) {
			$js.='
			var '.$row['infotype'].'=$("*[name=\''.$row['infotype'].'\']").val();';
			if(!empty($row['notnull'])){
			$js.='	
			if('.$row['infotype'].'==""){dialog2("请输入'.$row['infoname'].'");return false;}';
			}
			switch($row['infotype']){
				case 'mobile':
					$html .= '<li><div class="tlt">'.$row['infoname'].'</div><div class="cont"><input name="'.$row['infotype'].'"  type="tel" placeholder="请输入'.$row['infoname'].'" class="tx"></div></li>';
					$js.='
					if(!(/^1[3|4|5|6|7|8|9][0-9]\d{8}$/.test(mobile))){dialog2("请输入正确的手机号码！");return false; } ';
				    break;
				case 'email':
					$html .= '<li><div class="tlt">'.$row['infoname'].'</div><div class="cont"><input name="'.$row['infotype'].'"  type="text" placeholder="请输入'.$row['infoname'].'" class="tx"></div></li>';
					$js.='if(!email.match(/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/)){dialog2("请输入正确的电子邮箱！");return false; } ';
					break;
				case 'vqqcom':
					$html .= '<li><div class="tlt">'.$row['infoname'].'</div><div class="cont"><input name="'.$row['infotype'].'"  type="text" placeholder="请输入'.$row['infoname'].'" class="tx"></div><p style="font-size: 0.8em;color: #960f0f;">至腾讯视频页面，“分享”-“复制flash地址”。</p>
					</li>';
					break;
				case 'sex':
					$html .= '<li><div class="tlt">'.$row['infoname'].'</div><input name="'.$row['infotype'].'" type="radio" value="2" checked> 女　　<input name="'.$row['infotype'].'" type="radio" value="1" style="margin-left:5%"> 男</li>			';
					$js.='
					var '.$row['infotype'].'=$("input[name=\''.$row['infotype'].'\']:checked").val();';				
				    break;
				case 'bio':
				case 'interest':
					$html .= '<li><div class="tlt">'.$row['infoname'].'</div>
					<div class="cont">
						<textarea name="'.$row['infotype'].'" class="ta"  placeholder="请输入'.$row['infoname'].'"></textarea></div>
					</li>';
					 break;
				default:
				    $html .= '<li><div class="tlt">'.$row['infoname'].'</div><div class="cont"><input name="'.$row['infotype'].'"  type="text" placeholder="请输入'.$row['infoname'].'" class="tx"></div></li>';
					break;
			}
			
			$input.=$row['infotype'].":".$row['infotype'].",";
		}
		

		$res=array($html,$js,$input);
		return $res;
		
	}
	function tpl_inputweb($styp = array(),$value = array()) {
		foreach ($styp as $key =>$row){

			switch($row['infotype']){
				case 'sex':
				$html.='<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> '.$row["infoname"].'</label>
					<div class="col-sm-8 col-xs-12">
						<input type="text" class="form-control" name="join['.$row["infoname"].']" value="'.$value[$key]["val"].'"/>
						<span class="help-block">2为“女”，1为“男”</span>
					</div>
				</div>  ';
				
				 break;
				case 'bio':
				case 'interest':
				$html.='<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> '.$row["infoname"].'</label>
					<div class="col-sm-8 col-xs-12">
						<textarea name="join['.$row["infoname"].']" class="form-control js-a" cols="30" rows="2">'.$value[$key]["val"].'</textarea>
						<span class="help-block"></span>
					</div>
				   </div>  ';
				break;
				default:
				    $html.='<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> '.$row["infoname"].'</label>
					<div class="col-sm-8 col-xs-12">
						<input type="text" class="form-control" name="join['.$row["infoname"].']" value="'.$value[$key]["val"].'"/>
						<span class="help-block"></span>
					</div>
				   </div>  ';
					break;
				
			}
				
			
		}
		

		
		return $html;
		
	}

function tpl_app_form_field_image_tyzm($name, $value = '',$rid) {
	global $_W;
	$thumb = empty($value) ? 'images/global/nopic.jpg' : $value;
	$thumb = tomedia($thumb);
	$file="index.php?i=".$_W['uniacid']."&c=entry&do=file&m=tyzm_diamondvote&rid=".$rid;
	$html = <<<EOF
	<div class="mui-table-view-chevron">
		<div class="mui-image-uploader">
			<a href="javascript:;" class="mui-upload-btn mui-pull-right js-image-{$name}"></a>
			<div class="mui-image-preview js-image-preview mui-pull-right"></div>
		</div>
	</div>
	<script>
		util.image($('.js-image-{$name}'), function(url){
			$('.js-image-{$name}').parent().find('.js-image-preview').append('<input type="hidden" value="'+url.attachment+'" name="{$name}[]" /><img src="'+url.url+'" data-id="'+url.id+'" data-preview-src="" data-preview-group="__IMG_UPLOAD_{$name}" />');
		}, {
			crop : false,
			multiple : true,
			server:"{$file}",
			preview : '__IMG_UPLOAD_{$name}'
		});
	</script>
EOF;
	return $html;
}

function tpl_form_field_image_tyzm($name, $value = '', $default = '', $options = array()) {
	global $_W;
	if (empty($default)) {
		$default = './resource/images/nopic.jpg';
	}
	$val = $default;
	if (!empty($value)) {
		$val = tomedia($value);
	}
	if (!empty($options['global'])) {
		$options['global'] = true;
	} else {
		$options['global'] = false;
	}
	if (empty($options['class_extra'])) {
		$options['class_extra'] = '';
	}
	if (isset($options['dest_dir']) && !empty($options['dest_dir'])) {
		if (!preg_match('/^\w+([\/]\w+)?$/i', $options['dest_dir'])) {
			exit('图片上传目录错误,只能指定最多两级目录,如: "we7_store","we7_store/d1"');
		}
	}
	$options['server'] = "index.php?i=".$_W['uniacid']."&c=entry&do=file&m=tyzm_diamondvote&rid=".$rid;;
	$options['direct'] = true;
	$options['multiple'] = false;
	if (isset($options['thumb'])) {
		$options['thumb'] = !empty($options['thumb']);
	}
	$options['fileSizeLimit'] = intval($GLOBALS['_W']['setting']['upload']['image']['limit']) * 1024;
	$s = '';
	if (!defined('TPL_INIT_IMAGE')) {
		$s = '
		<script type="text/javascript">
			function showImageDialog(elm, opts, options) {
				require(["util"], function(util){
					var btn = $(elm);
					var ipt = btn.parent().prev();
					var val = ipt.val();
					var img = ipt.parent().next().children();
					options = '.str_replace('"', '\'', json_encode($options)).';
					util.image(val, function(url){
						if(url.url){
							if(img.length > 0){
								img.get(0).src = url.url;
							}
							ipt.val(url.attachment);
							ipt.attr("filename",url.filename);
							ipt.attr("url",url.url);
						}
						if(url.media_id){
							if(img.length > 0){
								img.get(0).src = "";
							}
							ipt.val(url.media_id);
						}
					}, options);
				});
			}
			function deleteImage(elm){
				$(elm).prev().attr("src", "./resource/images/nopic.jpg");
				$(elm).parent().prev().find("input").val("");
			}
		</script>';
		define('TPL_INIT_IMAGE', true);
	}

	$s .= '
		<div class="input-group ' . $options['class_extra'] . '">
			<input type="text" name="' . $name . '" value="' . $value . '"' . ($options['extras']['text'] ? $options['extras']['text'] : '') . ' class="form-control" autocomplete="off">
			<span class="input-group-btn">
				<button class="btn btn-default" type="button" onclick="showImageDialog(this);">选择图片</button>
			</span>
		</div>
		<div class="input-group ' . $options['class_extra'] . '" style="margin-top:.5em;">
			<img src="' . $val . '" onerror="this.src=\'' . $default . '\'; this.title=\'图片未找到.\'" class="img-responsive img-thumbnail" ' . ($options['extras']['image'] ? $options['extras']['image'] : '') . ' width="150" />
			<em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="deleteImage(this)">×</em>
		</div>';
	return $s;
}

function tpl_footer() {
	global $_W;
	$html ='
<script>
function dialog2(msg){$("#dialog2 .weui-dialog__bd").html(msg);$("#dialog2").show()}function hidemod(boxid){$("#"+boxid).hide()}function loadingToast(msg){$("#loadingToast .weui-toast__content").html(msg);$("#loadingToast").show()}function toast(msg){$("#toast .weui-toast__content").html(msg);$("#toast").show();setTimeout("hidemod(\'toast\')",3000)}</script>';
	return $html;
}

}