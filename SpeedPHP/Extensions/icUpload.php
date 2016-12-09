<?php
/**
 * icUpload
 * 
 * 基于SpeedPHP的非官方文件上传类
 * 此类不包含图片处理功能
 * 
 * @author Pony
 * @link http://icase-speedphp.googlecode.com
 * 
 */

class icUpload {
	/**
	 * icUpload类版本信息
	 * @var Array
	 */
	protected $VERSION = array('COMMON','20110223','beta');
	
	/**
	 * 默认的文件上传设置
	 * 你可以直接更改这些设置
	 * @var Array
	 */
	private $DEFAULT_SET = array('SAVEPATH' => './tmp/',								//默认文件保存路径
								 'MAXSIZE' => 0,										//默认的最大上传文件大小，以字节为单位，为0则不作限制
								 'ALLOW_SUFFIX' => array('jpg'=>TRUE,'gif'=>TRUE,'php'=>FALSE),	//允许上传的文件后缀，TRUE为允许，FALSE为阻止；当存在大于等于一个TRUE时，除TRUE的文件后缀，其它后缀均被阻止
								 'FORCE_SUFFIX' => FALSE								//是否强制添加后缀，若为TRUE值，则会在所有上传的文件名后加上.safe后缀
								);
	
	/**
	 * 接收到的文件信息
	 * @var Array
	 */
	private $CURRENT_INFO = array('TMP_PATH'=>'','FILESIZE'=>'','ORIGIN_NAME'=>'','ORIGIN_SUFFIX'=>'');

	/**
	 * 构造函数;
	 * 当表单中的上传input名称不是file时，可以通过传入一个值改变;
	 * 该构造函数用于自动获取已经上传文件的信息，以利于类的下一步操作
	 * @param string $inputname 上传的input的名称
	 */
	public function __construct($inputname = 'file'){
		if(!isset($_FILES[$inputname]['tmp_name'])) return FALSE;
		$this -> CURRENT_INFO['TMP_PATH'] = $_FILES[$inputname]['tmp_name'];
		$this -> CURRENT_INFO['FILESIZE'] = $_FILES[$inputname]['size'];
		$this -> CURRENT_INFO['ORIGIN_NAME'] = $_FILES[$inputname]['name'];
		$pathinfo = pathinfo($this -> CURRENT_INFO['ORIGIN_NAME']);
		$this -> CURRENT_INFO['ORIGIN_SUFFIX'] = $pathinfo['extension'];
	}
	
	/**
	 * 文件上传参数设置
	 * @param string $beseted 需要设置的参数
	 * @param mix $val 参数值
	 */
	public function set($beseted,$val){
		$this -> DEFAULT_SET[$beseted] = $val;
	}

	/**
	 * 校验上传的文件是否超出大小限制
	 */
	public function checkSize(){
		if($this -> DEFAULT_SET['MAXSIZE'] == 0) return TRUE;
		elseif($this -> DEFAULT_SET['MAXSIZE'] >= $this -> CURRENT_INFO['FILESIZE']) return TRUE;
		else return FALSE;
	}
	
	/**
	 * 校验上传的文件是否超出后缀限制
	 */
	public function checkSuffix(){
		if(empty($this -> DEFAULT_SET['ALLOW_SUFFIX'])){
			return TRUE;
		}
		elseif(array_search(TRUE,$this -> DEFAULT_SET['ALLOW_SUFFIX'])){
			if($this -> DEFAULT_SET['ALLOW_SUFFIX'][$this -> CURRENT_INFO['ORIGIN_SUFFIX']] == TRUE) return TRUE;
			else return FALSE;
		}
		elseif(array_search(FALSE,$this -> DEFAULT_SET['ALLOW_SUFFIX']))
		{
			if($this -> DEFAULT_SET['ALLOW_SUFFIX'][$this -> CURRENT_INFO['ORIGIN_SUFFIX']] == FALSE) return FALSE;
			else return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function save($filename = NULL){
		if(!$this->checkSize() || !$this->checkSuffix()) return FALSE;
		$filename = empty($filename) ? $this->CURRENT_INFO['ORIGIN_NAME'] : $filename;
		$filename = $this->CURRENT_INFO['FORCE_SUFFIX'] ? $filename.'.safe' : $filename;
		if(copy($this->CURRENT_INFO['TMP_PATH'],$this->DEFAULT_SET['SAVEPATH'].$filename)) return $this->DEFAULT_SET['SAVEPATH'].$filename;
		else return FALSE;
	}
}
?>