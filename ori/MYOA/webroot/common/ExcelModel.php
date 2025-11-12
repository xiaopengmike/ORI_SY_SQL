<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

/**
 * 用户数据公共操作类
 */
class ExcelModel
{
    /** 
     * 数据导出 
     * @param array $title   标题行名称 
     * @param array $data   导出数据 
     * @param string $fileName 文件名 
     * @param string $savePath 保存路径 
     * @param $type   是否下载  false--保存   true--下载 
     * @return string   返回文件全路径 
     * @throws PHPExcel_Exception 
     * @throws PHPExcel_Reader_Exception 
     */
    //格式： exportExcel(array('姓名','年龄'), array(array('a',21),array('b',23)), '档案', './', true);
    function exportExcel($title = array(), $data = array(), $fileName = '', $savePath = './', $isDown = false)
    {
        include('inc/PHPExcelNew/PHPExcel.php');
        $obj = new PHPExcel();
        //横向单元格标识  
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        //$obj->getActiveSheet(0)->setTitle('sheet名称');   //设置sheet名称  
        $_row = 1;   //设置纵向单元格标识  
        if ($title) {
            // $_cnt = count($title);
            // $obj->getActiveSheet(0)->mergeCells('A' . $_row . ':' . $cellName[$_cnt - 1] . $_row);   //合并单元格  
            // $obj->setActiveSheetIndex(0)->setCellValue('A' . $_row, '数据导出：' . date('Y-m-d H:i:s'));  //设置合并后的单元格内容  
            // $_row++;
            $i = 0;
            foreach ($title as $v) {   //设置列标题  
                $obj->setActiveSheetIndex(0)->setCellValue($cellName[$i] . $_row, iconv("gbk", "utf-8", $v));
                $i++;
            }
            $_row++;
        }
        //填写数据  
        if ($data) {
            $i = 0;
            foreach ($data as $_v) {
                $j = 0;
                foreach ($_v as $_cell) {
                    $obj->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + $_row), $_cell);
                    $j++;
                }
                $i++;
            }
        }
        //文件名处理 
        if (!$fileName) {
            $fileName = uniqid(time(), true);
        }

        $objWrite = PHPExcel_IOFactory::createWriter($obj, 'Excel2007');

        if ($isDown) {   //网页下载  
            header("Content-type: text/html; charset=gbk");
            header('pragma:public');
            header("Content-Disposition:attachment;filename=$fileName.xls");
            $objWrite->save('php://output');
            exit;
        }

        $_fileName = iconv("utf-8", "gb2312", $fileName);   //转码  
        $_savePath = $savePath . $_fileName . '.xlsx';
        $objWrite->save($_savePath);
        return $savePath . $fileName . '.xlsx';
    }


    /** 
     *  数据导入
     * @param string $file excel文件 
     * @param string $sheet 
     * @return string   返回解析数据 
     * @throws PHPExcel_Exception 
     * @throws PHPExcel_Reader_Exception 
     */
    function importExcel($file = '', $sheet = 0)
    {
        $file = iconv("utf-8", "gb2312", $file);   //转码  
        if (empty($file) or !file_exists($file)) {
            die('file not exists!');
        }
        include('inc/PHPExcelNew/PHPExcel.php');  //引入PHP EXCEL类  
        $objRead = new PHPExcel_Reader_Excel2007();   //建立reader对象  
        if (!$objRead->canRead($file)) {
            $objRead = new PHPExcel_Reader_Excel5();
            if (!$objRead->canRead($file)) {
                die('No Excel!');
            }
        }
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        $obj = $objRead->load($file);  //建立excel对象  
        $currSheet = $obj->getSheet($sheet);   //获取指定的sheet表  
        $columnH = $currSheet->getHighestColumn();   //取得最大的列号  
        $columnCnt = array_search($columnH, $cellName);
        $rowCnt = $currSheet->getHighestRow();   //获取总行数  
        $data = array();
        for ($_row = 1; $_row <= $rowCnt; $_row++) {  //读取内容  
            for ($_column = 0; $_column <= $columnCnt; $_column++) {
                $cellId = $cellName[$_column] . $_row;
                $cellValue = $currSheet->getCell($cellId)->getValue();
                //$cellValue = $currSheet->getCell($cellId)->getCalculatedValue();  #获取公式计算的值  
                if ($cellValue instanceof PHPExcel_RichText) {   //富文本转换字符串  
                    $cellValue = $cellValue->__toString();
                }
                // $data[$_row][$cellName[$_column]] = $cellValue;
                $data[$_row][$cellName[$_column]] = iconv('utf-8','gbk',$cellValue);    // 中文编码转换返回
            }
        }
        return $data;
    }

    /**
     * 导出多sheet的excel表格
     * 初步用于导出MySQL数据库数据字典，后续完善为通用方法
     * 待完善补充
     */
    function exportExcelSheets($title = array(), $data = array(), $fileName = '', $savePath = './', $isDown = false)
    {
        //格式： exportExcel(array('姓名','年龄'), array(array('a',21),array('b',23)), '档案', './', true);
        include('inc/PHPExcelNew/PHPExcel.php');
        $obj = new PHPExcel();
        //横向单元格标识  
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        $obj->getActiveSheet(0)->setTitle('sheet名称');   //设置sheet名称  

        //设置当前sheet索引,用于后续的内容操作
        //一般用在对个Sheet的时候才需要显示调用
        //缺省情况下,PHPExcel会自动创建第一个SHEET被设置SheetIndex=0
        //设置SHEET
        // $obj->setactivesheetindex(0);
        //创建一个新的工作空间(sheet)
        // $obj->createSheet();
        // $obj->setactivesheetindex(1);

        $_row = 1;   //设置纵向单元格标识  
        if ($title) {
            $_cnt = count($title);
            $obj->getActiveSheet(0)->mergeCells('A' . $_row . ':' . $cellName[$_cnt - 1] . $_row);   //合并单元格  
            $obj->setActiveSheetIndex(0)->setCellValue('A' . $_row, '数据导出：' . date('Y-m-d H:i:s'));  //设置合并后的单元格内容  
            $_row++;
            $i = 0;
            foreach ($title as $v) {   //设置列标题  
                $obj->setActiveSheetIndex(0)->setCellValue($cellName[$i] . $_row, $v);
                $i++;
            }
            $_row++;
        }
        //填写数据  
        if ($data) {
            $i = 0;
            foreach ($data as $_v) {
                $j = 0;
                foreach ($_v as $_cell) {
                    $obj->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + $_row), $_cell);
                    $j++;
                }
                $i++;
            }
        }
        //文件名处理 
        if (!$fileName) {
            $fileName = uniqid(time(), true);
        }

        $objWrite = PHPExcel_IOFactory::createWriter($obj, 'Excel2007');

        if ($isDown) {   //网页下载  
            
            header('pragma:public');
            header("Content-Disposition:attachment;filename=$fileName.xls");
            $objWrite->save('php://output');
            exit;
        }

        $_fileName = iconv("utf-8", "gb2312", $fileName);   //转码  
        $_savePath = $savePath . $_fileName . '.xlsx';
        $objWrite->save($_savePath);
        return $savePath . $fileName . '.xlsx';
    }

    /**
     * 将含有GBK的中文数组转为utf-8
     *
     * @param array $arr   数组
     * @param string $in_charset 原字符串编码
     * @param string $out_charset 输出的字符串编码
     * @return array
     */
    function array_iconv($arr, $in_charset="gbk", $out_charset="utf-8")
    {
        $ret = eval('return '.iconv($in_charset,$out_charset,var_export($arr,true).';'));
        // return $ret;
        // 这里转码之后可以输出json
        return json_encode($ret);
    }

    /**
     * 导出Excel数据表格
     * @param  array    $dataList     要导出的数组格式的数据
     * @param  array    $headList     导出的Excel数据第一列表头
     * @param  string   $fileName     输出Excel表格文件名
     * @param  string   $exportUrl    直接输出到浏览器or输出到指定路径文件下
     * @return bool|false|string
     */
    public static function toExcel($dataList,$headList,$fileName,$exportUrl){
        //set_time_limit(0);//防止超时
        //ini_set("memory_limit", "512M");//防止内存溢出
        //header("Content-type: text/html; charset=gbk");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$fileName.'.csv"');
        header('Cache-Control: max-age=0');
        //打开PHP文件句柄,php://output 表示直接输出到浏览器,$exportUrl表示输出到指定路径文件下
        $fp = fopen($exportUrl, 'a');

        //输出Excel列名信息
        // foreach ($dataList as $key => $value) {
        //     //CSV的Excel支持GBK编码，一定要转换，否则乱码
        //     $dataList[$key] = iconv('gbk', 'utf-8', $value);
        // }
        
        //将数据通过fputcsv写到文件句柄
        fputcsv($fp, $headList);

        //计数器
        $num = 0;

        //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;

        //逐行取出数据，不浪费内存
        $count = count($dataList);
        for ($i = 0; $i < $count; $i++) {

            $num++;

            //刷新一下输出buffer，防止由于数据过多造成问题
            if ($limit == $num) {
                ob_flush();
                flush();
                $num = 0;
            }

            $row = $dataList[$i];
            foreach ($row as $key => $value) {
                $row[$key] = iconv('utf-8', 'gbk', $value);
            }
            fputcsv($fp, $row);
        }
        return $fileName;
    }

}
