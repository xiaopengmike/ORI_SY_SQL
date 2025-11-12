<?php
include_once("../../inc/auth.php");
include_once("../../inc/utility_all.php");
include_once("../../inc/utility_org.php");
include_once("lang.php");
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['客户信息登记变更'][$_COOKIE['LANG']]);
include_once("../../inc/header.inc.php");
include_once("../../common/Common.php");
include_once("../../common/DataModel.php");
$dataModel = new DataModel();
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
?>
<script type="text/javascript" src="../../inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/attach.js"></script>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></SCRIPT>
<script language="Javascript" type="text/javascript" src="../../customer_relationship/list.js"></script>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">

<script>
    $(function() {
        layui.use(['form', 'layer', 'laydate'], function() {
            var layer = layui.layer,
                form = layui.form;
            $.loadList('1');
            form.render();
        });
    });
</script>

<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="type" name="type" value="customer_data_change" />
        <input type="hidden" id="sort_way" name="sort_way" value="" />
        <input type="hidden" id="sort_field" name="sort_field" value="" /> <!-- 排序字段 -->
        <input type="hidden" id="page_size" name="page_size" value="10" />
        <input type="hidden" id="checkedIds" name="checkedIds" value="" /> <!-- 用于实现跨页选择数据 -->
        <table style="margin: 1%">
            <tr>
                <td align="right"><?=$LG_CUSTOMER_DATA['登记单号'][$_COOKIE['LANG']]?></td>
                <td>
                    <input type="text" name="form_id" id="form_id" class="layui-input" placeholder="<?=$LG_CUSTOMER_DATA['请输入登记单号'][$_COOKIE['LANG']]?>">
                </td>
                <td align="right"><?=$LG_CUSTOMER_DATA['客户名称'][$_COOKIE['LANG']]?></td>
                <td>
                    <input type="text" name="customer_name" id="customer_name" class="layui-input" placeholder="<?=$LG_CUSTOMER_DATA['请输入客户名称'][$_COOKIE['LANG']]?>">
                </td>
                <td nowrap align="right"><?=$LG_CUSTOMER_DATA['公司'][$_COOKIE['LANG']]?></td>
				<td>
					<div class="layui-input-inline" style="width: 150px;">
						<select id="user_bu" name="user_bu" lay-verify="" lay-search>
							<option value=''><?=$LG_CUSTOMER_DATA['请选择'][$_COOKIE['LANG']]?></option>
                            <?php foreach ($crm_process_sort as $company => $item) { ?>
                                <option value='<?=$item[paras_value]?>'><?= $_COOKIE['LANG']=="EN"?$company:$item['paras_desc'] ?></option>
                            <?php } ?>
						</select>
					</div>
				</td>
                <td  align="right"><?=$LG_CUSTOMER_DATA['状态'][$_COOKIE['LANG']]?></td>
                <td>
                    <div class="layui-input-inline">
                        <select id="status" name="status">
                            <option value=''><?=$LG_CUSTOMER_DATA['请选择'][$_COOKIE['LANG']]?></option>
                            <option value='T'><?=$LG_CUSTOMER_DATA['待提交'][$_COOKIE['LANG']]?></option>
                            <option value='P'><?=$LG_CUSTOMER_DATA['审核中'][$_COOKIE['LANG']]?></option>
                            <option value='R'><?=$LG_CUSTOMER_DATA['备案中'][$_COOKIE['LANG']]?></option>
                            <option value='Y'><?=$LG_CUSTOMER_DATA['已审核'][$_COOKIE['LANG']]?></option>
                            <option value='N'><?=$LG_CUSTOMER_DATA['退回'][$_COOKIE['LANG']]?></option>
                        </select>
                    </div>
                </td>
                <td>
                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.loadList('1');return false;" style="margin-left: 10px;"><?=$LG_CUSTOMER_DATA['查询'][$_COOKIE['LANG']]?></button>
                    <!-- <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="exportData();return false;">导出</button> -->
                </td>
            </tr>
        </table>
    </form>
    <div id="tableList" style="width:100%;text-align:center;"></div>
</body>

</html>