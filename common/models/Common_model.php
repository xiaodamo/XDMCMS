<?php
/**
 * 公用Model
 *
 *
 */
class Common_Model extends CI_Model {
	/**
	 *
	 * @return Common_model
	 */
	function __construct() {
		parent::__construct ();
	}
	
	// --------------------------------------------------------------------
	/*
	 *
	 * 公用函数 , 判断是否在其他表里存在要删除的数据
	 * condition  可判断公司内部是否重复
	 */
	function check_delete($table = array(), $field_value = '',$condition="") {
// 		$table = array (
// 				'notice_goods' => 'notice_goods_id',
// 				'goods' => 'goods_id' 
// 		);
// 		$field_value = 1;
		
		if (empty ( $table ) || empty ( $field_value )) {
			
			return false;
		} else {
			foreach ( $table as $key => $value ) {
				
				$key = $this->db->dbprefix . $key;
				$sql = "select COUNT(" . $value . ") as total from " . $key . " where 1=1 AND " . $value . "= '" . $field_value."' ".$condition;
				$is_delete = $this->db->field_exists ( 'is_delete', $key );
				if ($is_delete) {
					$sql .= " AND is_delete <> 1";
				}
				
				$query = $this->db->query ( $sql );
				$row = $query->row_array ();
				$res [$key] = $row ['total'];
			}
			$result = array_sum ( $res );
			if (!empty($result)) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	/*
	 * 获取Excel内容
	 * $uploadfile:传入的文件,$colum为从哪列开始取,$row为从哪行开始取,$nums为取多少行：为0时取所有
	 */
	function excel_getContents($uploadfile,$colum='A',$row=2,$nums=0)
	{
	    //读取excel
	    //获取上传成功的Excel
	    if(!$uploadfile)
	    {
	        exit('URL为空，读取Excel内容失败');
	    }
	     
	    $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
	    $objReader = IOFactory::createReader('Excel5');//use excel2007 for 2007 format
	    $objPHPExcel = $objReader->load($uploadfile);//加载目标Excel
	    $sheet = $objPHPExcel->getSheet(0);//读取第一个sheet
	    $Rows = $nums?$nums:$sheet->getHighestRow(); // 取得总行数
	    $Columns = $sheet->getHighestColumn(); // 取得总列数
	    $con_arr =  array();
	    //入库
	    for($j=$row;$j<=$Rows;$j++)
	    {
	        $cel_arr = array();
	        for($k=$colum;$k<=$Columns;$k++)
	        {
	            //读取单元格
	            $cel_arr[] = $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
	        }
	         
	        $con_arr[] = $cel_arr;
	    }
	     
	    return $con_arr;
	}
	
	/*
	 * 导出Excel内容
	 * $fileName:文件名,$headArr:表头,$data:数据array
	 * 例：$fileName = "test_excel";
     *    $headArr = array("第一列","第二列","第三列");
     *    $data = array(array(1,2,3),array(1,3,4),array(5,7,9));
	 */
	function excel_putContents($fileName,$headArr,$data){
	    if(empty($data) || !is_array($data)){
	        die("数据内容为空或格式不正确");
	    }
	    if(empty($fileName)){
	        die("导出文件名为空");
	    }
	    $date = date("Y_m_d_His",time());
	    $fileName .= "_{$date}.xls";
	    
	    $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
	    //创建新的PHPExcel对象
	    $objPHPExcel = new PHPExcel();
	    $objProps = $objPHPExcel->getProperties();
	     
	    //设置表头
		//解决超过26个字母导出报错
    	$key = ord("A");//A--65
        $key2 = ord("@");//@--64</span>
        foreach($headArr as $v){
    		if($key>ord("Z")){
    			$key2 += 1;
    			$key = ord("A");
    			$colum = chr($key2).chr($key);//超过26个字母时才会启用  dingling 20150626
    		}else{
    			if($key2>=ord("A")){
    				$colum = chr($key2).chr($key);
    			}else{
    				$colum = chr($key);
    			}
    		}
    		$objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }

		$column = 2;
		$objActSheet = $objPHPExcel->getActiveSheet();

		foreach($data as $key => $rows){ //行写入
			$span = ord("A");
        $span2 = ord("@");
        foreach($rows as $k=>$v){
			if($span>ord("Z")){
				$span2 += 1;
				$span = ord("A");
				$j = chr($span2).chr($span);//超过26个字母时才会启用  dingling 20150626
			}else{
				if($span2>=ord("A")){
					$j = chr($span2).chr($span);
				}else{
					$j = chr($span);
				}
			}
			//$j = chr($span);
			$objActSheet->setCellValue($j.$column,$v);
            $span++;
            }
            $column++;
        }


		$fileName = iconv("utf-8", "gb2312", $fileName);
	    //重命名表
	    $objPHPExcel->getActiveSheet()->setTitle('内容');
	    //设置活动单指数到第一个表,所以Excel打开这是第一个表
	    $objPHPExcel->setActiveSheetIndex(0);
	    //将输出重定向到一个客户端web浏览器(Excel2007)
	    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
	    header('Pragma:public');
	    header('Content-Type:application/x-msexecl;name="'.$fileName.'"');
	    header('Content-Disposition:inline;filename="'.$fileName.'"');
	    header('Cache-Control: max-age=0');
	    $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
	    $objWriter->save('php://output');
	    exit;
	
	}


	 function excel_read($filename,$encode='utf-8'){
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		//创建新的PHPExcel对象
		 $objReader = IOFactory::createReader('Excel5');

		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($filename);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$excelData = array();
		for ($row = 1; $row <= $highestRow; $row++) {
			 for ($col = 0; $col < $highestColumnIndex; $col++) {
				$excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
			}
		}
		return $excelData;
	}


	
	
	
	
	
	
	
	
	
	
	
	
	
}