<?php
	class M_online extends CI_Model{
		private $online_table;
		
		/**
		 * 构造函数 
		 */
		function __construct(){
			parent::__construct();
			$this->online_table=$this->db->dbprefix('online');
		}
		

		/**
		 * 更新用户在线状况
		 * @param array $data(user_id,display_name,online_type,create_time)
		 * @return boolean
		 */
		function add_online($data){
			$this->db->select('user_id')->from($this->online_table)->where('user_id',$data['user_id']);
			$query = $this->db->get();
			$num = $query->num_rows();
			if($num>0)
				$this->db->update($this->online_table,array('create_time'=>$data['create_time']),
						array('user_id'=>$data['user_id']));
			else $this->db->insert($this->online_table,$data);
			
			//1%的机会删除过期在线用户
			if(rand(1,100)==1)
				$this->updata_online();
			
			return TRUE;
		}
		
		function delete_online($data){
			$this->db->where('user_id',$data['user_id']);
			$this->db->delete($this->online_table);
		}
		
		
		function updata_online(){
			$expiration=time()-300;
			$this->db->where('create_time <',$expiration);
			$this->db->delete($this->online_table); 
		}
		
		
	}