<?php
include_once("/MYOA/webroot/general/customer_relationship/common/CommonControl.php");  // 公共类

$model = new CommonControlModel();

$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'yes_or_no,contract_protocol_type,continent,manage_dept',
);
$selectArr = $model->getCommonParam($selectParam);
$yes_or_no = array();
foreach ($selectArr['yes_or_no'] as $co) {
    $yes_or_no[$co['paras_value']] = $co['paras_desc'];
}
$manage_dept = array();
foreach ($selectArr['manage_dept'] as $md) {
    $manage_dept[$md['paras_value']] = $md['paras_desc'];
}
$contract_protocol_type = array();
foreach ($selectArr['contract_protocol_type'] as $v) {
    $contract_protocol_type[$v['paras_value']] = $v['paras_desc'];
}
$continent = array();
foreach ($selectArr['continent'] as $v) {
    $continent[$v['paras_value']] = $v['paras_desc'];
}

// $limit
// $page
$param = $model->getParamToArray();

$where = " where 1=1 ";
$pageLimit = "";

// 数据权限
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = 'contract_protocol';
$data = $model->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
if (!$data['systemAdmin']) {
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

if (array_key_exists("if_exclusive", $param) && $param['if_exclusive'] != "") {
    $where .= " and m.if_exclusive = '" . $param['if_exclusive'] . "' ";
}
if (array_key_exists("country", $param) && $param['country'] != "") {
    $where .= " and m.country like '%" . trim($param['country']) . "%' ";
}
if (array_key_exists("project_code", $param) && $param['project_code'] != "") {
    $where .= " and m.project_code like '%" . trim($param['project_code']) . "%' ";
}
if (array_key_exists("contract_party", $param) && $param['contract_party'] != "") {
    $where .= " and m.contract_party like '%" . trim($param['contract_party']) . "%' ";
}
if (array_key_exists("user_name", $param) && $param['user_name'] != "") {
    $where .= " and m.user_name like '%" . trim($param['user_name']) . "%' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
$orderBy = " order by serial_number desc ";

$sql = " select m.*, t.id attachId, t.filename attachName, t.path attachUrl from inhe_contract_protocol m "
    . "left join inhe_attachment t on t.type = 'contract_protocol' and t.relatedId = m.id "
    . $where . $orderBy . $pageLimit;
$countSql = " select count(*) total_count from inhe_contract_protocol m " . $where . $orderBy;
$countRes = exequery(TD::conn(), $countSql);
$countArr = mysql_fetch_assoc($countRes);
$totalCount = $countArr['total_count'];

$res = exequery(TD::conn(), $sql);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    $item['if_exclusive_desc'] = $yes_or_no[$item['if_exclusive']];
    $item['if_archive_desc'] = $yes_or_no[$item['if_archive']];
    $item['continent_desc'] = $continent[$item['continent']];
    $item['type_desc'] = $contract_protocol_type[$item['type']];
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

echo json_encode($model->array_iconv($result));
exit;
