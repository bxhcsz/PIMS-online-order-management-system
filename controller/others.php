<?php
/*
版本：4.2
官网：www.diantuo.net
二次开发请联系 www.diantuo.net
本程序开源，您可以自由下载、使用、修改，但不允许用于商业用途
一、协议许可的权利
1.您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于非商业用途，而不必支付软件版权授权费用。
2.您可以在协议规定的约束和限制范围内修改 PIMS 源代码或界面风格以适应您的网站要求。
3.您拥有使用本软件构建的网站全部内容所有权，并独立承担与这些内容的相关法律义务。
二、协议规定的约束和限制
1.不得将本软件用于商业用途（包括但不限于企业网站、经营性网站、以营利为目的或实现盈利的网站）。
2.不得对本软件进行出租、出售、抵押或发放子许可证。
3.不管您的网站是否整体使用 PIMS ，还是部份栏目使用 PIMS，在您使用了 PIMS 的网站主页上必须保留版权信息链接。
4.禁止在 PIMS 的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发。
三、有限担保和免责声明
1.本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。
2.用户出于自愿而使用本软件，您必须了解使用本软件的风险，我们不承诺对免费用户提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。
3.电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始确认本协议并安装PIMS，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。
*/

header("Content-type: text/html; charset=utf-8"); 
set_time_limit(0);
include('controller/search.php');
class others extends spController
{
	function logs($dowhat) {
	$sp=spClass("logs");
	$dotime=date("Y-m-d H:i:s");
	$area = spClass('spIpArea')->get($this->egetip());
	$newarr=array(
		'dowhat' => $dowhat,
		'dotime' => $dotime,
		'doip' => $this->egetip(),
		'areas' => $area,
		'username' => $_SESSION[admin_username][username]
	);
	$sp->create($newarr);
	}
//手机短信提醒_显示页面
	function mobile() {
	$mobs=spClass('mob');
	$this->mob=$mobs->find();
	$this->display("admin/mobile.html");
	}
//手机短信提醒_开关
	function savemobtixing() {
	$mobs=spClass('mob');
	$mobs->update(NULL,array('zt'=>$this->spArgs('select20')));
	$this->logs("操作手机短信提醒开关");
	$this->success("手机短信提醒开关设置成功！",spUrl('others','mobile'));
	}
//手机短信提醒_保存
	function savemob() {
	$mobs=spClass('mob');
	$mobs->update(NULL,array('mail139'=>$this->spArgs('sendaddr')));
	$this->logs("修改手机短信提醒邮箱为".$this->spArgs('sendaddr'));
	$this->success("手机短信提醒邮箱设置成功！",spUrl('others','mobile'));
	}
//管理员_显示添加页面
	function addadmin() {
		$rs=spClass('groups');
		$this->showqx=$rs->findAll();
		$this->display("admin/addadmin.html");
	}
//检测管理员是否重复
	function admincheck() {
	$gp=spClass('adminuser');
	$keys=$this->spArgs('param');
	$this->gnums = $gp->find(array('username' => $keys));
		if ($this->gnums) {
		echo "该用户名已存在！";
		} else {
		echo "y";
		}
	}
//添加管理员_保存
	function saveadmin() {
	$au=spClass("adminuser");
	$pass=md5($this->spArgs('pass'));
	$qx=implode(",",$this->spArgs('qx'));
	$ddqx=implode(",",$this->spArgs('ddqx'));
	$qx=$qx."|".$ddqx;
	$arr=array(
		'username' => $this->spArgs('user'),
		'password' => $pass,
		'acl' => 'GBADMIN',
		'qx' => $qx
	);
	$au->create($arr);
	$this->logs("添加管理员：".$this->spArgs('user'));
	$this->success("管理员添加成功！",spUrl('others','addadmin'));
	}
//管理员列表
	function adminlist() {
	$banklist = spClass("adminuser");
	$this->results=$banklist->spPager($this->spArgs('page',1),15)->findAll(null,"id desc",null,null);
	$this->pager=$banklist->spPager()->getPager();
	$this->display("admin/adminlist.html");
	}
//删除管理员
	function deladmin() {
	$id=$this->spArgs('mid');
	$au=spClass("adminuser");
	$temp=$au->find(array('id'=>$id));
	$re=$au->delete(array('id'=>$id));
	$this->logs("删除管理员：".$temp[username]);
	if ($re) {echo "1";}
	}
//编辑管理员
	function editadmin() {
	$au=spClass("adminuser");
	$this->showau=$au->find(array('id'=>$this->spArgs('id')));
	$temp2=@explode("|",$this->showau[qx]);
	$temp=@explode(",",$temp2[0]);
	$this->qx1=in_array("1",$temp)?"1":"0";
	$this->qx2=in_array("2",$temp)?"1":"0";
	$this->qx3=in_array("3",$temp)?"1":"0";
	$this->qx4=in_array("4",$temp)?"1":"0";
	$this->qx5=in_array("5",$temp)?"1":"0";
	$this->qx6=in_array("6",$temp)?"1":"0";
	$this->qx7=in_array("7",$temp)?"1":"0";
	//获取产品组权限数组
	$sp=spClass("groups");
	$this->showqx=$sp->findAll();
	$this->ddqx=@explode(",",$temp2[1]);
	$this->display("admin/editadmin.html");
	}
//保存编辑管理员
	function saveeditadmin() {
	$id=$this->spArgs('cid');
	$newpass=md5($this->spArgs('pass'));
	$qx=implode(",",$this->spArgs('qx'));
	$ddqx=implode(",",$this->spArgs('ddqx'));
	$qx=$qx."|".$ddqx;
	$arr=array(
		'password' => $newpass,
		'qx' => $qx
	);
	$au=spClass("adminuser");
	$au->update(array('id'=>$id),$arr);
	$temp=$au->find(array('id'=>$id));
	$this->logs("编辑管理员信息：".$temp[username]);
	$this->success("管理员修改成功！",spUrl('others','adminlist'));
	}
//显示新闻分类
	function newsort() {
	$sp=spClass('newsort');
	$this->sorts=$sp->findAll();
	$this->display("admin/newsort.html");
	}
//保存新闻分类
	function savenewsort() {
	$sp=spClass('newsort');
	$sp->create(array('sortname'=>$this->spArgs('sortname'),'sortfile'=>$this->spArgs('sortfile')));
	if (file_exists($this->spArgs('sortfile'))) {	
	} else {
	mkdir(APP_PATH."/".$this->spArgs('sortfile'));
	}
	$this->success("新闻分类添加成功！",spUrl('others','newsort'));
	}
//实时生成拼音
	function dopinyin() {
	import(APP_PATH.'/include/pinyin.php');
	echo Pinyin($this->spArgs('sortname'));
	}
//检测新闻分类是否重复
	function newsortcheck() {
	$gp=spClass('newsort');
	$keys=$this->spArgs('param');
	$this->gnums = $gp->find(array('sortname' => $keys));
		if ($this->gnums) {
		echo "该分类名称已存在！";
		} else {
		echo "y";
		}
	}
//删除新闻分类
	function delsort() {
	$basedir = dirname(__FILE__); 
	$basedir=str_replace("controller","",$basedir);
	$id=$this->spArgs('mid');
	$au=spClass("newsort");
	//获取文件夹名并删除
	import(APP_PATH.'/include/deldir.php');
	$file=$au->find(array('id'=>$id));
	deldir($basedir.$file[sortfile]);
	$re=$au->delete(array('id'=>$id));
	$sp=spClass("news");
	$sp->delete(array('pid'=>$id));
	if ($re) {echo "1";}
	}
//修改新闻分类
	function updatenewsort() {
	$sp=spClass("newsort");
	$sp->update(array('id'=>$this->spArgs('id')),array('sortname'=>$this->spArgs('b1')));
	echo "修改成功";
	}
//显示分类修改页面
	function showeditnewsort() {
	$sp=spClass("newsort");
	$this->sorts=$sp->find(array('id'=>$this->spArgs('id')));
	$this->display("admin/editnewsort.html");
	}
//添加新闻_显示页面
	function addnews() {
	import(APP_PATH.'/afckeditor/fckeditor.php');
	$sp=spClass('newsort');
	$this->sorts=$sp->findall();
	$oFCKeditor = new FCKeditor('contents') ;
	$oFCKeditor->BasePath	= './afckeditor/';
	$oFCKeditor->Height = "450";
	$oFCKeditor->Value	= "";
	$this->editor = $oFCKeditor->CreateHtml() ;
	$this->display("admin/addnews.html");
	}
//保存新闻内容
	function savenews() {
	$sp=spClass('news');
	$addtime=date("Y-m-d");
	$arr=array(
		'pid' => $this->spArgs('newsort'),
		'title' => $this->spArgs('title'),
		'writter' => $this->spArgs('writter'),
		'addtime' => $addtime,
		'content' => $this->spArgs('contents')
	);
	$sp->create($arr);
	$this->success("新闻添加成功！",spUrl('others','addnews'));
	}
//显示新闻列表
	function newslist() {
	$banklist = spClass("news");
	$this->results=$banklist->spPager($this->spArgs('page',1),15)->findAll(null,"id desc",null,null);
	$this->pager=$banklist->spPager()->getPager();		
	$sp=spClass("newsort");
	$temp=$this->results;
	$i=0;
	foreach ($temp as $v) {
		$sortname=$sp->find(array('id'=>$v[pid]));
		$temp[$i][]=$sortname[sortname];
	$i++;
	}
	$this->results=$temp;
	$this->display("admin/newslist.html");
	}
//订单成交额区域统计
	function area() {
	$basedir = dirname(__FILE__); 
	$basedir=str_replace("controller","",$basedir);
	$prefix = $GLOBALS['G_SP']['db']['prefix'];
	$arr=array('北京','天津','河北','山西','内蒙古','辽宁','吉林','黑龙江','上海','江苏','浙江','安徽','福建','江西','山东','河南','湖北','湖南','广东','广西','海南','重庆','四川','贵州','西藏','陕西','甘肃','青海','宁夏','新疆','香港','澳门','台湾');
	$sp=spClass("orders");
	foreach ($arr as $v) {
		$c0=mysql_fetch_row($sp->runSql("select sum(totle) from {$prefix}orders where areas like '%$v%' "));
		$c=$c0[0];
		if ($c<>"0" && $c<>"" && $c<>NULL) {
			if (isset($links)) {
			$links=$links.","."{"."\"label\":"."\"$v\","."\"value\":".$c."}";
			} else {
			$links="{"."\"label\":"."\"$v\","."\"value\":".$c."}";
			}
		}
	}
	$content=file_get_contents("include/pie-bug-green.txt");
	$t=preg_replace("/values\":\[.*?\]/i","values\":[".$links."]",$content,1);
	$fo=fopen("include/pie-bug-green.txt","w"); 
	fwrite($fo,$t);
	fclose($fo);
	$this->showz="<div id=\"my_chart\" style=\"height:750px; color: #C10C02 border:1px solid #CCC\"></div><script>swfobject.embedSWF(\"include/open-flash-chart.swf\", \"my_chart\", \"100%\", \"300\", \"9.0.0\",\"expressInstall.swf\",{\"data-file\":\"include/pie-bug-green.txt\"});</script></div>";
	$this->display("admin/area.html");
	}
//删除新闻
	function delnews() {
	$id=$this->spArgs('mid');
	$au=spClass("news");
	$re=$au->delete(array('id'=>$id));
	if ($re) {echo "1";}
	}
//显示新闻编辑页面
	function showeditnews() {
	import(APP_PATH.'/afckeditor/fckeditor.php');
	$sp=spClass('news');
	$sort=spClass('newsort');
	$this->sorts=$sort->findAll();
	$this->news=$sp->find(array('id'=>$this->spArgs('id')));
	$this->mysort=$sort->find(array('id'=>$this->news[pid]));
	$oFCKeditor = new FCKeditor('contents') ;
	$oFCKeditor->BasePath	= './afckeditor/';
	$oFCKeditor->Height = "450";
	$oFCKeditor->Value	= $this->news[content];
	$this->editor = $oFCKeditor->CreateHtml() ;
	$this->display("admin/showeditnews.html");
	}
//修改新闻
	function saveeditnews() {
	$sp=spClass("news");
	$arr=array(
		'pid' => $this->spArgs('newsort'),
		'title' => $this->spArgs('title'),
		'writter' => $this->spArgs('writter'),
		'content' => $this->spArgs('contents')
	);
	$sp->update(array('id'=>$this->spArgs('cid')),$arr);
	$this->success("新闻修改成功！",spUrl('others','newslist'));
	}
//显示留言管理
	function guestbook() {
		$gp=spClass("groups");
		$this->group=$gp->findAll();
	$banklist = spClass("guestbook");
	$gid=$this->spArgs('gid');
		if ($gid=="" || empty($gid)) {
		$tiaojian=NULL;
		} else {
		$tiaojian="gid='$gid'";
		}
	$this->results=$banklist->spPager($this->spArgs('page',1),15)->findAll($tiaojian,"id desc",null,null);
	$this->pager=$banklist->spPager()->getPager();		
		$gp=spClass("groups");
		$this->mygp=$gp->findAll(null,"id desc",null,null);
		$tp=$this->results;
		foreach ($tp as $key=>$v) {
		$con=array('id'=>$v['gid']);
		$re=$gp->find($con);
		array_push($tp[$key],$re['gname']);
		}
		$this->results=$tp;
	$this->display("admin/guestbook.html");
	}
//生成订单页面
	function makeorder() {
		$xyz=$this->spArgs("xyz");
		//获取模板并生成订单页
		$content=file_get_contents("include/order.html");
		$t=str_replace("<body>","<body>".$xyz,content);
		$fo=fopen('order.html',"w"); 
		fwrite($fo,$t);
		fclose($fo);
		$this->success("订购页已生成，位于pims目录，文件名order.html！", spUrl("admin", "getform"));
	}
//获取留言发布代码
	function submitbook() {
	$gps=$this->spArgs('gps');
	if (PATH_SEPARATOR==':') {
	$apps_temp2=explode("/",APP_PATH);
	$apps2=array_reverse($apps_temp2);
	$mulu=$apps2[0];
	} else {
	$basedir = dirname(__FILE__); 
	$basedir=str_replace("controller","",$basedir);
	$temp=explode("\\",$basedir);
	$temp=array_reverse($temp);
	$mulu=$temp[1];
	}
echo <<<EOD
<script language="javascript">
function checkguestform() {
var f=document.bookform;
	if (f.title.value=="") {
	alert("请填写昵称");
	return false;
	}
	if (f.content.value=="") {
	alert("请填写留言内容");
	return false;
	}
	f.submit();
}
</script>
<form method="post" action="$mulu/index.php?c=others&a=saveguestbook" name="bookform" onsubmit="return false;">
<input name="gid_guestbook" type="hidden" value="$gps" style="width:0px" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="14%" height="25" align="right">昵称：</td>
    <td width="86%" align="left"><input name="title" type="text" id="title" /></td>
  </tr>
  <tr>
    <td height="25" align="right">留言内容：</td>
    <td align="left"><textarea name="content" cols="30" rows="5" id="content"></textarea></td>
  </tr>
  <tr>
    <td height="25" align="right">&nbsp;</td>
    <td align="left"><input type="submit" name="Submit" value="提交留言" onclick="checkguestform()" /></td>
  </tr>
</table>
</form>
EOD;
	$bodys=ob_get_contents();
	ob_end_clean();
	import(APP_PATH.'/afckeditor/fckeditor.php');
	$oFCKeditor = new FCKeditor('contents') ;
	$oFCKeditor->BasePath	= './afckeditor/';
	$oFCKeditor->Height = "450";
	$oFCKeditor->Value	= $bodys;
	$this->editor = $oFCKeditor->CreateHtml();
	$this->display("admin/getcode_addguest.html");
	}
	//获取IP
	function egetip(){
		if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')) 
		{
			$ip=getenv('HTTP_CLIENT_IP');
		} 
		elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown'))
		{
			$ip=getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown'))
		{
			$ip=getenv('REMOTE_ADDR');
		}
		elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown'))
		{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return preg_replace("/^([\d\.]+).*/", "\\1",$ip);
	}
//前台添加留言
	function saveguestbook() {
	$sp=spClass("guestbook");
	$addtime=date("Y-m-d H:i:s");
	$area = spClass('spIpArea')->get($this->egetip());
	$arr=array(
		'writter' => $this->spArgs('title'),
		'mess' => $this->spArgs('content'),
		'addtime' => $addtime,
		'ips' => $this->egetip(),
		'areas' => $area,
		'gid' => $this->spArgs('gid_guestbook'),
	);
	$sp->create($arr);
	echo "<script>alert('留言提交成功！');";
	echo "javascript:history.back(-1);</script>";
	}
//删除留言
	function delmess() {
	$id=$this->spArgs('mid');
	$au=spClass("guestbook");
	$re=$au->delete(array('id'=>$id));
	$this->logs("删除留言");
	if ($re) {echo "1";}
	}
//回复留言_显示页面
	function remess() {
	$sp=spClass('guestbook');
	$this->guest=$sp->find(array('id'=>$this->spArgs('id')));
	$this->display("admin/showmess.html");
	}
//更新留言
	function updatemess() {
	$sp=spClass("guestbook");
	$re=$sp->update(array('id'=>$this->spArgs('cid')),array('remess'=>$this->spArgs('b11')));
	$this->logs("回复留言");
	if ($re) {echo "回复成功";}
	}
//显示获取留言发布代码页面
	function getbookcode() {
		$gp=spClass("groups");
		$this->group=$gp->findAll();
	if (PATH_SEPARATOR==':') {
	$apps_temp2=explode("/",APP_PATH);
	$apps2=array_reverse($apps_temp2);
	$mulu=$apps2[0];
	} else {
	$basedir = dirname(__FILE__); 
	$basedir=str_replace("controller","",$basedir);
	$temp=explode("\\",$basedir);
	$temp=array_reverse($temp);
	$mulu=$temp[1];
	}
	$this->basedir=$mulu;
	$this->display("admin/getbookcode.html");
	}
//检测文件夹是否存在
	function checkfile() {
	$file=$this->spArgs('param');
	$sp=spClass("newsort");
	$sum=$sp->findCount(array('sortfile'=>$file));
	if ($sum<1) {
	echo "y";
	} else {
	echo "该文件夹已存在";
	}
	}
//显示操作日志
	function log() {
	$banklist = spClass("logs");
	$this->results=$banklist->spPager($this->spArgs('page',1),15)->findAll(null,"id desc",null,null);
	$this->pager=$banklist->spPager()->getPager();		
	$this->display("admin/adminlog.html");
	}
//批量删除操作日志
	function dellogs() {
	$ids=$this->spArgs('ids'); 
	if (empty($ids)) {
	$this->jump(spUrl("others","log",array('page'=>$this->spArgs('page'))));
	}
	$gb = spClass('logs');
	foreach ($ids as $id) {
	$gb->delete(array('id'=>$id));
	}
	$this->jump(spUrl("others","log",array('page'=>$this->spArgs('page'))));
	}
//清空全部操作日志
	function dellogs2() {
		$gb=spClass('logs');
		$gb->delete();
		$this->jump(spUrl("others","log",array('page'=>$this->spArgs('page'))));
	}
//显示登录日志页面
	function loginlog() {
	$banklist = spClass("loginlog");
	$this->results=$banklist->spPager($this->spArgs('page',1),15)->findAll(null,"id desc",null,null);
	$this->pager=$banklist->spPager()->getPager();		
	$this->display("admin/loginlog.html");
	}
//批量删除操作登录日志
	function delloginlog() {
	$ids=$this->spArgs('ids'); 
	if (empty($ids)) {
	$this->jump(spUrl("others","loginlog",array('page'=>$this->spArgs('page'))));
	}
	$gb = spClass('loginlog');
	foreach ($ids as $id) {
	$gb->delete(array('id'=>$id));
	}
	$this->jump(spUrl("others","loginlog",array('page'=>$this->spArgs('page'))));
	}
//清空全部操作日志
	function delloginlog2() {
		$gb=spClass('loginlog');
		$gb->delete();
		$this->jump(spUrl("others","loginlog",array('page'=>$this->spArgs('page'))));
	}
//显示备份页面
	function showbak(){
		$db = spClass('dbbackup', array(0=>$GLOBALS['G_SP']['db']));
 		$this->table=$db->showAllTable();
		//print_r("<pre>");
		//print_r($this->table);
		//print_r("</pre>");
		$this->display("admin/showbak.html");
	}
//导出单个表
	function youhuabak() {
		$tname=$this->spArgs('tablename');
		$db = spClass('dbbackup', array(0=>$GLOBALS['G_SP']['db']));
		if ($db->outTable($tname)) {
		$this->success("导出完成", spUrl("others", "showbak"));
		} else {
		$this->success("操作失败", spUrl("others", "showbak"));
		}
	}
//导出全部表
	function allbak() {
		$db = spClass('dbbackup', array(0=>$GLOBALS['G_SP']['db']));
		$db->outAllData();
	}
}

?>