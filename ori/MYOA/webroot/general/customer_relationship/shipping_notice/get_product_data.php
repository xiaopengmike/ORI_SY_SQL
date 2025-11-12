<?php
include_once("../common/DataModel.php");  // ¹«¹²Àà
$model = new DataModel();
$userId = $_SESSION['LOGIN_USER_ID'];

// $limit
// $page
$param = $model->getParamToArray();
$where = $where2 = " where 1=1 ";
$pageLimit = "";
$data = array();
if (!array_key_exists("form_id", $param) || $param['form_id'] == "") {
    $result = array(
        "code" => 0,
        "msg" => "",
        "count" => '',
        "data" => $data
    );
    echo json_encode($model->array_iconv($result));
    exit;
}
$where .= " and m.form_id = '" . trim($param['form_id']) . "' ";
if (array_key_exists("type", $param) && $param['type'] != "") {
    $where .= " and m.type = '" . trim($param['type']) . "' ";
    $where2 .= " and m.type = '" . trim($param['type']) . "' ";
}
if (array_key_exists("project_number", $param) && $param['project_number'] != "") {
    $where .= " and k.project_number = '" . trim($param['project_number']) . "' ";
    $where2 .= " and k.project_number = '" . trim($param['project_number']) . "' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
$orderBy = " order by m.id desc ";
$version='';

$sql = " select * from inhe_contract_review k where k.form_id = '" . trim($param['form_id']) . "' ";
$res = exequery(TD::conn(), $sql);
if ($item = mysql_fetch_assoc($res)) {
    $version=$item['version'];
}

if($version>0){
    $data=array();

    $countSql = "  select count(*) total_count  from inhe_product_detail m left join inhe_contract_review k on k.form_id = m.form_id "
    .$where2." and k.type ='sales_contract_review_change' and k.status in('R','Y') and k.version=".$version." and k.related_id='".$param['form_id']."'". $orderBy ;
    $countRes = exequery(TD::conn(), $countSql);
    $countArr = mysql_fetch_assoc($countRes);
    $totalCount = $countArr['total_count'];
    $countSql = "  select m.*,k.related_id form_id,k.project_name,k.project_number,k.version from inhe_product_detail m left join inhe_contract_review k on k.form_id = m.form_id "
    .$where2." and k.type ='sales_contract_review_change' and k.status in('R','Y') and k.version=".$version." and k.related_id='".$param['form_id']."'". $orderBy ;
    $changeSql =  $countSql. $pageLimit;
    $changeData = exequery(TD::conn(), $changeSql);
    while ($item = mysql_fetch_assoc($changeData)) {
        $data[] = $item;
    }
    mysql_free_result($changeData);
}else{
    $sql = " select m.*,k.form_id,k.project_name,k.project_number,k.version from inhe_product_detail m left join inhe_contract_review k on k.form_id = m.form_id "
    . $where . $orderBy . $pageLimit;

    $countSql = " select count(*) total_count from inhe_product_detail m left join inhe_contract_review k on k.form_id = m.form_id " . $where ;
    $countRes = exequery(TD::conn(), $countSql);
    $countArr = mysql_fetch_assoc($countRes);
    $totalCount = $countArr['total_count'];

    $res = exequery(TD::conn(), $sql);

    while ($item = mysql_fetch_assoc($res)) {
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
