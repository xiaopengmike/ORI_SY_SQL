<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("立项项目代号取号");
include_once("inc/header.inc.php");
include_once("common/Common.php");

include_once("../common/UserModel.php");
$userModel = new UserModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];

$companyNew = $userModel->getUserBu($deptId);
$company    =empty($company)?$companyNew:$company;
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

    <script type="text/javascript">
        $(function() {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;

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
                        console.log(reJson);
                        if (reJson.code == "200") {
                            if (reJson.data.code=='200') {
                                layer.msg(reJson.data.data);
                                return;
                            }
                            layer.alert(btnVal + "成功!", {
                                icon: 1
                            }, function(index, layero) {
                                // 返回项目代号
                                var project_code = $("#project_code").val();
                                var temp_project_code = $("#temp_project_code").val();
                                parent.returnProjectCode(project_code,temp_project_code);
                                var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                                parent.layer.close(index); //再执行关闭
                            });
                        } else {
                            layer.alert(btnVal + '失败！', {
                                icon: 5
                            });
                        }
                    });
                });

                $("#project_code,#temp_project_code").blur(function(){
                    projectCode =$("#project_code").val();
                    temp_project_code=$("#temp_project_code").val();
                    var url = "action.php?action=Retrieve";
                    $.post(url, {
                        dataType: 'verifyProjectCode',
                        projectCode: projectCode,
                        user_bu: $("#user_bu").val(),
                        temp_project_code: temp_project_code
                    }, function(data) {
                        var reJson = null;
                        reJson = eval('(' + data + ')');
                        if (reJson.code == '200') {
                            console.log(reJson.data);
                            if (reJson.data.code=='200') {
                                layer.msg(reJson.data.data);
                                return;
                            }
                        }
                    });
                })
                
                $('#createCode').on('click', function() {
                    var url = "action.php?action=Retrieve";
                    $.post(url, {
                        dataType: 'getFormalProjectCode',
                        charLength: 3
                    }, function(data) {
                        var reJson = null;
                        reJson = eval('(' + data + ')');
                        if (reJson.code == '200') {
                            $("#project_code").val(reJson.data);
                            form.render();
                            return false;
                        }
                    });
                });

                form.render();
            });
        });
    </script>

<body>
    <div class="weadmin-body" style="width:90%;margin:1% 4%;">
        <form id="mainForm" class="layui-form">
            <input type="hidden" name="dataType" id="form_type" value="verifyProjectCode" />
            <input type="hidden" name="user_bu" id="user_bu" value="<?= $company ?>" />
            <table class="layui-table" border="0" cellpadding="0" cellspacing="10" lay-size="sm">
                <tr>
                    <td nowrap class="layui-bg-gray" width="25%">正式立项项目代号</td>
                    <td>
                        <div class="layui-input-inline">
                            <input type="text" name="projectCode"  id="project_code" lay-verify="required" class="layui-input" placeholder="请输入项目代号" autocomplete="off" minlength="3"/>
                        </div>
                        <div class="layui-input-inline">
                            <input id="createCode" name="createCode" value="取号" type=button class="layui-btn layui-btn-oa" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">关联临时项目代号</td>
                    <td>
                        <input type="text" name="temp_project_code" id="temp_project_code" class="layui-input" placeholder="请输入临时项目代号" autocomplete="off" />
                    </td>
                </tr>
                <tr>
                    <td colspan="8" align="center" style="padding: 5px;">
                        <input lay-submit lay-filter="submit" name="btn_submit" actionType="Retrieve" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>