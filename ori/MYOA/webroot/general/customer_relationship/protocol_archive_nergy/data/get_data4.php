<?php
include_once("/MYOA/webroot/general/customer_relationship/common/CommonControl.php");  // 公共类

$model = new CommonControlModel();

$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'yes_or_no,manage_dept_nergy',
);
$selectArr = $model->getCommonParam($selectParam);
$yes_or_no = array();
foreach ($selectArr['yes_or_no'] as $co) {
    $yes_or_no[$co['paras_value']] = $co['paras_desc'];
}
$manage_dept = array();
foreach ($selectArr['manage_dept_nergy'] as $md) {
    $manage_dept[$md['paras_value']] = $md['paras_desc'];
}

// $limit
// $page
$param = $model->getParamToArray();

$where = " where 1=1 ";
$pageLimit = "";

// 数据权限
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = 'guarantee_letter_nergy';
$data = $model->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
if(!$data['systemAdmin']){
    if($isAdmin){
        $where .= " and (m.user_id = '" . $userId . "' ";
        if (!empty($data['deptIds'])) {
            $where .= " or m.manage_dept in(" . $data['deptIds'] . ") ";
        }
        if (!empty($data['userIds'])) {
            $where .= " or m.user_id in(" . $data['userIds'] . ") ";
        }
        $where .= ") ";
            
    } else {
        $where .= " and m.user_id = '" . $userId . "' ";
    }
}

if (array_key_exists("project_code", $param) && $param['project_code'] != "") {
    $where .= " and m.project_code like '%" . trim($param['project_code']) . "%' ";
}
if (array_key_exists("contract_party", $param) && $param['contract_party'] != "") {
    $where .= " and m.contract_party like '%" . trim($param['contract_party']) . "%' ";
}
if (array_key_exists("authorized_person", $param) && $param['authorized_person'] != "") {
    $where .= " and m.authorized_person like '%" . trim($param['authorized_person']) . "%' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1)*$param['limit'] . "," . $param['limit'];
}
$orderBy = " order by serial_number desc ";

$userId = $_SESSION['LOGIN_USER_ID'];

$sql = " select m.*, t.filename attachName from inhe_guarantee_letter m "
    . "left join inhe_attachment t on t.type = 'guarantee_letter_nergy' and t.relatedId = m.id "
    . $where . $orderBy . $pageLimit;
$countSql = " select count(*) total_count from inhe_guarantee_letter m ". $where . $orderBy;
// var_export($sql);exit;
$countRes = exequery(TD::conn(), $countSql);
$countArr = mysql_fetch_assoc($countRes);
$totalCount = $countArr['total_count'];

$res = exequery(TD::conn(), $sql);
$data = array();
// var_export($yes_or_no);exit;
while ($item = mysql_fetch_assoc($res)) {
    $item['if_archive_desc'] = $yes_or_no[$item['if_archive']];
    $item['manage_dept_desc'] = $manage_dept[$item['manage_dept']] == '' ? $item['manage_dept'] : $manage_dept[$item['manage_dept']];
    $data[] = $item;
}
mysql_free_result($res);

$result = array(
    "code" => 0,
    "msg" => "",
    "count" => $totalCount,
    "data" => $data
);
// var_export($result);exit;

echo json_encode($model->array_iconv($result));
exit;
