<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("项目编号参与人登记表");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");
include_once("../common/UserModel.php");
require_once '../common/DataModel.php';
$dataModel = new DataModel();
$model = new UserModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());

$userInfo = $model->getUserInfo($userId);

$action = new ActionModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$param = array(
    'id' => $id,
    'mainTable' => 'inhe_number_participant',
    'detailTable' => 'inhe_number_participant_item',
    'user_field' => 'user_id',
    'dept_field' => 'organ_id'
);
$data = $action->queryById($param);
$main = $data['main'];
$detail = $data['detail'];
$isChange = '';
$submitAction = 'Submit';
$saveAction = 'Save';
if (strpos($main['type'], '_change') != false) {
    $isChange = '_change';
    $submitAction = 'Change';
    $saveAction = 'ChangeSave';
}

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
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
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
                        '<td nowrap class="TableData"><input type="text" name="proportion' + userItem + '" id="proportion' + userItem + '" data-type="proportion" class="layui-input border-solid" onkeyup="clearNoNum(this);" /></td>' +
                        '</tr>';
                    $("#member_list").append(lineHtml);
                    $("[data-type='proportion']").each(function() {
                        this.onblur = function() {
                            sumProportion();
                        }
                    });
                    form.render();
                });

                $("#project_number").click(function() {
                    var user_bu=$("#user_bu").val()
                    var index = layui.layer.open({
                        title: "项目编号列表",
                        type: 2,
                        content: "./project_number_list.php?user_bu="+user_bu,
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

                $("[data-type='proportion']").each(function() {
                    this.onblur = function() {
                        sumProportion();
                    }
                });

                form.render();
            });
        });

        // 页面初始化加载
        function init() {
            $('#mainForm').find('input,textarea,select,td[class="TableData"]').not('[type="button"]').attr('style', 'color:#0066cc');
        }

        function returnUser(userName, userId, domId) {
            $("#" + domId).val(userName);
            $("#" + domId).parent().find("[data-type='user_id']").val(userId); //取userId隐藏栏位赋值
        }

        function returnProjectInfo(reData) {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $("#project_number").val(reData.project_number);
                $("#project_name").val(reData.project_name);
                $("#menu_parent_name").val(reData.project_dept_name);
                $("#menu_parent").val(reData.dept_id);
                $("#customer_name").val(reData.customer_name);
                // 回显项目参与人及比例数据
                var url = "action.php?action=Retrieve";
                $.post(url, {
                    dataType: 'findParticipantData',
                    project_code: reData.project_name
                }, function(data) {
                    var reJson = null;
                    try {
                        reJson = eval('(' + data + ')');
                    } catch (e) {
                        layer.alert('服务器异常: ' + e, {
                            icon: 5
                        });
                        return;
                    }
                    $("#member_list").empty();
                    if (reJson.code == "200" && reJson.data.length > 0) {
                        var data = reJson.data;
                        $("#leader_id").val(data[0].leader_id);
                        $("#leader").val(data[0].leader);
                        $("#leader_proportion").val(data[0].leader_proportion);
                        var userItem = member_i = 0;
                        var lineHtml = '';
                        var length = data.length;
                        // for (; member_i < length; member_i++) {
                        //     userItem = member_i + 1;
                        //     lineHtml += '<tr align="center"><input type="hidden" name="data_line" id="data_line" />' +
                        //         '<td nowrap class="TableData" width="15%"><img src="/static/images/remove.png" align="absmiddle" id="remove_line' + userItem + '" name="remove_line' + userItem + '" onclick="removeLine(this);" /></td>' +
                        //         '<td nowrap class="TableData" width="15%"><input type="hidden" name="user_item' + userItem + '" id="user_item' + userItem + '" value="' + userItem + '" />' + userItem + '</td>' +
                        //         '<td nowrap class="TableData" width="35%">' +
                        //         '<input type="hidden" data-type="user_id" value="' + data[member_i].user_id + '" name="user_id' + userItem + '" id="user_id' + userItem + '" />' +
                        //         '<input readonly type="text" name="user_name' + userItem + '" id="user_name' + userItem + '" value="' + data[member_i].user_name + '" onclick="getUserSelect(this);return false;" class="layui-input border-solid" /></td>' +
                        //         '<td nowrap class="TableData"><input type="text" name="proportion' + userItem + '" id="proportion' + userItem + '" value="' + data[member_i].member_proportion + '" data-type="proportion" class="layui-input border-solid" onkeyup="clearNoNum(this);" /></td>' +
                        //         '</tr>';
                        // }
                        // $("#member_list").append(lineHtml);
                        $("[data-type='proportion']").each(function() {
                            this.onblur = function() {
                                sumProportion();
                            }
                        });
                        form.render();
                    } else {
                        initInputValue();
                        form.render();
                    }
                });
                layer.closeAll();
                form.render();
            });
        }

        function initInputValue() {
            $("#leader_id").val('');
            $("#leader").val('');
            $("#leader_proportion").val('');
        }
    </script>
</head>

<body>
    <div class="weadmin-body" style="width:80%;margin:1% 9%;">
        <form id="mainForm" class="layui-form">
            <input type="hidden" name="fieldName" value="form_id" />
            <input type="hidden" name="tableName" value="inhe_number_participant" />
            <input type="hidden" name="flowType" value="menbn" />
            <input type="hidden" name="reset" value="day" />
            <input type="hidden" name="digit" value="2" />
            <input type="hidden" id="type" name="type" value="project_number_participant<?= $isChange ?>" />
            <input type="hidden" id="step" name="step" value="1" />
            <input type="hidden" name="title" id="title" value="<?= $main['title'] ?>" />
            <input type="hidden" name="dept_number" id="dept_number" value="" />
            <input type="hidden" name="data_count" id="data_count" value="<?= count($detail) ?>" />
            <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
            <input type="hidden" name="form_id" id="form_id" value="<?= $id ?>" />
            <table width="100%" align="center">
                        <td align="center">
                            <img alt="" src="../../../../../images/<?= $main[user_bu] ?>/project_number_participant.jpg" style="width: 34%; height: auto;" title="" />
                        </td>
                    </table>
                    <br />
            <table class="TableBlock" width="50%" align="center" style="margin: auto;">
                <tr>
                    <td colspan="6">
                        <table id="tab1" width="100%">
                        <tr>
                                <td nowrap class="TableContent" width="15%">所属公司</td>
                                <td nowrap class="TableData">
                                <input type="hidden" name="user_item1" id="user_item1" value="1" />
                                <input type="hidden" name="data_count" id="data_count" value="1" />
                                    <input type="hidden" data-type="user_id" name="user_id1" id="user_id1" value="<?=$userId?>"/>
                                    <input type="hidden" data-type="user_name" name="user_name1" id="user_name1" value="<?=$_SESSION['LOGIN_USER_NAME']?>"/>
                                    <input class="layui-input" id="company" name="company" value="<?=$crm_process_sort[$main[user_bu]]["paras_desc"]?>" readonly>
                                </td>
                            </tr>
                            <tr hidden>
                                <td nowrap class="TableContent" width="15%">所属部门</td>
                                <td nowrap class="TableData">
                                    <input type="text" id="menu_parent_name" class="layui-input" data-type="dept_select" name="dept_name" style="width: 220px;" value="<?= $main['dept_belong'] ?>" readonly/>
                                    <input type="hidden" id="menu_parent" name="menu_parent" value="<?= $main['dept_belong'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <td nowrap class="TableContent">项目名称</td>
                                <td nowrap class="TableData">
                                    <input type="text" name="project_name" id="project_name" class="layui-input" style="width: 220px;" value="<?= $main['project_code'] ?>" readonly />
                                </td>
                            </tr>
                            <tr>
                                <td nowrap class="TableContent">项目编号</td>
                                <td nowrap class="TableData">
                                    <input type="text" name="project_number" id="project_number" class="layui-input mandatory" style="width: 220px;" readonly="readonly" value="<?= $main['project_number'] ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td nowrap class="TableContent">客户名称</td>
                                <td nowrap class="TableData">
                                    <input type="text" name="customer_name" id="customer_name" class="layui-input" style="width: 220px;" value="<?= $main['customer_name'] ?>" readonly />
                                </td>
                            </tr>
                            <td class="TableContent" width="25%">备注</td>
                            <td class="TableData">
                                
                                <textarea class="layui-textarea mandatory" id="remark" name="remark" placeholder="备注说明" lay-verify="required"><?= $main['remark'] ?></textarea>
                            </td>
                        </table>
                        <table id="tab2" width="100%">
                            <!-- <tr align="center">
                                <td nowrap class="TableContent" colspan="2">项目负责人</td>
                                <td nowrap class="TableContent" colspan="2">比例</td>
                            </tr>
                            <tr align="center">
                                <td class="TableData" colspan="2">
                                    <input type="hidden" data-type="user_id" name="leader_id" id="leader_id" value="<?= $main['leader_id'] ?>" />
                                    <input readonly type="text" name="leader" id="leader" onclick="getUserSelect(this);return false;" class="layui-input border-solid" value="<?= $main['leader'] ?>" />
                                </td>
                                <td class="TableData" colspan="2">
                                    <input type="text" name="leader_proportion" id="leader_proportion" data-type="proportion" class="layui-input border-solid" onkeyup="clearNoNum(this);" value="<?= $main['proportion'] ?>" />
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
                                    <td nowrap class="TableData" width="15%"><input type="hidden" name="user_item<?= $i ?>" id="user_item" class="layui-input" value="<?= $i ?>" /><?= $i ?></td>
                                    <td nowrap class="TableData" width="35%"><input type="text" name="user_name<?= $i ?>" id="user_name" class="layui-input" value="<?= $val['user_name'] ?>" onclick="getUserSelect(this);return false;"  /></td>
                                    <td nowrap class="TableData"><input type="text" name="proportion<?= $i ?>" id="proportion" class="layui-input" value="<?= $val['proportion'] ?>" /></td>
                                </tr>
                            <?php endforeach; ?>
                            <table id="member_list" width="100%"></table> -->
                            <tr class="layui-bg-body">
                                <td colspan="4" align="center" style="padding: 5px;">
                                    <input lay-submit lay-filter="submit" name="btn_submit" actionType="<?= $submitAction ?>" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <input lay-submit lay-filter="submit" name="btn_save" actionType="<?= $saveAction ?>" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.closeIframe();return false;" />
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