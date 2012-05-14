<?php 
	class c_page extends CI_Controller{
			
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
				$feeds=$rpc->getResponse();
				if(!array_key_exists('error', $feeds[0])){
					$this->user_data['feeds']=$feeds;
				}
				$data['user_data']=$this->user_data;
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_page_weibo',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function goto_ppt(){
			$this->load->view('mobile/v_page_ppt');
		}
		
		function page_private(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$data['user_data']=$this->user_data;
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_page_private',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function page_friend(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.getAllUserData',
						$this->user_data['token'],
						'all'
				);
				$user_datas=$rpc->getResponse();
				if(!array_key_exists('error', $user_datas[0])){
					$this->user_data['all_user_datas']=$user_datas;
				}
				$data['user_data']=$this->user_data;
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_page_friend',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function page_group(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.getGroupDatasbyCategory',
						$this->user_data['token'],
						'0'
				);
				$group_data=$rpc->getResponse();
				if(!array_key_exists('error', $group_data[0])){
					$this->user_data['group_data']=$group_data;
				}
				$data['user_data']=$this->user_data;
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_page_group',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function page_place(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.getPlaceDatasbyCategory',
						$this->user_data['token'],
						'0'
				);
				$place_data=$rpc->getResponse();
				if(!array_key_exists('error', $place_data[0])){
					$this->user_data['place_data']=$place_data;
				}
				$data['user_data']=$this->user_data;
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_page_place',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function page_near(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.getNearbyBluetoothDatas',
						$this->user_data['token'],
						''
				);
				$user_datas=$rpc->getResponse();
				if(!array_key_exists('error', $user_datas[0])){
					$this->user_data['near_user_data']=$user_datas;
				}
				
				$data['user_data']=$this->user_data;
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_page_near',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function add_group(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$data['user_data']=$this->user_data;
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_add_group',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function add_gourp_submit(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.addGroup',
						$this->user_data['token'],
						$this->input->post('group_name'),
						$this->input->post('group_destription'),
						$this->input->post('group_category')
				);
				if(is_array($rpc->getResponse())){
					redirect('mobile/c_page/page_group');
				}
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function add_place(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$data['user_data']=$this->user_data;
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_add_place',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function add_place_submit(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.addPlace',
						$this->user_data['token'],
						$this->input->post('place_name'),
						$this->input->post('place_destription'),
						$this->input->post('place_category'),
						"111111111111"
				);
				if(is_array($rpc->getResponse())){
					redirect('mobile/c_page/page_place');
				}
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function single_weibo($feed){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$data['feed']=unserialize(base64_decode(str_replace(array('-','_'),array('+','/'),$feed)));
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_single_weibo',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function single_user($feed){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$data['feed']=unserialize(base64_decode(str_replace(array('-','_'),array('+','/'),$feed)));
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_single_user',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function single_group($group){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$data['group']=unserialize(base64_decode(str_replace(array('-','_'),array('+','/'),$group)));
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_single_group',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function single_place($place){
			
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$data['place']=unserialize(base64_decode(str_replace(array('-','_'),array('+','/'),$place)));
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.addPlaceMember',
						$this->user_data['token'],
						$data['place']['place_id']
				);
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.getPlaceRecentMembers',
						$this->user_data['token'],
						$data['place']['place_id']
				);
				$data['place']['place_recent_member']=$rpc->getResponse();
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_single_place',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
			
		}
		
		function comment_weibo($weibo_id){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$data['input_title']="发表评论";
				$data['input_action_url']=base_url("mobile/c_page/comment_weibo_submit/".$weibo_id);
				$data['input_prompt']="请输入评论内容";
				$data['user_data']=$this->user_data;
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_input_dialog',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function comment_weibo_submit($weibo_id){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.commentWeibo',
						$this->user_data['token'],
						$weibo_id,
						$this->input->post('input_content')
				);
				if(is_array($rpc->getResponse())){
					redirect('mobile/c_page');
				}
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function transpond_weibo($weibo_id){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$data['input_title']="转发微博";
				$data['input_action_url']=base_url("mobile/c_page/transpond_weibo_submit/".$weibo_id);
				$data['input_prompt']="请输入附加内容";
				$data['user_data']=$this->user_data;
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_input_dialog',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function transpond_weibo_submit($weibo_id){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.transpondWeibo',
						$this->user_data['token'],
						$weibo_id,
						$this->input->post('input_content'),
						'public'
				);
				if(is_array($rpc->getResponse())){
					redirect('mobile/c_page');
				}
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function post_weibo(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$data['input_title']="发布微博";
				$data['input_action_url']=base_url("mobile/c_page/post_weibo_submit");
				$data['input_prompt']="请输入微博内容";
				$data['user_data']=$this->user_data;
				$this->load->view('mobile/v_header');
				$this->load->view('mobile/v_input_dialog',$data);
				$this->load->view('mobile/v_footer');
			}else{
				redirect('mobile/c_login');
			}
		}
		
		function post_weibo_submit(){
			if($this->user_data&&$this->is_user_login($this->user_data['token'])){
				$this->load->helper('ixr_xmlrpc');
				$rpc = new IXR_Client( $this->rpc_url );
				$status = $rpc->query(
						'we.postWeibo',
						$this->user_data['token'],
						$this->input->post('input_content'),
						"public"
				);
				if(is_array($rpc->getResponse())){
					redirect('mobile/c_page');
				}
			}else{
				redirect('mobile/c_login');
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