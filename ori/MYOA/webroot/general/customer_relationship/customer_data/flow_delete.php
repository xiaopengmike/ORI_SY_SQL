<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("../common/CommonMethodModel.php");  // 公共类
require_once("../common/ProcessModel.php");
require_once("../common/FormModel.php");
require_once("../common/ResponseCode.php");
$response = new ResponseCode();
if (empty($id)) {
    $result = $response->failed("对应数据不存在！");
    exit;
}
$param['id'] = $id;
$sql = " delete from inhe_customer_data_flow  where id = '" . $param['id'] . "' ";
exequery(TD::conn(), $sql);
$result = $response->success(true);

?>