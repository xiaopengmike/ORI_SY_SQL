<?php
include_once("/MYOA/webroot/general/customer_relationship/common/CommonControl.php");  // 公共类

$model = new CommonControlModel();

$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'yes_or_no,contract_protocol_type,continent,manage_dept,currency',
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
$currency = array();
foreach ($selectArr['currency'] as $md) {
    $currency[$md['paras_value']] = $md['paras_desc'];
}

// $limit
// $page
$param = $model->getParamToArray();

$where = " where 1=1 ";
$pageLimit = "";

// 数据权限
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = 'sales_contract';
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

if (array_key_exists("project_name", $param) && $param['project_name'] != "") {
    $where .= " and m.project_name like '%" . trim($param['project_name']) . "%' ";
}
if (array_key_exists("project_no", $param) && $param['project_no'] != "") {
    $where .= " and m.project_no like '%" . trim($param['project_no']) . "%' ";
}
if (array_key_exists("contract_no", $param) && $param['contract_no'] != "") {
    $where .= " and m.contract_no like '%" . trim($param['contract_no']) . "%' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1)*$param['limit'] . "," . $param['limit'];
}
$orderBy = " order by m.id desc ";

$userId = $_SESSION['LOGIN_USER_ID'];

$sql = " select m.*, pp.paras_desc contract_category_desc, t.filename foreignAttachName, tt.filename chineseAttachName  from inhe_sales_contract m "
    . "left join inhe_oa_paras pp on pp.type = 'contract_category' and pp.paras_value = m.contract_category "
    . "left join inhe_attachment t on t.type = 'foreign_attach' and t.relatedId = m.id "
    . "left join inhe_attachment tt on tt.type = 'chinese_attach' and tt.relatedId = m.id "
    . $where . $orderBy . $pageLimit;
$countSql = " select count(*) total_count from inhe_sales_contract m "
. "left join inhe_oa_paras pp on pp.type = 'contract_category' and pp.paras_value = m.contract_category "
    . "left join inhe_attachment t on t.type = 'foreign_attach' and t.relatedId = m.id "
    . "left join inhe_attachment tt on tt.type = 'chinese_attach' and tt.relatedId = m.id "
. $where . $orderBy;
$countRes = exequery(TD::conn(), $countSql);
$countArr = mysql_fetch_assoc($countRes);
$totalCount = $countArr['total_count'];

$res = exequery(TD::conn(), $sql);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    $item['foreign_attach'] = $item['foreign_attach'] == '' ? $item['foreignAttachName'] : $item['foreign_attach'];
    $item['chinese_attach'] = $item['chinese_attach'] == '' ? $item['chineseAttachName'] : $item['chinese_attach'];
    $item['if_exclusive_desc'] = $yes_or_no[$item['if_exclusive']];
    $item['if_archive_desc'] = $yes_or_no[$item['if_archive']];
    $item['currency'] = $currency[$item['currency']];
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
