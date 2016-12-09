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
include('controller/search.php');
class books extends spController
{
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
//显示添加发货记录页面
	function addsend() {
		$gp=spClass("groups");
		$this->group=$gp->findAll();
	$this->display("admin/addsend.html");
	}
//保存发货记录
	function savesend() {
	$sp=spClass("send");
	$sends=$this->spArgs('sends');
	$gid=$this->spArgs('gp');
	if (empty($sends)) {$this->jump(spUrl('books','send'));}
	array_filter($sends);
	$addtime=date("Y-m-d");
	foreach ($sends as $v) {
		if ($v!="") {
		$sp->create(array('content'=>$v,'gid'=>$gid));
		}
	}
	$this->logs("添加发货记录");
	$this->success('发货记录保存成功！',spUrl('books','send'));
	}
//删除发货记录
	function delfahuo() {
	$id=$this->spArgs('mid');
	$au=spClass("send");
	$re=$au->delete(array('id'=>$id));
	if ($re) {echo "1";}
	}
//显示发货页面
	function send() {
	$banklist = spClass("send");
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
		$this->group=$gp->findAll();
	$this->display("admin/showsend.html");
	}
//显示发货记录修改页面
	function updatesend() {
		$gp=spClass("groups");
		$this->group=$gp->findAll();
	$sp=spClass("send");
	$this->mybank=$sp->find(array('id'=>$this->spArgs('id')));
		$re=$gp->find(array('id'=>$this->mybank['gid']));
		$this->gname=$re[gname];
	$this->display("admin/updatesend.html");
	}
//修改发货记录
	function changesend() {
	$sp=spClass("send");
	$sp->update(array('id'=>$this->spArgs('cid')),array('content'=>$this->spArgs('b1'),'gid'=>$this->spArgs('gps')));
	$this->logs("修改发货记录");
	echo "修改成功！";
	}
//显示调用发货记录
	function getsendcode() {
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
	$this->display("admin/showgetsendcode.html");
	}
}
?>