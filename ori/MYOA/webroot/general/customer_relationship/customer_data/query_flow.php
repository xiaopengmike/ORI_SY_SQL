<?php
include_once("../common/CommonMethodModel.php");
include_once("../common/SystemEnum.php");
include_once("../common/DataModel.php");
include_once("../common/UserModel.php");
include_once("lang.php");
$model = new CommonMethodModel();
$dataModel = new DataModel();
$userModel = new UserModel();

$param = $dataModel->getParamToArray();

$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = "customer_data";
$data = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
$systemAdmin = $data['systemAdmin'];

$whereStr = " where 1=1 ";
$queryStr = "";
if (array_key_exists('customer_name', $param) && $param['customer_name'] != "") {
    $whereStr .= " and d.customer_name like '%" . trim($param['customer_name']) . "%' ";
}
if (array_key_exists('user_name', $param) && $param['user_name'] != "") {
    $whereStr .= " and k.user_name like '%" . trim($param['user_name']) . "%' ";
}
if (array_key_exists('user_byid', $param) && $param['user_byid'] != "") {
    $whereStr .= " and u.user_byid = '" . trim($param['user_byid']) . "' ";
}
if (array_key_exists('order_will', $param) && $param['order_will'] != "") {
    $whereStr .= " and k.order_will = '" . $param['order_will'] . "' ";
}
//时间范围
if (array_key_exists('starting_time', $param) && $param['starting_time'] != "") {
    $whereStr .= " and k.flow_date >= '" . $param['starting_time'] . "' ";
}
if (array_key_exists('ending_time', $param) && $param['ending_time'] != "") {
    $end_day=date('Y-m-d', strtotime ("+1 day", strtotime($param['ending_time'])));
    $whereStr .= " and k.flow_date < '" . $end_day . "' ";
}

// 数据权限范围
if ($isAdmin) {
    $whereStr .= " and (k.user_id = '" . $userId . "' ";
    if (!empty($data['userBu'])) {
        $whereStr .= " or k.user_bu in (" . $data['userBu'] . ") ";
    }
    if (!empty($data['deptIds'])) {
        $whereStr .= " or k.dept_id in(" . $data['deptIds'] . ") ";
    }
    if (!empty($data['userIds'])) {
        $whereStr .= " or k.user_id in(" . $data['userIds'] . ") ";
    }
    
} else {
    // 查询本人填报数据
    $whereStr .= " and (k.user_id = '" . $userId . "' ";
}
$whereStr .= ") ";

$groupBy = $limit = "";
$orderBy = " order by k.id desc ";
$groupBy = " ";
$limit = " limit " . ($param['page'] - 1) * $param['page_size'] . "," . $param['page_size'];

// 排序功能
$laySort = array();
if (array_key_exists('sort_field', $param) && $param['sort_field'] != "" && array_key_exists('sort_way', $param) && $param['sort_way'] != "") {
    $orderBy = " order by " . $param['sort_field'] . " " . $param['sort_way'];
    $laySort[$param['sort_field']] = $param['sort_way'];
}

$totalQuery = "select d.customer_name,k.* from inhe_customer_data_flow k " .
    " left join inhe_customer_data d on k.form_id = d.form_id ".
    " left join user u on u.user_id = k.user_id " .
    $whereStr . $groupBy . $orderBy;
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
    <?= $model->page_bar($param['page'], $count, $param['page_size'], 'loadList(\'%s\')', '1') ?>
</div>
<br />
<table id="tab1" class="table layui-table table-auto" style="width: 98%;margin:auto;">
    <tr class="layui-bg-head layui-bg-gray" id="tabHead">
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['序号'][$_COOKIE['LANG']]?></th>
        <th style="text-align: center;">
            <span><?=$LG_CUSTOMER_DATA['客户名称'][$_COOKIE['LANG']]?></span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['d.customer_name'] ?>" sort-field="d.customer_name">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="sortList(this);"></i>
            </span>
        </th>
        <th  style="text-align: center;"><?=$LG_CUSTOMER_DATA['跟进人员'][$_COOKIE['LANG']]?></th>
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['跟进日期'][$_COOKIE['LANG']]?></th>
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['联系人'][$_COOKIE['LANG']]?></th>
        <th  style="text-align: center;"><?=$LG_CUSTOMER_DATA['是否有意向采购产品'][$_COOKIE['LANG']]?></th>
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['总数量'][$_COOKIE['LANG']]?></th>
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['创建时间'][$_COOKIE['LANG']]?></th>
        <th nowrap style="text-align: center;" width="30%"><?=$LG_CUSTOMER_DATA['商谈事项'][$_COOKIE['LANG']]?></th>
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['操作'][$_COOKIE['LANG']]?></th>
        <!-- <th nowrap style="text-align: center;">办理状态</th> -->
        <!-- <th nowrap style="text-align: center;">操作</th> -->
    </tr>
    <tbody id="tabBody" name="tabBody" class="layui-bg-body">
        <?php if (!isset($result) || $count == 0) : ?>
            <tr class="TableData">
                <td colspan="9">
                    <div style="text-align: center;"><?=$LG_CUSTOMER_DATA['暂无数据'][$_COOKIE['LANG']]?></div>
                </td>
            </tr>
        <?php else : ?>
            <?php $line = 0;
            foreach ($result as $k => $val) : $line++; ?>
                <tr class="TableLine<?= $line % 2 == 0 ? 1 : 2 ?>">
                    <td align="center" width="3%"><?= $line + ($param['page'] - 1) * $param['page_size'] ?></td>
                    <td align="center"><?= $val["customer_name"] ?></td>
                    <td align="center"><?= $val["user_name"] ?></td>
                    <td align="center"><?= $val["flow_date"] ?></td>
                    <td align="center"><?= $val["contract_name"] ?></td>
                    <td align="center"><?= $val["order_will"]=="Y"?$LG_CUSTOMER_DATA['是'][$_COOKIE['LANG']]:$LG_CUSTOMER_DATA['否'][$_COOKIE['LANG']] ?></td>
                    <td align="center"><?= $val["order_num"] ?></td>
                    <td align="center"><?= $val["create_time"] ?></td>
                    <td nowrap   align="left" ><p id="remarks" class="content" title="<?= $val["remark"] ?>"><?= $val["remark"] ?></p></td>
                    <!-- <td align="center"><font class="<?= SystemEnum::$STATUS_CSS[$val["status"]] ?>"><?= SystemEnum::$STATUS[$val["status"]] ?></font></td> -->
                    <!-- <td align="center" nowrap>
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('modify.php?id=<?= $val[form_id] ?>','modify');return false;">修改</a>&nbsp;
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('action.php?action=Delete&id=<?= $val[form_id] ?>','delete');return false;">删除</a>&nbsp;
                    </td> -->
                    <td align="center" nowrap>
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('flow_detail.php?id=<?= $val[id] ?>','detail');return false;"><?=$LG_CUSTOMER_DATA['查阅'][$_COOKIE['LANG']]?></a>&nbsp;
                        <?php if ($userId=="admin"||$userId=="INHE-1008")  : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('flow_modify.php?id=<?= $val[id] ?>','modify');return false;"><?=$LG_CUSTOMER_DATA['修改'][$_COOKIE['LANG']]?></a>&nbsp;
                        <?php endif; ?>
                        <?php if ($userId=="admin"||$userId=="INHE-1008") : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('flow_delete.php&id=<?= $val[id] ?>','delete');return false;"><?=$LG_CUSTOMER_DATA['删除'][$_COOKIE['LANG']]?></a>&nbsp;
                        <?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>