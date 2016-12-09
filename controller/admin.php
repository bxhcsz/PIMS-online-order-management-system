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
include('skin/admin/fckeditor/fckeditor.php') ;
class admin extends spController
{
	function logs($dowhat) {
	$sp=spClass("logs");
	$dotime=date("Y-m-d H:i:s");
	$area = spClass('spIpArea')->get($this->egetip());
	$newarr=array(
		'dowhat' => $dowhat,
		'dotime' => $dotime,
		'doip' => $this->egetip(),
		'areas' => $area,
		'username' => $_SESSION[admin_username][username]
	);
	$sp->create($newarr);
	}
	function index(){
		$this->display("admin/login.html");
	}
    public function top()
    {
        $this->display("admin/top.html");
    }
    public function left()
    {
		$sp=spClass("adminuser");
		$tmp=$sp->find(array('username'=>$_SESSION[admin_username][username]));
		$temp2=explode("|",$tmp[qx]);
		$temp=explode(",",$temp2[0]);
		$this->qx1=in_array("1",$temp)?"1":"0";
		$this->qx2=in_array("2",$temp)?"1":"0";
		$this->qx3=in_array("3",$temp)?"1":"0";
		$this->qx4=in_array("4",$temp)?"1":"0";
		$this->qx5=in_array("5",$temp)?"1":"0";
		$this->qx6=in_array("6",$temp)?"1":"0";
		$this->qx7=in_array("7",$temp)?"1":"0";
        $this->display("admin/menu.html");
    }
    public function right()
    {
		include('controller/search.php');
		$sp=spClass("orders");
		$today=date("Y-m-d");
		$yes=date("Y-m-d",strtotime("-1 day"));
		$prefix = $GLOBALS['G_SP']['db']['prefix'];
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
		//读取本地当前系统版本
		$this->bb1=@file_get_contents("include/v.txt");
			//今日统计
			$this->t1=$sp->findCount(" DATE_FORMAT(addtime,'%Y-%m-%d')='$today' && zt1=1 ".$farr." ");
			$this->t2=$sp->findCount(" DATE_FORMAT(addtime,'%Y-%m-%d')='$today' && zt2=2 && zt1=2 ".$farr." ");
			$this->t3=$sp->findCount(" DATE_FORMAT(addtime,'%Y-%m-%d')='$today' ".$farr." ");
			$re1=$sp->runSql("select sum(totle) from {$prefix}orders where DATE_FORMAT(addtime,'%Y-%m-%d')='$today' && zt2=2 ".$farr);
			$totle1=mysql_fetch_row($re1);
			$this->zong1=$totle1[0];
			$re2=$sp->runSql("select sum(totle) from {$prefix}orders where DATE_FORMAT(addtime,'%Y-%m-%d')='$today' && zt1=1 ".$farr);
			$totle2=mysql_fetch_row($re2);
			$this->zong2=$totle2[0];
			//昨日统计
			$this->y1=$sp->findCount(" DATE_FORMAT(addtime,'%Y-%m-%d')='$yes' && zt1=1 ".$farr." ");
			$this->y2=$sp->findCount(" DATE_FORMAT(addtime,'%Y-%m-%d')='$yes' && zt2=2 && zt1=2 ".$farr." ");
			$this->y3=$sp->findCount(" DATE_FORMAT(addtime,'%Y-%m-%d')='$yes' ".$farr." ");
			$re3=$sp->runSql("select sum(totle) from {$prefix}orders where DATE_FORMAT(addtime,'%Y-%m-%d')='$yes' && zt2=2 ".$farr);
			$totle3=mysql_fetch_row($re3);
			$this->zong3=$totle3[0];
			$re4=$sp->runSql("select sum(totle) from {$prefix}orders where DATE_FORMAT(addtime,'%Y-%m-%d')='$yes' && zt1=1 ".$farr);
			$totle4=mysql_fetch_row($re4);
			$this->zong4=$totle4[0];
			//本月成交额概览
			$basedir = dirname(__FILE__); 
			$basedir=str_replace("controller","",$basedir);
			$year=date("Y");
			$month=date("m");
			$days=$this->getDaysofMonth($year,$month);//获取本月天数
			//获取每天销售额
			$j=0;
			for ($i=1;$i<=$days;$i++) {
				if ($i=="1") {
				$yuefen="\"1\"";
				} else {
				$yuefen=$yuefen.","."\"$i\"";
				}
				if (strlen($i)=="1") {$i="0".$i;}
				$dates=$year."-".$month."-".$i;
				$newarr=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='2' ".$farr." ";
				$no1=$sp->findAll($newarr);
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
			$title="本月(".$year."年".$month."月)成交额概览-总成交额:".$j."元-(X轴:日  Y轴:成交额)";
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
			$this->showz="<div id=\"my_chart\" style=\"height:600px; color: #C10C02 border:1px solid #CCC\"></div><script>swfobject.embedSWF(\"include/open-flash-chart.swf\", \"my_chart\", \"100%\", \"300\", \"9.0.0\",\"expressInstall.swf\",{\"data-file\":\"include/old.txt?r=".rand(0,99999)."\"});</script></div>";
        $this->display("admin/main.html");
    }
    public function center()
    {
        $this->display("admin/index.html");
    }
	//获取IP
	function egetip(){
		if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')) 
		{
			$ip=getenv('HTTP_CLIENT_IP');
		} 
		elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown'))
		{
			$ip=getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown'))
		{
			$ip=getenv('REMOTE_ADDR');
		}
		elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown'))
		{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return preg_replace("/^([\d\.]+).*/", "\\1",$ip);
	}
	//显示验证码
	function _vcode() { 
	$vcode = spClass('spVerifyCode');
	$vcode->display();
	}	
	//登录
	function login() { 
	$userObj = spClass("adminuser");
	$username = $this->spArgs("username");
    $password = md5($this->spArgs("password"));
 	$vcode = spClass('spVerifyCode');
	$mymac=$this->spArgs("mymac");
	//判断是否开启mac绑定
	$ma=spClass("mac");
	$mac=$ma->findAll();
	$mac_open=$mac[0][is_open];
	$mac_addr=$mac[0][macaddr];
	if ($mac_open=="yes") {
		if (strpos($mac_addr, $mymac)===false) {
				$this->success("系统开启了mac绑定，请使用绑定的客户端登陆，并使用IE或IE内核浏览器！", spUrl("admin", "index"));
		} 
	}
        if($vcode->verify($this->spArgs('checkcode'))) {
				if( false == $userObj->userlogin($username, $password) ){
					// 登录失败，提示后跳转回登录页面
					$this->error("用户名/密码错误，请重新输入！", spUrl("admin","index"));
				}else{
					$useracl = spClass("spAcl")->get(); // 通过acl的get可以获取到当前用户的角色标识
					$area = spClass('spIpArea')->get($this->egetip());
					$logtime=date("Y-m-d H:i:s");
					$updateadmin=spClass("adminuser");
					$old=$updateadmin->find(array('username'=>$username));
					$updateadmin->update(array('username'=>$username),array('logtime'=>$logtime,'logip'=>$this->egetip(),'logarea'=>$area));
					//写入登录日志
					$loghis=spClass("loginlog");
					$loghis->create(array('username'=>$username,'logtime'=>$logtime,'logip'=>$this->egetip(),'logarea'=>$area));
					$this->success("登录成功，欢迎您，管理员：".$username."！ \\n 上次登录时间是：".$old[logtime]." \\n 上次登录IP：".$old[logip]." \\n 上次登录位置：".$old[logarea], spUrl("admin","center"));
				}
        }else{
		$this->error("验证码输入错误！",spUrl("admin","index"));
        }	
	}
//计算某月的天数
	function getDaysofMonth($year, $month) {
		if ($year < 1970 || $month < 1 || $month > 12) {
			return false;
		}
		$days = date("d", mktime(0, 0, 0, $month+1, 1-1, $year));
		return $days;
	}
	//管理员列表
	function adminlist() { 
	include('controller/search.php');
	$adminlist=spClass("adminuser");
	$this->results=$adminlist->spPager($this->spArgs('page',1),15)->findAll(null,"id desc",null,null);
	$this->pager=$adminlist->spPager()->getPager();
	$this->display("admin/adminlist.html");
	}
	//添加管理员_页面
	function addadmin() { 
	include('controller/search.php');
	$this->display("admin/addadmin.html");
	}
	//编辑管理员_页面
	function editadmin() {
	include('controller/search.php');
	$editadmin = spClass("adminuser");
	if ($id = $this->spArgs("id")) {
		$this->admininfo = $editadmin->find(array('id' => $id));
	} else {
		$this->jump(spUrl("admin", "adminlist"));
	}
	$this->display("admin/adminedit.html");
	}
	//编辑管理员
	function editadminaction() {
	include('controller/search.php');
	$editadmin=spClass("adminuser");
	$conditions = array('id' => $this->spArgs('id'));
	$newpass=md5($this->spArgs('password'));
	$newrow = array(
		'password' => $newpass,
	);
	$editadmin->update($conditions,$newrow);
		$sp=spClass("adminuser");
		$temp=$sp->find(array('id'=>$this->spArgs('id')));
		$this->logs("编辑管理员：".$temp[username]);
	$this->success("修改成功！", spUrl("admin", "adminlist"));
	}
	//登出
    public function logout(){ 
        header('Content-Type: text/html; charset=utf-8');
        $_SESSION = array();
        if (isset($_COOKIE[session_name()]))
        {
            setcookie(session_name(), '', time() - 420000, '/');
        }
		spClass('spAcl')->set("");
        session_destroy();
        echo "<script language='javascript'>parent.window.location.href='index.php?c=admin&a=index';</script>";
    }   
	//支付宝_页面
	function alipay() {
	include('controller/search.php');
	$alipay = spClass("interfaces");
	$this->alipayinfo = $alipay->find(array('id' => "1"));
	$this->display("admin/alipay.html");
	}
	//支付宝_页面编辑
	function alipaysave() {
	include('controller/search.php');
	$alipay = spClass("interfaces");
	$conditions = array('id' => $this->spArgs('id'));
	$newrow = array(
		'zhanghao' => $this->spArgs('zhanghao'),
		'jianyanma' => $this->spArgs('jianyanma'),
		'hezuoid' => $this->spArgs('hezuoid'),
		'mingcheng' => $this->spArgs('mingcheng'),
		'urladdress' => $this->spArgs('urladdress')
	);
	$alipay->update($conditions,$newrow);
	$content=file_get_contents("pay/alipay/alipay.config.php");//修改支付宝配置文件(即时到帐)
	$t=preg_replace("/partner']='.*?'/i","partner']='".$this->spArgs('hezuoid')."'",$content,1);
	$t=preg_replace("/key']='.*?'/i","key']='".$this->spArgs('jianyanma')."'",$t,1);
	$t=preg_replace("/seller_email']='.*?'/i","seller_email']='".$this->spArgs('zhanghao')."'",$t,1);
	$t=preg_replace("/(?<=notify_url']=').*?(?=')/i",$this->spArgs('urladdress')."/pay/alipay/notify_url.php",$t,1);
	$t=preg_replace("/return_url']='.*?'/i","return_url']='".$this->spArgs('urladdress')."/pay/alipay/return_url.php'",$t,1);
	$fo=fopen('pay/alipay/alipay.config.php',"w"); 
	fwrite($fo,$t);
	fclose($fo);
	$contentdb=file_get_contents("pay/alipaydb/alipay.config.php");//修改支付宝配置文件(担保交易)
	$tdb=preg_replace("/partner']='.*?'/i","partner']='".$this->spArgs('hezuoid')."'",$contentdb,1);
	$tdb=preg_replace("/key']='.*?'/i","key']='".$this->spArgs('jianyanma')."'",$tdb,1);
	$tdb=preg_replace("/seller_email']='.*?'/i","seller_email']='".$this->spArgs('zhanghao')."'",$tdb,1);
	$tdb=preg_replace("/notify_url']='.*?'/i","notify_url']='".$this->spArgs('urladdress')."/pay/alipaydb/notify_url.php'",$tdb,1);
	$tdb=preg_replace("/return_url']='.*?'/i","return_url']='".$this->spArgs('urladdress')."/pay/alipaydb/return_url.php'",$tdb,1);
	$fo=fopen('pay/alipaydb/alipay.config.php',"w"); 
	fwrite($fo,$tdb);
	fclose($fo);
	$contenttwo=file_get_contents("pay/alipaytwo/alipay.config.php");//修改支付宝配置文件(双接口)
	$ttwo=preg_replace("/partner']='.*?'/i","partner']='".$this->spArgs('hezuoid')."'",$contenttwo,1);
	$ttwo=preg_replace("/key']='.*?'/i","key']='".$this->spArgs('jianyanma')."'",$ttwo,1);
	$ttwo=preg_replace("/seller_email']='.*?'/i","seller_email']='".$this->spArgs('zhanghao')."'",$ttwo,1);
	$ttwo=preg_replace("/(?<=notify_url']=').*?(?=')/i",$this->spArgs('urladdress')."/pay/alipaytwo/notify_url.php",$ttwo,1);
	$ttwo=preg_replace("/return_url']='.*?'/i","return_url']='".$this->spArgs('urladdress')."/pay/alipaytwo/return_url.php'",$ttwo,1);
	$fo=fopen('pay/alipaytwo/alipay.config.php',"w"); 
	fwrite($fo,$ttwo);
	fclose($fo);
	$this->success("支付宝接口保存成功！", spUrl("admin", "alipay"));
	}
	//网银在线_页面
	function chinabank() {
	include('controller/search.php');
	$cb = spClass("interfaces");
	$this->cbinfo = $cb->find(array('id' => "2"));
	$this->display("admin/chinabank.html");
	}
	//网银在线_页面编辑
	function cbsave() {
	include('controller/search.php');
	$basedir = dirname(__FILE__); 
	$basedir=str_replace("controller","",$basedir);
	$cb = spClass("interfaces");
	$conditions = array('id' => $this->spArgs('id'));
	$newrow = array(
		'zhanghao' => $this->spArgs('zhanghao'),
		'jianyanma' => $this->spArgs('jianyanma'),
		'urladdress' => $this->spArgs('urladdress')
	);
	$cb->update($conditions,$newrow);
	$content=file_get_contents("pay/chinabank/Send.php");//修改网银在线配置文件
	$t=preg_replace("/v_mid='.*?'/i","v_mid='".$this->spArgs('zhanghao')."'",$content,1);
	$t=preg_replace("/v_url='.*?'/i","v_url='".$this->spArgs('urladdress')."/pay/chinabank/Receive.php'",$t,1);
	$t=preg_replace("/key='.*?'/i","key='".$this->spArgs('jianyanma')."'",$t,1);
	$fo=fopen('pay/chinabank/Send.php',"w"); 
	fwrite($fo,$t);
	fclose($fo);
	$content2=file_get_contents("pay/chinabank/Receive.php");//修改网银在线配置文件
	$t2=preg_replace("/key='.*?'/i","key='".$this->spArgs('jianyanma')."'",$content2,1);
	$fo=fopen('pay/chinabank/Receive.php',"w"); 
	fwrite($fo,$t2);
	fclose($fo);
	$this->success("网银在线接口保存成功！", spUrl("admin", "chinabank"));
	}
	//财付通_页面
	function tenpay() {
	include('controller/search.php');
	$tp = spClass("interfaces");
	$this->cbinfo = $tp->find(array('id' => "3"));
	$this->display("admin/tenpay.html");
	}
	//财付通_页面编辑
	function tpsave() {
	include('controller/search.php');
	$basedir = dirname(__FILE__); 
	$basedir=str_replace("controller","",$basedir);
	$cb = spClass("interfaces");
	$conditions = array('id' => $this->spArgs('id'));
	$newrow = array(
		'zhanghao' => $this->spArgs('zhanghao'),
		'jianyanma' => $this->spArgs('jianyanma'),
		'urladdress' => $this->spArgs('urladdress')
	);
	$cb->update($conditions,$newrow);
	$content=file_get_contents("pay/stenpay/tenpay_config.php");//修改财付通配置文件，即时到帐
	$t=preg_replace("/partner=\".*?\"/i","partner=\"".$this->spArgs('zhanghao')."\"",$content,1);
	$t=preg_replace("/return_url=\".*?\"/i","return_url=\"".$this->spArgs('urladdress')."/pay/stenpay/payReturnUrl.php\"",$t,1);
	$t=preg_replace("/notify_url=\".*?\"/i","notify_url=\"".$this->spArgs('urladdress')."/pay/stenpay/payNotifyUrl.php\"",$t,1);
	$t=preg_replace("/key=\".*?\"/i","key=\"".$this->spArgs('jianyanma')."\"",$t,1);
	$fo=fopen('pay/stenpay/tenpay_config.php',"w"); 
	fwrite($fo,$t);
	fclose($fo);
	$this->success("财付通即时到帐/担保交易双接口保存成功！", spUrl("admin", "tenpay"));
	}
	//易宝_页面
	function yeepay() {
	include('controller/search.php');
	$tp = spClass("interfaces");
	$this->cbinfo = $tp->find(array('id' => "4"));
	$this->display("admin/yeepay.html");
	}
	//易宝_页面编辑
	function ypsave() {
	include('controller/search.php');
	$cb = spClass("interfaces");
	$conditions = array('id' => $this->spArgs('id'));
	$newrow = array(
		'zhanghao' => $this->spArgs('zhanghao'),
		'jianyanma' => $this->spArgs('jianyanma'),
		'urladdress' => $this->spArgs('urladdress')
	);
	$cb->update($conditions,$newrow);
	$content=file_get_contents("pay/yeepay/merchantProperties.php");//修改易宝配置文件
	$t=preg_replace("/p1_MerId=\".*?\"/i","p1_MerId=\"".$this->spArgs('zhanghao')."\"",$content,1);
	$t=preg_replace("/merchantKey=\".*?\"/i","merchantKey=\"".$this->spArgs('jianyanma')."\"",$t,1);
	$fo=fopen('pay/yeepay/merchantProperties.php',"w"); 
	fwrite($fo,$t);
	fclose($fo);
	$this->success("易宝接口保存成功！", spUrl("admin", "yeepay"));
	}
	//Paypal_页面
	function paypal() {
	include('controller/search.php');
	$tp = spClass("interfaces");
	$this->cbinfo = $tp->find(array('types' => "9"));
	$this->display("admin/paypal.html");
	}
	//Paypal_页面编辑
	function paypalsave() {
	include('controller/search.php');
	$cb = spClass("interfaces");
	$conditions = array('id' => $this->spArgs('id'));
	$newrow = array(
		'zhanghao' => $this->spArgs('zhanghao'),
	);
	$tworow = array(
		'types' => "9",
		'zhanghao' => $this->spArgs('zhanghao')
	);
	if ($this->spArgs('id')!="") {
	$cb->update($conditions,$newrow);
	} else {
	$cb->create($tworow);
	}
	$content=file_get_contents("pay/paypal/include/paypal.config.inc.php");
	$t=preg_replace("/paypal_business='.*?'/i","paypal_business='".$this->spArgs('zhanghao')."'",$content,1);
	$fo=fopen('pay/paypal/include/paypal.config.inc.php',"w"); 
	fwrite($fo,$t);
	fclose($fo);
	$this->success("Paypal接口保存成功！", spUrl("admin", "paypal"));
	}
	//银行帐号_页面
	function bankpay() {
	include('controller/search.php');
	$banklist = spClass("banks");
	$this->results=$banklist->spPager($this->spArgs('page',1),15)->findAll(null,"id desc",null,null);
	$this->pager=$banklist->spPager()->getPager();
	$this->display("admin/bankpay.html");
	}
	//银行帐号_添加
	function banksave() { 
	include('controller/search.php');
	$banksave=spClass("banks");
	$newrow=array(
		'bankname' => $this->spArgs('bankname'),
		'banknum' => $this->spArgs('banknum'),
		'realname' => $this->spArgs('realname')
	);
	$banksave->create($newrow);
	$this->logs("添加银行帐号：".$this->spArgs('bankname'));
	$this->success("添加成功！", spUrl("admin", "bankpay"));
	}
	//银行帐号_删除
	function delbank() {
	include('controller/search.php');
	$delbank=spClass("banks");
	if ($id = $this->spArgs('id')) {
		$delbank->deleteByPk($id);
		$this->logs("删除银行帐号");
		$this->jump(spUrl("admin", "bankpay",array('page'=>$this->spArgs('page'))));
	} else {
		$this->error("参数错误！", spUrl("admin", "bankpay"));
	}
	}
	//显示编辑银行页面
	function showeditbank() {
	include('controller/search.php');
	$ebank=spClass("banks");
	if ($id = $this->spArgs("id")) {
		$this->mybank = $ebank->find(array('id' => $id));
	} else {
		$this->jump(spUrl("admin", "bankpay"));
	}
	$this->display("admin/editbank.html");
	}
	//删除缓存
	function clearcache() {
	$basedir = dirname(__FILE__); 
	$basedir=str_replace("controller","",$basedir);
	$basedir=$basedir."tmp/";
	$dir= "tmp"; 
	$handle=opendir($dir); 
	while (($file=readdir($handle))!="") {
		if ($file!="." && $file!="..") {
			if (unlink($basedir.$file)) {
			} else {
			echo "删除失败，请检查tmp目录权限";
			exit;
			}
		}	
	} 
	closedir($handle); 
	$this->logs("清理缓存");
	echo "<script>alert('缓存清理成功！');history.back(-1);</script>";
	}
	//产品组_页面
	function group() {
	include('controller/search.php');
	$banklist = spClass("groups");
	$this->results=$banklist->spPager($this->spArgs('page',1),15)->findAll(null,"id desc",null,null);
	$this->pager=$banklist->spPager()->getPager();
	$this->display("admin/group.html");
	}
	//产品组_添加
	function groupsave() {
	include('controller/search.php');
	$banksave=spClass("groups");
	$newrow=array(
		'gname' => $this->spArgs('gname'),
		'zhekou' => $this->spArgs('zhekou'),
		'is_form' => '0',
		'ticheng' => $this->spArgs('ticheng')
	);
	$banksave->create($newrow);
	$this->logs("添加产品组");
	$this->success("添加成功！", spUrl("admin", "group"));
	}
	//产品组_删除
	function delgroup() {
	include('controller/search.php');
	$delbank=spClass("groups");
	if ($id = $this->spArgs('id')) {
		$delbank->deleteByPk($id);
			$delp=spClass("products");
			$c=array('gid'=>$id);
			$delp->delete($c);
			$this->logs("删除产品组");
		$this->jump(spUrl("admin", "group",array('page'=>$this->spArgs('page'))));
	} else {
		$this->error("参数错误！", spUrl("admin", "group"));
	}
	}
	//显示编辑产品组页面
	function showeditgroup() {
	include('controller/search.php');
	$ebank=spClass("groups");
	if ($id = $this->spArgs("id")) {
		$this->mybank = $ebank->find(array('id' => $id));
	} else {
		$this->jump(spUrl("admin", "group"));
	}
	$this->display("admin/editgroup.html");
	}
	//产品_页面
	function product() {
	include('controller/search.php');
	$banklist = spClass("products");
	$this->results=$banklist->spPager($this->spArgs('page',1),15)->findAll(null,"gid desc",null,null);
	$this->pager=$banklist->spPager()->getPager();
		$gp=spClass("groups");
		$this->mygp=$gp->findAll(null,"id desc",null,null);
		$tp=$this->results;
		foreach ($tp as $key=>$v) {
		$con=array('id'=>$v['gid']);
		$re=$gp->find($con);
		array_push($tp[$key],$re['gname']);
		}
		$this->results=$tp;
	$this->display("admin/product.html");
	}
	//产品_添加
	function productsave() {
	include('controller/search.php');
	$ap=spClass("products");
	$newrow=array(
		'pname' => $this->spArgs('pname'),
		'price' => $this->spArgs('price'),
		'gid' => $this->spArgs('groups'),
	);
	$ap->create($newrow);
	$this->logs("添加产品");
	$this->success("添加成功！", spUrl("admin", "product"));
	}
	//产品_删除
	function delproduct() {
	include('controller/search.php');
	$delbank=spClass("products");
	if ($id = $this->spArgs('id')) {
		$delbank->deleteByPk($id);
		$this->logs("删除产品");
		$this->jump(spUrl("admin", "product",array('page'=>$this->spArgs('page'))));
	} else {
		$this->error("参数错误！", spUrl("admin", "product"));
	}
	}
	//显示编辑产品页面
	function showeditproduct() {
	include('controller/search.php');
	$ebank=spClass("products");
	if ($id = $this->spArgs("id")) {
		$this->mybank = $ebank->find(array('id' => $id));
		$temp=$this->mybank;
		$gp=spClass("groups");
		$this->mygp=$gp->findAll(null,"id desc",null,null);
		$con=array('id'=>$this->mybank['gid']);
		$re=$gp->find($con);
		array_push($temp,$re['gname']);
		$this->mybank=$temp;
	} else {
		$this->jump(spUrl("admin", "product"));
	}
	$this->display("admin/editproduct.html");
	}
	//表单项_页面
	function setform() {
	include('controller/search.php');
	$sf = spClass("forms");
	if ($id = $this->spArgs("id")) {
		$this->editoption = array('id' => $id);
	} else {
		$this->jump(spUrl("admin", "group"));
	}
	$this->display("admin/formoption.html");
	}
	//表单项_修改
	function updateform() {
	include('controller/search.php');
	$sf = spClass("forms");
	if ($id = $this->spArgs("id")) {
		$this->editoption = array('id' => $id);
		$this->old1 = $sf->find(array('gid' => $id,'fname' => '产品列表'));
		$this->old2 = $sf->find(array('gid' => $id,'fname' => '订购数量'));
		$this->old3 = $sf->find(array('gid' => $id,'fname' => '收货人姓名'));
		$this->old4 = $sf->find(array('gid' => $id,'fname' => '收货地址'));
		$this->old5 = $sf->find(array('gid' => $id,'fname' => '手机'));
		$this->old6 = $sf->find(array('gid' => $id,'fname' => '订单备注'));
		$this->old7 = $sf->findAll(array('gid' => $id,'olds' => '1'));
		$t1=$this->old1;
		$p = explode(",",$t1[payment]);
		$this->z1=in_array("1",$p)?"1":"0";
		$this->z2=in_array("2",$p)?"1":"0";
		$this->z3=in_array("3",$p)?"1":"0";
		$this->z4=in_array("4",$p)?"1":"0";
		$this->z5=in_array("5",$p)?"1":"0";
		$this->z6=in_array("6",$p)?"1":"0";
		$this->z7=in_array("7",$p)?"1":"0";
		$this->z8=in_array("8",$p)?"1":"0";
		$this->z9=in_array("9",$p)?"1":"0";
		$this->z10=in_array("10",$p)?"1":"0";
	} else {
		$this->jump(spUrl("admin", "group"));
	}
	$this->display("admin/formupdate.html");
	}
	//保存表单项
	function saveform() {
	include('controller/search.php');
	$ap=spClass("forms");
			//保存固定项
	$gid=$this->spArgs('id');
	$temp=$this->spArgs('checkbox4');
	$tmp=array_filter($this->spArgs('payment'));
	$payment=implode(",",$tmp);
	$oldrow1=array(
		'gid' => $gid,
		'fname' => '产品列表',
		'musts' => '1',
		'types' => '99',
		'olds' => '0',
		'elseoption1' => $this->spArgs('elseoption1'),
		'elseoption2' => '99',
		'paixu' => $this->spArgs('o1'),
		'payment' => $payment,
		'paytype' => $this->spArgs('paytype'),
		'yzm' => $this->spArgs('yzm')
	);
	$ap->create($oldrow1);
	$oldrow2=array(
		'gid' => $gid,
		'fname' => '订购数量',
		'musts' => '1',
		'types' => '1',
		'olds' => '0',
		'elseoption1' => '99',
		'elseoption2' => $this->spArgs('elseoption2'),
		'paixu' => $this->spArgs('o2'),
		'payment' => $payment,
		'paytype' => $this->spArgs('paytype'),
		'yzm' => $this->spArgs('yzm')
	);
	$ap->create($oldrow2);
	$oldrow3=array(
		'gid' => $gid,
		'fname' => '收货人姓名',
		'musts' => '1',
		'types' => '0',
		'olds' => '0',
		'elseoption1' => '99',
		'elseoption2' => '99',
		'paixu' => $this->spArgs('o3'),
		'payment' => $payment,
		'paytype' => $this->spArgs('paytype'),
		'yzm' => $this->spArgs('yzm')
	);
	$ap->create($oldrow3);
	$oldrow4=array(
		'gid' => $gid,
		'fname' => '收货地址',
		'musts' => '1',
		'types' => '0',
		'olds' => '0',
		'elseoption1' => '99',
		'elseoption2' => '99',
		'paixu' => $this->spArgs('o4'),
		'payment' => $payment,
		'paytype' => $this->spArgs('paytype'),
		'yzm' => $this->spArgs('yzm')
	);
	$ap->create($oldrow4);
	$oldrow5=array(
		'gid' => $gid,
		'fname' => '手机',
		'musts' => '1',
		'types' => '2',
		'olds' => '0',
		'elseoption1' => '99',
		'elseoption2' => '99',
		'paixu' => $this->spArgs('o5'),
		'payment' => $payment,
		'paytype' => $this->spArgs('paytype'),
		'yzm' => $this->spArgs('yzm')
	);
	$ap->create($oldrow5);
	$oldrow6=array(
		'gid' => $gid,
		'fname' => '订单备注',
		'musts' => $temp,
		'types' => '99',
		'olds' => '0',
		'elseoption1' => '99',
		'elseoption2' => '99',
		'paixu' => $this->spArgs('o6'),
		'payment' => $payment,
		'paytype' => $this->spArgs('paytype'),
		'yzm' => $this->spArgs('yzm')
	);
	$ap->create($oldrow6);
		//自定义项
	$useroption=$this->spArgs('useroption');
	$usercheck=$this->spArgs('usercheck');
	$userselect=$this->spArgs('userselect');
	$usernum=$this->spArgs('usernum');
	$i=0;
	if ($useroption) {
	foreach ($useroption as $key=>$v) {
		if ($v!="") {
			$newarr=array(
				'gid' => $gid,
				'fname' => $v,
				'musts' => $usercheck[$i],
				'types' => $userselect[$i],
				'olds' => '1',
				'elseoption1' => '99',
				'elseoption2' => '99',
				'paixu' => $usernum[$i],
				'payment' => $payment,
				'paytype' => $this->spArgs('paytype'),
				'yzm' => $this->spArgs('yzm')
			);
		$ap->create($newarr);
		}
	$i++;
	} 
	}
	$cb = spClass("groups");
	$conditions = array('id' => $gid);
	$newrow = array(
		'is_form' => "1"
	);
	$cb->update($conditions,$newrow);
	$this->logs("保存表单项");
	$this->success("表单项设置成功！", spUrl("admin", "group"));
	}
//更新表单项
	function updatemyform() {
	include('controller/search.php');
		$ap=spClass("forms");
		$gid=$this->spArgs('id');
		$tmp=array_filter($this->spArgs('payment'));
		$payment=implode(",",$tmp);
		//固定项修改
		$ap->update(array('gid' => $gid,'fname' => '产品列表'),array('elseoption1' => $this->spArgs('elseoption1'),'paixu' => $this->spArgs('o1'),'payment' => $payment,		'paytype' => $this->spArgs('paytype'),'yzm' => $this->spArgs('yzm')));
		$ap->update(array('gid' => $gid,'fname' => '订购数量'),array('elseoption2' => $this->spArgs('elseoption2'),'paixu' => $this->spArgs('o2'),'payment' => $payment,'paytype' => $this->spArgs('paytype'),'yzm' => $this->spArgs('yzm')));
		$ap->update(array('gid' => $gid,'fname' => '收货人姓名'),array('paixu' => $this->spArgs('o3'),'payment' => $payment,'paytype' => $this->spArgs('paytype'),'yzm' => $this->spArgs('yzm')));
		$ap->update(array('gid' => $gid,'fname' => '收货地址'),array('paixu' => $this->spArgs('o4'),'payment' => $payment,'paytype' => $this->spArgs('paytype'),'yzm' => $this->spArgs('yzm')));
		$ap->update(array('gid' => $gid,'fname' => '手机'),array('paixu' => $this->spArgs('o5'),'payment' => $payment,'paytype' => $this->spArgs('paytype'),'yzm' => $this->spArgs('yzm')));
		$ap->update(array('gid' => $gid,'fname' => '订单备注'),array('musts' => $this->spArgs('checkbox4'),'paixu' => $this->spArgs('o6'),'payment' => $payment,'paytype' => $this->spArgs('paytype'),'yzm' => $this->spArgs('yzm')));
		//原有自定义项修改
		$cid=$this->spArgs('cid');
		$useroptionold=$this->spArgs('useroptionold');
		$usercheckold=$this->spArgs('usercheckold');
		$userselectold=$this->spArgs('userselectold');
		$usernumold=$this->spArgs('usernumold');
		if ($useroptionold) {
			$i=0;
			foreach ($useroptionold as $key=>$v) {
				if ($v!="") {
					$oldarr=array(
						'fname' => $v,
						'musts' => $usercheckold[$i],
						'types' => $userselectold[$i],
						'paixu' => $usernumold[$i],
						'payment' => $payment,
						'paytype' => $this->spArgs('paytype'),
						'yzm' => $this->spArgs('yzm')
					);
					$ap->update(array('id' => $cid[$i]),$oldarr);
				}
				$i++;
			}
		}
		//新增自定义项目保存
		$useroption=$this->spArgs('useroption');
		$usercheck=$this->spArgs('usercheck');
		$userselect=$this->spArgs('userselect');
		$usernum=$this->spArgs('usernum');
		if ($useroption) {
			$j=0;
			foreach ($useroption as $key=>$v) {
				if ($v!="") {
					$newarr=array(
						'gid' => $gid,
						'fname' => $v,
						'musts' => $usercheck[$j],
						'types' => $userselect[$j],
						'olds' => '1',
						'elseoption1' => '99',
						'elseoption2' => '99',
						'paixu' => $usernum[$j],
						'payment' => $payment,
						'paytype' => $this->spArgs('paytype'),
						'yzm' => $this->spArgs('yzm')
					);
					$ap->create($newarr);
				}
			}
		}
	$this->logs("修改产品组表单项");
		$this->success("表单项修改成功！", spUrl("admin", "group"));
	}
//邮箱提醒_显示页面
	function email() {
	include('controller/search.php');
	$sp=spClass("email");
	$this->email=$sp->find();
	$this->display("admin/email.html");
	}
//邮箱提醒_开启关闭
	function savealert() {
	include('controller/search.php');
	$sp=spClass("email");
	$sp->update(NULL,array('is_open'=>$this->spArgs('select20')));
	$this->logs("邮件提醒开关设置");
	$this->success("邮件提醒开启/关闭成功！", spUrl("admin", "email"));
	}		
//邮箱提醒_保存
	function saveemail() {
	include('controller/search.php');
	ob_start();
	$sp=spClass("email");
	$newarr=array(
		'sendaddr' => $this->spArgs('sendaddr'),
		'smtpaddr' => $this->spArgs('smtpaddr'),
		'username' => $this->spArgs('username'),
		'userpass' => $this->spArgs('userpass'),
		'getaddr' => $this->spArgs('getaddr'),
		'title' => $this->spArgs('title')
	);
	$sp->update(NULL,$newarr);
	$sa=explode("@",$this->spArgs('sendaddr'));
	$basedir = dirname(__FILE__); 
	$basedir=str_replace("controller","",$basedir);
	$content=file_get_contents("SpeedPHP/spConfig.php");//修改配置文件
	$t=preg_replace("/host_name'=>'.*?'/i","host_name'=>'".$sa[1]."'",$content,1);
	$t=preg_replace("/smtp_host'=>'.*?'/i","smtp_host'=>'".$this->spArgs('smtpaddr')."'",$t,1);
	$t=preg_replace("/from'=>'.*?'/i","from'=>'".$this->spArgs('sendaddr')."'",$t,1);
	$t=preg_replace("/user'=>'.*?'/i","user'=>'".$this->spArgs('username')."'",$t,1);
	$t=preg_replace("/pass'=>'.*?'/i","pass'=>'".$this->spArgs('userpass')."'",$t,1);
	$fo=fopen('SpeedPHP/spConfig.php',"w"); 
	fwrite($fo,$t);
	fclose($fo);
	$this->logs("邮件提醒设置");
	$this->success("邮件提醒设置保存成功！",spUrl("admin","email"));
	}	
//订单列表
	function orderlist() {
	include('controller/search.php');
		//组合搜索
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
				$myarr[]=$key."="."'".$v."'";
				}
			}
		}
		}
		//获取用户权限
		$users=spClass("adminuser");
		$qx=$users->find(array('username'=>$_SESSION[admin_username][username]));
		$qx=explode("|",$qx[qx]);
			//组合产品组ID
			$temp=explode(",",$qx[1]);
			$nums=count($temp);
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
				$farr="(".$farr.")";
			} else {
			$farr=$temp[1];
			}
		if ($qx[0]=="1,2,3,4,5,6,7") {
			if (empty($myarr)) {
			$newarr=$myarr;
			} else {
			$newarr=" ".implode("&&",$myarr)." ";
			}
		} else {
			if (empty($myarr)) {
				if ($farr=="") {
				$newarr=" (kefu="."'".$_SESSION[admin_username][username]."' || kefu is NULL) ";
				} else {
				$newarr=" (kefu="."'".$_SESSION[admin_username][username]."' || kefu is NULL) && ".$farr." ";
				}
			} else {
				if ($farr=="") {
				$newarr=" ".implode("&&",$myarr)." && (kefu="."'".$_SESSION[admin_username][username]."' || kefu is NULL) ";
				} else {
				$newarr=" ".implode("&&",$myarr)." && (kefu="."'".$_SESSION[admin_username][username]."' || kefu is NULL) && ".$farr." ";
				}
			}
		}
		$banklist = spClass("orders");
		$this->results=$banklist->spPager($this->spArgs('page',1),12)->findAll($newarr,"id desc",null,null);
		$this->pager=$banklist->spPager()->getPager();
		//获取产品组
		$progroups=spClass("groups");
		$this->proresults = $progroups->findAll();
		$this->display("admin/orderlist.html");
	}
//获取表单代码_显示页面
	function getform() {
	$temp001=explode("/index.php",$_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
	$mulu="http://" . $temp001[0];
	include('controller/search.php');
	$sp=spClass("forms");
	$this->forms=$sp->findAll(array('gid'=>$this->spArgs('id')),'paixu asc');
	//获取组名称并赋值
	$this->groupname=$this->spArgs('names');
	$gid=$this->spArgs('id');
	$temp=$this->forms;
	//获取银行帐号
	$zh=spClass("banks");
	$this->bank=$zh->findAll();
	$banks=$this->bank;
	foreach ($banks as $bs) {
	$zhanghao=$zhanghao."<li style='list-style:none'>".$bs[bankname]."&nbsp;&nbsp;帐号：".$bs[banknum]."&nbsp;&nbsp;收款人：".$bs[realname]."</li>";
	}
	$zhanghao="<ul>".$zhanghao."</ul>";
	$paytemp=explode(",",$temp[0][payment]);
	$b=0;
	foreach ($paytemp as $pay) {
		switch ($pay) {
		case "1":
		$payname="支付宝即时到帐";
		break;
		case "2":
		$payname="支付宝担保交易";
		break;
		case "3":
		$payname="银联在线支付";
		break;
		case "4":
		$payname="财付通在线支付";
		break;
		case "5":
		$payname="易宝在线支付";
		break;
		case "6":
		$payname="银行汇款";
		break;
		case "7":
		$payname="货到付款";
		break;
		case "8":
		$payname="上门取货";
		break;
		case "9":
		$payname="Paypal";
		case "10":
		$payname="支付宝双接口";
		}
		switch ($temp[0][paytype]) {
		case "0":
		$paylist1=$paylist1."<option value='".$pay."'>".$payname."</option>";
		break;
		case "1":
		if ($b=="0") {
		$temp99="checked='checked'";
		}
		$paylist1=$paylist1."<p><input type='radio' name='pay' ".$temp99." value='".$pay."' class='pro' /><label>".$payname."</label></p>";
		}
		$b++;
	}
	switch ($temp[0][paytype]) {
	case "0":
	$paylist="<select name='pay'>".$paylist1."</select>";
	break;
	case "1":
	$paylist=$paylist1;
	}
	$bp=spClass("products");
	$this->pro=$bp->findAll(array('gid'=>$gid),'id desc');
	$temppro=$this->pro;
//验证码
	if ($temp[0][yzm]=="2") {
	$yzm="<div class=\"things\"><span class=\"pimsleft\">验证码：</span><span class=\"pimsright\"><input id=\"text\" type=\"text\" name=\"text\" style=\"width:50px\" class='inputxt' datatype='*' nullmsg='请输入验证码！'><img id=\"yanz\" src=\"$mulu/include/yan.php\" onclick=\"refreshimg();\"/></span><div style=\"clear:both\"></div></div>";
	$yzm_js="
				<script language=\"javascript\" type=\"text/javascript\">
			var b;
			function refreshimg()
			{
			var im =document.getElementById(\"yanz\");
			im.src=\"$mulu/include/yan.php?\"+Math.random();
			}
			</script>
	";
	} else {
	$yzm="";
	$yzm_js="";
	}
	
echo <<<EOF
<link rel="stylesheet" href="$mulu/skin/admin/style/css.css" type="text/css" />
<script type="text/javascript" src="$mulu/skin/admin/js/jquery.js"></script>
<script type="text/javascript" src="$mulu/skin/admin/js/jquery.quickpaginate.js"></script>
<script type="text/javascript" src="$mulu/skin/admin/js/Validform.js"></script>
<script type="text/javascript">
$(function(){
	$(".registerform:last").Validform({
		tiptype:1,
		ajaxPost:false,
		callback:function(data){
			if(data.status=="y"){
				setTimeout(function(){
					$.Hidemsg();
				},2000);
			}
		}
	});
})
</script>
$yzm_js
<form method="post" action="$mulu/index.php?c=buy&a=saveorder" class="registerform" target="_blank">
<input name="gid" type="hidden" value="$gid" style="width:0px">
<input name="fromurl" id="fromurl" type="hidden" />
<input id="fromdomain" type="hidden" name="fromdomain" />
<div class="order">
EOF;
foreach ($temp as $v) {
	if ($v[fname]=="产品列表") {
		$k1="<select name='pname'>";
			foreach ($temppro as $va) {
				$k2=$k2."<option value='".$va[pname]."|".$va[price]."'>".$va[pname]."</option>";
			}
		$k11=$k1.$k2."</select>";
			$h=0;
			foreach ($temppro as $vb) {
				$cd=$h=="0"?" checked='checked'":"";
				$k3=$k3."<p><input type='radio' name='pname' value='".$vb[pname]."|".$vb[price]."'".$cd." class='pro'><label>".$vb[pname]."</label></p>";
				$h++;
			}
		switch ($v[elseoption1]) {
		case "0":
		$k=$k11;
		break;
		case "1":
		$k=$k3;
		}
	} elseif ($v[fname]=="订购数量") {
		switch ($v[elseoption2]) {
		case "0":
		$k="<input type='text' name='nums' class='inputxt' datatype='n' nullmsg='订购数量不能留空！' errormsg='订购数量必须为数字！'>";
		break;
		case "1":
		$k="<select name='nums'><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option></select>";
		}
	} elseif ($v[fname]=="收货人姓名") {
		$k="<input type='text' name='username' class='inputxt' datatype='*' nullmsg='收货人姓名不能留空！'>";
	} elseif ($v[fname]=="收货地址") {
		$k="<input type='text' name='address' width='35' style='width:300px' class='inputxt' datatype='*' nullmsg='收货地址不能留空！'>";
	} elseif ($v[fname]=="手机") {
		$k="<input type='text' name='mob' class='inputxt' datatype='m' nullmsg='手机不能留空！' errormsg='手机号码格式错误！'>";
	} elseif ($v[fname]=="订单备注") {
		if ($v[musts]=="0") {
		$k="<textarea name='beizhu' style='width:300px' rows='4'></textarea>";
		} else {
		$k="<textarea name='beizhu' style='width:300px' cols='35' rows='4' errormsg='请填写订单备注！'  datatype='*' altercss='gray' class='gray'></textarea>";
		}
	} else {
		switch ($v[types]) {
		case "99":
		$ftest="";
		break;
		case "0":
		$ftest=" class='inputxt' datatype='*'";
		break;
		case "1":
		$ftest=" class='inputxt' datatype='n'";
		break;
		case "2":
		$ftest=" class='inputxt' datatype='m'";
		break;
		case "3":
		$ftest=" class='inputxt' datatype='e'";
		break;
		case "4":
		$ftest=" class='inputxt' datatype='p'";
		break;
		case "5":
		$ftest=" class='inputxt' datatype='idnumber'";
		}
		switch ($v[musts]) {
		case "0":
		$k="<input type='text' name='".$v[fname]."'>";
		break;
		case "1":
		$k="<input type='text' name='".$v[fname]."'".$ftest.">";
		}
		$hiddenk="<input type='hidden' value='".$v[fname]."' style='width:0px'>";
	}
echo <<<EOF
		<div class="things">
			<span class="pimsleft">$hiddenk$v[fname]：</span>
			<span class="pimsright">
			$k
			</span>
			<div style="clear:both"></div>
		</div>
EOF;
}
echo <<<EOF
		<div class="things">
			<span class="pimsleft">支付方式：</span>
			<span class="pimsright">
			$paylist
			</span>
			<div style="clear:both"></div>
		</div>
		$yzm
		<div style="text-align:center;height:20px;padding-top:6px">
			<input type="submit" name="Submit" value="立即提交订单">
			<div style="clear:both"></div>
		</div>
</div>
</form>
<script>
var myurl=top.location.href;
document.getElementById("fromurl").value=myurl;
var ref = ''; 
 if (document.referrer.length > 0) { 
  ref = document.referrer; 
 } 
 try { 
  if (ref.length == 0 && opener.location.href.length > 0) { 
   ref = opener.location.href; 
  } 
 } catch (e) {}
document.getElementById("fromdomain").value=ref;
</script>
EOF;
$bodys=ob_get_contents();
		//获取模板并生成订单页
		$content=file_get_contents("include/order.html");
		$t=str_replace("<body>","<body>".$bodys,$content);
		$fo=fopen('order.html',"w"); 
		fwrite($fo,$t);
		fclose($fo);
		$this->success("订购页已生成，位于pims目录，文件名order.html！", spUrl("admin", "group"));
	}
}
?>