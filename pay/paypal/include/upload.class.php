<?php
class fileupload {

	var $attachdir;	
	var $target;
	var $thumbW;
	var $thumbH;
	var $outputboth;
	var $imgthumb;

	var $attach = array();

	function __construct($ifthumb = false, $thumbW = 1, $thumbH = 1, $outputboth = true) {
		$this->attachdir = str_replace('\\', '/', WEB_ROOT .'./attachments');
		$this->imgthumb = $ifthumb;
		$this->thumbW = $thumbW;
		$this->thumbH = $thumbH;
		$this->outputboth = $outputboth;
	}

	function upload_error($attacharray = array()) {
		if(!empty($attacharray)) {
			foreach($attacharray as $attach) {
				@unlink($this->attachdir.'/'.$attach['attachment']);
			}
		}
		header("HTTP/1.0 403 Forbidden");
		exit();
	}

	function upload($varname = 'file') {
		if(!empty($_FILES[$varname]) && $_FILES[$varname]['name'] != '') {
			$attacharray = array();

			static $safeext  = array('jpg', 'jpeg', 'gif', 'png', 'bmp', 'mp3', 'aac', 'wma', 'ogg');

			if(isset($_FILES[$varname]) && is_array($_FILES[$varname])) {
				foreach($_FILES[$varname] as $key => $var) {
					$this->attach[$key] = $var;
				}
			}

			if(empty($this->attach)) {
				exit();
			}

			$attach_saved = false;

			if(!disuploadedfile($this->attach['tmp_name']) || !($this->attach['tmp_name'] != 'none' && $this->attach['tmp_name'] && $this->attach['name'])) {
				continue;
			}

			$filename = daddslashes($this->attach['name']);

			$this->attach['ext'] = strtolower(fileext($this->attach['name']));

			if (!in_array($this->attach['ext'], $safeext)) {
				$this->upload_error($attacharray);
			}

			$this->attach['name'] = htmlspecialchars($this->attach['name'], ENT_QUOTES);
			if(strlen($this->attach['name']) > 100) {
				$this->attach['name'] = 'abbr_'.md5($this->attach['name']).'.'.$this->attach['ext'];
			}

			if(empty($this->attach['size'])) {
				$this->upload_error($attacharray);
			}

			$maxattachsize = 20000000;

			if($maxattachsize && $this->attach['size'] > $maxattachsize) {
				$this->upload_error($attacharray);
			}

			$attachsubdir = 'month_'.date('Ym');
			$attach_dir = $this->attachdir.'/'.$attachsubdir;
			if(!is_dir($attach_dir)) {
				@mkdir($attach_dir, 0777);
				@fclose(fopen($attach_dir.'/index.html', 'w'));
			}

			$this->attach['attachment'] = $attachsubdir.'/'.preg_replace("/(php|phtml|php3|php4|jsp|exe|dll|asp|cer|asa|shtml|shtm|aspx|asax|cgi|fcgi|pl)(\.|$)/i", "_\\1\\2",
				date('Ymd').'_'.substr(md5($filename.microtime()), 12).random(12).'.'.$this->attach['ext']);

			$this->target = $this->attachdir.'/'.$this->attach['attachment'];

			if(@copy($this->attach['tmp_name'], $this->target) || (function_exists('move_uploaded_file') && @move_uploaded_file($this->attach['tmp_name'], $this->target))) {
				@unlink($this->attach['tmp_name']);
				$attach_saved = true;
			}

			if(!$attach_saved && @is_readable($this->attach['tmp_name'])) {
				@$fp = fopen($this->attach['tmp_name'], 'rb');
				@flock($fp, 2);
				@$attachedfile = fread($fp, $this->attach['size']);
				@fclose($fp);

				@$fp = fopen($this->target, 'wb');
				@flock($fp, 2);
				if(@fwrite($fp, $attachedfile)) {
					@unlink($this->attach['tmp_name']);
					$attach_saved = true;
				}
				@fclose($fp);
			}

			if(in_array($this->attach['ext'], array('jpg', 'jpeg', 'gif', 'png', 'bmp')) && $attach_saved && function_exists('getimagesize') && @getimagesize($this->target)) {
				$this->imgthumb && $this->thumb();
			}

			return $this->attach;
		}
	}

	function thumb() {
		@chmod($this->target, 0644);
		$imginfo = getimagesize($this->target);
		$srcWidth = $imginfo[0];
		$srcHeight = $imginfo[1];
		$thumb = $this->outputboth ? str_replace('.'.$this->attach['ext'], '', $this->attach['attachment']).'_thumb.'.$this->attach['ext'] : $this->attach['attachment'];
		$thumbtarget = $this->attachdir.'/'.$thumb;

		if ($srcWidth > $this->thumbW || $srcHeight > $this->thumbH){

			$radio = max(($srcWidth/$this->thumbW),($srcHeight/$this->thumbH));
			$destWidth = intval($srcWidth/$radio);
			$destHeight = intval($srcHeight/$radio);

			if (!function_exists('imagecreatefromjpeg')) {
				die("PHP running on your server does not support the GD image library");
			}
			if (!function_exists('imagecreatetruecolor')) {
				die("PHP running on your server does not support GD version 2.x, please change to GD version 1.x on your method");
			}

			if ($imginfo[2] == 2){
				$src_img = imagecreatefromjpeg($this->target);
			}elseif($imginfo[2] == 3){
				$src_img = imagecreatefrompng($this->target);
			}else{
				$src_img = imagecreatefromgif($this->target);
			}

			if (!$src_img){
				exit('ͼƬ����..');
			}

			$dst_img = imagecreatetruecolor($this->thumbW, $this->thumbH);
			$white = imagecolorallocate($dst_img, 0, 0, 0);
			imagefill($dst_img, 0, 0, $white);
			imagecopyresampled($dst_img, $src_img, ($this->thumbW - $destWidth) / 2, ($this->thumbH - $destHeight) / 2, 0, 0, $destWidth, intval($destHeight), $srcWidth, $srcHeight);
			imagejpeg($dst_img, $thumbtarget, $this->thumbW);
			imagedestroy($src_img);
			imagedestroy($dst_img);
			chmod($thumbtarget, 0755);
		}else{
			@copy($this->target, $thumbtarget);
		}

		$this->attach['thumb'] = $thumb;
	}
}
?>