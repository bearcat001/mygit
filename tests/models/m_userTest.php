<?php
class m_userTest extends CIUnit_TestCase{
    function __construct(){
        parent::__construct();
    }
    function setUp(){
        $this->CI->load->model('m_user');
        parent::setUp();
    }
    function tearDown(){
        parent::tearDown();
    }
    function test(){
        $tt=$this->CI->m_user->get_user_real_name_by_display_name("hello");
        $this->assertEquals("hello(0)",$tt);
        return 1;
    }
}