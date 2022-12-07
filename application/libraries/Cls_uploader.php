<?php
/**
 * Created by JetBrains PhpStorm.
 * User: taoqili
 * Date: 12-7-18
 * Time: 上午11: 32
 * UEditor编辑器通用上传类
 */
date_default_timezone_set("PRC");
class Cls_uploader
{
    private $fileField;            //文件域名
    private $file;                 //文件上传对象
    private $config;               //配置信息
    private $oriName;              //原始文件名
    private $fileName;             //新文件名
    private $savePath;             //保存到数据库的路径
    private $fullPath;             //文件的磁盘路径
    private $domainType;           //文件域的类型
    private $fileSize;             //文件大小
    private $fileType;             //文件类型
    private $stateInfo;            //上传状态信息,
    private $stateMap = array(     //上传状态映射表，国际化用户需考虑此处数据的国际化
        "SUCCESS" ,                //上传成功标记，在UEditor中内不可改变，否则flash判断会出错
        "文件大小超出 upload_max_filesize 限制" ,
        "文件大小超出 MAX_FILE_SIZE 限制" ,
        "文件未被完整上传" ,
        "没有文件被上传" ,
        "上传文件为空" ,
        "POST" => "文件大小超出 post_max_size 限制" ,
        "SIZE" => "文件大小超出网站限制" ,
        "TYPE" => "不允许的文件类型" ,
        "DIR" => "目录创建失败" ,
        "IO" => "输入输出错误" ,
        "UNKNOWN" => "未知错误" ,
        "MOVE" => "文件保存时出错",
        "SYSERR" => "文件系统繁忙"
    );

    public function __construct(){ }
    
    /**
     * 上传函数
     * @param string $fileField 表单名称
     * @param array $config  配置项
     */
    public function upload_file( $fileField , $config)
    {
        $this->fileField = $fileField;
        $this->config = $config;
        $this->stateInfo = $this->stateMap[ 0 ];
        $this->upFile();
    }
    
    /**
     * 上传文件的主处理方法
     * @return mixed
     */
    private function upFile()
    {
        //处理普通上传
        $file = $this->file = $_FILES[ $this->fileField ];
        if ( !$file ) {
            $this->stateInfo = $this->getStateInfo( 'POST' );
            return;
        }
        if ( $this->file[ 'error' ] ) {
            $this->stateInfo = $this->getStateInfo( $file[ 'error' ] );
            return;
        }
        if ( !is_uploaded_file( $file[ 'tmp_name' ] ) ) {
            $this->stateInfo = $this->getStateInfo( "UNKNOWN" );
            return;
        }

        $this->oriName = $file[ 'name' ];
        $this->fileSize = $file[ 'size' ];
        $this->fileType = $this->getFileExt();

        if ( !$this->checkSize() ) {
            $this->stateInfo = $this->getStateInfo( "SIZE" );
            return;
        }
        if ( !$this->checkType() ) {
            $this->stateInfo = $this->getStateInfo( "TYPE" );
            return;
        }

        //------------------------------ 上传 begin -----------------------------//
        $fi = $this->create_newfile_path(mt_rand(1,10000), $this->fileType);//$fi['path']$fi['strs']
        if(!empty($fi['path']))
        {
            if ( $this->stateInfo == $this->stateMap[0] ) {
                if ( !move_uploaded_file( $file[ "tmp_name" ] , $fi['path']) ) {
                    $this->stateInfo = $this->getStateInfo( "MOVE" );
                }else{
                    $this->domainType=1; //默认1
                    $this->savePath = FILE_SAVE_NAME.$fi['strs'];
                    $this->fullPath = $fi['path'];
                }
            }
        }
        else
        {
          $this->stateInfo = $this->getStateInfo( "FILESYS" );
        }
        //-------------------------------- 上传 end -------------------------------//
        
    }

/**
 * 获取当前上传成功文件的各项信息
 * @return array(
 *     "originalName" => "",   //原始文件名
 *     "name" => "",           //新文件名
 *     "savepath" => "",       //返回的存储地址
 *     "domain_type" => "",    //文件域的类型
 *     "size" => "",           //文件大小
 *     "type" => "" ,          //文件类型
 *     "state" => ""           //上传状态，上传成功时必须返回"SUCCESS"
 * )
**/
    public function getFileInfo()
    {
        return array(
            "originalName" => $this->oriName ,
            "name" => $this->fileName ,
            "savepath" => $this->savePath ,
            "domain_type" =>$this->domainType,
            "size" => $this->fileSize ,
            "type" => $this->fileType ,
            "state" => $this->stateInfo,
            'fullpath' => $this->fullPath
        );
    }    
    /**
     * 上传错误检查
     * @param $errCode
     * @return string
     */
    private function getStateInfo( $errCode )
    {
        return !$this->stateMap[ $errCode ] ? $this->stateMap[ "UNKNOWN" ] : $this->stateMap[ $errCode ];
    }

    /**
     * 文件类型检测
     * @return bool
     */
    private function checkType()
    {
        return in_array( $this->getFileExt() , $this->config[ "allowFiles" ] );
    }

    /**
     * 文件大小检测
     * @return bool
     */
    private function  checkSize()
    {
        return $this->fileSize <= ( $this->config[ "maxSize" ] * 1024 );
    }
    
    /**
     * 获取文件扩展名
     * @return string
     */
    private function getFileExt()
    {
        return strtolower( strrchr( $this->file[ "name" ] , '.' ) );
    }
    
    /**
     * @name mkdirs 创建文件夹
     * @param $dir 需要创建的文件夹路径
     * @return boolean 返回创建状态
    */
    private function mkdirs($dir)
	{
		if (!is_dir($dir))
		{
			if (!$this->mkdirs(dirname($dir)))
			{
				return false;
			}
			if (!mkdir($dir, 0777))
			{
				return false;
			}
		}
		return true;
	}
    
    
    /**
     * @name   根据原文件生成一个全新的文件磁盘路径
     * @param  string diskfile_path  原文件磁盘路径
     * @param  string fileExt        文件类型，如'.jpg','.rar'; 当diskfile_path为临时文件时此值才必须传
     * @return array('code'=>1, 'path'=>)
     */
    private function create_newfile_path($diskfile_path='', $fileExt='')
    {
        $return_arr = array('code'=>'1', 'path'=>'');
        //1. 生成第一级目录 以时间命名如：201307
        $first_dir = date('Ym');
        if( ! file_exists(FILE_DIR.'/'.$first_dir))
        {
            if( ! mkdir(FILE_DIR.'/'.$first_dir, 0777 , TRUE))
            {
                $return_arr = array('code'=>'-1', 'path'=>'');
                return $return_arr;
            }
        }
        
        $unique_str = md5(date('YmdHis').uniqid().mt_rand(0 ,10000).$diskfile_path);
        
        //2. 生成第二层
//      $second_dir = substr($unique_str, 0, 2);
		$second_dir = date('d');
        $newfile_dir = FILE_DIR.'/'.$first_dir.'/'.$second_dir;
        log_message("error", "保存的路径为：".$newfile_dir);
        if(!file_exists($newfile_dir)){ 
            if( ! mkdir($newfile_dir, 0777 , TRUE))
            {
                $return_arr = array('code'=>'-2', 'path'=>'');
                return $return_arr;
            }
        }
        
        //3. 生成文件名
        $ext = $this->getFileExt($diskfile_path);//获得文件扩展名
        if(empty($ext))
        {
            $ext = $fileExt;
        }
        
        $ext = str_replace('.', '', $ext);        
        $file_name = substr($unique_str, 2, 14).'.'.$ext;
        $return_arr = array('code'=>'1', 'path'=>$newfile_dir.'/'.$file_name, 'strs'=>$first_dir.'/'.$second_dir.'/'.$file_name);
        return $return_arr;
    }
}