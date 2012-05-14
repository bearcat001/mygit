<?php
	class M_album extends CI_Model{
		private $album_table;
		
		private $picture_table;
		
		/**
		 * 构造函数 
		 */
		function __construct(){
			parent::__construct();
			$this->album_table=$this->db->dbprefix('album');
			$this->picture_table=$this->db->dbprefix('picture');
		}
		
		/**
		 * 创建一个相册
		 * @param array $data(album_name,user_id,create_time,picture_count,album_appearance,album_visible,album_password)
		 */
		function insert_album($data){
			$this->db->insert($this->album_table,$data);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		}
		
		
		/**
		 * 创建一个图片
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_picture($data){
			$this->db->insert($this->picture_table,$data);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		}
		
		/**
		 * 从相册表中获得用户某相册id
		 * @param unknown_type $user_id
		 * @param unknown_type $album_name
		 */
		function get_user_album_id($user_id,$album_name='default'){
			$this->db->select('album_id');
			$this->db->from($this->album_table);
			$this->db->where(array(
					'user_id'=>$user_id,
					'album_name'=>$album_name
					));
			
			$query=$this->db->get();
			$num=$query->row();
			$query->free_result();
			return $num->album_id;
		}
		
		/**
		 * 根据相册ID获得用户ID
		 * @param unknown_type $album_id
		 * @return unknown
		 */
		function get_user_id_by_album_id($album_id){
			$this->db->where('album_id',$album_id);
			$query = $this->db->get($this->album_table);
			$user_id=$query->row();
			$query->free_result();
			return $user_id;
		}
		
		
		
	
	}