<?php
include_once("../common/DataModel.php");  // 公共类
require_once("../common/UserModel.php");
$model = new DataModel();
$userModel = new UserModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,company,crm_process_sort',
);
$selectArr = $model->getCommonParam($selectParam);

$param = $model->getParamToArray();
$where =" where 1=1 and k.status in('R','Y') and type='CUSTOMER_DATA' and TRIM(k.customer_name) = '" . trim($param['customer_name']) . "'";

if (array_key_exists('user_bu', $param) && $param['user_bu'] != "") {
    $where .= " and k.user_bu = '" . $param['user_bu'] . "' ";
}

if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
$orderBy = " order by k.id desc ";
$groupBy = " ";

// FIX: 由于部分公司名词带有,号，修改采用其他分隔符拼接
$sql = " select k.*,tc.zh_name continentName, yc.zh_name countryName, tc.en_name continentEnName,yc.country countryEnName from inhe_customer_data k ".
    " left join continent_code tc on tc.iso_two = k.continent " .
    " left join country_code yc on yc.iso_three = k.country and yc.continent=k.continent " .
    $where . $groupBy . $orderBy;
    //echo $sql;
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
