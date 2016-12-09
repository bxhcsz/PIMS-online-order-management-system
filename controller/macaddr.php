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
class macaddr extends spController
{
//显示mac页面
	function show() {
		$sp=spClass("mac");
		$rs=$sp->findAll();
		if ($rs[0][is_open]=="yes") {
		$this->is_open="<font color=green>已开启mac登陆绑定</font>";
		} else {
		$this->is_open="<font color=red>已关闭mac登陆绑定</font>";
		}
		$arr=$rs[0][macaddr];
		$this->temp=@explode(",",$arr);
		
        $this->display("admin/mac.html");
	}
//开启/关闭mac绑定
	function open() {
		$sp=spClass("mac");
		$rs=$sp->findAll();
		$temp1=$rs[0][is_open];
		$temp2=$rs[0][macaddr];
		if (empty($temp2) || $temp2=="") {
		$this->success("尚未进行客户端绑定，请先绑定客户端后再开启本功能！", spUrl("macaddr", "show"));
		} else {
			if ($temp1=="yes") {
			$sp->update(NULL,array('is_open'=>'no'));
			$this->success("已关闭！", spUrl("macaddr", "show"));
			} else {
			$sp->update(NULL,array('is_open'=>'yes'));
			$this->success("已开启！", spUrl("macaddr", "show"));
			}
		}
	}
//添加mac绑定
	function add() {
		$new=$this->spArgs('mymac');
		if (empty($new) || $new=="") {
			$this->success("请输入客户端mac地址", spUrl("macaddr", "show"));
		}
		$sp=spClass("mac");
		$rs=$sp->findAll();
		//判断是否重复绑定
		$temp=$rs[0][macaddr];
		$tt=@explode(",",$temp);
		foreach ($tt as $v) {
			if ($new==$v) {
			$this->success("该客户端已经绑定，无需重复操作！", spUrl("macaddr", "show"));
			}
		}
		if (empty($temp) || $temp=="") {
			$newmac=$new;
		} else {
			$newmac=$temp.",".$new;
		}
		$sp->update(NULL,array('macaddr'=>$newmac));
		$this->success("绑定成功！", spUrl("macaddr", "show"));
	}
	//删除mac绑定
	function del() {
		$mac=$this->spArgs('mac');
		$sp=spClass("mac");
		$rs=$sp->findAll();
		$temp=$rs[0][macaddr];
		$tt=@explode(",",$temp);
		if (strpos($temp, ",")===false) {
			$sp->update(NULL,array('macaddr'=>NULL));
		} else {
			foreach ($tt as $key=>$v) {
				$tv=trim($v);
				if ($v==$mac) {
				unset($tt[$key]);
				}
			}
			$nums=count($tt);
			if ($nums<2) {
			$final=$tt[0];
			} else {
			$final=@implode(",",$tt);
			}
			$sp->update(NULL,array('macaddr'=>$final));
		}
		$this->success("解绑成功！", spUrl("macaddr", "show"));
	}

}
?>