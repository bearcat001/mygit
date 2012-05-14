<?php
	/**
	 * 处理New Feeds
	 * 
	 * 这个model是用来处理feed的
	 * @author Yan Su
	 *
	 */
	/**
	 * 这个类主要包含了对feed的表的操作
	 * 
	 * @author Yan Su
	 *
	 */
	class M_feed extends CI_Model{
		/**
		 * @access private
		 * @var string
		 */
		private $feed_table;
		
		/**
		 * 构造函数 
		 * 
		 * 包括对父类构造函数的实现和表的创建
		 */
		function __construct(){
			parent::__construct();
			$this->feed_table=$this->db->dbprefix('feed');
		}
			
		/**
		 * 创建一条feed
		 * 
		 * feed内容包含
		 * feed_id：feed的唯一标示
		 * user_id：本条feed的作者ID
		 * feed_type：feed的属性
		 * 0 状态
		 * 1 日志
		 * 
		 * feed_content：feed的具体内容
		 * create_time：创建时间
		 * transpond_id：转载来自
		 * transpond_count：被转载次数
		 * comment_count：评论的次数
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_feed($data){
			$this->db->insert($this->feed_table,$data);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		}
		
		/**
		 * 增加某feed转发数
		 * @param unknown_type $feed_id
		 */
		function increase_transpond_count($feed_id){
			$this->db->set('transpond_count',"transpond_count+1",FALSE);
			$this->db->where(array('feed_id'=>$feed_id));
			$this->db->update($this->feed_table);
		}
		
		function increase_comment_count($feed_id){
			$this->db->set('comment_count',"comment_count+1",FALSE);
			$this->db->where(array('feed_id'=>$feed_id));
			$this->db->update($this->feed_table);
		}
		
		function get_feed_by_feed_id($feed_id){
			$this->db->select("*");
			$this->db->from($this->feed_table);
			$this->db->where('feed_id',$feed_id);
			$query=$this->db->get();
			if($query->num_rows()>0){
				$feed=$query->row_array();
				$query->free_result();
				$feed=array_merge($feed,$this->m_user->get_user_data_by_user_id($feed['user_id']));
				return $feed;
			}else{
				return FALSE;
			}
		}
		
		/**
		 * 获得某个用户的feeds
		 * @param unknown_type $user_id
		 * @param unknown_type $page
		 * @param unknown_type $page_count
		 * @return boolean|unknown
		 */
		function get_feeds_by_user_id($user_id,$relationship,$page,$page_count){
			
			if(!$page)
				$page=1;
			
			if(!$page_count)
				$page_count=20;
			
			$this->db->select('*');
			$this->db->where('user_id',$user_id);
			switch($relationship){
				case 'self':
					break;
				case 'friend':
					$this->db->where_in('visibility',array('friend','public'));
					break;
				case 'stranger':
					$this->db->where('visibility','public');
					break;
			}
			$this->db->order_by("feed_id", "desc");
			$query=$this->db->get($this->feed_table,$page_count,($page-1)*$page_count);
			if($query->num_rows()>0){
				$feeds=$query->result_array();
				$query->free_result();
			}else{
				return FALSE;
			}
			foreach($feeds as $key=>$value){
				if($value['transpond_id']){
					$this->db->select('*')->from($this->feed_table)->where('feed_id',$value['transpond_id']);
					$query=$this->db->get();
					if($query->num_rows()>0){
						$value['source_feed']=$query->row_array();
						$value['source_feed']=array_merge($value['source_feed'],$this->m_user->get_user_data_by_user_id($value['source_feed']['user_id']));
						$query->free_result();
					}else{
						return FALSE;
					}
					$feeds[$key]=$value;
				}
			}
			return $feeds;
		}
		
		/**
		 * 获得所有好友的最新状态
		 * @param integer $friend_ids
		 * @param integer $page
		 * @param integer $page_count
		 * @return boolean|array
		 */
		function get_feeds_by_friends_ids($friend_ids,$page,$page_count){
			if(!$page)
				$page=1;
			
			if(!$page_count)
				$page_count=20;
			
			$this->db->select('*');
			$this->db->where_in('visibility',array('friend','public'));
			$this->db->where_in('user_id',$friend_ids);
			$this->db->order_by('feed_id','desc');
			$query=$this->db->get($this->feed_table,$page_count,($page-1)*$page_count);
			if($query->num_rows()>0){
				$feeds=$query->result_array();
				$query->free_result();
			}else{
				return FALSE;
			}
			
			foreach($feeds as $key=>$value){
				$value=array_merge($value,$this->m_user->get_user_data_by_user_id($value['user_id']));
				if($value['transpond_id']){
					$this->db->select('*')->from($this->feed_table)->where('feed_id',$value['transpond_id']);
					$query=$this->db->get();
					if($query->num_rows()>0){
						$value['source_feed']=$query->row_array();
						$value['source_feed']=array_merge($value['source_feed'],$this->m_user->get_user_data_by_user_id($value['source_feed']['user_id']));
						$query->free_result();
					}else{
						return FALSE;
					}
				}
				$feeds[$key]=$value;
			}
			return $feeds;
		}
	
		/**
		 * 获得所有的公共微博
		 * @param unknown_type $filter
		 * @param unknown_type $id
		 * @param unknown_type $page
		 * @param unknown_type $page_count
		 * @return boolean|multitype:
		 */
		function get_all_public_feeds($filter,$id,$page,$page_count){
			
			if(!$page)
				$page=1;
			
			if(!$page_count)
				$page_count=20;
			
			if(!$id){
				$this->db->select_max('feed_id');
				$query = $this->db->get($this->feed_table);
				$row=$query->row();
				$id=$row->feed_id;
				$query->free_result();
				$id++;
			}
			
			$this->db->select('*');
			
			if($filter=='old')
				$this->db->where('feed_id <',$id);
			else
				$this->db->where('feed_id >',$id);
			
			$this->db->order_by("feed_id", "desc");
			
			$query=$this->db->get($this->feed_table,$page_count,($page-1)*$page_count);
			
			if($query->num_rows()>0){
				$feeds=$query->result_array();
				$query->free_result();
			}else{
				return FALSE;
			}
			
			$this->load->model('m_user');
			foreach($feeds as $key=>$value){
				$value=array_merge($this->m_user->get_user_data_by_user_id($value['user_id']),$value);
				if($value['transpond_id']){
					$this->db->select('*')->from($this->feed_table)->where('feed_id',$value['transpond_id']);
					$query=$this->db->get();
					if($query->num_rows()>0){
						$value['source_feed']=$query->row_array();
						$value['source_feed']=array_merge($this->m_user->get_user_data_by_user_id($value['source_feed']['user_id']),$value['source_feed']);
						$query->free_result();
					}else{
						return FALSE;
					}
				}
				$feeds[$key]=$value;
			}
			return $feeds;
			
		}
	}