<?php
include_once("/MYOA/webroot/general/hr/UserCommonModel.php");
$model = new UserCommonModel();

$result = array();
$result = $model->getInterviewInfo($userId);

echo json_encode($model->array_iconv($result));
exit;