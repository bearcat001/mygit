<?php
	class M_comment extends CI_Model{
		private $friend_table;
		
		/**
		 * 构造函数 
		 */
		function __construct(){
			parent::__construct();
			$this->comment_table=$this->db->dbprefix('comment');
		}

		
		/**
		 * 增加一条好友信息
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_comment($data){
			$this->db->insert($this->comment_table,$data);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		}
		
	}