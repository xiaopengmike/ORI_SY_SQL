<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("项目组名单");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../common/DataModel.php");
include_once("../common/UserModel.php");
include_once("./ActionModel.php");
$dataModel = new DataModel();
$userModel = new UserModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$date = date('Y-m-d');
$companyNew = $userModel->getUserBu($deptId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'temp_code_rule,company'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$temp_code_rule = array();
foreach ($selectArr['temp_code_rule'] as $v) {
    $temp_code_rule[$v['paras_value']] = $v;
}

if(!empty($id)){
    $action = new ActionModel();
    $param[id]=$id;
    $data = $action->queryById($param);
    $business_owner=$data[business_owner];
    $commerce_owner=$data[commerce_owner];
    $technical_support_owner=$data[technical_support_owner];
    $business=$data[business];
    $commerce=$data[commerce];
    $technical_support=$data[technical_support];
    $business_name=$data[business_name];
    $commerce_name=$data[commerce_name];
    $technical_support_name=$data[technical_support_name];
    $business_owner_name=$data[business_owner_name];
    $commerce_owner_name=$data[commerce_owner_name];
    $technical_support_owner_name=$data[technical_support_owner_name];
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
                                <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="window.close();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="width:80%;margin:1% 9%;">
            <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_project_team" />
                <input type="hidden" name="flowType" value="PT" />
                <input type="hidden" name="digit" value="3" />
                <input type="hidden" name="form_id" value="<?=$data[form_id]?>" />
                <input type="hidden" name="id" value="<?=$data[id]?>" />
                <table class="TableBlock" width="100%" align="center">
                <tr>
                        <td class="TableContent">公司</td>
                        <td class="TableData">
                        <div class="layui-input-inline mandatory" style="width: 120px;">
                        <select name="user_bu" id="user_bu" class="normalSelect " lay-verify="required" >
                            <option value="">请选择</option>
                            <?php foreach($selectArr['company'] as $flow_status):?>
                                <?if($flow_status['paras_value']=="CORP"){continue;}?>
                            <option value='<?=$flow_status['paras_value']?>' <?if($data[user_bu]==$flow_status[paras_value]):?>selected<?endif;?>><?=$flow_status['paras_desc']?></option>
                            <?php endforeach;?>
                        </select>
                        </div>
                        </td>
                    </tr>
                
                    <tr>
                        <td class="TableContent">项目组</td>
                        <td class="TableData">
                            <input type="text" name="team_name" id="team_name" value="<?=$data[team_name]?>" class="layui-input mandatory" placeholder="项目组" autocomplete="off" lay-verify="required" />
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="TableContent">负责区域</td>
                        <td class="TableData">
                            <textarea class="layui-textarea mandatory" id="region" name="region" value="<?=$data[region]?>" placeholder="负责区域" autocomplete="off"><?=$data[region]?></textarea>
                        </td>
                    </tr>
                   
                    <tr>
                        <td class="TableContent">业务负责人</td>
                        <td class="TableData">
                        <input type="hidden" name="business_owner" id="business_owner" value="<?=$business_owner?>">
                                        <textarea name="business_owner_name" id="business_owner_name" cols="100" rows="3" class="SmallStatic" value="" style="min-height: 50px;overflow-y:auto;" wrap="yes" readonly><?=$business_owner_name?></textarea>
                                        <a href="#" class="orgAdd" onClick="SelectUserSingle('','business_owner', 'business_owner_name')" title="添加收件人">添加</a>
                                        <a href="#" class="orgClear" onClick="ClearUser('business_owner', 'business_owner_name')" title="清空收件人">清空</a><br>
                            <!-- <input type="text" name="business_owner" id="business_owner" class="layui-input " placeholder="业务负责人" autocomplete="off" lay-verify="required" /> -->
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">商务负责人</td>
                        <td class="TableData">
                        <input type="hidden" name="commerce_owner" id="commerce_owner" value="<?=$commerce_owner?>">
                                        <textarea name="commerce_owner_name" id="commerce_owner_name" cols="100" rows="3" class="SmallStatic" value="" style="min-height: 50px;overflow-y:auto;" wrap="yes" readonly><?=$commerce_owner_name?></textarea>
                                        <a href="#" class="orgAdd" onClick="SelectUserSingle('','commerce_owner', 'commerce_owner_name')" title="添加收件人">添加</a>
                                        <a href="#" class="orgClear" onClick="ClearUser('commerce_owner', 'commerce_owner_name')" title="清空收件人">清空</a><br>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">技术支持负责人</td>
                        <td class="TableData">
                        <input type="hidden" name="technical_support_owner" id="technical_support_owner" value="<?=$technical_support_owner?>">
                                        <textarea name="technical_support_owner_name" id="technical_support_owner_name" cols="100" rows="3" class="SmallStatic" value="" style="min-height: 50px;overflow-y:auto;" wrap="yes" readonly><?=$technical_support_owner_name?></textarea>
                                        <a href="#" class="orgAdd" onClick="SelectUserSingle('','technical_support_owner', 'technical_support_owner_name')" title="添加收件人">添加</a>
                                        <a href="#" class="orgClear" onClick="ClearUser('technical_support_owner', 'technical_support_owner_name')" title="清空收件人">清空</a><br>
                            
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">业务团队</td>
                        <td class="TableData">
                        <input type="hidden" name="business" id="business" value="<?=$business?>">
                                        <textarea name="business_name" id="business_name" cols="100" rows="3" class="SmallStatic" value="" style="min-height: 50px;overflow-y:auto;" wrap="yes" readonly><?=$business_name?></textarea>
                                        <a href="#" class="orgAdd" onClick="SelectUser('','business', 'business_name')" title="添加收件人">添加</a>
                                        <a href="#" class="orgClear" onClick="ClearUser('business', 'business_name')" title="清空收件人">清空</a><br>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">商务团队</td>
                        <td class="TableData">
                        <input type="hidden" name="commerce" id="commerce" value="<?=$commerce?>">
                                        <textarea name="commerce_name" id="commerce_name" cols="100" rows="3" class="SmallStatic" value="" style="min-height: 50px;overflow-y:auto;" wrap="yes" readonly><?=$commerce_name?></textarea>
                                        <a href="#" class="orgAdd" onClick="SelectUser('','commerce', 'commerce_name')" title="添加收件人">添加</a>
                                        <a href="#" class="orgClear" onClick="ClearUser('commerce', 'commerce_name')" title="清空收件人">清空</a><br>
                            
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">技术支持团队</td>
                        <td class="TableData">
                        <input type="hidden" name="technical_support" id="technical_support" value="<?=$technical_support?>">
                                        <textarea name="technical_support_name" id="technical_support_name" cols="100" rows="3" class="SmallStatic" value="" style="min-height: 50px;overflow-y:auto;" wrap="yes" readonly><?=$technical_support_name?></textarea>
                                        <a href="#" class="orgAdd" onClick="SelectUser('','technical_support', 'technical_support_name')" title="添加收件人">添加</a>
                                        <a href="#" class="orgClear" onClick="ClearUser('technical_support', 'technical_support_name')" title="清空收件人">清空</a><br>
                            
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