<?php
	class M_friend extends CI_Model{
		private $friend_table;
		
		/**
		 * 构造函数 
		 */
		function __construct(){
			parent::__construct();
			$this->friend_table=$this->db->dbprefix('friend');
		}

		
		/**
		 * 增加一条好友信息
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_friend($data){
			$this->db->insert($this->friend_table,$data);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		}
		
		/**
		 * 获得用户的所有好友ID
		 * @param unknown_type $user_id
		 * @return boolean
		 */
		function get_user_friend_ids($user_id){
			$this->db->select('friend_id');
			$this->db->from($this->friend_table);
			$this->db->where('user_id',$user_id);
			$query=$this->db->get();
			if($query->num_rows()>0){
				$ids=$query->result_array();
				$query->free_result();
				foreach($ids as $key=>$value){
					$ids[$key]=$value['friend_id'];
				}
			}else{
				return FALSE;
			}
			return $ids;
		}
		
		/**
		 * 根据两个用户ID获得好友关系ID
		 * @param unknown_type $from_id
		 * @param unknown_type $to_id
		 * @return integer |boolean
		 */
		function get_friendship_id_by_user_id($from_id,$to_id){
			$this->db->select('friendship_id');
			$this->db->from($this->friend_table);
			$this->db->where('user_id',$from_id);
			$this->db->where('friend_id',$to_id);
			$query=$this->db->get();
			if($query->num_rows()>0){
				$num=$query->row_array();
				return $num['friendship_id'];
			}else{
				return FALSE;
			}
				
		}
		
	}