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
class sound extends spController
{
//显示新订单提醒页面
	function show() {
	$sp=spClass("rings");
	$this->re=$sp->find();
	$this->results=explode("|",$this->re[urls]);
	$this->display("admin/showsound.html");
	}
//上传声音文件
	function upload() {
	$test = spClass('icUpload',array('file'));//array('file')对应的是html中的 name="file"
	$allowtype = array('mid'=>TRUE);//设置允许上传.txt和.rar文件，不允许上传其它类型
	$test -> set('ALLOW_SUFFIX',$allowtype);//使用set函数操作设置
	$test -> set('MAXSIZE',10240);//使用set函数设置，最大允许上传文件大小为102400字节，即100KB
	$test -> set('SAVEPATH','./include/midi/');//使用set函数设置，文件保存路径为根目录，此处可以输入相对路径或绝对路径
	$fn=date("YmdHis");
	$filepath = $test -> save("".$fn.".mid");//保存到服务器上的文件名
	$sp=spClass("rings");
	$re=$sp->find();
	$tmp=$re[urls]."|".$fn.".mid";
	$sp->update(NULL,array('urls'=>$tmp));
	if($filepath != FALSE) {
	$this->success('上传成功', spUrl('sound', 'show'));
	} else {
	$this->success('上传失败，文件扩展名必须为.mid，大小不超过10K', spUrl('sound', 'show'));
	}
	}	
//修改声音提醒配置
	function csound() {
	$sp=spClass("rings");
	$sp->update(NULL,array('types'=>$this->spArgs('types'),'usedsound'=>$this->spArgs('sounds')));
	$this->success('保存成功', spUrl('sound', 'show'));
	}
//每20秒检测一次
	function ding() {
	$sp=spClass("rings");
	$re=$sp->find();
	if ($re[types]=="1" || $re[types]=="2" || $re[types]=="3") {
		$spp=spClass("orders");
		$nums=$spp->findCount(array('zt1'=>'1','zt2'=>'1'));
	}
	switch ($re[types]) {
	case "1":
	if ($nums>0) {
	echo "<embed src=\"include/midi/".$re[usedsound]."\" loop=\"false\" volume=\"int\" hidden=\"true\">";
	
	}
	break;
	case "2":
	if ($nums>0) {echo "<embed src=\"include/midi/".$re[usedsound]."\" loop=\"false\" volume=\"int\" hidden=\"true\">";}
	break;
	case "3":
	if ($nums>0) {echo "show";}
	break;
	case "4":
	}
	}
}
?>