<?php
define("APP_PATH",dirname(__FILE__));
define("SP_PATH",APP_PATH."/SpeedPHP");
@date_default_timezone_set('PRC');
$spConfig = array(
	"db" => array(
		'host'=>'',
		'login'=>'',
		'password'=>'',
		'database'=>'',
		'prefix'=>''
	),
	"view" =>array(
		'enabled' => TRUE,
		'config' => array(
			'template_dir' => APP_PATH.'/tpl',
			'compile_dir' => APP_PATH.'/tmp',
			'cache_dir' => APP_PATH.'/tmp',
			'left_delimiter' => '<{',
			'right_delimiter' => '}>',
			'allow_php_tag' => TRUE,
		),
	),
	'launch' => array( 
		 'router_prefilter' => array( 
			array('spAcl','maxcheck')
		 )
	 ),
	 'ext' => array(
	 	'spAcl' => array(
	 		'prompt' => array("adminuser", "acljump"),
	 	),
	 ),
	'html' => array(
    	'enabled' => TRUE,
	),
);
?>