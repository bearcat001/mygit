<?php 
	class c_page_weibo extends CI_Controller{
			
		private $rpc_url;
		private $base_url;
		private $user_data;
		
		function __construct(){
			parent::__construct();
			$this->load->library('session');
			$this->rpc_url=base_url('xmlrpc');
			$this->base_url=base_url();
			$this->load->helper('date');
			$this->user_data=unserialize($this->session->userdata('user_data'));
		}

		function index(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				
				//获得最新状态
				$status = $rpc->query(
						'we.getAllPublicWeibo',
						$this->user_data['token'],
						''
				);
				$feeds=$rpc->getResponse();
				if(!array_key_exists('error', $feeds[0])){
					$this->user_data['feeds']=$feeds;
				}
				
				//获得历史发现的用户
				$status = $rpc->query(
						'we.getHistoryNearUserDatas',
						$this->user_data['token'],
						'',
						'1',
						'3'
				);
				$search_user_datas=$rpc->getResponse();
				if(!array_key_exists('error', $search_user_datas[0])){
					$this->user_data['search_user_datas']=$search_user_datas;
				}
				
				$status = $rpc->query(
						'we.getGroupDatasbyCategory',
						$this->user_data['token'],
						'',
						'1',
						'3'
				);
				$group_datas=$rpc->getResponse();
				if(!array_key_exists('error', $group_datas[0])){
					$this->user_data['group_datas']=$group_datas;
				}
				
				$data['user_data']=$this->user_data;
				$this->load->view('v_header');
				$this->load->view('v_page_weibo',$data);
				$this->load->view('v_footer');
			}else{
				redirect('c_login');
			}
		}
		
		function post_weibo(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.postWeibo',
						$this->user_data['token'],
						$this->input->post('weibo_content'),
						"public"
				);
				$result=$rpc->getResponse();
				if(!array_key_exists('error',$result)){
					redirect('c_page_weibo');
				}
			}else{
				redirect('c_login');
			}
		}
		
		function transpond_weibo_submit(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.transpondWeibo',
						$this->user_data['token'],
						$this->input->post('transpond_weibo_id'),
						$this->input->post('transpond_weibo_content'),
						'public'
				);
				if(is_array($rpc->getResponse())){
					redirect('c_page_weibo');
				}
			}else{
				redirect('c_login');
			}
		}
		
		function join_group_submit(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.joinGroup',
						$this->user_data['token'],
						$this->input->post('join_group_id')
				);
				$result=$rpc->getResponse();
				if(!array_key_exists('error',$result)){
					redirect('c_page_weibo');
				}
			}else{
				redirect('c_login');
			}
		}
		
		function upload_avatar() {
			$config['upload_path'] = getcwd()."/upload/".$this->user_data['user_id']."/";
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = '1000';
			$config['max_width']  = '1024';
			$config['max_height']  = '768';
			$config['file_name'] = $this->user_data['user_id'].'_'.time();
			$this->load->library('upload', $config);
		
			if (! $this->upload->do_upload ()) {
				echo '<script>alert("上传失败");</script>';
				redirect("c_page_weibo");
			} else {
				$this->load->model('m_album');
				$upload_data=$this->upload->data();
				$picture_data['album_id']=$this->m_album->get_user_album_id($this->user_data['user_id']);
				$picture_data['user_id']=$this->user_data['user_id'];
				$picture_data['picture_name']="头像";
				$picture_data['picture_destription']="个人头像";
				$picture_data['file_size']=$upload_data['file_size']*1000;
				switch($upload_data['file_ext']){
					case ".gif":$picture_data['file_type']=1;break;
					case ".jpg":$picture_data['file_type']=2;break;
					case ".png":$picture_data['file_type']=3;break;
				}
				$picture_data['create_time']=time();
				$picture_data['file_name']=$upload_data['file_name'];
				$picture_id=$this->m_album->add_picture($picture_data);
				
				$avatar_data=array(
						'user_id'=>$this->user_data['user_id'],
						'meta_key'=>'user_avatar',
						'meta_value'=>base_url('upload')."/".$this->user_data['user_id']."/".$picture_data['file_name']
				);
				$this->load->model('m_user');
				$this->m_user->add_user_meta($avatar_data);
				$config=array();
				$config['image_library'] = 'gd2';
				$config['source_image'] = getcwd()."/upload/".$this->user_data['user_id']."/".$picture_data['file_name'];
				$config['maintain_ratio'] = FALSE;
				$config['width'] = 140;
				$config['height'] = 140;
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				echo '<script>alert("上传成功");</script>';
				redirect("c_page_weibo");
			}
		}
		
		function is_user_login($token){
			$this->load->helper('ixr_xmlrpc');
			$rpc = new IXR_Client( $this->rpc_url );
			$status = $rpc->query(
					'we.sayHello',
					$token
			);
			$user_data=$rpc->getResponse();
			if($user_data){
				$this->session->set_userdata('user_data',serialize($user_data));
				$this->user_data=$user_data;
				return TRUE;
			}else
				return FALSE;
		}
	}