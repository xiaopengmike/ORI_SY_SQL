<?php
include_once("../common/SystemEnum.php");
require_once("../common/UserModel.php");
require_once("../common/DataModel.php");
require_once("../common/CommonMethodModel.php");
$userModel = new UserModel();
$dataModel = new DataModel();
$commonModel = new CommonMethodModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userBu = $userModel->getUserBu($deptId);
$systemId = "project_review";
if (!empty($type)) $systemId = $type;
$data = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
$systemAdmin = $data['systemAdmin'];
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
$param = $commonModel->getParamToArray();
$checkedIds = $param['checkedIds'];
$checkedArr = explode(',', $checkedIds);

$whereStr = " where 1=1 ";
$queryStr = "";
if (array_key_exists('form_id', $param) && $param['form_id'] != "") {
    $whereStr .= " and k.form_id like '%" . trim($param['form_id']) . "%' ";
}
if (array_key_exists('project_code', $param) && $param['project_code'] != "") {
    $whereStr .= " and k.project_code like '%" . trim($param['project_code']) . "%' ";
}

if (array_key_exists('country', $param) && $param['country'] != "") {
    $whereStr .= " and info.country like '%" . trim($param['country']) . "%' ";
}

if (array_key_exists('create_user_name', $param) && $param['create_user_name'] != "") {
    $whereStr .= " and u.user_name like '%" . $param['create_user_name'] . "%' ";
}
if (array_key_exists('create_time', $param) && $param['create_time'] != "") {
    $whereStr .= " and k.write_time like '%" . $param['create_time'] . "%' ";
}
if (array_key_exists('status', $param) && $param['status'] != "") {
    $whereStr .= " and k.status = '" . $param['status'] . "' ";
}
if (array_key_exists('type', $param) && $param['type'] != "") {
    $whereStr .= " and k.type = '" . $param['type'] . "' ";
}
if (array_key_exists('user_bu', $param) && $param['user_bu'] != "") {
    $whereStr .= " and k.user_bu = '" . $param['user_bu'] . "' ";
}

if (array_key_exists('change_star', $param) && $param['change_star'] != "") {
    $whereStr .= " and k.change_star = '" . $param['change_star'] . "' ";
}else{
    $whereStr .= " and (k.change_star !='1' or k.change_star is null)";
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
//增加立项登记人可见项目
// $sql="select distinct b.project_code from inhe_project_participant b left join inhe_project_participant_item a on a.form_id=b.form_id where b.status='Y' and (b.leader_id='$userId' or a.user_id='$userId')";
// $res = exequery(TD::conn(), $sql);
// $project_codes='';
// while ($item = mysql_fetch_assoc($res)) {
//     $project_codes .= "'".$item['project_code']."',";
// }
// if($project_codes){
//     $project_codes =rtrim($project_codes, ',');
//     $whereStr .= " or k.project_code in (".$project_codes.") ";
// }

$whereStr .= " or exists (select  b.project_code from inhe_project_participant b left join inhe_project_participant_item a on a.form_id=b.form_id where b.status='Y' and b.project_code=k.project_code and b.USER_BU=k.USER_BU and (b.leader_id='$userId' or a.user_id='$userId')) ";

$whereStr .= ") ";
$groupBy = $limit = "";
$orderBy = " order by k.id desc ";
$limit = " limit " . ($param['page'] - 1) * $param['page_size'] . "," . $param['page_size'];

// 排序功能
$laySort = array();
if (array_key_exists('sort_field', $param) && $param['sort_field'] != "" && array_key_exists('sort_way', $param) && $param['sort_way'] != "") {
    $orderBy = " order by k." . $param['sort_field'] . " " . $param['sort_way'];
    $laySort[$param['sort_field']] = $param['sort_way'];
}
$totalQuery = "select k.*,info.country,u.user_name createUserName, d.dept_name deptName,o.approve_user,o.step,o.id opinion_id " .
    "  from inhe_project_main k " .
    " left join inhe_project_info info on info.form_id = k.form_id  " .
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
            <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.startChangeForm();return false;">变更</button>
            <button class="layui-btn layui-btn-oa layui-btn-sm" style="margin-left: 0px;" onclick="changeStarForm();return false;">星级变更</button>
            <?=$commonModel->crm_help('project_review');?>
        </div>
    <?php } ?>
    <?= $commonModel->page_bar($param['page'], $count, $param['page_size'], '$.loadList(\'%s\')', '1') ?>
</div>
<br />
<table id="tab1" class="layui-table table-auto" style="width: 98%;margin:auto;">
    <tr class="layui-bg-head layui-bg-gray" id="tabHead">
        <!-- <input type="checkbox" id="check-all" name="check-all"> -->
        <th nowrap style="text-align: center; width: 3%">选择</th>
        <th nowrap style="text-align: center;">序号</th>
        <th nowrap style="text-align: center;">
            <span>流程单号</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['form_id'] ?>" sort-field="form_id">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;">
            <span>项目代号</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['project_code'] ?>" sort-field="name">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;">项目所在国</th>
        <th nowrap style="text-align: center;">制单人</th>
        <th nowrap style="text-align: center;">制单时间</th>
        <th nowrap style="text-align: center;">办理状态</th>
        <th nowrap style="text-align: center;">操作</th>
    </tr>
    <tbody id="tabBody" name="tabBody" class="layui-bg-body">
        <?php if (!isset($result) || $count == 0) : ?>
            <tr class="TableData">
                <td colspan="9">
                    <div style="text-align: center;">暂无数据</div>
                </td>
            </tr>
        <?php else : ?>
            <?php $line = 0;
            foreach ($result as $k => $val) : $line++; ?>
                <tr class="TableLine<?= $line % 2 == 0 ? 1 : 2 ?>">
                    <td align="center"><input type="checkbox" id="<?= $val["form_id"] ?>" name="cb_list" title='<?= $val["status"] ?>' <?php if (in_array($val['id'], $checkedArr)) { ?>checked<?php } ?>></td>
                    <td align="center" width="3%"><?= $line + ($param['page'] - 1) * $param['page_size'] ?></td>
                    <td align="center"><?= $val["form_id"] ?></td>
                    <td align="center"><?= $val["project_code"] ?></td>
                    <td align="center"><?= $val["country"] ?></td>
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
                        <!-- <a href="javascript:void(0);" class="newColor" onclick="$.historyPage('<?= $val[form_id] ?>');return false;">变更历史</a>&nbsp; -->
                        <?php if (($userId == $val['user_id']||$userId == $val['approve_user'] || $systemAdmin) && ($val["status"] == 'T' || ($val["status"] == 'N'&&$val['step']==1))) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('modify.php?id=<?= $val[form_id] ?>','modify');return false;">修改</a>&nbsp;
                        <?php endif; ?>
                        <?php if (!empty($val['opinion_id'])&&!empty($val['from_id'])&&($userId == $val['user_id'] || $systemAdmin ||$userId == $val['approve_user']) && ($val["status"] == 'T' || ($val["status"] == 'N'&&$val['step']==1))) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('update_flow_opinion.php?id=<?= $val[opinion_id] ?>&&form_id=<?= $val[form_id] ?>&&type=<?= $val[type] ?>&&step=1','modify');return false;">移交</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId ==$val['approve_user']||in_array($userId,explode(',',$val['approve_user']))) && ($val["status"] == 'P' ||$val["status"] == 'R'||($val["status"] == 'N'&&$val['step']>1))) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('detail.php?id=<?= $val[form_id] ?>&operate=approve&step=<?=$val[step]?>','detail');return false;">办理</a>&nbsp;
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