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
$approveInfo = $processModel->findApproveInfo($param);
$ifAgree = $approveInfo['if_agree'];

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

    $sql = "update inhe_quotation_apply set status = 'Y' where form_id = '" . $param['form_id'] . "' ";
    exequery(TD::conn(), $sql);

    header("Location: ../common/list/finish_page.php");
    exit;
}

$param['tableName'] = 'inhe_quotation_apply';
$formData = $formModel->query($param);
$data = $formData[0];

$userData=array();
$sql = " select k.* from user k where k.user_id='".$data['user_id']."'";
$res = exequery(TD::conn(), $sql);
while ($item = mysql_fetch_assoc($res)) {
    $userData = $item;
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
        <input type="hidden" name="create_user" id="create_user" value="<?= $data['user_id'] ?>" />
        <input type="hidden" name="create_user_name" id="create_user_name" value="<?= $userData['USER_NAME'] ?>" />
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend><?= $actionType ?>任务</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">下一步骤</label>
            <!-- 合同评审：商务->软件产品(黄总)；是否为01->财务；其他->技术支持；if_chief_contract
            风控(商务经理是否符合条件="02"&&财务经理意见.是否需要董事长审核="01")->董事长；商务经理意见.是否符合条件="01"&&财务经理意见.是否需要董事长审核<>"01"->备案 -->
            <?php foreach ($approverArr as $app) {
                //耐吉生产流程只能退回生产流程步骤，流程修改注意判断该段代码是否需要修改步骤
                if($actionType=='退回' && $data['user_bu']=='INHENERGY' && $param['step']>=12 && $app['step']<12){
                    continue;
                }
                if ($approveRole == '商务总监') {
                    if ($param['if_eligible'] == '01' && $app['role'] != '财务会计') {
                        continue;
                    } else if ($param['if_eligible'] != '01' && $app['role'] == '财务会计') {
                        continue;
                    }
                    if ($param['soft_count'] <= 0 && $app['role'] == '软件事业部总经理') {
                        continue;
                    } else if ($param['soft_count'] > 0 && $app['role'] == '技术支持总监') {
                        continue;
                    }
                }
                if ($approveRole == '风险控制与项目融资部'&&$data['user_bu']!='INHENERGY') {
                    if ($param['if_eligible'] == '02' && $param['if_chief_contract'] == '01' && $app['role'] == '备案') {
                        continue;
                    }
                    if ($param['if_eligible'] == '01' && $param['if_chief_contract'] != '01' && $app['role'] == '董事长') {
                        continue;
                    }
                }
                if ($approveRole == '市场总监'&&$data['user_bu']=='INHENERGY' && $actionType=='提交' ) {
                    if($data['contract_type'] == 2){
                        //样机合同和全款提货不需要总经理审核，直接备案
                        if ($param['if_not_full_payment_goods'] == '02' && $app['role'] !== '备案') {
                            continue;
                        }
                        //样机合同和非全款提货需要总经理审核，不能直接备案
                        if ($param['if_not_full_payment_goods'] == '01' && $app['role'] !== '总经理') {
                            continue;
                        }
                    }else{
                        //非样机合同，批量合同
                        if ($app['role'] == '备案'||$app['role'] == '总经理') {
                            continue;
                        }
                       
                    }
                }
                if ($approveRole == '出纳'&&$data['user_bu']=='INHENERGY' && $actionType=='提交') {
                  
                    //全款支付不需要总经理审核，直接备案
                    if ($param['if_not_full_payment'] == '02' && $app['role'] !== '备案') {
                        continue;
                    }
                    //非全款支付需要总经理审核，不能直接备案
                    if ($param['if_not_full_payment'] == '01' && $app['role'] !== '总经理') {
                        continue;
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
                var create_user = $("#create_user").val();
                var create_user_name = $("#create_user_name").val();
                $("#user_select").hide();
                $("#user_id").val('');
                $("#approver_list").val('');
                // 分3种情况：1、指定1人； 2、指定多人 3、选择一人 4、选择申请人
                if (approver == 'applicant_select') {
                    returnUser(create_user_name, create_user, 'approver_list')
                }else if (approver == 'dept_select') {
                    getUserSelect('approver_list');
                }else {
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