<?php
require_once("../../config.php");
//从 PayPal 出读取 POST 信息同时添加变量cmd
$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}
//建议在此将接受到的信息记录到日志文件中以确认是否收到 IPN 信息
//将信息 POST 回给 PayPal 进行验证
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type:application/x-www-form-urlencoded\r\n";
$header .= "Content-Length:" . strlen($req) ."\r\n\r\n";
//在 Sandbox 情况下，设置：
//$fp = fsockopen(www.sandbox.paypal.com,80,$errno,$errstr,30);
$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
//将 POST 变量记录在本地变量中
//该付款明细所有变量可参考：
//https://www.paypal.com/IntegrationCenter/ic_ipn-pdt-variable-reference.html
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];
$ordernum=$_POST['invoice'];
//…
//判断回复 POST 是否创建成功
if (!$fp) {
//HTTP 错误
}else {
//将回复 POST 信息写入 SOCKET 端口
fputs ($fp, $header .$req);
//开始接受 PayPal 对回复 POST 信息的认证信息
while (!feof($fp)) {
$res = fgets ($fp, 1024);
//已经通过认证
if (strcmp ($res, "VERIFIED") == 0) {
//检查付款状态
//检查 txn_id 是否已经处理过
//检查 receiver_email 是否是您的 PayPal 账户中的 EMAIL 地址
//检查付款金额和货币单位是否正确
//处理这次付款，包括写数据库
$host=$spConfig['db']['host'];
$logname=$spConfig['db']['login'];
$logpass=$spConfig['db']['password'];
$logdata=$spConfig['db']['database'];
$prefix=$spConfig['db']['prefix'];
mysql_connect($host,$logname,$logpass);
mysql_select_db($logdata);
mysql_query("update ".$prefix."orders set zt2=2 where ordernum='$ordernum'");
echo "Success!<br>";
echo "Order number:".$ordernum."<br>";
echo "Product Name:".$item_name."<br>";
echo "Amount:".$payment_amount."<br>";
}else if (strcmp ($res, "INVALID") == 0) {
//未通过认证，有可能是编码错误或非法的 POST 信息
}
}
fclose ($fp);
}
?>
