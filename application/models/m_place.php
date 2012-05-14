<?php
	class M_place extends CI_Model{
		private $place_table;
		
		private $place_category_table;
		
		private $place_member_table;
		
		private $place_meta_table;
		
		/**
		 * 构造函数 
		 */
		function __construct(){
			parent::__construct();
			$this->place_table=$this->db->dbprefix('place');
			$this->place_category_table=$this->db->dbprefix('place_category');
			$this->place_member_table=$this->db->dbprefix('place_member');
			$this->place_meta_table=$this->db->dbprefix('place_meta');
		}
		
		/**
		 * 创建场所
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_place($data){
			$this->db->insert($this->place_table,$data);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		}
		
		function add_place_meta($data){
			$place_meta_id=$this->get_place_meta_id_by_key($data['place_id'],$data['meta_key']);
		
			if($place_meta_id){
				$this->db->update(
						$this->place_meta_table,
						array('meta_value'=>$data['meta_value']),
						array('place_meta_id'=>$place_meta_id)
				);
				return TRUE;
			}else{
				$this->db->insert(
						$this->place_meta_table,
						array(
								'place_id'=>$data['place_id'],
								'meta_key'=>$data['meta_key'],
								'meta_value'=>$data['meta_value']
						));
				return TRUE;
			}
		}
		
		function get_place_meta_id_by_key($place_id,$meta_key){
			$this->db->select('place_meta_id')->from($this->place_meta_table);
			$this->db->where(array(
					'place_id'=>$place_id,
					'meta_key'=>$meta_key
			));
			$query = $this->db->get();
			$num = $query->row();
			$query->free_result();
			if($num)
				return intval($num->place_meta_id);
			else
				return FALSE;
		}
		
		
		function get_place_meta_value_by_key($place_id,$meta_key){
			$this->db->select('meta_value')->from($this->place_meta_table);
			$this->db->where(array(
					'place_id'=>$place_id,
					'meta_key'=>$meta_key
			));
			$query = $this->db->get();
			$num = $query->row();
			$query->free_result();
			if($num)
				return $num->meta_value;
			else
				return FALSE;
		}
		
		
		function get_place_metas_by_place_id($place_id){
			$this->db->select('meta_key,meta_value')->from($this->place_meta_table);
			$this->db->where('place_id',$place_id);
			$query = $this->db->get();
			$row = $query->result_array();
			$query->free_result();
			if($row)
				return $row;
			else
				return FALSE;
		}
		
		
		/**
		 * 创建场所分类
		 * @param unknown_type $data
		 * @return unknown
		 */
		function add_place_category($data){
			$place_category_id=$this->get_place_category_id_by_name($data['place_category_name']);
			if($place_category_id){
				$this->db->set('place_count',"place_count+1",FALSE);
				$this->db->where(array('place_category_id'=>$place_category_id));
				$this->db->update($this->place_category_table);
				return $place_category_id;
			}else{
				$this->db->insert($this->place_category_table,$data);
				return $this->db->insert_id();
			}
		}
		
		/**
		 * 增加场所内用户
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_place_member($data){
			$place_member_id=$this->get_place_member_id_by_place_id($data['user_id'], $data['place_id']);
			if($place_member_id){
				$this->db->update(
						$this->place_member_table,
						array('create_time'=>$data['create_time']),
						array('place_member_id'=>$place_member_id)
				);
				return TRUE;
			}else{
				$this->db->insert(
						$this->place_member_table,
						array('place_id'=>$data['place_id'],
								'user_id'=>$data['user_id'],
								'is_admin'=>$data['is_admin'],
								'is_confirmed'=>$data['is_confirmed'],
								'create_time'=>$data['create_time']
								)
						);
				return TRUE;
			}
		
		}
		
		function updata_place_member(){
			$expiration=time()-300;
			$this->db->where('create_time <',$expiration);
			$this->db->delete($this->place_member_table);
		}
		
		function get_recent_place_members($place_id){
			$expiration=time()-300;
			$this->db->select('user_id')->from($this->place_member_table);
			$this->db->where('create_time >',$expiration);
			$this->db->where('place_id',$place_id);
			$query=$this->db->get();
			$user_ids=$query->result_array();
			if($user_ids){
				foreach($user_ids as $key=>$value){
					$user_ids[$key]=$value['user_id'];
				}
				return $user_ids;
			}
			else
				return FALSE;
		}
		
		function get_place_member_id_by_place_id($user_id,$place_id){
			$this->db->select('place_member_id')->from($this->place_member_table);
			$this->db->where('user_id',$user_id);
			$this->db->where('place_id',$place_id);
			$query=$this->db->get();
			$num=$query->row();
			if($num)
				return $num->place_member_id;
			else
				return FAlSE;
		}
		
		/**
		 * 根据场所名称获取场所分类ID
		 * @param unknown_type $place_category_name
		 */
		function get_place_category_id_by_name($place_category_name){
			$this->db->select('place_category_id');
			$this->db->from($this->place_category_table);
			$this->db->where('place_category_name',$place_category_name);
			$query = $this->db->get();
			$num = $query->row();
			$query->free_result();
			return $num->place_category_id;
		}
		
		function get_place_data_by_bluetooth_id($bluetooth_id){
			$this->db->select("place_id,place_name,place_destription,place_category,member_count,create_time");
			$this->db->from($this->place_table);
			$this->db->where('bluetooth_id',$bluetooth_id);
			$query=$this->db->get();
			$place=$query->row_array();
			if($place){
				$place_metas=$this->get_place_metas_by_place_id($place['place_id']);
				if($place_metas){
					foreach($place_metas as $value){
						$place[$value['meta_key']]=$value['meta_value'];
					}
				}
				return $place;
			}
			else
				return FALSE;
		}
		
		function get_place_datas_by_category($category,$page=0,$page_count=0){
		
			$place_datas=array();
		
			if(!$page)
				$page=1;
		
			if(!$page_count)
				$page_count=20;
		
			if($category){
					$this->db->where('place_category',$category);
			}
		
			$query = $this->db->get($this->place_table,$page_count,($page-1)*$page_count);
		
			$place_datas=$query->result_array();
		
			$query->free_result();
		
			if($place_datas){
				foreach($place_datas as $key=>$value){
					$place_metas=$this->get_place_metas_by_place_id($value['place_id']);
					if($place_metas){
						foreach($place_metas as $meta){
							$value[$meta['meta_key']]=$meta['meta_value'];
						}
					}
					$place_datas[$key]=$value;
				}
				return $place_datas;
			}
			else
				return FALSE;
		}
		
		/**
		 * 增加地点内用户数
		 * @param unknown_type $place_id
		 */
		function increase_place_member_count($place_id){
			$this->db->set('member_count',"member_count+1",FALSE);
			$this->db->where(array('place_id'=>$place_id));
			$this->db->update($this->place_table);
		}
	}