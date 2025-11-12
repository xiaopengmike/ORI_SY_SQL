<?php
include_once "../../common/CommonMethodModel.php";
include_once "../../common/SystemEnum.php";
include_once "../../common/DataModel.php";
include_once "../../common/UserModel.php";
$model = new CommonMethodModel();
$dataModel = new DataModel();
$userModel = new UserModel();
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_type,company,project_schedule_nodes,project_star',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$project_schedule_nodes = array();
foreach ($selectArr['project_schedule_nodes'] as $v) {
    $project_schedule_nodes[$v['paras_value']] = $v['paras_desc'];
}
$project_star = array();
foreach ($selectArr['project_star'] as $v) {
    $project_star[$v['paras_value']] = $v['paras_desc'];
}
$project_type = array();
foreach ($selectArr['project_type'] as $v) {
    $project_type[$v['paras_value']] = $v['paras_desc'];
}
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = "talent_record";
if (!empty($type)) {
    $systemId = $type;
}

$data = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
$userBu = $userModel->getUserBu($deptId);

$param = $dataModel->getParamToArray();
$whereStr = " where 1=1 ";
$queryStr = "";

if (array_key_exists('region', $param) && $param['region'] != "") {
    $whereStr .= " and (k.region like '%" . trim($param['region']) . "%' ";
    $whereStr .= " or exists(select * from inhe_project_main ipm
    left join department d on d.dept_id = ipm.organ_id
    where ipm.project_code=k.project_code and ipm.user_bu=k.user_bu and ipm.status in('Y','R') and d.dept_name like '%" . trim($param['region']) . "%') )";
    
}

if (array_key_exists('project_code', $param) && $param['project_code'] != "") {
    $whereStr .= " and k.project_code = '" . trim($param['project_code']) . "' ";
}

if (array_key_exists('status', $param) && $param['status'] != "") {
    $whereStr .= " and k.status = '" . trim($param['status']) . "' ";
}

if (array_key_exists('user_bu', $param) && $param['user_bu'] != "") {
    $whereStr .= " and k.user_bu = '" . trim($param['user_bu']) . "' ";
}

if (array_key_exists('u_date', $param) && $param['u_date'] != "") {
    $whereStr2 .= " and create_time >= '" . trim($param['u_date']) . "' ";
}

if (array_key_exists('end_u_date', $param) && $param['end_u_date'] != "") {
    $whereStr2 .= " and create_time <= '" . trim($param['end_u_date']) . "' ";
}

$groupBy = $pageLimit = "";
$groupBy = " group by project_code ";
$orderBy = " order by k.id desc ";
//$limit = " limit " . ($param['page'] - 1) * $param['page_size'] . "," . $param['page_size'];
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
// 排序功能
$laySort = array();
if (array_key_exists('sort_field', $param) && $param['sort_field'] != "" && array_key_exists('sort_way', $param) && $param['sort_way'] != "") {
    $orderBy = " order by k." . $param['sort_field'] . " " . $param['sort_way'];
    $laySort[$param['sort_field']] = $param['sort_way'];
}

$totalQuery =" select k.*,u.user_name createUserName,d.dept_name deptName " .
"  from inhe_project_main_report k " .
" left join user u on u.user_id = k.region_user_id " .
" left join department d on d.dept_id = k.dept_id " .
    $whereStr .$groupBy. $orderBy;
//  echo $totalQuery;
$query = $totalQuery . $pageLimit;
$res = exequery(TD::conn(), $query);
$res1 = exequery(TD::conn(), $totalQuery);
$count = mysql_num_rows($res1);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    $item[project_star]='';//星级
    $item[country]='';//项目所在国
    $item[end_date]='';//截标时间
    $item[project_model]='';//合作模式
    $item[project_need]='';    //项目需求
    $item[project_desc]='';//立项批示备注
    $item[nodes_desc]='';//进度描述
    $item[nodes_time]='';//进度填写时间
    $project_form_id="";
    $project_dept_id="";
    $project_user_dept_id="";
    $project_related_id="";

    $projectSql =" select a.*,u.user_name createUserName,d.dept_name deptName,d.dept_id user_dept_id,b.country,b.project_type,c.end_date  from inhe_project_main a 
     left join inhe_project_info b on b.form_id=a.form_id
     left join inhe_bidding_strategy c on c.form_id=a.form_id
     left join user u on u.user_id = a.user_id 
     left join department d on d.dept_id = u.dept_id
    where a.project_code='$item[project_code]' and a.user_bu='$item[user_bu]' and a.status in('Y','R')   order by a.id desc limit 1";
    $projectRes = exequery(TD::conn(), $projectSql);
    if($projectItem = mysql_fetch_assoc($projectRes)){
        $item[project_star]=$project_star[$projectItem[project_star]];//星级
        $item[project_model]=$project_type[$projectItem[project_type]];//合作模式
        $item[region]=$item[region]?$item[region]:($projectItem[dept_name]?$projectItem[dept_name]:$projectItem[deptName]);//区域
        $item[country]=$projectItem[country];//项目所在国
        $item[business_user_name]=$item[business_user_name]?$item[business_user_name]:$projectItem[createUserName];//业务负责人
        $item[end_date]=$projectItem[end_date];//截标时间
        $project_form_id=$projectItem[form_id];
        $project_related_id=$projectItem[related_id]?$projectItem[related_id]:$projectItem[form_id];
        $project_dept_id=$projectItem[organ_id];
        $project_user_dept_id=$projectItem[user_dept_id];
    }
    
    if($project_form_id){
        //项目需求
        $projectDetailSql =" select * from inhe_project_detail a where form_id='$project_form_id'";
        $projectDetailRes = exequery(TD::conn(), $projectDetailSql);
        //mysql_data_seek($projectDetailRes,0);//数据复位，不然以下$resDay取值后变空，无法实现for的多次循环
        while($projectDetailItem = mysql_fetch_assoc($projectDetailRes)){  
            $item[project_need].=$projectDetailItem[product]."       预期数量：".$projectDetailItem[number]."\n";//项目需求
        }
        $projectOpinionSql =" select * from inhe_flow_opinion a where form_id='$project_form_id' and approve_user ='INHE-0036' and opinion!='' order by id desc limit 1";
        $projectOpinionRes = exequery(TD::conn(), $projectOpinionSql);
        if($projectOpinionItem = mysql_fetch_assoc($projectOpinionRes)){
            $item[project_desc].=$projectOpinionItem[signature]."：\n";//立项批示备注
            $item[project_desc].=$projectOpinionItem[opinion]."\n";//立项批示备注
        }
        $projectOpinionSql =" select * from inhe_flow_opinion a where form_id='$project_form_id' and approve_user ='meterw'   order by id desc limit 1";
        $projectOpinionRes = exequery(TD::conn(), $projectOpinionSql);
        if($projectOpinionItem = mysql_fetch_assoc($projectOpinionRes)){  
            if(!empty($projectOpinionItem[opinion])){
                $item[project_desc].=$projectOpinionItem[signature]."：\n";//立项批示备注
                $item[project_desc].=$projectOpinionItem[opinion]."\n";//立项批示备注
            }
            if($project_form_id==$project_related_id&&empty($item[project_time])){
                $item[project_time]=$projectOpinionItem[approve_time];//立项时间
            }
        }
    }

      
    if($project_related_id&&$project_form_id!=$project_related_id&&empty($item[project_time])){
        $projectOpinionSql =" select * from inhe_flow_opinion a where form_id='$project_related_id' and approve_user ='meterw'   order by id desc limit 1";
        $projectOpinionRes = exequery(TD::conn(), $projectOpinionSql);
        if($projectOpinionItem = mysql_fetch_assoc($projectOpinionRes)){  
            $item[project_time]=$projectOpinionItem[approve_time];//立项时间
        }
    }

    $item[project_time]=$item[project_time]?$item[project_time]:$item[create_time];
   
    if(!empty($project_dept_id)){
        $regionSql =" select *  from inhe_project_region a 
        where a.status='Y'  and a.business_dept_id='$project_dept_id' order by a.id desc limit 1";
        $regionRes = exequery(TD::conn(), $regionSql);
        if($regionItem = mysql_fetch_assoc($regionRes)){
            $item[region_user_name]=$item[region_user_name]?$item[region_user_name]:$regionItem[business_user_name];//区域负责人
            $item[region]=$item[region]?$item[region]:$regionItem[business_dept_name];
        }else{
            $regionSql2 =" select *  from inhe_project_region a 
            where a.status='Y'  and a.business_dept_id='$project_user_dept_id' order by a.id desc limit 1";
            $regionRes2 = exequery(TD::conn(), $regionSql2);
            if($regionItem2 = mysql_fetch_assoc($regionRes2)){
                $item[region_user_name]=$item[region_user_name]?$item[region_user_name]:$regionItem2[business_user_name];//区域负责人
                $item[region]=$item[region]?$item[region]:$regionItem2[business_dept_name];
            }
        }
    }

    $scheduleSql =" select *  from inhe_project_schedule a 
     left join inhe_project_region b on b.id = a.region_id 
     where a.status='Y' and a.project_code='$item[project_code]' and a.user_bu='$item[user_bu]' order by a.id desc limit 1";
    $scheduleRes = exequery(TD::conn(), $scheduleSql);
    if($scheduleItem = mysql_fetch_assoc($scheduleRes)){
        $item[nodes_desc]=$project_schedule_nodes[$scheduleItem[progress_nodes]]."\n";//进度描述
        $item[nodes_desc].=$scheduleItem[nodes_desc];//进度描述
        $item[nodes_time]=$scheduleItem[create_time];//进度填写时间
        //$item[region_user_name]=$item[region_user_name]?$item[region_user_name]:$scheduleItem[business_user_name];//区域负责人
    }
    

    $data[] = $item;
}
// 释放结果集
mysql_free_result($res);
if ($param["export"]) {
    include_once("../export.php");
    $exportModel = new ExportModel();

    $titleArr = array(
        "id" => "序号",
        "region" => "区域",
        "project_code" => "项目",
        "project_star" => "星级",
        "project_model" => "合作模式",
        "project_need" => "项目需求",
        "project_time" => "立项时间",
        "country" => "项目所在国",
        "business_user_name" => "业务负责人",
        "region_user_name" => "区域负责人",
        "end_date" => "截标时间",
        "project_desc" => "立项批示/备注",
        "nodes_desc" => "进度说明",
        "nodes_time" => "进度时间",
    );
    $workSheetName = "立项统计表";

    $exportModel->exportExcel($data, $titleArr, $workSheetName);
}
$result = array(
    "code" => 0,
    "msg" => "",
    "count" => $count,
    "data" => $data,
    "totalRow"=>array('leave_time'=>$sum_time['sum_time']),
);

echo json_encode($model->array_iconv($result));
exit;
