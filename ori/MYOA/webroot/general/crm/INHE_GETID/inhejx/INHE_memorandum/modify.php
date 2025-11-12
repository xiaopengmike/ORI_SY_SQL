<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("编辑页面");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");

$model = new ActionModel();
$result = $model->query($id);
$mainRes = $result['mainResult'];  // 项目主要信息
$subRes = $result['subResult'];  // 项目比例详细信息

?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/attach.js"></script>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/dtreeck.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="pm_manage.js"></SCRIPT>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/dtreeck.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">

<style>
    .border-solid {
        border: 1px solid;
    }
</style>
<script type="text/javascript">
    $(function() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;

            $("#menu_parent_name").change(function() {
                if (ifEmpty(this.value)) {
                    $("#menu_parent").val("");
                }
            });

            $("#add_line").click(function() {
                var length = $("input[name='data_line']").length;
                var userItem = length + 1;
                $("#data_count").val(userItem);
                var lineHtml = '<tr align="center"><input type="hidden" name="data_line" id="data_line" />' +
                    '<td nowrap class="TableData" width="15%"><img src="/static/images/remove.png" align="absmiddle" id="remove_line' + userItem + '" name="remove_line' + userItem + '" onclick="removeLine(this);" /></td>' +
                    '<td nowrap class="TableData" width="15%"><input type="hidden" name="user_item' + userItem + '" id="user_item' + userItem + '" value="' + userItem + '" />' + userItem + '</td>' +
                    '<td nowrap class="TableData" width="35%">' +
                    '<input type="hidden" data-type="user_id" name="user_id' + userItem + '" id="user_id' + userItem + '" />' +
                    '<input readonly type="text" name="user_name' + userItem + '" id="user_name' + userItem + '" onclick="getUserSelect(this);return false;" class="layui-input border-solid" placeholder="请选择人员" /></td>' +
                    '<td nowrap class="TableData"><input type="text" name="proportion' + userItem + '" id="proportion' + userItem + '" placeholder="请输入比例" class="layui-input border-solid" onkeyup="clearNoNum(this);" /></td>' +
                    '</tr>';

                $("#tHeader").before(lineHtml);
            });

            // 审核人员下拉数据初始化
            selectUserByDeptId('<?= $mainRes['dept_id'] ?>', '', '<?= $mainRes['Reviewed_ID'] ?>');

            form.render();
        });
    });
</script>

<body>
    <div class="weadmin-body">
        <form id="form1" class="layui-form">
            <input type="hidden" name="dept_number" id="dept_number" value="" />
            <input type="hidden" name="data_count" id="data_count" value="" />
            <table class="TableBlock" width="70%" align="center" style="margin-top: 20px;">
                <tr>
                    <td>
                        <table id="tab" width="100%">
                            <tr>
                                <td nowrap class="TableContent" width="15%">项目代号：</td>
                                <td nowrap class="TableData">
                                    <input type="hidden" name="pm_id" id="pm_id" class="layui-input" value="<?= $mainRes['PM_ID'] ?>" />
                                    <?= $mainRes['PM_ID'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap class="TableContent" width="15%">审核人员：</td>
                                <td nowrap class="TableData">
                                    <input type="text" id="menu_parent_name" class="layui-input" data-type="dept_select" name="dept_name" style="width: 220px;" placeholder="请选择部门" value="<?= $mainRes['dept_name'] ?>" />
                                    <input type="hidden" id="menu_parent" name="menu_parent" value="<?= $mainRes['dept_id'] ?>" />
                                    <div class="layui-input-inline" style="width: 220px;">
                                        <select name="user_name" id="user_name" lay-filter="user_name"></select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap class="TableContent" style="text-align: left" colspan="2">项目比例关系：</td>
                            </tr>
                        </table>
                        <table id="tab" width="100%">
                            <tr align="center">
                                <td nowrap class="TableData" width="15%">
                                    <img src="/static/images/add.png" align="absmiddle" id="add_line" name="add_line" />
                                </td>
                                <td nowrap class="TableData" width="15%">序号</td>
                                <td nowrap class="TableData" width="35%">人员</td>
                                <td nowrap class="TableData">比例</td>
                            </tr>
                            <?php foreach ($subRes as $key => $val) : ?>
                                <tr align="center">
                                    <input type="hidden" name="data_line" id="data_line" />
                                    <td nowrap class="TableData" width="15%">
                                        <img src="/static/images/remove.png" align="absmiddle" id="remove_line<?= $val['user_item'] ?>" name="remove_line<?= $val['user_item'] ?>" onclick="removeLine(this);" />
                                    </td>
                                    <td nowrap class="TableData" width="15%">
                                        <input type="hidden" name="user_item<?= $val['user_item'] ?>" id="user_item<?= $val['user_item'] ?>" value="<?= $val['user_item'] ?>" />
                                        <?= $val['user_item'] ?>
                                    </td>
                                    <td nowrap class="TableData" width="35%">
                                        <input type="hidden" data-type="user_id" name="user_id<?= $val['user_item'] ?>" id="user_id<?= $val['user_item'] ?>" />
                                        <input readonly type="text" name="user_name<?= $val['user_item'] ?>" id="user_name<?= $val['user_item'] ?>" value="<?= $val['user_name'] ?>" onclick="getUserSelect(this);return false;" class="layui-input border-solid" placeholder="请选择人员" />
                                    </td>
                                    <td nowrap class="TableData">
                                        <input type="text" name="proportion<?= $val['user_item'] ?>" id="proportion<?= $val['user_item'] ?>" value="<?= $val['proportion'] ?>" placeholder="请输入比例" class="layui-input border-solid" onkeyup="clearNoNum(this);" />
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="layui-bg-body layui-bg-gray" id="tHeader">
                                <td colspan="4" align="center" style="padding: 5px;">
                                    <input name="btn_submit" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="updateData(this);return false;" />
                                    <input name="btn_save" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="updateData(this);return false;" />
                                    <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="closeModel();return false;" />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <br />
    <?php
    include_once("common/mydtree_new.php");
    ?>
</body>

</html>