<?php
class dbbackup {

	public $conn;
	public $arrSql;
	public $database;
	private $ln = "\n";
	public $sqlEnd = "; ";
	
 	public function __construct($dbConfig) {
	$this->database = $dbConfig['database'];
		$this->conn = mysql_connect($dbConfig['host'].":".$dbConfig['port'], $dbConfig['login'], $dbConfig['password']) or spError("数据库链接错误 : " . mysql_error()); 
		mysql_select_db('information_schema', $this->conn) or spError("无法链接数据库或者没有information_schema访问权限。");
		$this->exec("SET NAMES UTF8");
 	}
	
	/*获取所有表*/
	public function showAllTable($chk)
	{
		$sql = "SELECT * FROM `TABLES` where TABLE_SCHEMA ='$this->database'";
		$rs =  $this->getArray($sql);
		$all_byte = 0; //大小
		$all_table = 0; //表个数
		$all_free = 0; //多余
		foreach($rs as $k=>$d)
		{
			$all_byte += $d['DATA_LENGTH'];
			$all_free += $d['DATA_FREE'];
			$all_table++;
			if($chk)
			{
				mysql_select_db($this->database, $this->conn) or spError("无法链接数据库或者没有".$this->database."数据库访问权限。");
				$sql = "CHECK TABLE `{$d['TABLE_NAME']}` ";
				$ck = $this->getArray($sql);
				$rs[$k]['CHECK_TABLE'] = $ck[0]['Msg_text'];
			}else{
				$rs[$k]['CHECK_TABLE'] = 'NCHECK'; //没有检查
			}
		}
		$da['rs'] = $rs;
		$da['all_byte'] = $all_byte;
		$da['all_table'] = $all_table;
		$da['all_free'] = $all_free;
		return $da;
	}
	
	public function exec($sql)
	{
		$this->arrSql[] = $sql;
		if( $result = mysql_query($sql, $this->conn) ){
			return $result;
		}else{
			spError("{$sql}<br />执行错误: " . mysql_error());
		}
	}
	
	public function optimizeTable($tab)
	{
		mysql_select_db($this->database, $this->conn) or spError("无法链接数据库或者没有".$this->database."数据库访问权限。");
		$sql = "OPTIMIZE TABLE `$tab` ";
		$this->exec($sql);
	}
	
	public function repairTable($tab)
	{
		mysql_select_db($this->database, $this->conn) or spError("无法链接数据库或者没有".$this->database."数据库访问权限。");
		$sql = "REPAIR TABLE `$tab` ";
		$this->exec($sql);
	}
	
	/*导出表*/
	public function outTable($table)
	{
		$tabsql = $this->getTableCreateSql($table);
		$tablinfo = $this->getTableInfo($table);
		$filename = $table.'_'.date('YmdHis',time()).'.sql';
		$this->output($filename,$tabsql.$tablinfo);
	}
	
	public function outAllData()
	{
		
		$strinstart = "-- {$GLOBALS['YB']['soft']} {$GLOBALS['YB']['version']} SQL Dump".$this->ln;
		$strinstart .= "-- {$GLOBALS['YB']['url']}".$this->ln;
		$strinstart .= "-- 生成日期：".date('Y年 m月 d日 H:i').$this->ln;
		$strinstart .= "-- 服务器版本:" .$this->version(). $this->ln;
		$strinstart .= "-- PHP版本:". phpversion(). $this->ln;
        $strinstart .=  $this->ln;
		$strinstart .= 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";'. $this->ln;
		$strinstart .= 'SET time_zone = "+00:00";'. $this->ln;
		$strinstart .=  $this->ln;
		$strinstart .= "--" . $this->ln;
		$strinstart .= "-- 数据库：" . $this->database. $this->ln;
		$strinstart .= "--" . $this->ln;
        $strinstart .= $this->ln;
		$strinstart .=  $this->ln;
		
		mysql_select_db($this->database, $this->conn) or spError("无法链接数据库或者没有".$this->database."数据库访问权限。");
		$rs = $this->getArray('show tables');
		
	
		$data = '';
		foreach($rs as $d)
		{
			$str = $this->getTableCreateSql($d['Tables_in_'.$this->database]);
			$str2 = $this->getTableInfo($d['Tables_in_'.$this->database]);
			$data.= $str.$str2;
		}
		$filename = $this->database.'_'.date('YmdHis',time()).'.sql';
		$this->output($filename,$strinstart.$data);
	}
	
	/** 获取该表的结构
     * @param string $table
     * @return string
     */
    private function getTableCreateSql($table) {
		mysql_select_db($this->database, $this->conn) or spError("无法链接数据库或者没有".$this->database."数据库访问权限。");
		$sql = "SHOW CREATE TABLE `$table`";
		$rs = $this->getArray($sql); //获取crate sql
		$string = $rs[0]['Create Table'];
		$tbname = $rs[0]['Table'];
		
		$string = str_replace('CREATE TABLE "'.$tbname.'"','CREATE TABLE IF NOT EXISTS "'.$tbname.'"',$string);  //修改成可以判断是否存在的sql
		$string = str_replace('"','`',$string);
		
		$strinstart = "--".$this->ln;
        $strinstart .= "-- 转储表中的数据" .$table. $this->ln;
        $strinstart .= "--" . $this->ln;
        $strinstart .= $this->ln;
		
		mysql_select_db('information_schema', $this->conn) or spError("无法链接数据库或者没有".$this->database."数据库访问权限。"); //切换到信息表查询引擎 字符集信息
		$sql = "SELECT * FROM `TABLES` where TABLE_SCHEMA = '$this->database' and TABLE_NAME = '$tbname'";
		$rs =  $this->getArray($sql);
		$charset = explode('_',$rs[0]['TABLE_COLLATION']); //获取字符集
		if($rs[0]['AUTO_INCREMENT'] != ''){$auto = "AUTO_INCREMENT=".$rs[0]['AUTO_INCREMENT'];} //检查是否有autoincrement
		return $strinstart.$string.' ENGINE='.$rs[0]['ENGINE'].'  DEFAULT CHARSET='.$charset[0].' COMMENT=\''.$rs[0]['TABLE_COMMENT'].'\' '.$auto.' ;';
    }
	
	/**获取表内容*
	 * @param string $table
     * @return string
	 */
	private function getTableInfo($table)
	{
		mysql_select_db($this->database, $this->conn) or spError("无法链接数据库或者没有".$this->database."数据库访问权限。");
		$sql = "select * from `{$this->database}`.`$table` WHERE 1";
		$rs = $this->getArray($sql); //获取crate sql
		
		if(is_array($rs))
		{
		$filed = $val = '';
		//处理字段
		foreach($rs[0] as  $k=>$v){ $filed .= '`'.$k.'` ,'; }
		$filed = substr($filed,0,-1); //去掉多余的逗号
		
		foreach($rs as $d)
		{
			$vas = '';
			foreach($d as $va)
			{
				if(is_numeric($va)){ $vas .= $va .','; }else{  $vas.= "'".addslashes($va)."',"; }
			}
			$vas = substr($vas,0,-1);
			$val.= '('.$vas."),{$this->ln}";
		}
		$sql = "{$this->ln}{$this->ln}INSERT INTO `$table` ";
		$sql .="( $filed ) VALUES {$this->ln} $val";
		$sql = substr($sql,0,-2).';';
		return  $sql.$this->ln.$this->ln;
		}else{
		return  $this->ln.$this->ln;
		}
	}
	
	
	function output($filename,$content)
	{
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");;
		header("Content-Disposition: attachment;filename=$filename"); 
		header("Content-Transfer-Encoding: binary ");
		echo $content;
		exit;
	}
	
	
	
	public function getArray($sql)
	{
		if( ! $result = $this->exec($sql) )return FALSE;
		if( ! mysql_num_rows($result) )return FALSE;
		$rows = array();
		while($rows[] = mysql_fetch_array($result,MYSQL_ASSOC)){}
		mysql_free_result($result);
		array_pop($rows);
		return $rows;
	}
	
	private function version()
	{
		$sql = "select version() as v";
		$rs = $this->getArray($sql);
		return $rs[0]['v'];
	
	}
	
	public function __destruct()
	{
		@mysql_close($this->conn);
	}
	

 
}


?>