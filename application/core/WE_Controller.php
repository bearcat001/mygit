<?php
/**
 * WE_Controller
 * 实际是wordpress的IXR_Server
 *
 * @package IXR
 * @since 1.5
 */
class WE_Controller extends CI_Controller
{
    var $data;
    var $callbacks = array();
    var $message;
    var $capabilities;
	
    /**
     * 构造函数
     * @param 回调函数  $callbacks
     * @param unknown_type $data
     * @param unknown_type $wait
     */
    function __construct($callbacks = false, $data = false, $wait = false)
    {
    	parent::__construct();
    	$this->load->helper(array('check','email','ixr_xmlrpc'));
    	//设置功能数组，和xmlrpc网站有关
        $this->setCapabilities();
        
        //回调函数，即初始化申明的那些函数数组
        if ($callbacks) {
            $this->callbacks = $callbacks;
        }
        
        //往回调函数中增加几个本系统功能
        $this->setCallbacks();
        
        //如果没有等待信息的化就执行
        if (!$wait) {
            $this->serve($data);
        }
    }

    function serve($data = false)
    {
    	//如果数据为空
        if (!$data) {
        	
        	//是否从post提交了数据，如果没有提交就终止
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            	header('Content-Type: text/plain'); // merged from WP #9093
                die('XML-RPC server accepts POST requests only.');
            }
            
			//获得浏览器提交的数据
            global $HTTP_RAW_POST_DATA;
            
            if (empty($HTTP_RAW_POST_DATA)) {
                // workaround for a bug in PHP 5.2.2 - http://bugs.php.net/bug.php?id=41293
                $data = file_get_contents('php://input');
            } else {
                $data =& $HTTP_RAW_POST_DATA;
            }
        }
        
        //生成信息处理类，将此类赋给message(此信息为未经处理的xml内容)
        $this->message = new IXR_Message($data);
        
        
        
        //Edit by YanSu
        //开始解析此次数据
        if (!$this->message->parse()) {
        	//如果失败，则报错
        	log_message("error","request_fail:".$this->message);
            $this->error(-32700, 'parse error. not well formed');
        }else{//否则就输出此次结果
        	log_message("error","request_success:".$this->message->methodName);
        	log_message("error",serialize($this->message->params));
        }
       	
        if ($this->message->messageType != 'methodCall') {
            $this->error(-32600, 'server error. invalid xml-rpc. not conforming to spec. Request must be a methodCall');
        }
        //调用相应函数
        $result = $this->call($this->message->methodName, $this->message->params);

        // Is the result an error?
        if (is_a($result, 'IXR_Error')) {
            $this->error($result);
        }

        // Encode the result
        $r = new IXR_Value($result);
        $resultxml = $r->getXml();

        // Create the XML
        $xml = <<<EOD
<methodResponse>
  <params>
    <param>
      <value>
      $resultxml
      </value>
    </param>
  </params>
</methodResponse>

EOD;
      // Send it
      $this->output($xml);
    }
	
    //调用需要的函数
    function call($methodname, $args)
    {
    	//检查是否有所需函数
        if (!$this->hasMethod($methodname)) {
            return new IXR_Error(-32601, 'server error. requested method '.$methodname.' does not exist.');
        }
        //即将调用的函数为method
        $method = $this->callbacks[$methodname];

        //如果只有一个参数，就将整个数组传入--也就是解析的时候发现只有一个参数是没法解析的
        // Perform the callback and send the response
        if (count($args) == 1) {
            // If only one paramater just send that instead of the whole array
            $args = $args[0];
        }
		
        //如果我们将要执行的是一个类中的方法
        // Are we dealing with a function or a method?
        if (is_string($method) && substr($method, 0, 5) == 'this:') {
            // It's a class method - check it exists
            //获得这个方法的名称
            $method = substr($method, 5);
            
            //检查这个方法是否在当前类中，也就是必须得在当前类中
            if (!method_exists($this, $method)) {
                return new IXR_Error(-32601, 'server error. requested class method "'.$method.'" does not exist.');
            }
            
			//调用方法
            //Call the method
            $result = $this->$method($args);
        } else {
            // It's a function - does it exist?
            if (is_array($method)) {
                if (!is_callable(array($method[0], $method[1]))) {
                    return new IXR_Error(-32601, 'server error. requested object method "'.$method[1].'" does not exist.');
                }
            } else if (!function_exists($method)) {
                return new IXR_Error(-32601, 'server error. requested function "'.$method.'" does not exist.');
            }

            // Call the function
            $result = call_user_func($method, $args);
        }
        //返回调用结果
        return $result;
    }

    function error($error, $message = false)
    {
        // Accepts either an error object or an error code and message
        if ($message && !is_object($error)) {
            $error = new IXR_Error($error, $message);
        }
        $this->output($error->getXml());
    }

    function output($xml)
    {
        $xml = '<?xml version="1.0"?>'."\n".$xml;
        $length = strlen($xml);
        header('Connection: close');
        header('Content-Length: '.$length);
        header('Content-Type: text/xml');
        header('Date: '.date('r'));
        echo $xml;
        exit;
    }

    function hasMethod($method)
    {
        return in_array($method, array_keys($this->callbacks));
    }

    /**
     * 设置功能
     */
    function setCapabilities()
    {
        // 初始化功能数组
        $this->capabilities = array(
            'xmlrpc' => array(
                'specUrl' => 'http://www.xmlrpc.com/spec',
                'specVersion' => 1
        ),
            'faults_interop' => array(
                'specUrl' => 'http://xmlrpc-epi.sourceforge.net/specs/rfc.fault_codes.php',
                'specVersion' => 20010516
        ),
            'system.multicall' => array(
                'specUrl' => 'http://www.xmlrpc.com/discuss/msgReader$1208',
                'specVersion' => 1
        ),
        );
    }
	
    /**
     * 获得功能
     * @param unknown_type $args
     * @return multitype:multitype:string number
     */
    function getCapabilities($args)
    {
        return $this->capabilities;
    }

    /**
     * 设置回调函数
     */
    function setCallbacks()
    {
    	//将回调函数中增加三个当前系统功能
    	//获得刚刚设置的功能
        $this->callbacks['system.getCapabilities'] = 'this:getCapabilities';
        //取得回调函数中的key值，然后颠倒数组返回
        $this->callbacks['system.listMethods'] = 'this:listMethods';
        
        $this->callbacks['system.multicall'] = 'this:multiCall';
    }

    /**
     * 列出函数
     * @param unknown_type $args
     * @return multitype:
     */
    function listMethods($args)
    {
        // Returns a list of methods - uses array_reverse to ensure user defined
        // methods are listed before server defined methods
        //取得回调函数中的key值，然后颠倒数组返回
        return array_reverse(array_keys($this->callbacks));
    }
	
    /**
     * 
     * @param unknown_type $methodcalls
     * @return multitype:multitype:NULL  multitype:Ambigous <IXR_Error, mixed>
     */
    function multiCall($methodcalls)
    {
        // See http://www.xmlrpc.com/discuss/msgReader$1208
        $return = array();
        foreach ($methodcalls as $call) {
            $method = $call['methodName'];
            $params = $call['params'];
            if ($method == 'system.multicall') {
                $result = new IXR_Error(-32600, 'Recursive calls to system.multicall are forbidden');
            } else {
                $result = $this->call($method, $params);
            }
            if (is_a($result, 'IXR_Error')) {
                $return[] = array(
                    'faultCode' => $result->code,
                    'faultString' => $result->message
                );
            } else {
                $return[] = array($result);
            }
        }
        return $return;
    }
}

?>
