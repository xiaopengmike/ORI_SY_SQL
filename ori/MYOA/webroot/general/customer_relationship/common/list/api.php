<?php
require_once 'inc/auth.inc.php';
require_once("../FormModel.php");
require_once("../CommonMethodModel.php");
$form = new FormModel();
$common = new CommonMethodModel();
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$time = date('Y-m-d H:i:s', time());
$attach = array();
$relatedId = "";
$remark = "textarea";
$formData = array(
    'form_id' => '1',
    'dept_id' => $deptId . "_update",
    'user_id' => $userId . "_update",
    'user_name' => $userName . "_update",
    'remark' => $remark . "_update",
    'dept_id1' => $deptId . '_update',
    'user_id1' => $userId . '_update',
    'user_name1' => $userName . '_update',
    'remark1' => 'remark1' . '_update'
);
$paramInsert = array(
    'form_id' => '1',
    'dept_id' => $deptId . "_change",
    'user_id' => $userId . "_change",
    'user_name' => $userName . "_change",
    'remark' => $remark . "_change",
);
$condition = array(
    'form_id' => '1'
);
$normalField = array(
    'form_id' => 'form_id'
);
$batchField = array(
    'dept_id' => 'dept_id',
    'user_id' => 'user_id',
    'user_name' => 'user_name',
    'remark' => 'remark'
);
$tableName = "api_test_detail";
$count = 2;
// $result = $form->updateDetailData($normalField, $batchField, $formData, $condition, $tableName, $count);
// $result = $form->changeMainData($paramInsert, $condition, $tableName, $attach, $relatedId);
$result = $form->changeDetailData($normalField, $batchField, $formData, $condition, $tableName, $count);

// echo $result;
echo json_encode($common->array_iconv($result));

exit;
