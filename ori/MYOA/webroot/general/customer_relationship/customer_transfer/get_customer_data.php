<?php
require_once("../common/DataModel.php");
include_once("../common/UserModel.php");
$model = new DataModel();
$userModel = new UserModel();

$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star',
);
$selectArr = $model->getCommonParam($selectParam);

$param = $model->getParamToArray();
$userId = $_SESSION['LOGIN_USER_ID'];
$form_userid = $param['form_userid'];


$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = "customer_data";
$data = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
// and m.create_user = '$userId'
$where = " where 1=1 and k.type ='customer_data' and k.status in('R','Y') ";
$pageLimit = "";

// 取项目编号参与人数据控制权限 TODO
// 筛选条件:CRM03.申请人.制单人=填报人 或 CRM23.主表.项目负责人=填报人 或 CRM23.参与人.项目参与人=填报人 或  当前用户="鲁慧珍"

if (array_key_exists("user_bu", $param) && $param['user_bu'] != "") {
    $where .= " and k.user_bu = '" . trim($param['user_bu']) . "' ";
}
if (array_key_exists("customer_name", $param) && $param['customer_name'] != "") {
    $where .= " and k.customer_name like '%" . trim($param['customer_name']) . "%' ";
}
if (array_key_exists("short_name", $param) && $param['short_name'] != "") {
    $where .= " and k.short_name like '%" . trim($param['short_name']) . "%' ";
}
if (array_key_exists("continent", $param) && $param['continent'] != "") {
    $where .= " and k.continent = '" . trim($param['continent']) . "' ";
}
if (array_key_exists("country", $param) && $param['country'] != "") {
    $where .= " and k.country = '" . trim($param['country']) . "' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
// // 查询本人填报数据
 $where .= " and (k.user_id = '" . $form_userid . "' ";
// //增加分享客户可见
// $childSql="select distinct b.customer_id from inhe_customer_share b where  b.user_ids='$form_userid'";

// $where .= " or k.id in (".$childSql.") ";

 $where .= ") ";

// 数据权限范围
if ($isAdmin) {
    $where .= " and (k.user_id = '" . $userId . "' ";
    // //增加分享客户可见
    $childSql="select distinct b.customer_id from inhe_customer_share b where  b.user_ids='$userId'";

    $where .= " or k.id in (".$childSql.") ";
    if (!empty($data['userBu'])) {
        $where .= " or k.user_bu in (" . $data['userBu'] . ") ";
    }
    if (!empty($data['deptIds'])) {
        $where .= " or k.organ_id in(" . $data['deptIds'] . ") ";
    }
    if (!empty($data['userIds'])) {
        $where .= " or k.user_id in(" . $data['userIds'] . ") ";
    }
    
} else {
    // 查询本人填报数据
    $where .= " and (k.user_id = '" . $userId . "' ";
    // //增加分享客户可见
    $childSql="select distinct b.customer_id from inhe_customer_share b where  b.user_ids='$userId'";

    $where .= " or k.id in (".$childSql.") ";
}


$where .= ") ";

$orderBy = " order by k.id desc ";
$commonSql = "select k.id,k.customer_name,k.short_name,u.user_name createUserName, u.user_name createUserName, d.dept_name deptName, " .
" tc.zh_name continentName, yc.zh_name countryName from inhe_customer_data k " .
" left join user u on u.user_id = k.user_id " .
" left join department d on d.dept_id = k.organ_id " .
" left join continent_code tc on tc.iso_two = k.continent " .
" left join country_code yc on yc.iso_three = k.country and yc.continent=k.continent " .
$where
. $orderBy;

//   echo $commonSql;exit;
$sql=$commonSql.$pageLimit;
$countSql = $commonSql;
$countRes = exequery(TD::conn(), $countSql);
$totalCount = mysql_num_rows($countRes);

$res = exequery(TD::conn(), $sql);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    if($item['version']>0){
        $customer_id= $item[id];
        $changeSql = " select k.id,k.customer_name,k.short_name,u.user_name createUserName, u.user_name createUserName, d.dept_name deptName,tc.zh_name continentName, yc.zh_name countryName from inhe_customer_data k "
        . "left join user u on u.user_id = k.user_id left join department d on d.dept_id = k.organ_id left join continent_code tc on tc.iso_two = k.continent left join country_code yc on yc.iso_three = k.country and yc.continent=k.continent "
        ."where 1=1 and k.type ='customer_data_change' and k.status in('R','Y') and k.version=".$item['version']." and k.related_id='".$item['form_id']."'";
        $changeData = exequery(TD::conn(), $changeSql);
        $item = mysql_fetch_assoc($changeData);
        $item[id] =$customer_id;
        
    }
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
