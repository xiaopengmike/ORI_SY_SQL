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
    $company=$main["user_bu"];
}

$query = "select k.* " .
    "  from inhe_project_region k where user_bu='$company' and status='Y'" ;
    //echo $query;
$res = exequery(TD::conn(), $query);
$project_region = array();
while ($item = mysql_fetch_assoc($res)) {
    $project_region[] = $item;
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
        
    <script language="Javascript" type="text/javascript" src="../add.js"></script>
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
                                <input lay-submit lay-filter="submit" name="btn_save" actionType="Save" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="window.close();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="width:80%;margin:1% 9%;">
            <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_project_schedule" />
                <input type="hidden" name="flowType" value="INHEPS" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" id="type" name="type" value="inhe_project_schedule" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $main[form_id] ?>" />
                <input type="hidden" name="schedule_id" id="schedule_id" value="<?= $main[id] ?>" />
                <input type="hidden" name="company" id="company" value="<?= $companyNew ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $company?$company:$userInfo[company_user_bu] ?>" />
                <input type="hidden" name="dept_id" id="dept_id" value="<?= $userInfo[dept_id] ?>" />
                <input type="hidden" name="dept_name" id="dept_name" value="<?= $userInfo[deptName] ?>" />
                <table class="TableBlock" width="100%" align="center">
                    <tr>
                        <td class="TableContent">项目所在地/区域</td>
                        <td class="TableData">
                        <input type="hidden"  name="region" id="region" class="layui-input mandatory" value="<?=$main[region]?$main[region]:$userInfo[deptName]?>" lay-verify="required"/>
                        <div class="layui-input-inline mandatory">
                        <select id="region_id" name="region_id" lay-filter="region_id" lay-search lay-verify="required">
                                    <option value=''>请选择</option>
                                    <?php foreach ($project_region as $cVal) : ?>
                                        <option value="<?= $cVal['id'] ?>" <?if($main[region_id]==$cVal['id']):?>selected=selected<?endif;?>><?= $cVal['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">项目代号</td>
                        <td class="TableData">
                            <input type="text" name="project_code" id="project_code" class="layui-input mandatory" placeholder="项目代号" autocomplete="off" lay-verify="required" value="<?=$main[project_code] ?>"/>
                        </td>
                    </tr>
                    <!-- <tr>
                        <td class="TableContent">项目星级</td>
                        <td class="TableData">
                            
                        </td>
                    </tr> -->
                    <tr>
                        <td class="TableContent">项目进度</td>
                        <td class="TableData">
                            <div class="layui-input-inline mandatory" style="float:left">
                                <select id="progress_nodes" name="progress_nodes" lay-filter="progress_nodes" lay-search lay-verify="required">
                                    <option value=''>请选择</option>
                                    <?php foreach ($selectArr['project_schedule_nodes'] as $cVal) : ?>
                                        <option value="<?= $cVal['paras_value'] ?>"  extra="<?= $cVal['extra'] ?>" title="<?= $cVal['url'] ?>" <?if($main[progress_nodes]==$cVal['paras_value']):?>selected=selected<?endif;?>><?= $cVal['paras_desc'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <span <?if(empty($main[project_number])):?>hidden="hidden"<?endif;?> class="project_number_tr" style="color:black;float:left;line-height: 3;" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;涉及项目编号：</span>
                            <span <?if(empty($main[project_number])):?>hidden="hidden"<?endif;?> class="project_number_tr" style="float:left">
                            <input style="width:300px" class="layui-input mandatory" id="project_number" name="project_number" placeholder="ABC01,ABC02" autocomplete="off" lay-verify="required" value="<?=$main[project_number] ?>">
                            </span>
                            <span <?if(empty($main[project_number])):?>hidden="hidden"<?endif;?> class="project_number_tr" style="color:red;float:left;line-height: 3;" >（多个项目编号请使用英文逗号间隔，如：ABC01,ABC02)</span>
                            
                        </td>
                        
                    </tr>
                    <!-- <tr <?if(empty($main[project_number])):?>hidden="hidden"<?endif;?> id="project_number_tr">
                        <td class="TableContent">涉及项目编号</td>
                        <td class="TableData">
                            <pre style="color:red">多个编号请换行填写，比如项目代号是ABC，涉及项目编号为ABC01，ABC02，填写如下</pre>
                            <textarea class="layui-textarea mandatory" id="project_number" name="project_number" placeholder="ABC01&#10;ABC02" autocomplete="off" lay-verify="required"><?=$main[project_number] ?></textarea>
                        </td>
                    </tr> -->
                    <tr>
                        <td class="TableContent">进度描述</td>
                        <td class="TableData">
                            <pre id="progress_nodes_desc" style="color:red"></pre>
                            <textarea class="layui-textarea mandatory" id="nodes_desc" name="nodes_desc" placeholder="请根据红色提示内容，详细描述项目情况" autocomplete="off" lay-verify="required"><?=$main[nodes_desc] ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent">附件</td>
                        <td class="TableData">
                        <input id="project_schedule_nodes_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
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
                $.initTextarea();
               // $.initAttach();
               if($("#form_id").val()){
                var attachObj = <?= $attachObj?>;
                $.initAttachList(attachObj);
               }
               
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
                        var errMsg=reJson.msg?reJson.msg:btnVal + '失败！';
                        layer.alert(errMsg, {
                            icon: 5
                        });
                    }
                });
            });

            form.on('select(progress_nodes)', function(data) {
                //console.log(data.value);
                $("#progress_nodes_desc").text($(data.elem).find("option:selected").attr("title"));
                if($(data.elem).find("option:selected").attr("extra")=="2"){
                    $(".project_number_tr").show();
                    $("#project_number").attr("disabled",false);
                    $("#project_number").attr("lay-verify","required");
                    
                }else{
                    $(".project_number_tr").hide();
                    $("#project_number").attr("disabled",true);
                    $("#project_number").attr("lay-verify","");
                    
                }
            });
            form.on('select(region_id)', function(data) {
                $("#region").val($(data.elem).find("option:selected").text());
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
                        console.log(reJson.code);
                        if (reJson.data.code == '200') {
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