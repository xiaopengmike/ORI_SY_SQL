<?php
include_once("/MYOA/webroot/general/hr/ApproveCommonModel.php");
$model = new ApproveCommonModel();

$param = $model->getParamToArray();
// var_export($param);exit;

$result = array();
// $result = $model->getApproverPerson($param['type'],$param['companyId'],$param['bz'],$param['deptId'],$param['sysUserId']);
$result = $model->getApproverPersonNew($param);

$result = $model->array_iconv($result);

echo json_encode($result);
exit;