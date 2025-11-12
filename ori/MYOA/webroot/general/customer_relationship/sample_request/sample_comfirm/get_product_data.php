<?php
require_once("../common/DataModel.php");
$model = new DataModel();
$userId = $_SESSION['LOGIN_USER_ID'];

// $limit
// $page
$param = $model->getParamToArray();
// and m.create_user = '$userId'
$where = " where 1=1 ";
$pageLimit = "";

// 取项目编号参与人数据控制权限 TODO
// 筛选条件:CRM03.申请人.制单人=填报人 或 CRM23.主表.项目负责人=填报人 或 CRM23.参与人.项目参与人=填报人 或  当前用户="鲁慧珍"

if (array_key_exists("form_id", $param) && $param['form_id'] != "") {
    $where .= " and k.form_id = '" . trim($param['form_id']) . "' ";
}

if (array_key_exists("non_excipients_name", $param) && $param['non_excipients_name'] != "") {
    $where .= " and k.non_excipients_name = '" . trim($param['non_excipients_name']) . "' ";
}

if (array_key_exists("non_excipients_specification", $param) && $param['non_excipients_specification'] != "") {
    $where .= " and k.non_excipients_specification = '" . trim($param['non_excipients_specification']) . "' ";
}

if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}

$orderBy = " order by k.id desc ";

$commonSql = "select k.* from inhe_outexcipients_detail k ".$where." and not exists(
    select b.status from inhe_lieureceive_detail a,inhe_lieu_receive b where a.form_id=b.form_id and a.non_lieu_excipients_id=k.id and b.status in('P','R','Y'))"
    . $orderBy;

    //echo $commonSql;exit;
$sql=$commonSql.$pageLimit;
$countSql = $commonSql;
$countRes = exequery(TD::conn(), $countSql);
$totalCount = mysql_num_rows($countRes);

$res = exequery(TD::conn(), $sql);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
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
