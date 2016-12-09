<?php

$mail = array
(
	'mailsend' => 2,
	'server' => 'smtp.163.com',
	'port' => 25,
	'auth' => 1,
	'from' => '909toy <gba_book@163.com>',
	'auth_username' => 'gba_book@163.com',
	'auth_password' => 'iloveyou',
	'maildelimiter' => 0,
	'mailusername' => 1,
	'sendmail_silent' => 1,
);

$charset = 'utf-8';
$success = 1;

if($mail['sendmail_silent']) {
	error_reporting(0);
}

$maildelimiter = $mail['maildelimiter'] == 1 ? "\r\n" : ($mail['maildelimiter'] == 2 ? "\r" : "\n");
$mailusername = isset($mail['mailusername']) ? $mail['mailusername'] : 1;

$email_subject = '=?'.$charset.'?B?'.base64_encode(str_replace("\r", '', str_replace("\n", '', $email_subject))).'?=';
$email_message = chunk_split(base64_encode(str_replace("\r\n.", " \r\n..", str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $email_message)))))));

$email_from = $mail['from'];
$email_from = $email_from == '' ? '=?'.$charset.'?B?'.base64_encode('909toy')."?= <toy909@163.com>" : (preg_match('/^(.+?) \<(.+?)\>$/',$email_from, $from) ? '=?'.$charset.'?B?'.base64_encode($from[1])."?= <$from[2]>" : $email_from);

foreach(explode(',', $email_to) as $touser) {
	$tousers[] = preg_match('/^(.+?) \<(.+?)\>$/',$touser, $to) ? ($mailusername ? '=?'.$charset.'?B?'.base64_encode($to[1])."?= <$to[2]>" : $to[2]) : $touser;
}
$email_to = implode(',', $tousers);

$headers = "From: $email_from{$maildelimiter}X-Priority: 3{$maildelimiter}X-Mailer: NewGameSolution {$maildelimiter}MIME-Version: 1.0{$maildelimiter}Content-type: text/plain; charset=$charset{$maildelimiter}Content-Transfer-Encoding: base64{$maildelimiter}";

$mail['port'] = $mail['port'] ? $mail['port'] : 25;

if($mail['mailsend'] == 1 && function_exists('mail')) {

	$success = @mail($email_to, $email_subject, $email_message, $headers);

} elseif($mail['mailsend'] == 2) {

	if(!$fp = fsockopen($mail['server'], $mail['port'], $errno, $errstr, 30)) {
		//exit("($mail[server]:$mail[port]) CONNECT - Unable to connect to the SMTP server");
		
		$success = -1;
		return;
	}
 	stream_set_blocking($fp, true);

	$lastmessage = fgets($fp, 512);
	if(substr($lastmessage, 0, 3) != '220') {
		//exit("$mail[server]:$mail[port] CONNECT - $lastmessage");
		$success = -1;
		return;
	}

	fputs($fp, ($mail['auth'] ? 'EHLO' : 'HELO')." NGP\r\n");
	$lastmessage = fgets($fp, 512);
	if(substr($lastmessage, 0, 3) != 220 && substr($lastmessage, 0, 3) != 250) {
		//exit("($mail[server]:$mail[port]) HELO/EHLO - $lastmessage");
		$success = -1;
		return;
	}

	while(1) {
		if(substr($lastmessage, 3, 1) != '-' || empty($lastmessage)) {
 			break;
 		}
 		$lastmessage = fgets($fp, 512);
	}

	if($mail['auth']) {
		fputs($fp, "AUTH LOGIN\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 334) {
			//exit("($mail[server]:$mail[port]) AUTH LOGIN - $lastmessage");
			$success = -1;
			return;
		}

		fputs($fp, base64_encode($mail['auth_username'])."\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 334) {
			//exit("($mail[server]:$mail[port]) USERNAME - $lastmessage");
			$success = -1;
			return;
		}

		fputs($fp, base64_encode($mail['auth_password'])."\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 235) {
			//exit("($mail[server]:$mail[port]) PASSWORD - $lastmessage");
			$success = -1;
			return;
		}

		$email_from = $mail['from'];
	}

	fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_from).">\r\n");
	$lastmessage = fgets($fp, 512);
	if(substr($lastmessage, 0, 3) != 250) {
		fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_from).">\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 250) {
			//exit("($mail[server]:$mail[port]) MAIL FROM - $lastmessage");
			$success = -1;
			return;
		}
	}

	$email_tos = array();
	foreach(explode(',', $email_to) as $touser) {
		$touser = trim($touser);
		if($touser) {
			fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser).">\r\n");
			$lastmessage = fgets($fp, 512);
			if(substr($lastmessage, 0, 3) != 250) {
				fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser).">\r\n");
				$lastmessage = fgets($fp, 512);
				//exit("($mail[server]:$mail[port]) RCPT TO - $lastmessage");
				$success = -1;
				return;
			}
		}
	}

	fputs($fp, "DATA\r\n");
	$lastmessage = fgets($fp, 512);
	if(substr($lastmessage, 0, 3) != 354) {
		//exit("($mail[server]:$mail[port]) DATA - $lastmessage");
		$success = -1;
		return;
	}

	$headers .= 'Message-ID: <'.gmdate('YmdHs').'.'.substr(md5($email_message.microtime()), 0, 6).rand(100000, 999999).'@'.$_SERVER['HTTP_HOST'].">{$maildelimiter}";

	fputs($fp, "Date: ".gmdate('r')."\r\n");
	fputs($fp, "To: ".$email_to."\r\n");
	fputs($fp, "Subject: ".$email_subject."\r\n");
	fputs($fp, $headers."\r\n");
	fputs($fp, "\r\n\r\n");
	fputs($fp, "$email_message\r\n.\r\n");
	fputs($fp, "QUIT\r\n");

} elseif($mail['mailsend'] == 3) {

	ini_set('SMTP', $mail['server']);
	ini_set('smtp_port', $mail['port']);
	ini_set('sendmail_from', $email_from);

	@mail($email_to, $email_subject, $email_message, $headers);

}

?>