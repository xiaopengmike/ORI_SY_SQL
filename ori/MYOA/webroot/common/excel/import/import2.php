<?php

// include_once "inc/auth.inc.php";
// include_once "inc/utility_all.php";
// include_once "inc/check_type.php";
// $HTML_PAGE_TITLE = "导入数据";
// include_once "inc/header.inc.php";
// include_once "common/ExcelModel.php";
// $model = new ExcelModel();

// $paras = $_POST;
// $paramArr = array(
//     'formatType' => $paras['format_type'],
//     'fileName' => $paras['FILE_NAME'],
//     'groupId' => $paras['GROUP_ID'],
//     'tableName' => $paras['table_name'],
//     'isExtra' => $paras['is_extra'],
// );

// // echo "\r\n\r\n\r\n<body class=\"bodycolor\">\r\n";
// $CUR_DATE = date("Y-m-d", time());
// if (strtolower(substr($FILE_NAME, -3)) != "xls" && strtolower(substr($FILE_NAME, -4)) != "xlsx") {
//     message("错误", "只能导入Excel文件!");
//     button_back();
//     exit;
// }


include_once "common/excel/ExcelModel.php";

//引用PHPexcel 类
include('inc/PHPExcelNew/PHPExcel.php');  //引入PHP EXCEL类  

$file = $EXCEL_FILE;
$objRead = new PHPExcel_Reader_Excel2007();   //建立reader对象
if (!$objRead->canRead($file)) {
    $objRead = new PHPExcel_Reader_Excel5();
    if (!$objRead->canRead($file)) {
        die('No Excel!');
    }
}
$Sheets = $objRead->load($file);

//开始读取上传到服务器中的Excel文件，返回一个二维数组
$dataArray = $Sheets->getSheet(0)->toArray();
var_export($dataArray);
exit;
return $dataArray;

var_export($EXCEL_FILE);exit;
exit;

$data = $model->importExcel($EXCEL_FILE);
// var_dump($model->array_iconv($data));exit;

/**
 * formatType参数说明： 
 * 1：表头为字段名，内容为数值，无单元格合并情况； 
 * 2：表头为字段名对应的中文描述名称，内容为数值，无单元格合并情况
 * 3：第一行为标题，第二行为字段名对应的中文描述名称，内容为数值，表头及内容无单元格合并情况（与类型2类似）
 * 4：表头存在单元格合并情况，特殊处理
 * */ 

 /**
  * 字段名对应的中文描述数组，务必保持一致性
  * 修改为查询数据库参数，保证数据库字段名及备注与表格名称对应即可，减少代码修改任务 
  */
// $colSql = "SELECT a.COLUMN_NAME  col_name, a.COLUMN_COMMENT col_desc 
//             FROM information_schema.COLUMNS a
//             JOIN information_schema.TABLES b ON a.TABLE_SCHEMA = b.TABLE_SCHEMA AND a.TABLE_NAME = b.TABLE_NAME 
//             WHERE a.TABLE_SCHEMA = 'TD_OA' 
//             and a.TABLE_NAME = '".$paramArr['tableName']."' 
//         ";
// $colRes = exequery(TD::conn(),$colSql);
// $colDescArr = array();
// while($item = mysql_fetch_assoc($colRes)){
//     $colDescArr[$item['col_desc']] = $item['col_name'];
// }

// $extraColArr = array();
// if($paramArr['isExtra']){
//     $extraColArr = array(
//         'create_user' => $_SESSION['LOGIN_USER_ID'],
//         'create_time' => date('Y-m-d H:i:s',time())
//     );
// }

// $columnArr = array();
// $insertCol = "";
// $colCount = 0;
// $titleArr = array();
// $beginCol = 0;
// $needConvert = true;
// // 针对表格格式类型作分别处理
// if($paramArr['formatType'] == '1'){
//     $titleArr = $data[1];
//     $beginCol = 2;
//     $needConvert = false;
// } 
// else if($paramArr['formatType'] == '2'){
//     $titleArr = $data[1];
//     $beginCol = 2;
// }
// else if($paramArr['formatType'] == '3'){
//     $titleArr = $data[2];
//     $beginCol = 3;
// }
// else if($paramArr['formatType'] == '4'){
//     // 4：表头存在单元格合并情况，特殊处理（暂无此类情况，后续按实际情况补充完善）
// } else {
//     // 默认按情况2作处理（最常见情况）
//     $titleArr = $data[1];
//     $beginCol = 2;
// }

// if(!$needConvert){
//     $insertCol .= "insert into ".$paramArr['tableName']." (";
//     foreach($titleArr as $cellName => $cellValue){
//         $columnArr[$cellName] = $cellValue;
//         $insertCol .= $cellValue.",";
//         $colCount += 1;
//     }
//     foreach($extraColArr as $eck => $ecv){
//         $insertCol .=  $eck.",";
//     }
//     if($colCount > 0){
//         $insertCol = rtrim($insertCol,',');
//         $insertCol .= ") values";
//     } else {
//         $insertCol = "";
//     }
// } else {
//     $insertCol .= "insert into ".$paramArr['tableName']." (";
//     foreach($titleArr as $cellName => $cellValue){
//         if(array_key_exists($cellValue,$colDescArr)){
//             $columnArr[$cellName] = $colDescArr[$cellValue];
//             $insertCol .= $colDescArr[$cellValue].",";
//             $colCount += 1;
//         }
//     }
//     foreach($extraColArr as $eck => $ecv){
//         $insertCol .=  $eck.",";
//     }
//     if($colCount > 0){
//         $insertCol = rtrim($insertCol,',');
//         $insertCol .= ") values";
//     } else {
//         $insertCol = "";
//     }
// }

// $ROW_COUNT = 0;
// $MSG_ERROR = "";
// $insertVal = "";
// for($i=$beginCol;$i<=count($data);$i++){
//     $cellArr = $data[$i];
//     $insertVal = "(";
//     foreach($columnArr as $ck => $cv){
//         $insertVal .= "'".$cellArr[$ck] . "',";
//     }
//     foreach($extraColArr as $ecv){
//         $insertVal .=  "'".$ecv . "',";
//     }
//     $insertVal = rtrim($insertVal,',');
//     $insertVal .= ")";
    
//     $sql = $insertCol . $insertVal;
//     exequery(TD::conn(), $sql);
//     ++$ROW_COUNT;
// }

// // exit;

// if (file_exists($EXCEL_FILE)) {
//     @unlink($EXCEL_FILE);
// }
// $MSG6 = sprintf("共%d条数据导入!", $ROW_COUNT);
// message("", $MSG6);
// echo $MSG_ERROR;
// echo "<div align=\"center\"><input type=\"button\" value=\"";
// echo "返回";
// echo "\" class=\"BigButton\"\r\n\tonClick=\"location='pre_import.php';\" title=\"";
// echo "返回";
// echo "\"></div>\r\n</body>\r\n</html>\r\n";