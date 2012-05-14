<?php

	class M_blog extends CI_Model{
		private $blog_table;
		private $blog_category_table;
		private $blog_tag_table;
		
		/**
		 * 构造函数 
		 */
		function __construct(){
			parent::__construct();
			$this->blog_table=$this->db->dbprefix('blog_table');
			$this->blog_category_table=$this->db->dbprefix('blog_category_table');
			$this->blog_tag_table=$this->db->dbprefix('blog_tag_table');
		}

		
		/**
		 * 添加一个用户
		 * @param array $data
		 * @return string|Ambigous <string, boolean>
		 */
		function add_blog($data){
			$this->db->insert($this->blog_table,$data);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		}

	
	}
?>
		
		