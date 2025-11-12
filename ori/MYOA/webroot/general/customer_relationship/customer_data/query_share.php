<?php
include_once("../common/CommonMethodModel.php");
include_once("../common/SystemEnum.php");
include_once("../common/DataModel.php");
include_once("../common/UserModel.php");
include_once("lang.php");
$model = new CommonMethodModel();
$dataModel = new DataModel();
$userModel = new UserModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = "customer_data";
$data = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
$systemAdmin = $data['systemAdmin'];
$userBu = $userModel->getUserBu($deptId);

$param = $dataModel->getParamToArray();

$whereStr = " where 1=1 ";
$queryStr = "";
if (array_key_exists('customer_name', $param) && $param['customer_name'] != "") {
    $whereStr .= " and k.customer_name like '%" . trim($param['customer_name']) . "%' ";
}
if (array_key_exists('status', $param) && $param['status'] != "") {
    $whereStr .= " and k.status = '" . $param['status'] . "' ";
}


// 数据权限范围
if ($isAdmin) {
    $whereStr .= " and (d.user_id = '" . $userId . "' ";
    if (!empty($data['userBu'])) {
        $whereStr .= " or d.user_bu in (" . $data['userBu'] . ") ";
    }
    if (!empty($data['deptIds'])) {
        $whereStr .= " or d.organ_id in(" . $data['deptIds'] . ") ";
    }
    if (!empty($data['userIds'])) {
        $whereStr .= " or d.user_id in(" . $data['userIds'] . ") ";
    }
    
} else {
    // 查询本人填报数据
    $whereStr .= " and (d.user_id = '" . $userId . "' ";
}
$whereStr .= ") ";


$groupBy = $limit = "";
$orderBy = " order by MAX(k.id) desc ";
$groupBy = " group by k.customer_id ";
$limit = " limit " . ($param['page'] - 1) * $param['page_size'] . "," . $param['page_size'];

// 排序功能
$laySort = array();
if (array_key_exists('sort_field', $param) && $param['sort_field'] != "" && array_key_exists('sort_way', $param) && $param['sort_way'] != "") {
    $orderBy = " order by " . $param['sort_field'] . " " . $param['sort_way'];
    $laySort[$param['sort_field']] = $param['sort_way'];
}

$totalQuery = "select k.customer_id, k.customer_name,GROUP_CONCAT(DISTINCT u.user_name) userNameList,uu.user_name createUserName from inhe_customer_share k " .
    " left join inhe_customer_data d on k.customer_id = d.id " .
    " left join user u on k.user_ids = u.user_id " .
    " left join user uu on d.user_id = uu.user_id " .
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
<table id="tab1" class="layui-table table-auto" style="width: 98%;margin:auto;">
    <tr class="layui-bg-head layui-bg-gray" id="tabHead">
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['序号'][$_COOKIE['LANG']]?></th>
        <th style="text-align: center;">
            <span><?=$LG_CUSTOMER_DATA['客户名称'][$_COOKIE['LANG']]?></span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['d.customer_name'] ?>" sort-field="d.customer_name">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['共享业务人员'][$_COOKIE['LANG']]?></th>
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['登记人'][$_COOKIE['LANG']]?></th>
        <!-- <th nowrap style="text-align: center;">办理状态</th> -->
        <!-- <th nowrap style="text-align: center;">操作</th> -->
    </tr>
    <tbody id="tabBody" name="tabBody" class="layui-bg-body">
        <?php if (!isset($result) || $count == 0) : ?>
            <tr class="TableData">
                <td colspan="4">
                    <div style="text-align: center;"><?=$LG_CUSTOMER_DATA['暂无数据'][$_COOKIE['LANG']]?></div>
                </td>
            </tr>
        <?php else : ?>
            <?php $line = 0;
            foreach ($result as $k => $val) : $line++; ?>
                <tr class="TableLine<?= $line % 2 == 0 ? 1 : 2 ?>">
                    <td align="center" width="3%"><?= $line + ($param['page'] - 1) * $param['page_size'] ?></td>
                    <td align="center"><?= $val["customer_name"] ?></td>
                    <td align="center"><?= $val["userNameList"] ?></td>
                    <td align="center"><?= $val["createUserName"] ?></td>
                    <!-- <td align="center"><font class="<?= SystemEnum::$STATUS_CSS[$val["status"]] ?>"><?= SystemEnum::$STATUS[$val["status"]] ?></font></td> -->
                    <!-- <td align="center" nowrap>
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('modify.php?id=<?= $val[form_id] ?>','modify');return false;">修改</a>&nbsp;
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('action.php?action=Delete&id=<?= $val[form_id] ?>','delete');return false;">删除</a>&nbsp;
                    </td> -->
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>