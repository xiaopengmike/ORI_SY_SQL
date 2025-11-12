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

if (array_key_exists("form_id", $param) && $param['form_id'] != "") {
    $where .= " and m.form_id = '" . trim($param['form_id']) . "' ";
}
if (array_key_exists("project_name", $param) && $param['project_name'] != "") {
    $where .= " and m.project_name = '" . trim($param['project_name']) . "' ";
}

// if (array_key_exists("company", $param) && $param['company'] != "") {
//     $where .= " and m.user_bu = '" . trim($param['company']) . "' ";
// }

if (array_key_exists("project_number", $param) && $param['project_number'] != "") {
    $where .= " and m.project_number like '%" . trim($param['project_number']) . "%' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}

// 数据权限管控
$where .= " and (m.user_id = '" . $userId . "' or exists(SELECT id FROM inhe_flow_opinion where form_id=m.form_id and approve_user='$userId' LIMIT 1)";
// //增加立项登记人可见项目
// $sql="select distinct b.project_code from inhe_project_participant b left join inhe_project_participant_item a on a.form_id=b.form_id where b.status='Y' and (b.leader_id='$userId' or a.user_id='$userId')";
// //echo $sql;
// $res = exequery(TD::conn(), $sql);
// //360极速浏览器下载$item=mysql_fetch_assoc($res);
// $project_codes='';
// while ($item = mysql_fetch_assoc($res)) {
//     $project_codes .= "'".$item['project_code']."',";
// }
// if($project_codes){
//     $project_codes =rtrim($project_codes, ',');
//     $where .= " or p.project_code in (".$project_codes.") ";
// }

$project_numbers=$model->findNumberParticipant($userId);
if(!empty($project_numbers)){
    $project_numbers_str='';
    foreach ($project_numbers as $v) {
        $project_numbers_str .= "'" . $v . "',";
    }
    $project_numbers_str = rtrim($project_numbers_str, ',');
    $where .= " or m.project_number in ($project_numbers_str)";
}

$where .= ") ";

$orderBy = " order by m.id desc ";
$projectCondition = " and p.type='project_review' and p.project_star != '' ";
$commonSql = " select distinct m.delivery_time,m.customer_order_no,m.form_id, m.customer_name, m.contract_party, m.project_name, m.project_number, m.project_number, m.contract_belong,m.version m_version,d.spare_requirement "
    . ", p.project_star, p.star_icon,d.past_pcode,d.c_past_pcode,d.contract_begin_time,d.contract_end_time from inhe_contract_review m left join inhe_contract_detail d on m.form_id=d.form_id left join inhe_project_main p on m.project_name = p.project_code "
    . $projectCondition. $where  . $orderBy;

    //echo $commonSql;exit;
$sql=$commonSql.$pageLimit;
$countSql = $commonSql;
$countRes = exequery(TD::conn(), $countSql);
$totalCount = mysql_num_rows($countRes);

$res = exequery(TD::conn(), $sql);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    if($item['m_version']>0){
        $changeSql = " select distinct m.delivery_time,m.customer_order_no,m.related_id form_id,m.form_id change_form_id, m.customer_name, m.contract_party, m.project_name, m.project_number , m.project_number, m.contract_belong ,m.version m_version,d.spare_requirement"
        . ",p.project_star, p.star_icon,d.past_pcode,d.c_past_pcode,d.contract_begin_time,d.contract_end_time from inhe_contract_review m left join inhe_contract_detail d on m.form_id=d.form_id  left join inhe_project_main p on m.project_name = p.project_code "
        ."where 1=1 and m.type ='sales_contract_review_change' and m.status in('R','Y') and m.version=".$item['m_version']." and m.related_id='".$item['form_id']."'";
        $changeData = exequery(TD::conn(), $changeSql);
        $item = mysql_fetch_assoc($changeData);
        $item['star_icon'] = $project_star[$item['project_star']]['extra'];
        $item['project_star_desc'] = $project_star[$item['project_star']]['paras_desc'];
    }else{
        $item['project_star_desc'] = $project_star[$item['project_star']]['paras_desc'];
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
