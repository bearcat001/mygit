<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
class WE_Error{
    var $error_num;
    var $error_message;
    function __construct(){
       
    }
    function set_error($params){
        $this->error_num=$params[0];
        $this->error_message=$params[1];
    }

    public function we_sigle_error(){
        return array('error'=>$error_message);
    }
    public function we_double_error(){
        return array(array('error'=>$error_message));
    }
}

/* End of file we_error.php */