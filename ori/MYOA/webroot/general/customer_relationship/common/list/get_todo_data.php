<?php
include_once("../DataModel.php");  // 公共类
$model = new DataModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'crm_process_type',
);
$selectArr = $model->getCommonParam($selectParam);
$crm_process_type = array();
$process_type='';
foreach ($selectArr['crm_process_type'] as $v) {
    $crm_process_type[$v['paras_value']] = $v['paras_desc'];
    $process_type.='"'.$v['paras_value'].'",';
}
$process_type=trim($process_type,',');

// $limit
// $page
$param = $model->getParamToArray();
$where = " where 1=1 and m.status = 'U' and type  in($process_type) and m.approve_user = '$userId' and form_id != '' ";
$pageLimit = "";

if (array_key_exists("type", $param) && $param['type'] != "") {
    $where .= " and m.type = '" . trim($param['type']) . "' ";
}
if (array_key_exists("form_id", $param) && $param['form_id'] != "") {
    $where .= " and m.form_id = '" . trim($param['form_id']) . "' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
$orderBy = " order by m.urgent desc,m.id desc ";

$sql = " select m.*, u.user_name prev_writer_name, uu.user_name create_user_name from inhe_flow_opinion m " .
    " left join user u on u.user_id = m.prev_writer " . 
    " left join user uu on uu.user_id = m.user_id " . 
    $where . $orderBy . $pageLimit;
    //echo $sql;
$countSql = " select count(*) total_count from inhe_flow_opinion m " . $where ;
$countRes = exequery(TD::conn(), $countSql);
$countArr = mysql_fetch_assoc($countRes);
$totalCount = $countArr['total_count'];

$res = exequery(TD::conn(), $sql);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    $item['crm_process_type'] = $crm_process_type[$item['type']];
    $item['title'] =$item['urgent']=="Y"?'<span style="color:red"><b>【紧急】</b></span>'.$item['title']:$item['title'];
    $data[] = $item;
}
mysql_free_result($res);

$result = array(
    "code" => 0,
    "msg" => "",
    "count" => $totalCount,
    "data" => $data
);

echo json_encode($model->array_iconv($result));
exit;
