<?php
require("config.php");
require(SP_PATH."/SpeedPHP.php");
require(APP_PATH.'/controller/functions.php');
function formatBytes($params) {
$bytes = $params['size'];
if($bytes >= 1073741824) {
$bytes = round($bytes / 1073741824 * 100) / 100 . 'GB';
} elseif($bytes >= 1048576) {
$bytes = round($bytes / 1048576 * 100) / 100 . 'MB';
} elseif($bytes >= 1024) {
$bytes = round($bytes / 1024 * 100) / 100 . 'KB';
} else {
$bytes = $bytes . 'Bytes';
}
return $bytes;
}
spAddViewFunction('formatBytes','formatBytes'); 
spRun();
?>