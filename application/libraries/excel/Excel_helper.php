<?php
include 'PHPExcel.php';
include 'PHPExcel/IOFactory.php';


Class Excel_helper{

	private $objPHPExcel;//excel对象
	private $sheet_num = 0;
	
	const MERGE_CELL_LIST = 'merge_cell_list';//合并的单元格列表
	const TITLE_STYLE = 'title_style';//头部样式
	const CELL_STYLE = 'cell_style';//单元格样式
	const STYLE_BACKGROUND = 'background';//背景颜色样式
    const STYLE_COLOR = 'style_color';
	
	
	/**
	 * 先new PHPExcel 对象
	 *
	 */
	function __construct(){
	    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_memcache;
	    $cacheSettings = array(
	        'memcacheServer' => '127.0.0.1',
            'memcachePort' => 11211,
            'cacheTime' => 600
        );
	    PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
		$this->objPHPExcel = new PHPExcel();
        $this->objPHPExcel->getDefaultStyle()->getFont()->setName('微软雅黑');
        $this->objPHPExcel->getDefaultStyle()->getFont()->setSize(11);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex(0))->setAutoSize(true);
    }

	/**
	 * 增加一个工作表的内容
	 *
	 * @param array $title_arr  	标题
	 * @param array $data_arr		数据内容
	 * @param string $sheet_name	工作表名称
	 */
	public function addSheet($title_arr, $data_arr, $sheet_name='', $merge_cell_list=array(), $other_params=array()){

	    $titleBg = isset($other_params['titleBg'])?$other_params['titleBg']:false;
        $titleColTotal = isset($other_params['titleColTotal'])?$other_params['titleColTotal']:0;
        $titleColIndex = isset($other_params['titleColIndex'])?$other_params['titleColIndex']:'';
        $customWidth = isset($other_params['customWidth'])?$other_params['customWidth']:false;
	    $widthList = isset($other_params['widthList'])?$other_params['widthList']:array();

		if($this->sheet_num != 0){
			$this->objPHPExcel->createSheet();
			$this->objPHPExcel->setActiveSheetIndex($this->sheet_num);
		}
		if($sheet_name){
			$this->objPHPExcel->getActiveSheet()->setTitle($sheet_name);;
		}
		//计算头部
		$row = 1;

        //标题加颜色
		if($title_arr){
			$col = 0;
			foreach ($title_arr as $title_index => $title)
			{
				if(is_array($title)){
					$col = 0;
					foreach ($title as $field){
						$this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $field);
					}
					$row++;
				}else{
					$this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, 1, $title);
					$row = 2;
				}

			}
			if($titleBg) {
			    if(is_array($titleColIndex)){
			        foreach($titleColIndex as $colIndex){
                        for ($i = 0; $i < $titleColTotal; $i++) {
                            $colsName = $this->num2Letter($i + 1);
                            $this->objPHPExcel->getActiveSheet()->getstyle($colsName . $colIndex)->getFill()->setFillType(PHPExcel_style_Fill::FILL_SOLID);
                            $this->objPHPExcel->getActiveSheet()->getstyle($colsName . $colIndex)->getFill()->getStartColor()->setRGB('000000');
                            $this->objPHPExcel->getActiveSheet()->getstyle($colsName . $colIndex)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));
                        }
                    }
                }else {
                    for ($i = 0; $i < $titleColTotal; $i++) {
                        $colsName = $this->num2Letter($i + 1);
                        $this->objPHPExcel->getActiveSheet()->getstyle($colsName . $titleColIndex)->getFill()->setFillType(PHPExcel_style_Fill::FILL_SOLID);
                        $this->objPHPExcel->getActiveSheet()->getstyle($colsName . $titleColIndex)->getFill()->getStartColor()->setRGB('000000');
                        $this->objPHPExcel->getActiveSheet()->getstyle($colsName . $titleColIndex)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));
                    }
                }
            }
			if($customWidth){
                for ($i = 0; $i < $titleColTotal; $i++) {
                    $colsName = $this->num2Letter($i + 1);
                    $withV = $widthList[$i];
                    $this->objPHPExcel->getActiveSheet()->getColumnDimension($colsName)->setWidth($withV);
                }
            }
		}
		
		//合并单元格
		if($merge_cell_list){
			foreach($merge_cell_list as $v){
				$this->objPHPExcel->getActiveSheet()->mergeCells($v);
				$cell = substr($v, 0, strpos($v, ':'));
				// 设置垂直居中
				$this->objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            	// 设置水平居中
            	$this->objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			}
		}

		//数据输出
		foreach($data_arr as $data)
		{
			foreach($data as $col => $v){
				if(0 == $col){
                    $this->objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $row,
                        $data[$col],
                        PHPExcel_Cell_DataType::TYPE_STRING);
				}else{
					$this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data[$col]);
				}
			}
			$row++;
		}

		$this->sheet_num++;
	}

	private function num2Letter($num) {
        $num = intval($num);
        if ($num <= 0)
            return false;
        $letterArr = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $letter = '';
        do {
            $key = ($num - 1) % 26;
            $letter = $letterArr[$key] . $letter;
            $num = floor(($num - $key) / 26);
        } while ($num > 0);
        return $letter;
    }

	/**
	 * 增加一个工作表的内容
	 *
	 * @param array $title_arr  	标题
	 * @param array $data_arr		数据内容
	 * @param string $sheet_name	工作表名称
	 * @param array $other_params	其他参数
	 * 		array MERGE_CELL_LIST 合并单元格列表
	 * 		array TITLE_STYLE 标题的样式 数组
	 * 		array CELL_STYLE 单元格的样式 列表
	 * 			STYLE_BACKGROUND 背景色样式 
	 * 
	 */
	public function addSheet2($title_arr, $data_arr, $sheet_name='', $other_params=array()){
		$merge_cell_list = isset($other_params[self::MERGE_CELL_LIST])?$other_params[self::MERGE_CELL_LIST]:array();
		$title_style = isset($other_params[self::TITLE_STYLE])?$other_params[self::TITLE_STYLE]:array();
		$cell_style = isset($other_params[self::CELL_STYLE])?$other_params[self::CELL_STYLE]:array();

		if($this->sheet_num != 0){
			$this->objPHPExcel->createSheet();
			$this->objPHPExcel->setActiveSheetIndex($this->sheet_num);
		}
		if($sheet_name){
			$this->objPHPExcel->getActiveSheet()->setTitle($sheet_name);;
		}
		//计算头部
		$row = 1;
		if($title_arr){
			$col = 0;
			foreach ($title_arr as $title_index => $title)
			{
				if(is_array($title)){
					$col = 0;
					foreach ($title as $field){
						$this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $field);
					}
					$row++;
				}else{
					$this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, 1, $title);
					$row = 2;
				}
				
				//头部样式
				if($title_style){
					$title_cell = $this->colIndex2chr($title_index);
					for($i=1; $i<$row; $i++){
						foreach($title_style as $title_style_k => $title_style_y){
							if($title_style_k == self::STYLE_BACKGROUND){
								$this->objPHPExcel->getActiveSheet()->getstyle($title_cell.$i)->getFill()->setFillType(PHPExcel_style_Fill::FILL_SOLID);
								$this->objPHPExcel->getActiveSheet()->getstyle($title_cell.$i)->getFill()->getStartColor()->setARGB($title_style_y);
							}
						}
					}
				}
			}
		}
		
		//指定单元格样式
		if($cell_style){
			_p($cell_style);
			foreach($cell_style as $cell_name => $cell_style_list){
				foreach($cell_style_list as $cell_style_k => $cell_style_y){
					if($cell_style_k == self::STYLE_BACKGROUND){
						$this->objPHPExcel->getActiveSheet()->getstyle($cell_name)->getFill()->setFillType(PHPExcel_style_Fill::FILL_SOLID);
						$this->objPHPExcel->getActiveSheet()->getstyle($cell_name)->getFill()->getStartColor()->setARGB($cell_style_y);
					}
				}
				
			}
		}
		//合并单元格
		if($merge_cell_list){
			foreach($merge_cell_list as $v){
				$this->objPHPExcel->getActiveSheet()->mergeCells($v);
				$cell = substr($v, 0, strpos($v, ':'));
				// 设置垂直居中
				$this->objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            	// 设置水平居中
            	$this->objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			}
		}
		//数据输出
		foreach($data_arr as $data)
		{
			foreach($data as $col => $v){
				if(0 == $col){
					$this->objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$row,
                                $data[$col],
                                 PHPExcel_Cell_DataType::TYPE_STRING);
				}else{
					$this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data[$col]);
				}
			}
			$row++;
		}
		$this->sheet_num++;
	}

	/**
	 * 提供下载
	 *
	 * @param string $file_name 下载时的文件名
	 */
	public function download($file_name='new_excel'){
		$objWriter = IOFactory::createWriter($this->objPHPExcel, 'Excel5');
		//发送标题强制用户下载文件
		ob_end_clean();//清除缓冲区,避免乱码
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$file_name.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}
	
	/**
	 * 保存目录
	 *
	 * @param string $file_path 保存目录
	 */
	public function save($file_path){
		$objWriter = IOFactory::createWriter($this->objPHPExcel, 'Excel5');
		$objWriter->save($file_path);
	}

	/**
	 *  
	 * @name   read()  读取excel内的数据
	 *
	 * @param  $name     		控件名
	 * @param  $sheet_name      工作表名称
	 * 
	 * 
	 * @return array    excel内的数据
	 **/
	public function read($name, $sheet_name=''){
		//设定缓存模式为经gzip压缩后存入cache（PHPExcel导入导出及大量数据导入缓存方式的修改 ）
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
		$cacheSettings = array();
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);

		$objPHPExcel = new PHPExcel();
		//读入上传文件
		$indata = null;
		if(isset($_FILES[$name]) && !$_FILES[$name]['error']){
			$objPHPExcel = IOFactory::load($_FILES[$name]["tmp_name"]);
			//内容转换为数组
			if($sheet_name){
				$sheet = $objPHPExcel->getSheetByName($sheet_name);
			}else{
				$sheet = $objPHPExcel->getSheet(0);
			}
			if($sheet){
				$indata = $sheet->toArray();
			}
		}
		return $indata;
	}

	public function read_file($file_path, $sheet_name=''){
		//设定缓存模式为经gzip压缩后存入cache（PHPExcel导入导出及大量数据导入缓存方式的修改 ）
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
		$cacheSettings = array();
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);

		$objPHPExcel = new PHPExcel();
		//读入上传文件
		$indata = null;
		if(isset($file_path) && $file_path){
			$objPHPExcel = IOFactory::load($file_path);
			//内容转换为数组
			if($sheet_name){
				$sheet = $objPHPExcel->getSheetByName($sheet_name);
			}else{
				//$sheet = $objPHPExcel->getSheet(0);
			}
			if($sheet){
				$indata = $sheet->toArray();
			}
		}
		return $indata;
	}

	/**
     * @name 根据sheet名称获取信息
	*/
	public function chkSheetByName($file_path){
	    $chkStatus = false;
	    try {
            //设定缓存模式为经gzip压缩后存入cache（PHPExcel导入导出及大量数据导入缓存方式的修改 ）
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
            $cacheSettings = array();
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

            $objPHPExcel = new PHPExcel();
            //读入上传文件
            $indata = null;
            if (isset($file_path) && $file_path) {
                $objPHPExcel = IOFactory::load($file_path);
                //内容转换为数组
                $sheetList = $objPHPExcel->getSheetNames();
                /*
                foreach($sheetList as $sheet){
                    if($sheet==$sheet_name){
                        $chkStatus = true;
                        break;
                    }
                }
                */
            }
        }catch (Exception $err){
            log_message("error", $err->getMessage());
        }finally{
	        return $sheetList;
        }
    }
	/**
	 * 计算 列转 成 ABCDE 列名
	 */
	public function colIndex2chr($index=0){
		if($index >= 26){
			return $this->index2chr($index/26-1).chr($index%26+65);
		}else{
			return chr($index+65);
		}
	}
}
