<?php
require_once("../common/SystemEnum.php");
require_once("../common/DataModel.php");
require_once("../common/UserModel.php");
require_once("../common/CommonMethodModel.php");
$dataModel = new DataModel();
$userModel = new UserModel();
$common = new CommonMethodModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = "sales_contract_review";
if (!empty($type)) $systemId = $type;
$data = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
$systemAdmin = $data['systemAdmin'];
$userBu = $userModel->getUserBu($deptId);
// CRM表单类型
$selectParam = array(
    'is_used' => '1',
    'type' => 'crm_process_sort,crm_process_type'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$crm_process_sort = array();
foreach ($selectArr['crm_process_sort'] as $v) {
    $crm_process_sort[$v['paras_value']] = $v;
}
$crm_process_type = array();
foreach ($selectArr['crm_process_type'] as $v) {
    $crm_process_type[$v['paras_value']] = $v['paras_desc'];
}

$param = $dataModel->getParamToArray();
$checkedIds = $param['checkedIds'];
$checkedArr = explode(',', $checkedIds);

$whereStr = " where 1=1 ";
$queryStr = "";
if (array_key_exists('form_id', $param) && $param['form_id'] != "") {
    $whereStr .= " and k.form_id like '%" . trim($param['form_id']) . "%' ";
}
if (array_key_exists('customer_order_no', $param) && $param['customer_order_no'] != "") {
    $whereStr .= " and k.customer_order_no like '%" . trim($param['customer_order_no']) . "%' ";
}
if (array_key_exists('project_name', $param) && $param['project_name'] != "") {
    $whereStr .= " and k.project_name like '%" . trim($param['project_name']) . "%' ";
}
if (array_key_exists('customer_name', $param) && $param['customer_name'] != "") {
    $whereStr .= " and k.customer_name like '%" . trim($param['customer_name']) . "%' ";
}
if (array_key_exists('create_user_name', $param) && $param['create_user_name'] != "") {
    $whereStr .= " and u.user_name like '%" . $param['create_user_name'] . "%' ";
}
if (array_key_exists('create_time', $param) && $param['create_time'] != "") {
    $whereStr .= " and k.write_time like '%" . $param['create_time'] . "%' ";
}
if (array_key_exists('status', $param) && $param['status'] != "") {
    $whereStr .= " and  k.status ='" . $param['status'] . "' ";
}
if (array_key_exists('type', $param) && $param['type'] != "") {
    $whereStr .= " and k.type = '" . $param['type'] . "' ";
}
if (array_key_exists('user_bu', $param) && $param['user_bu'] != "") {
    $whereStr .= " and k.user_bu = '" . $param['user_bu'] . "' ";
}

if (array_key_exists('business_user_id', $param) && $param['business_user_id'] != "") {
    $whereStr .= " and kd.business_user_id = '" . trim($param[business_user_id]) . "' ";
}
if ($param['status'] == ""&&array_key_exists('business_status', $param) && $param['business_status'] == "Y") {
    $whereStr .= " and k.status in('R','Y') ";
}
//时间范围
if (array_key_exists('starting_time', $param) && $param['starting_time'] != "") {
    $whereStr .= " and k.write_time >= '" . $param['starting_time'] . "' ";
}
if (array_key_exists('ending_time', $param) && $param['ending_time'] != "") {
    $end_day=date('Y-m-d', strtotime ("+1 day", strtotime($param['ending_time'])));
    $whereStr .= " and k.write_time < '" . $end_day . "' ";
}
// 数据权限范围
if ($isAdmin) {
    $whereStr .= " and ((k.user_id = '" . $userId . "' ";
    $whereStr .= " or (SELECT id FROM inhe_flow_opinion where form_id=k.form_id and approve_user='$userId' LIMIT 1) ";
    if (!empty($data['userBu'])) {
        $whereStr .= " or k.user_bu in (" . $data['userBu'] . ") ";
    }
    if (!empty($data['deptIds'])) {
        $whereStr .= " or k.organ_id in(" . $data['deptIds'] . ") ";
    }
    if (!empty($data['deptIds'])) {
        $whereStr .= " or kd.business_dept_id in(" . $data['deptIds'] . ") ";
    }
    if (!empty($data['userIds'])) {
        $whereStr .= " or k.user_id in(" . $data['userIds'] . ") ";
        //郭涛可见二部数据
        if($userId=="INHE-0036"){
            $whereStr .= " or (SELECT id FROM inhe_flow_opinion where form_id=k.form_id and approve_user in(" . $data['userIds'] . ") LIMIT 1) ";
        }
        
    }
    $whereStr .= ") ";
} else {
     //增加权限显示，办理人（申请人和办理人）只可见自己内容
     $whereStr .= " and ((SELECT id FROM inhe_flow_opinion where form_id=k.form_id and approve_user='$userId' LIMIT 1) ";
}

$groupBy = $limit = "";
$orderBy = " order by k.id desc ";
$limit = " limit " . ($param['page'] - 1) * $param['page_size'] . "," . $param['page_size'];

// $project_numbers=$dataModel->findNumberParticipant($userId);
// if(!empty($project_numbers)){
//     $project_numbers_str='';
//     foreach ($project_numbers as $v) {
//         $project_numbers_str .= "'" . $v . "',";
//     }
//     $project_numbers_str = rtrim($project_numbers_str, ',');
//     $whereStr .= " or k.project_number in ($project_numbers_str)";
// }

$whereStr .= " or exists (select  b.project_code from inhe_number_participant b left join inhe_number_participant_item a on a.form_id=b.form_id where b.status='Y' and b.project_number=k.project_number and b.USER_BU=k.USER_BU and (b.leader_id='$userId' or a.user_id='$userId')) ";

$whereStr .= ") ";

// 排序功能
$laySort = array();
if (array_key_exists('sort_field', $param) && $param['sort_field'] != "" && array_key_exists('sort_way', $param) && $param['sort_way'] != "") {
    $orderBy = " order by k." . $param['sort_field'] . " " . $param['sort_way'];
    $laySort[$param['sort_field']] = $param['sort_way'];
}

$totalQuery = "select k.*,u.user_name createUserName, u.user_name createUserName, d.dept_name deptName,o.approve_user,o.step,o.id opinion_id  " .
    "  from inhe_contract_review k " .
    " left join inhe_contract_detail kd on k.form_id = kd.form_id " .
    " left join user u on u.user_id = k.user_id " .
    " left join department d on d.dept_id = k.organ_id " .
    " left join inhe_flow_opinion o on o.form_id=k.form_id and o.status='U' and o.approve_user='$userId' ".
    $whereStr
    . $orderBy;
$query = $totalQuery . $limit;
if ($param["export"]) {
    $query = $totalQuery;
}
$res = exequery(TD::conn(), $query);
$res1 = exequery(TD::conn(), $totalQuery);
$count = mysql_num_rows($res1);
$result = array();
while ($item = mysql_fetch_assoc($res)) {
    if($systemAdmin){
        $sql2="select * from inhe_flow_opinion where form_id='$item[form_id]' and type='$item[type]' and status='U' and step='1' ";
        $res2 = exequery(TD::conn(), $sql2);
        if($item2 = mysql_fetch_assoc($res2)){
            $item[opinion_id]=$item2[id];
        }
    }
    $result[] = $item;
}
// 释放结果集
mysql_free_result($res);

?>
<div style="text-align: right;width: 98%;margin:auto;">
    <?php if (strpos($type, '_change') == false) { ?>
        <div style="float: left;">
            <div class="dropdown">
                <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">新建</button>
                <div class="dropdown-content" align="center">
                    <?php foreach ($crm_process_sort as $company => $arr) { ?>
                        <a href="javascript:void(0);" class="newColor" onclick="$.addByProcessSort('<?= $company ?>','<?= $arr['paras_desc'] ?>','<?= $crm_process_type[$systemId] ?>','<?= $userBu ?>');return false;"><?= $arr['paras_desc'] ?></a>
                    <?php } ?>
                </div>
            </div>
            <div class="dropdown">
                <button style="width:105px" class="layui-btn layui-btn-oa layui-btn-sm drop-btn">新建(取合同信息)</button>
                <div class="dropdown-content" align="center">
                    <?php foreach ($crm_process_sort as $company => $arr) { ?>
                        <a href="javascript:void(0);" class="newColor" onclick="$.selectAddByProcessSort('<?= $company ?>','<?= $arr['paras_desc'] ?>','<?= $crm_process_type[$systemId] ?>','<?= $userBu ?>');return false;"><?= $arr['paras_desc'] ?></a>
                    <?php } ?>
                </div>
            </div>
            <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.startChangeForm();return false;">变更</button>
            <!-- <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="importData();return false;">导入</button> -->
            <?=$common->crm_help('sales_contract_review');?>
        </div>
    <?php } ?>
    <?= $common->page_bar($param['page'], $count, $param['page_size'], '$.loadList(\'%s\')', '1') ?>
</div>
<br />
<table id="tab1" class="layui-table table-auto" style="width: 98%;margin:auto;">
    <tr class="layui-bg-head layui-bg-gray" id="tabHead">
        <th nowrap style="text-align: center;width: 1%">选择</th>
        <th nowrap style="text-align: center;width: 1%">序号</th>
        <th nowrap style="text-align: center;width: 10%">
            <span>编号</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['form_id'] ?>" sort-field="form_id">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <?php if (strpos($type, '_change') == true) { ?>
        <th nowrap style="text-align: center;width: 10%">
            <span>原编号</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['related_id'] ?>" sort-field="related_id">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <?php } ?>
        <th nowrap style="text-align: center;width: 10%">
            <span>合同编号</span>
        </th>
        <th nowrap style="text-align: center;">
            <span>项目编号</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['project_number'] ?>" sort-field="project_number">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;">客户名称</th>
        <th nowrap style="text-align: center;">制单人</th>
        <th nowrap style="text-align: center;">制单时间</th>
        <th nowrap style="text-align: center;">办理状态</th>
        <th nowrap style="text-align: center;">操作</th>
    </tr>
    <tbody id="tabBody" name="tabBody" class="layui-bg-body">
        <?php if (!isset($result) || $count == 0) : ?>
            <tr class="TableData">
                <td colspan="10">
                    <div style="text-align: center;">暂无数据</div>
                </td>
            </tr>
        <?php else : ?>
            <?php $line = 0;
            foreach ($result as $k => $val) : $line++; ?>
                <tr class="TableLine<?= $line % 2 == 0 ? 1 : 2 ?>">
                    <input type="hidden" id="id" name="id" value="<?= $val['id'] ?>" />
                    <td align="center"><input type="checkbox" id="<?= $val["form_id"] ?>" name="cb_list" title='<?= $val["status"] ?>' <?php if (in_array($val['id'], $checkedArr)) { ?>checked<?php } ?>></td>
                    <td align="center"><?= $line + ($param['page'] - 1) * $param['page_size'] ?></td>
                    <td align="center"><?= $val["form_id"] ?></td>
                    <?php if (strpos($type, '_change') == true) { ?>
                        <td align="center"><?= $val["related_id"] ?></td>
                    <?php } ?>
                    <td align="center"><?= $val['user_bu']=='INHENERGY'||$val['user_bu']=='HKNERGY'?($val["related_id"]?$val["related_id"]:$val["form_id"]):$val["customer_order_no"]?></td>
                    <td align="center"><?= $val["project_number"] ?></td>
                    <td align="center"><?= $val["customer_name"] ?></td>
                    <td align="center"><?= $val["deptName"] . ' ' . $val["createUserName"] ?></td>
                    <td align="center"><?= $val["write_time"] ?></td>
                    <td align="center">
                        <font class="<?= SystemEnum::$STATUS_CSS[$val["status"]] ?>"><?= SystemEnum::$STATUS[$val["status"]] ?></font>
                    </td>
                    <td align="center">
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('detail.php?id=<?= $val[form_id] ?>&operate=detail','detail');return false;">查阅</a>&nbsp;
                        <?php if ($val["status"] != 'T') : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('../common/list/process_schedule.php?id=<?= $val[form_id] ?>','smallModel');return false;">查看进度</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['user_id']||$userId == $val['approve_user'] || $systemAdmin) && ($val["status"] == 'T' || $val["status"] == 'N')) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('modify.php?id=<?= $val[form_id] ?>','modify');return false;">修改</a>&nbsp;
                        <?php endif; ?>
                        <?php if (!empty($val['opinion_id'])&&!empty($val['from_id'])&&($userId == $val['user_id'] || $systemAdmin||$userId == $val['approve_user']) && ($val["status"] == 'T' || ($val["status"] == 'N'&&$val['step']==1))) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('update_flow_opinion.php?id=<?= $val[opinion_id] ?>&&form_id=<?= $val[form_id] ?>&&type=<?= $val[type] ?>&&step=1','modify');return false;">移交</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['approve_user']) && ($val["status"] == 'P')) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('detail.php?id=<?= $val[form_id] ?>&operate=approve&step=<?=$val[step]?>','detail');return false;">办理</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['user_id'])&&($userId != $val['approve_user']) && ($val["status"] == 'P')&& ($val["urgent"] == '是')) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="sureData('action.php?action=Submit&form_type=urgent_notify&type=<?= $val[type] ?>&form_id=<?= $val[form_id] ?>&operate=approve&step=<?=$val[step]?>','detail');return false;">催办</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['user_id']||$userId == $val['approve_user'] || $systemAdmin) && ($val["status"] == 'T' || ($val["status"] == 'N'&&$val['step']==1))) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('action.php?action=Delete&id=<?= $val[form_id] ?>','delete');return false;">删除</a>&nbsp;
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>