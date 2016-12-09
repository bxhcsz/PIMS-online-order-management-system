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

error_reporting(7);
include('controller/search.php');
require '../include/api.class.php';
$act=trim($_GET['act']);
$api=new KaidiApi();
//检查物流公司是否需要验证码
if($act=='check_valicode'){
	$wlgs_dm=trim($_GET['wlgs_dm']);
	if($api->needValicode($wlgs_dm)){
		$result=array('need'=>1);
	}else{
		$result=array('need'=>0);
	}
	output($result);
}elseif($act=='load_valicode'){
	$img=$api->loadValicode(trim($_GET['wlgs_dm']));
	echo $img;
}elseif($act=='load_wl'){
	$wlgs_dm=trim($_GET['wlgs_dm']);//快递公司代码
	$wldh=trim($_GET['wldh']);//订单号
	$valicode=trim($_GET['valicode']);//验证码
	$result=$api->loadWL($wldh, $wlgs_dm,$valicode);
	output($result);
}

/*JSON返回结果
 * @param $result 返回的结果数组
 * 
 */
function output($result){
	if(is_string($result)){
		$result=array('message'=>$result);
	}
	header('Content-Encoding: plain');
    header("Content-Type:text/html; charset=utf-8");
    die(json_encode($result));
}
?>