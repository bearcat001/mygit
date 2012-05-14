<?php
	class M_group extends CI_Model{
		private $group_table;
		
		private $group_category_table;
		
		private $group_member_table;
		/**
		 * 构造函数 
		 */
		function __construct(){
			parent::__construct();
			$this->group_table=$this->db->dbprefix('group');
			$this->group_category_table=$this->db->dbprefix('group_category');
			$this->group_member_table=$this->db->dbprefix('group_member');
		}

		/**
		 * 创建群组
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_group($data){
			$this->db->insert($this->group_table,$data);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		}
		
		/**
		 * 增加群组分类
		 * @param unknown_type $data
		 * @return unknown
		 */
		function add_group_category($data){
			$group_category_id=$this->get_group_category_id_by_name($data['group_category_name']);
			if($group_category_id){
				$this->db->set('group_count',"group_count+1",FALSE);
				$this->db->where(array('group_category_id'=>$group_category_id));
				$this->db->update($this->group_category_table);
				return $group_category_id;
			}else{
				$this->db->insert($this->group_category_table,$data);
				return $this->db->insert_id();
			}
		}
		
		/**
		 * 增加群组成员
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_group_member($data){
			$this->db->insert($this->group_member_table,$data);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		}
		
		/**
		 * 根据群组ID获得创建人ID
		 * @param unknown_type $group_id
		 * @return unknown|boolean
		 */
		function get_group_create_user_id_by_group_id($group_id){
			
			$this->db->select('user_id')->from($this->group_table);
			$this->db->where('group_id',$group_id);
			$query=$this->db->get();
			$row=$query->row_array();
			$query->free_result();
			
			if($row)
				return $row['user_id'];
			else 
				return FALSE;
		}
		
		/**
		 * 根据群组ID获得群组成员数据
		 * @param unknown_type $group_id
		 * @return unknown|boolean
		 */
		function get_group_member_datas_by_group_id($group_id,$exclude_user_id=0){
			
			$this->load->model('m_user');
			
			$group_member_datas=array();
			
			$this->db->select('user_id')->from($this->group_member_table);
			
			$this->db->where('group_id',$group_id);
			
			$this->db->where('is_confirmed',1);
			
			$this->db->where('user_id <>',$exclude_user_id);
			
			$query=$this->db->get();
			
			$row=$query->result_array();
			
			$query->free_result();
			
			if($row){
				foreach($row as $value){
					$user_datas[]=$this->m_user->get_user_data_by_user_id($value['user_id']);
				}
				return $user_datas;
			}
				
			else 
				return FALSE;
			
		}
		
		/**
		 * 根据用户ID和群组ID，获得此关系ID
		 * @param unknown_type $user_id
		 * @param unknown_type $group_id
		 * @return unknown|boolean
		 */
		function get_group_member_id_by_user_id($user_id,$group_id){
			$this->db->select('group_member_id,is_confirmed')->from($this->group_member_table);
			$this->db->where(
					array(
					'group_id'=>$group_id,
					'user_id'=>$user_id
					));
			$query=$this->db->get();
			$row=$query->row_array();
			$query->free_result();
			
			if($row)
				return $row;
			else
				return FALSE;
		}
		
		/**
		 * 根据用户信息获得群组中未到的用户信息
		 * @param unknown_type $group_id
		 * @param unknown_type $user_datas
		 * @return multitype:
		 */
		function get_absent_member_datas_by_user_datas($group_id,$user_datas=array(),$exclude_user_id=0){
			
			$absent_member_datas=array();
			
			$group_member_datas=$this->get_group_member_datas_by_group_id($group_id,$exclude_user_id);
			
			if($group_member_datas){
				$absent_member_datas=array_udiff(
						$group_member_datas, $user_datas,
						array('M_group','group_member_user_datas_compare')
				);
				
				return $absent_member_datas;
			}else{
				return FALSE;
			}
			
			
		}
		
		/**
		 * array_diff的比较数
		 * @param unknown_type $user_datas1
		 * @param unknown_type $user_datas2
		 * @return number
		 */
		function group_member_user_datas_compare($user_datas1,$user_datas2){
			if ($user_datas1['user_id']===$user_datas2['user_id'])
			{
				return 0;
			}
			return ($user_datas1['user_id'] > $user_datas2['user_id'])? 1:-1;
		}
		
		/**
		 * 根据分类获得群组列表
		 * @param unknown_type $page_count
		 * @param unknown_type $page
		 * @param unknown_type $category
		 * @return unknown|boolean
		 */
		function get_group_datas_by_category($category,$page=0,$page_count=0){
			
			$group_datas=array();
			
			if(!$page)
				$page=1;
			
			if(!$page_count)
				$page_count=20;
			
			if($category){
					$this->db->where('group_category',$category);
			}
				
			$query = $this->db->get($this->group_table,$page_count,($page-1)*$page_count);
			
			$group_datas=$query->result_array();
			
			$query->free_result();
			
			if($group_datas)
				return $group_datas;
			else
				return FALSE;
		}
		
		function get_group_datas_by_user_id($user_id,$is_admin,$page=0,$page_count=0){
			$group_datas=array();
			
			if(!$page)
				$page=1;
			
			if(!$page_count)
				$page_count=20;
			
			$this->db->select('group_id,is_admin')->from($this->group_member_table);
			$this->db->where('user_id',$user_id);
			if($is_admin)
				$this->db->where('is_admin',1);
			$query=$this->db->get();
			$row=$query->result_array();
			$query->free_result();
			if($row){
				foreach($row as $key=>$value){
					$group_data=$this->get_group_data_by_group_id($value['group_id']);
					$group_data['is_admin']=$value['is_admin'];
					$group_datas[]=$group_data;
				}
				return $group_datas;
			}else{
				return FALSE;
			}

		}
		
		/**
		 * 根据群组分类名称获得群组分类ID
		 * @param unknown_type $group_category_name
		 */
		function get_group_category_id_by_name($group_category_name){
			$this->db->select('group_category_id');
			$this->db->from($this->group_category_table);
			$this->db->where('group_category_name',$group_category_name);
			$query = $this->db->get();
			$row = $query->row();
			$query->free_result();
			if($row)
				return $row->group_category_id;
			else return FALSE;
		}

		function get_user_status_by_user_id_and_group_id($user_id,$group_id){
			$this->db->select('is_admin,is_confirmed');
			$this->db->from($this->group_member_table);
			$this->db->where('user_id',$user_id);
			$this->db->where('group_id',$group_id);
			$query=$this->db->get();
			$row=$query->row_array();
			$query->free_result();
			
			if($row)
				return $row;
			else 
				return FALSE;
		}
		
		/**
		 * 根据群组ID获得群组信息
		 * @param unknown_type $group_id
		 * @return unknown|boolean
		 */
		function get_group_data_by_group_id($group_id){
			$this->db->select("*")->from($this->group_table);
			$this->db->where('group_id',$group_id);
			$query=$this->db->get();
			$row=$query->row_array();
			$query->free_result();
			if($row){
				return $row;
			}
			else return FALSE;
		}
		
		/**
		 * 增加群组成员数
		 * @param unknown_type $group_id
		 */
		function increase_group_member_count($group_id){
			$this->db->set('member_count',"member_count+1",FALSE);
			$this->db->where(array('group_id'=>$group_id));
			$this->db->update($this->group_table);
		}
	}