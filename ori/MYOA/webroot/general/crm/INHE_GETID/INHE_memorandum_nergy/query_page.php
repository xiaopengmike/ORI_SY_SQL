<?php
include_once("/MYOA/webroot/general/hr/UserCommonModel.php");
$model = new UserCommonModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];

$param = $model->getParamToArray();

//$whereStr = " where 1=1 ";
//if (array_key_exists('pm_id', $param) && $param['pm_id'] != "") {
//    $whereStr .= " and k.pm_id like '%" . trim($param['pm_id']) . "%' ";
//}
//if (array_key_exists('create_date', $param) && $param['create_date'] != "") {
//    $whereStr .= " and k.create_date like '%" . trim($param['create_date']) . "%' ";
//}
// if (array_key_exists('type', $param) && $param['type'] != "") {
//     $whereStr .= " and k.lx = '" . $param['type'] . "' ";
// }

// 管理员权限查询范围
//$adminArr = $model->getAdminDataQuery($userId, "F05");
//if ($adminArr['isAdmin']) {
//    // $whereStr .= " and c.user_bu in(" . $adminArr['userBu'] . ") ";
//} else {
//    // $whereStr .= " and k.ZDR='" . $userName . "' ";
//}

//$groupBy = $limit = "";
//$orderBy = " order by k.Create_date desc ";
//$limit = " limit " . ($param['page'] - 1) * $param['page_size'] . "," . $param['page_size'];
//
//$totalQuery = "select k.*,u.user_name approver, GROUP_CONCAT(p.user_name,'(',p.proportion,'%)' ORDER BY p.user_item ASC) pm_users from inhe_pm_proportion k  " .
//    " left join inhe_pm_proportion_item p on p.PM_ID = k.PM_ID " .
//    " left join user u on u.user_id = k.Reviewed_ID " .
//    $whereStr .
//    " group by pm_id "
//    . $orderBy;
//$query = $totalQuery . $limit;
//$query="select * from INHE_CRM_SALES_CONFIRMATION ORDER BY Create_date DESC";
$query="select A.*,user.user_name u_name from inhe_crm_memorandum_nergy A,user WHERE A.USER_ID=user.user_id and A.USER_ID='" . $userId . "' ORDER BY Create_date DESC";
$res = exequery(TD::conn(), $query);
//$res1 = exequery(TD::conn(), $totalQuery);
//$count = mysql_num_rows($res1);
$count = mysql_num_rows($res);
$result = array();
while ($item = mysql_fetch_assoc($res)) {
    // if ($item["LX"] == 1) {
    //     $item["lx_desc"] = "KPI考核";
    // } else if ($item["LX"] == 2) {
    //     $item["lx_desc"] = "研发KPI考核";
    // } else {
    //     $item["lx_desc"] = "业务KPI考核";
    // }
    $result[] = $item;
}
// 释放结果集
mysql_free_result($res);

// $subSql = " select p.* from inhe_pm_proportion_item p where p.PM_ID = '".."' ";

?>

<div style="text-align: right;">
    <?= $model->page_bar($param['page'], $count, $param['page_size'], 'loadList(\'%s\')', '1') ?>
</div>
<table id="tab1" class="layui-table table-auto">
    <tr class="layui-bg-head layui-bg-gray" id="tabHead">
       <th nowrap style="text-align: center;width: 5%;">序号</th>
        <th nowrap style="text-align: center;width: 10%;">备忘录号码</th>
        <th nowrap style="text-align: center;width: 10%;">对应项目编号</th>
        <th nowrap style="text-align: center;width: 10%;">备忘录类型</th>
        <th nowrap style="text-align: center;width: 10%;">创建人</th>
        <th nowrap style="text-align: left;width: 15%;">创建时间</th>
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
                    <td nowrap align="center"><?= $line + ($param['page'] - 1) * $param['page_size'] ?></td>
                    <td align="center"><?= $val["SC_NO"] ?></td>
                    <td align="center"><?= $val['PM_ID'] ?></td>
                    <td align="center" ><?= $val["USER_NAME"] ?></td>
                    <td align="center"><?= $val["u_name"] ?></td>
                    <td align="left"><?= $val["Create_date"] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>