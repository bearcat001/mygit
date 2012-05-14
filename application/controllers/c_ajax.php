<?php
class c_ajax extends CI_Controller {
	private $rpc_url;
	private $base_url;
	private $user_data;
	
	function __construct() {
		parent::__construct ();
		$this->load->library ( 'session' );
		$this->rpc_url = base_url ( 'xmlrpc' );
		$this->base_url = base_url ();
		$this->load->helper ( 'date' );
		$this->user_data = unserialize ( $this->session->userdata ( 'user_data' ) );
	}
	
	function index() {
		$action = $this->input->post ( 'action' );
		switch ($action) {
			case "userLogin":
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
				if(array_key_exists('user_id',$user_data)){
					echo "yes";
				}else{
					echo "no";
				}
				break;
			case "get_public_weibo":
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.getAllPublicWeibo',
						$this->user_data['token'],
						$this->input->post('filter'),
						$this->input->post('last_feed_id')
				);
				$result=$rpc->getResponse();
				if(!array_key_exists("error",$result[0])){
					$ret="";
					foreach($result as $key=>$value){
						$ret.=<<<EOD
						<div class="span1">
							<img width="50" src="{$value['user_avatar']}" />
						</div>
						<div class="span6">
							<div class="row">
								<div class="span6">
									<a href="javascript:void(0);">{$value['display_name']}</a>:{$value['feed_content']}
								</div>
								<div class="span6"></div>
								<div class="span6"><br></div>
EOD;
								if(isset($value['source_feed'])){
									$transpond_url="transpond_".$value['feed_id']."_".$value['source_feed']['feed_id'];
									$comment_url="comment_".$value['feed_id']."_".$value['source_feed']['feed_id'];
								}else{
									$transpond_url="transpond_".$value['feed_id'];
									$comment_url="comment_".$value['feed_id'];
								}
								if(isset($value['source_feed'])){
								$time=timespan($value['source_feed']['create_time'],now());
								
								$ret.=<<<EOD
									<div class="span5 well">
										<div class="row">
											<div class="span5">
											<a href="javascript:void(0);">{$value['source_feed']['display_name']}</a>:{$value['source_feed']['feed_content']}
											</div>
											<div class="span5"><br></div>
											<div class="span5">
												<div style="float:left;">
													<a href="javascript:void(0);">{$time}前</a>
												</div>
												<div style="float:right;">
													<a data-toggle="modal" href="#{$transpond_url}">转发({$value['source_feed']['transpond_count']})</a> | 
													<a href="javascript:void(0);" data-toggle="collapse" data-target="#{$comment_url}">评论({$value['source_feed']['comment_count']})</a>
												</div>
											</div>
											<!-- 评论对话框 -->
											<div class="span6">
												<div id="{$comment_url}" class="collapse">暂时不能评论哦~！</div>
											</div>
										</div>
									</div>
									<div class="modal fade" id="{$transpond_url}">
										<div class="modal-header">
											<a class="close" data-dismiss="modal">×</a>
											<h3>转发微博</h3>
										</div>
										<div class="modal-body">
											<form action="<?php echo base_url('c_page_weibo/transpond_weibo_submit');?>" method="post">
												<textarea style="width: 98%" class="input-xlarge"
													name="transpond_weibo_content" rows="3">//{$value['display_name']}:{$value['feed_content']}</textarea>
												<input type="hidden" name="transpond_weibo_id"
													value="{$value['source_feed']['feed_id']}" />
												<div style="text-align: right;">
													<button type="submit" class="btn">转发</button>
												</div>
											</form>
										</div>
									</div>
EOD;
								}
								$time=timespan($value['create_time'],now());
								$ret.=<<<EOD
								<div class="span6">
									<div style="float:left;">
										<a href="javascript:void(0);">{$time}前</a>
									</div>
									<div style="float:right;">
										<a data-toggle="modal" href="#{$transpond_url}">转发({$value['transpond_count']})</a> |
										<a href="javascript:void(0);" data-toggle="collapse" data-target="#comment{$value['feed_id']}">评论({$value['comment_count']})</a>
									</div>
								</div>
								<!-- 评论对话框 -->
								<div class="span6">
									<div id="comment{$value['feed_id']}" class="collapse">暂时不能评论哦~！</div>
								</div>
								<!-- 转发对话框 -->
								<div class="modal fade" id="{$transpond_url}">
									<div class="modal-header">
										<a class="close" data-dismiss="modal">×</a>
										<h3>转发微博</h3>
									</div>
									<div class="modal-body">
										<form action="<?php echo base_url('c_page_weibo/transpond_weibo_submit');?>" method="post">
											<textarea style="width: 98%" class="input-xlarge"
												name="transpond_weibo_content" rows="3" placeholder="转发微博"></textarea>
											<input type="hidden" name="transpond_weibo_id"
												value="{$value['feed_id']}" />
											<div style="text-align: right;">
												<button type="submit" class="btn">转发</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="span7"><hr size="1" style="border:1px solid #bbb;"></div>
						<div id="feed_id{$value['feed_id']}" style="visibility:hidden; display:none;">{$value['feed_id']}</div>
EOD;
					}	
					echo $ret;
				}
				else
					echo "no";
				break;
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

