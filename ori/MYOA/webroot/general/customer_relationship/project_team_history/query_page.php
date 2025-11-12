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

$data = $userModel->getAdminDataQuery($userId, $deptId, "project_team");
$isAdmin = $data['isAdmin'];
$systemAdmin = $data['systemAdmin'];
$user_ids = $data['userIds'];
$dept_ids = $data['deptIds'];
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

$operateType['add']="新增";
$operateType['update']="修改";
$operateType['delete']="删除";

$param = $dataModel->getParamToArray();
$where = " where 1=1 ";
$queryStr = "";

if($isAdmin){
    $where .= " and ((business_owner='$userId' or commerce_owner='$userId' or technical_support_owner='$userId' 
    or find_in_set('$userId',business) or find_in_set('$userId',commerce) or find_in_set('$userId',technical_support)) ";
    if (!empty($data['userBu'])) {
        $where .= " or k.user_bu in (" . $data['userBu'] . ") ";
    }
    if($user_ids){
        $where .=" or find_in_set(team_name," . $user_ids . ")";
    }
    if(empty($data['userBu'])&&empty($user_ids)){
        $where .=" or 1=1 ";
    }
    
    $where .= ")";
}else{
    $where .= " and (business_owner='$userId' or commerce_owner='$userId' or technical_support_owner='$userId' 
    or find_in_set('$userId',business) or find_in_set('$userId',commerce) or find_in_set('$userId',technical_support)) ";
}

if (array_key_exists("team_name", $param) && $param['team_name'] != "") {
    $where .= " and k.team_name like '%" . trim($param['team_name']) . "%' ";
}
if (array_key_exists("form_id", $param) && $param['form_id'] != "") {
    $where .= " and k.form_id like '%" . trim($param['form_id']) . "%' ";
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
    "  from inhe_project_team_history k " .
    " left join user u on u.user_id = k.update_user_id " .
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
    <?= $common->page_bar($param['page'], $count, $param['page_size'], '$.loadList(\'%s\')', '1') ?>
</div>
<br />
<table id="tab1" class="layui-table table-auto" style="width: 98%;margin:auto;">
    <tr class="layui-bg-head layui-bg-gray" id="tabHead">
        <th nowrap style="text-align: center;">序号</th>
        <th nowrap style="text-align: center;">单号</th>
        <th nowrap style="text-align: center;">公司</th>
        <th nowrap style="text-align: center;">
            <span>项目组</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['team_name'] ?>" sort-field="team_name">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: left;">负责区域</th>
        <th nowrap style="text-align: left;">业务负责人</th>
        <th nowrap style="text-align: left;">商务负责人</th>
        <th nowrap style="text-align: left;">技术支持负责人</th>
        <th nowrap style="text-align: left;">业务团队</th>
        <th nowrap style="text-align: left;">商务团队</th>
        <th nowrap style="text-align: left;">技术支持团队</th>
        <th nowrap style="text-align: left;">操作人员</th>
        <th nowrap style="text-align: left;">操作时间</th>
        <th nowrap style="text-align: left;">操作类型</th>
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
                    <td align="center"><?= $val["form_id"] ?></td>
                    <td align="center"><?= $company[$val["user_bu"]] ?></td>
                    <td align="center"><?= $val["team_name"] ?></td>
                    <td align="center"><?= $val["region"] ?></td>
                    <td align="center"><?= $val[business_owner_name] ?></td>
                    <td align="center"><?= $val[commerce_owner_name] ?></td>
                    <td align="center"><?= $val[technical_support_owner_name] ?></td>
                    <td align="center"><?= $val[business_name] ?></td>
                    <td align="center"><?= $val[commerce_name] ?></td>
                    <td align="center"><?= $val[technical_support_name] ?></td>
                    <td align="center"><?= $val[createUserName] ?></td>
                    <td align="center"><?= $val[update_time] ?></td>
                    <td align="center">
                      <?=$operateType[$val[type]]?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>