<?php 
	class c_login extends CI_Controller{
			
		private $base_url;
		private $user_data;
		
		function __construct(){
			parent::__construct();
			$this->base_url=base_url();
			$this->load->helper('date');
            $this->load->library('session');
			$this->load->model('m_wlinke');
            $this->user_data=$this->m_wlinke->is_user_login();
		}

		function index($error=""){
			if(!is_we_error($this->user_data)){
				redirect('c_page_weibo');
			}else{
				$data['latest_user_data']=$this->m_wlinke->get_recent_register_users_data(6);
				if($error)
					$data['error']=$error;
				$this->load->view('v_header');
				$this->load->view('v_login',$data);
				$this->load->view('v_footer');
			}
		}
		
		function login_submit(){
			$login_email=$this->input->post('login_email');
			$login_password=$this->input->post('login_password');
            $user_data=$this->m_wlinke->user_login($login_email,$login_password);
			if(!is_we_error($user_data)){
				redirect('c_page_weibo');
			}else{
				//提示账号或者密码错误
				$this->index("账号或密码错误");
			}
		}
		
		function login_faild($error){
			$data['error_title']="登陆失败";
			$data['error_message']="用户名或密码错误";
			$this->load->view('mobile/v_header');
			$this->load->view('mobile/v_error_dialog',$data);
			$this->load->view('mobile/v_footer');
		}
		
		function logout_submit(){
			if(!is_we_error($this->user_data)){
				$this->m_wlinke->user_logout();
				redirect('c_login');
			}else{
				$this->index();
			}
		}
	}