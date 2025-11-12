<?php
include_once("/MYOA/webroot/general/customer_relationship/common/CommonControl.php");  // 公共类

$model = new CommonControlModel();

$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'opening_time,bid_type,bid_status,manage_dept_grid,currency',
);
$selectArr = $model->getCommonParam($selectParam);
$opening_time = array();
foreach ($selectArr['opening_time'] as $co) {
    $opening_time[$co['paras_value']] = $co['paras_desc'];
}
$manage_dept = array();
foreach ($selectArr['manage_dept_grid'] as $md) {
    $manage_dept[$md['paras_value']] = $md['paras_desc'];
}
$currency = array();
foreach ($selectArr['currency'] as $md) {
    $currency[$md['paras_value']] = $md['paras_desc'];
}
$bid_type = array();
foreach ($selectArr['bid_type'] as $md) {
    $bid_type[$md['paras_value']] = $md['paras_desc'];
}
$bid_status = array();
foreach ($selectArr['bid_status'] as $md) {
    $bid_status[$md['paras_value']] = $md['paras_desc'];
}

// $limit
// $page
$param = $model->getParamToArray();

$where = " where 1=1 ";
$pageLimit = "";

// 数据权限
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = 'authorization_letter_grid';
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
if (array_key_exists("project_no", $param) && $param['project_no'] != "") {
    $where .= " and m.project_no like '%" . trim($param['project_no']) . "%' ";
}
if (array_key_exists("contract_no", $param) && $param['contract_no'] != "") {
    $where .= " and m.contract_no like '%" . trim($param['contract_no']) . "%' ";
}
if (array_key_exists("project_country", $param) && $param['project_country'] != "") {
    $where .= " and m.project_country like '%" . trim($param['project_country']) . "%' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1)*$param['limit'] . "," . $param['limit'];
}
$orderBy = " order by id desc ";

$userId = $_SESSION['LOGIN_USER_ID'];

$sql = " select m.*, t.filename applyAttachName, tt.filename bankAttachName, ttt.filename transferAttachName from inhe_authorization_letter_grid m "
    // . "left join inhe_oa_paras pp on pp.type = 'bid_type' and pp.paras_value = m.contract_category "
    . "left join inhe_attachment t on t.type = 'apply_attach_grid' and t.relatedId = m.id "
    . "left join inhe_attachment tt on tt.type = 'bank_attach_grid' and tt.relatedId = m.id "
    . "left join inhe_attachment ttt on ttt.type = 'transfer_attach_grid' and ttt.relatedId = m.id "
    . $where . $orderBy . $pageLimit;
$countSql = " select count(*) total_count from inhe_authorization_letter_grid m ". $where . $orderBy;
// var_export($sql);exit;
$countRes = exequery(TD::conn(), $countSql);
$countArr = mysql_fetch_assoc($countRes);
$totalCount = $countArr['total_count'];

$res = exequery(TD::conn(), $sql);
$data = array();
// var_export($yes_or_no);exit;
while ($item = mysql_fetch_assoc($res)) {
    $item['apply_attach_grid'] = $item['apply_attach_grid'] == '' ? $item['applyAttachName'] : $item['apply_attach_grid'];
    $item['bank_attach_grid'] = $item['bank_attach_grid'] == '' ? $item['bankAttachName'] : $item['bank_attach_grid'];
    $item['transfer_attach_grid'] = $item['transfer_attach_grid'] == '' ? $item['transferAttachName'] : $item['transfer_attach_grid'];
    $item['opening_time'] = $opening_time[$item['opening_time']];
    $item['bid_type'] = $bid_type[$item['bid_type']];
    $item['status'] = $bid_status[$item['status']];
    $item['currency'] = $currency[$item['currency']]==''? $item['currency']: $currency[$item['currency']];
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
