<?php

include_once "inc/auth.inc.php";
include_once "inc/utility_all.php";
include_once "inc/check_type.php";
$HTML_PAGE_TITLE = "导入数据";
include_once "inc/header.inc.php";
include_once("common/Common.php");
include_once("common/ExcelModel.php");
$model = new ExcelModel();

?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/attach.js"></script>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></SCRIPT>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">

<?php

$paras = $_POST;
$paramArr = array(
    'formatType' => $paras['format_type'],
    'fileName' => $paras['FILE_NAME'],
    'groupId' => $paras['GROUP_ID'],
    'tableName' => $paras['table_name'],
    'isExtra' => $paras['is_extra'],
);

$CUR_DATE = date("Y-m-d", time());
if (strtolower(substr($FILE_NAME, -3)) != "xls" && strtolower(substr($FILE_NAME, -4)) != "xlsx") {
    message("错误", "只能导入Excel文件!");
    button_back();
    exit;
}

$data = $model->importExcel($EXCEL_FILE);
// var_export($model->array_iconv($data));exit;
// var_export($data);exit;

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
$colSql = "SELECT a.COLUMN_NAME  col_name, a.COLUMN_COMMENT col_desc 
            FROM information_schema.COLUMNS a
            JOIN information_schema.TABLES b ON a.TABLE_SCHEMA = b.TABLE_SCHEMA AND a.TABLE_NAME = b.TABLE_NAME 
            WHERE a.TABLE_SCHEMA = 'TD_OA' 
            and a.TABLE_NAME = '" . $paramArr['tableName'] . "' 
        ";
$colRes = exequery(TD::conn(), $colSql);
$colDescArr = array();
while ($item = mysql_fetch_assoc($colRes)) {
    $colDescArr[$item['col_desc']] = $item['col_name'];
}

$extraColArr = array();
if ($paramArr['isExtra']) {
    $extraColArr = array(
        'user_id' => $_SESSION['LOGIN_USER_ID'],
        'create_time' => date('Y-m-d H:i:s', time())
    );
}

$columnArr = array();
$insertCol = "";
$colCount = 0;
$titleArr = array();
$beginCol = 0;
$needConvert = true;
// 针对表格格式类型作分别处理
if ($paramArr['formatType'] == '1') {
    $titleArr = $data[1];
    $beginCol = 2;
    $needConvert = false;
} else if ($paramArr['formatType'] == '2') {
    $titleArr = $data[1];
    $beginCol = 2;
} else if ($paramArr['formatType'] == '3') {
    $titleArr = $data[2];
    $beginCol = 3;
} else if ($paramArr['formatType'] == '4') {
    // 4：表头存在单元格合并情况，特殊处理（暂无此类情况，后续按实际情况补充完善）
} else {
    // 默认按情况2作处理（最常见情况）
    $titleArr = $data[1];
    $beginCol = 2;
}

$paramArr['tableName'] = $paramArr['tableName'] . "_temp";  //临时数据表

if (!$needConvert) {
    $insertCol .= "insert into " . $paramArr['tableName'] . " (";
    foreach ($titleArr as $cellName => $cellValue) {
        $columnArr[$cellName] = $cellValue;
        $insertCol .= $cellValue . ",";
        $colCount += 1;
    }
    foreach ($extraColArr as $eck => $ecv) {
        $insertCol .=  $eck . ",";
    }
    if ($colCount > 0) {
        $insertCol = rtrim($insertCol, ',');
        $insertCol .= ") values";
    } else {
        $insertCol = "";
    }
} else {
    $insertCol .= "insert into " . $paramArr['tableName'] . " (";
    foreach ($titleArr as $cellName => $cellValue) {
        if (array_key_exists($cellValue, $colDescArr)) {
            $columnArr[$cellName] = $colDescArr[$cellValue];
            $insertCol .= $colDescArr[$cellValue] . ",";
            $colCount += 1;
        }
    }
    foreach ($extraColArr as $eck => $ecv) {
        $insertCol .=  $eck . ",";
    }
    if ($colCount > 0) {
        $insertCol = rtrim($insertCol, ',');
        $insertCol .= ") values";
    } else {
        $insertCol = "";
    }
}

$ROW_COUNT = 0;
$MSG_ERROR = "";
$insertVal = "";

// 导入数据前删除临时表数据
$tempSql = " truncate table " . $paramArr['tableName'];
exequery(TD::conn(), $tempSql);

for ($i = $beginCol; $i <= count($data); $i++) {
    $cellArr = $data[$i];
    $insertVal = "(";
    foreach ($columnArr as $ck => $cv) {
        $insertVal .= "'" . trim($cellArr[$ck]) . "',";
    }
    foreach ($extraColArr as $ecv) {
        $insertVal .=  "'" . $ecv . "',";
    }
    $insertVal = rtrim($insertVal, ',');
    $insertVal .= ")";

    $sql = $insertCol . $insertVal;
    // var_export($sql);exit;
    exequery(TD::conn(), $sql);
    ++$ROW_COUNT;
}

// exit;

if (file_exists($EXCEL_FILE)) {
    @unlink($EXCEL_FILE);
}

// 从缓存表新增数据到存量表，排除已存在数据无需导入
$getSql = " select t.* from inhe_control_library_temp t " .
    " where not exists (select * from inhe_control_library k where k.chinese = t.chinese and k.english = t.english) ";
$res1 = exequery(TD::conn(), $getSql);
$data1 = array();
while ($item1 = mysql_fetch_assoc($res1)) {
    $data1[] = $item1;
}
mysql_free_result($res1);

$userId = $_SESSION['LOGIN_USER_ID'];
$time = date('Y-m-d H:i:s');
$status = '1';

foreach ($data1 as $k => $val) {
    $addSql = "";

    $contactsSql = "insert into inhe_control_library_history (term_id, type, chinese, english, create_user, create_time) values" .
        "('" . $id . "', '" . $val['type'] . "', '" . $val['chinese'] . "', '" . $val['english'] . "', '" . $userId . "', '" . $time . "')";
    exequery(TD::conn(), $contactsSql);
}

// 存在歧义数据
$querySql = " select distinct t.*, k.english nowWord from inhe_control_library_temp t " .
    "left join inhe_control_library k on k.chinese = t.chinese " .
    " where t.status = 2 ";
$res = exequery(TD::conn(), $querySql);
$result = array();
while ($item = mysql_fetch_assoc($res)) {
    $result[] = $item;
}
mysql_free_result($res);

?>

<body class="bodycolor">
    <br />
    <?php
    message("", "导入成功！");
    ?>
</body>

</html>