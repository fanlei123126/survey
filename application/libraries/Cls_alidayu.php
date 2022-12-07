<?php
date_default_timezone_set("PRC");
ini_set("display_errors", "on");

require_once 'api_sdk/vendor/autoload.php';

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

// 加载区域结点配置
Config::load();

class Cls_alidayu
{

    static $acsClient       = null;
    private $send_code_time = 60;   //发送短信有效时间为
    private $valid_code_time= 600;   //验证码有效时间为
    private $CI;
    public $verify_type_id  = 1;     //短信功能类别:1为注册的短信，2为找回密码，3为修改手机
	static public $appkey          = 'LTAI4G5fsE8MSAHPjnANQK5B';    //阿里大鱼appkey
    static public $secretKey       = '6H3a4wttlA4GlmoTIiOQYFxxKrYWqL';    //阿里大鱼secretkey
    public $sign            = 'U童';    //单条短信的签名
    public $sign_list       = array();  //多条短信的签名
    public $mobile          = '';    //发送的手机号码
    public $mobile_list     = array();  //批量发送的手机号码集合
    public $template_name   = '';    //发送的短信模版
    public $template_list   = array();  //批量发送的短信模版
    public $param           = array();//替换短信参数的集合
    public $outId           = '';    //设置流水号
    public $protocol        = '';    //启用https协议
    public $extend_code     = '';    //上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
    public $extend_code_list= '';    //"[\"90997\",\"90998\"]"
    private $session_key    = 'verify_sms';
    
    function __construct($data=array())
    {
        $this->CI = & get_instance();
    }
    
    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient() 
    {
        $product = "Dysmsapi";
        $domain = "dysmsapi.aliyuncs.com";
        $accessKeyId = static::$appkey;
        $accessKeySecret = static::$secretKey;
        $region = "cn-hangzhou";
        $endPointName = "cn-hangzhou";

        if(static::$acsClient == null) 
        {
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }
    
    /**
     * @name sendCode 发送验证短信
     * @return array 
    */
    function sendCode($mobile='')
    {
    	$session_code_arr = $this->CI->session->userdata($this->session_key);
		if(isset($session_code_arr['code']) 
			&& time()-$session_code_arr['time'] < $this->send_code_time){
			return array('code'=>-1, 'msg'=>'发送短信过于频繁，请稍后再试');
		}
		
    	$code = rand(1000, 9999);
    	$this->mobile = $mobile;
		
        $this->param['code'] = $code;
        $this->template_name = 'SMS_168555201';
		
		$session_code_arr = array('mobile' => $mobile, 'code' => $code, 'time'=>time());
    	$this->CI->session->set_userdata($this->session_key, $session_code_arr);//保存进session验证
    	//TODO test
    	log_message("error", 'sendCode '.json_encode($session_code_arr));
//		return array('code'=>0, 'msg'=>'发送成功');
    	//TODO test
        return $this->commonSendSms();
    }

	/**
	 * @name checkCode 验证验证码
	 */
    public function checkCode($mobile, $code){
    	$session_code_arr = $this->CI->session->userdata($this->session_key);
		if(isset($session_code_arr['code']) 
			&& strtolower($code) == $session_code_arr['code'] 
			&& strtolower($mobile) == $session_code_arr['mobile'] 
			&& time()-$session_code_arr['time'] <= $this->valid_code_time){
			return true;
		}else{
			return false;
		}
    }
	
	/**
	 * @name clearCode 清除验证码
	 */
    public function clearCode(){
    	$this->CI->session->set_userdata($this->session_key, '');//保存进session验证
    }
	
	
    /**
     * 发送短信
     * @return stdClass
     */
    public function commonSendSms()
    {
        $code = 0; $message = ""; 
        if(!$this->mobile)
        {
            $code = -1; $message = "手机号码出现空值"; 
        }
        elseif(!$this->sign)
        {
            $code = -1; $message = "接口签名出现空值"; 
        }
        elseif(!$this->template_name)
        {
            $code = -1; $message = "短信模版出现空值"; 
        }
        else
        {
            $request = new SendSmsRequest();
            if($this->protocol)
            {
                $request->setProtocol("https");
            }
            $request->setPhoneNumbers($this->mobile);
            $request->setSignName($this->sign);
            $request->setTemplateCode($this->template_name);
    
            if($this->param)
            {
                $request->setTemplateParam(json_encode($this->param, JSON_UNESCAPED_UNICODE));
            }
            if($this->outId)
            {
                $request->setOutId($this->outId);
            }
            if($this->extend_code)
            {
                $request->setSmsUpExtendCode($this->extend_code);
            }
            $acsResponse = static::getAcsClient()->getAcsResponse($request);
			log_message("error", 'acsResponse = '.json_encode($acsResponse));
            if($acsResponse->Code == 'OK')
            {
                $code = 1; $message = "短信发送成功"; 
            }
            else
            {
                $code = 0; $message = $acsResponse->Message; 
            }
        }
        return array('code'=>$code, 'msg'=>$message);
    }

}
