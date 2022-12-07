<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @name    微信昵称
 * @author  2020-01-19 by wing
 * @version 1.0.0
 */

// ------------------------------------------------------------------------

//编码
if( ! function_exists('emoji_encode'))
{
    function emoji_encode($str){
        $strEncode = '';

        $length = mb_strlen($str,'utf-8');

        for ($i=0; $i < $length; $i++) {
            $_tmpStr = mb_substr($str,$i,1,'utf-8');
            if(strlen($_tmpStr) >= 4){
                $strEncode .= '[[EMOJI:'.rawurlencode($_tmpStr).']]';
            }else{
                $strEncode .= $_tmpStr;
            }
        }

        return $strEncode;
    }
}


//对emoji表情转反义
if( ! function_exists('emoji_decode'))
{
    function emoji_decode($str){
        $strDecode = preg_replace_callback('|\[\[EMOJI:(.*?)\]\]|', function($matches){
            return rawurldecode($matches[1]);
        }, $str);
        return $strDecode;
    }
}

//emoji表情转化为HTML实体字符
if( ! function_exists('utf16_to_entities'))
{
    function utf16_to_entities($content = '')
    {
        $content = mb_convert_encoding($content, 'utf-16');
        $bin = bin2hex($content);
        $arr = str_split($bin, 4);
        $l = count($arr);
        $str = '';

        for ($n = 0; $n < $l; $n++) {

            if (isset($arr[$n + 1]) && ('0x' . $arr[$n] >= 0xd800 && '0x' . $arr[$n] <= 0xdbff && '0x' . $arr[$n + 1] >= 0xdc00 && '0x' . $arr[$n + 1] <= 0xdfff)) {
                $H = '0x' . $arr[$n];
                $L = '0x' . $arr[$n + 1];
                $code = ($H - 0xD800) * 0x400 + 0x10000 + $L - 0xDC00;
                $str.= '&#' . $code . ';';
                $n++;
            } else {
                $str.=mb_convert_encoding(hex2bin($arr[$n]),'utf-8','utf-16');
            }
        }
        return $str;
    }
}
