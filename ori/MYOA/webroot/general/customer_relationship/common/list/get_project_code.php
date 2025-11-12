<?php
include_once("../DataModel.php");  // 公共类
$model = new DataModel();
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
$where = " where 1=1 and m.if_project='01' and project_star!='' ";
$pageLimit = "";

if (array_key_exists("project_code", $param) && $param['project_code'] != "") {
    $where .= " and m.project_code = '" . trim($param['project_code']) . "' ";
}
if (array_key_exists("project_star", $param) && $param['project_star'] != "") {
    $where .= " and m.project_star = '" . trim($param['project_star']) . "' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
$orderBy = " order by m.id desc ";

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
