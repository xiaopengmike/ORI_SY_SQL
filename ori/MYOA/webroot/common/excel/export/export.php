<?php

/**
 * 导出数据公共方法类
 * 
 * @author ysr
 *
 */

class ExportModel
{

    /**
     * 封装Excel导出方法
     * @auther ysr
     * @param type $result 需要到出的结果集
     * @param type $titleArr 标题头数组，键值对的形式，键为$result中的下标，值为标题
     * @param type $workSheetName excel文件名和sheet名
     * @param type $title 首行标题
     */
    public function exportExcel($result, $titleArr, $workSheetName)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        if (PHP_SAPI == 'cli') {
            die('This example should only be run from a Web Browser');
        }

        require_once "/MYOA/webroot/inc/PHPExcelNew/PHPExcel.php";
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(35); // 設置單元格寬度
        $objPHPExcel->getActiveSheet()->setTitle($this->convertToUTF8($workSheetName)); // 設置當前工作表的名稱
        if (Count($result) > 0) {
            // 註：單元格第一豎是以0開始的，第一行是以1開始的。
            $index = 0;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, 1, $this->convertToUTF8($workSheetName));
            //设置跨列
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, 1, count($titleArr) - 1, 1);
            //设置水平居中
            $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //设置字体大小
            $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(20);
            //加粗
            $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
            //设置填充色
            $objPHPExcel->getActiveSheet()->getStyle("A1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle("A1")->getFill()->getStartColor()->setARGB('00b6c4cc');
            //设置边框
            $objPHPExcel->getActiveSheet()->getStyle("A1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            foreach ($titleArr as $k => $value) {
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($index, 2)->getFont()->setBold(true); //第一行标题加粗
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index, 2, $this->convertToUTF8($value));
                //设置border
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($index, 2)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($index, 2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $index++;
            }

            $row = 3;
            for ($i = 0; $i < count($result); $i++) {
                $j = 0;
                foreach ($titleArr as $k => $value) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j, $row + $i, $this->convertToUTF8($result[$i][$k]));
                    //设置border
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($j, $row + $i)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($j, $row + $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($j, $row + $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $j++;
                }
            }

            $objPHPExcel->createSheet(); // 創建一個新的工作表
            $objPHPExcel->setActiveSheetIndex(1); // 設置為當前工作表

            $objPHPExcel->setActiveSheetIndex(0); // 設置打開excel時顯示哪個工作表
        }
        $excelName = $workSheetName . date("YmdHis") . '.xls'; // 設置導出excel的文件名
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment; filename=" . $excelName);
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
        exit;
    }

    /**
     * * 导出excel时读取中文时调用此方法。
     * * @param string $str 需要转码的字符串 
     * * @return string
     */
    public function convertToUTF8($str)
    {
        if (!isset($str) || (empty($str) && $str != 0))
            return '';
        return @iconv('gbk', 'utf-8//IGNORE', $str);
    }
}
