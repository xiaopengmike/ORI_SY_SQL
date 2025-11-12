<?php
include_once("../common/DataModel.php");  // 公共类
require_once("../common/UserModel.php");

$dataModel = new DataModel();
$userModel = new UserModel();

$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'company',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$company = array();
foreach ($selectArr['company'] as $cc) {
    $company[$cc['paras_value']] = $cc['paras_desc'];
}

$param = $dataModel->getParamToArray();

$where = " where 1=1 ";
$pageLimit = "";

$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = "temp_code";
$data = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
// 数据权限范围
if ($isAdmin) {
    if (!empty($data['userBu'])) {
        $where .= " and m.company in (" . $data['userBu'] . ") ";
    }
    if (!empty($data['deptIds'])) {
        $where .= " and m.dept_id in(" . $data['deptIds'] . ") ";
    }
    if (!empty($data['userIds'])) {
        $where .= " and m.user_id in(" . $data['userIds'] . ") ";
    }
}

if (array_key_exists("temp_code", $param) && $param['temp_code'] != "") {
    $where .= " and m.temp_code like '%" . trim($param['temp_code']) . "%' ";
}
if (array_key_exists("scope_code", $param) && $param['scope_code'] != "") {
    $where .= " and m.scope_code = '" . $param['scope_code'] . "' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
$orderBy = " order by id desc ";

$sql = " select m.*,d.dept_name realDeptName from inhe_temp_code m left join department d on d.dept_id = m.dept_id "
    . $where . $orderBy . $pageLimit;
$countSql = " select count(*) total_count from inhe_temp_code m " . $where . $orderBy;
$countRes = exequery(TD::conn(), $countSql);
$countArr = mysql_fetch_assoc($countRes);
$totalCount = $countArr['total_count'];

$res = exequery(TD::conn(), $sql);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    $item['company'] = $company[$item['company']];
    $item['dept_name'] = $item['realDeptName'] == '' ? $item['dept_name'] : $item['realDeptName'];
    $data[] = $item;
}
mysql_free_result($res);

$result = array(
    "code" => 0,
    "msg" => "",
    "count" => $totalCount,
    "data" => $data
);

echo json_encode($dataModel->array_iconv($result));
exit;
