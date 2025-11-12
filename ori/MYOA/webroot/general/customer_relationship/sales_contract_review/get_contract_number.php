<?php
include_once("../common/DataModel.php");  // 公共类
$model = new DataModel();
$userId = $_SESSION['LOGIN_USER_ID'];

// $limit
// $page
$param = $model->getParamToArray();
$where = " where a.form_id is null and m.user_id = '$userId' ";
if (array_key_exists("project_code", $param) && $param['project_code'] != "") {
    $where .= " and m.pm_id like '%" . trim($param['project_code']) . "%' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
$orderBy = " order by m.sc_no desc ";
$tableName='inhe_crm_sales_confirmation';
switch($company){
    case 'INHE':
        $tableName='inhe_crm_sales_confirmation';
        break;
    case 'INHEGRID':
        $tableName='inhe_crm_sales_confirmation_grid';
        break;
    case 'INHENERGY':
        $tableName='inhe_crm_sales_confirmation_nergy';
        break;
    case 'HKNERGY':
        $tableName='inhe_crm_sales_confirmation_nergy';
        break;
    case 'WITLINK':
        $tableName='inhe_crm_sales_confirmation_witlink';
        break;
    case 'INHEJX':
        $tableName='inhe_crm_sales_confirmation_ihjx';
        break;
    case 'JXINHE':
        $tableName='inhe_crm_sales_confirmation_JXih';
        break;
    default :
        $tableName='inhe_crm_sales_confirmation';
        break;
}
// FIX: 由于部分公司名词带有,号，修改采用其他分隔符拼接
$sql = " select sc_no, pm_id, m.user_id, u.user_name createUserName from $tableName m "
    . " left join inhe_contract_review a on m.sc_no = a.form_id "
    . " left join user u on u.user_id = m.user_id "
    . $where . $groupBy . $orderBy;
$countSql = " select count(*) total_count from ($sql) t ";
$countRes = exequery(TD::conn(), $countSql);
$countArr = mysql_fetch_assoc($countRes);
$totalCount = $countArr['total_count'];

$res = exequery(TD::conn(), $sql . $pageLimit);
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
