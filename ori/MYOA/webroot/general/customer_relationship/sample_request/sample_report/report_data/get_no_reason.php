<?php
include_once "../../../common/CommonMethodModel.php";
include_once "../../../common/SystemEnum.php";
include_once "../../../common/DataModel.php";
include_once "../../../common/UserModel.php";
$model = new CommonMethodModel();
$dataModel = new DataModel();
$userModel = new UserModel();
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'talent_school_type,talent_flow_status,company,sample_no_reason',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$sample_no_reason = array();
foreach ($selectArr['sample_no_reason'] as $v) {
    $sample_no_reason[$v['paras_value']] = $v['paras_desc'];
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
$whereStr = " where 1=1 ";
$queryStr = "";

if (array_key_exists('user_id', $param) && $param['user_id'] != "") {
    $whereStr .= " and k.user_id like '%" . trim($param['user_id']) . "%' ";
}

if (array_key_exists('user_name', $param) && $param['user_name'] != "") {
    $whereStr .= " and u.user_name like '%" . trim($param['user_name']) . "%' ";
}

if (array_key_exists('form_id', $param) && $param['form_id'] != "") {
    $whereStr .= " and a.form_id like '%" . trim($param['form_id']) . "%' ";
}

if (array_key_exists('project_code', $param) && $param['project_code'] != "") {
    $whereStr .= " and a.project_code like '%" . trim($param['project_code']) . "%' ";
}

if (array_key_exists('material_name', $param) && $param['material_name'] != "") {
    $whereStr .= " and a.material_name like '%" . trim($param['material_name']) . "%' ";
}

if (array_key_exists('u_date', $param) && $param['u_date'] != "") {
    $whereStr2 .= " and create_time >= '" . trim($param['u_date']) . "' ";
}

if (array_key_exists('end_u_date', $param) && $param['end_u_date'] != "") {
    $whereStr2 .= " and create_time <= '" . trim($param['end_u_date']) . "' ";
}

$groupBy = $pageLimit = "";
$orderBy = " order by  k.id desc ";
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

$totalQuery = " select k.*,a.project_code,a.material_name,a.form_id,u.user_name signature,o.work_flow from inhe_sample_return_report k 
join  inhe_sample_request a on a.id=k.request_id 
left join inhe_flow_opinion o on o.id=k.opinion_id
left join user u on u.user_id=o.approve_user
" .
    $whereStr . $orderBy;
 // echo $totalQuery;
$query = $totalQuery . $pageLimit;
$res = exequery(TD::conn(), $query);
$res1 = exequery(TD::conn(), $totalQuery);
$count = mysql_num_rows($res1);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    $item[reason]=$sample_no_reason[$item[reason]];
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
