<?php
require_once 'ActionModel.php';

$model = new ActionModel();
$param = $model->getParamToArray();
$detailItem = $model->getOrderDetailItem();
$number = $param['number'];
$type = $param['type'];
$htmlArr = $detailArr = array();
if ($type == 'addNonLieu') {
    $detailArr['non_lieu'] = $detailItem['non_lieu'];
    $add_type='_'.substr($type,3);
} else {
    echo json_encode($model->array_iconv($htmlArr));
    exit;
}

$htmlArr = $model->makeLineHtml($detailArr, $number,$add_type);

echo json_encode($model->array_iconv($htmlArr));
exit;