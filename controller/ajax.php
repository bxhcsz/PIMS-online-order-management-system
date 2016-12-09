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
class ajax extends spController
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
	//银行帐号_编辑
	function updatebank() {
	$cb = spClass("banks");
	$conditions = array('id' => $this->spArgs('id'));
	$newrow = array(
	'bankname' => $this->spArgs('b1'),
	'banknum' => $this->spArgs('b2'),
	'realname' => $this->spArgs('b3')
	);
	$cb->update($conditions,$newrow);
	$this->logs("编辑银行帐号");
	echo "修改成功！";
	}
	//产品组_编辑
	function updategroup() {
	$cb = spClass("groups");
	$conditions = array('id' => $this->spArgs('id'));
	$newrow = array(
	'gname' => $this->spArgs('b1'),
	'zhekou' => $this->spArgs('b2'),
	'ticheng' => $this->spArgs('b3')
	);
	$cb->update($conditions,$newrow);
	$this->logs("编辑产品组");
	echo "修改成功！";
	}
	//产品组_重名检测
	function groupcheck() {
	$gp=spClass('groups');
	$keys=$this->spArgs('param');
	$this->gnums = $gp->find(array('gname' => $keys));
		if ($this->gnums) {
		echo "该产品组名称已存在！";
		} else {
		echo "y";
		}
	}
	//产品_编辑
	function updateproduct() {
	$cb = spClass("products");
	$conditions = array('id' => $this->spArgs('id'));
	$newrow = array(
	'pname' => $this->spArgs('b1'),
	'price' => $this->spArgs('b2'),
	'gid' => $this->spArgs('b3')
	);
	$cb->update($conditions,$newrow);
	$this->logs("编辑产品");
	echo "修改成功！";
	}
	//表单项_删除
	function delform() {
	$gb = spClass('forms');
	$re = $gb->deleteByPk($this->spArgs('id'));
	if ($re) {echo "1";}  
	}
	//修改订单
	function corder() {
	$t1=$this->spArgs('arr1');
	$t2=$this->spArgs('arr2');
	$i=0;
	foreach ($t1 as $v) {
		$t3[]=$v."|".$t2[$i];	
		$i++;
	}
	$t4=implode("~",$t3);
	$id=$this->spArgs('cid');
	$price=$this->spArgs('price');
	$nums=$this->spArgs('nums');
	$realname=$this->spArgs('realname');
	$mob=$this->spArgs('mob');
	$address=$this->spArgs('address');
	$payments=$this->spArgs('payments');
	$sp=spClass("orders");
	$newarr=array(
		'price' => $price,
		'totle' => $price*$nums,
		'nums' => $nums,
		'realname' => $realname,
		'mob' => $mob,
		'address' => $address,
		'useroption' => $t4,
		'payment' => $payments
	);
	$sp->update(array('id'=>$id),$newarr);
	$ons=$sp->find(array('id'=>$id));
	$this->logs("修改订单，订单编号：".$ons[ordernum]);
	echo "修改成功";
	}
}
?>