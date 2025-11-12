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
$systemId = "customer_share";
$data = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];
$systemAdmin = $data['systemAdmin'];
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

$param = $dataModel->getParamToArray();
$checkedIds = $param['checkedIds'];
$checkedArr = explode(',', $checkedIds);

$whereStr = " where 1=1 ";
$queryStr = "";
if (array_key_exists('form_id', $param) && $param['form_id'] != "") {
    $whereStr .= " and k.form_id like '%" . trim($param['form_id']) . "%' ";
}
if (array_key_exists('customer_name', $param) && $param['customer_name'] != "") {
    $whereStr .= " and c.customer_name like '%" . trim($param['customer_name']) . "%' ";
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
if (array_key_exists('type', $param) && $param['type'] != "") {
    $whereStr .= " and k.type = '" . $param['type'] . "' ";
}
if (array_key_exists('user_bu', $param) && $param['user_bu'] != "") {
    $whereStr .= " and k.user_bu = '" . $param['user_bu'] . "' ";
}
if (array_key_exists('flow_date', $param) && $param['flow_date'] != "") {
    $flow_date=date('Y-m-d', strtotime ("-".$param['flow_date']." day", time()));
    $childSql="select * from inhe_customer_data_flow where form_id=k.form_id GROUP BY form_id  having max(flow_date)<'$flow_date'";
    $whereStr .= " and EXISTS($childSql)";
}
$whereStr2="";
$whereStr3=" ";
if (array_key_exists('user_byid', $param) && $param['user_byid'] != "") {
    $sql = " select * from user where user_byid='$param[user_byid]' ";
    $res = exequery(TD::conn(), $sql);
    $userInfo = mysql_fetch_assoc($res);
    $whereStr2 .= " and k.user_id = '" . trim($userInfo['USER_ID']) . "' ";
    $whereStr3.="  and user_id = '" . trim($userInfo['USER_ID']) . "' ";
}
//时间范围
if (array_key_exists('starting_time', $param) && $param['starting_time'] != "") {
    $whereStr2 .= " and k.write_time >= '" . $param['starting_time'] . "' ";

    $whereStr3 .= " and flow_date >= '" . $param['flow_date'] . "' ";
}
if (array_key_exists('ending_time', $param) && $param['ending_time'] != "") {
    $end_day=date('Y-m-d', strtotime ("+1 day", strtotime($param['ending_time'])));
    $whereStr2 .= " and k.write_time < '" . $end_day . "' ";
    $whereStr3 .= " and flow_date < '" . $end_day . "' ";
}

if (array_key_exists('order_will', $param) && $param['order_will'] != "") {
    $whereStr3 .= " and order_will = '" . $param['order_will'] . "' ";
}

if(array_key_exists('data_flow', $param) && $param['data_flow'] != ""){
    $flowSql="select * from inhe_customer_data_flow  WHERE form_id=k.form_id";
    $flowSql.=$whereStr3;
    $whereStr .= " and EXISTS($flowSql)";
}else{
    $whereStr .=$whereStr2;
}





// 数据权限范围
if ($isAdmin) {
    $whereStr .= " and (k.user_id = '" . $userId . "' ";
    if (!empty($data['userBu'])) {
        $whereStr .= " or k.user_bu in (" . $data['userBu'] . ") ";
    }
    if (!empty($data['deptIds'])) {
        $whereStr .= " or k.organ_id in(" . $data['deptIds'] . ") ";
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
$limit = " limit " . ($param['page'] - 1) * $param['page_size'] . "," . $param['page_size'];

// 排序功能
$laySort = array();
if (array_key_exists('sort_field', $param) && $param['sort_field'] != "" && array_key_exists('sort_way', $param) && $param['sort_way'] != "") {
    $orderBy = " order by " . $param['sort_field'] . " " . $param['sort_way'];
    $laySort[$param['sort_field']] = $param['sort_way'];
}

$totalQuery = "select k.*,o.approve_user,o.step,u.user_name createUserName, u.user_name createUserName, d.dept_name deptName, " .
    " tc.zh_name continentName, yc.zh_name countryName from inhe_customer_share_main k " .
    " join inhe_customer_data c on c.id = k.customer_id " .
    " left join user u on u.user_id = k.user_id " .
    " left join department d on d.dept_id = k.organ_id " .
    " left join continent_code tc on tc.iso_two = c.continent " .
    " left join country_code yc on yc.iso_three = c.country and yc.continent=c.continent " .
    " left join inhe_flow_opinion o on o.form_id=k.form_id and o.status='U' and o.approve_user='$userId' ".
    $whereStr
    . $orderBy;
$query = $totalQuery . $limit;
//echo $query;
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
        "form_id" => "登记单号",
        "customer_name" => "客户名称",
        "continentName" => "大洲",
        "countryName" => "国家",
        "createUserName" => "制单人",
        "create_time" => "制单时间"
    );
    $workSheetName = "客户信息登记数据";
    $exportModel->exportExcel($result, $titleArr, $workSheetName);
}
?>
<script>
    $(function() {
        layui.use(['form', 'layer', 'laydate'], function() {
            var layer = layui.layer,
                form = layui.form;
            form.on('select(change_lang)', function(data){
                window.location = "change_lang.php?LANG="+data.value;
			});

            form.render();
        });
    });
    function change_lang(data){
        window.location = "change_lang.php?LANG="+data.value;
    }

</script>
<div style="text-align: right;width: 98%;margin:auto;">
    <?php if (strpos($type, '_change') == false) { ?>
        <div style="float: left;">
        <!-- <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="pageOperate('add.php','add');return false;"><?=$LG_CUSTOMER_DATA['新建'][$_COOKIE['LANG']]?></button> -->
        <div class="dropdown">
                <button class="layui-btn layui-btn-oa layui-btn-sm drop-btn"><?=$LG_CUSTOMER_DATA['新建'][$_COOKIE['LANG']]?></button>
                
                <div class="dropdown-content" align="center">
                    <?php foreach ($crm_process_sort as $company => $arr) { ?>
                        <a href="javascript:void(0);" class="newColor" onclick="$.addByProcessSort('<?= $company ?>','<?= $arr['paras_desc'] ?>','<?= $crm_process_type[$systemId] ?>','<?= $userBu ?>');return false;"><?= $_COOKIE['LANG']=="EN"?$company:$arr['paras_desc'] ?></a>
                    <?php } ?>
                </div>
            </div>
            <select class="SmallInput" id="change_lang" onchange="change_lang(this)" style="width: 80px;height: 29px;font-size: 12px;text-align: center;" lay-filter="change_lang">
                <?
                    if($LANG=="CH")
                    {
                        echo "<option value='CH' selected>Chinese</option>";
                        echo "<option value='EN'>English</option>";
                    }
                    else
                    {
                        echo "<option value='CH'>中文</option>";
                        echo "<option value='EN' selected>英文</option>";
                    }
                ?>
            </select>

            <input type="hidden" id="LANG" value="<?=$_COOKIE[LANG]?>" />
        </div>
    <?php } ?>
    <?= $model->page_bar($param['page'], $count, $param['page_size'], '$.loadList(\'%s\')', '1') ?>
</div>
<br />
<table id="tab1" class="layui-table table-auto" style="width: 98%;margin:auto;">
    <tr class="layui-bg-head layui-bg-gray" id="tabHead">
        <th nowrap style="text-align: center; width: 3%">
        <?=$LG_CUSTOMER_DATA['选择'][$_COOKIE['LANG']]?>
        </th>
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['序号'][$_COOKIE['LANG']]?></th>
        <th nowrap style="text-align: center;">
            <span><?=$LG_CUSTOMER_DATA['登记单号'][$_COOKIE['LANG']]?></span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['form_id'] ?>" sort-field="form_id">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <th style="text-align: center;">
            <span><?=$LG_CUSTOMER_DATA['客户名称'][$_COOKIE['LANG']]?></span>
            <span class="layui-table-sort layui-inline" lay-sort="<?= $laySort['k.customer_name'] ?>" sort-field="k.customer_name">
                <i class="layui-edge layui-table-sort-asc" title="升序" sort-way="asc" onclick="$.sortList(this);"></i>
                <i class="layui-edge layui-table-sort-desc" title="降序" sort-way="desc" onclick="$.sortList(this);"></i>
            </span>
        </th>
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['大洲'][$_COOKIE['LANG']]?></th>
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['国家'][$_COOKIE['LANG']]?></th>
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['制单人'][$_COOKIE['LANG']]?></th>
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['制单时间'][$_COOKIE['LANG']]?></th>
       
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['办理状态'][$_COOKIE['LANG']]?></th>
        <th nowrap style="text-align: center;"><?=$LG_CUSTOMER_DATA['操作'][$_COOKIE['LANG']]?></th>
    </tr>
    <tbody id="tabBody" name="tabBody" class="layui-bg-body">
        <?php if (!isset($result) || $count == 0) : ?>
            <tr class="TableData">
                <td colspan="10">
                    <div style="text-align: center;"><?=$LG_CUSTOMER_DATA['暂无数据'][$_COOKIE['LANG']]?></div>
                </td>
            </tr>
        <?php else : ?>
            <?php $line = 0;
            foreach ($result as $k => $val) : $line++; ?>
                <tr class="TableLine<?= $line % 2 == 0 ? 1 : 2 ?>">
                    <td align="center"><input type="checkbox" id="<?= $val["form_id"] ?>" name="cb_list" title='<?= $val["status"] ?>' <?php if (in_array($val['id'], $checkedArr)) { ?>checked<?php } ?>></td>
                    <td align="center" width="3%"><?= $line + ($param['page'] - 1) * $param['page_size'] ?></td>
                    <td align="center"><?= $val["form_id"] ?></td>
                    <td align="center"><?= $val["customer_name"] ?></td>
                    <td align="center"><?= $val["continentName"] == '' ? $val["continent"] : $val["continentName"] ?></td>
                    <td align="center"><?= $val["countryName"] == '' ? $val["country"] : $val["countryName"] ?></td>
                    <td align="center"><?= $val["deptName"] . ' ' . $val["createUserName"] ?></td>
                    <td align="center"><?= $val["write_time"] ?></td>
                    <td align="center">
                        <font class="<?= SystemEnum::$STATUS_CSS[$val["status"]] ?>"><?=$LG_CUSTOMER_DATA[SystemEnum::$STATUS[$val["status"]]][$_COOKIE['LANG']]?></font>
                    </td>
                    <td align="center" nowrap>
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('detail.php?id=<?= $val[form_id] ?>','detail');return false;"><?=$LG_CUSTOMER_DATA['查阅'][$_COOKIE['LANG']]?></a>&nbsp;
                        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('process_schedule.php?id=<?= $val[form_id] ?>&type=<?= $val[type] ?>','smallModel');return false;"><?=$LG_CUSTOMER_DATA['查看进度'][$_COOKIE['LANG']]?></a>&nbsp;
                        <?php if ($userId == $val['user_id'] && ($val["status"] == 'T' || $val["status"] == 'N')) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('modify.php?id=<?= $val[form_id] ?>','modify');return false;"><?=$LG_CUSTOMER_DATA['修改'][$_COOKIE['LANG']]?></a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['approve_user']) && ($val["status"] == 'P'||$val["status"] == 'R')) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('detail.php?id=<?= $val[form_id] ?>&operate=approve&step=<?=$val[step]?>','detail');return false;"><?=$LG_CUSTOMER_DATA['办理'][$_COOKIE['LANG']]?></a>&nbsp;
                        <?php endif; ?>
                        <?php if ($userId == $val['user_id'] && ($val["status"] == 'T' || $val["status"] == 'N')) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('action.php?action=Delete&id=<?= $val[form_id] ?>','delete');return false;"><?=$LG_CUSTOMER_DATA['删除'][$_COOKIE['LANG']]?></a>&nbsp;
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>