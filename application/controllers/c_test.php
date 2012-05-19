<?php
class c_test extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
	}
	
	function index($args="") {
        $user_data=array(
                         'user_name'=>'aaa',
                         'user_password'=>'bbb'
                         );
        /* $user_data=serialize($user_data); */
        $this->session->set_userdata('user_data',$user_data);
        var_dump($this->session->userdata('session_id'));
        var_dump($this->session->all_userdata());
	}
    
    function getUserDatabySession(){
        $this->load->model('m_wlinke');
        var_dump($this->m_wlinke->get_user_data_by_session());
    }

    function isUserLogin($token=""){
        $this->load->model('m_wlinke');
        var_dump($this->m_wlinke->is_user_login());
    }
    
    function userLogin($email="",$password=""){
        $this->load->model('m_wlinke');
        var_dump($this->m_wlinke->user_login($email,$password));
    }
    function addUser($email="test1@163.com",$password="111111",$real_name="test1",$bluetooth_mac="111111111111",$bluetooth_name="test1"){
        $this->load->model('m_wlinke');
        var_dump($this->m_wlinke->add_user($email,$password,$real_name,$bluetooth_mac,$bluetooth_name));
        
    }

    function getUserRealNamebyDisplayName($display_name){
        $this->load->model('m_user');
        var_dump( $this->m_user->get_user_real_name_by_display_name($display_name));
    }
}