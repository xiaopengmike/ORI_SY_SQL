<?php
include_once("../DataModel.php");  // 公共类
include_once("../UserModel.php");
$model = new DataModel();
$userModel = new UserModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star',
);
$selectArr = $model->getCommonParam($selectParam);
$project_star = array();
foreach ($selectArr['project_star'] as $v) {
    $project_star[$v['paras_value']] = $v['paras_desc'];
}

// $limit
// $page
$param = $model->getParamToArray();
// and m.create_user = '$userId'
$where = " where 1=1 and m.type='sales_contract_review'";
$pageLimit = "";

$where .= " and ((m.user_id ='$userId') ";
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = "sales_contract_review_scope";
$adminData = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $adminData['isAdmin'];
if (!empty($adminData['userBu'])) {
    $where .= " or m.user_bu in (" . $adminData['userBu'] . ") ";
}
$where .= ") ";

if (array_key_exists("project_number", $param) && $param['project_number'] != "") {
    $where .= " and m.project_number like '%" . trim($param['project_number']) . "%' ";
}
if (array_key_exists("project_code", $param) && $param['project_code'] != "") {
    $where .= " and m.project_name like '%" . trim($param['project_code']) . "%' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
$orderBy = " order by m.id desc ";

$sql = " select distinct m.*, c.dept_id oldDeptName, d.dept_name realDeptName from inhe_contract_review m " .
    "  left join inhe_contract_detail c on c.form_id = m.form_id " .
    "  left join department d on d.dept_id = c.dept_id "
    . $where . $orderBy . $pageLimit;
$countSql = " select count(*) total_count from inhe_contract_review m " . $where . $orderBy;
$countRes = exequery(TD::conn(), $countSql);
$countArr = mysql_fetch_assoc($countRes);
$totalCount = $countArr['total_count'];

$res = exequery(TD::conn(), $sql);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    $item['project_dept_name'] = $item['realDeptName'] == '' ? $item['oldDeptName'] : $item['realDeptName'];
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
