<?php 
	class c_register extends CI_Controller{
			
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

		function index($error=""){
			$data=array();
			if($error)
				$data['error']=$error;
			$this->load->view('v_header');
			$this->load->view('v_register',$data);
			$this->load->view('v_footer');
		}
		
		function register_submit(){
			if($this->input->post('register_password')!=$this->input->post('register_password_again'))
			{
				$this->register_faild("password_not_yes");
				return;
			}
			$this->load->helper('ixr_xmlrpc');
			$rpc = new IXR_Client( $this->rpc_url );
			$status = $rpc->query(
					'we.addUser',
					$this->input->post('register_email'),
					$this->input->post('register_password'),
					$this->input->post('register_real_name'),
					$this->input->post('register_bluetooth_mac')
			);
			$user_data=$rpc->getResponse();
			if(!array_key_exists('error', $user_data)){
				$this->session->set_userdata('user_data',serialize($user_data));
				redirect('c_page_weibo');
			}else{
				$this->register_faild($user_data['error']);
			}		
		}
		
		function register_faild($error){
			$data['error_title']="注册失败";
			switch($error){
				case "password_not_yes":
					$data['error_message']="两次密码不一致";
					break;
				case "invalid_email":
					$data['error_message']="邮箱格式错误";
					break;
				case "existing_email":
					$data['error_message']="邮箱已存在";
					break;
				case "invalid_password":
					$data['error_message']="密码格式错误";
					break;
				case "invalid_real_name":
					$data['error_message']="请输出真实姓名";
					break;
				case "invalid_bluetooth_mac":
					$data['error_message']="蓝牙地址格式错误";
					break;
				case "existing_bluetooth_mac":
					$data['error_message']="蓝牙地址已被注册";
					break;
			}
			$this->index($data['error_message']);
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