<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @name 文件操作类
 */

// ------------------------------------------------------------------------

class Cls_fileutil
{
    private $f; //--文件类库对象
    
    function __construct()
    {
        //include_once '/www/admin/robots/lib/kykFile.php';
        //$this->f = new kykFile(IMG_DOMAIN_TYPE);
    }
    
    /**
     * @name 获得文件访问URL
     * @param string $save_path   数据库存储路径
     * @param int    $domain_type 文件域名类型
     * @param string $type        多规格类型
     * @return string
     */
    function get_file_url($save_path='', $domain_type=0, $type='')
    {
        //return $this->f->get_url($save_path, $domain_type, $type);
        return FILE_URL.$save_path;
    }
    
    /**
     * @name 获得文件磁盘路径
     * @param string $save_path   数据库存储路径
     * @param int    $domain_type 文件域名类型
     * @param string $type        多规格类型
     * @return string
     */
    function get_file_path($save_path='', $domain_type=0, $type='')
    {
        return $this->f->get_path($save_path, $domain_type, $type);
    }
    
    /**
     * @name 从文件系统中获得一个新文件路径
     * @param string ext 新文件的文件类型
     * @return array(
     *            'path'=>'', //文件磁盘路径
     *            'strs'=>'', //存储到数据库的路径
     *            'domain_type'=>1 //文件所在域名类型 
     *         )
     */
    function get_new_file_path($ext='jpg')
    {
        $path_arr = $this->f->star_name($ext);
        return array('path'=>$path_arr['path'], 'strs'=>$path_arr['strs'], 'domain_type'=>$path_arr['domain_type']);
    }
    
    /**
     * @name 根据URL反查 文件信息
     * @return array('code'=>'1', 'path'=>'/var/.../ssfasfas.jpg', 'strs'=>'/2013/7c/asdf123sfa12af.jpg', 'domain_type'=>1)
     *         code => 1 返回成功；
     *         code => 0 返回失败；
     */
    function get_file_byurl($file_url='')
    {
        $path_arr = $this->f->get_file_byurl($file_url);
        return $path_arr;
    }
    
    /**
     * @name  删除文件
     * @param string $save_path   数据库存储路径
     * @param int    $domain_type 文件域名类型
     * @param string $type        多规格类型
     */
    function delete_file($save_path='', $domain_type=0, $type='')
    {
        $file_path = $this->f->get_path($save_path, $domain_type, $type);
        if($file_path)
        {
            @unlink($file_path);
        }
    }
    
    function __destruct()
    {
        if($this->f) unset($this->f);
    }
}
/* End of file Cls_fileutil.php */
/* Location: ./application/libraries/Cls_fileutil.php */