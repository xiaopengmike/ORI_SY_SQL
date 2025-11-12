<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("下一步审核");
include_once("inc/header.inc.php");
include_once("common/Common.php");
require_once("../common/ProcessModel.php");
require_once("../common/FormModel.php");
$processModel = new ProcessModel();
$formModel = new FormModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$time = date('Y-m-d H:i:s');

$param = $processModel->getParamToArray();
$ifAgree = "";
$param2=$param;
$param['tableName'] = 'inhe_shipping_notice';
$formData = $formModel->query($param);
$data = $formData[0];
//成都走电力流程特殊处理
$flow_manage_param=$param;
if($data['user_bu']=="INHE"&&$data['create_user_bu']=="CDINHE"){
    $flow_manage_param[user_bu]=$data['user_bu']."_".$data['create_user_bu'];
}
$flowArr = $processModel->getFlowStep($param2['type'], $param2['user_bu']);
//流程设置中董事长前面一个步骤为必须经过步骤,回退一步判断
// if(strpos($param2['form_id'],strtolower($param2['user_bu']).'_')===0&&$flowArr[$param2[step]]['approver']=='meterw'){
//     $param2[step]=$param2[step]-1;
//     $currApproverData2 = $processModel->getApprover($param2);
//     $approveRole2 = $currApproverData2[0]['role'];
// }
$approveInfo = $processModel->findApproveInfo($param2);

if(count($approveInfo)==0){
    Message("", "流程已提交，请勿重复操作！");
    echo '<div align=center>
    <input type="button" value="关闭" class="BigButton" title="关闭窗口" onclick="window.parent.close();">
    </div>';
    exit;
}
$ifAgree = $approveInfo['if_agree'];
$approverArr = array();
if ($param['actionType'] == 'Back' || $ifAgree == FormModel::DISAGREE) {
    $actionType = '退回';
    $approverArr = $processModel->getBackStep($param);
} else {
    $actionType = '提交';
    $approverArr = $processModel->nextApprover($flow_manage_param);
}
// 获取当前审核角色
$currApproverData = $processModel->getApprover($flow_manage_param);
$approveRole = $currApproverData[0]['role'];
$currApproverData2=empty($currApproverData2)?$currApproverData:$currApproverData2;

$sql = " select id,company,user_bu,zjl from company where  zjl!='meterw'";
$res = exequery(TD::conn(), $sql);
while ($item = mysql_fetch_assoc($res)) {
    $company_zjl[$item[user_bu]] = $item[zjl];
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="main.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
</head>

<?php
if (empty($approverArr) || count($approverArr) == 0) {
    // 无下一步即最后一步时
    $flowParam = array(
        'form_id' => $param['form_id'],
        'type' => $param['type'],
        'prev_writer' => $userId,
        'prev_time' => $time
    );
    $processModel->updateFlowData($flowParam);

    $sql = "update inhe_shipping_notice set status = 'Y' where form_id = '" . $param['form_id'] . "' ";
    exequery(TD::conn(), $sql);

    header("Location: ../common/list/finish_page.php");
    exit;
}



?>

<body>
    <form id="mainForm" class="layui-form">
        <input type="hidden" name="actionType" id="actionType" value="<?= $param['actionType'] ?>" />
        <input type="hidden" name="systemId" id="systemId" value="<?= $param['type'] ?>" />
        <input type="hidden" name="form_type" id="form_type" value="update_approver" />
        <input type="hidden" name="form_id" id="form_id" value="<?= $param['form_id'] ?>" />
        <input type="hidden" name="prev_step" id="prev_step" value="<?= $param['step'] ?>" />
        <input type="hidden" name="user_bu" id="user_bu" value="<?= $data['user_bu'] ?>" />
        <input type="hidden" name="create_user_bu" id="create_user_bu" value="<?= $data['create_user_bu'] ?>" />
        <input type="hidden" name="title" id="title" value="<?= $data['title'] ?>" />
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend><?= $actionType ?>任务</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">下一步骤</label>
            <!-- 发货：跟单组长审核.是否符合条件="02"->市场总监->会计->风控->财务->江西；跟单组长审核.是否符合条件="01"->会计->财务经理；
                跟单组长审核.是否符合条件="01" 与 财务经理审批.是否董事长审核="01" 财务->董事长
                跟单组长审核.是否符合条件="01"  与 财务经理审批.是否董事长审核<>"01" 财务->备案 -->
            <?php foreach ($approverArr as $app) {
                if ($approveRole == '跟单组长'&&$actionType == '提交'&&in_array($data['user_bu'],array('INHE'))) {
                    if ($param['if_match_contract'] == '01' && ($app['role'] == '业务负责人')) {
                        continue;
                    } else if ($param['if_match_contract'] == '02' && $app['role'] == '销售会计') {
                        continue;
                    }
                }
                
                // if ($approveRole == '销售会计'&&$actionType == '提交'&&in_array($data['user_bu'],array('INHE','INHEIAC'))) {
                //     if ($param['if_match_contract'] == '01' && $app['role'] == '风险控制与项目融资部') {
                //         continue;
                //     } else if ($param['if_match_contract'] == '02' && $app['role'] == '财务经理'&&$currApproverData[0]['next_step']!==$app['step']) {
                //         continue;
                //     }
                // }
                // if ($approveRole == '财务经理'&&$actionType == '提交'&&in_array($data['user_bu'],array('INHE','INHEIAC'))) {
                //     if ($param['if_match_contract'] == '02' && $app['role'] != '江西总经理') {
                //         continue;
                //     } else if ($param['if_match_contract'] == '01') {
                //         if ($param['if_chief_shipping'] == '01' && $app['role'] != '董事长') {
                //             continue;
                //         } else if ($param['soft_count']==0&&$param['if_chief_shipping'] != '01' && $app['role'] != '备案') {
                //             continue;
                //         } else if ($param['soft_count']==1&&$param['if_chief_shipping'] != '01' && $app['role'] != '慧联备案') {
                //             continue;
                //         }
                //     }
                // }
                // if ($approveRole == '风控'&&$actionType == '提交'&&in_array($data['user_bu'],array('INHE','INHEIAC'))) {
                //     if (($param['if_match_contract'] == '02'||$param['if_chief_shipping'] == '02') && ($app['role'] == '江西总经理')) {
                //         continue;
                //     } else if (($param['if_match_contract'] == '01'&&$param['if_chief_shipping'] == '01')&&$app['role'] != '江西总经理') {
                //         continue;
                //     }
                // }
                 if ($approveRole == '风控'&&$actionType == '提交'&&in_array($data['user_bu'],array('INHE','INHEIAC'))) {
                    if ($E!=1 && ($app['role'] == '江西总经理')) {
                        continue;
                    } else if ($E==1 &&$app['role'] != '江西总经理') {
                        continue;
                    }
                }
               
                if (($approveRole == '江西总经理'||$approveRole == '成都董事长')&&$actionType == '提交'&&in_array($data['user_bu'],array('INHE','INHEIAC'))) {
                    if (($param['if_match_contract'] == '02'||$param['if_chief_shipping'] == '02') && ($app['role'] == '备案'||$app['role'] == '慧联备案')) {
                        continue;
                    } else if (($param['if_match_contract'] == '01'&&$param['if_chief_shipping'] == '01')) {
                        if ($param['soft_count']==0 && ($app['role'] != '备案')) {
                            continue;
                        } else if ($param['soft_count']==1&&$app['role'] != '慧联备案') {
                            continue;
                        }
                    }
                }

                if ($approveRole == '董事长'&&$actionType == '提交'&&in_array($data['user_bu'],array('INHE','INHEIAC'))) {
                    if ($param['soft_count']==0 && ($app['role'] != '备案')) {
                        continue;
                    } else if ($param['soft_count']==1&&$app['role'] != '慧联备案') {
                        continue;
                    }
                }
                

                // if($actionType == '提交'&&strpos($param2['form_id'],strtolower($param2['user_bu']).'_')===0&&$currApproverData2[0]['auto_approver']=="1"&&$currApproverData2[0]['operate']!='record'){
                //     $role_names = array_column($approverArr, 'role');
                //     if($app['role'] != '备案'&&in_array('备案',$role_names)) {
                //         continue;
                //     }
                //     if($app['operate']=='record'){
                //         //备案流程
                //         $approve_user='';
                //         $opinionSql=" select * from  user u where 
                //         exists(select * from inhe_flow_manage where type='$data[type]' and user_bu='$data[user_bu]'  
                //             and notice_user is not null
                //             and ((find_in_set(u.user_id,substring_index(approver,',',1)) and if_show='1' and notice_user='1') or  (find_in_set(u.user_id,approver) and operate='record' and next_step=''))) 
                //         and u.user_id!='meterw' and not exists(select * from inhe_flow_opinion where form_id='$data[form_id]' and approve_user=u.user_id) ";
                //         $res4=exequery(TD::conn(), $opinionSql); 
                //         while ($item = mysql_fetch_assoc($res4)) {
                //             $approve_user.=$item[USER_ID].",";
                //         }
                //         $app['approver']=$approve_user;
                //      }
                // }
                //增加产品经理备案
                if(strpos($app['role'],"备案")!==false&&!empty($data[customer_service])){
                    $app['approver']=$data[customer_service].",".$app['approver'];
                }
                
                if(strpos($app['role'],"备案")!==false&&$data['user_bu']=="INHE"&&$G==1&&!empty($company_zjl[INHEGRID])){
                    //
                    $app['approver']=$company_zjl[INHEGRID].",".$app['approver'];
                }

                
            ?>
                <div class="layui-input-block">
                    <input type="radio" name="step" value="<?= $app['step'] ?>" title="<?= $app['role'] ?>" data-type="<?= $app['operate'] ?>" data-approver="<?= $app['approver'] ?>" lay-filter="step" />
                </div>
            <?php } ?>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">待办人员</label>
            <div class="layui-input-block" id="user_select" style="width: 150px;display: none">
                <select id="user_name" name="user_name" lay-filter="user_name">
                    <option value=""></option>
                </select>
            </div>
            <br />
            <div class="layui-input-block">
                <input type="hidden" data-type="user_id" name="user_id" id="user_id" />
                <textarea placeholder="显示指定待办人员" class="layui-textarea" id="approver_list" name="approver_list" readonly></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input lay-submit lay-filter="submit" name="btn_submit" actionType="Submit" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
            </div>
        </div>
    </form>
</body>

<script type="text/javascript">
    $(function() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;

            $.each($("textarea"), function(i, n) {
                $(n).css("height", n.scrollHeight + "px");
                $(n).css("width", "80%");
                $(n).css("float", "left");
                $(n).css("resize", "none");
                $(n).css("overflow", "hidden");
            });

            form.on('submit(submit)', function(data) {
                var btnVal = data.elem.value;
                var actionType = $(data.elem).attr("actionType");
                var url = "action.php?action=" + actionType;
                var userList = $("#user_id").val();
                if (ifEmpty(userList)) {
                    layer.msg('未指定下一步待办人员！');
                    return false;
                }
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
                            parent.layer.close(index); //再执行关闭
                            parent.closeIframe();
                            parent.location.reload();
                            // parent.opener.location.reload();
                        });
                    } else {
                        layer.alert(btnVal + '失败！', {
                            icon: 5
                        });
                    }
                });
            });

            form.on('select(user_name)', function(data) {
                $("#user_id").val(data.value);
                $("#approver_list").val(data.elem[data.elem.selectedIndex].text);

                form.render();
            });

            form.on('radio(step)', function(data) {
                var approver = $(this).data('approver');
                var type = $(this).data('type');
                var step = data.value;
                $("#user_select").hide();
                $("#user_id").val('');
                $("#approver_list").val('');
                // 分3种情况：1、指定1人； 2、指定多人 3、选择一人
                if (approver == 'dept_select') {
                    getUserSelect('approver_list');
                } else {
                    var url = "action.php?action=Retrieve";
                    $.post(url, {
                        dataType: 'getApproverList',
                        approver: approver,
                        type: type
                    }, function(data) {
                        var obj = eval('(' + data + ')');
                        if (type == 'record') {
                            var userNameList = userIdList = "";
                            for (var se in obj.data) {
                                userNameList += obj.data[se].user_name + ','; // 单选
                                userIdList += obj.data[se].user_id + ',';
                            }
                            returnUser(userNameList, userIdList, 'approver_list');
                        } else {
                            $("#user_select").show();
                            $("#user_name").empty();
                            var selected = "";
                            if (obj.data.length == 1) {
                                // 仅有一个选项时默认带出值
                                $("#user_name").append("<option value='" + obj.data[0].user_id + "' " + selected + ">" + obj.data[0].user_name + "</option>");
                                $("#user_id").val(obj.data[0].user_id);
                                $("#approver_list").val(obj.data[0].user_name);
                                form.render();
                            } else {
                                $("#user_name").append("<option value=''>请选择</option>");
                                for (i = 0; i < obj.data.length; i++) {
                                    $("#user_name").append("<option value='" + obj.data[i].user_id + "' " + selected + ">" + obj.data[i].user_name + "</option>");
                                }
                            }
                        }

                        form.render();
                    });
                }
            });

            form.render();
        });
    });

    function getUserSelect(domId) {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;

            layer.open({
                title: '选择人员',
                type: 2,
                skin: 'layui-layer-rim',
                area: ['75%', '80%'],
                fixed: false,
                maxmin: true,
                offset: '50px',
                content: '../common/list/single_type_list.php?domId=' + domId,
                moveOut: true
            });
            form.render();
        });
    }

    function returnUser(userName, userId, domId) {
        $("#" + domId).val(userName);
        $("#" + domId).parent().find("[data-type='user_id']").val(userId); //取userId隐藏栏位赋值
    }
</script>

</html>