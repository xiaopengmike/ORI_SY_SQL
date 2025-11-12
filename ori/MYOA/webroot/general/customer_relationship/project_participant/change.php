<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("项目参与人登记表");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");
require_once '../common/DataModel.php';

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());

$action = new ActionModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$param = array(
    'id' => $id,
    'mainTable' => 'inhe_project_participant',
    'detailTable' => 'inhe_project_participant_item',
    'user_field' => 'user_id',
    'dept_field' => 'organ_id'
);
$data = $action->queryById($param);
$main = $data['main'];
$detail = $data['detail'];
$dataModel = new DataModel();
$customerData = $dataModel->findCustomerByCode();
?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="main.js"></script>
    <script language="Javascript" type="text/javascript" src="../add.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/dtreeck.css">
    <SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/dtreeck.js"></SCRIPT>

    <script type="text/javascript">
        $(function() {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;

                init();
                $.formSubmit();

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
                    form.render();
                });

                $("#project_name").click(function() {
                    var index = layui.layer.open({
                        title: "项目代号列表",
                        type: 2,
                        content: "../sales_contract_review/project_list.php",
                        area: ['600px', '650px'],
                        success: function(layero, index) {
                            setTimeout(function() {
                                layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                    tips: 3
                                });
                            }, 500)
                        }
                    });
                });

                form.render();
            });
        });

        // 页面初始化加载
        function init() {
            $('#mainForm').find('input,textarea,select,td[class="TableData"]').not('[type="button"]').attr('style', 'color:#0066cc');
        }

        function returnProjectInfo(reData) {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;

                $("#project_name").val(reData.project_code);
                $("#menu_parent_name").val(reData.dept_name);
                $("#menu_parent").val(reData.dept_id);

                var obj = <?= $customerData ?>;
                var customerArr = obj[reData.project_code];
                var cus_length = customerArr.length;
                var customer_i = 0;
                for (; customer_i < cus_length; customer_i++) {
                    $("#customer_name").append('<option value="' + customerArr[customer_i] + '">' + customerArr[customer_i] + '</option>');
                }
                layer.closeAll();
                form.render();
            });
        }
    </script>
</head>

<body>
    <div class="weadmin-body" style="width:90%;margin:1% 4%;">
        <form id="mainForm" class="layui-form">
            <input type="hidden" name="fieldName" value="form_id" />
            <input type="hidden" name="tableName" value="inhe_project_participant" />
            <input type="hidden" name="flowType" value="menc" />
            <input type="hidden" name="reset" value="day" />
            <input type="hidden" name="digit" value="2" />
            <input type="hidden" id="type" name="type" value="project_participant_change" />
            <input type="hidden" id="step" name="step" value="1" />
            <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
            <input type="hidden" name="form_id" id="form_id" />
            <input type="hidden" name="related_id" id="related_id" value="<?= $main['form_id'] ?>" />
            <input type="hidden" name="dept_number" id="dept_number" value="" />
            <input type="hidden" name="data_count" id="data_count" value="<?= count($detail) ?>" />
            <table class="TableBlock" width="50%" align="center" style="margin-top: 20px;">
                <tr>
                    <td colspan="6">
                        <table id="tab1" width="100%">
                            <tr>
                                <td nowrap class="TableContent" width="15%">所属部门</td>
                                <td nowrap class="TableData">
                                    <input type="text" id="menu_parent_name" class="layui-input" data-type="dept_select" name="dept_name" style="width: 220px;" value="<?= $main['dept_belong'] ?>" />
                                    <input type="hidden" id="menu_parent" name="menu_parent" value="<?= $main['dept_belong'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <td nowrap class="TableContent">项目名称</td>
                                <td nowrap class="TableData">
                                    <input type="text" name="project_name" id="project_name" class="layui-input" style="width: 220px;" value="<?= $main['project_code'] ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td nowrap class="TableContent">客户名称</td>
                                <td nowrap class="TableData">
                                    <input type="text" name="customer_name" id="customer_name" class="layui-input" style="width: 220px;" value="<?= $main['customer_name'] ?>" />
                                </td>
                            </tr>
                        </table>
                        <table id="tab2" width="100%">
                            <tr align="center">
                                <td nowrap class="TableContent" colspan="2">项目负责人</td>
                                <td nowrap class="TableContent" colspan="2">比例</td>
                            </tr>
                            <tr align="center">
                                <td class="TableData" colspan="2">
                                    <input type="hidden" data-type="user_id" name="leader_id" id="leader_id" value="<?= $main['leader_id'] ?>" />
                                    <input type="text" name="leader" id="leader" value="<?= $main['leader'] ?>" class="layui-input border-solid" onclick="getUserSelect(this);return false;" />
                                </td>
                                <td class="TableData" colspan="2">
                                    <input type="text" name="leader_proportion" id="leader_proportion" class="layui-input border-solid" value="<?= $main['proportion'] ?>" />
                                </td>
                            </tr>
                            <tr align="center">
                                <td nowrap class="TableContent" width="15%">
                                    <img src="/static/images/add.png" align="absmiddle" id="add_line" name="add_line" />
                                </td>
                                <td nowrap class="TableContent" width="15%">序号</td>
                                <td nowrap class="TableContent" width="35%">项目参与人</td>
                                <td nowrap class="TableContent">比例</td>
                            </tr>
                            <?php $i = 0;
                            foreach ($detail as $val) : $i++; ?>
                                <tr align="center">
                                    <input type="hidden" name="data_line" id="data_line" />
                                    <td nowrap class="TableData" width="15%"><img src="/static/images/remove.png" align="absmiddle" id="remove_line" name="remove_line" onclick="removeLine(this);" /></td>
                                    <td nowrap class="TableData" width="15%"><input type="hidden" name="user_item<?= $i ?>" id="user_item" class="layui-input border-solid" value="<?= $val['serial_number'] ?>" /><?= $val['serial_number'] ?></td>
                                    <td nowrap class="TableData" width="35%">
                                        <input type="hidden" data-type="user_id" name="user_id<?= $i ?>" id="user_id" value="<?= $val['user_id'] ?>" />
                                        <input type="text" name="user_name<?= $i ?>" id="user_name" class="layui-input" value="<?= $val['user_name'] ?>" />
                                    </td>
                                    <td nowrap class="TableData">
                                        <input type="text" name="proportion<?= $i ?>" id="proportion" class="layui-input" value="<?= $val['proportion'] ?>" />
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="layui-bg-body layui-bg-gray" id="tHeader">
                                <td colspan="4" align="center" style="padding: 5px;">
                                    <input lay-submit lay-filter="submit" name="btn_submit" actionType="Change" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <input lay-submit lay-filter="submit" name="btn_save" actionType="ChangeSave" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="closeIframe();return false;" />
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