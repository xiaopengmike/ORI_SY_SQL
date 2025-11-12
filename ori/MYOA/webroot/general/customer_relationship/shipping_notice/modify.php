<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("发货通知单");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");
require_once '../common/AttachModel.php';
require_once '../common/ProcessModel.php';
require_once '../common/DataModel.php';
require_once '../common/UserModel.php';
$attachModel = new AttachModel();
$process = new ProcessModel();
$dataModel = new DataModel();
$userModel = new UserModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());
$systemId = "shipping_notice";

$userInfo = $userModel->getUserInfo($userId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'check_opinion,currency,yes_or_no,quote_source,sale_model,other_condition,inhegrid_notice_type,license_product,license_type'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$checkArr = array();
foreach ($selectArr['check_opinion'] as $co) {
    $checkArr[$co['paras_value']] = $co;
}
$currency = array();
foreach ($selectArr['currency'] as $v) {
    $currency[$v['paras_value']] = $v['paras_desc'];
}
$yes_or_no = array();
foreach ($selectArr['yes_or_no'] as $v) {
    $yes_or_no[$v['paras_value']] = $v['paras_desc'];
}
$quote_source = array();
foreach ($selectArr['quote_source'] as $v) {
    $quote_source[$v['paras_value']] = $v['paras_desc'];
}
$sale_model = array();
foreach ($selectArr['sale_model'] as $v) {
    $sale_model[$v['paras_value']] = $v['paras_desc'];
}
$other_condition = array();
foreach ($selectArr['other_condition'] as $v) {
    $other_condition[$v['paras_value']] = $v['paras_desc'];
}
$notice_type = array();
foreach ($selectArr['inhegrid_notice_type'] as $v) {
    $notice_type[$v['paras_value']] = $v['paras_desc'];
}

$action = new ActionModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$param['id'] = $id;
$data = $action->queryById($param);
$main = $data['main'];
$company=$main[user_bu];
$product = $data['productDetail'];
// 获取审核数据
$attachObj = $attachModel->getAttachById($id);
$approveData = $process->getApproveData($param);
$flowArr = $process->getFlowStep($systemId, $main['user_bu']);
$curOperate = $flowArr[$step]['operate'];
$backRecord = $process->findBackRecord($param);
$currencyObj = json_encode($dataModel->array_iconv($currency));
//授权产品下拉列表
$license_product_html = '<div id="license_product_select" hidden="hidden">';
$license_product_html .= '<option value="">请选择</option>';
foreach ($selectArr['license_product'] as $v) {
    $license_product_html .= '<option value="' . $v['paras_value'] . '">' . $v['paras_desc'] . '</option>';
}
$license_product_html .= '</div>';

//授权方式
$license_type_html = '<div id="license_type_select" hidden="hidden">';
$license_type_html .= '<option value="">请选择</option>';
foreach ($selectArr['license_type'] as $v) {
    $license_type_html .= '<option value="' . $v['paras_value'] . '">' . $v['paras_desc'] . '</option>';
}
$license_type_html .= '</div>';


$sql = " select distinct d.* from inhe_contract_review k left join inhe_contract_detail d on k.form_id=d.form_id where k.form_id='$main[order_no]'";
$res = exequery(TD::conn(), $sql);
$contract=array();
if ($item = mysql_fetch_assoc($res)) {
    $contract = $item;
}
//存在变更，则取上次变更信息继续变更
if($contract['version']>0){
    $sql = " select distinct d.* from inhe_contract_review k left join inhe_contract_detail d on k.form_id=d.form_id
     where k.type='sales_contract_review_change' and k.version='$contract[version]' and k.related_id='$contract[form_id]' ";
    $res = exequery(TD::conn(), $sql);
    $contract=array();
    if ($item = mysql_fetch_assoc($res)) {
        $contract = $item;
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script src="/inc/js/module.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="main.js?v=20250307"></script>
    <script language="Javascript" type="text/javascript" src="../add.js"></script>
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
                $.initDate();
                $.initTextarea();
                var attachObj = <?= $attachObj ?>;
                $.initAttachList(attachObj);
                $.formSubmit();
                
                $("#customer_name,#order_no").click(function() {
                    if($("input[name=user_bu]").val()=="RETAILNERGY"){
                        return true;
                    }
                    var index = layui.layer.open({
                        title: "客户名称列表",
                        type: 2,
                        content: "customer_list.php",
                        area: ['950px', '650px'],
                        success: function(layero, index) {
                            setTimeout(function() {
                                layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                    tips: 3
                                });
                            }, 500)
                        }
                    });
                });
                changeNumber();
                lineInit();
                form.render();
            });
        });

        function returnOrderInfo(reData) {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                var currencyObj = <?= $currencyObj ?>;
                $("#customer_name").val(reData.customer_name);
                $("#contract_party").val(reData.contract_party);
                $("#shipment_place").val(reData.delivery_place);
                $("#order_no").val(reData.form_id);
                //TODO 慧软CRM合同评审$("#order_form_id").val(reData.orig_form_id);
                $("#project_number").val(reData.project_number);
                $("#currency").val(currencyObj[reData.currency]);
                $("#transport_mode").val(reData.transport_mode);
                $("#freight_bearing").val(reData.freight_bearing);
                $("#business_belong").val(reData.business_attribution);
                $("#business_dept").val(reData.business_dept);
                $("#have_entrepot_trade").val(reData.have_entrepot_trade);
                $("#involved_product").val(reData.involved_product);
                $("#contract_begin_time").val(reData.contract_begin_time);
                $("#contract_end_time").val(reData.contract_end_time);
                layer.closeAll();
                form.render();
            });
        }

        function findProductList(obj) {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                var formId = $("#order_no").val();
                var type = $("[name='" + obj.name + "']").data('type');
                var index = layui.layer.open({
                    title: "产品列表",
                    type: 2,
                    content: "product_list.php?type=" + type + "&form_id=" + formId + "&domId=" + obj.name,
                    area: ['950px', '650px'],
                    success: function(layero, index) {
                        setTimeout(function() {
                            layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                tips: 3
                            });
                        }, 500)
                    }
                });
                form.render();
            });
        }

        function returnProductInfo(reData, domId) {
            if($("input[name=user_bu]").val()=="RETAILNERGY"){
                        return true;
                    }
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                var type = $("[name='" + domId + "']").data('type');
                var i = 0;
                var length = reData.length;
                var typeArr = {};
                typeArr['electric'] = 'addEleDetail';
                typeArr['other'] = 'addOtherDetail';
                typeArr['soft'] = 'addSoftDetail';
                // TODO: 实现下拉多选回显列表
                for (i; i < length; i++) {
                    if (i == 0) {
                        $("#project_number").val(reData.project_number);
                        $("[name='" + domId + "']").parent().parent().find("[id='name_" + type + "']").val(reData[i].name);
                        $("[name='" + domId + "']").parent().parent().find("[id='specification_" + type + "']").val(reData[i].specification);
                        $("[name='" + domId + "']").parent().parent().find("[id='number_" + type + "']").val(reData[i].number);
                        $("[name='" + domId + "']").parent().parent().find("[id='price_" + type + "']").val(reData[i].price);
                        $("[name='" + domId + "']").parent().parent().find("[id='total_amount_" + type + "']").val(reData[i].total_amount);
                        $("[name='" + domId + "']").parent().parent().find("[id='no_" + type + "']").val(reData[i].project_number);
                        $("[name='" + domId + "']").parent().parent().find("[id='code_" + type + "']").val(reData[i].project_name);
                        $("[name='" + domId + "']").parent().parent().find("[id='order_no_" + type + "']").val(reData[i].form_id);
                    } else {
                        var noName = addLine(typeArr[type]);
                        $("[name='" + noName + "']").parent().parent().find("[id='name_" + type + "']").val(reData[i].name);
                        $("[name='" + noName + "']").parent().parent().find("[id='specification_" + type + "']").val(reData[i].specification);
                        $("[name='" + noName + "']").parent().parent().find("[id='number_" + type + "']").val(reData[i].number);
                        $("[name='" + noName + "']").parent().parent().find("[id='price_" + type + "']").val(reData[i].price);
                        $("[name='" + noName + "']").parent().parent().find("[id='total_amount_" + type + "']").val(reData[i].total_amount);
                        $("[name='" + noName + "']").parent().parent().find("[id='no_" + type + "']").val(reData[i].project_number);
                        $("[name='" + noName + "']").parent().parent().find("[id='code_" + type + "']").val(reData[i].project_name);
                        $("[name='" + noName + "']").parent().parent().find("[id='order_no_" + type + "']").val(reData[i].form_id);
                    }
                }
                sumNumber(type);
                sumAmount(type);
                layer.closeAll();
                form.render();
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
                                <input lay-submit lay-filter="submit" name="btn_submit" actionType="Submit" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input lay-submit lay-filter="submit" name="btn_save" actionType="Save" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="window.close();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="width:90%;margin:1% 4%;">
                <input type="hidden" id="type" name="type" value="shipping_notice" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="project_number" id="project_number" value="<?=$main[project_number]?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="title" id="title" value="<?= $main['title'] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $id ?>" />
                <?= $license_product_html ?>
                <?= $license_type_html ?>
                <table width="100%" align="center">
                    <tr>
                        <td align="center">
                            <img alt="" src="../../../../../images/<?= $main['user_bu'] ?>/shipping_notice_<?= $main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <?php include_once("instructions.php"); ?>
                        </td>
                    </tr>
                </table>
                <br />
                <table class="TableBlock" width="100%" align="center">
                    <tr>
                        <td colspan="6">
                            <?php include_once("change/customer_info.php"); ?>
                            <?php include_once("change/product_detail.php"); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

</html>