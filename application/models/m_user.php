<?php
	/**
	 * 
	 * @author Yan Su
	 * 这个是用户model，用来处理用户表的操作信息，并且进行session处理，在这个
	 * 类中，所有函数只返回TRUE或FALSE
	 *
	 */
	class M_user extends CI_Model{

		private $user_table;
		
		private $user_meta_table;
		
		private $session_table;
		
		private $bluetooth_table;
		
		private $blutooth_search_table;
		
		/**
		 * 构造函数 
		 */
		function __construct(){
			parent::__construct();
			$this->user_table=$this->db->dbprefix('user');
			$this->session_table=$this->db->dbprefix('session');
			$this->user_meta_table=$this->db->dbprefix('user_meta');
			$this->bluetooth_table=$this->db->dbprefix('bluetooth');
			$this->bluetooth_search_table=$this->db->dbprefix('bluetooth_search');
		}

		/**
		 * 添加一个用户数据
		 * @param array $data
		 * @return string|Ambigous <string, boolean>
		 */
		function insert_user($data){
			$this->db->insert($this->user_table,$data);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
		}
        
        /**
         * 删除一个用户数据
         * @param int $user_id
         * @return boolean
         */
        function delete_user($user_id){
            $this->db->delete($this->user_table,array('user_id'=>intval($user_id)));
            return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
        }

		/**
		 * 增加用户额外设定
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_user_meta($user_id,$meta_key,$meta_value){
			$user_meta_id=$this->get_user_meta_id_by_key($user_id,$meta_key);
			
			if($user_meta_id){
				$this->db->update(
						$this->user_meta_table,
						array('meta_value'=>$meta_value),
						array('user_meta_id'=>$user_meta_id)
				);
				return TRUE;
			}else{
				$this->db->insert(
						$this->user_meta_table,
						array(
								'user_id'=>$user_id,
								'meta_key'=>$meta_key,
								'meta_value'=>$meta_value
								));
				return TRUE;
			}
		}

        function add_user_metas($user_id,$meta_datas){
            foreach($meta_datas as $key=>$value){
                if(!$this->add_user_meta($user_id,$key,$value))
                    return false;
            }
            return true;
        }
            
		
		function increase_user_meta($user_id,$meta_key){	
			$this->db->set('meta_value','meta_value+1',FALSE);
			$this->db->where(array('user_id'=>$user_id,'meta_key'=>$meta_key));
			$this->db->update($this->user_meta_table);
		}
		
		function decrease_user_meta($user_id,$meta_key){
			$this->db->set('meta_value','meta_value-1',FALSE);
			$this->db->where(array('user_id'=>$user_id,'meta_key'=>$meta_key));
			$this->db->update($this->user_meta_table);
		}

		/**
		 * 增加蓝牙模块
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_bluetooth($data){
			//根据蓝牙地址获得蓝牙ID
			$bluetooth_id=$this->get_bluetooth_id_by_mac($data['bluetooth_mac']);
			log_message("error",serialize($data));
			log_message("error",$bluetooth_id);
			//如果有此蓝牙并且蓝牙已发现过
			if($bluetooth_id&&isset($data['user_id'])){
				$this->db->update(
						$this->bluetooth_table,
						array('user_id'=>$data['user_id'],'bluetooth_name'=>$data['bluetooth_name']),
						array('bluetooth_id'=>$bluetooth_id)
						);
				return $bluetooth_id;
			}else if($bluetooth_id&&isset($data['bluetooth_name'])&&$data['bluetooth_name']){
				$this->db->update(
						$this->bluetooth_table,
						array('bluetooth_name'=>$data['bluetooth_name']),
						array('bluetooth_id'=>$bluetooth_id)
				);
				return $bluetooth_id;
			}else if($bluetooth_id) {
				return $bluetooth_id;
			}else{
				$this->db->insert($this->bluetooth_table,$data);
				return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
			}
				
		}		

		/**
		 * 增加蓝牙的发现关系
		 * @param unknown_type $data
		 * @return boolean
		 */
		function add_bluetooth_search($data){
			$this->db->insert(
				$this->bluetooth_search_table,
				array(
						'from_id'=>$data['from_id'],
						'to_id'=>$data['to_id'],
						'rssi'=>$data['rssi'],
						'create_time'=>$data['create_time']
				));
			$this->db->insert(
				$this->bluetooth_search_table,
				array(
						'from_id'=>$data['to_id'],
						'to_id'=>$data['from_id'],
						'rssi'=>$data['rssi'],
						'create_time'=>$data['create_time']
				));
			return TRUE;
		}
		
		/**
		 * 根据蓝牙数组增加蓝牙的发现关系
		 * @param unknown_type $user_id
		 * @param unknown_type $bluetooth_datas
		 * @return boolean
		 */
		function add_bluetooth_searchs_by_bluetooth_datas($bluetooth_id,$bluetooth_datas){
			
			$bluetooth_ids=$this->get_bluetooth_ids_by_bluetooth_datas($bluetooth_datas);
			
			$length=count($bluetooth_ids);
			for($i=0;$i<$length;$i++){
				$this->add_bluetooth_search(array(
						'from_id'=>$bluetooth_id,
						'to_id'=>$bluetooth_ids[$i],
						'rssi'=>$bluetooth_datas[$i][2],
						'create_time'=>time()
						));
			}
			return TRUE;
		}

		/**
		 * 根据当前时间和用户蓝牙ID获得发现的蓝牙ID
		 * @param unknown_type $bluetooth_id
		 * @param unknown_type $current_time
		 * @return multitype:
		 */
		function get_bluetooth_ids_by_search_time($bluetooth_id,$current_time){
			
			$bluetooth_ids=array();
			
			$create_time=$current_time-300;
			
			$this->db->select('distinct to_id',FALSE)->from($this->bluetooth_search_table);
			
			$this->db->where(array(
					'from_id'=>$bluetooth_id,
					'create_time >' =>$create_time
					));
			
			$query=$this->db->get();
			
			$bluetooth_ids=$query->result_array();
			
			foreach($bluetooth_ids as $key => $value){
				$bluetooth_ids[$key]=$value['to_id'];
			}
			
			$query->free_result(); 
			
			return $bluetooth_ids;
			
		}
		
		/**
		 * 根据当前时间和用户蓝牙ID获得发现的蓝牙发现数据
		 * @param unknown_type $bluetooth_id
		 * @param unknown_type $current_time
		 * @return unknown
		 */
		function get_bluetooth_search_datas_by_search_time($bluetooth_id,$current_time){
			$bluetooth_datas=array();
			
			$create_time=$current_time-300;
			
			$this->db->select('to_id, MAX( create_time ) AS create_time, COUNT( to_id ) AS search_count',FALSE);
			
			$this->db->from($this->bluetooth_search_table);
			
			$this->db->where(array(
					'from_id'=>$bluetooth_id,
					'create_time >' =>$create_time
			));
			
			$this->db->group_by('to_id');
			
			$this->db->order_by('create_time','desc');
			
			$query=$this->db->get();
			
			$bluetooth_datas=$query->result_array();
			
			$query->free_result();
			
			return $bluetooth_datas;
		}
		
		function get_double_bluetooth_search_datas_by_search_time($bluetooth_id,$current_time){
			$bluetooth_datas=array();
			
			$create_time=$current_time-300;
			
			$query=$this->db->query("
					SELECT to_id, MAX( create_time ) AS create_time, COUNT( to_id ) AS search_count
					FROM  $this->bluetooth_search_table
					WHERE to_id <> $bluetooth_id
					AND `create_time` > $create_time
					AND from_id
					IN (
					SELECT `to_id`
					FROM  $this->bluetooth_search_table 
					WHERE `from_id` =$bluetooth_id
					AND `create_time` > $create_time
					)
					GROUP BY  `to_id`  
					UNION 
					SELECT to_id, MAX( create_time ) AS create_time, COUNT( to_id ) AS search_count
					FROM  $this->bluetooth_search_table
					WHERE from_id = $bluetooth_id
					AND `create_time` > $create_time
					GROUP BY  `to_id` 
					ORDER BY create_time DESC 
			");
			
			
			$bluetooth_datas=$query->result_array();
			
			$query->free_result();
			
			return $bluetooth_datas;
		}
		
		function get_bluetooth_search_datas_by_bluetooth_id($bluetooth_id,$sort,$page,$page_count){
			$bluetooth_datas=array();
			
			$this->db->select('to_id, MAX( create_time ) AS create_time, COUNT( to_id ) AS search_count',FALSE);
			
			$this->db->where('from_id',$bluetooth_id);
			
			$this->db->group_by('to_id');
			
			$this->db->order_by($sort,'desc');
			
			$query=$this->db->get($this->bluetooth_search_table,$page_count,($page-1)*$page_count);
			
			$bluetooth_datas=$query->result_array();
			
			$query->free_result();
			
			return $bluetooth_datas;
		}
		
		function get_user_datas_by_bluetooth_search_datas($bluetooth_datas){
			
			$user_datas=array();
			
			foreach($bluetooth_datas as $value){
				$user_data=$this->get_user_data_by_bluetooth_id($value['to_id']);
				if(!$user_data){
					$user_data['user_id']=0;
					$user_data['bluetooth_id']=$value['to_id'];
					$user_data['display_name']=$this->get_bluetooth_name_by_bluetooth_id($value['to_id']);
					$user_data['real_name']=$user_data['display_name'];
					$user_data['last_activity']=time();
					$user_data['user_avatar']=base_url('upload/default.jpg');
					$user_data['friend_count']=0;
					$user_data['weibo_count']=0;
					$user_data['latest_update']="ta还没有注册蜗邻客";
				}
				$user_data['last_search_time']=$value['create_time'];
				$user_data['search_count']=$value['search_count'];
				$user_datas[]=$user_data;
			}
			return $user_datas;
						
		}
		
		/**
		 * 根据蓝牙IDS获得相应用户的数据
		 * @param unknown_type $bluetooth_ids
		 * @return multitype:number
		 */
		function get_user_datas_by_bluetooth_ids($bluetooth_ids){
			
			$user_datas=array();
			
			foreach($bluetooth_ids as $value){
				$user_data=$this->get_user_data_by_bluetooth_id($value);
				if(!$user_data){
					$user_data['user_id']=0;
					$user_data['bluetooth_id']=$value;
					$user_data['display_name']=$this->get_bluetooth_name_by_bluetooth_id($value);
					$user_data['real_name']=$user_data['display_name'];
					$user_data['last_activity']=time();
					$user_data['user_avatar']=base_url('upload/default.jpg');
					$user_data['friend_count']=0;
					$user_data['weibo_count']=0;
					$user_data['latest_update']="我还没有注册蜗邻客，快来邀请我吧！";
				}
				$user_datas[]=$user_data;
			}
			return $user_datas;
			
		}

		/**
		 * 获得用户的个人设定ID
		 * @param unknown_type $user_id
		 * @param unknown_type $meta_key
		 */
		function get_user_meta_id_by_key($user_id,$meta_key){
			$this->db->select('user_meta_id')->from($this->user_meta_table);
			$this->db->where(array(
					'user_id'=>$user_id,
					'meta_key'=>$meta_key
					));
			$query = $this->db->get();
			$num = $query->row();
			$query->free_result();
			if($num)
				return intval($num->user_meta_id);
			else 
				return FALSE;
		}
		
		/**
		 * 获得用户个人设定的值
		 * @param unknown_type $user_id
		 * @param unknown_type $meta_key
		 * @return boolean
		 */
		function get_user_meta_value_by_key($user_id,$meta_key){
			$this->db->select('meta_value')->from($this->user_meta_table);
			$this->db->where(array(
					'user_id'=>$user_id,
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
		
		/**
		 * 获得用户的所有附加值
		 * @param unknown_type $user_id
		 * @return unknown|boolean
		 */
		function get_user_metas_by_user_id($user_id){
			$this->db->select('meta_key,meta_value')->from($this->user_meta_table);
			$this->db->where('user_id',$user_id);
			$query = $this->db->get();
			$row = $query->result_array();
			$query->free_result();
			if($row)
				return $row;
			else
				return FALSE;
		}
		
		/**
		 * 根据蓝牙地址获得蓝牙ID
		 * @param unknown_type $bluetooth_mac
		 */
		function get_bluetooth_id_by_mac($bluetooth_mac){
			$this->db->select('bluetooth_id')->from($this->bluetooth_table);
			$this->db->where('bluetooth_mac',$bluetooth_mac);
			$query = $this->db->get();
			$num = $query->row();
			$query->free_result();
			if($num)
				return intval($num->bluetooth_id);
			else
				return FALSE;
		}
		
		/**
		 * 根据蓝牙地址和用户ID获得蓝牙编号
		 * @param unknown_type $bluetooth_mac
		 * @param unknown_type $user_id
		 * @return number|boolean
		 */
		function get_bluetooth_id_by_mac_and_user_id($bluetooth_mac,$user_id){
			$this->db->select('bluetooth_id')->from($this->bluetooth_table);
			$this->db->where('bluetooth_mac',$bluetooth_mac);
			$this->db->where('user_id',$user_id);
			$query = $this->db->get();
			$num = $query->row();
			$query->free_result();
			
			if($num)
				return intval($num->bluetooth_id);
			else
				return FALSE;
		}
		
		/**
		 * 根据发现的蓝牙数据获得蓝牙相应的ID
		 * 
		 * $bluetooth_datas[]=array('bluetooth_mac','bluetooth_name','rssi')
		 * 
		 * @param array $bluetooth_datas
		 * @return array $bluetooth_ids
		 */
		function get_bluetooth_ids_by_bluetooth_datas($bluetooth_datas){
			
			$bluetooth_ids=array();
			
			while($bluetooth_datas){
				$bluetooth_data=array_shift($bluetooth_datas);
				$bluetooth_id=$this->add_bluetooth(array(
						'bluetooth_mac'=>$bluetooth_data[0],
						'bluetooth_name'=>$bluetooth_data[1],
						'create_time'=>time()
					));
				
				$bluetooth_ids[]=$bluetooth_id;
			}
			
			return $bluetooth_ids;
		}
		
		/**
		 * 根据发现关系获得关系ID
		 * @param unknown_type $from_id
		 * @param unknown_type $to_id
		 * @return boolean
		 */
		function get_bluetooth_search_id_by_bluetooth_id($from_id,$to_id){
			
			$this->db->select('bluetooth_search_id')->from($this->bluetooth_search_table);
				
			$this->db->where(array('from_id'=>$from_id,'to_id'=>$to_id));
			
			$query = $this->db->get();
			$num = $query->row();
			$query->free_result();
			
			if($num)
				return intval($num->bluetooth_search_id);
			else
				return FALSE;
		}
		
		/**
		 * 根据蓝牙mac获取用户的ID
		 * @param unknown_type $bluetooth_mac
		 * @return boolean
		 */
		function get_user_id_by_bluetooth_mac($bluetooth_mac){
			$this->db->select('user_id')->from($this->user_table);
			$this->db->where('bluetooth_id',
					'(SELECT bluetooth_id FROM '.$this->bluetooth_table.' WHERE bluetooth_mac="'.$bluetooth_mac.'")',
					FALSE);
			$query=$this->db->get();
			$num=$query->row();
			$query->free_result();
			
			if($num)
				return $num->user_id;
			else
				return FALSE;
		}
		
		/**
		 * 根据用户id获得用户的资料
		 * @param unknown_type $user_id
		 * @return unknown|multitype:unknown
		 */
		function get_user_data_by_user_id($user_id){
			
			$user_data=array();
			
			$this->db->select('display_name,real_name,last_activity,create_time')->from($this->user_table);
			$this->db->where('user_id',$user_id);
			$query = $this->db->get();
			$row = $query->row_array();
			$query->free_result();
			
			if(!$row)
				return FALSE;
			$user_data['user_id']=$user_id;
			$user_data['real_name']=$row['real_name'];
			$user_data['display_name']=$row['display_name'];
			$user_data['last_activity']=$row['last_activity'];
			$user_data['create_time']=$row['create_time'];
			$user_metas=$this->get_user_metas_by_user_id($user_id);
			if($user_metas){
				foreach($user_metas as $value){
					$user_data[$value['meta_key']]=$value['meta_value'];
				}
			}
			return $user_data;
		}

		function get_user_data_by_display_name($display_name){
		
			$user_data=array();
		
			$this->db->select('user_id,display_name,real_name,last_activity,create_time')->from($this->user_table);
			$this->db->like('display_name', $display_name);
			$query = $this->db->get();
			$row = $query->result_array();
			$query->free_result();
		
			if(!$row)
				return FALSE;
			foreach($row as $key=>$value){
				$user_data=array();
				$user_data['user_id']=$value['user_id'];
				$user_data['real_name']=$value['real_name'];
				$user_data['display_name']=$value['display_name'];
				$user_data['last_activity']=$value['last_activity'];
				$user_data['create_time']=$value['create_time'];
				$user_metas=$this->get_user_metas_by_user_id($user_data['user_id']);
				if($user_metas){
					foreach($user_metas as $meta_value){
						$user_data[$meta_value['meta_key']]=$meta_value['meta_value'];
					}
				}
				$row[$key]=$user_data;
			}
			return $row;
		}
		
		/**
		 * 由用户IDS获得用户数据
		 * @param unknown_type $user_ids
		 * @return multitype:Ambigous <unknown, multitype:unknown, multitype:unknown_type unknown >
		 */
		function get_user_datas_by_user_ids($user_ids){
			$user_datas=array();
			if($user_ids){
				foreach($user_ids as $value){
					$user_data=$this->get_user_data_by_user_id($value);
					$user_datas[]=$user_data;
				}
			}
			return $user_datas;
			
		}
		
		/**
		 * 获得所有用户的id
		 * @return multitype:number
		 */
		function get_all_user_id($page=1,$page_count=20){
			$this->db->select('user_id');
			$this->db->order_by("user_id", "desc");
			$query=$this->db->get($this->user_table,$page_count,($page-1)*$page_count);
			if($query->num_rows()>0){
				$user_ids=$query->result_array();
				$query->free_result();
				foreach ($user_ids as $key=>$value){
					$user_ids[$key]=$value['user_id'];
				}
				return $user_ids;
			}else{
				return FALSE;
			}
		}
		
		/**
		 * 根据蓝牙ID获得用户的资料
		 * @param unknown_type $bluetooth_id
		 * @return unknown|multitype:unknown
		 */
		function get_user_data_by_bluetooth_id($bluetooth_id){
		
			$user_data=array();
			
			$this->db->select('user_id,bluetooth_id,display_name,real_name,last_activity')->from($this->user_table);
			$this->db->where('bluetooth_id',$bluetooth_id);
			$query = $this->db->get();
			$row = $query->row_array();
			$query->free_result();
			
			if($row){
				$user_data['user_id']=$row['user_id'];
				$user_data['bluetooth_id']=$row['bluetooth_id'];
				$user_data['real_name']=$row['real_name'];
				$user_data['display_name']=$row['display_name'];
				$user_data['last_activity']=$row['last_activity'];
				$user_metas=$this->get_user_metas_by_user_id($row['user_id']);
				if($user_metas){
					foreach($user_metas as $value){
						$user_data[$value['meta_key']]=$value['meta_value'];
					}
				}
				return $user_data;
			}
			else{
				return FALSE;
			}
			
		}		
		
		/**
		 * 根据蓝牙ID获得蓝牙名称
		 * @param unknown_type $bluetooth_id
		 * @return string
		 */
		function get_bluetooth_name_by_bluetooth_id($bluetooth_id){
			$this->db->select('bluetooth_name')->from($this->bluetooth_table);
			$this->db->where('bluetooth_id',$bluetooth_id);
			$query=$this->db->get();
			$row=$query->row();
			$query->free_result();
			
			if($row)
				return $row->bluetooth_name;
			else 
				return FLASE;
		}
        
        /**
         * 根据用户昵称获得用户真实姓名
         * @param string
         * @return string
         */
        function get_user_real_name_by_display_name($display_name){
            //获得用户即将获得的ID
            $query=$this->db->query(
                             'SHOW TABLE STATUS FROM '.$this->config->item("database")
                             .' LIKE "'.$this->user_table.'"');
            $user_id=$query->row_array();
            if($user_id){
                return $display_name."(".$user_id['Auto_increment'].")";
            }else{
                return false;
            }
        }

		/**
		 * 验证用户表中某个内容是否存在
		 * @param string $key
		 * @param string $value
		 * @param int $exclude_uid
		 * @return boolean
		 */
		function check_exist($key = 'email',$value = '', $exclude_user_id = 0)
		{
			if(!empty($value))
			{
				
				if($key=='real_name'){
					$this->db->select('user_id')->from($this->user_table)->like($key,$value.'(','after');
				}else {
					$this->db->select('user_id')->from($this->user_table)->where($key,$value);
				}
				
				if(!empty($exclude_user_id) && is_numeric($exclude_user_id))
				{
					$this->db->where('uid <>', $exclude_user_id);
				}
				
			    $query = $this->db->get();
				$num = $query->num_rows();
				$query->free_result();
				
				if($num)
					return $num;
				else
					return FALSE;
			}
			return FALSE;
		}
		
		/**
		 * 更新用户最后活动时间
		 * @param unknown_type $user_id
		 * @param unknown_type $last_activity
		 */
		function update_last_activity($user_id,$last_activity){
			if($user_id){
				$this->db->update(
						$this->user_table,
						array('last_activity'=>$last_activity),
						array('user_id'=>$user_id)
						);
			}
		}
		
		/**
		 * 验证用户账号和密码
		 * @param string $username 账号
		 * @param string $password 密码
		 * @return Ambigous <boolean, unknown>
		 */
		function validate_user($email,$password)
		{
			$user_data = FALSE;
		
			$this->db->select('*')->from($this->user_table);
			$this->db->where('email', $email);
			$query = $this->db->get();
			
			if($query->num_rows() == 0)
				return FALSE;
			
			$user_data = $query->row_array();

			$user_data = ($user_data['password']===md5($password)) ? $user_data : FALSE;

			$query->free_result();
			
			$user_metas=$this->get_user_metas_by_user_id($user_data['user_id']);
			if($user_metas){
				foreach($user_metas as $value){
					$user_data[$value['meta_key']]=$value['meta_value'];
				}
			}
			unset($user_data['password']);
			return $user_data;
		}

	}
	