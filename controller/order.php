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
class order extends spController
{
	function logs($dowhat) {
	$sp=spClass("logs");
	$dotime=date("Y-m-d H:i:s");
	$area = spClass('spIpArea')->get(egetip());
	$newarr=array(
		'dowhat' => $dowhat,
		'dotime' => $dotime,
		'doip' => egetip(),
		'areas' => $area,
		'username' => $_SESSION[admin_username][username]
	);
	$sp->create($newarr);
	}
//删除订单
	function delorder() {
	$gb = spClass('orders');
	$gb2 = spClass('rubbish');
	$rs = $gb->find(array('id'=>$this->spArgs('mid')));
		$arr=array(
			'gid' => $rs[gid],
			'ordernum' => $rs[ordernum],
			'pname' => $rs[pname],
			'price' => $rs[price],
			'totle' => $rs[totle],
			'nums' => $rs[nums],
			'realname' => $rs[realname],
			'mob' => $rs[mob],
			'address' => $rs[address],
			'beizhu' => $rs[beizhu],
			'useroption' => $rs[useroption],
			'payment' => $rs[payment],
			'addtime' => $rs[addtime],
			'ips' => $rs[ips],
			'areas' => $rs[areas],
			'zt1' => $rs[zt1],
			'zt2' => $rs[zt2],
			'wuliu' => $rs[wuliu],
			'wuliunum' => $rs[wuliunum],
			'kefu' => $rs[kefu],
			'beizhu2' => $rs[beizhu2],
			'sendtime' =>$rs[sendtime],
			'url1' => $rs[url1],
			'url2' => $rs[url2],
			'fromdomain' => $rs[fromdomain]
		);
		$gb2->create($arr);
		$this->logs("删除订单到回收站，订单编号：".$rs[ordernum]);
	$re = $gb->deleteByPk($this->spArgs('mid'));
	if ($re) {echo "1";}  
	}
//从回收站恢复订单
	function helporder() {
	$gb = spClass('orders');
	$gb2 = spClass('rubbish');
	$rs = $gb2->find(array('id'=>$this->spArgs('mid')));
		$arr=array(
			'gid' => $rs[gid],
			'ordernum' => $rs[ordernum],
			'pname' => $rs[pname],
			'price' => $rs[price],
			'totle' => $rs[totle],
			'nums' => $rs[nums],
			'realname' => $rs[realname],
			'mob' => $rs[mob],
			'address' => $rs[address],
			'beizhu' => $rs[beizhu],
			'useroption' => $rs[useroption],
			'payment' => $rs[payment],
			'addtime' => $rs[addtime],
			'ips' => $rs[ips],
			'areas' => $rs[areas],
			'zt1' => $rs[zt1],
			'zt2' => $rs[zt2],
			'wuliu' => $rs[wuliu],
			'wuliunum' => $rs[wuliunum],
			'kefu' => $rs[kefu],
			'beizhu2' => $rs[beizhu2],
			'sendtime' =>$rs[sendtime],
			'url1' => $rs[url1],
			'url2' => $rs[url2],
			'fromdomain' => $rs[fromdomain]
		);
		$gb->create($arr);
		$this->logs("从回收站恢复订单，编号：".$rs[ordernum]);
	$re = $gb2->deleteByPk($this->spArgs('mid'));
	if ($re) {echo "1";}  
	}
//彻底删除订单
	function delorderover() {
	$gb = spClass('rubbish');
	$temp=$gb->find(array('id'=>$this->spArgs('mid')));
	$re = $gb->deleteByPk($this->spArgs('mid'));
	$this->logs("彻底删除订单，编号：".$temp[ordernum]);
	if ($re) {echo "1";}  
	}
//批量删除订单
	function delallorder() {
	$ids=$this->spArgs('ids'); 
	if (empty($ids)) {
	$this->jump(spUrl("admin","orderlist",array('page'=>$this->spArgs('page'),'zt1'=>$this->spArgs('zt1'),'zt2'=>$this->spArgs('zt2'),'zt3'=>$this->spArgs('zt3'),'payment'=>$this->spArgs('payment'))));
	}
	$gb = spClass('orders');
	foreach ($ids as $id) {
	$gb2 = spClass('rubbish');
	$rs = $gb->find(array('id'=>$id));
		$arr=array(
			'gid' => $rs[gid],
			'ordernum' => $rs[ordernum],
			'pname' => $rs[pname],
			'price' => $rs[price],
			'totle' => $rs[totle],
			'nums' => $rs[nums],
			'realname' => $rs[realname],
			'mob' => $rs[mob],
			'address' => $rs[address],
			'beizhu' => $rs[beizhu],
			'useroption' => $rs[useroption],
			'payment' => $rs[payment],
			'addtime' => $rs[addtime],
			'ips' => $rs[ips],
			'areas' => $rs[areas],
			'zt1' => $rs[zt1],
			'zt2' => $rs[zt2],
			'wuliu' => $rs[wuliu],
			'wuliunum' => $rs[wuliunum],
			'kefu' => $rs[kefu],
			'beizhu2' => $rs[beizhu2],
			'sendtime' =>$rs[sendtime],
			'url1' => $rs[url1],
			'url2' => $rs[url2],
			'fromdomain' => $rs[fromdomain]
		);
		$gb2->create($arr);
	$gb->delete(array('id'=>$id));
	}
	$this->logs("批量删除订单至回收站");
	$this->jump(spUrl("admin","orderlist",array('page'=>$this->spArgs('page'),'zt1'=>$_REQUEST[zt1],'zt2'=>$_REQUEST[zt2],'zt3'=>$_REQUEST[zt3],'payment'=>$_REQUEST[payment])));
	}
//批量彻底删除订单
	function delallorderover() {
	$ids=$this->spArgs('ids'); 
	if (empty($ids)) {
	$this->jump(spUrl("order","isdel",array('page'=>$this->spArgs('page'),'zt1'=>$this->spArgs('zt1'),'zt2'=>$this->spArgs('zt2'),'zt3'=>$this->spArgs('zt3'),'payment'=>$this->spArgs('payment'))));
	}
	$gb = spClass('rubbish');
	foreach ($ids as $id) {
	$gb->delete(array('id'=>$id));
	}
	$this->logs("批量彻底删除订单");
	$this->jump(spUrl("order","isdel",array('page'=>$this->spArgs('page'),'zt1'=>$_REQUEST[zt1],'zt2'=>$_REQUEST[zt2],'zt3'=>$_REQUEST[zt3],'payment'=>$_REQUEST[payment])));
	}
//显示订单详情
	function showorder() {
	$gb = spClass('orders');
	$this->order=$gb->find(array('id'=>$this->spArgs('id')));
	$temp=$this->order;
	$tmp=explode("~",$temp[useroption]);
	$i=0;
	foreach ($tmp as $key=>$v) {
	$tm=explode("|",$v);
	$tmp[$i]=$tm;
	$i++;
	}
	foreach($tmp as $k=>$v){   
		if(!$v[0])   
		unset($tmp[$k]);   
	}  
	$this->uo=$tmp;
	$areainfo = spClass('spIpArea')->get($temp[ips]);
	$this->area=$areainfo;
	$this->zt1=$this->spArgs('zt1');
	$this->zt2=$this->spArgs('zt2');
	$this->payment=$this->spArgs('payment');
	$this->page=$this->spArgs('page');
	$this->display("admin/showorder.html");
	}
//显示订单详情_搜索页面
	function showorder2() {
	$gb = spClass('orders');
	$this->order=$gb->find(array('id'=>$this->spArgs('id')));
	$temp=$this->order;
	$tmp=explode("~",$temp[useroption]);
	$i=0;
	foreach ($tmp as $key=>$v) {
	$tm=explode("|",$v);
	$tmp[$i]=$tm;
	$i++;
	}
	foreach($tmp as $k=>$v){   
		if(!$v[0])   
		unset($tmp[$k]);   
	}  
	$this->uo=$tmp;
	$areainfo = spClass('spIpArea')->get($temp[ips]);
	$this->area=$areainfo;
	$this->sid=$this->spArgs('sid');
	$this->types=$this->spArgs('types');
	$this->keys=$this->spArgs('keys');
	$this->gp=$this->spArgs('gp');
	$this->bigname=$this->spArgs('bigname');
	$this->smallname=$this->spArgs('smallname');
	$this->s1=$this->spArgs('s1');
	$this->s2=$this->spArgs('s2');
	$this->s3=$this->spArgs('s3');
	$this->s4=$this->spArgs('s4');
	$this->page=$this->spArgs('page');
	$this->display("admin/showorder2.html");
	}
//显示订单详情_回收站
	function showorder3() {
	$gb = spClass('rubbish');
	$this->order=$gb->find(array('id'=>$this->spArgs('id')));
	$temp=$this->order;
	$tmp=explode("~",$temp[useroption]);
	$i=0;
	foreach ($tmp as $key=>$v) {
	$tm=explode("|",$v);
	$tmp[$i]=$tm;
	$i++;
	}
	$this->uo=$tmp;
	$areainfo = spClass('spIpArea')->get($temp[ips]);
	$this->area=$areainfo;
	$this->zt1=$this->spArgs('zt1');
	$this->zt2=$this->spArgs('zt2');
	$this->payment=$this->spArgs('payment');
	$this->page=$this->spArgs('page');
	$this->display("admin/showorder3.html");
	}
//修改订单状态为未处理
	function order_zt1() {
	$gb = spClass('orders');
	$gb->update(array('id'=>$this->spArgs('cid')),array('zt1'=>'1'));
	$re=$gb->find(array('id'=>$this->spArgs('cid')));
	if ($re[kefu]=="") {
	$gb->update(array('id'=>$this->spArgs('cid')),array('kefu'=>$_SESSION[admin_username][username]));
	}
	$this->logs("修改订单状态为未处理，订单编号：".$re[ordernum]);
	echo "修改订单状态为：未处理";
	}
//修改订单状态为待发货
	function order_zt2() {
	$gb = spClass('orders');
	$gb->update(array('id'=>$this->spArgs('cid')),array('zt1'=>'2'));
	$re=$gb->find(array('id'=>$this->spArgs('cid')));
	if ($re[kefu]=="") {
	$gb->update(array('id'=>$this->spArgs('cid')),array('kefu'=>$_SESSION[admin_username][username]));
	}
	$this->logs("修改订单状态为待发货，订单编号：".$re[ordernum]);
	echo "修改订单状态为：待发货";
	}
//修改订单状态为已发货
	function order_zt3() {
	$gb = spClass('orders');
	$sendtime=date("Y-m-d H:i:s");
	$gb->update(array('id'=>$this->spArgs('cid')),array('zt1'=>'3','sendtime'=>$sendtime));
	$re=$gb->find(array('id'=>$this->spArgs('cid')));
	if ($re[kefu]=="") {
	$gb->update(array('id'=>$this->spArgs('cid')),array('kefu'=>$_SESSION[admin_username][username]));
	}
	$this->logs("修改订单状态为已发货，订单编号：".$re[ordernum]);
	echo "修改订单状态为：已发货";
	}
//修改订单状态为未付款
	function order_zt4() {
	$gb = spClass('orders');
	$gb->update(array('id'=>$this->spArgs('cid')),array('zt2'=>'1'));
	$re=$gb->find(array('id'=>$this->spArgs('cid')));
	if ($re[kefu]=="") {
	$gb->update(array('id'=>$this->spArgs('cid')),array('kefu'=>$_SESSION[admin_username][username]));
	}
	$this->logs("修改订单状态为未付款，订单编号：".$re[ordernum]);
	echo "修改订单状态为：未付款";
	}
//修改订单状态为已付款
	function order_zt5() {
	$gb = spClass('orders');
	$gb->update(array('id'=>$this->spArgs('cid')),array('zt2'=>'2'));
	$re=$gb->find(array('id'=>$this->spArgs('cid')));
	if ($re[kefu]=="") {
	$gb->update(array('id'=>$this->spArgs('cid')),array('kefu'=>$_SESSION[admin_username][username]));
	}
	$this->logs("修改订单状态为已付款，订单编号：".$re[ordernum]);
	echo "修改订单状态为：已付款";
	}
//修改订单状态为已签收
	function order_zt58() {
	$gb = spClass('orders');
	$gb->update(array('id'=>$this->spArgs('cid')),array('zt1'=>'4'));
	$re=$gb->find(array('id'=>$this->spArgs('cid')));
	if ($re[kefu]=="") {
	$gb->update(array('id'=>$this->spArgs('cid')),array('kefu'=>$_SESSION[admin_username][username]));
	}
	$this->logs("修改订单状态为已签收，订单编号：".$re[ordernum]);
	echo "修改订单状态为：已签收";
	}
//修改订单状态为已退回
	function order_zt59() {
	$gb = spClass('orders');
	$gb->update(array('id'=>$this->spArgs('cid')),array('zt1'=>'5'));
	$re=$gb->find(array('id'=>$this->spArgs('cid')));
	if ($re[kefu]=="") {
	$gb->update(array('id'=>$this->spArgs('cid')),array('kefu'=>$_SESSION[admin_username][username]));
	}
	$this->logs("修改订单状态为已退回，订单编号：".$re[ordernum]);
	echo "修改订单状态为：已退回";
	}
//保存物流信息
	function order_zt6() {
	$gb = spClass('orders');
	$gb->update(array('id'=>$this->spArgs('cid')),array('wuliu'=>$this->spArgs('wuliu'),'wuliunum'=>$this->spArgs('wuliunum'),'beizhu2'=>$this->spArgs('beizhu2')));
	$re=$gb->find(array('id'=>$this->spArgs('cid')));
	if ($re[kefu]=="") {
	$gb->update(array('id'=>$this->spArgs('cid')),array('kefu'=>$_SESSION[admin_username][username]));
	}
	$this->logs("修改订单物流信息，订单编号：".$re[ordernum]);
	echo "物流信息已保存";
	}
//加载小分类
	function loadsort() {
	$k=$this->spArgs("k");
	$ls=spClass("products");
	$ss=$ls->findAll(array('gid'=>$k));
	$rt="<select name='smallname' id='smallname' onchange='getprice(this.value)'>";
	$rt.="<option value=''>-请选择-</option>";
	foreach ($ss as $v) {
	$rt.='<option value="'.$v[pname].'">'.$v[pname].'</option>';
	}
	$rt.='</select>';
	echo $rt;
	}
//加载小分类
	function loadsort2() {
	$k=$this->spArgs("k");
	$ls=spClass("products");
	$ss=$ls->findAll(array('gid'=>$k));
	$rt="<select name='smallname' id='smallname' onchange=\"javascript:document.getElementById('hidsel').value=this.value\">";
	$rt.="<option value=''>-请选择-</option>";
	foreach ($ss as $v) {
	$rt.='<option value="'.$v[pname].'">'.$v[pname].'</option>';
	}
	$rt.='</select>';
	echo $rt;
	}
//显示订单搜索结果
	function searchorder() {
		$gp=spClass("groups");
		$this->group=$gp->findAll();
	$this->sid=$this->spArgs('sid');
	$this->types=$this->spArgs('types');
	$this->keys=$this->spArgs('keys');
	$this->gp=$this->spArgs('gp');
	$this->bigname=$this->spArgs('bigname');
	$this->smallname=$this->spArgs('smallname');
	$this->s1=$this->spArgs('s1');
	$this->s2=$this->spArgs('s2');
	$this->s3=$this->spArgs('s3');
	$this->s4=$this->spArgs('s4');
	$this->page=$this->spArgs('page');
		$banklist = spClass("orders");
		$act=$this->spArgs('sid');
		//客服分类
		$users=spClass("adminuser");
		$qx=$users->find(array('username'=>$_SESSION[admin_username][username]));
		$qx=explode("|",$qx[qx]);
			//组合产品组ID
			$temp=explode(",",$qx[1]);
			$nums=count($temp);
		//组合产品组ID
			$nums=@count($temp);
		if (!empty($qx[1])) {
			if ($nums>0) {
				$o=0;
				foreach ($temp as $value) {
					if ($o=="0") {
					$farr="gid=".$value;
					} else {
					$farr=$farr." || gid=".$value;
					}
					$o++;
				}
				$farr="&& (".$farr.")";
			} else {
			$farr=$temp[1];
			}
		} else {
		$farr="";
		}
		if ($qx[0]=="1,2,3,4,5,6,7") {
		$hou=" ";
		} else {
			if ($farr=="") {
			$hou=" && (kefu is NULL || kefu='".$_SESSION[admin_username][username]."') ";
			} else {
			$hou=" && (kefu is NULL || kefu='".$_SESSION[admin_username][username]."') ".$farr." ";
			}
		}
		switch ($act) {
		case "1":
			$keys=$this->spArgs('keys');
			switch ($this->spArgs('types')) {
			case "1":
			$myarr=" ordernum like '%$keys%'".$hou;
			break;
			case "2":
			$myarr=" realname like '%$keys%'".$hou;
			break;
			case "3":
			$myarr=" address like '%$keys%'".$hou;
			break;
			case "4":
			$myarr=" mob like '%$keys%'".$hou;
			break;
			case "5":
			$myarr=" beizhu like '%$keys%'".$hou;
			break;
			case "6":
			$myarr=" beizhu2 like '%$keys%'".$hou;
			break;
			case "7":
			$myarr=" wuliu like '%$keys%'".$hou;
			break;
			case "8":
			$myarr=" wuliunum like '%$keys%'".$hou;
			break;
			case "9":
			$myarr=" pname like '%$keys%'".$hou;
			break;
			}
		break;
		case "2":
		$myarr=" gid=".$this->spArgs('gp').$hou;
		break;
		case "4":
		$s1=$this->spArgs('s1');
		$s2=$this->spArgs('s2')." 23:59:00";
		$myarr=" addtime>='$s1' && addtime<='$s2'".$hou;
		break;
		case "5":
		$s3=$this->spArgs('s3');
		$s4=$this->spArgs('s4')." 23:59:00";
		$myarr=" sendtime>='$s3' && sendtime<='$s4'".$hou;
		}
		if (isset($myarr)) {
		$this->results=$banklist->spPager($this->spArgs('page',1),10)->findAll($myarr,"id desc",null,null);
		$this->pager=$banklist->spPager()->getPager();
		}
		$this->display("admin/searchorder.html");
	}
//添加订单_显示页面
	function addorder() {
	$gp=spClass("groups");
		//获取用户权限
		$users=spClass("adminuser");
		$qx=$users->find(array('username'=>$_SESSION[admin_username][username]));
		$qx=explode("|",$qx[qx]);
			//组合产品组ID
			$temp=explode(",",$qx[1]);
			$nums=count($temp);
		if (!empty($qx[1])) {
			if ($nums>0) {
				$o=0;
				foreach ($temp as $value) {
					if ($o=="0") {
					$farr="id=".$value;
					} else {
					$farr=$farr." || id=".$value;
					}
					$o++;
				}
				$farr=$farr;
			} else {
			$farr=$temp[1];
			}
			$this->group=$gp->findAll($farr);
		} else {
		$this->group=$gp->findAll();
		}
	$this->display("admin/addorder.html");
	}
//添加订单_加载自定义项
	function loadoption() {
	$k=$this->spArgs("k");
	$forms=spClass("forms");
	$form=$forms->findAll(array('gid'=>$k,'olds'=>'1'));
	foreach ($form as $v) {
	$t[]="<li><input type='hidden' name='usertitle[]' value='".$v[fname]."'><strong>".$v[fname]."：</strong><input name='uservalue[]' type='text' /></li>";
	}
	$tt=implode("",$t);
	echo "<ul>".$tt."</ul>";
	}
//添加订单_获取价格
	function getprice() {
	$j=$this->spArgs("j");
	$sp=spClass("products");
	$re=$sp->find(array('pname'=>$j));
	echo $re[price];
	}
//添加订单_保存手动订单
	function savemyorder() {
	$ordernum=date("YmdHis").rand(100,999);
	$addtime=date("Y-m-d H:i:s");
	if ($this->spArgs('sendtime')!="") {
	$sendtime=$this->spArgs('sendtime')." ".date("H:i:s");
	} else {
	$sendtime="0000-00-00 00:00:00";
	}
	$usertitle=$this->spArgs('usertitle');
	$uservalue=$this->spArgs('uservalue');
	$area = spClass('spIpArea')->get(egetip());
	$i=0;
	foreach ($uservalue as $v) {
		if ($v!="") {
			if (!isset($t)) {
				$t=$usertitle[$i]."|".$v;
			} else {
				$t=$t."~".$usertitle[$i]."|".$v;
			}
		}
	$i++;	
	} 
	$arr=array(
		'gid' => $this->spArgs('bigname'),
		'ordernum' => $ordernum,
		'pname' => $this->spArgs('smallname'),
		'price' => $this->spArgs('price'),
		'totle' => $this->spArgs('price')*$this->spArgs('nums'),
		'nums' => $this->spArgs('nums'),
		'realname' => $this->spArgs('realname'),
		'mob' => $this->spArgs('mob'),
		'address' => $this->spArgs('address'),
		'beizhu' => $this->spArgs('beizhu'),
		'useroption' => $t,
		'payment' => $this->spArgs('select3'),
		'addtime' => $addtime,
		'ips' => '127.0.0.1',
		'areas' => $area,
		'zt1' => $this->spArgs('select2'),
		'zt2' => $this->spArgs('select'),
		'wuliu' => $this->spArgs('wuliu'),
		'wuliunum' => $this->spArgs('wuliunum'),
		'kefu' => $_SESSION[admin_username][username],
		'beizhu2' => $this->spArgs('beizhu2'),
		'sendtime' => $sendtime,
		'url1' => '手动添加',
		'url2' => '手动添加'
	);
	$orders=spClass("orders");
	$orders->create($arr);
	$this->logs("手动添加订单，订单编号：".$ordernum);
	$this->success("订单添加成功！",spUrl('admin','orderlist'));
	}
//订单回收站列表
	function isdel() {
		$conditions = array('zt1','zt2','payment');
		$this->zt1='';
		$this->zt2='';
		$this->payment='';
		foreach($conditions as $value){
			if(isset($_REQUEST[$value])){
				$this->$value = $_REQUEST[$value];
			}
		}
		foreach ($_REQUEST as $key=>$v) {
		$v=trim($v);
		if ($v!="") {
			if ($v!="all") {
				if ($key=="zt1" || $key=="zt2" || $key=="payment") {
				$myarr[$key]=$v;
				}
			}
		}
		}
		$banklist = spClass("rubbish");
		$this->results=$banklist->spPager($this->spArgs('page',1),12)->findAll($myarr,"id desc",null,null);
		$this->pager=$banklist->spPager()->getPager();
		$this->display("admin/deled.html");
	}
//计算某月的天数
	function getDaysofMonth($year, $month) {
		if ($year < 1970 || $month < 1 || $month > 12) {
			return false;
		}
		$days = date("d", mktime(0, 0, 0, $month+1, 1-1, $year));
		return $days;
	}
//报表统计_显示日统计页面
	function table_day() {
		$basedir = dirname(__FILE__); 
		$basedir=str_replace("controller","",$basedir);
		$order=spClass("orders");
		$year=$this->spArgs('SelTjYear');
		$month=$this->spArgs('SelTjMonth');
		$year1=$this->spArgs('SelTjYear1');
		$month1=$this->spArgs('SelTjMonth1');
		$year2=$this->spArgs('SelTjYear2');
		$month2=$this->spArgs('SelTjMonth2');
		//获取某月天数
		$days1=$this->getDaysofMonth($year,$month);
		$days2=$this->getDaysofMonth($year1,$month1);
		$days3=$this->getDaysofMonth($year2,$month2);
		$act=$this->spArgs('act');
		switch ($act) {
		case "1":
			//获取每天销售额
			$j=0;
			for ($i=1;$i<=$days1;$i++) {
				if ($i=="1") {
				$yuefen="\"1\"";
				} else {
				$yuefen=$yuefen.","."\"$i\"";
				}
				if (strlen($i)=="1") {$i="0".$i;}
				if (strlen($month)=="1") {$month="0".$month;}
				$dates=$year."-".$month."-".$i;
				$newarr=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='2' ";
				$no1=$order->findAll($newarr);
				$t=0;
					foreach ($no1 as $v) {
					$t=$t+$v[price]*$v[nums];
					}
				if (isset($numlist)) {
				$numlist=$numlist.",".$t;
				} else {
				$numlist=$t;
				}
				$j=$j+$t;
			}
			$title=$year."年".$month."月销售报表-总成交额:".$j."元-(X轴:日  Y轴:成交额)";
			$bujin=round($j/10);
			//生成配置文件
			$content=file_get_contents("include/old.txt");
			$t=preg_replace("/text\":\".*?\"/i","text\":\"".$title."\"",$content,1);
			$t=preg_replace("/values\":\[.*?\]/i","values\":[".$numlist."]",$t,1);
			$t=preg_replace("/labels\":\[.*?\]/i","labels\":[".$yuefen."]",$t,1);
			$t=preg_replace("/max\":.*?,/i","max\":".$j.",",$t,1);
			$t=preg_replace("/steps\":.*?,/i","steps\":".$bujin.",",$t,1);
			$fo=fopen("include/old.txt","w"); 
			fwrite($fo,$t);
			fclose($fo);
			$this->z1="<div id=\"my_chart\" style=\"height:600px; color: #C10C02 border:1px solid #CCC\"></div><script>swfobject.embedSWF(\"include/open-flash-chart.swf\", \"my_chart\", \"98%\", \"400\", \"9.0.0\",\"expressInstall.swf\",{\"data-file\":\"include/old.txt\"});</script></div>";
		break;
		case "2":
			//获取每天销售额
			$gid=$this->spArgs('gp');
			$j=0;
			for ($i=1;$i<=$days2;$i++) {
				if ($i=="1") {
				$yuefen="\"1\"";
				} else {
				$yuefen=$yuefen.","."\"$i\"";
				}
				if (strlen($i)=="1") {$i="0".$i;}
				if (strlen($month1)=="1") {$month1="0".$month1;}
				$dates=$year1."-".$month1."-".$i;
				$newarr=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='2' && gid='$gid' ";
				$no1=$order->findAll($newarr);
				$t=0;
					foreach ($no1 as $v) {
					$t=$t+$v[price]*$v[nums];
					}
				if (isset($numlist)) {
				$numlist=$numlist.",".$t;
				} else {
				$numlist=$t;
				}
				$j=$j+$t;
			}
			$title=$year1."年".$month1."月销售报表-总成交额:".$j."元-(X轴:日  Y轴:成交额)";
			$bujin=round($j/10);
			//生成配置文件
			$content=file_get_contents("include/old.txt");//修改支付宝配置文件(即时到帐)
			$t=preg_replace("/text\":\".*?\"/i","text\":\"".$title."\"",$content,1);
			$t=preg_replace("/values\":\[.*?\]/i","values\":[".$numlist."]",$t,1);
			$t=preg_replace("/labels\":\[.*?\]/i","labels\":[".$yuefen."]",$t,1);
			$t=preg_replace("/max\":.*?,/i","max\":".$j.",",$t,1);
			$t=preg_replace("/steps\":.*?,/i","steps\":".$bujin.",",$t,1);
			$fo=fopen("include/old.txt","w"); 
			fwrite($fo,$t);
			fclose($fo);
			$this->z1="<div id=\"my_chart\" style=\"height:600px; color: #C10C02 border:1px solid #CCC\"></div><script>swfobject.embedSWF(\"include/open-flash-chart.swf\", \"my_chart\", \"98%\", \"400\", \"9.0.0\",\"expressInstall.swf\",{\"data-file\":\"include/old.txt\"});</script></div>";
		break;
		case "3":
			$smallname=$this->spArgs('hidsel');
			//获取每天销售额
			$j=0;
			for ($i=1;$i<=$days3;$i++) {
				if ($i=="1") {
				$yuefen="\"1\"";
				} else {
				$yuefen=$yuefen.","."\"$i\"";
				}
				if (strlen($i)=="1") {$i="0".$i;}
				if (strlen($month2)=="1") {$month2="0".$month2;}
				$dates=$year2."-".$month2."-".$i;
				$newarr=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='2' && pname='$smallname' ";
				$no1=$order->findAll($newarr);
				$t=0;
					foreach ($no1 as $v) {
					$t=$t+$v[price]*$v[nums];
					}
				if (isset($numlist)) {
				$numlist=$numlist.",".$t;
				} else {
				$numlist=$t;
				}
				$j=$j+$t;
			}
			$title=$year2."年".$month2."月销售报表-总成交额:".$j."元-(X轴:日  Y轴:成交额)";
			$bujin=round($j/10);
			//生成配置文件
			$content=file_get_contents("include/old.txt");//修改支付宝配置文件(即时到帐)
			$t=preg_replace("/text\":\".*?\"/i","text\":\"".$title."\"",$content,1);
			$t=preg_replace("/values\":\[.*?\]/i","values\":[".$numlist."]",$t,1);
			$t=preg_replace("/labels\":\[.*?\]/i","labels\":[".$yuefen."]",$t,1);
			$t=preg_replace("/max\":.*?,/i","max\":".$j.",",$t,1);
			$t=preg_replace("/steps\":.*?,/i","steps\":".$bujin.",",$t,1);
			$fo=fopen("include/old.txt","w"); 
			fwrite($fo,$t);
			fclose($fo);
			$this->z1="<div id=\"my_chart\" style=\"height:600px; color: #C10C02 border:1px solid #CCC\"></div><script>swfobject.embedSWF(\"include/open-flash-chart.swf\", \"my_chart\", \"98%\", \"400\", \"9.0.0\",\"expressInstall.swf\",{\"data-file\":\"include/old.txt\"});</script></div>";
		}
		$gb = spClass('groups');
		$this->group=$gb->findAll();
		$this->display("admin/table_day.html");
	}
	//订单导出(显示)
	function output() {
		$gb = spClass('groups');
		$this->group=$gb->findAll();
		$this->display("admin/output.html");
	}
	//导出订单为excel
	function doexcel() {
		$arrnum=5;
		$zt1=$this->spArgs('zt1');
		$zt2=$this->spArgs('zt2');
		$zt3=$this->spArgs('zt3');
		$zt4=$this->spArgs('zt4');
		$s=$this->spArgs('s');
		$e=$this->spArgs('e');
		$ifarray=array("$zt1","$zt2","$zt3","$zt4","$s","$e");
		$sqlarray=array(" where zt1='$zt1'"," where zt2='$zt2'"," where payment='$zt3'"," where gid='$zt4'"," where addtime>='$s' && addtime<='$e'");
		for($i=0;$i<$arrnum;$i++) {
		if($ifarray[$i]=="" || $ifarray[$i]<0)
		$sqlarray[$i]="";
		$haveWhere=false;
		for($j=0;$j<$i;$j++) {
		$wherePosition=strpos($sqlarray[$j],"where");
		if(($wherePosition=="1")&&($haveWhere==false)) {
		$sqlarray[$i]=ereg_replace("where","&&",$sqlarray[$i]);
		$haveWhere=true;
		}
		}
		}
		for($i=0;$i<$arrnum;$i++) {
		$sql3=$sql3.$sqlarray[$i];
		}	
		$conditions=str_replace("where ","",$sql3);
		$gb=spClass("orders");
		$this->mydata=$gb->findAll($conditions);
		$now=date("Y-m-d");
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition:filename=".$now.".xls");
		$this->display("admin/doexcel.html");
	}
}
?>