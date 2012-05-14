<?php
//核心类，包含XML调用和WEB端调用
class M_Wlinke extends CI_Model{
  
    var $userdata;
    var $current_time;

    function __construct(){
        parent::__construct();//原构造函数
        $this->current_time=time();
        $this->load->model('m_user');//默认载入用户类
        $this->load->helper(array('we_error','email','check'));//载入错误处理函数
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
            return we_single_error('invalid_email');
        }
        //检查用户邮箱是否已经被注册
        if($this->m_user->check_exist('email',$email)){
            return we_single_error('existing_email');
        }
        $this->userdata['email']=$email;//邮箱
        //检查用户密码格式
        if(!valid_password($password)){
            return we_single_error('invalid_password');
        }
        $this->userdata['password']=$password;
 
        //检查用户真实姓名
        if(!valid_real_name($real_name))
            return we_single_error('invalid_real_name');
        $this->userdata['display_name']=$real_name;//真实名字
        //检查蓝牙地址
        if(!$bluetooth_mac||!valid_bluetooth_mac($bluetooth_mac))
            return we_single_error('invalid_bluetooth_mac');
        $bluetooth_mac=strtoupper($bluetooth_mac);//蓝牙地址
        
        //内容初始化完毕，操作数据
        //检查蓝牙地址相应的用户ID，如果不是0，则返回错误，否则可以使用
        $bluetooth_id=$this->m_user->get_user_id_by_bluetooth_mac($bluetooth_mac);
        if($bluetooth_id){
            return we_single_error('existing_bluetooth_mac');
        }else{
            $bluetooth_data=array(
                                  'bluetooth_mac'=>$bluetooth_mac,
                                  'bluetooth_name'=>$bluetooth_name,//如果没有蓝牙名称，则空出来
                                  'create_time'=>$this->current_time
                                  );
            $this->userdata['bluetooth_id']=$this->m_user->add_bluetooth($bluetooth_data);
        }
        //加密密码
        $this->userdata['password']=md5($password);//密码
        //设置用户的用来@的名称,即下一个ID及其真实名字联合
        $this->userdata['real_name']=$this->m_user->get_user_real_name_by_display_name($real_name);
        $this->userdata['create_time']=$this->current_time;
        $this->userdata['user_type']='user';
        //添加用户信息
        $this->userdata['user_id']=$this->m_user->insert_user($this->userdata);
        //如果用户获得的ID与之前估计不同，则删除并报错
        if(!$this->userdata['user_id']){
            return we_single_error('unknown_error');
        }else if(intval($this->userdata['real_name'])!=$this->userdata['user_id']){
            $this->m_user->delete_user($this->userdata['user_id']);
            return we_single_error('unknown_error');
        }else{//更新蓝牙信息
            $bluetooth_data=array(
                                  'user_id'=>$this->userdata['user_id'],
                                  'bluetooth_mac'=>$bluetooth_mac,
                                  'bluetooth_name'=>$bluetooth_name,
                                  'create_time'=>$this->current_time;
                                  );
        }
        //接下来为用户创建默认相册
        
        return $this->userdata;
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
        if($album_name=='默认相册'){
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
        else
            return we_single_error('unknown_error');
    }
}