<?php
include_once("/MYOA/webroot/general/hr/UserCommonModel.php");
include_once("../common/SystemEnum.php");
$model = new UserCommonModel();

$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$systemId = "control_library";
$data = $model->getAdminDataQuery($userId, $systemId);
$isAdmin = $data['isAdmin'];

$param = $model->getParamToArray();
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
    $whereStr .= " and k.create_time like '%" . $param['create_time'] . "%' ";
}
if (array_key_exists('status', $param) && $param['status'] != "") {
    $whereStr .= " and k.status = '" . $param['status'] . "' ";
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

$totalQuery = "select k.*,u.user_name createUserName, uu.user_name approveUserName, d.dept_name deptName " .
    "  from inhe_contract_protocol k " .
    " left join user u on u.user_id = k.create_user " .
    " left join user uu on uu.user_id = k.approve_user " .
    " left join department d on d.dept_id = k.dept_id " .
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

if ($param["export"]) {
    include_once("/MYOA/webroot/common/excel/export/export.php");
    $exportModel = new ExportModel();

    $titleArr = array(
        // "id" => "序号",
        "type" => "分类",
        "chinese" => "中文",
        "english" => "英语",
        "french" => "法语",
        "spanish" => "西语"
    );
    $workSheetName = "名词翻译库数据";

    $exportModel->exportExcel($result, $titleArr, $workSheetName);
}

?>

<div style="text-align: right;width: 98%;margin:auto;">
    <?php if ($isAdmin) : ?>
        <div style="float: left;">
            <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="pageOperate('add.php','add');return false;">新建</button>
            <!-- <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="changeCustomerInfo();return false;">变更</button> -->
            <!-- <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="importData();return false;">导入</button> -->
        </div>
    <?php endif; ?>
    <?= $model->page_bar($param['page'], $count, $param['page_size'], 'loadList(\'%s\')', '1') ?>
</div>
<br />
<table id="tab1" class="layui-table table-auto" style="width: 98%;margin:auto;">
    <tr class="layui-bg-head layui-bg-gray" id="tabHead">
        <th nowrap style="text-align: center; width: 3%"><input type="checkbox" id="check-all" name="check-all">全选</th>
        <th nowrap style="text-align: center;">序号</th>
        <th nowrap style="text-align: center;">
            <span>流程单号</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['form_id'] ?>" sort-field="form_id">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;">
            <span>项目代号</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['project_code'] ?>" sort-field="name">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;">
            <span>项目编号</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['project_num'] ?>" sort-field="name">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="sortList(this);"></i>
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
                    <td align="center"><input type="checkbox" id="<?= $val["id"] ?>" name="cb_list" status='<?= $val["status"] ?>' <?php if (in_array($val['id'], $checkedArr)) { ?>checked<?php } ?>></td>
                    <td nowrap align="center" width="3%"><?= $line + ($param['page'] - 1) * $param['page_size'] ?></td>
                    <td align="center"><?= $val["form_id"] ?></td>
                    <td align="center"><?= $val["project_code"] ?></td>
                    <td align="center"><?= $val["project_num"] ?></td>
                    <td align="center"><?= $val["deptName"] . ' ' . $val["createUserName"] ?></td>
                    <td align="center"><?= $val["create_time"] ?></td>
                    <td align="center">
                        <font class="<?= SystemEnum::$STATUS_CSS[$val["status"]] ?>"><?= SystemEnum::$STATUS[$val["status"]] ?></font>
                    </td>
                    <td align="center" nowrap>
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('detail.php?id=<?= $val[form_id] ?>&operate=detail','detail');return false;">查阅</a>&nbsp;
                        <?php if ($val["status"] != 'T') : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('process_schedule.php?id=<?= $val[form_id] ?>','smallModel');return false;">查看进度</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['create_user'] || $isAdmin) && $val["status"] == 'T' || $val["status"] == 'N') : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('modify.php?id=<?= $val[form_id] ?>','modify');return false;">修改</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['approve_user']) && ($val["status"] == 'P')) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('detail.php?id=<?= $val[form_id] ?>&operate=approve','detail');return false;">审核</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['create_user'] || $isAdmin) && ($val["status"] == 'T' || $val["status"] == 'N')) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('action.php?action=Delete&id=<?= $val[id] ?>','delete');return false;">删除</a>&nbsp;
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>