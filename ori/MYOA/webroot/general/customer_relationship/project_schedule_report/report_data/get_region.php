<?php
include_once "../../common/CommonMethodModel.php";
include_once "../../common/SystemEnum.php";
include_once "../../common/DataModel.php";
include_once "../../common/UserModel.php";
$model = new CommonMethodModel();
$dataModel = new DataModel();
$userModel = new UserModel();
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'talent_school_type,talent_flow_status,company,project_schedule_nodes',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$project_schedule_nodes = array();
foreach ($selectArr['project_schedule_nodes'] as $v) {
    $project_schedule_nodes[$v['paras_value']] = $v['paras_desc'];
}

$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = "talent_record";
if (!empty($type)) {
    $systemId = $type;
}

$data = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
$userBu = $userModel->getUserBu($deptId);

$param = $dataModel->getParamToArray();
$whereStr = " where 1=1 and k.id in(select max(id) from inhe_project_schedule group by project_code) ";
$queryStr = "";

if (array_key_exists('user_id', $param) && $param['user_id'] != "") {
    $whereStr .= " and k.user_id like '%" . trim($param['user_id']) . "%' ";
}

if (array_key_exists('user_name', $param) && $param['user_name'] != "") {
    $whereStr .= " and a.user_name like '%" . trim($param['user_name']) . "%' ";
}

if (array_key_exists('u_date', $param) && $param['u_date'] != "") {
    $whereStr2 .= " and create_time >= '" . trim($param['u_date']) . "' ";
}

if (array_key_exists('end_u_date', $param) && $param['end_u_date'] != "") {
    $whereStr2 .= " and create_time <= '" . trim($param['end_u_date']) . "' ";
}

$groupBy = $pageLimit = "";
$groupBy = " ";
$orderBy = " order by k.region ";
//$limit = " limit " . ($param['page'] - 1) * $param['page_size'] . "," . $param['page_size'];
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
// 排序功能
$laySort = array();
if (array_key_exists('sort_field', $param) && $param['sort_field'] != "" && array_key_exists('sort_way', $param) && $param['sort_way'] != "") {
    $orderBy = " order by k." . $param['sort_field'] . " " . $param['sort_way'];
    $laySort[$param['sort_field']] = $param['sort_way'];
}

$totalQuery =" select k.*,u.user_name createUserName,pr.business_user_name, d.dept_name deptName " .
"  from inhe_project_schedule k " .
" left join inhe_project_region pr on pr.id = k.region_id " .
" left join user u on u.user_id = k.create_user_id " .
" left join department d on d.dept_id = k.dept_id " .
    $whereStr .$groupBy. $orderBy;
//  echo $totalQuery;
$query = $totalQuery . $pageLimit;
$res = exequery(TD::conn(), $query);
$res1 = exequery(TD::conn(), $totalQuery);
$count = mysql_num_rows($res1);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    $item['progress_nodes']=$project_schedule_nodes[$item['progress_nodes']];
    $data[] = $item;
}
// 释放结果集
mysql_free_result($res);
$result = array(
    "code" => 0,
    "msg" => "",
    "count" => $count,
    "data" => $data,
    "totalRow"=>array('leave_time'=>$sum_time['sum_time']),
);

echo json_encode($model->array_iconv($result));
exit;
