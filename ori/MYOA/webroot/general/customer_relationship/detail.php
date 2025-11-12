<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_org.php");
$HTML_PAGE_TITLE = "立项申请表";
require_once("inc/header.inc.php");
require_once("common/Common.php");
require_once("ActionModel.php");
require_once("../common/AttachModel.php");
require_once("../common/ProcessModel.php");
require_once("../common/UserModel.php");
require_once("../common/DataModel.php");
$attachModel = new AttachModel();
$processModel = new ProcessModel();
$userModel = new UserModel();
$dataModel = new DataModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());
$systemId = "project_review";
if (strpos($id, 'bg') !== false) {
    $systemId .= "_change";
}
$userInfo = $userModel->getUserInfo($userId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,check_opinion,project_type,yes_or_no',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$psArr = array();
foreach ($selectArr['project_star'] as $ps) {
    $psArr[$ps['paras_value']] = $ps;
}
$checkArr = array();
foreach ($selectArr['check_opinion'] as $co) {
    $checkArr[$co['paras_value']] = $co;
}
$project_type = array();
foreach ($selectArr['project_type'] as $v) {
    $project_type[$v['paras_value']] = $v['paras_desc'];
}
$yes_or_no = array();
foreach ($selectArr['yes_or_no'] as $v) {
    $yes_or_no[$v['paras_value']] = $v['paras_desc'];
}
$project_star = array();
foreach ($selectArr['project_star'] as $v) {
    $project_star[$v['paras_value']] = $v['paras_desc'];
}

$action = new ActionModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$param['id'] = $id;
//获取变更记录
$changeData=$dataModel->getNewChange($id,str_replace('_change', '', $systemId));
$data = $action->queryById($param);
$main = $data['main'];
$customerData = $data['customer'];
$project = $data['project'];
$projectDetail = $data['projectDetail'];
$bidding = $data['bidding'];
// 获取审核数据
$approveData = $processModel->getApproveData($param);
$attachObj = $attachModel->getAttachById($id);
$customerSelect = $dataModel->getCustomerList();
$flowArr = $processModel->getFlowStep($systemId, $main['user_bu']);
$curOperate = $flowArr[$step]['operate'];
$backRecord = $processModel->findBackRecord($param);
$conditionParam = array(
    'step' => $step,
    'form_id' => $id,
    'approve_user' => $userId,
);
$approveInfo = $processModel->findApproveInfo($conditionParam);
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
    <script language="Javascript" type="text/javascript" src="../detail.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <link rel="stylesheet" href="../crm.css">
    <script type="text/javascript">
        $(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer', 'attach'], function() {
                var layer = layui.layer,
                    form = layui.form,
                    attach = layui.attach;
                $.initPage('<?= $operate ?>');
                $.verifyChecked();
                $.formSubmit();
                var attachObj = <?= $attachObj ?>;
                $.initAttachList(attachObj);
                $.projectStarSelect();
                form.render();
            });
        });

        function getNextApprover(formId, actionType) {
            var step = $('#step').val();
            var user_bu = $('#user_bu').val();
            var type = $('#type').val();
            layer.open({
                title: "",
                type: 2,
                area: ['600px', '600px'],
                content: "next_approver.php?step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&actionType=" + actionType + "&type=" + type,
                success: function(layero, index) {
                    setTimeout(function() {
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    }, 500);
                }
            });
        }
    </script>
</head>

<body>
    <div style="width: 100%;height:auto;margin:auto;">
        <form id="mainForm" class="layui-form">
            <div class="r" style="height: auto;">
                <div style="text-align: left;" class="layui-btn-group">
                    <table id="tab" width="100%">
                        <tr class="layui-bg-body">
                            <td style="padding: 5px;">
                                <?php if ($operate == 'approve' && count($approveInfo) > 0) : ?>
                                    <input lay-submit lay-filter="submit" name="btn_submit" actionType="Approve" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <?php if ($curOperate == 'approve') : ?>
                                        <input lay-submit lay-filter="submit" name="btn_back" actionType="Back" value="退回" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <?php endif; ?>
                                <?php endif; ?>
                                <input name="btn_close" value="关闭" type=button actionType="Close" class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.closeIframe();" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="width:80%;margin:1% 9%;">
                <input type="hidden" name="type" id="type" value="<?= $main['type'] ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $id ?>" />
                <input type="hidden" name="step" id="step" value="<?= $step ?>" />
                <table width="100%" align="center">
                    <tr>
                        <td align="center">
                            <img alt="" src="../../../../../images/<?= $main['user_bu'] ?>/<?= $main['type'] ?>_<?= $main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>
                                填表说明：<br />
                                1、选择联系人，可自动带出客户信息登记表中“客户名称”所对应的“客户所在国”、“联系人”“联系人电话”“联系人电邮”“客户背景”等字段，并填入的本表相应字段中。系统自动填写后可以修改。
                            </p>
                        </td>
                    </tr>
                </table>
                <br />
                <table class="TableBlock" width="100%" align="center">
                    <tr>
                        <td class="TableContent" width="15%" align="right">立项部门：</td>
                        <td class="TableData" width="18%"><?= $main['deptName'] ?></td>
                        <td class="TableContent" width="15%" align="right">制单人：</td>
                        <td class="TableData" width="18%"><?= $main['createUserName'] ?></td>
                        <td class="TableContent" width="15%" align="right">日期：</td>
                        <td class="TableData"><?= $main['write_time'] ?></td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <?php include_once("detail/customer_info.php"); ?>
                            <?php include_once("detail/project_situation.php"); ?>
                            <?php include_once("detail/bidding_strategy.php"); ?>
                            <?php include_once("detail/approve_opinion.php"); ?>
                            <?php
                            if (count($backRecord) > 0) {
                                include_once("../back_record.php");
                            }
                            ?>
                        </td>
                    </tr>
                </table>
                <?php include_once("../common/list/change_html.php"); ?>
            </div>
        </form>
    </div>
</body>

</html>