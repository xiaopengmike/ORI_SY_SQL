<?php
require_once("../common/DataModel.php");
$model = new DataModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star',
);
$selectArr = $model->getCommonParam($selectParam);
$project_star = array();
foreach ($selectArr['project_star'] as $v) {
    $project_star[$v['paras_value']]= array('paras_desc'=>$v['paras_desc'],'extra'=>$v['extra']);
}

// $limit
// $page
$param = $model->getParamToArray();
// and m.create_user = '$userId'
$where = " where 1=1 and m.type ='sales_contract_review' and m.status in('R','Y') ";
$pageLimit = "";

// 取项目编号参与人数据控制权限 TODO
// 筛选条件:CRM03.申请人.制单人=填报人 或 CRM23.主表.项目负责人=填报人 或 CRM23.参与人.项目参与人=填报人 或  当前用户="鲁慧珍"

// if (array_key_exists("form_id", $param) && $param['form_id'] != "") {
//     $where .= " and m.form_id = '" . trim($param['form_id']) . "' ";
// }
// if (array_key_exists("project_name", $param) && $param['project_name'] != "") {
//     $where .= " and m.project_name = '" . trim($param['project_name']) . "' ";
// }
if (array_key_exists("project_number", $param) && $param['project_number'] != "") {
    $where .= " and m.project_number = '" . trim($param['project_number']) . "' ";
}else{
    $result = array(
        "code" => 0,
        "msg" => "",
        "count" => 0,
        "data" => array()
    );
    
    echo json_encode($model->array_iconv($result));exit;
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}

$orderBy = " order by m.id desc ";
$commonSql = " select distinct m.form_id,m.currency, m.customer_name, m.contract_party, m.project_name, m.project_number, m.project_number, m.contract_belong,m.version m_version "
    . ",k.dept_id,k.create_user,k.total_amount,k.business_attribution,d.dept_name,u.user_name create_user_name from inhe_contract_review m left join inhe_contract_detail k on m.form_id=k.form_id "
    ." left join user u on u.user_id = k.create_user left join department d on d.dept_id = k.dept_id "
    . $where . $orderBy;

    //echo $commonSql;exit;
$sql=$commonSql.$pageLimit;
$countSql = $commonSql;
$countRes = exequery(TD::conn(), $countSql);
$totalCount = mysql_num_rows($countRes);

$res = exequery(TD::conn(), $sql);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    if($item['m_version']>0){
        $changeSql = " select distinct m.related_id form_id,m.currency,m.form_id change_form_id, m.customer_name, m.contract_party, m.project_name, m.project_number , m.project_number, m.contract_belong ,m.version m_version"
        . ",m.project_star,k.dept_id,k.create_user,k.total_amount,k.business_attribution,d.dept_name,u.user_name create_user_name  from inhe_contract_review m left join inhe_contract_detail k on m.form_id=k.form_id "
        ." left join user u on u.user_id = k.create_user left join department d on d.dept_id = k.dept_id "
        ."where 1=1 and m.type ='sales_contract_review_change' and m.status in('R','Y') and version=".$item['m_version']." and related_id='".$item['form_id']."'";
        $changeData = exequery(TD::conn(), $changeSql);
        $item = mysql_fetch_assoc($changeData);
    }
    $data[] = $item;
}
mysql_free_result($res);

$result = array(
    "code" => 200,
    "msg" => "",
    "count" => $totalCount,
    "data" => $data
);

echo json_encode($model->array_iconv($result));
exit;
