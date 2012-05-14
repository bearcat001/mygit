<?php 
	class c_ajax extends CI_Controller{
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
			$action=$this->input->post('action');
			switch($action){
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
