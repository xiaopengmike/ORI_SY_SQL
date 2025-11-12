<?php
require_once 'ActionModel.php';

$model = new ActionModel();
$param = $model->getParamToArray();
$detailItem = $model->getOrderDetailItem();
$number = $param['number'];
$type = $param['type'];
$other_company = $param['other_company'];
$htmlArr = $detailArr = array();
if ($type == 'addMeter') {
    $detailArr['meter_one'] = $detailItem['meter_one'];
    $detailArr['meter_two'] = $detailItem['meter_two'];
    $detailArr['meter_three'] = $detailItem['meter_three'];
} else if ($type != 'addMeter'&&$type != 'addSoft') {
    $detailArr['non_meter'] = $detailItem['non_meter'];
    $add_type='_'.substr($type,3);
}  else if ($type = 'addSoft') {
    $detailArr['non_meter'] = $detailItem['non_Soft'];
    $add_type='_'.substr($type,3);
} else {
    echo json_encode($model->array_iconv($htmlArr));
    exit;
}

$htmlArr = $model->makeLineHtml($detailArr, $number,$add_type,$other_company);

echo json_encode($model->array_iconv($htmlArr));
exit;