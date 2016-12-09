<?php
function respond()
{
	global $db, $paypal_business, $paypal_auth_url;

	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	foreach ($_POST as $key => $value)
	{
		$value = urlencode(stripslashes($value));
		$req .= "&$key=$value";
	}

	// assign posted variables to local variables
	$item_name = safeRequest(2, 'item_name', 1);
	$item_number = safeRequest(2, 'item_number', 1);
	$payment_status = safeRequest(2, 'payment_status', 1);
	$payment_amount = safeRequest(2, 'mc_gross', 1);
	$payment_currency = safeRequest(2, 'mc_currency', 1);
	$txn_id = safeRequest(2, 'txn_id', 1);
	$receiver_id = safeRequest(2, 'receiver_id', 1);
	$order_sn = safeRequest(2, 'invoice', 1);
	$memo = safeRequest(2, 'memo', 1);

	// post back to PayPal system to validate
	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) ."\r\n\r\n";
	$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

	if (!$fp)
	{
		fclose($fp);

		return false;
	}
	else
	{
		fputs($fp, $header . $req);
		while (!feof($fp))
		{
			$res = fgets($fp, 1024);
			if (strcmp($res, 'VERIFIED') == 0)
			{
				// check the payment_status is Completed
				if ($payment_status != 'Completed' && $payment_status != 'Pending')
				{
					fclose($fp);

					return false;
				}

				// check that txn_id has not been previously processed
				/*$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('order_action') . " WHERE action_note LIKE '" . mysql_like_quote($txn_id) . "%'";
				if ($GLOBALS['db']->getOne($sql) > 0)
				{
					fclose($fp);

					return false;
				}*/

				// check that receiver_email is your Primary PayPal email
				if ($receiver_id != $paypal_business)
				{
					fclose($fp);

					return false;
				}

				// check that payment_amount/payment_currency are correct
				/*$sql = "SELECT order_amount FROM " . $GLOBALS['ecs']->table('pay_log') . " WHERE log_id = '$order_sn'";
				if ($GLOBALS['db']->getOne($sql) != $payment_amount)
				{
					fclose($fp);

					return false;
				}*/
				if ('USD' != $payment_currency)
				{
					fclose($fp);

					return false;
				}

				// process payment
				//updateamount($order_sn);
				//$db->query("UPDATE ec_orders SET status = 1, payedcredits = '$payment_amount' WHERE orderno = '$order_sn'");	
				fclose($fp);

				return true;
			}
			elseif (strcmp($res, 'INVALID') == 0)
			{
				// log for manual investigation
				fclose($fp);

				return false;
			}
		}
	}
}
