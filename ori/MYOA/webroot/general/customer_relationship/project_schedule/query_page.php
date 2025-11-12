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
    $whereStr .= " and ((k.create_user_id = '" . $userId . "' ";
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
     $whereStr .= " and (k.create_user_id = '" . $userId . "' or exists(select * from inhe_project_region where id=k.region_id 
     and (find_in_set('$userId',business_user) or find_in_set('$userId',delivery_user)))";
}
$whereStr .= ") ";

$queryStr = "";
if (array_key_exists("project_code", $param) && $param['project_code'] != "") {
    $whereStr .= " and k.project_code like '%" . trim($param['project_code']) . "%' ";
}
if (array_key_exists("region", $param) && $param['region'] != "") {
    $whereStr .= " and k.region like '%" . trim($param['region']) . "%' ";
}
if (array_key_exists("progress_nodes", $param) && $param['progress_nodes'] != "") {
    $whereStr .= " and k.progress_nodes = '" . $param['progress_nodes']. "' ";
}
if (array_key_exists("user_name", $param) && $param['user_name'] != "") {
    $whereStr .= " and k.user_name like '%" . trim($param['user_name']) . "%' ";
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
    "  from inhe_project_schedule k " .
    " left join user u on u.user_id = k.create_user_id " .
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
        <!-- <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="pageOperate('add.php','smallModel');return false;">登记</button> -->
        <div class="dropdown">
        <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn">登记</button>
        <div class="dropdown-content" align="center">
            <?php foreach ($company as $k => $v) { ?>
                <?php if($k=="CORP"){continue;}?>
                <a href="javascript:void(0);" class="newColor" onclick="$.addByProcessSort('<?= $k ?>','<?= $v ?>','<?= '项目进度登记' ?>','<?= $companyNew ?>');return false;"><?= $v ?></a>
            <?php } ?>
        </div>
    </div>
    </div>
    <?= $common->page_bar($param['page'], $count, $param['page_size'], '$.loadList(\'%s\')', '1') ?>
</div>
<br />
<table id="tab1" class="layui-table table-auto" style="width: 98%;margin:auto;">
    <tr class="layui-bg-head layui-bg-gray" id="tabHead">
        <th nowrap style="text-align: center;">序号</th>
        <th nowrap style="text-align: center;">
            <span>项目代号</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['project_code'] ?>" sort-field="project_code">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;">进度</th>
        <th style="text-align: center;">
            <span>项目所在地/区域</span>
        </th>
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
                    <td align="center"><a href="javascript:void(0);" class="newColor" onclick="pageOperate('project_detail.php?project_code=<?= $val[project_code] ?>&operate=detail','detail');return false;"><?= $val["project_code"] ?></a></td>
                    <td align="center"><?= $project_schedule_nodes[$val["progress_nodes"]] ?></td>
                    <td align="center"><a href="javascript:void(0);" class="newColor" onclick="pageOperate('region_detail.php?region_id=<?= $val[region_id] ?>&operate=detail','detail');return false;"><?= $val["region"] ?></a></td>
                    <td align="center"><?= $company[$val["user_bu"]] ?></td>
                    <td align="center"><?= $val["deptName"] . ' ' . $val["createUserName"] ?></td>
                    <td align="center"><?= $val["create_time"] ?></td>
                    <td align="center">
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('detail.php?id=<?= $val[id] ?>&operate=detail','detail');return false;">查阅</a>&nbsp;
                        <? if (($userId == $val['create_user_id']&&$val["status"] == 'T') || $systemAdmin) : ?>
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('add.php?id=<?= $val[id] ?>','modify');return false;">修改</a>&nbsp;
                        <?endif;?>
                        <? if (($userId == $val['create_user_id']&&$val["status"] == 'T') || $systemAdmin) : ?>
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('action.php?action=Delete&id=<?= $val[id] ?>','delete');return false;">删除</a>&nbsp;
                        <?endif;?>
                        <? if (($userId == $val['create_user_id']&&($val["status"] == 'Y'||$val["status"] == 'T')) || $systemAdmin) : ?>
                        <a href="javascript:void(0);" class="newColor" onclick="ForbiddenData('action.php?action=Forbidden&id=<?= $val[id] ?>','delete');return false;"><?=$val["status"] == 'T'?'提交':'退回'?></a>&nbsp;
                        <?endif;?>
                    </td>
                    
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>