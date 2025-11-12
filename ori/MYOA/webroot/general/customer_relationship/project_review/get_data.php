<?php
include_once("/MYOA/webroot/general/customer_relationship/common/DataModel.php");  // 公共类

$model = new DataModel();

$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,company',
);
$selectArr = $model->getCommonParam($selectParam);
$project_star = $star_icon = $company = array();
foreach ($selectArr['project_star'] as $co) {
    $project_star[$co['paras_value']] = $co['paras_desc'];
    $star_icon[$co['paras_value']] = $co['extra'];
}
foreach ($selectArr['company'] as $cc) {
    $company[$cc['paras_value']] = $cc['paras_desc'];
}

$param = $model->getParamToArray();

$where = " where 1=1 ";
$pageLimit = "";

if (array_key_exists("project_code", $param) && $param['project_code'] != "") {
    $where .= " and m.project_code like '%" . $param['project_code'] . "%' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
$orderBy = " order by id desc ";

$sql = " select m.* from inhe_project_main m "
    . $where . $orderBy . $pageLimit;
$countSql = " select count(*) total_count from inhe_project_main m " . $where . $orderBy;
$countRes = exequery(TD::conn(), $countSql);
$countArr = mysql_fetch_assoc($countRes);
$totalCount = $countArr['total_count'];

$res = exequery(TD::conn(), $sql);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    $item['project_star_desc'] = $project_star[$item['project_star']];
    $item['star_icon'] = $star_icon[$item['project_star']];
    $item['company'] = $company[$item['user_bu']];
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
