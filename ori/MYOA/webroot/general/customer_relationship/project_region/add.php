<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("项目进度登记");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../common/DataModel.php");
include_once("../common/UserModel.php");
require_once("ActionModel.php");
require_once("../common/AttachModel.php");
$dataModel = new DataModel();
$userModel = new UserModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$date = date('Y-m-d');
$userInfo = $userModel->getUserInfo($userId);
$companyNew=$userInfo[company_user_bu];
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_schedule_nodes'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$project_schedule_nodes = array();
foreach ($selectArr['project_schedule_nodes'] as $v) {
    $project_schedule_nodes[$v['paras_value']] = $v;
}
$attachObj ="[]";
if(!empty($_GET[id])){
    $param['id'] = $_GET[id];
    $action = new ActionModel();
    $data = $action->queryById($param);
    $main =$data[main];
    $attachModel = new AttachModel();
    $attachObj = $attachModel->getAttachById($main[form_id]);
}

?>

<head>
<meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script src="<?= MYOA_JS_SERVER ?>/inc/js/module.js"></script>
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
                                <?if($main[status]!="Y"):?>
                                <input lay-submit lay-filter="submit" name="btn_save" actionType="Save" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <?endif;?>
                                <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="window.close();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="width:80%;margin:1% 9%;">
            <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_project_region" />
                <input type="hidden" name="flowType" value="INHEPR" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" id="type" name="type" value="inhe_project_region" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $main[form_id] ?>" />
                <input type="hidden" name="company" id="company" value="<?= $companyNew ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $company?$company:$userInfo[company_user_bu] ?>" />
                <input type="hidden" name="dept_id" id="dept_id" value="<?= $userInfo[dept_id] ?>" />
                <input type="hidden" name="dept_name" id="dept_name" value="<?= $userInfo[deptName] ?>" />
                <table class="TableBlock" width="100%" align="center">
                    <tr>
                        <td class="TableContent" >项目所在地/区域</td>
                        <td class="TableData">
                        <input type="text" name="name" class="layui-input mandatory" value="<?=$main[name]?>" lay-verify="required"/>
                        </td>
                    </tr>

                    <tr>
                        <td class="TableContent">业务部门</td>
                        <td class="TableData">
                        <input type="hidden" name="business_dept" id="business_dept" value="<?=$main[business_dept_id]?>">
                                        <textarea name="business_dept_name" id="business_dept_name" cols="100" rows="3" class="SmallStatic" value="" style="min-height: 50px;overflow-y:auto;" wrap="yes" readonly><?=$main[business_dept_name]?></textarea>
                                        <a href="#" class="orgAdd" onClick="SelectDeptSingle('','business_dept', 'business_dept_name')" title="添加收件人">添加</a>
                                        <a href="#" class="orgClear" onClick="ClearDept('business_dept', 'business_dept_name')" title="清空收件人">清空</a><br>
                            <!-- <input type="text" name="business_user" id="business_user" class="layui-input " placeholder="业务负责人" autocomplete="off" lay-verify="required" /> -->
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">业务负责人</td>
                        <td class="TableData">
                        <input type="hidden" name="business_user" id="business_user" value="<?=$main[business_user]?>">
                                        <textarea name="business_user_name" id="business_user_name" cols="100" rows="3" class="SmallStatic" value="" style="min-height: 50px;overflow-y:auto;" wrap="yes" readonly><?=$main[business_user_name]?></textarea>
                                        <a href="#" class="orgAdd" onClick="SelectUserSingle('','business_user', 'business_user_name')" title="添加收件人">添加</a>
                                        <a href="#" class="orgClear" onClick="ClearUser('business_user', 'business_user_name')" title="清空收件人">清空</a><br>
                            <!-- <input type="text" name="business_user" id="business_user" class="layui-input " placeholder="业务负责人" autocomplete="off" lay-verify="required" /> -->
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">交付经理</td>
                        <td class="TableData">
                        <input type="hidden" name="delivery_user" id="delivery_user" value="<?=$main[delivery_user]?>">
                                        <textarea name="delivery_user_name" id="delivery_user_name" cols="100" rows="3" class="SmallStatic" value="" style="min-height: 50px;overflow-y:auto;" wrap="yes" readonly><?=$main[delivery_user_name]?></textarea>
                                        <a href="#" class="orgAdd" onClick="SelectUserSingle('','delivery_user', 'delivery_user_name')" title="添加收件人">添加</a>
                                        <a href="#" class="orgClear" onClick="ClearUser('delivery_user', 'delivery_user_name')" title="清空收件人">清空</a><br>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

<script type="text/javascript">
    $(function() {
        layui.extend({
                attach: '/common/js/extends/attach'
            }).use(['form', 'layer', 'table'], function() {
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

            form.on('select(progress_nodes)', function(data) {
                console.log(data.value);
                $("#progress_nodes_desc").text($(data.elem).find("option:selected").attr("title"));
            });

            $("#project_code").blur(function() {
                var url = "action.php?action=Retrieve";
                // 校验系统项目代号
                if (this.value != '') {
                    $.post(url, {
                        dataType: 'existProjectCode',
                        project_code: this.value
                    }, function(data) {
                        var reJson = null;
                        reJson = eval('(' + data + ')');
                        if (reJson.code == '200') {
                            // if (reJson.data) {
                            //     layer.msg('项目代号已存在！');
                            // }
                        }else{
                            layer.msg('项目代号不存在！');
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