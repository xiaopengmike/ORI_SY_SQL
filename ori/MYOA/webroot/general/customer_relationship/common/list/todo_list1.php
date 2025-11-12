<?php
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("待办列表");
include_once("inc/header.inc.php");
include_once("common/Common.php");
require_once '../DataModel.php';
$model = new DataModel();
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'crm_process_type',
);
$selectArr = $model->getCommonParam($selectParam);
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/attach.js"></script>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="../../list.js"></SCRIPT>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
<script>
    $(function() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;
            $.loadList('1');
            form.render();
        });
    });
</script>

<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="sort_way" name="sort_way" value="" />
        <input type="hidden" id="sort_field" name="sort_field" value="" />
        <input type="hidden" id="page_size" name="page_size" value="10" />
        <table style="margin: 1%">
            <tr>
                <td align="right">流程类型</td>
                <td>
                    <div class="layui-inline">
                        <div class="layui-input-inline" style="width: auto;">
                            <select id="type" name="type" lay-search>
                                <option value="">请选择</option>
                                <?php foreach ($selectArr['crm_process_type'] as $val) : ?>
                                    <option value="<?= $val['paras_value'] ?>"><?= $val['paras_desc'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </td>
                <td align="right">流程单号</td>
                <td>
                    <input class="layui-input" name="form_id" id="form_id" autocomplete="off">
                </td>
                <td>
                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.loadList('1');return false;" style="margin-left: 10px;">查询</button>
                </td>
            </tr>
        </table>
    </form>
    <div id="tableList" style="width:100%;text-align:center;">

    </div>
</body>

</html>