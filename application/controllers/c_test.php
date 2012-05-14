<?php
class c_test extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
	}
	
	function index($args="") {
        var_dump(func_get_args());
	}

    function addUser($email="",$password="",$real_name="",$bluetooth_mac="",$bluetooth_name=""){
        $this->load->model('m_wlinke');
        var_dump( $this->m_wlinke->add_user($email,$password,$real_name,$bluetooth_mac,$bluetooth_name));   
    }

    function getUserRealNamebyDisplayName($display_name){
        $this->load->model('m_user');
        var_dump( $this->m_user->get_user_real_name_by_display_name($display_name));
    }
}