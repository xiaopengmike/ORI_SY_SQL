<?php
require_once("../common/DataModel.php");
$model = new DataModel();
$userId = $_SESSION['LOGIN_USER_ID'];

// $limit
// $page
$param = $model->getParamToArray();
$where = " where 1=1 and m.type ='sales_contract_review' and m.status in('R','Y') ";
$pageLimit = "";

if (array_key_exists("form_id", $param) && $param['form_id'] != "") {
    $where .= " and m.form_id = '" . trim($param['form_id']) . "' ";
}
if (array_key_exists("project_number", $param) && $param['project_number'] != "") {
    $where .= " and m.project_number like '%" . trim($param['project_number']) . "%' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
$orderBy = " order by m.id desc ";
$sql = " select distinct m.form_id, m.customer_name, m.contract_party, m.project_name, m.project_number,m.version, "
    . " m.currency, c.total_amount from inhe_contract_review m left join inhe_contract_detail c on c.form_id = m.form_id "
    . $where  . $orderBy . $pageLimit;
$countSql = " select count(*) total_count from inhe_contract_review m " . $where . $orderBy;
$countRes = exequery(TD::conn(), $countSql);
$countArr = mysql_fetch_assoc($countRes);
$totalCount = $countArr['total_count'];

$res = exequery(TD::conn(), $sql);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    if($item['version']>0){
        $changeSql = " select distinct m.related_id form_id, m.customer_name, m.contract_party, m.project_name, m.project_number,m.version, "
        . " m.currency, c.total_amount from inhe_contract_review m left join inhe_contract_detail c on c.form_id = m.form_id "
        ."where 1=1 and m.type ='sales_contract_review_change' and m.status in('R','Y') and version=".$item['version']." and related_id='".$item['form_id']."'";
        $changeData = exequery(TD::conn(), $changeSql);
        $data[] = mysql_fetch_assoc($changeData);
    }else{
        $data[] = $item;
    }
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
