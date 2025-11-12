<?php
include_once("../DataModel.php");  // 公共类
include_once("../CommonMethodModel.php");  // 公共类
$commonModel = new CommonMethodModel();
$dataModel = new DataModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'crm_process_type',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$crm_process_type = array();
foreach ($selectArr['crm_process_type'] as $v) {
    $crm_process_type[$v['paras_value']] = $v['paras_desc'];
}
$param = $dataModel->getParamToArray();

$where = " where m.status = 'U' and m.approve_user = '$userId' and form_id != '' ";
if (array_key_exists("type", $param) && $param['type'] != "") {
    $where .= " and m.type = '" . trim($param['type']) . "' ";
}
if (array_key_exists("form_id", $param) && $param['form_id'] != "") {
    $where .= " and m.form_id = '" . trim($param['form_id']) . "' ";
}

$limit = "";
$orderBy = " order by m.id desc ";
$limit = " limit " . ($param['page'] - 1) * $param['page_size'] . "," . $param['page_size'];

// 排序功能
$laySort = array();
if (array_key_exists('sort_field', $param) && $param['sort_field'] != "" && array_key_exists('sort_way', $param) && $param['sort_way'] != "") {
    $orderBy = " order by m." . $param['sort_field'] . " " . $param['sort_way'];
    $laySort[$param['sort_field']] = $param['sort_way'];
}

$totalQuery = " select m.*, u.user_name prev_writer_name, uu.user_name create_user_name from inhe_flow_opinion m " .
    " left join user u on u.user_id = m.prev_writer " .
    " left join user uu on uu.user_id = m.user_id " .
    $where . $orderBy;
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
// var_export($result[0]);exit;

?>

<div style="text-align: right;width: 98%;margin:auto;">
    <?= $commonModel->page_bar($param['page'], $count, $param['page_size'], '$.loadList(\'%s\')', '1') ?>
</div>
<table id="tab1" class="layui-table table-auto" style="width: 98%;margin:auto;">
    <tr class="layui-bg-head layui-bg-gray" id="tabHead">
        <th nowrap style="text-align: center;">序号</th>
        <th nowrap style="text-align: center;">
            <span>标题</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['title'] ?>" sort-field="title">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;">
            <span>流程类型</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['type'] ?>" sort-field="type">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;">
            <span>流程单号</span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['form_id'] ?>" sort-field="form_id">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;">交办人</th>
        <th nowrap style="text-align: center;">交办时间</th>
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
            foreach ($result as $k => $val) : $line++;
                $mainUrl = str_replace('_change', '', $val['type']); ?>
                <tr class="TableLine<?= $line % 2 == 0 ? 1 : 2 ?>">
                    <input type="hidden" id="id" name="id" value="<?= $val['id'] ?>" />
                    <td align="center" width="3%"><?= $line + ($param['page'] - 1) * $param['page_size'] ?></td>
                    <td align="center"><?= $val["title"] ?></td>
                    <td align="center"><?= $crm_process_type[$val["type"]] ?></td>
                    <td align="center"><?= $val["form_id"] ?></td>
                    <td align="center"><?= $val["prev_writer_name"] ?></td>
                    <td align="center"><?= $val["prev_time"] ?></td>
                    <td nowrap align="center">
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('../../<?= $mainUrl ?>/detail.php?id=<?= $val[form_id] ?>&operate=detail','detail');return false;">查阅</a>&nbsp;
                        <?php if ($val['step'] == '1') : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('../../<?= $mainUrl ?>/modify.php?id=<?= $val[form_id] ?>','modify');return false;">修改</a>&nbsp;
                        <?php else : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('../../<?= $mainUrl ?>/detail.php?id=<?= $val[form_id] ?>&operate=approve&step=<?= $val[step] ?>','detail');return false;">办理</a>&nbsp;
                        <?php endif; ?>
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('process_schedule.php?id=<?= $val[form_id] ?>','smallModel');return false;">查看进度</a>&nbsp;
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>