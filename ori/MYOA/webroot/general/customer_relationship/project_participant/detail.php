<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("项目参与人登记表");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");
include_once("../common/ProcessModel.php");
include_once("../common/UserModel.php");
require_once '../common/DataModel.php';
$model = new UserModel();
$dataModel = new DataModel();

$systemId = "project_participant";
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
$conditionParam = array(
    'step' => $step,
    'form_id' => $id,
    'approve_user' => $userId,
);
$process = new ProcessModel();
// 获取审核数据
$approveData = $process->getApproveData($param);
$flowArr = $process->getFlowStep($systemId, $main['user_bu']);
$curOperate = $flowArr[$step]['operate'];
$backRecord = $process->findBackRecord($param);
$passRecord = $process->findPassRecord($param);

$conditionParam = array(
    'step' => $step,
    'form_id' => $id,
    'approve_user' => $userId,
);

$approveInfo = $process->findApproveInfo($conditionParam);
if (count($approveInfo) <= 0) {
    $operate = 'detail';
    $step = '';
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
    <script language="Javascript" type="text/javascript" src="../detail.js?v.20220312"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" href="../crm.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <script type="text/javascript">
        $(function() {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                    $.initPage('<?= $operate ?>');
                $.verifyChecked();
                $.formSubmit();
                form.render();
                // form.on('submit(submit)', function(data) {
                //     var btnVal = data.elem.value;
                //     var actionType = $(data.elem).attr("actionType");
                //     var url = "action.php?action=" + actionType;
                //     var formId = $("#form_id").val();
                //     getNextApprover(formId, actionType);
                //     return false;
                // });

                // form.render();
            });
        });

        // 页面初始化加载
        function init() {
            $('#mainForm').find('input,textarea,select,td[class="TableData"]').not('[actionType="Approve"],[actionType="Back"],[actionType="Close"],[type="button"]').attr('style', 'color:#0066cc');
            if ('<?= $operate ?>' == 'detail') {
                $('#mainForm').find('input,textarea,select').not('[actionType="Approve"],[actionType="Back"],[actionType="Close"]').prop('disabled', true);

                // layui下拉只读显示不置灰
                $.each($("select"), function(i, n) {
                    var optText = $(n).find("option:selected").text();
                    $(n).next().find("input").val(optText);
                    $(n).remove();
                    $('dl').remove();
                });
            } else if ('<?= $operate ?>' == 'approve') {
                $('#tab1,#tab2,#tab3').find('input,textarea,select').not('[actionType="Approve"],[actionType="Back"],[actionType="Close"]').prop('disabled', true);
            }
        }

        function getNextApprover(formId, actionType) {
            var step = $('#step').val();
            var user_bu = $('#user_bu').val();
            var type = $('#type').val();
            layer.open({
                title: "",
                type: 2,
                area: ['600px', '80%'],
                content: "next_approver.php?step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&actionType=" + actionType + "&type=" + type,
                success: function(layero, index) {
                    setTimeout(function() {
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    }, 500)
                },
                cancel: function() {
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.location.reload();
                    parent.layer.close(index);
                }
            });
        }
    </script>
</head>

<body>
<form id="mainForm" class="layui-form">
        <div style="width: 100%;height:auto;margin:auto;">
            <div class="r" style="height: auto;" <?php if ($isLink) : ?>style="display: none;" <?php endif; ?>>
                <div style="text-align: left;" class="layui-btn-group">
                    <table id="tab2" width="100%">
                        <tr class="layui-bg-body layui-bg-gray" id="tHeader">
                            <td style="padding: 5px;">
                                <?php if ($operate == 'approve' && count($approveInfo) > 0) : ?>
                                    <input lay-submit lay-filter="submit" name="btn_submit" actionType="Approve" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <?php if ($curOperate == 'approve') : ?>
                                        <input lay-submit lay-filter="submit" name="btn_back" actionType="Back" value="退回" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($main['status'] == 'Y' || $main['status'] == 'R' || $main['status'] == 'E'||strpos($approveInfo['work_flow'], '备案') !== false) : ?>
                                    <button type="button" class="layui-btn layui-btn-sm layui-btn-oa" onclick="$.printDetail();return false;">打印</button>
                                <?php endif; ?>
                                <input name="btn_close" value="关闭" type=button actionType="Close" class="layui-btn layui-btn-oa layui-btn-sm" onclick="closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
    <div class="weadmin-body" style="width:80%;margin:1% 9%;">
        
            <input type="hidden" name="type" id="type" value="<?= $main['type'] ?>" />
            <input type="hidden" name="step" id="step" value="<?= $step ?>" />
            <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
            <input type="hidden" name="form_id" id="form_id" value="<?= $id ?>" />
            <table width="100%" align="center">
                        <td align="center">
                            <img alt="" src="../../../../../images/<?= $main['user_bu'] ?>/<?= $main['type'] ?>.jpg" style="width: 34%; height: auto;" title="" />
                        </td>
                    </table>
                    <br />
            <table class="TableBlock" width="100%" align="center">
                <tr>
                    <td colspan="6">
                        <table id="tab1" width="100%">
                        <tr>
                            <td nowrap class="TableContent" width="20%">申请人</td>
                            <td nowrap class="TableData">
                            <?= $main['createUserName'] ?>
                            </td>
                            <td nowrap class="TableContent" width="20%">申请人部门</td>
                            <td nowrap class="TableData">
                            <?= $main['deptName'] ?>
                            </td>
                        </tr>
                            <tr>
                                <td nowrap class="TableContent">项目名称</td>
                                <td nowrap class="TableData">
                                    <?= $main['project_code'] ?>
                                </td>
                                <td nowrap class="TableContent">客户名称</td>
                                <td nowrap class="TableData">
                                    <?= $main['customer_name'] ?>
                                </td>
                            </tr>
                        </table>
                        <!-- <table id="tab2" width="100%">
                            
                            <tr align="center">
                                <td nowrap class="TableContent" colspan="2">项目负责人</td>
                                <td nowrap class="TableContent" colspan="2">比例</td>
                            </tr>
                            <tr align="center">
                                <td class="TableData" colspan="2">
                                    <?= $main['leader'] ?>
                                </td>
                                <td class="TableData" colspan="2">
                                    <?= $main['proportion'] ?>
                                </td>
                            </tr>
                            
                            <tr align="center">
                                <td nowrap class="TableContent" width="15%">序号</td>
                                <td nowrap class="TableContent" width="35%">项目参与人</td>
                                <td nowrap class="TableContent">比例</td>
                            </tr>
                            <?php foreach ($detail as $val) : ?>
                                <tr align="center">
                                    <td nowrap class="TableData" width="15%"><?= $val['serial_number'] ?></td>
                                    <td nowrap class="TableData" width="35%"><?= $val['user_name'] ?></td>
                                    <td nowrap class="TableData"><?= $val['proportion'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="layui-bg-body">
                                <td colspan="4" align="center" style="padding: 5px;">
                                    <?php if ($operate == 'approve' && count($approveInfo) > 0) : ?>
                                        <input lay-submit lay-filter="submit" name="btn_approve" actionType="Approve" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                        <input lay-submit lay-filter="submit" name="btn_back" actionType="Back" value="退回" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <?php endif; ?>
                                    <input name="btn_close" value="关闭" type=button actionType="Close" class="layui-btn layui-btn-oa layui-btn-sm" onclick="closeIframe();return false;" />
                                </td>
                            </tr>
                        </table> -->
                        <table id="tab3" width="100%">
                                        <tr>
                                            <td class="TableContent" width="20%">备案</td>
                                            <td class="TableData" >
                                            
                                                <textarea class="layui-textarea" id="remark" name="remark" placeholder="备注" readonly><?=$main[remark]?></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php
                                        if ($main['status']=="Y"&&$operate=='detail') {
                                            include_once("../approve_opinion.php");
                                        }else{
                                            include_once("./approve_opinion.php");
                                        }
                                    ?>
                                    <?php
                                    if (count($backRecord) > 0) {
                                        include_once("../back_record.php");
                                    }
                                    ?>
                                    <?php
                                    if (count($passRecord) > 0) {
                                        include_once("../pass_record.php");
                                    }
                                    ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    </form>
</body>

</html>