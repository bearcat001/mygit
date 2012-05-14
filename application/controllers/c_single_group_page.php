<?php
	class c_single_group_page extends CI_Controller{
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
						'10'
				);
				$group_datas=$rpc->getResponse();
				if(!array_key_exists('error', $group_datas[0])){
					$this->user_data['group_datas']=$group_datas;
				}
				$data['user_data']=$this->user_data;
				$this->load->view('v_header');
				$this->load->view('v_page_group',$data);
				$this->load->view('v_footer');
			}else{
				redirect('c_login');
			}
		}
		
		function group($group_id){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				//获得历史发现的用户
				$status = $rpc->query(
						'we.getGroupDatasbyUserId',
						$this->user_data['token'],
						'',
						'1',
						'5'
				);
				$group_datas=$rpc->getResponse();
				if(!array_key_exists('error', $group_datas[0])){
					$this->user_data['group_datas']=$group_datas;
				}
				$status = $rpc->query(
						'we.getGroupDatabyGroupId',
						$this->user_data['token'],
						$group_id
				);
				$this_group_data=$rpc->getResponse();
				if(!array_key_exists('error', $this_group_data)){
					$this->user_data['this_group_data']=$this_group_data;
				}
				$data['user_data']=$this->user_data;
				$this->load->view('v_header');
				$this->load->view('v_single_group_page',$data);
				$this->load->view('v_footer');
			}else{
				redirect('c_login');
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