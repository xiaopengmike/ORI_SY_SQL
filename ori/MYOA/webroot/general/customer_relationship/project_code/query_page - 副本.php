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
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'company',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$company = array();
foreach ($selectArr['company'] as $cc) {
    $company[$cc['paras_value']] = $cc['paras_desc'];
}

$param = $dataModel->getParamToArray();
$where = " where 1=1 ";
$queryStr = "";
if (array_key_exists("temp_code", $param) && $param['temp_code'] != "") {
    $where .= " and k.temp_code like '%" . trim($param['temp_code']) . "%' ";
}
if (array_key_exists("scope_code", $param) && $param['scope_code'] != "") {
    $where .= " and k.scope_code = '" . $param['scope_code'] . "' ";
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
    "  from inhe_temp_code k " .
    " left join user u on u.user_id = k.user_id " .
    " left join department d on d.dept_id = k.dept_id " .
    $where
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
        <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="pageOperate('add.php','smallModel');return false;">新建</button>
    </div>
    <?= $common->page_bar($param['page'], $count, $param['page_size'], '$.loadList(\'%s\')', '1') ?>
</div>
<br />
<table id="tab1" class="layui-table table-auto" style="width: 98%;margin:auto;">
    <tr class="layui-bg-head layui-bg-gray" id="tabHead">
        <th nowrap style="text-align: center;">序号</th>
        <th nowrap style="text-align: center;">
            <span>项目代号</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['temp_code'] ?>" sort-field="temp_code">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;">项目名称</th>
        <th style="text-align: center;">
            <span>项目所在地/国家代码</span>
        </th>
        <th nowrap style="text-align: center;">归属公司</th>
        <th nowrap style="text-align: center;">业务负责人</th>
        <th nowrap style="text-align: center;">创建时间</th>
        <!-- <th nowrap style="text-align: center;">操作</th> -->
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
                    <td align="center"><?= $val["temp_code"] ?></td>
                    <td align="center"><?= $val["project_name"] ?></td>
                    <td align="center"><?= $val["location"] ?></td>
                    <td align="center"><?= $company[$val["company"]] ?></td>
                    <td align="center"><?= $val["deptName"] . ' ' . $val["createUserName"] ?></td>
                    <td align="center"><?= $val["create_time"] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>