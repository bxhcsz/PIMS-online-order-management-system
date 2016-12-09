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
class kuaidi extends spController
{
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
//显示快递设置页
	function keys() {
		$this->content=file_get_contents("include/kuaidi.php");
		$this->display("admin/kuaidikey.html");
	}	
//保存快递key
	function save() {
		$fo=fopen('include/kuaidi.php',"w"); 
		fwrite($fo,$this->spArgs('keys'));
		fclose($fo);
		//写入配置文件
		$content=file_get_contents("controller/get.php");
		$kd=preg_replace("/AppKey='.*?'/i","AppKey='".$this->spArgs('keys')."'",$content,1);
		$fo=fopen('controller/get.php',"w"); 
		fwrite($fo,$kd);
		fclose($fo);
		$this->success("授权key保存成功，您现在可以正常使用快递追踪功能", spUrl("kuaidi", "keys"));
	}
//获取快递公司代码
	function getkdcode($obj) {
		switch ($obj) {
		case "安信达":
		return "anxindakuaixi";
		break;
		case "申通":
		return "shentong";
		break;
		case "EMS":
		return "ems";
		break;
		case "顺丰":
		return "shunfeng";
		break;
		case "圆通":
		return "yuantong";
		break;
		case "中通":
		return "zhongtong";
		break;
		case "如风达":
		return "rufengda";
		break;
		case "韵达":
		return "yunda";
		break;
		case "天天":
		return "tiantian";
		break;
		case "汇通":
		return "huitongkuaidi";
		break;
		case "全峰":
		return "quanfengkuaidi";
		break;
		case "德邦":
		return "debangwuliu";
		break;
		case "宅急送":
		return "zhaijisong";
		break;
		case "包裹平邮":
		return "youzhengguonei";
		break;
		case "CCES":
		return "cces";
		break;
		case "传喜物流":
		return "chuanxiwuliu";
		break;
		case "DHL":
		return "dhl";
		break;
		case "大田物流":
		return "datianwuliu";
		break;
		case "E邮宝":
		return "ems";
		break;
		case "飞康达":
		return "feikangda";
		break;
		case "FedEx(国际)":
		return "fedex";
		break;
		case "港中能达":
		return "ganzhongnengda";
		break;
		case "挂号信":
		return "youzhengguonei";
		break;
		case "共速达":
		return "gongsuda";
		break;
		case "华宇物流":
		return "tiandihuayu";
		break;
		case "佳吉快运":
		return "jiajiwuliu";
		break;
		case "佳怡物流":
		return "jiayiwuliu";
		break;
		case "急先达":
		return "jixianda";
		break;
		case "快捷速递":
		return "kuaijiesudi";
		break;
		case "龙邦快递":
		return "longbanwuliu";
		break;
		case "联邦快递":
		return "lianbangkuaidi";
		break;
		case "联昊通":
		return "lianhaowuliu";
		break;
		case "全一快递":
		return "quanyikuaidi";
		break;
		case "全日通":
		return "quanritongkuaidi";
		break;
		case "速尔快递":
		return "suer";
		break;
		case "TNT快递":
		return "tnt";
		break;
		case "天地华宇":
		return "tiandihuayu";
		break;
		case "UPS快递":
		return "ups";
		break;
		case "USPS":
		return "usps";
		break;
		case "新邦物流":
		return "xinbangwuliu";
		break;
		case "优速快递":
		return "youshuwuliu";
		break;
		case "中铁快运":
		return "ztky";
		break;
		case "中邮物流":
		return "zhongyouwuliu";
		break;
		}
	}
//快递追踪
	function search () {
		$sp=spClass('orders');
		$this->rs=$sp->find(array('id'=>$this->spArgs('id')));
		$this->daima=$this->getkdcode($this->rs[wuliu]);
		//获取当前快递公司代码
		$this->display("admin/kuaidi.html");
	}	
//显示发件人设置页面
	function printshow() {
		$gb=spClass("fajian");
		$this->arr=$gb->find();
		$this->display("admin/kuaidi_fajian.html");
	}
//保存发件人设置
	function fajiansave() {
		$array=array(
			'sendname' => $this->spArgs('sendname'),
			'sendgongsi' => $this->spArgs('sendgongsi'),
			'sendaddr' => $this->spArgs('sendaddr'),
			'sendtel' => $this->spArgs('sendtel'),
			'sendmob' => $this->spArgs('sendmob'),
			'sendyoubian' => $this->spArgs('sendyoubian'),
			'daishou' => $this->spArgs('daishou'),
			'beizhu' => $this->spArgs('beizhu'),
			'huodao' => $this->spArgs('huodao')
		);
		$gb=spClass("fajian");
		$bool=$gb->find();
		if ($bool[sendname]=="") {
		$gb->create($array);
		} else {
		$gb->update('',$array);
		}
		$this->success("发件人保存成功", spUrl("kuaidi", "printshow"));
	}
//单订单快递单打印
	function prints() {
		$oid=$this->spArgs('id');
		$sp=spClass('orders');
		$rs=$sp->find(array('id'=>$oid));
		$daima=$this->getkdcode($rs[wuliu]);
		//获取发件人信息
		$gb=spClass("fajian");
		$this->fajian=$gb->find();
		//收件人信息
		$this->oname=$rs[realname];
		$this->omob=$rs[mob];
		$this->oaddress=$rs[address];
		switch ($daima) {
			case "yuantong":
				
				$this->display("admin/kuaidi_single_yuantong.html");
			break;
			default:
				echo "暂时不支持此快递的快递单打印功能";
		}
		
	}
}
?>