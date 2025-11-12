<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("项目代号取号");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../common/DataModel.php");
include_once("../common/UserModel.php");
$dataModel = new DataModel();
$userModel = new UserModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$date = date('Y-m-d');
$companyNew = $userModel->getUserBu($deptId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'temp_code_rule'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$temp_code_rule = array();
foreach ($selectArr['temp_code_rule'] as $v) {
    $temp_code_rule[$v['paras_value']] = $v;
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
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <link rel="stylesheet" type="text/css" href="../crm.css">
</head>

<body>
    <div style="width: 100%;height:auto;margin:auto;">
        <form id="mainForm" class="layui-form" lay-filter="mainForm">
            <div class="r" style="height: auto;">
                <div style="text-align: left;" class="layui-btn-group">
                    <table id="tab" width="100%">
                        <tr class="layui-bg-body">
                            <td style="padding: 5px;">
                                <input lay-submit lay-filter="submit" name="btn_submit" actionType="Submit" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="window.close();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="width:80%;margin:1% 9%;">
                <input type="hidden" name="form_type" id="form_type" value="temp_code" />
                <input type="hidden" name="company" id="company" value="<?= $companyNew ?>" />
                <table class="TableBlock" width="100%" align="center">
                    <tr>
                        <td class="TableContent">项目代号</td>
                        <td class="TableData">
                            <input type="text" name="temp_code" id="temp_code" readonly class="layui-input" placeholder="系统自动生成" />
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">项目名称</td>
                        <td class="TableData">
                            <input type="text" name="project_name" id="project_name" class="layui-input mandatory" placeholder="项目名称" autocomplete="off" lay-verify="required" />
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">项目分类</td>
                        <td class="TableData">
                            <div class="layui-input-inline mandatory">
                                <select id="scope_code" name="scope_code" lay-filter="scope_code" lay-search lay-verify="required">
                                    <option value=''>请选择</option>
                                    <option value='F' selected>国际项目</option>
                                    <option value='D'>国内项目</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">项目所在地/国家代码</td>
                        <td class="TableData">
                            <input type="text" name="location" id="location" class="layui-input mandatory" placeholder="项目所在地" autocomplete="off" lay-verify="required" />
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">取号人姓名</td>
                        <td class="TableData">
                            <input type="hidden" name="user_id" id="user_id" class="layui-input" value="<?= $userId ?>" />
                            <input type="text" name="user_name" id="user_name" class="layui-input" value="<?= $userName ?>" readonly />
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">取号时间</td>
                        <td class="TableData">
                            <input type="text" name="date" id="date" class="layui-input" value="<?= $date ?>" readonly />
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">备注</td>
                        <td class="TableData">
                            <textarea class="layui-textarea" id="remark" name="remark" placeholder="备注" autocomplete="off"></textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

<script type="text/javascript">
    $(function() {
        layui.use(['form', 'layer', 'table'], function() {
            var layer = layui.layer,
                form = layui.form,
                table = layui.table;

            form.on('submit(submit)', function(data) {
                var btnVal = data.elem.value;
                var actionType = $(data.elem).attr("actionType");
                var url = "action.php?action=" + actionType;
                $.post(url, $("#mainForm").serializeArray(), function(data) {
                    var reJson = null;
                    try {
                        reJson = eval('(' + data + ')');
                    } catch (e) {
                        layer.alert('服务器异常: ' + e, {
                            icon: 5
                        });
                        return;
                    }
                    if (reJson.code == "200") {
                        layer.alert(btnVal + "成功!", {
                            icon: 1
                        }, function(index, layero) {
                            window.opener.location.reload();
                            parent.layer.close(index);
                            parent.closeIframe();
                        });
                    } else {
                        layer.alert(btnVal + '失败！', {
                            icon: 5
                        });
                    }
                });
            });

            form.on('select(scope_code)', function(data) {
                var btnVal = data.elem.value;
                var actionType = $(data.elem).attr("actionType");
                var url = "action.php?action=" + actionType;
                $.post(url, $("#mainForm").serializeArray(), function(data) {
                    var reJson = null;
                    try {
                        reJson = eval('(' + data + ')');
                    } catch (e) {
                        layer.alert('服务器异常: ' + e, {
                            icon: 5
                        });
                        return;
                    }
                    if (reJson.code == "200") {
                        layer.alert(btnVal + "成功!", {
                            icon: 1
                        }, function(index, layero) {
                            // var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                            parent.layer.close(index); //再执行关闭
                        });
                    } else {
                        layer.alert(btnVal + '失败！', {
                            icon: 5
                        });
                    }
                });
            });

            $("#temp_code").blur(function() {
                var url = "action.php?action=Retrieve";
                // 校验系统项目代号
                if (this.value != '') {
                    $.post(url, {
                        dataType: 'verifyProjectCode',
                        projectCode: this.value
                    }, function(data) {
                        var reJson = null;
                        reJson = eval('(' + data + ')');
                        if (reJson.code == '200') {
                            if (reJson.data) {
                                layer.msg('项目代号已存在！');
                                $("#temp_code").val('');
                            }
                        }
                    });
                }
            });

            form.on('select(user_id)', function(data) {
                $("#user_name").val(data.elem[data.elem.selectedIndex].text);

                form.render();
            });

            form.render();
        });
    });

    function selectUserByDeptId(deptId, number, defaultVal) {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;
            if (deptId != '') {
                $.post('user_info.php?deptId=' + deptId, {}, function(data) {
                    var obj = eval('(' + data + ')');

                    $("#user_id" + number).empty();
                    $("#user_id" + number).append("<option value=''>请选择</option>");
                    var selected = "";
                    for (i = 0; i < obj.length; i++) {
                        if (defaultVal == obj[i].user_id) {
                            selected = " selected ";
                        }
                        $("#user_id" + number).append("<option value='" + obj[i].user_id + "' " + selected + ">" + obj[i].user_name + "</option>");
                    }
                });
            }
            form.render();
        });
    }
</script>

</html>