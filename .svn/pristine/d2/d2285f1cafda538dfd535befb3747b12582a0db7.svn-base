<?php
	/**
	 * 检查密码是否可用
	 * @param unknown_type $password
	 */
	function valid_password($password){
		if(is_string($password)){
			return preg_match("|^[a-zA-Z0-9]{6,20}$|", $password);
		}
		return FALSE;
	}
	
	function valid_real_name($real_name){
		if(is_string($real_name)){
			return preg_match("/^[\x{4e00}-\x{9fa5} a-zA-Z0-9]{2,10}+$/u", $real_name);
		}
		return FALSE;
	}
	
	function valid_bluetooth_name($bluetooth_name){
		if(is_string($bluetooth_name)){
			return preg_match("/^[\x{4e00}-\x{9fa5} a-zA-Z0-9]{2,10}+$/u", $bluetooth_name);
		}
		return FALSE;
	}
	
	function valid_bluetooth_mac($bluetooth_mac){
		if(is_string($bluetooth_mac)){
			return preg_match("|^[a-zA-Z0-9]{12}$|", $bluetooth_mac);
		}
		return FALSE;
	}