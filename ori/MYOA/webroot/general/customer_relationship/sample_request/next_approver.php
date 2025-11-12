<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("下一步审核");
include_once("inc/header.inc.php");
include_once("common/Common.php");
require_once("../common/ProcessModel.php");
require_once("../common/FormModel.php");
require_once("../common/DataModel.php");
$dataModel = new DataModel();
$processModel = new ProcessModel();
$formModel = new FormModel();
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$time = date('Y-m-d H:i:s');
$param = $processModel->getParamToArray();
// FIX:若选择不同意但点击提交操作时，视为退回操作，获取退回步骤
$ifAgree = "";

$param2=$param;
$flowArr = $processModel->getFlowStep($param2['type'], $param2['user_bu']);

$approveInfo = $processModel->findApproveInfo($param2);
if(count($approveInfo)==0){
    Message("", "流程已提交，请勿重复操作！");
    echo '<div align=center>
    <input type="button" value="关闭" class="BigButton" title="关闭窗口" onclick="window.parent.close();">
    </div>';
    exit;
}

$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'sample_no_reason,sample_no_reason_role'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$sample_no_reason_role=array();
foreach ($selectArr['sample_no_reason_role'] as $v) {
    $sample_no_reason_role[]=$v['paras_desc'];
}

$ifAgree = $approveInfo['if_agree'];

// TODO: 若退回某步骤后，修改提交是否可直接跳过已审核步骤人员，待确定
$approverArr = array();
if ($param['actionType'] == 'Back' || $ifAgree == FormModel::DISAGREE) {
    $actionType = '退回';
    $approverArr = $processModel->getBackStep($param);
} else {
    $actionType = '提交';
    $approverArr = $processModel->nextApprover($param);
}
// 获取当前审核角色
$currApproverData = $processModel->getApprover($param);
$approveRole = $currApproverData[0]['role'];
$param['tableName'] = 'inhe_sample_request';
$formData = $formModel->query($param);
$data = $formData[0];
?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script src="/inc/js/module.js"></script>
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

    
    if($currApproverData[0]['step']==900){
        $sql = " update inhe_sample_request set status='E'  where form_id = '$param[form_id]' ";
        exequery(TD::conn(), $sql);  
        header("Location: ./finish_page2.php");
        exit;
    }else{
        if($approveRole=="采购外发"){
            $sql = " insert into inhe_flow_opinion (type, form_id,title, user_id,write_time, step, work_flow, approve_user,approve_time, status,prev_writer,prev_time, user_bu) 
            values ('sample_request','$data[form_id]','$data[title]','$_SESSION[LOGIN_USER_ID]','$time','800','申请料号','$data[user_id]','','U','$_SESSION[LOGIN_USER_ID]','$time','$data[user_bu]')";
            exequery(TD::conn(), $sql);
            $REMIND_URL = "/customer_relationship/sample_request/list.php?form_id=" . $data[form_id];
            $SMS_CONTENT = $data[title] . _("采购外发结束，可申请料号！");
            send_sms($REMIND_TIME, $_SESSION["LOGIN_USER_ID"], $data[user_id], 0, $SMS_CONTENT, $REMIND_URL);
        }
        
        $sql = "update inhe_sample_request set status = 'Y' where status in('R','P') and  form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $sql);
        header("Location: ./finish_page.php");
        exit;
    }

    
    // echo '<br />';
    // Message("提示", "流程结束");
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
        <input type="hidden" name="title" id="title" value="<?= $data['title'] ?>" />
        <input type="hidden" name="request_id" id="request_id" value="<?= $data['id'] ?>" />
        <input type="hidden" name="opinion_id" id="opinion_id" value="<?= $approveInfo['id'] ?>" />
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend><?= $actionType ?>任务</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">下一步骤</label>
            <?php foreach ($approverArr as $app) {

                  //多步骤选择才判断
                  if(isset($approverArr[1])&&$actionType == '提交'){
                    //按顺序，只要出现某类产品大于0，则后面步骤不在显示，前面步骤如果在设置的四类技术评审内则不显示，前面存在其他步骤会显示
                    //TODO 如果需要仅仅只显示某一个步骤，则需循环判断取出符合条件的步骤，然后通过不等于则跳过其他所有步骤，类似上面判断的商务总监步骤
                    $role_names = array_column($approverArr, 'role');
                    $param['结构出图']=in_array('结构出图',$role_names)?($data[material_flow]=='03'?0:1):0;//变压器跳过
                    $param['电子复核']=in_array('电子复核',$role_names)?($data[material_flow]=='02'?0:1):0;//结构件跳过
                    $param['采购经理']=in_array('采购经理',$role_names)?1:0;
                    

                    if (isset($param[$app['role']])&&$param[$app['role']]>0) {
                        echo '<div class="layui-input-block">
                                <input type="radio" name="step" value="'.$app['step'].'" title="'.$app['role'].'" data-type="'.$app['operate'].'" data-approver="'.$app['approver'].'" lay-filter="step" />
                            </div>';
                        break;
                    }

                    if (isset($param[$app['role']])&&$param[$app['role']]<= 0) {
                        continue;
                    }
                    if($data['user_bu']=="WITLINK"&&$approveRole == '董事长'){
                        if ($param['EP'] == '01' && $app['role'] == '备案') {
                            continue;
                        }
                        if($param['EP'] != '01' && $app['role'] != '备案') {
                            continue;
                        }
                    }
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
        <?if($actionType == '退回'&&in_array($approveInfo[work_flow],$sample_no_reason_role)):?>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">退回原因</label>
            <div class="layui-input-inline">
                <select id="sample_no_reason" name="sample_no_reason" lay-filter="sample_no_reason" lay-search lay-verify="required">
                    <option value="">请选择</option>
                    <?php foreach ($selectArr['sample_no_reason'] as $val) : ?>
                        <option value="<?= $val['paras_value'] ?>"><?= $val['paras_desc'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?endif;?>
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
                 // 单击之后提交按钮不可选,防止重复提交
                $('[name="btn_submit"]').addClass('layui-btn-disabled');
                $('[name="btn_submit"]').attr('disabled', 'disabled');
                $.post(url, $("#mainForm").serializeArray(), function(data) {
                    var reJson = null;
                    try {
                        reJson = eval('(' + data + ')');
                    } catch (e) {
                        $('[name="btn_submit"]').removeClass('layui-btn-disabled');
                        $('[name="btn_submit"]').removeAttr('disabled');
                        layer.alert('服务器异常: ' + e, {
                            icon: 5
                        });
                        return;
                    }
                    if (reJson.code == "200") {
                        layer.alert(btnVal + "成功!", {
                            icon: 1
                        }, function(index, layero) {
                            parent.layer.close(index);
                            parent.closeIframe();
                            parent.location.reload();
                            // parent.opener.location.reload();
                        });
                    } else {
                        $('[name="btn_submit"]').removeClass('layui-btn-disabled');
                         $('[name="btn_submit"]').removeAttr('disabled');
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
                    SelectUserSingle('','user_id', 'approver_list')
                    //getUserSelect('approver_list');
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
                                $("#user_name").append("<option value='" + obj.data[0].user_id + "' selected>" + obj.data[0].user_name + "</option>");
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