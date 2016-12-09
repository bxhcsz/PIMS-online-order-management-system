<?php
//---------------------------------------------------------
//财付通即时到帐支付请求示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require_once ("classes/RequestHandler.class.php");
require_once ("tenpay_config.php");







//订单号，此处用时间加随机数生成，商户根据自己情况调整，只要保持全局唯一就行
$out_trade_no = $_REQUEST['ordernum'];
$total_fee = $_REQUEST['price']*100;
$total_fee2 = $_REQUEST['price'];


/* 创建支付请求对象 */
$reqHandler = new RequestHandler();
$reqHandler->init();
$reqHandler->setKey($key);
$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");

//----------------------------------------
//设置支付参数 
//----------------------------------------
$reqHandler->setParameter("partner", $partner);
$reqHandler->setParameter("out_trade_no", $out_trade_no);
$reqHandler->setParameter("total_fee", $total_fee);  //总金额
$reqHandler->setParameter("return_url",  $return_url);
$reqHandler->setParameter("notify_url", $notify_url);
$reqHandler->setParameter("body", "在线支付");
$reqHandler->setParameter("bank_type", "DEFAULT");  	  //银行类型，默认为财付通
//用户ip
$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);//客户端IP
$reqHandler->setParameter("fee_type", "1");               //币种
$reqHandler->setParameter("subject","商品名称");          //商品名称，（中介交易时必填）

//系统可选参数
$reqHandler->setParameter("sign_type", "MD5");  	 	  //签名方式，默认为MD5，可选RSA
$reqHandler->setParameter("service_version", "1.0"); 	  //接口版本号
$reqHandler->setParameter("input_charset", "GBK");   	  //字符集
$reqHandler->setParameter("sign_key_index", "1");    	  //密钥序号

//业务可选参数
$reqHandler->setParameter("attach", "");             	  //附件数据，原样返回就可以了
$reqHandler->setParameter("product_fee", "");        	  //商品费用
$reqHandler->setParameter("transport_fee", "0");      	  //物流费用
$reqHandler->setParameter("time_start", date("YmdHis"));  //订单生成时间
$reqHandler->setParameter("time_expire", "");             //订单失效时间
$reqHandler->setParameter("buyer_id", "");                //买方财付通帐号
$reqHandler->setParameter("goods_tag", "");               //商品标记
$reqHandler->setParameter("trade_mode","1");              //交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
$reqHandler->setParameter("transport_desc","");              //物流说明
$reqHandler->setParameter("trans_type","1");              //交易类型
$reqHandler->setParameter("agentid","");                  //平台ID
$reqHandler->setParameter("agent_type","");               //代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
$reqHandler->setParameter("seller_id","");                //卖家的商户号



//请求的URL
$reqUrl = $reqHandler->getRequestURL();

//获取debug信息,建议把请求和debug信息写入日志，方便定位问题
/**/
$debugInfo = $reqHandler->getDebugInfo();
//echo "<br/>" . $reqUrl . "<br/>";
//echo "<br/>" . $debugInfo . "<br/>";


?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>财付通即时到帐</title>
</head>
<body>
<br/>
<table width="500" border="1" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse" bordercolor="#c6c6c6">
  <tr>
    <td height="25" colspan="2" align="center" bgcolor="#f2f2f2"><strong>订单确认</strong></td>
  </tr>
  <tr>
    <td width="112" height="25" align="right">订单编号：</td>
    <td width="382" align="left">&nbsp;<?=$out_trade_no?></td>
  </tr>
  <tr>
    <td height="25" align="right">产品名称：</td>
    <td align="left">&nbsp;<?=$_REQUEST[pname]?></td>
  </tr>
  <tr>
    <td height="25" align="right">订单金额：</td>
    <td align="left">&nbsp;<?=$_REQUEST[price]?>元</td>
  </tr>
  <tr>
    <td height="25" align="right">收货人姓名：</td>
    <td align="left">&nbsp;<?=$_REQUEST[user]?></td>
  </tr>
  <tr>
    <td height="25" align="right">收货人电话：</td>
    <td align="left">&nbsp;<?=$_REQUEST[mob]?></td>
  </tr>
  <tr>
    <td height="25" align="right">收货人地址：</td>
    <td align="left">&nbsp;<?=$_REQUEST[addr]?></td>
  </tr>
  <tr>
    <td height="25" colspan="2" align="center">
<form action="<?php echo $reqHandler->getGateUrl() ?>" method="post" target="_blank">
<?php
$params = $reqHandler->getAllParameters();
foreach($params as $k => $v) {
	echo "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
}
?>
<input type="submit" value="前往网上银行支付">
</form>
</td>
  </tr>
</table>
</body>
</html>
