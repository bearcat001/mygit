<?php 
	class c_register extends CI_Controller{
			
		private $rpc_url;
		private $base_url;
		private $ajax_url;
		private $user_data;
		
		function __construct(){
			parent::__construct();
			$this->load->library('session');
			$this->rpc_url=base_url('xmlrpc');
			$this->base_url=base_url();
			$this->ajax_url=base_url("mobile/c_wlinke/wlinke_ajax/");
			$this->load->helper('date');
			$this->user_data=unserialize($this->session->userdata('user_data'));
		}

		function index(){
			$this->load->view('mobile/v_header');
			$this->load->view('mobile/v_register');
			$this->load->view('mobile/v_footer');
		}
		
		function register_submit(){
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
				redirect('mobile/c_page');
			}else{
				$this->register_faild($user_data['error']);
			}		
		}
		
		function register_faild($error){
			$data['error_title']="注册失败";
			switch($error){
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
			$this->load->view('mobile/v_header');
			$this->load->view('mobile/v_error_dialog',$data);
			$this->load->view('mobile/v_footer');
		}
		
		function is_user_login($token){
			$this->load->helper('ixr_xmlrpc');
			$rpc = new IXR_Client( $this->rpc_url );
			$status = $rpc->query(
					'we.sayHello',
					$token
			);
			if($rpc->getResponse())
				return TRUE;
			else
				return FALSE;
		}
	}