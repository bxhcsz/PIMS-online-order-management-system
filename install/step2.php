<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PIMS_安装程序</title>
<style type="text/css">
<!--
body,td,th {
	font-size: 12px;
}
-->
</style></head>

<body>
<?php
//检测是否同意协议
if ($_POST[xieyi]!="1") {
echo "<script>alert('您不同意授权协议，安装程序未运行');location.href='index.php';</script>";
exit;
}
//检测数据库信息
$host=trim($_POST[host]);
$dbname=trim($_POST[dbname]);
$username=trim($_POST[username]);
$password=trim($_POST[password]);
$qianzui=trim($_POST[qianzui]);
$adminuser=trim($_POST[adminuser]);
$adminpass=trim($_POST[adminpass]);
$newpass=md5($adminpass);
$domain=trim($_POST[domain]);
$passport=trim($_POST[passport]);
$c1=@mysql_connect($host,$username,$password);
	if (!$c1) {
	echo "<script>alert('连接数据库失败，请检查主机名、数据库名、数据库用户以及数据库密码是否正确');location.href='index.php';</script>";
	exit;
	}
$c2=@mysql_select_db($dbname);
	if (!$c2) {
	echo "<script>alert('读取数据库失败，请检查数据库名是否正确');location.href='index.php';</script>";
	exit;
	}
//读取表结构开始建表
$table=file_get_contents("sql/table.sql");
$table=str_replace("pims_",$qianzui,$table);
$array_table=explode(";",$table);
foreach ($array_table as $v) {
	mysql_query($v);
}
//写入基础数据
$data=file_get_contents("sql/data.sql");
$data=str_replace("pims_",$qianzui,$data);
$array_data=explode(";",$data);
foreach ($array_data as $v) {
	mysql_query($v);
}
//保存管理员
mysql_query("insert into ".$qianzui."adminuser(username,password,acl,qx) values('$adminuser','$newpass','GBADMIN','1,2,3,4,5,6,7')") or die(mysql_error());
//设置目录权限
@chmod("../SpeedPHP", 0777);
@chmod("../tmp", 0777);
@chmod("../include", 0777);
//生成配置文件
$content=file_get_contents("config-sample.php");
$t=preg_replace("/host'=>'.*?'/i","host'=>'".$host."'",$content,1);
$t=preg_replace("/login'=>'.*?'/i","login'=>'".$username."'",$t,1);
$t=preg_replace("/password'=>'.*?'/i","password'=>'".$password."'",$t,1);
$t=preg_replace("/database'=>'.*?'/i","database'=>'".$dbname."'",$t,1);
$t=preg_replace("/prefix'=>'.*?'/i","prefix'=>'".$qianzui."'",$t,1);
$fo=fopen("../config.php","w"); 
fwrite($fo,$t);
fclose($fo);
//生成锁文件
$fo=fopen("install.locked","w"); 
fwrite($fo,"");
fclose($fo);
//完成
echo "<script>alert('安装完成，请删除install文件夹以确保安全');";
echo "location.href='../index.php?c=admin&a=index';</script>";
?>
</body>
</html>
