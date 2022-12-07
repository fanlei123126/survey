<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name    各个项目公用的方法集
 * @author  2013-07-01 by wing
 * @version 1.0.0
 */

// ------------------------------------------------------------------------

/**
 * @name    OA系统公共方法
 * @author  2016-11-23 by wing
 * @version 1.0.0
 */

// ------------------------------------------------------------------------

if( ! function_exists('load_views'))
{
    function load_views($view_name = '', $data = array())
    {
        $CI = &get_instance();

        $CI->load->view($view_name, $data);
    }
}

// ------------------------------------------------------------------------

/**
 * @name 输出字符串
 * @param string str
 */
if( ! function_exists('output_str'))
{
    function output_str( $str = '' )
    {
        @ob_clean();
        @ob_start();
        @header('Content-Type: text/html; charset=utf-8');
        @header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        @header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        @header('Pragma: no-cache');

        echo $str;
        exit();
    }
}

// -----------------------------------------------------------------------

/**
 * @name 取得DISCUZ登录COOKIE(并查询登录用户相关信息)
 */
if( ! function_exists('get_user_bycookie'))
{
    function get_user_bycookie()
    {
        //initialization CI
        $CI =&get_instance();
        $CI->load->library('session');

        //initialization user object
        $header_user = new stdClass();
        $cookie_auth = getcookie('auth');
        //发现authcode方法中
        if( ! empty($cookie_auth)){
            //decode auth and split
            $decode_cookie_str = authcode_new($cookie_auth, 'DECODE');

            $current_cookie_uid = intval($decode_cookie_str);
            /**获取用户信息*/
            $mckey = 'txtx_wxuser_'.$current_cookie_uid;
            $header_user = getMemcache($mckey);
            if($header_user === FALSE)
            {
                $CI->load->model('kykdb_model');
                $header_user = $CI->kykdb_model->get_result('act_txtx_wxuser', array('uid' => $current_cookie_uid), 1);
                if( isset($header_user->uid) )
                {
                    setMemcache($mckey, $header_user);
                }
            }

            //如果用户信息不为空
            if(isset($header_user->uid))
            {
                //update CI_session
                $sess_arr = array(
                    'uid'=>$header_user->uid,
                    'wxname'=>urlencode($header_user->wxname),
                    'title'=>$header_user->title,
                    'face'=>file_url($header_user->face, $header_user->domain_type)
                );
                $CI->session->set_userdata($sess_arr);

                //如果用户信息为空
            }else{
                /**当用户信息不存在，置空session的用户部分*/
                $cur_user_id = $CI->session->userdata('uid');
                if( ! empty($cur_user_id)){
                    $sess_arr = array(
                        'uid'=>0,
                        'wxname'=>'',
                        'title'=>'',
                        'face'=>''
                    );
                    $CI->session->set_userdata($sess_arr);
                }
            }
            /*END charset process*/
        }
        else
        {
            /**当用户信息不存在，置空session的用户部分*/
            $cur_user_id = $CI->session->userdata('uid');
            if( ! empty($cur_user_id)){
                $sess_arr = array(
                    'uid'=>0,
                    'wxname'=>'',
                    'title'=>'',
                    'avatar'=>''
                );
                $CI->session->set_userdata($sess_arr);
            }
        }
        unset($CI);
        return $header_user;
    }
}

// -----------------------------------------------------------------------

/**
 *写cookie的方法
 *
 * @param $var String cookie名称
 * @param $value String cookie值
 * @param $life Integer cookie存活时间
 */
if ( ! function_exists('setMyCookie')) {
    function setMyCookie($key, $value = "", $life = 0)
    {
        //把Token设置Cookie
        $CI = &get_instance();
        $CI->load->helper("cookie");
        $cookie_domain = $CI->config->item('cookie_domain');
        if ($life > 0) {
            $life = time() + $life;
        }
        set_cookie($key, $value, $life, $cookie_domain);
       //echo "111111".get_cookie($key)."/p";
    }
}

/**
 * 删除cookie
 *
 */
if ( ! function_exists('delMyCookie')) {
    function delMyCookie($key)
    {
        $CI = &get_instance();
        $CI->load->helper("cookie");
        delete_cookie($key);
    }
}

/**
 * 获取cookie
 * @param $key
 * @return mixed|string
 */
if ( ! function_exists('getMyCookie')) {
    function getMyCookie($key)
    {
        $CI = &get_instance();
        $CI->load->helper("cookie");
        $value = get_cookie($key);
        return $value ?? "";
    }
}
/**
 *写cookie的方法
 *
 * @param $var String cookie名称
 * @param $value String cookie值
 * @param $life Integer cookie存活时间
 * @param $prefix Integer 是否使用config中的cookie_prefix
 * @param $httponly Integer 是否仅仅是http请求
 */
if ( ! function_exists('dsetcookie')){
    function dsetcookie($var, $value = '', $life = 0, $prefix = 1, $httponly = false) {

        $CI = &get_instance();

        $cookie_prefix = '';
        if($prefix)
        {
            $cookie_prefix = $CI->config->item('cookie_prefix');
            $cookie_domain = $CI->config->item('cookie_domain');
            $cookie_path   = $CI->config->item('cookie_path');
            $cookie_secure = $CI->config->item('cookie_secure');
            $cookie_prefix = $cookie_prefix.substr(md5($cookie_path.'|'.$cookie_domain), 0, 4).'_';
        }

        unset($CI);

        $var = $cookie_prefix.$var;
        $_COOKIE[$var] = $var;
        if($value == '' || $life < 0) {
            $value = '';
            $life = -1;
        }

        $timestamp = time();
        $life = $life > 0 ? $timestamp + $life : ($life < 0 ? $timestamp - 31536000 : 0);
        $path = $httponly && PHP_VERSION < '5.2.0' ? $cookie_path.'; HttpOnly' : $cookie_path;

        $secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;

        if(PHP_VERSION < '5.2.0') {
            setcookie($var, $value,$life , $path, $cookie_domain, $secure);
        } else {
            setcookie($var, $value, $life, $path, $cookie_domain, $secure, $httponly);
        }
    }
}

// -----------------------------------------------------------------------

/**
 * @name 获取cookie的值
 */
if( ! function_exists('getcookie'))
{
    function getcookie($key='', $prefix = 1)
    {
        $cookie_prefix = '';
        if($prefix)
        {
            $cookie_prefix = config_item('cookie_prefix');
            $cookie_domain = config_item('cookie_domain');
            $cookie_path   = config_item('cookie_path');
            $cookie_prefix = $cookie_prefix.substr(md5($cookie_path.'|'.$cookie_domain), 0, 4).'_';
        }

        return isset($_COOKIE[$cookie_prefix.$key]) ? $_COOKIE[$cookie_prefix.$key] : '';
    }
}

// -----------------------------------------------------------------------

/**
 * 加密解密函数
 * @param $string 加密内容
 * @param $operation 加密/解密
 * @param $key 加密Key
 * @param $expiry 有效时间
 */
if( ! function_exists('authcode_new'))
{
    function authcode_new($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        $ckey_length = 4;
        //$key = md5($key != '' ? $key : getglobal('authkey'));
        //$key= md5(md5($CI->config->item('authkey').$_SERVER['HTTP_USER_AGENT']));
        $key= md5(md5(config_item('authkey')));
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }
}

// -----------------------------------------------------------------------

/**
 * @name  字符串截取，一个汉字长度为2；一个英文长度为1
 * @param String  string   要处理的字符串
 * @param int     length   要截取的长度
 * @param String  dot      被省略的字符显示
 * @param String  charset  要处理的字符串的字符集
 * @return String
 */
if( ! function_exists('dz_cutstr'))
{
    function dz_cutstr($string, $length=0, $dot = '..', $charset='utf-8') {
        if(strlen($string) <= $length) {
            return $string;
        }

        $strcut = '';
        if(strtolower($charset) == 'utf-8') {
            //$start = 0;
            $i = 0;
            $str = $string;
            //开始截取
            $show_len = 0;
            //while($i < strlen($str) && $show_len< $start + $length) {
            while($i < strlen($str) && $show_len < $length) {
                $ord = ord($str[$i]);
                //echo '<br />';
                //echo $i.'=='.$str[$i].'=='.$ord;

                if($ord < 192) {
                    $strcut .= $str[$i];
                    $i++;
                    $show_len++;
                } elseif($ord <224) {
                    $strcut .= $str[$i].$str[$i+1];
                    $i += 2;
                    $show_len+=2;
                } else {
                    if($ord == 240)
                    {
                        $strcut .= $str[$i].$str[$i+1].$str[$i+2].$str[$i+3];
                        //echo '----'.$str[$i].$str{$i+1}.$str{$i+2}.$str{$i+3};//UTF-8码
                        $i += 4;
                    }
                    else
                    {
                        $strcut .= $str[$i].$str[$i+1].$str[$i+2];
                        $i += 3;
                    }
                    $show_len+=2;
                }
                //echo '<br />';
            }

            if($i < strlen($str)) {
                $strcut .= $dot;
            }
        } else {
            for($i = 0; $i < $length; $i++)
            {
                $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
            }

            if($i < strlen($str)) {
                $strcut .= $dot;
            }
        }
        return $strcut;
    }
}

// -----------------------------------------------------------------------

/**
 * @name 计算字符串长度，一个中文算两个英文字符
 */
if( ! function_exists('dz_strlen'))
{
    function dz_strlen($str='', $charset='utf-8')
    {
        $strlen = 0;
        $i = 0;
        if(strtolower($charset) == 'utf-8') {
            while($i < strlen($str)) {
                $ord = ord($str[$i]);
                if($ord < 192) {
                    $i++;
                    $strlen++;
                } elseif($ord <224) {
                    $i += 2;
                    $strlen+=2;
                } else {
                    $i += 3;
                    $strlen+=2;
                }
            }
        } else {
            for($i = 0; $i < strlen($str); $i++)
            {
                if(ord($str[$i]) > 127){
                    $strlen+=2;
                } else {
                    $strlen+=1;
                }
            }
        }
        return $strlen;
    }
}

// -----------------------------------------------------------------------

/**
 * @name   setMemcache()  根据key赋值Memcache缓存数据
 * @param  $key    键值
 * @param  $data   赋值数据
 * @param  $time   开启后，过期时间
 * @param  $isopen 是否开启
 * @return bool
 */
if(! function_exists('setMemcache'))
{
    function setMemcache($key, $data, $time=300, $isopen=true){
        if(!$isopen){
            return '';
        }
        $key=trim($key);
        $time=abs($time);
        if(!$key || !$data || !$time){
            return '';
        }
        $CI = &get_instance();
        $CI->load->library('cls_memcache');
        return $CI->cls_memcache->set($key, $data, $time);
    }
}

// -----------------------------------------------------------------------

/**
 * @name   getMemcache()  根据key获取Memcache缓存数据
 * @param  $key  键值
 * @return 对应key值数据
 */
if(! function_exists('getMemcache'))
{
    function getMemcache($key)
    {
        $key=trim($key);
        $CI = &get_instance();
        $CI->load->library('cls_memcache');
        return $CI->cls_memcache->get($key);
    }
}

// -----------------------------------------------------------------------

/**
 * @name del_memcache 删除相对应key的缓存数据
 * @param $key 缓存的key
 */
if(! function_exists('delMemcache'))
{
    function delMemcache($key)
    {
        $key=trim($key);
        $CI = &get_instance();
        $CI->load->library('cls_memcache');
        return $CI->cls_memcache->delete_bykey($key);
    }
}

// -----------------------------------------------------------------------

/**
 * 生成指定位数的随机数
 */
if( ! function_exists('random'))
{
    function random($length, $numeric = 0)
    {
        $seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
        $hash = '';
        $max = strlen($seed) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $seed[mt_rand(0, $max)];
        }
        return $hash;
    }
}

// -----------------------------------------------------------------------

/**
 * @name 邮件地址是否合法
 */
if( ! function_exists('isemail'))
{
    function isemail($email) {
        return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
    }
}

// ------------------------------------------------------------------------

/**
 * @name URL是否正确
 * @access public
 * @param  string url
 * @return bool
 */
if( ! function_exists('isurl'))
{
    function isurl($url)
    {
        return preg_match("/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/", $url);
    }
}

// ------------------------------------------------------------------------

/**
 * @name 手机号码格式是否正确
 * @access public
 * @param  string str 手机号
 * @return bool
 */
if( ! function_exists('ismobile'))
{
    function ismobile($mobile)
    {
        return preg_match("/^1[3-9]\d{9}$/", $mobile);
    }
}

// -----------------------------------------------------------------------

/**
 * @name 商务活动中的分页方法，只有（首页 / 上页 / 下页 三个链接）
 * @param  $cur_page=0 当前页码
 * @param  $per_page   每页数目
 * @param  $total_num  总数目
 * @param  $prefix     页码前拼接符
 * @param  $suffix     页码后拼接符
 * @param  $dynamic    URL是否启用查询字符串
 * @param  $base_url   基础URL
 * @return array('offset'=>起始值, 'page_html'=>'分页信息', 'pre_page'=>'每页数目')
 */
if ( ! function_exists('page_fpn'))
{
    function page_fpn($cur_page=0, $per_page, $total_num, $prefix='', $suffix='', $base_url='')
    {
        $CI =&get_instance();
        $current_page = intval($cur_page);  //获取当前分页页码数
        if(0 == $current_page)
        {
            $current_page = 1;
        }
        /*分页处理*/
        $CI->load->library('cls_staticpage');
        $config['prefix'] = $prefix;
        $config['suffix'] = $suffix;
        $config['base_url'] = $base_url ? $base_url : BASE_SITE_URL;
        $config['total_rows'] = $total_num;
        $config['cur_page']   = $current_page;
        $config['first_url'] = $config['base_url'].$prefix.'1'.$suffix;

        $CI->lang->load('pages','zh_cn');//获得语言包
        $config['prev_link']  = lang('prev_previous');
        $config['next_link']  = lang('next_previous');
        $config['first_link'] = lang('first_link');
        //$config['last_link']  = lang('last_link');
        $config['isshow_last_link']  = FALSE;
        $config['per_page']   = $per_page;
        $config['use_page_numbers'] = TRUE;//在URL后面显示页码通过$cur_page获得当前页

        //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）
        $offset   = ($current_page - 1 ) * $config['per_page'];
        $CI->cls_staticpage->initialize($config);
        $page_html = $CI->viewdata['page_html'] = $CI->cls_staticpage->create_links_forcoo();
        $info=array('offset'=>$offset,'page_html'=>$page_html,'per_page'=>$per_page);
        return $info;
    }
}

// -----------------------------------------------------------------------


/**
 * 分页方法，只有（首页 / 上页 / 下页 / 尾页 四个链接）
 * @param  $cur_page=0 当前页码
 * @param  $per_page   每页数目
 * @param  $total_num  总数目
 * @param  $prefix     页码前拼接符
 * @param  $suffix     页码后拼接符
 * @param  $dynamic    URL是否启用查询字符串
 * @param  $base_url   基础URL
 * @return array('offset'=>起始值, 'page_html'=>'分页信息', 'pre_page'=>'每页数目')
 */
if ( ! function_exists('page_fpnl'))
{
    function page_fpnl($cur_page=0, $per_page, $total_num, $prefix='', $suffix='', $base_url='')
    {
        $CI =&get_instance();
        $current_page = intval($cur_page);  //获取当前分页页码数
        if(0 == $current_page)
        {
            $current_page = 1;
        }
        /*分页处理*/
        $CI->load->library('cls_staticpage');
        $config['prefix'] = $prefix;
        $config['suffix'] = $suffix;
        $config['base_url'] = $base_url ? $base_url : BASE_SITE_URL;
        $config['total_rows'] = $total_num;
        $config['cur_page']   = $current_page;
        $config['first_url'] = $config['base_url'].$prefix.'1'.$suffix;

        $CI->lang->load('pages','zh_cn');//获得语言包
        $config['prev_link']  = lang('prev_link');
        $config['next_link']  = lang('next_link');
        $config['first_link'] = lang('first_link');
        $config['last_link']  = lang('last_link');
        $config['isshow_last_link']  = FALSE;
        $config['per_page']   = $per_page;
        $config['use_page_numbers'] = TRUE;//在URL后面显示页码通过$cur_page获得当前页

        //设置偏移量 限定 数据查询 起始位置（从 $offset 条开始）
        $offset   = ($current_page - 1 ) * $config['per_page'];
        $CI->cls_staticpage->initialize($config);
        $page_html = $CI->viewdata['page_html'] = $CI->cls_staticpage->create_links_forcoo2();
        $info=array('offset'=>$offset,'page_html'=>$page_html,'per_page'=>$per_page);
        return $info;
    }
}

// -----------------------------------------------------------------------

/**
 * @name    pageHTML()   后台管理 --- 获得分页HTML信息
 *
 * @param  [str]  $url    分页数字前面链接地址
 * @param  [int]  $num    记录总数
 * @param  [int]  $cur    当前页
 * @param  [int]  $size   每页个数
 * @param  [bool] $showEnter    是否手动输入
 * @param  [int]  $displayPage  最多显示页数
 * @param  [bool] $showMaxPage  是否显示最大页【当页数大于最多显示页数时】
 * @param  [arr]  $addition     附加拼接信息  array('format'=>'结尾拼接符，如 -html',  'home'=>'首页',  'prev'=>'上一页',  'next'=>'下一页',  'last'=>'尾页')
 * @return string
 **/
if( ! function_exists('pageHTML'))
{
    function pageHTML($url='', $num=0, $cur=1, $size=25, $showEnter=true, $displayPage=7, $showMaxPage=true, $addition=array('format'=>'', 'home'=>'首页', 'prev'=>'上一页', 'next'=>'下一页', 'last'=>'尾页')){
        $url=ltrim($url);
        $num=intval($num);
        $cur=intval($cur);
        $size=intval($size);
        $displayPage=intval($displayPage);
        $html='共 <b id="total_num">'.$num.'</b> 条记录&nbsp;&nbsp;&nbsp;&nbsp;';

        if($size<1){$size=25;}
        if($displayPage<1){$displayPage=7;}

        if($num<=$size){
            return $html;
        }

        $max=ceil($num/$size);
        $cur=$cur<1?1:($cur>$max?$max:$cur);

        $prev=$cur-1<1?1:$cur-1;
        $next=$cur+1>$max?$max:$cur+1;

        $format=isset($addition['format'])?$addition['format']:'';

        $html.='<a href="'.$url.'1'.$format.'" title="'.$addition['home'].'">'.$addition['home'].'</a>&nbsp;';
        $html.='<a href="'.$url.$prev.$format.'" title="'.$addition['prev'].'">&lt;&lt;</a>';
        if($max>$displayPage){
            $diff=floor($displayPage/2);
            $star=$end=0;
            if($displayPage%2==0){
                $star=$cur-$diff;
                $end=$cur+$diff-1;
            }else{
                $star=$cur-$diff;
                $end=$cur+$diff;
            }
            if($star<1){
                $star=1;
                $end=$displayPage;
            }
            if($end>$max){
                $star=$max-$displayPage+1;
                $end=$max;
            }
            if($star>1){
                $html.=' <a href="'.$url.'1'.$format.'">1</a> ';
            }
            if($star>2){
                $html.=' <span>..</span> ';
            }
            for($i=$star; $i<=$end; $i++){
                $html.=$cur==$i?' <strong>'.$i.'</strong> ':' <a href="'.$url.$i.$format.'">'.$i.'</a> ';
            }
            if($end<$max-1 || (!$showMaxPage && $end==$max-1)){
                $html.=' <span>..</span> ';
            }
            if($end<$max && $showMaxPage){
                $html.=' <a href="'.$url.$max.$format.'">'.$max.'</a> ';
            }
            $html.=' <a href="'.$url.$next.$format.'" title="'.$addition['next'].'">&gt;&gt;</a> ';
            if($showMaxPage){
                $html.=' <a href="'.$url.$max.$format.'" title="'.$addition['last'].'">'.$addition['last'].'</a> ';
            }
        }else{
            for($i=1; $i<=$max; $i++){
                $html.=$cur==$i?' <strong>'.$i.'</strong> ':' <a href="'.$url.$i.$format.'">'.$i.'</a> ';
            }
            $html.=' <a href="'.$url.$next.$format.'" title="'.$addition['next'].'">&gt;&gt;</a> ';
            $html.=' <a href="'.$url.$max.$format.'" title="'.$addition['last'].'">'.$addition['last'].'</a> ';
        }
        if($showEnter){
            $html.=' <input type="text" id="goPageOfInputEnter" size="2" value="'.$cur.'" onfocus="this.value=\'\';" onblur="if(this.value==\'\')this.value='.$cur.';" /> ';
            $html.=' <a onclick="window.location.href=\''.$url.'\'+parseInt(document.getElementById(\'goPageOfInputEnter\').value)+\''.$format.'\';" href="javascript:;">GO</a>';
        }
        return $html;
    }
}

// -----------------------------------------------------------------------

/**
 * @name 根据YJY文件的存储信息获得YJY文件的URL
 * @param string $save_path   数据库存储路径
 * @param int    $domain_type 文件域名类型
 * @param string $type        多规格类型
 * @return string URL
 */
if( ! function_exists('file_url'))
{
    function file_url($save_path = '', $domain_type = 0, $type = '')
    {
        $CI = &get_instance();
        $CI->load->library('cls_fileutil');
        return $CI->cls_fileutil->get_file_url($save_path, $domain_type, $type);
    }
}

// -----------------------------------------------------------------------

/**
 * system message
 *
 * @access	public
 * @param	$content 提示信息
 * @param	array    供选择的页面链接
 * @param    $type    提示信息的类型(0--information；1--warning; confirm)
 * @param    $is_redirect  是否自动跳转
 */
if ( ! function_exists('sys_message'))
{
    function sys_message($content, $arr_links=array(),$type=0, $is_redirect = true)
    {
        $CI = &get_instance();
        $data['message'] = $content;
        $data['msg_type']    = $type;
        if(empty($arr_links) || !is_array($arr_links))
        {
            $data['links'] = array(array('title'=>lang('return_prev_page'),'link'=>'javascript:history.go(-1)'));
        }
        else
        {
            $data['links'] = $arr_links;
        }
        $data['default_url'] = $data['links'][0]['link'];
        $data['is_redirect'] = $is_redirect;
        $CI->load->view('admin/message',$data);
        unset($CI,$data);
    }
}

// -----------------------------------------------------------------------

/**
 * @name 获得上传图片的信息
 * @param string imgfile          页面<input type="file"/>的name, 默认imgfile
 * @param array  allow_imgtype    所允许的图片类型 array('.jpg', 'png')
 * @param array(
 *           'state' => ''        //上传文件的状态, 'SUCCESS' - 上传成功
 *           'original' => '',    //上传时的原名
 *           'path' => '',        //上传后图片的磁盘全路径
 *           'strs' => '',        //数据库存储路径
 *           'domain_type' => '', //上传后图片所在域名类型
 *           'size' => 0,         //图片文件大小，单位B
 *           'type' => '',        //图片文件后缀
 *           'width' => 0,        //图片宽度px
 *           'height' => 0        //图片高度px
 *        )
 * @param string file_def         文件后缀描述： img-图片
 */
if( ! function_exists('get_upload_imginfo'))
{
    function get_upload_imginfo($imgfile = 'imgfile', $allow_imgtype = array('.jpg','.jpeg','.png', '.gif', '.bmp', '.ico'), $file_def = 'img', $maxsize=5120000)
    {
        $rtn_arr = array();
        //先上传图片处理
        $CI = & get_instance();
        $CI->load->library('cls_uploader');
        $config = array( "maxSize" => $maxsize,  "allowFiles" => $allow_imgtype);
        $CI->cls_uploader->upload_file($imgfile, $config);
        $info = $CI->cls_uploader->getFileInfo();
        //返回结果初始化
        $rtn_arr['state']       = $info['state'];
        $rtn_arr['original']    = $info['original'];
        $rtn_arr['path']        = $info['fullpath'];
        $rtn_arr['strs']        = $info['savepath'];
        $rtn_arr['domain_type'] = $info['domain_type'];
        $rtn_arr['size']        = $info['size'];
        $rtn_arr['type']        = $info['type'];
        $rtn_arr['width']       = $rtn_arr['height'] = 0;

        if($info['state'] == 'SUCCESS') //上传成功
        {
            if($file_def == 'img'){
                $img = new Imagick($rtn_arr['path']);
                $srcWH = $img->getImageGeometry();
                $rtn_arr['width'] = $srcWH['width'];
                $rtn_arr['height'] = $srcWH['height'];
            }
        }

        unset($info, $config);
        return $rtn_arr;
    }
}

// -----------------------------------------------------------------------

/**
 * @name 判断 string 是否是一个 URL
 */
if( ! function_exists('str_is_url'))
{
    function str_is_url($str = '')
    {
        if (filter_var ($str, FILTER_VALIDATE_URL )) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

// -----------------------------------------------------------------------

if( ! function_exists('xml_to_arr'))
{
    function xml_to_arr($xml)
    {
        $array = array();
        if( ! $xml)
        {
            return $array;
        }
        //将XML转为array
        libxml_disable_entity_loader(true);
        $array = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array;
    }
}

// -----------------------------------------------------------------------

/**
 * @name 取得微信登录COOKIE(并查询登录微信用户相关信息)
 */
if( ! function_exists('get_wxuser_bycookie'))
{
    function get_wxuser_bycookie()
    {
        //initialization CI
        $CI =&get_instance();
        $CI->load->library('session');

        //initialization user object
        $header_user = new stdClass();
        $cookie_auth = getcookie('txtxauth');
        //发现authcode方法中
        if( ! empty($cookie_auth)){
            //decode auth and split
            $decode_cookie_str = authcode_new($cookie_auth, 'DECODE');

            $current_cookie_uid = intval($decode_cookie_str);
            /**获取用户信息*/
            $mckey = 'txtx_wxuser_'.$current_cookie_uid;
            $header_user = getMemcache($mckey);
            if($header_user === FALSE)
            {
                $CI->load->model('kykdb_model');
                $header_user = $CI->kykdb_model->get_result('act_txtx_wxuser', array('uid' => $current_cookie_uid), 1);
                if( isset($header_user->uid) )
                {
                    setMemcache($mckey, $header_user);
                }
            }

            //如果用户信息不为空
            if( ! isset($header_user->uid) )
            {
                dsetcookie('txtxauth', '', 0);
            }
            /*END charset process*/
        }
        else
        {
            dsetcookie('txtxauth', '', 0);
        }
        unset($CI);
        return $header_user;
    }
}

// -----------------------------------------------------------------------

/**
 * @name 取得微信登录COOKIE(并查询登录微信用户相关信息)
 */
if( ! function_exists('get_ut_wxuser_bycookie'))
{
    function get_ut_wxuser_bycookie()
    {
        //initialization CI
        $CI =&get_instance();
        $CI->load->library('session');

        //initialization user object
        $header_user = new stdClass();
        $cookie_auth = getcookie('txtxutauth');
        //发现authcode方法中
        if( ! empty($cookie_auth)){
            //decode auth and split
            $decode_cookie_str = authcode_new($cookie_auth, 'DECODE');

            $current_cookie_uid = intval($decode_cookie_str);
            /**获取用户信息*/
            $mckey = 'txtx_wxuser_'.$current_cookie_uid;
            $header_user = getMemcache($mckey);
            if($header_user === FALSE)
            {
                $CI->load->model('kykdb_model');
                $header_user = $CI->kykdb_model->get_result('act_txtx_wxuser', array('uid' => $current_cookie_uid), 1);
                if( isset($header_user->uid) )
                {
                    setMemcache($mckey, $header_user);
                }
            }

            //如果用户信息不为空
            if( ! isset($header_user->uid) )
            {
                dsetcookie('txtxutauth', '', 0);
            }
            /*END charset process*/
        }
        else
        {
            dsetcookie('txtxutauth', '', 0);
        }
        unset($CI);
        return $header_user;
    }
}

// -----------------------------------------------------------------------

/**
 * @name 取得微信登录COOKIE(并查询登录微信用户相关信息)
 */
if( ! function_exists('get_gas_wxuser_bycookie'))
{
    function get_gas_wxuser_bycookie()
    {
        //initialization CI
        $CI =&get_instance();
        $CI->load->library('session');

        //initialization user object
        $header_user = new stdClass();
        $cookie_auth = getcookie('txtxgasauth');
        //发现authcode方法中
        if( ! empty($cookie_auth)){
            //decode auth and split
            $decode_cookie_str = authcode_new($cookie_auth, 'DECODE');

            $current_cookie_uid = intval($decode_cookie_str);
            /**获取用户信息*/
            $mckey = 'txtx_wxuser_'.$current_cookie_uid;
            $header_user = getMemcache($mckey);
            if($header_user === FALSE)
            {
                $CI->load->model('kykdb_model');
                $header_user = $CI->kykdb_model->get_result('act_txtx_wxuser', array('uid' => $current_cookie_uid), 1);
                if( isset($header_user->uid) )
                {
                    setMemcache($mckey, $header_user);
                }
            }

            //如果用户信息不为空
            if( ! isset($header_user->uid) )
            {
                dsetcookie('txtxgasauth', '', 0);
            }
            /*END charset process*/
        }
        else
        {
            dsetcookie('txtxgasauth', '', 0);
        }
        unset($CI);
        return $header_user;
    }
}

// -----------------------------------------------------------------------


/**
 * @name 取得微信登录COOKIE(并查询登录微信用户相关信息)
 */
if( ! function_exists('get_mcfa_wxuser_bycookie'))
{
    function get_mcfa_wxuser_bycookie()
    {
        //initialization CI
        $CI =&get_instance();
        $CI->load->library('session');

        //initialization user object
        $header_user = new stdClass();
        $cookie_auth = getcookie('mcfaauth');
        //发现authcode方法中
        if( ! empty($cookie_auth)){
            //decode auth and split
            $decode_cookie_str = authcode_new($cookie_auth, 'DECODE');

            $current_cookie_uid = intval($decode_cookie_str);
            /**获取用户信息*/
            $mckey = 'mcfa_wxuser_'.$current_cookie_uid;
            $header_user = getMemcache($mckey);
            if($header_user === FALSE)
            {
                $CI->load->model('kykdb_model');
                $header_user = $CI->kykdb_model->get_result('mcfa_wxuser', array('uid' => $current_cookie_uid), 1);
                if( isset($header_user->uid) )
                {
                    setMemcache($mckey, $header_user);
                }
            }

            //如果用户信息不为空
            if( ! isset($header_user->uid) )
            {
                dsetcookie('mcfaauth', '', 0);
            }
            /*END charset process*/
        }
        else
        {
            dsetcookie('mcfaauth', '', 0);
        }
        unset($CI);
        return $header_user;
    }
}

// -----------------------------------------------------------------------

/**
 * @name 格式化 微信分享 的描述
 * @param string $content
 * @return string
 */
if( ! function_exists('format_wx_desc'))
{
    function format_wx_desc($content = '')
    {
        if( ! function_exists('lang'))
        {
            $ci = & get_instance();
            $ci->load->helper('language');
            $ci->lang->load('common',  'zh_cn');
        }

        $desc = '';
        if( ! empty($content))
        {
            $desc = str_replace(lang('ar_wx_find'), lang('ar_wx_replace'), strip_tags($content));
        }

        return $desc;
    }
}

// -----------------------------------------------------------------------

/**
 * 仿照朋友圈时间显示的转换
 *
 * @param $create 对象日时时间戳
 * @param $current 系统日时时间戳
 * @return 转换后的字符串
 */
if( ! function_exists('pyq_date_format'))
{
    function pyq_date_format($create = 0, $current = 0)
    {
        $current = $current > 0 ? $current : time();

        if (date ( "Y", $current ) == date ( "Y", $create ))
        {
            $time = $current - $create;
            if ($time < 60)
            {
                return "刚刚";
            }

            $sec = $time / 60;
            if ($sec < 60)
            {
                return round ( $sec ) . "分钟前";
            }

            $hours = $time / 3600;
            if ($hours < 48)
            {

                if (date ( 'Ymd', $create ) + 1 == date ( 'Ymd', $current ))
                {
                    return "昨天 " . date ( "H:i", $create );
                }
                elseif ($hours < 24)
                {
                    return round ( $hours ) . "小时前";
                }
            }

            return date ( "m月d日 H:i", $create );
        }
        else
        {
            return date ( "Y年m月d日 H:i", $create );
        }
    }
}

/**
 * @name display 模版显示
 */
if(! function_exists('display'))
{
    function display($tpname='', $data=array())
    {
        $CI =& get_instance();
        $CI->load->view('common/header', $data);
        $CI->load->view($tpname, $data);//加载页面
        $CI->load->view('common/footer');
    }
}

/**
 * 根据$split分割字符串
 *
 * @param $str 需要分割的字符串
 * @param $split 分割的条件
 *
 * @return array
 */
if(! function_exists('explode_to_array')){
    function explode_to_array($str, $split){
        $res = stripos($str,$split);
        if($res || 0 === $res){
            return explode($split,$str);
        }else{
            return array($str);
        }
    }
}

/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 * @return array
 */
if(! function_exists('object_to_array')){
    function object_to_array($obj) {
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (gettype($v) == 'resource') {
                return;
            }
            if (gettype($v) == 'object' || gettype($v) == 'array') {
                $obj[$k] = (array)object_to_array($v);
            }
        }
        return $obj;
    }
}

/**
 * ajax接口请求成功
 * @param $msg 提示信息
 * @param $data 返回的内容
 *
 */
if( ! function_exists('output_success'))
{
    function output_success($msg = '请求成功', $data = array())
    {
        $res = array(
            'code' => 1,
            'msg' => $msg,
            'data' => $data
        );
        output_str(json_encode($res));
    }
}

/**
 * ajax接口请求失败
 * @param $msg 提示信息
 * @param $data 返回的内容 理论上是没有的
 *
 */
if( ! function_exists('output_error'))
{
    function output_error($msg = '请求失败', $data = array())
    {
        $res = array(
            'code' => 0,
            'msg' => $msg,
            'data' => $data
        );
        output_str(json_encode($res));
    }
}

/**
 * 基础发起curl请求函数
 * @param int $method HTTP请求的方式 以下4种
 * 					get=0
 * 					post=1
 * 					put=2
 * 					delete=3
 * @param String $url url地址
 * @param String $data 参数
 * @param String $token 本项目的特殊头部 token
 *
 */
if (!function_exists('do_request')) {
    function do_request($params = array()) {
        $method=paramsIsNull($params, 'method')?'get':$params['method'];
        $url=paramsIsNull($params, 'url')?'':$params['url'];
        $data=paramsIsNull($params, 'data')?'':$params['data'];
        $header=paramsIsNull($params, 'header')?array():$params['header'];
       //var_dump($params);exit;

        $ch = curl_init();
        //初始化curl并判断是哪种请求方式
        switch(strtoupper($method)) {
            case 'GET':
                if($data){
                    $url .= '?' . getParamsString($data);
                }
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); //设置请求体，提交数据包
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); //设置请求体，提交数据包
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//https
        $res = curl_exec($ch);
        //运行curl
        curl_close($ch);
        return $res;
    }
}

/**
 * 获取某个日期是星期几
 *
 */
if( ! function_exists('get_week_name'))
{
    function get_week_name($datetime='')
    {
        if(!$datetime) $datetime = get_now_datetime();

        $cn_week_arr = array('星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六');

        return $cn_week_arr[date('w', strtotime($datetime))];
    }
}

/**
 * @name 数字转小数格式化显示
 * @param string $str 需要转换的数字字符
 * @param int $length 保留小数的位数
 * @param string $endStr 小数后续需要带的字符
 * @return string 返回格式化完成的字符
*/
if(! function_exists('is_number_format')){
    function is_number_format($str, $length=2, $endStr=""){
        $resultString = "";
        if($str){
            $resultString = " ".number_format(round(floatval($str),$length),$length).$endStr;
        }else{
            $resultString = " 0".$endStr;
        }
        return $resultString;
    }
}

/**
 * 判断是否是否为空
 * 		1 是否设置过
 * 		2 是否为NULL
 * 		3 是否为空字符串
 * 		4 是否是空array
 *
 * @param T $params
 *
 * @return boolean
 */
if(! function_exists('paramsIsNull')){
    function paramsIsNull($arr, $keyarr){//$keyarr 参数 可为string 或 array
        if(is_string($keyarr)){
            $keyarr = array($keyarr);
        }
        for($i=0;$i<count($keyarr);$i++){
            $name = $keyarr[$i];
            if(!isset($arr[$name]))return true;
            $params = $arr[$name];
            if(is_null($params))return true;
            if('' === $params)return true;
            if(is_array($params)){
                if(0 == count($params))return true;
            }
        }
        return false;
    }
}

/**
 * 验证数据
 * 		trim()
 * 		escape_str()转义字符串 防止sql注入
 *		未来可能要增加更多验证
 *
 * @param string $params		需要处理的数据
 *
 * @return string				处理过的数据
 */
if(! function_exists('verifyData')){
    function verifyData($arr, $key, $default='', $check=1){
        return paramsIsNull($arr, $key)?$default:escape_str(trim($arr[$key]));
    }
}

/**
 * @name escape_str 字符串转义 防止SQL注入
 */
if (!function_exists('escape_str')) {
    function escape_str($str, $like = FALSE)
    {
        if (is_array($str))
        {
            foreach ($str as $key => $val)
            {
                $str[$key] = escape_str($val, $like);
            }

            return $str;
        }

        if (function_exists('mysql_real_escape_string'))
        {
            $str = addslashes($str);
        }
        elseif (function_exists('mysql_escape_string'))
        {
            $str = mysql_escape_string($str);
        }
        else
        {
            $str = addslashes($str);
        }

        // escape LIKE condition wildcards
        if ($like === TRUE)
        {
            $str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
        }

        return $str;

    }
}

/**
 * @name 获得上传文件的信息
 * @param string filename          页面<input type="file"/>的name, 默认file
 * @param array  allow_imgtype    所允许的图片类型 array('.jpg', 'png')
 * @param array(
 *           'state' => ''        //上传文件的状态, 'SUCCESS' - 上传成功
 *           'original' => '',    //上传时的原名
 *           'path' => '',        //上传后的磁盘全路径
 *           'strs' => '',        //数据库存储路径
 *           'domain_type' => '', //上传后所在域名类型
 *           'size' => 0,         //文件大小，单位B
 *           'type' => '',        //文件后缀
 *        )
 */
if( ! function_exists('get_upload_fileinfo'))
{
    function get_upload_fileinfo($filename = 'file', $allow_type = array('.pdf'))
    {
        $rtn_arr = array();
        //先上传图片处理
        $CI = & get_instance();
        $CI->load->library('cls_uploader');
        $config = array( "maxSize" => 5120,  "allowFiles" => $allow_type);
        $CI->cls_uploader->upload_file($filename, $config);
        $info = $CI->cls_uploader->getFileInfo();
        //返回结果初始化
        $rtn_arr['state']       = $info['state'];
        $rtn_arr['original']    = $info['originalName'];
        $rtn_arr['path']        = $info['fullpath'];
        $rtn_arr['strs']        = $info['savepath'];
        $rtn_arr['domain_type'] = $info['domain_type'];
        $rtn_arr['size']        = $info['size'];
        $rtn_arr['type']        = $info['type'];

        if($info['state'] == 'SUCCESS') //上传成功
        {
            //特殊处理
        }

        unset($info, $config);
        return $rtn_arr;
    }
}

/**
 * @name volidateMobile 检测手机号码格式
 * @param string $mobile
 * @return boolean 返回检测结果
 */
if(! function_exists('volidateMobile'))
{
    function volidateMobile($mobile="")
    {
        if(strlen($mobile) == 11)
        {
            $res = preg_match_all("/13[123569]{1}\d{8}|15[1235689]\d{8}|188\d{8}/", $mobile, $array);
            if($array)
            {
                return true;
            }
        }
        return false;
    }
}

/** 函数名称:get_code()
 *作用:取得随机字符串
 * 参数:
1、(int)$length = 32 #随机字符长度
2、(int)$mode = 0    #随机字符类型，
0为大小写英文和数字,1为数字,2为小写字母,3为大写字母,
4为大小写字母,5为大写字母和数字,6为小写字母和数字
 *返回:取得的字符串
 */
if(! function_exists('get_random_code')) {

    function get_random_code($length = 32, $mode = 0)//获取随机验证码函数
    {
        switch ($mode) {
            case '1':
                $str = '123456789';
                break;
            case '2':
                $str = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case '3':
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case '4':
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case '5':
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                break;
            case '6':
                $str = 'abcdefghijklmnopqrstuvwxyz1234567890';
                break;
            default:
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
                break;
        }
        $checkstr = '';
        $len = strlen($str) - 1;
        for ($i = 0; $i < $length; $i++) {
            //$num=rand(0,$len);//产生一个0到$len之间的随机数
            $num = mt_rand(0, $len);//产生一个0到$len之间的随机数
            $checkstr .= $str[$num];
        }
        return $checkstr;
    }
}


// -----------------------------------------------------------------------

/* End of file common_helper.php */
/* Location: ./application/helpers/common_helper.php */