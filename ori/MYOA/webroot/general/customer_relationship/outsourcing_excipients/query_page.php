<?php
require_once("../common/DataModel.php");
require_once("../common/SystemEnum.php");
require_once("../common/UserModel.php");
require_once("../common/CommonMethodModel.php");
$dataModel = new DataModel();
$userModel = new UserModel();
$common = new CommonMethodModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = "outsourcing_excipients";
if (!empty($type)) $systemId = $type;
$data = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
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
if (array_key_exists('project_code', $param) && $param['project_code'] != "") {
    $whereStr .= " and k.project_code like '%" . trim($param['project_code']) . "%' ";
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
    }
    $whereStr .= ") ";
} else {
    // 查询本人填报数据
     //增加权限显示，办理人（申请人和办理人）只可见自己内容
     $whereStr .= " and ((SELECT id FROM inhe_flow_opinion where form_id=k.form_id and approve_user='$userId' LIMIT 1) ";
}

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

$totalQuery = "select k.*,u.user_name createUserName, d.dept_name deptName,o.approve_user,o.step " .
    "  from inhe_outsourcing_excipients k " .
    " left join user u on u.user_id = k.user_id " .
    " left join department d on d.dept_id = k.organ_id " .
    " left join inhe_flow_opinion o on o.form_id=k.form_id and o.status='U' and o.approve_user='$userId' ".
    $whereStr
    . $orderBy;
$query = $totalQuery . $limit;
//echo $totalQuery;
if ($param["export"]) {
    $query = $totalQuery;
}
$res = exequery(TD::conn(), $query);
$res1 = exequery(TD::conn(), $totalQuery);
$count = mysql_num_rows($res1);
$result = array();
while ($item = mysql_fetch_assoc($res)) {
    $result[] = $item;
}

// 释放结果集
mysql_free_result($res);
if ($type == 'trial_outsourcing_excipients') $url = 'trial_add.php';
?>

<div style="text-align: right;width: 98%;margin:auto;">
    <?php if (strpos($type, '_change') == false) { ?>
        <div style="float: left;">
        <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="pageOperate('add.php?company=INHE', 'add');return false;">新建</button>
        </div>
    <?php } ?>
    <?= $common->page_bar($param['page'], $count, $param['page_size'], '$.loadList(\'%s\')', '1') ?>
</div>
<br />
<table id="tab1" class="layui-table table-auto" style="width: 98%;margin:auto;">
    <tr class="layui-bg-head layui-bg-gray" id="tabHead">
        <th nowrap style="text-align: center; width: 3%">选择</th>
        <th nowrap style="text-align: center;">序号</th>
        <th nowrap style="text-align: center;">
            <span>流程单号</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['form_id'] ?>" sort-field="form_id">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;">制单人</th>
        <th nowrap style="text-align: center;">制单时间</th>
        <th nowrap style="text-align: center;">办理状态</th>
        <th nowrap style="text-align: center;">操作</th>
    </tr>
    <tbody id="tabBody" name="tabBody" class="layui-bg-body">
        <?php if (!isset($result) || $count == 0) : ?>
            <tr class="TableData">
                <td colspan="7">
                    <div style="text-align: center;">暂无数据</div>
                </td>
            </tr>
        <?php else : ?>
            <?php $line = 0;
            foreach ($result as $k => $val) : $line++; ?>
                <tr class="TableLine<?= $line % 2 == 0 ? 1 : 2 ?>">
                    <input type="hidden" id="id" name="id" value="<?= $val['id'] ?>" />
                    <td align="center"><input type="checkbox" id="<?= $val["form_id"] ?>" name="cb_list" title='<?= $val["status"] ?>' <?php if (in_array($val['id'], $checkedArr)) { ?>checked<?php } ?>></td>
                    <td align="center" width="3%"><?= $line + ($param['page'] - 1) * $param['page_size'] ?></td>
                    <td align="center"><?= $val["form_id"] ?></td>
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
                        <?php if (($userId == $val['user_id'] || $isAdmin) && $val["status"] == 'T' || $val["status"] == 'N') : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('modify.php?id=<?= $val[form_id] ?>','modify');return false;">修改</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['approve_user']) && ($val["status"] == 'P')) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('detail.php?id=<?= $val[form_id] ?>&operate=approve&step=<?=$val[step]?>','detail');return false;">办理</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['user_id'] || $isAdmin) && ($val["status"] == 'T' || $val["status"] == 'N')) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('action.php?action=Delete&id=<?= $val[form_id] ?>','delete');return false;">删除</a>&nbsp;
                        <?php endif; ?>
                        <?php if ($val["status"] == 'R'||$val["status"] == 'Y') : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('to_lieu.php?id=<?= $val[form_id] ?>&operate=detail','detail');return false;">转代收货</a>&nbsp;
                        <?php endif; ?>
                        <!-- <a href="javascript:void(0);" class="newColor" onclick="pageOperate('inheOrJx.php?id=<?= $val[form_id] ?>&operate=detail','detail');return false;">抛单</a>&nbsp; -->
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>