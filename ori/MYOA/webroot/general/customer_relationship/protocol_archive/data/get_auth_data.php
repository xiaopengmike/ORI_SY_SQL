<?php
include_once("/MYOA/webroot/general/customer_relationship/common/CommonControl.php");  // 公共类

$model = new CommonControlModel();

$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'manage_dept',
);
$selectArr = $model->getCommonParam($selectParam);
$manage_dept = array();
foreach ($selectArr['manage_dept'] as $md) {
    $manage_dept[$md['paras_value']] = $md['paras_desc'];
}

function getDeptName($deptStr, $deptArr){
    $deptNameStr = '';
    foreach(explode(',',$deptStr) as $dv){
        $deptNameStr .= $deptArr[$dv] . ', ';
    }
    $deptNameStr = rtrim($deptNameStr,', ');

    return $deptNameStr;
}

// $limit
// $page
$param = $model->getParamToArray();

$where = " where 1=1 and m.system_admin != 1 ";
$pageLimit = "";

if (array_key_exists("user_name", $param) && $param['user_name'] != "") {
    $where .= " and u.user_name like '%" . $param['user_name'] . "%' ";
}
if (array_key_exists("type", $param) && $param['type'] != "") {
    $where .= " and m.type = '" . $param['type'] . "' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1)*$param['limit'] . "," . $param['limit'];
}
$orderBy = " order by id desc ";

$userId = $_SESSION['LOGIN_USER_ID'];

$sql = " select m.*, u.user_name useName, d.dept_name deptName from inhe_system_admin m "
    . "left join user u on u.user_id = m.user_id "
    . "left join department d on d.dept_id = u.dept_id "
    . $where . $orderBy . $pageLimit;
$countSql = " select count(*) total_count from inhe_system_admin m left join user u on u.user_id = m.user_id ". $where . $orderBy;
// var_export($sql);exit;
$countRes = exequery(TD::conn(), $countSql);
$countArr = mysql_fetch_assoc($countRes);
$totalCount = $countArr['total_count'];

$res = exequery(TD::conn(), $sql);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    $item['manage_dept_desc'] = getDeptName($item['dept_ids'],$manage_dept);
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
