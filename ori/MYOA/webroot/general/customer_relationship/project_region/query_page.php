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
$userBu = $userModel->getUserBu($deptId);

$systemId="project_schedule";
$data = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
$systemAdmin = $data['systemAdmin'];


$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'company,project_schedule_nodes',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$company = array();
foreach ($selectArr['company'] as $cc) {
    $company[$cc['paras_value']] = $cc['paras_desc'];
}

$project_schedule_nodes = array();
foreach ($selectArr['project_schedule_nodes'] as $v) {
    $project_schedule_nodes[$v['paras_value']] = $v['paras_desc'];
}
$param = $dataModel->getParamToArray();
$whereStr = " where 1=1 ";

// 数据权限范围
if ($isAdmin) {
    $whereStr .= " and ((k.user_id = '" . $userId . "' ";
    if (!empty($data['userBu'])) {
        $whereStr .= " or k.user_bu in (" . $data['userBu'] . ") ";
    }
    if (!empty($data['deptIds'])) {
        $whereStr .= " or k.dept_id in(" . $data['deptIds'] . ") ";
    }
    if (!empty($data['userIds'])) {
        $whereStr .= " or k.user_id in(" . $data['userIds'] . ") ";
    }
    $whereStr .= ") ";
} else {
     //增加权限显示，办理人（申请人和办理人）只可见自己内容
     $whereStr .= " and (k.user_id = '" . $userId . "' ";
}
$whereStr .= ") ";

$queryStr = "";
if (array_key_exists("delivery_user_name", $param) && $param['delivery_user_name'] != "") {
    $whereStr .= " and k.delivery_user_name like '%" . trim($param['delivery_user_name']) . "%' ";
}
if (array_key_exists("name", $param) && $param['name'] != "") {
    $whereStr .= " and k.name like '%" . trim($param['name']) . "%' ";
}

if (array_key_exists("business_user_name", $param) && $param['business_user_name'] != "") {
    $whereStr .= " and k.business_user_name like '%" . trim($param['business_user_name']) . "%' ";
}
$groupBy = $limit = "";
$orderBy = " order by k.id desc ";
$limit = " limit " . ($param['page'] - 1) * $param['page_size'] . "," . $param['page_size'];

// 排序功能
$laySort = array();
if (array_key_exists('sort_field', $param) && $param['sort_field'] != "" && array_key_exists('sort_way', $param) && $param['sort_way'] != "") {
    $orderBy = " order by k." . $param['sort_field'] . " " . $param['sort_way'];
    $laySort[$param['sort_field']] = $param['sort_way'];
}

$totalQuery = "select k.*,u.user_name createUserName, d.dept_name deptName " .
    "  from inhe_project_region k " .
    " left join user u on u.user_id = k.user_id " .
    " left join department d on d.dept_id = k.dept_id " .
    $whereStr
    . $orderBy;
$query = $totalQuery . $limit;
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
            <?php foreach ($company as $k => $v) { ?>
                <?php if($k=="CORP"){continue;}?>
                <a href="javascript:void(0);" class="newColor" onclick="$.addByProcessSort('<?= $k ?>','<?= $v ?>','<?= '项目区域管理' ?>','<?= $companyNew ?>');return false;"><?= $v ?></a>
            <?php } ?>
        </div>
    </div>
        <!-- <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="pageOperate('add.php','smallModel');return false;">登记</button>
        -->
    </div>
    <?= $common->page_bar($param['page'], $count, $param['page_size'], '$.loadList(\'%s\')', '1') ?>
</div>
<br />
<table id="tab1" class="layui-table table-auto" style="width: 98%;margin:auto;">
    <tr class="layui-bg-head layui-bg-gray" id="tabHead">
        <th nowrap style="text-align: center;">序号</th>
        <th style="text-align: center;">
            <span>项目所在地/区域</span>
        </th>
        <th nowrap style="text-align: center;">业务部门</th>
        <th nowrap style="text-align: center;">业务负责人</th>
        <th nowrap style="text-align: center;">交付经理</th>
        <th nowrap style="text-align: center;">归属公司</th>
        <th nowrap style="text-align: center;">登记人员</th>
        <th nowrap style="text-align: center;">登记时间</th>
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
                    <td align="center" width="3%"><?= $line + ($param['page'] - 1) * $param['page_size'] ?></td>
                    <td align="center"><?= $val["name"] ?></td>
                    <td align="center"><?= $val["business_dept_name"] ?></td>
                    <td align="center"><?= $val["business_user_name"] ?></td>
                    <td align="center"><?= $val["delivery_user_name"] ?></td>
                    <td align="center"><?= $company[$val["user_bu"]] ?></td>
                    <td align="center"><?= $val["deptName"] . ' ' . $val["createUserName"] ?></td>
                    <td align="center"><?= $val["create_time"] ?></td>
                    <td align="center">
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('detail.php?id=<?= $val[id] ?>&operate=detail','detail');return false;">查阅</a>&nbsp;
                        <? if (($userId == $val['user_id']&&$val["status"] == 'T') || $systemAdmin) : ?>
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('add.php?id=<?= $val[id] ?>','modify');return false;">修改</a>&nbsp;
                        <?endif;?>
                        <? if (($userId == $val['user_id']&&$val["status"] == 'T') || $systemAdmin) : ?>
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('action.php?action=Delete&id=<?= $val[id] ?>','delete');return false;">删除</a>&nbsp;
                        <?endif;?>
                        <? if (($userId == $val['user_id']&&($val["status"] == 'Y'||$val["status"] == 'N')) || $systemAdmin) : ?>
                        <a href="javascript:void(0);" class="newColor" onclick="ForbiddenData('action.php?action=Forbidden&id=<?= $val[id] ?>','delete');return false;"><?=$val["status"] == 'Y'?'禁用':'启用'?></a>&nbsp;
                        <?endif;?>
                    </td>
                    
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>