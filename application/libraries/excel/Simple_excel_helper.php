<?php

Class Simple_excel_helper{

	private $title;//标题
	private $data;//数据

	function __construct(){
	}

	
	public function setTitle($title){
		$this->title = $title;
	}
	
	public function setData($data){
		$this->data = $data;
	}
	
	/**
	 * 提供下载
	 *
	 * @param string $file_name 下载时的文件名
	 */
	public function download($file_name='new_excel'){
		//发送标题强制用户下载文件
		header("Content-Type: application/vnd.ms-excel;charset=UTF-8");
		header("Content-Disposition: attachment;filename=".$file_name.".xls");
		header("Cache-Control: max-age=0");
		$this->output();
	}
	
	/**
	 * 输出excel内容
	 */
	private function output(){
		$title = "";
		foreach ($this->title as $v){
			$title .= iconv("UTF-8", "gb2312",$v)."\t";
		}
		echo $title."\n";
		
		foreach ($this->data as $d){
			$a = "";
			foreach ($d as $v){
				$v = $this->isNumber($v);
				$a .= iconv("UTF-8", "gb2312",$v)."\t";
			}
			echo $a."\n";
		}
	}
	
	/**
	 * 追加内容方式的下载，需要传title
	 *
	 * @param string $file_name 下载时的文件名
	 * @param array $title excle的标题
	 * 
	 */
	public function appendDownload($file_name='new_excel', $title=array()){
		//发送标题强制用户下载文件
		header("Content-Type: application/vnd.ms-excel;charset=UTF-8");
		header("Content-Disposition: attachment;filename=".$file_name.".xls");
		header("Cache-Control: max-age=0");
		if($title){
			$t = "";
			foreach ($title as $v){
				$t .= iconv("UTF-8", "gb2312",$v)."\t";
			}
			echo $t."\n";
		}
	}
	
	/**
	 * 追加内容输出
	 */
	public function appendOutput($data){
		foreach ($data as $d){
			$a = "";
			foreach ($d as $v){
				$v = $this->isNumber($v);
				$a .= iconv("UTF-8", "gb2312",$v)."\t";
			}
			echo $a."\n";
		}
	}
	
	/**
	 * 针对超过10位的纯数字，会被科学计数法
	 * Enter description here ...
	 * @param unknown_type $str
	 */
	private function isNumber($str){
		$pattern = '/^-?\d{10,}$/';
    	if(preg_match($pattern,$str)){
    		$str = "=\"".$str."\""; 
    	}
    	return $str;
	}
}
