<?php
include_once("../common/DataModel.php");  // 公共类
require_once("../common/UserModel.php");
$model = new DataModel();
$userModel = new UserModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,company,crm_process_sort',
);
$selectArr = $model->getCommonParam($selectParam);
$project_star = array();
foreach ($selectArr['project_star'] as $v) {
    $project_star[$v['paras_value']] = $v['paras_desc'];
}
$company_names=array();
foreach ($selectArr['company'] as $v) {
    $company_names[$v['paras_value']]=$v['paras_desc'];
}
// $limit
// $page
$param = $model->getParamToArray();
$where =" where 1=1 and m.status in('R','Y') and m.type ='project_review'";
if (!array_key_exists("scope", $param) || $param['scope'] != "all") {
    // and m.user_id = '$userId'
    //$where .= " and m.type ='project_review'  ";
    $pageLimit = "";
    // 权限管控: 立项申请.主表（申请人）.申请人=填报人 或 项目参与人.主表.项目负责人=填报人 或 项目参与人.参与人.项目参与人=填报人
    // or b.leader_id='$userId' or c.user_id = '$userId'
    $where .= " and (m.user_id ='$userId' or a.leader_id='$userId' or b.user_id = '$userId') ";
}else{
    $where .= " and ((m.user_id ='$userId' or a.leader_id='$userId' or b.user_id = '$userId') ";
    $deptId = $_SESSION['LOGIN_DEPT_ID'];
    $systemId = "project_review_scope";
    $adminData = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
    $isAdmin = $adminData['isAdmin'];
    if (!empty($adminData['userBu'])) {
        $where .= " or m.user_bu in (" . $adminData['userBu'] . ") ";
    }
    $where .= ") ";
}

if (array_key_exists("project_code", $param) && $param['project_code'] != "") {
    $where .= " and m.project_code like '%" . trim($param['project_code']) . "%' ";
}

if (array_key_exists("company", $param) && $param['company'] != "") {
    if(in_array($param['company'],array('HKINHE','INHEIAC'))){
        $where .= " and (m.user_bu = '" . trim($param['company']) . "' ";
        $where .= " or m.user_bu = 'INHE' )";
    }else{
        $where .= " and m.user_bu = '" . trim($param['company']) . "' ";
    }
    
}
if (array_key_exists("project_star", $param) && $param['project_star'] != "") {
    $where .= " and m.project_star = '" . trim($param['project_star']) . "' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
$orderBy = " order by m.id desc ";
$groupBy = " group by m.form_id ";

// FIX: 由于部分公司名词带有,号，修改采用其他分隔符拼接
$sql = " select distinct m.*, GROUP_CONCAT(distinct p.customer_name SEPARATOR '||') as customer_name from inhe_project_main m left join inhe_customer_info p on m.form_id = p.form_id "
    . " left join inhe_project_participant a on a.project_code = m.project_code and a.status='Y' "
    . " left join inhe_project_participant_item b on b.form_id = a.form_id "
    . $where . $groupBy . $orderBy;
    //echo $sql;
$countSql = " select count(*) total_count from ($sql) t ";
$countRes = exequery(TD::conn(), $countSql);
$countArr = mysql_fetch_assoc($countRes);
$totalCount = $countArr['total_count'];

$res = exequery(TD::conn(), $sql . $pageLimit);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    if($item['version']>0){
        $changeSql = "select distinct m.*, GROUP_CONCAT(distinct p.customer_name SEPARATOR '||') as customer_name from inhe_project_main m left join inhe_customer_info p on m.form_id = p.form_id "
        ."where 1=1 and m.type ='project_review_change' and m.status in('R','Y') and version=".$item['version']." and related_id='".$item['form_id']."'";
        $changeData = exequery(TD::conn(), $changeSql);
        $item = mysql_fetch_assoc($changeData);
        $item['form_id']=$item['related_id'];
    }  
    $item['project_star_desc'] = $project_star[$item['project_star']];
    $item['user_bu_name'] = $company_names[$item['user_bu']];
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
