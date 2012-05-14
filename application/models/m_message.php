<?php
	class M_message extends CI_Model{
		private $message_table;
		
		
		/**
		 * 构造函数 
		 */
		function __construct(){
			parent::__construct();
			$this->message_table=$this->db->dbprefix('message');
		}
		

		
		/**
		 * 创建信息
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_message($data){
			$this->db->insert($this->message_table,$data);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		}

	}