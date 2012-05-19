<?php
//核心类，包含XML调用和WEB端调用
class M_Wlinke extends CI_Model{
  
    var $user_data;//当前登录用户的信息
    var $current_time;//当前时间
    var $base_url;//网站跟目录

    function __construct(){
        parent::__construct();//原构造函数
        $this->current_time=time();
        $this->load->model('m_user');//默认载入用户类
        $this->load->helper(array('email','check'));//载入错误处理函数
        $this->base_url=base_url();
    }

    /**
     *   创建一个新用户，输入内容为
     *  string  email  邮箱
     *  string  password  密码
     *  string  real_name  真实姓名
     *  string  bluetooth_mac  蓝牙地址
     *  string  bluetooth_name 蓝牙名称  可选
     */
    function add_user($email,$password,$real_name,$bluetooth_mac="",$bluetooth_name=""){
        //检查邮箱地址
        if(!valid_email($email)){
            $this->load->library('we_error',array(0,'invalid_email'));
            return $this->we_error;
        }
        //检查用户邮箱是否已经被注册
        if($this->m_user->check_exist('email',$email)){
            $this->load->library('we_error',array(0,'existing_email'));
            return $this->we_error;
        }
        $this->user_data['email']=$email;//邮箱
        //检查用户密码格式
        if(!valid_password($password)){
            $this->load->library('we_error',array(0,'invalid_password'));
            return $this->we_error;
        }
        $this->user_data['password']=$password;
 
        //检查用户真实姓名
        if(!valid_real_name($real_name)){
            $this->load->library('we_error',array(0,'invalid_real_name'));
            return $this->we_error;
        }
        $this->user_data['display_name']=$real_name;//真实名字
        //检查蓝牙地址
        if(!$bluetooth_mac||!valid_bluetooth_mac($bluetooth_mac)){
            $this->load->library('we_error',array(0,'invalid_bluetooth_mac'));
            return $this->we_error;
        }
        $bluetooth_mac=strtoupper($bluetooth_mac);//蓝牙地址
        
        //内容初始化完毕，操作数据
        //检查蓝牙地址相应的用户ID，如果不是0，则返回错误，否则可以使用
        $bluetooth_id=$this->m_user->get_user_id_by_bluetooth_mac($bluetooth_mac);
        if($bluetooth_id){
            $this->load->library('we_error',array(0,'existing_bluetooth_mac'));
            return $this->we_error;
        }else{
            $bluetooth_data=array(
                                  'bluetooth_mac'=>$bluetooth_mac,
                                  'bluetooth_name'=>$bluetooth_name,//如果没有蓝牙名称，则空出来
                                  'create_time'=>$this->current_time
                                  );
            $this->user_data['bluetooth_id']=$this->m_user->add_bluetooth($bluetooth_data);
        }
        //加密密码
        $this->user_data['password']=md5($password);//密码
        //设置用户的用来@的名称,即下一个ID及其真实名字联合
        $this->user_data['real_name']=$this->m_user->get_user_real_name_by_display_name($real_name);
        $this->user_data['create_time']=$this->current_time;
        $this->user_data['user_type']='user';
        $this->user_data['last_activity']=$this->current_time;
        //添加用户信息
        $this->user_data['user_id']=$this->m_user->insert_user($this->user_data);
        //如果用户获得的ID与之前估计不同，则删除并报错
        if(!$this->user_data['user_id']){
            $this->load->library('we_error',array(0,'insert_user_error'));
            return $this->we_error;
        }else if(strstr($this->user_data['real_name'],$this->user_data['user_id'])){
            $this->m_user->delete_user($this->user_data['user_id']);
            $this->load->library('we_error',array(0,'insert_user_sequence_error'));
            return $this->we_error;
        }else{//更新蓝牙信息
            $bluetooth_data=array(
                                  'user_id'=>$this->user_data['user_id'],
                                  'bluetooth_mac'=>$bluetooth_mac,
                                  'bluetooth_name'=>$bluetooth_name,
                                  'create_time'=>$this->current_time
                                  );
        }
        //接下来为用户创建默认相册
        $album_data=$this->add_album($this->user_data['user_id'],"默认","用户的默认相册");
        if(is_we_error($album_data)){
            return $album_data;
        }
        $user_metas=array();
        //设置用户默认数据
        $user_metas['user_avatar']=$this->base_url.'upload/default.jpg';
        $user_metas['friend_count']=0;
        $user_metas['weibo_count']=0;
        $user_metas['latest_update']="我刚刚注册了蜗临客,来看看我吧！";
        $this->m_user->add_user_metas($this->user_data['user_id'],$user_metas);
        $this->user_data=array_merge($this->user_data,$user_metas);
        $this->user_data['token']=$this->session->userdata('session_id');
        return $this->user_data;
    }
    
    /**
     * 创建一个用户相册
     * @param int $user_id
     * @param string $album_name
     * @param string $album_password
     * return int $album_id
     */
    function add_album($user_id,$album_name,$album_destription="",$album_password=""){
        //如果用户还没有创建过相册，则需要先为他创建一个文件夹
        if($album_name=='默认'){
            $this->load->helper('album');
            create_user_dir($user_id);
        }
        $this->load->model('m_album');
        $album_data=array(
                          'user_id'=>$user_id,
                          'album_name'=>$album_name,
                          'album_destription'=>$album_destription,
                          'create_time'=>$this->current_time,
                          'picture_count'=>0,
                          'album_visible'=>0,
                          'album_password'=>($album_password?md5($album_password):"")
                          );
        $album_data['album_id']=$this->m_album->insert_album($album_data);
        if($album_data['album_id'])
            return $album_data;
        else{
            $this->load->library('we_error',array(0,'add_album_error'));
            return $this->we_error;
        }
    }

    /**
     * 登录一个用户
     * @param string email
     * @param string password
     * @return array $user_data
     */
    function user_login($email,$password){
        //检查邮箱地址是否正确
        $this->load->library('session');

        if(!valid_email($email)){
            $this->load->library('we_error',array(0,'invalid_email'));
            return $this->we_error;
        }
        $this->load->model('m_user');
        $this->user_data=$this->m_user->validate_user($email,$password);
        if(!$this->user_data){
            $this->load->library('we_error',array(0,'invalid_user_data'));
            return $this->we_error;
        }
        //登录成功后重置session
        $this->session->set_userdata('user_data',$this->user_data);
        //增加一个token字段
        $this->user_data['token']=$this->session->userdata('session_id');
        $this->user_data['relationship']="self";
        return $this->user_data;
    }

    function is_user_login($token=""){
        if($token){
            $this->load->library('session',array('token'=>$token));
            $this->user_data=$this->session->userdata('user_data');
        }else{
            $this->load->library('session');
            $this->user_data=$this->session->userdata('user_data');
        }
        if($this->user_data)
            return $this->user_data;
        else
            return false;
    }
}