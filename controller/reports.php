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
class reports extends spController
{
//计算某月的天数
	function getDaysofMonth($year, $month) {
		if ($year < 1970 || $month < 1 || $month > 12) {
			return false;
		}
		$days = date("d", mktime(0, 0, 0, $month+1, 1-1, $year));
		return $days;
	}
//详细报表1
	function table1() {
		$order=spClass("orders");
		$year=$this->spArgs('SelTjYear');
		$month=$this->spArgs('SelTjMonth');
		$year1=$this->spArgs('SelTjYear1');
		$month1=$this->spArgs('SelTjMonth1');
		$year2=$this->spArgs('SelTjYear2');
		$month2=$this->spArgs('SelTjMonth2');
		//获取某月天数
		$days1=$this->getDaysofMonth($year,$month);
		$days2=$this->getDaysofMonth($year1,$month1);
		$days3=$this->getDaysofMonth($year2,$month2);
		$act=$this->spArgs('act');
		switch ($act) {
		case "1":
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		for ($i=1;$i<=$days1;$i++) {
			if (strlen($i)=="1") {$i="0".$i;}
			if (strlen($month)=="1") {$month="0".$month;}
			$dates=$year."-".$month."-".$i;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='2' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='1' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt1='2' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt1='1' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$i;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title=$year."年".$month."月销售报表(详表)";
		break;
		case "2":
		$gid=$this->spArgs('gp');
		$tgroup=spClass("groups");
		$tp=$tgroup->find(array('id'=>$gid));
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		for ($i=1;$i<=$days2;$i++) {
			if (strlen($i)=="1") {$i="0".$i;}
			if (strlen($month1)=="1") {$month1="0".$month1;}
			$dates=$year1."-".$month1."-".$i;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='2' && gid='$gid' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='1' && gid='$gid' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt1='2' && gid='$gid' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt1='1' && gid='$gid' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && gid='$gid' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$i;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title="产品组：".$tp[gname].$year1."年".$month1."月销售报表(详表)";
		break;
		case "3":
		$smallname=$this->spArgs('hidsel');	
		$gid=$this->spArgs('bigname');
		$tgroup=spClass("groups");
		$tp=$tgroup->find(array('id'=>$gid));
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		for ($i=1;$i<=$days3;$i++) {
			if (strlen($i)=="1") {$i="0".$i;}
			if (strlen($month2)=="1") {$month2="0".$month2;}
			$dates=$year2."-".$month2."-".$i;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='2' && gid='$gid' && pname='$smallname' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='1' && gid='$gid' && pname='$smallname' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt1='2' && gid='$gid' && pname='$smallname' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt1='1' && gid='$gid' && pname='$smallname' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && gid='$gid' && pname='$smallname' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$i;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title="产品组：".$tp[gname]."-产品：".$smallname.$year2."年".$month2."月销售报表(详表)";
		}
		$this->display("admin/table1.html");
	}
//月报表
	function table_month() {
	//公共代码
	$basedir = dirname(__FILE__); 
	$basedir=str_replace("controller","",$basedir);
	$order=spClass("orders");
	$gp=spClass("groups");
	$this->group=$gp->findAll();
	$year1=$this->spArgs('SelTjYear');
	$year2=$this->spArgs('SelTjYear1');
	$year3=$this->spArgs('SelTjYear2');
	//开始
	switch ($this->spArgs('act')) {
	case "1":
		$zong=0;//初始化总金额
		for ($i=1;$i<=12;$i++) {
			if ($i=="1") {$i="0".$i;}
			$dates=$year1."-".$i;
			$newarr=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='2' ";
			$no=$order->findAll($newarr);
			//得到每个月的总金额
			$mon_totle=0;
			foreach ($no as $v) {
			$mon_totle=$mon_totle+$v[price]*$v[nums];
			}
			//连接每个月金额为字符串
			if (isset($links)) {
			$links=$links.",".$mon_totle;
			} else {
			$links=$mon_totle;
			}
			$zong=$zong+$mon_totle;
			}
			//连接月份
			$monlinks="\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\"";
			$title=$year1."年销售报表-总金额：".$zong."元-(X轴:月  Y轴:成交额)";
			$bujin=round($zong/10);
			//写配置文件
			$content=file_get_contents("include/old.txt");
			$t=preg_replace("/text\":\".*?\"/i","text\":\"".$title."\"",$content,1);
			$t=preg_replace("/values\":\[.*?\]/i","values\":[".$links."]",$t,1);
			$t=preg_replace("/labels\":\[.*?\]/i","labels\":[".$monlinks."]",$t,1);
			$t=preg_replace("/max\":.*?,/i","max\":".$zong.",",$t,1);
			$t=preg_replace("/steps\":.*?,/i","steps\":".$bujin.",",$t,1);
			$fo=fopen("include/old.txt","w"); 
			fwrite($fo,$t);
			fclose($fo);
			$this->z1="<div id=\"my_chart\" style=\"height:600px; color: #C10C02 border:1px solid #CCC\"></div><script>swfobject.embedSWF(\"include/open-flash-chart.swf\", \"my_chart\", \"98%\", \"400\", \"9.0.0\",\"expressInstall.swf\",{\"data-file\":\"include/old.txt\"});</script></div>";
	break;
	case "2":
		$gid=$this->spArgs('gp');
		$zong=0;//初始化总金额
		for ($i=1;$i<=12;$i++) {
			if ($i=="1") {$i="0".$i;}
			$dates=$year2."-".$i;
			$newarr=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='2' && gid='$gid' ";
			$no=$order->findAll($newarr);
			//得到每个月的总金额
			$mon_totle=0;
			foreach ($no as $v) {
			$mon_totle=$mon_totle+$v[price]*$v[nums];
			}
			//连接每个月金额为字符串
			if (isset($links)) {
			$links=$links.",".$mon_totle;
			} else {
			$links=$mon_totle;
			}
			$zong=$zong+$mon_totle;
			}
			//连接月份
			$monlinks="\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\"";
			$title=$year2."年销售报表-总金额：".$zong."元-(X轴:月  Y轴:成交额)";
			$bujin=round($zong/10);
			//写配置文件
			$content=file_get_contents("include/old.txt");
			$t=preg_replace("/text\":\".*?\"/i","text\":\"".$title."\"",$content,1);
			$t=preg_replace("/values\":\[.*?\]/i","values\":[".$links."]",$t,1);
			$t=preg_replace("/labels\":\[.*?\]/i","labels\":[".$monlinks."]",$t,1);
			$t=preg_replace("/max\":.*?,/i","max\":".$zong.",",$t,1);
			$t=preg_replace("/steps\":.*?,/i","steps\":".$bujin.",",$t,1);
			$fo=fopen("include/old.txt","w"); 
			fwrite($fo,$t);
			fclose($fo);
			$this->z1="<div id=\"my_chart\" style=\"height:600px; color: #C10C02 border:1px solid #CCC\"></div><script>swfobject.embedSWF(\"include/open-flash-chart.swf\", \"my_chart\", \"98%\", \"400\", \"9.0.0\",\"expressInstall.swf\",{\"data-file\":\"include/old.txt\"});</script></div>";
	break;
	case "3":
		$smallname=$this->spArgs('hidsel');
		$zong=0;//初始化总金额
		for ($i=1;$i<=12;$i++) {
			if ($i=="1") {$i="0".$i;}
			$dates=$year3."-".$i;
			$newarr=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='2' && pname='$smallname' ";
			$no=$order->findAll($newarr);
			//得到每个月的总金额
			$mon_totle=0;
			foreach ($no as $v) {
			$mon_totle=$mon_totle+$v[price]*$v[nums];
			}
			//连接每个月金额为字符串
			if (isset($links)) {
			$links=$links.",".$mon_totle;
			} else {
			$links=$mon_totle;
			}
			$zong=$zong+$mon_totle;
			}
			//连接月份
			$monlinks="\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\"";
			$title=$year3."年销售报表-总金额：".$zong."元-(X轴:月  Y轴:成交额)";
			$bujin=round($zong/10);
			//写配置文件
			$content=file_get_contents("include/old.txt");
			$t=preg_replace("/text\":\".*?\"/i","text\":\"".$title."\"",$content,1);
			$t=preg_replace("/values\":\[.*?\]/i","values\":[".$links."]",$t,1);
			$t=preg_replace("/labels\":\[.*?\]/i","labels\":[".$monlinks."]",$t,1);
			$t=preg_replace("/max\":.*?,/i","max\":".$zong.",",$t,1);
			$t=preg_replace("/steps\":.*?,/i","steps\":".$bujin.",",$t,1);
			$fo=fopen("include/old.txt","w"); 
			fwrite($fo,$t);
			fclose($fo);
			$this->z1="<div id=\"my_chart\" style=\"height:600px; color: #C10C02 border:1px solid #CCC\"></div><script>swfobject.embedSWF(\"include/open-flash-chart.swf\", \"my_chart\", \"98%\", \"400\", \"9.0.0\",\"expressInstall.swf\",{\"data-file\":\"include/old.txt\"});</script></div>";
	}
	$this->display("admin/table_month.html");
	}
//月详细报表
	function table2() {
		$order=spClass("orders");
		$year=$this->spArgs('SelTjYear');
		$year1=$this->spArgs('SelTjYear1');
		$year2=$this->spArgs('SelTjYear2');
		$act=$this->spArgs('act');
		switch ($act) {
		case "1":
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		for ($i=1;$i<=12;$i++) {
			if (strlen($i)=="1") {$i="0".$i;}
			$dates=$year."-".$i;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='2' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='1' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt1='2' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt1='1' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y-%m')='$dates' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$i;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title=$year."年销售报表(详表)";
		break;
		case "2":
		$gid=$this->spArgs('gp');
		$groups=spClass('groups');
		$group=$groups->find(array('id'=>$gid));
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		for ($i=1;$i<=12;$i++) {
			if (strlen($i)=="1") {$i="0".$i;}
			$dates=$year1."-".$i;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='2' && gid='$gid' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='1' && gid='$gid' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt1='2' && gid='$gid' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt1='1' && gid='$gid' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && gid='$gid' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$i;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title="产品组：".$group[gname].$year1."年销售报表(详表)";
		break;
		case "3":
		$smallname=$this->spArgs('hidsel');	
		$gid=$this->spArgs('bigname');
		$tgroup=spClass("groups");
		$tp=$tgroup->find(array('id'=>$gid));
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		for ($i=1;$i<=12;$i++) {
			if (strlen($i)=="1") {$i="0".$i;}
			$dates=$year2."-".$i;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='2' && gid='$gid' && pname='$smallname' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='1' && gid='$gid' && pname='$smallname' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt1='2' && gid='$gid' && pname='$smallname' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt1='1' && gid='$gid' && pname='$smallname' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && gid='$gid' && pname='$smallname' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$i;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title="产品组：".$tp[gname]."-产品：".$smallname.$year2."年销售报表(详表)";
		}
	$this->display("admin/table1.html");
	}
//年报表
	function table_year() {
	//公共变量
	$order=spClass('orders');
	$gp=spClass('groups');
	$this->group=$gp->findAll();
	$year1=$this->spArgs('SelTjYear1');
	$year2=$this->spArgs('SelTjYear2');
	$year3=$this->spArgs('SelTjYear3');
	$year4=$this->spArgs('SelTjYear4');
	$year5=$this->spArgs('SelTjYear5');
	$year6=$this->spArgs('SelTjYear6');
	switch ($this->spArgs('act')) {
	case "1":
		$c=$year2-$year1;
		$totle=0;
		for ($i=0;$i<=$c;$i++) {
			if ($i!="0") {$year1=$year1+1;}
			$newarr=" DATE_FORMAT(addtime,'%Y')='$year1' && zt2='2' ";
			$no=$order->findAll($newarr);
				//循环年，获取每年的总额
				$year_totle=0;
				foreach ($no as $v) {
				$year_totle=$year_totle+$v[price]*$v[nums];	
				}
				//连接每年的总额
				if (isset($values)) {
				$values=$values.",".$year_totle;
				} else {
				$values=$year_totle;
				}
				//计算总额
				$totle=$totle+$year_totle;
				//y轴
				if (isset($links)) {
				$links=$links.","."\"$year1\"";
				} else {
				$links="\"$year1\"";
				}
		}
		$title=$this->spArgs('SelTjYear1')."年至".$year2."年销售报表-总金额：".$totle."元-(X轴:年  Y轴:成交额)";
		$bujin=round($totle/10);
		//写配置文件
		$content=file_get_contents("include/old.txt");
		$t=preg_replace("/text\":\".*?\"/i","text\":\"".$title."\"",$content,1);
		$t=preg_replace("/values\":\[.*?\]/i","values\":[".$values."]",$t,1);
		$t=preg_replace("/labels\":\[.*?\]/i","labels\":[".$links."]",$t,1);
		$t=preg_replace("/max\":.*?,/i","max\":".$totle.",",$t,1);
		$t=preg_replace("/steps\":.*?,/i","steps\":".$bujin.",",$t,1);
		$fo=fopen("include/old.txt","w"); 
		fwrite($fo,$t);
		fclose($fo);
		$this->z1="<div id=\"my_chart\" style=\"height:600px; color: #C10C02 border:1px solid #CCC\"></div><script>swfobject.embedSWF(\"include/open-flash-chart.swf\", \"my_chart\", \"98%\", \"400\", \"9.0.0\",\"expressInstall.swf\",{\"data-file\":\"include/old.txt\"});</script></div>";
	break;
	case "2":
		$gid=$this->spArgs('gp');
		$c=$year4-$year3;
		$totle=0;
		for ($i=0;$i<=$c;$i++) {
			if ($i!="0") {$year3=$year3+1;}
			$newarr=" DATE_FORMAT(addtime,'%Y')='$year3' && zt2='2' && gid='$gid' ";
			$no=$order->findAll($newarr);
				//循环年，获取每年的总额
				$year_totle=0;
				foreach ($no as $v) {
				$year_totle=$year_totle+$v[price]*$v[nums];	
				}
				//连接每年的总额
				if (isset($values)) {
				$values=$values.",".$year_totle;
				} else {
				$values=$year_totle;
				}
				//计算总额
				$totle=$totle+$year_totle;
				//y轴
				if (isset($links)) {
				$links=$links.","."\"$year3\"";
				} else {
				$links="\"$year3\"";
				}
		}
		$title=$this->spArgs('SelTjYear3')."年至".$year4."年销售报表-总金额：".$totle."元-(X轴:年  Y轴:成交额)";
		$bujin=round($totle/10);
		//写配置文件
		$content=file_get_contents("include/old.txt");
		$t=preg_replace("/text\":\".*?\"/i","text\":\"".$title."\"",$content,1);
		$t=preg_replace("/values\":\[.*?\]/i","values\":[".$values."]",$t,1);
		$t=preg_replace("/labels\":\[.*?\]/i","labels\":[".$links."]",$t,1);
		$t=preg_replace("/max\":.*?,/i","max\":".$totle.",",$t,1);
		$t=preg_replace("/steps\":.*?,/i","steps\":".$bujin.",",$t,1);
		$fo=fopen("include/old.txt","w"); 
		fwrite($fo,$t);
		fclose($fo);
		$this->z1="<div id=\"my_chart\" style=\"height:600px; color: #C10C02 border:1px solid #CCC\"></div><script>swfobject.embedSWF(\"include/open-flash-chart.swf\", \"my_chart\", \"98%\", \"400\", \"9.0.0\",\"expressInstall.swf\",{\"data-file\":\"include/old.txt\"});</script></div>";
	break;
	case "3":
		$smallname=$this->spArgs('hidsel');	
		$c=$year6-$year5;
		$totle=0;
		for ($i=0;$i<=$c;$i++) {
			if ($i!="0") {$year5=$year5+1;}
			$newarr=" DATE_FORMAT(addtime,'%Y')='$year5' && zt2='2' && pname='$smallname' ";
			$no=$order->findAll($newarr);
				//循环年，获取每年的总额
				$year_totle=0;
				foreach ($no as $v) {
				$year_totle=$year_totle+$v[price]*$v[nums];	
				}
				//连接每年的总额
				if (isset($values)) {
				$values=$values.",".$year_totle;
				} else {
				$values=$year_totle;
				}
				//计算总额
				$totle=$totle+$year_totle;
				//y轴
				if (isset($links)) {
				$links=$links.","."\"$year5\"";
				} else {
				$links="\"$year5\"";
				}
		}
		$title=$this->spArgs('SelTjYear5')."年至".$year6."年销售报表-总金额：".$totle."元-(X轴:年  Y轴:成交额)";
		$bujin=round($totle/10);
		//写配置文件
		$content=file_get_contents("include/old.txt");
		$t=preg_replace("/text\":\".*?\"/i","text\":\"".$title."\"",$content,1);
		$t=preg_replace("/values\":\[.*?\]/i","values\":[".$values."]",$t,1);
		$t=preg_replace("/labels\":\[.*?\]/i","labels\":[".$links."]",$t,1);
		$t=preg_replace("/max\":.*?,/i","max\":".$totle.",",$t,1);
		$t=preg_replace("/steps\":.*?,/i","steps\":".$bujin.",",$t,1);
		$fo=fopen("include/old.txt","w"); 
		fwrite($fo,$t);
		fclose($fo);
		$this->z1="<div id=\"my_chart\" style=\"height:600px; color: #C10C02 border:1px solid #CCC\"></div><script>swfobject.embedSWF(\"include/open-flash-chart.swf\", \"my_chart\", \"98%\", \"400\", \"9.0.0\",\"expressInstall.swf\",{\"data-file\":\"include/old.txt\"});</script></div>";
	}
	$this->display("admin/table_year.html");
	}
//年报表打印
	function table3() {
		$order=spClass("orders");
		$year1=$this->spArgs('SelTjYear1');
		$year2=$this->spArgs('SelTjYear2');
		$year3=$this->spArgs('SelTjYear3');
		$year4=$this->spArgs('SelTjYear4');
		$year5=$this->spArgs('SelTjYear5');
		$year6=$this->spArgs('SelTjYear6');
		switch ($this->spArgs('act')) {
		case "1":
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		$c=$year2-$year1;
			for ($i=0;$i<=$c;$i++) {
			if ($i!="0") {$year1=$year1+1;}
			$dates=$year1;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y')='$dates' && zt2='2' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y')='$dates' && zt2='1' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y')='$dates' && zt1='2' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y')='$dates' && zt1='1' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y')='$dates' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$year1;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title=$this->spArgs('SelTjYear1')."至".$year2."年销售报表(详表)";
		break;
		case "2":
		$gid=$this->spArgs('gp');
		$groups=spClass('groups');
		$group=$groups->find(array('id'=>$gid));
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		$c=$year4-$year3;
			for ($i=0;$i<=$c;$i++) {
			if ($i!="0") {$year3=$year3+1;}
			$dates=$year3;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y')='$dates' && zt2='2' && gid='$gid' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y')='$dates' && zt2='1' && gid='$gid' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y')='$dates' && zt1='2' && gid='$gid' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y')='$dates' && zt1='1' && gid='$gid' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y')='$dates' && gid='$gid' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$year3;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title="产品组：".$group[gname].$this->spArgs('SelTjYear3')."至".$year4."年销售报表(详表)";
		break;
		case "3":
		$gid=$this->spArgs('bigname');
		$groups=spClass('groups');
		$group=$groups->find(array('id'=>$gid));
		$smallname=$this->spArgs('hidsel');
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		$c=$year6-$year5;
			for ($i=0;$i<=$c;$i++) {
			if ($i!="0") {$year5=$year5+1;}
			$dates=$year5;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y')='$dates' && zt2='2' && pname='$smallname' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y')='$dates' && zt2='1' && pname='$smallname' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y')='$dates' && zt1='2' && pname='$smallname' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y')='$dates' && zt1='1' && pname='$smallname' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y')='$dates' && pname='$smallname' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$year5;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title="产品组：".$group[gname]."产品：".$smallname.$this->spArgs('SelTjYear5')."至".$year6."年销售报表(详表)";
		}
	$this->display("admin/table1.html");
	}
//导出为excel_日
	function excel1() {
		$order=spClass("orders");
		$year=$this->spArgs('SelTjYear');
		$month=$this->spArgs('SelTjMonth');
		$year1=$this->spArgs('SelTjYear1');
		$month1=$this->spArgs('SelTjMonth1');
		$year2=$this->spArgs('SelTjYear2');
		$month2=$this->spArgs('SelTjMonth2');
		//获取某月天数
		$days1=$this->getDaysofMonth($year,$month);
		$days2=$this->getDaysofMonth($year1,$month1);
		$days3=$this->getDaysofMonth($year2,$month2);
		$act=$this->spArgs('act');
		switch ($act) {
		case "1":
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		for ($i=1;$i<=$days1;$i++) {
			if (strlen($i)=="1") {$i="0".$i;}
			if (strlen($month)=="1") {$month="0".$month;}
			$dates=$year."-".$month."-".$i;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='2' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='1' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt1='2' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt1='1' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$i;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title=$year."年".$month."月销售报表(详表)";
		break;
		case "2":
		$gid=$this->spArgs('gp');
		$tgroup=spClass("groups");
		$tp=$tgroup->find(array('id'=>$gid));
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		for ($i=1;$i<=$days2;$i++) {
			if (strlen($i)=="1") {$i="0".$i;}
			if (strlen($month1)=="1") {$month1="0".$month1;}
			$dates=$year1."-".$month1."-".$i;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='2' && gid='$gid' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='1' && gid='$gid' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt1='2' && gid='$gid' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt1='1' && gid='$gid' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && gid='$gid' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$i;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title="产品组：".$tp[gname].$year1."年".$month1."月销售报表(详表)";
		break;
		case "3":
		$smallname=$this->spArgs('hidsel');	
		$gid=$this->spArgs('bigname');
		$tgroup=spClass("groups");
		$tp=$tgroup->find(array('id'=>$gid));
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		for ($i=1;$i<=$days3;$i++) {
			if (strlen($i)=="1") {$i="0".$i;}
			if (strlen($month2)=="1") {$month2="0".$month2;}
			$dates=$year2."-".$month2."-".$i;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='2' && gid='$gid' && pname='$smallname' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt2='1' && gid='$gid' && pname='$smallname' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt1='2' && gid='$gid' && pname='$smallname' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && zt1='1' && gid='$gid' && pname='$smallname' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y-%m-%d')='$dates' && gid='$gid' && pname='$smallname' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$i;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title="产品组：".$tp[gname]."-产品：".$smallname.$year2."年".$month2."月销售报表(详表)";
		}
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition:filename=please_change_filename_yourself.xls");
		$this->display("admin/table1.html");
	}
//导出为excel_月
	function excel2() {
		$order=spClass("orders");
		$year=$this->spArgs('SelTjYear');
		$year1=$this->spArgs('SelTjYear1');
		$year2=$this->spArgs('SelTjYear2');
		$act=$this->spArgs('act');
		switch ($act) {
		case "1":
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		for ($i=1;$i<=12;$i++) {
			if (strlen($i)=="1") {$i="0".$i;}
			$dates=$year."-".$i;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='2' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='1' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt1='2' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt1='1' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y-%m')='$dates' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$i;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title=$year."年销售报表(详表)";
		break;
		case "2":
		$gid=$this->spArgs('gp');
		$groups=spClass('groups');
		$group=$groups->find(array('id'=>$gid));
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		for ($i=1;$i<=12;$i++) {
			if (strlen($i)=="1") {$i="0".$i;}
			$dates=$year1."-".$i;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='2' && gid='$gid' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='1' && gid='$gid' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt1='2' && gid='$gid' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt1='1' && gid='$gid' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && gid='$gid' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$i;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title="产品组：".$group[gname].$year1."年销售报表(详表)";
		break;
		case "3":
		$smallname=$this->spArgs('hidsel');	
		$gid=$this->spArgs('bigname');
		$tgroup=spClass("groups");
		$tp=$tgroup->find(array('id'=>$gid));
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		for ($i=1;$i<=12;$i++) {
			if (strlen($i)=="1") {$i="0".$i;}
			$dates=$year2."-".$i;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='2' && gid='$gid' && pname='$smallname' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}

				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt2='1' && gid='$gid' && pname='$smallname' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt1='2' && gid='$gid' && pname='$smallname' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && zt1='1' && gid='$gid' && pname='$smallname' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y-%m')='$dates' && gid='$gid' && pname='$smallname' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$i;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title="产品组：".$tp[gname]."-产品：".$smallname.$year2."年销售报表(详表)";
		}
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition:filename=please_change_filename_yourself.xls");
	$this->display("admin/table1.html");
	}
//导出excel_年
	function excel3() {
		$order=spClass("orders");
		$year1=$this->spArgs('SelTjYear1');
		$year2=$this->spArgs('SelTjYear2');
		$year3=$this->spArgs('SelTjYear3');
		$year4=$this->spArgs('SelTjYear4');
		$year5=$this->spArgs('SelTjYear5');
		$year6=$this->spArgs('SelTjYear6');
		switch ($this->spArgs('act')) {
		case "1":
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		$c=$year2-$year1;
			for ($i=0;$i<=$c;$i++) {
			if ($i!="0") {$year1=$year1+1;}
			$dates=$year1;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y')='$dates' && zt2='2' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y')='$dates' && zt2='1' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y')='$dates' && zt1='2' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y')='$dates' && zt1='1' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y')='$dates' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$year1;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title=$this->spArgs('SelTjYear1')."至".$year2."年销售报表(详表)";
		break;
		case "2":
		$gid=$this->spArgs('gp');
		$groups=spClass('groups');
		$group=$groups->find(array('id'=>$gid));
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		$c=$year4-$year3;
			for ($i=0;$i<=$c;$i++) {
			if ($i!="0") {$year3=$year3+1;}
			$dates=$year3;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y')='$dates' && zt2='2' && gid='$gid' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y')='$dates' && zt2='1' && gid='$gid' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y')='$dates' && zt1='2' && gid='$gid' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y')='$dates' && zt1='1' && gid='$gid' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y')='$dates' && gid='$gid' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$year3;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title="产品组：".$group[gname].$this->spArgs('SelTjYear3')."至".$year4."年销售报表(详表)";
		break;
		case "3":
		$gid=$this->spArgs('bigname');
		$groups=spClass('groups');
		$group=$groups->find(array('id'=>$gid));
		$smallname=$this->spArgs('hidsel');
		$j=$totle1=$totle2=$totle3=$totle4=$totle5=0;
		$c=$year6-$year5;
			for ($i=0;$i<=$c;$i++) {
			if ($i!="0") {$year5=$year5+1;}
			$dates=$year5;
			//已成交
			$newarr=" DATE_FORMAT(addtime,'%Y')='$dates' && zt2='2' && pname='$smallname' ";
			$no1=$order->findAll($newarr);
			$t=0;
				foreach ($no1 as $v) {
				$t=$t+$v[price]*$v[nums];
				}
				$totle1=$totle1+$t;
				$this->totle1=$totle1;
			//未支付
			$newarr2=" DATE_FORMAT(addtime,'%Y')='$dates' && zt2='1' && pname='$smallname' ";
			$no2=$order->findAll($newarr2);
			$t2=0;
				foreach ($no2 as $v) {
				$t2=$t2+$v[price]*$v[nums];
				}
				$totle2=$totle2+$t2;
				$this->totle2=$totle2;
			//待发货
			$newarr3=" DATE_FORMAT(addtime,'%Y')='$dates' && zt1='2' && pname='$smallname' ";
			$no3=$order->findAll($newarr3);
			$t3=0;
				foreach ($no3 as $v) {
				$t3=$t3+$v[price]*$v[nums];
				}
				$totle3=$totle3+$t3;
				$this->totle3=$totle3;
			//待发货
			$newarr4=" DATE_FORMAT(addtime,'%Y')='$dates' && zt1='1' && pname='$smallname' ";
			$no4=$order->findAll($newarr4);
			$t4=0;
				foreach ($no4 as $v) {
				$t4=$t4+$v[price]*$v[nums];
				}
				$totle4=$totle4+$t4;
				$this->totle4=$totle4;
			//全部
			$newarr5=" DATE_FORMAT(addtime,'%Y')='$dates' && pname='$smallname' ";
			$no5=$order->findAll($newarr5);
			$t5=0;
				foreach ($no5 as $v) {
				$t5=$t5+$v[price]*$v[nums];
				}
				$totle5=$totle5+$t5;
				$this->totle5=$totle5;
			$myday[$j][days]=$year5;
			$myday[$j][t1]=$t;
			$myday[$j][t2]=$t2;
			$myday[$j][t3]=$t3;
			$myday[$j][t4]=$t4;
			$myday[$j][t5]=$t5;
			$j++;
		}
		$this->myday=$myday;
		$this->title="产品组：".$group[gname]."产品：".$smallname.$this->spArgs('SelTjYear5')."至".$year6."年销售报表(详表)";
		}
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition:filename=please_change_filename_yourself.xls");
	$this->display("admin/table1.html");
	}
}

?>