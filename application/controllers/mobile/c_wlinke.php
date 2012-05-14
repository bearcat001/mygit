<?php 
	class c_wlinke extends CI_Controller{
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
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.getAllPublicWeibo',
						$this->user_data['token'],
						''
				);
				if(is_array($feeds=$rpc->getResponse())){
					$this->user_data['feeds']=$feeds;
				}
				$status = $rpc->query(
						'we.getAllUserData',
						$this->user_data['token'],
						'all'
				);
				if(is_array($user_datas=$rpc->getResponse())){
					$this->user_data['all_user_datas']=$user_datas;
				}
				$status = $rpc->query(
						'we.getGroupDatasbyUserId',
						$this->user_data['token'],
						'0'
				);
				if(is_array($group_data=$rpc->getResponse())){
					$this->user_data['group_data']=$group_data;
				}
				$this->page();
			}else{
				$this->login();
			}
		}
				
		function login(){
			$this->load->view('mobile/v_header');
			$this->load->view('mobile/v_login');
			$this->load->view('mobile/v_footer');
		}
		
	
		function page(){
			$data['user_data']=$this->user_data;
			$this->load->view('mobile/v_header');
			$this->load->view('mobile/v_page',$data);
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
		
		function wlinke_ajax(){
			$action=$this->input->post('action');
			switch($action){
				case "login":
					$this->load->helper('ixr_xmlrpc');
					$rpc = new IXR_Client( $this->rpc_url );
					$status = $rpc->query(
							'we.userLogin',
							$this->input->post('email'),
							$this->input->post('password')
					);
					if(is_array($user_data=$rpc->getResponse())){
						$this->session->set_userdata('user_data',serialize($user_data));
						echo "success";
					}
					else
						echo $user_data;
					break;
				case "register":
					$this->load->helper('ixr_xmlrpc');
					$rpc = new IXR_Client( $this->rpc_url );
					$status = $rpc->query(
							'we.addUser',
							$this->input->post('email'),
							$this->input->post('password'),
							$this->input->post('real_name'),
							$this->input->post('bluetooth_mac')
					);
					if(is_array($user_data=$rpc->getResponse())){
						$this->session->set_userdata('user_data',serialize($user_data));
						echo "success";
					}
					else
						echo $user_data;
					break;
				case "post_weibo":
					$this->load->helper('ixr_xmlrpc');
					$rpc = new IXR_Client( $this->rpc_url );
					$status = $rpc->query(
							'we.postWeibo',
							$this->input->post('token'),
							$this->input->post('weibo_content'),
							"public"
					);
					if(strchr($rpc->getResponse(),'success')){
						echo "success";
					}
					else
						echo "false";
					break;
				case "get_user_statuses":
					$this->load->helper('ixr_xmlrpc');
					$rpc = new IXR_Client( $this->rpc_url );
					$status = $rpc->query(
							'we.getUserStatuses',
							$this->input->post('token'),
							''
					);
					if(is_array($feeds=$rpc->getResponse())){
						echo serialize($feeds);
					}
					else
						echo $feeds;
					break;
				case "get_public_weibo":
					$this->load->helper('ixr_xmlrpc');
					$rpc = new IXR_Client( $this->rpc_url );
					$status = $rpc->query(
							'we.getAllPublicWeibo',
							$this->input->post('token'),
							$this->input->post('filter'),
							$this->input->post('last_feed_id')
					);
					if(is_array($feeds=$rpc->getResponse())){
						$result="";
						foreach($feeds as $feed){
							$create_time=timespan($feed['create_time'],now()).'前';
							$zhuanfa="转发：  ".$feed['transpond_count']."评论 ：".$feed['comment_count'];
							$result.=<<<EOD
							<li data-role="list-divider" data-theme="c" id="public_feed_{$feed['feed_id']}">
							<div class="ui-grid-a">
							<div class="ui-block-a" style="width:20%;">
							<img height="36" src="{$feed['user_avatar']}">
							</div>
							<div class="ui-block-b" style="width:80%;">
							{$feed['display_name']}
							<div style="text-align:right;"><p>{$create_time}</p></div>
							</div>
							<div class="ui-block-a" style="width:20%;">
							</div>
							<div class="ui-block-b" style="width:80%;">
							{$feed['feed_content']}
							</div>
							<div class="ui-block-a" style="width:20%;">
							</div>
							<div class="ui-block-b" style="width:80%;text-align:right;">
							<br/>
							<div><p>{$zhuanfa}</p></div>
						</div>
					</div>
				</li>
EOD;
				}
				echo $result;
			}else{
				echo $feeds;
			}
			break;
			}
		}
	}