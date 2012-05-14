<?php
	class M_event extends CI_Model{
		private $event_table;
		
		private $event_member_table;
		
		/**
		 * 构造函数 
		 */
		function __construct(){
			parent::__construct();
			$this->event_table=$this->db->dbprefix('event');
			$this->event_member_table=$this->db->dbprefix('event_member');
		}

		
		/**
		 * 创建事件
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_event($data){
			$this->db->insert($this->event_table,$data);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		
		}
		
		function get_place_recent_events($place_id,$now){
			$this->db->select("event_id,event_name,event_destription,start_time,end_time,place_id,member_count")->from($this->event_table);
			$this->db->where('start_time <',$now);
			$this->db->where('end_time >',$now);
			$this->db->order_by('start_time','desc');
			$query=$this->db->get();
			$events=$query->result_array();
			if($events)
				return $events;
			else
				return FALSE;
		}
		
		function get_place_recent_event($place_id,$now){
			$this->db->select("event_id,event_name,event_destription,start_time,end_time,place_id,member_count")->from($this->event_table);
			$this->db->where('start_time <',$now);
			$this->db->where('end_time >',$now);
			$this->db->order_by('start_time','desc');
			$query=$this->db->get();
			$events=$query->row_array();
			if($events)
				return $events;
			else
				return FALSE;
		}
		
		/**
		 * 增加事件内成员
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_event_member($data){
			$this->db->insert($this->event_member_table,$data);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		}
		
		
		/**
		 * 增加事件内用户数
		 * @param unknown_type $place_id
		 */
		function increase_event_member_count($place_id){
			$this->db->set('member_count',"member_count+1",FALSE);
			$this->db->where(array('place_id'=>$place_id));
			$this->db->update($this->event_table);
		}
	}