<?php
	
	/**
	 * 创建用户图片目录
	 * @param unknown_type $user_id
	 */
	function create_user_dir($user_id){
		if(!file_exists('upload/'.$user_id))
			mkdir('upload/'.$user_id);
	}