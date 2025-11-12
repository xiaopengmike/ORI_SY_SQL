<?php
require_once("../common/CommonMethodModel.php");
require_once("../common/SystemEnum.php");
require_once("../common/DataModel.php");
require_once("../common/UserModel.php");
$model = new CommonMethodModel();
$dataModel = new DataModel();
$userModel = new UserModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = "shipping_notice";
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
$param = $model->getParamToArray();
$checkedIds = $param['checkedIds'];
$checkedArr = explode(',', $checkedIds);

$whereStr = " where 1=1 ";
$queryStr = "";
if (array_key_exists('form_id', $param) && $param['form_id'] != "") {
    $whereStr .= " and k.form_id like '%" . trim($param['form_id']) . "%' ";
}
if (array_key_exists('project_code', $param) && $param['project_code'] != "") {
    $whereStr .= " and k.title like '%" . trim($param['project_code']) . "%' ";
}

if (array_key_exists('batch_number', $param) && $param['batch_number'] != "") {
    $whereStr .= " and k.batch_number like '%" . trim($param['batch_number']) . "%' ";
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
$project_numbers=$dataModel->findNumberParticipant($userId);
if(!empty($project_numbers)){
    $project_numbers_str='';
    foreach ($project_numbers as $v) {
        $project_numbers_str .= "'" . $v . "',";
    }
    $project_numbers_str = rtrim($project_numbers_str, ',');
    $whereStr .= " or k.project_number in ($project_numbers_str)";
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
    "  from inhe_shipping_notice k " .
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
    $result[] = $item;
}
// 释放结果集
mysql_free_result($res);

?>

<div style="text-align: right;width: 98%;margin:auto;">
    <div style="float: left;">
        <div class="dropdown">
            <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">新建</button>
            <div class="dropdown-content" align="center">
                <?php foreach ($crm_process_sort as $company => $arr) { ?>
                    
                    <?if($company=="INHENERGY"):?>
                        <div class="dropdown-child">
                        <a href="javascript:void(0);" class="newColor" onclick="$.addByProcessSort('<?= $company ?>','<?= $arr['paras_desc'] ?>','<?= $crm_process_type[$systemId] ?>','<?= $userBu ?>');return false;"><?= $arr['paras_desc'] ?></a>
                            <div class="dropdown-content-child" align="center">
                            <a href="javascript:void(0);" class="newColor" onclick="$.addByProcessSort('<?= $company ?>','<?= $arr['paras_desc'].'生产订单' ?>','<?= $crm_process_type[$systemId] ?>','<?= $userBu ?>');return false;">生产订单发货</a>
                                <a href="javascript:void(0);" class="newColor" onclick="$.addByProcessSort('RETAILNERGY','<?= $arr['paras_desc'].'国内零售库存产品' ?>','<?= $crm_process_type[$systemId] ?>','<?= $userBu ?>');return false;">国内零售库存产品发货</a>
                            </div>
                        </div>
                    <?elseif($company=="INHEGRID"):?>
                        <div class="dropdown-child">
                        <a href="javascript:void(0);" class="newColor" onclick="$.addByProcessSort('<?= $company ?>','<?= $arr['paras_desc'] ?>','<?= $crm_process_type[$systemId] ?>','<?= $userBu ?>');return false;"><?= $arr['paras_desc'] ?></a>
                            <div class="dropdown-content-child" align="center">
                            <a href="javascript:void(0);" class="newColor" onclick="$.addByProcessSort('<?= $company ?>','<?= $arr['paras_desc'].'(集团外)' ?>','<?= $crm_process_type[$systemId] ?>','<?= $userBu ?>');return false;">(集团外)发货通知单</a>
                                <a href="javascript:void(0);" class="newColor" onclick="$.addByProcessSort('INHEGRIDIN','<?= $arr['paras_desc'].'(集团内)' ?>','<?= $crm_process_type[$systemId] ?>','<?= $userBu ?>');return false;">(集团内)发货通知单</a>
                            </div>
                        </div>
                    <?else:?>
                        <a href="javascript:void(0);" class="newColor" onclick="$.addByProcessSort('<?= $company ?>','<?= $arr['paras_desc'] ?>','<?= $crm_process_type[$systemId] ?>','<?= $userBu ?>');return false;"><?= $arr['paras_desc'] ?></a>
                    <?endif;?>
                <?php } ?>
            </div>
        </div>
        <?=$model->crm_help('shipping_notice');?>
    </div>
    <?= $model->page_bar($param['page'], $count, $param['page_size'], '$.loadList(\'%s\')', '1') ?>
</div>
<br />
<table id="tab1" class="layui-table table-auto" style="width: 98%;margin:auto;">
    <tr class="layui-bg-head layui-bg-gray" id="tabHead">
        <th nowrap style="text-align: center;">序号</th>
        <th nowrap style="text-align: center;">
            <span>流程单号</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['form_id'] ?>" sort-field="form_id">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;">
            <span>客户名称</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['project_code'] ?>" sort-field="project_code">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;">项目编号批次</th>
        <th nowrap style="text-align: center;">
            <span>标题</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['title'] ?>" sort-field="title">
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
                <td colspan="9">
                    <div style="text-align: center;">暂无数据</div>
                </td>
            </tr>
        <?php else : ?>
            <?php $line = 0;
            foreach ($result as $k => $val) : $line++; ?>
                <tr class="TableLine<?= $line % 2 == 0 ? 1 : 2 ?>">
                    <input type="hidden" id="id" name="id" value="<?= $val['id'] ?>" />
                    <td align="center" width="3%"><?= $line + ($param['page'] - 1) * $param['page_size'] ?></td>
                    <td align="center"><?= $val["form_id"] ?></td>
                    <td align="center"><?= $val["customer_name"] ?></td>
                    <td align="center"><?= $val["batch_number"] ?></td>
                    <td align="center"><?= $val["title"] ?></td>
                    <td align="center"><?= $val["deptName"] . ' ' . $val["createUserName"] ?></td>
                    <td align="center"><?= $val["write_time"] ?></td>
                    <td align="center">
                        <font class="<?= SystemEnum::$STATUS_CSS[$val["status"]] ?>"><?= SystemEnum::$STATUS[$val["status"]] ?></font>
                    </td>
                    <td nowrap align="center">
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('detail.php?id=<?= $val[form_id] ?>&operate=detail','detail');return false;">查阅</a>&nbsp;
                        <?php if ($val["status"] != 'T') : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('../common/list/process_schedule.php?id=<?= $val[form_id] ?>','smallModel');return false;">查看进度</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['user_id'] || $isAdmin)&&$val['step']==1 && ($val["status"] == 'T' || $val["status"] == 'N')) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('modify.php?id=<?= $val[form_id] ?>','modify');return false;">修改</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['approve_user']) && ($val["status"] == 'P'||($val["status"] == 'N'&&$val['step']>1))) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('detail.php?id=<?= $val[form_id] ?>&operate=approve&step=<?=$val[step]?>','detail');return false;">办理</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['user_id'] || $isAdmin) && ($val["status"] == 'T' || $val["status"] == 'N')) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('action.php?action=Delete&id=<?= $val[form_id] ?>','delete');return false;">删除</a>&nbsp;
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>