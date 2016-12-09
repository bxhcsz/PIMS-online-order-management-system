<?php require_once "paypal.config.inc.php" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<form action="<?php echo $paypal_pay_url ?>" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $paypal_business ?>">
<input type="hidden" name="item_name" value="商品名称">
<input type="hidden" name="amount" value="0.01">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="return" value="http://<?php echo $_SERVER["SERVER_NAME"] ?>/pay/paypal_finish.php">
<input type="hidden" name="invoice" value="20102454020">
<input type="hidden" name="charset" value="utf-8">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="no_note" value="">
<input type="hidden" name="notify_url" value="http://<?php echo $_SERVER["SERVER_NAME"] ?>/pay/paypal_finish.php">
<input type="hidden" name="rm" value="2">
<input type="hidden" name="cancel_return" value="http://<?php echo $_SERVER["SERVER_NAME"] ?>/pay/">
<input type="submit" name="Submit" value="提交" />
</form>
</body>
</html>
