<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("报价申请");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");
require_once '../common/AttachModel.php';
require_once '../common/ProcessModel.php';
require_once '../common/DataModel.php';
require_once '../common/UserModel.php';
require_once '../common/FormUserManageModel.php';
$attachModel = new AttachModel();
$process = new ProcessModel();
$dataModel = new DataModel();
$userModel = new UserModel();
$formUserManageModel = new FormUserManageModel();

$isHide=$formUserManageModel->isHide('quotation_apply');

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());
$systemId = "quotation_apply";
if (strpos($id, 'bg') !== false) {
    $systemId .= "_change";
}

$userInfo = $userModel->getUserInfo($userId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,check_opinion,currency,yes_or_no,quote_source,sale_model,company,price_terms',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$product_detail_types=array();
foreach ($selectArr['product_detail_type'] as $v) {
    $product_detail_types[]=$v['paras_value'];
}

$psArr = array();
foreach ($selectArr['project_star'] as $ps) {
    $psArr[$ps['paras_value']] = $ps;
}
$checkArr = array();
foreach ($selectArr['check_opinion'] as $co) {
    $checkArr[$co['paras_value']] = $co;
}
$currency = array();
foreach ($selectArr['currency'] as $cy) {
    $currency[$cy['paras_value']] = $cy;
}
$yes_or_no = array();
foreach ($selectArr['yes_or_no'] as $yn) {
    $yes_or_no[$yn['paras_value']] = $yn;
}
$quoteSource = array();
foreach ($selectArr['quote_source'] as $qs) {
    $quoteSource[$qs['paras_value']] = $qs;
}
$sale_model = array();
foreach ($selectArr['sale_model'] as $sm) {
    $sale_model[$sm['paras_value']] = $sm;
}
$other_condition = array();
foreach ($selectArr['other_condition'] as $v) {
    $other_condition[$v['paras_value']] = $v;
}
$product_brand = array();
foreach ($selectArr['product_brand'] as $v) {
    $product_brand[$v['paras_value']] = $v;
}
$action = new ActionModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
//获取变更记录
$changeData=$dataModel->getNewChange($id,str_replace('rd_','',str_replace('trial_','',str_replace('_change', '', $systemId))));
$param['id'] = $id;
$data = $action->queryById($param);
$main = $data['main'];
$product = $data['product'];
$contract = $data['contract'];
$settle = $data['settle'];
$settleModel = $data['settleModel'];
// 获取审核数据
$approveData = $process->getApproveData($param);
$attachObj = $attachModel->getAttachById($id);

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
//var_dump($approveInfo);
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
    <link rel="stylesheet" href="../crm.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <script type="text/javascript">
        $(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $.initPage('<?= $operate ?>');
                $.verifyChecked();
                var attachObj = <?= $attachObj ?>;
                $.initAttachList(attachObj);
                $.formSubmit();
                form.render();
            });
        });

        function getNextApprover(formId, actionType) {
            var step = $('#step').val();
            var user_bu = $('#user_bu').val();
            var type = $('#type').val();
            var soft_count = $('#soft_count').val();
            var if_eligible = $('#if_eligible').val();
            var if_chief_contract = $('#if_chief_contract').val();
            var if_not_full_payment_goods = $('#if_not_full_payment_goods').val();
            var if_not_full_payment = $('#if_not_full_payment').val();
            var param = "step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&actionType=" + actionType + "&type=" + type + "&soft_count=" + soft_count;
            param += "&if_eligible=" + if_eligible;
            param += "&if_chief_contract=" + if_chief_contract;
            param += "&if_not_full_payment_goods=" + if_not_full_payment_goods;
            param += "&if_not_full_payment=" + if_not_full_payment;
            layer.open({
                title: "",
                type: 2,
                area: ['600px', '600px'],
                content: "next_approver.php?" + param,
                success: function(layero, index) {
                    setTimeout(function() {
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    }, 500)
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
                                    <?php if ($curOperate == 'approve'&&strpos($approveInfo['work_flow'], '备案') === false) : ?>
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
            <!--startprint-->
            <div class="weadmin-body" style="width:96%;margin:1% 1% 1% 0%;">
                <input type="hidden" name="soft_count" id="soft_count" value="<?= count($product['soft']) ?>" />
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
                </table>
                <br />
                <table class="TableBlock" width="100%" align="center">
                    <tr>
                        <td>
                            <?php include_once("detail/main_info.php"); ?>
                            <?php include_once("detail/product_detail.php"); ?>
                            <?php
                                if ($main['status']=="Y"&&$operate=='detail') {
                                    include_once("../approve_opinion.php");
                                }else{
                                    include_once("detail/approve_opinion.php");
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
                <?php include_once("../common/list/change_html.php"); ?>
            </div>
            <!--endprint-->
        </form>
    </div>
</body>

</html>