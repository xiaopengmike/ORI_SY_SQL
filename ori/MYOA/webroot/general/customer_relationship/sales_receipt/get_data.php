<?php
include_once("../common/DataModel.php");  // 公共类
include_once("/MYOA/webroot/general/customer_relationship/common/SystemEnum.php");  // 公共类
require_once("../common/UserModel.php");
$model = new DataModel();
$userModel = new UserModel();
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'currency,company,yes_or_no',
);
$selectArr = $model->getCommonParam($selectParam);
foreach ($selectArr['company'] as $v) {
    $company[$v['paras_value']] = $v['paras_desc'];
}
foreach ($selectArr['yes_or_no'] as $v) {
    $yes_or_no[$v['paras_value']] = $v['paras_desc'];
}
foreach ($selectArr['currency'] as $v) {
    $currency[$v['paras_value']] = $v['paras_desc'];
}

$param = $model->getParamToArray();
$where = " where 1=1 ";
$pageLimit = "";
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = "sales_receipt";
$data = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
// 数据权限范围
if ($isAdmin) {
    if (!empty($data['userBu'])) {
        $where .= " and m.user_bu in (" . $data['userBu'] . ") ";
    }
    if (!empty($data['deptIds'])) {
        $where .= " and m.organ_id in(" . $data['deptIds'] . ") ";
    }
    if (!empty($data['userIds'])) {
        $where .= " and m.user_id in(" . $data['userIds'] . ") ";
    }
}

if (array_key_exists("form_id", $param) && $param['form_id'] != "") {
    $where .= " and m.form_id like '%" . $param['form_id'] . "%' ";
}
if (array_key_exists("project_number", $param) && $param['project_number'] != "") {
    $where .= " and m.project_number like '%" . $param['project_number'] . "%' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
$orderBy = " order by id desc ";

$sql = " select m.*, d.dept_name, u.user_name from inhe_sales_receipt m "
    . " left join user u on u.user_id = m.user_id "
    . " left join department d on d.dept_id = m.organ_id "
    . $where . $orderBy . $pageLimit;
$countSql = " select count(*) total_count from inhe_sales_receipt m " . $where . $orderBy;
$countRes = exequery(TD::conn(), $countSql);
$countArr = mysql_fetch_assoc($countRes);
$totalCount = $countArr['total_count'];

$res = exequery(TD::conn(), $sql);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    $item['currency_desc'] = $currency[$item['currency']];
    $item['company_desc'] = $company[$item['user_bu']];
    $item['if_third_pay_desc'] = $yes_or_no[$item['if_third_pay']];
    $item['status_desc'] = '<font class="'.SystemEnum::$STATUS_CSS[$item["status"]]. '">'. SystemEnum::$STATUS[$item["status"]] .'</font>';
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
