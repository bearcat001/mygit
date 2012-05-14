<?php 
	class c_login extends CI_Controller{
			
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
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				redirect('mobile/c_page');
			}else{
				$this->load->model('m_user');
				$data['user_ids']=$this->m_user->get_all_user_id(1,5);
				$data['latest_user_data']=$this->m_user->get_user_datas_by_user_ids($data['user_ids']);
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_login',$data);
				$this->load->view('mobile/v_footer');
			}
		}
		
		function login_submit(){
			$login_email=$this->input->post('login_email');
			$login_password=$this->input->post('login_password');
			$this->load->helper('ixr_xmlrpc');
			$rpc = new IXR_Client( $this->rpc_url );
			$status = $rpc->query(
					'we.userLogin',
					$login_email,
					$login_password
			);
			$user_data=$rpc->getResponse();
			if(!array_key_exists('error',$user_data)){
				$this->session->set_userdata('user_data',serialize($user_data));
				redirect('mobile/c_page');
			}else{
				$this->login_faild($user_data['error']);
			}
		}
		
		function login_faild($error){
			$data['error_title']="登陆失败";
			$data['error_message']="用户名或密码错误";
			$this->load->view('mobile/v_header');
			$this->load->view('mobile/v_error_dialog',$data);
			$this->load->view('mobile/v_footer');
		}
		
		function login_out(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->session->sess_destroy();
				redirect('mobile/c_login');
			}else{
				$this->index();
			}
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