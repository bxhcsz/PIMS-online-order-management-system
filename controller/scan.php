<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>
<body>
<?php 
ob_start(); 
ini_set("error_reporting","E_ALL & ~E_NOTICE");
set_time_limit(0); 
$version = "PIMS木马扫描"; 
$realpath = realpath('./'); 
$selfpath = $_SERVER['PHP_SELF']; 
$selfpath = substr($selfpath, 0, strrpos($selfpath,'/')); 
define('REALPATH', str_replace('//','/',str_replace('\\','/',substr($realpath, 0, strlen($realpath) - strlen($selfpath))))); 
define('MYFILE', basename(__FILE__)); 
define('MYPATH', str_replace('\\', '/', dirname(__FILE__)).'/'); 
define('MYFULLPATH', str_replace('\\', '/', (__FILE__))); 
define('HOST', "http://".$_SERVER['HTTP_HOST']); 
?> 
<html> 
<head> 
<title><?php echo $version?></title> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<style> 
body{margin:0px;} 
body,td{font: 12px Arial,Tahoma;line-height: 16px;} 
a {color: #00f;text-decoration:underline;} 
a:hover{color: #f00;text-decoration:none;} 
.alt1 td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#f1f1f1;padding:5px 10px 5px 5px;} 
.alt2 td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#f9f9f9;padding:5px 10px 5px 5px;} 
.focus td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#ffffaa;padding:5px 10px 5px 5px;} 
.head td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#e9e9e9;padding:5px 10px 5px 5px;font-weight:bold;} 
.head td span{font-weight:normal;} 
</style> 
</head> 
<body> 
<?
if($action=="download" && isset($_GET['file']) && trim($_GET['file'])!="") 
{ 
$file = $_GET['file']; 
ob_clean(); 
if (@file_exists($file)) { 
header("Content-type: application/octet-stream"); 
header("Content-Disposition: filename=\"".basename($file)."\""); 
echo file_get_contents($file); 
} 
exit(); 
} 
?> 
<table border="0" cellpadding="0" cellspacing="0" width="100%"> 
</table> 
<br> 
<?php 
if($action=="setting") 
{ 
if(isset($_POST['btnsetting'])) 
{ 
$Ssetting = array(); 
$Ssetting['user']=isset($_POST['checkuser'])?$_POST['checkuser']:"php | php? | phtml"; 
$Ssetting['all']=isset($_POST['checkall'])&&$_POST['checkall']=="on"?1:0; 
$Ssetting['hta']=isset($_POST['checkhta'])&&$_POST['checkhta']=="on"?1:0; 
setcookie("t00ls_s", base64_encode(serialize($Ssetting)), time()+60*60*24*365,"/"); 
echo "设置完成！"; 
header( 'refresh: 1; url='.MYFILE.'?action=setting' ); 
exit(); 
} 
?> 
<?php 
} 
else 
{ 
$dir = isset($_POST['path'])?$_POST['path']:MYPATH; 
//$dir = substr($dir,-1)!="/"?$dir."/":$dir; 
$dir=str_replace("controller/","",$dir);
?> 
<form name="frmScan" method="post" action=""> 
<table width="100%%" border="0" cellspacing="0" cellpadding="0"> 
<tr> 
<td width="60" style="vertical-align:middle; padding-left:5px;">PIMS所在路径:</td> 
<td width="690"> 
<input type="text" name="path" id="path" style="width:600px" value="<?php echo $dir?>"> 
<input type="submit" name="btnScan" id="btnScan" value="开始扫描"></td> 
</tr> 
</table> 
</form> 
<?php 
if(isset($_POST['btnScan'])) 
{ 
$start=mktime(); 
$is_user = array(); 
$is_ext = ""; 
$list = ""; 
if(trim($setting['user'])!="") 
{ 
$is_user = explode("|",$setting['user']); 
if(count($is_user)>0) 
{ 
foreach($is_user as $key=>$value) 
$is_user[$key]=trim(str_replace("?","(.)",$value)); 
$is_ext = "(\.".implode("($|\.))|(\.",$is_user)."($|\.))"; 
} 
} 
if($setting['hta']==1) 
{ 
$is_hta=1; 
$is_ext = strlen($is_ext)>0?$is_ext."|":$is_ext; 
$is_ext.="(^\.htaccess$)"; 
} 
if($setting['all']==1 || (strlen($is_ext)==0 && $setting['hta']==0)) 
{ 
$is_ext="(.+)"; 
} 
$php_code = getCode(); 
if(!is_readable($dir)) 
$dir = MYPATH; 
$count=$scanned=0; 
scan($dir,$is_ext); 
$end=mktime(); 
$spent = ($end - $start); 
?> 
<div style="padding:10px; background-color:#f2f2f2">扫描: <?php echo $scanned?> 文件 | 发现: <?php echo $count?> 可疑文件 | 耗时: <?php echo $spent?> 秒</div> 
<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
<tr class="head"> 
<td width="25" align="center">No</td> 
<td width="48%">文件</td> 
<td width="12%">最后更新</td> 
<td width="10%">解释</td> 
<td width="20%">特征</td> 
<td>下载</td> 
</tr> 
<?php echo $list?> 
</table> 
<?php 
} 
} 
ob_flush(); 
?> 
</body> 
</html> 
<?php 
function scan($path = '.',$is_ext){ 
global $php_code,$count,$scanned,$list; 
$nov=array("fck_link.js","spellchecker.php","browser.html","fckeditorcode_gecko.js","fckeditorcode_ie.js","ciframe.html","global.js","json2.js","swfobject.js","jquery.js","TenpayHttpClient.class.php","jquery-1.2.6.min.js","jquery-1.4.2.min.js","jquery-1.4.4.min.js","jquery-1.4.min.js","jquery.timers.js","spModel.php","spView.php","mssql.php","mysql.php","mysqli.php","oracle.php","pdo.php","sae.php","block.php.php","smarty_internal_compile_extends.php","smarty_internal_compile_function.php","smarty_internal_compile_include.php","smarty_internal_compile_include_php.php","smarty_internal_compile_insert.php","smarty_internal_function_call_handler.php","smarty_internal_template.php","sqlite.php","class.template.php","template.config_loader.php","function.resize_image.php","PHPMailer.php","spFunctions.php","scan.php","snoopy.php","get.php","dbbackup.php");

$ignore = array('.', '..' ); 
$replace=array(" ","\n","\r","\t"); 
$dh = @opendir( $path ); 
while(false!==($file=readdir($dh))){ 
if( !in_array( $file, $ignore ) ){ 
if( is_dir( "$path$file" ) ){ 
scan("$path$file/",$is_ext); 
} else { 
$current = $path.$file; 
if(MYFULLPATH==$current) continue; 
if(!preg_match("/$is_ext/i",$file)) continue; 
if(is_readable($current)) 
{ 
$scanned++; 
$content=file_get_contents($current); 
$content= str_replace($replace,"",$content); 
foreach($php_code as $key => $value) 
{ 
if(preg_match("/$value/i",$content)) 
{ 
$filetime = date('Y-m-d H:i:s',filemtime($current)); 
$reason = explode("->",$key); 
$url = str_replace(REALPATH,HOST,$current); 
preg_match("/$value/i",$content,$arr); 
if (!in_array($file,$nov)) {
$count++; 
$j = $count % 2 + 1; 
$list.=" 
<tr class='alt$j' onmouseover='this.className=\"focus\";' onmouseout='this.className=\"alt$j\";'> 
<td>$count</td> 
<td><a href='$url' target='_blank'>$current</a></td> 
<td>$filetime</td> 
<td><font color=red>$reason[0]</font></td> 
<td><font color=#090>$reason[1]</font></td> 
<td><a href='?action=download&file=$current' target='_blank'>下载</a></td> 
</tr>"; 
}
//echo $key . "-" . $path . $file ."(" . $arr[0] . ")" ."<br />"; 
//echo $path . $file ."<br />"; 
break; 
} 
} 
} 
} 
} 
} 
closedir( $dh ); 
} 
function getSetting() 
{ 
$Ssetting = array(); 
$Ssetting['user']=isset($Ssetting['user'])?$Ssetting['user']:"php | php? | phtml | shtml | htm | html"; 
$Ssetting['all']=isset($Ssetting['all'])?intval($Ssetting['all']):0; 
$Ssetting['hta']=isset($Ssetting['hta'])?intval($Ssetting['hta']):1; 
return $Ssetting; 
} 
function getCode() 
{ 
return array( 
'后门特征->cha88.cn'=>'cha88\.cn', 
'后门特征->c99shell'=>'c99shell', 
'后门特征->phpspy'=>'phpspy', 
'后门特征->Scanners'=>'Scanners', 
'后门特征->cmd.php'=>'cmd\.php', 
'后门特征->str_rot13'=>'str_rot13', 
'后门特征->webshell'=>'webshell', 
'后门特征->EgY_SpIdEr'=>'EgY_SpIdEr', 
'后门特征->tools88.com'=>'tools88\.com', 
'后门特征->SECFORCE'=>'SECFORCE', 
'后门特征->eval("?>'=>'eval\((\'|")\?>', 
'可疑代码特征->system('=>'system\(', 
'可疑代码特征->passthru('=>'passthru\(', 
'可疑代码特征->shell_exec('=>'shell_exec\(', 
'可疑代码特征->exec('=>'exec\(', 
'可疑代码特征->popen('=>'popen\(', 
'可疑代码特征->proc_open'=>'proc_open', 
'可疑代码特征->eval($'=>'eval\((\'|"|\s*)\\$', 
'可疑代码特征->assert($'=>'assert\((\'|"|\s*)\\$', 
'危险MYSQL代码->returns string soname'=>'returnsstringsoname', 
'危险MYSQL代码->into outfile'=>'intooutfile', 
'危险MYSQL代码->load_file'=>'select(\s+)(.*)load_file', 
'加密后门特征->eval(gzinflate('=>'eval\(gzinflate\(', 
'加密后门特征->eval(base64_decode('=>'eval\(base64_decode\(', 
'加密后门特征->eval(gzuncompress('=>'eval\(gzuncompress\(', 
'加密后门特征->eval(gzdecode('=>'eval\(gzdecode\(', 
'加密后门特征->eval(str_rot13('=>'eval\(str_rot13\(', 
'加密后门特征->gzuncompress(base64_decode('=>'gzuncompress\(base64_decode\(', 
'加密后门特征->base64_decode(gzuncompress('=>'base64_decode\(gzuncompress\(', 
'一句话后门特征->eval($_'=>'eval\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)', 
'一句话后门特征->assert($_'=>'assert\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)', 
'一句话后门特征->require($_'=>'require\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)', 
'一句话后门特征->require_once($_'=>'require_once\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)', 
'一句话后门特征->include($_'=>'include\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)', 
'一句话后门特征->include_once($_'=>'include_once\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)', 
'一句话后门特征->call_user_func("assert"'=>'call_user_func\(("|\')assert("|\')', 
'一句话后门特征->call_user_func($_'=>'call_user_func\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)', 
'一句话后门特征->$_POST/GET/REQUEST/COOKIE[?]($_POST/GET/REQUEST/COOKIE[?]'=>'\$_(POST|GET|REQUEST|COOKIE)\[([^\]]+)\]\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)\[', 
'一句话后门特征->echo(file_get_contents($_POST/GET/REQUEST/COOKIE'=>'echo\(file_get_contents\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)', 
'上传后门特征->file_put_contents($_POST/GET/REQUEST/COOKIE,$_POST/GET/REQUEST/COOKIE'=>'file_put_contents\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)\[([^\]]+)\],(\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)', 
'上传后门特征->fputs(fopen("?","w"),$_POST/GET/REQUEST/COOKIE['=>'fputs\(fopen\((.+),(\'|")w(\'|")\),(\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)\[', 
'.htaccess插马特征->SetHandler application/x-httpd-php'=>'SetHandlerapplication\/x-httpd-php', 
'.htaccess插马特征->php_value auto_prepend_file'=>'php_valueauto_prepend_file', 
'.htaccess插马特征->php_value auto_append_file'=>'php_valueauto_append_file' 
); 
} 
?> 
</body>
</html>
