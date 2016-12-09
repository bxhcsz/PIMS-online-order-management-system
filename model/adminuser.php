<?php
class adminuser extends spModel
{
	public $pk = "id";
	public $table = "adminuser";
	
	public function userlogin($uname, $upass){ 
		$conditions = array(
			'username' => $uname,
			'password' => $upass, 
		);
		if( $result = $this->find($conditions) ){ 
			// 成功通过验证，下面开始对用户的权限进行会话设置，最后返回用户ID
			// 用户的角色存储在用户表的acl字段中
			spClass('spAcl')->set($result['acl']); // 通过spAcl类的set方法，将当前会话角色设置成该用户的角色
			$_SESSION["admin_username"] = $result; // 在SESSION中记录当前用户的信息
			return true;
		}else{
			return false;
		}
	}
	public function acljump(){ 
		$url = spUrl("admin","index");
		echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"您没有权限进行此操作，请登录！\");parent.location.href=\"{$url}\";}</script></head><body onload=\"sptips()\"></body></html>";
		exit;
	}
}
?>