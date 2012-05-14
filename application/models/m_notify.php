<?php
	class M_notify extends CI_Model{
		private $notify_table;
		
		
		/**
		 * 构造函数 
		 */
		function __construct(){
			parent::__construct();
			$this->notify_table=$this->db->dbprefix('notify');
		}
				
		/**
		 * 创建信息
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_notify($data){
			$this->db->insert($this->notify_table,$data);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		}
		
		/**
		 * 设置已查看的信息
		 * @param unknown_type $notify_id
		 * @return boolean
		 */
		function ignore_notify($notify_id){
			$this->db->set('is_read','1');
			$this->db->where('notify_id',$notify_id);
			$this->db->update($this->notify_table);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		}
		
		function get_all_user_not_read_notify_num($user_id){
			$this->db->select("notify_type,COUNT(notify_type) as notify_count",FALSE);
			$this->db->from($this->notify_table);
			$this->db->where('to_id',$user_id);
			$this->db->where('is_read',0);
			$query=$this->db->get();
			$row=$query->result_array();
			$query->free_result();
			if($row)
				return $row;
			else
				return FALSE;
		}
		
		/**
		 * 根据通知ID获得通知内容
		 * @param unknown_type $notify_id
		 * @return unknown|boolean
		 */
		function get_notify_data_by_notify_id($notify_id){
			$this->db->select('*');
			$this->db->from($this->notify_table);
			$this->db->where('notify_id',$notify_id);
			$query=$this->db->get();
			$row=$query->row_array();
			$query->free_result();
			if($row)
				return $row;
			else
				return FALSE;
		}
		
	}