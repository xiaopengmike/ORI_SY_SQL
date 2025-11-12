<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("销售合同评审");
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



$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());
$systemId = "sales_contract_review";
if (strpos($id, 'bg') !== false) {
    $systemId .= "_change";
}

$userInfo = $userModel->getUserInfo($userId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,check_opinion,currency,yes_or_no,quote_source,sale_model,other_condition,auto_flow_user,
    product_brand,product_detail_type,funds_come,product_project_type,license_service,license_product,license_type',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$product_detail_types=array();
foreach ($selectArr['product_detail_type'] as $v) {
    $product_detail_types[]=$v['paras_value'];
}

$license_service=array();
foreach ($selectArr['license_service'] as $v) {
    $license_service[$v['paras_value']]=$v['paras_desc'];
}
$product_project_type=array();
foreach ($selectArr['product_project_type'] as $v) {
    $product_project_type[$v['paras_value']]=$v;
}
$funds_come=array();
foreach ($selectArr['funds_come'] as $v) {
    $funds_come[$v['paras_value']]=$v;
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
$license_product = array();
foreach ($selectArr['license_product'] as $v) {
    $license_product[$v['paras_value']] = $v['paras_desc'];
}
$license_type = array();
foreach ($selectArr['license_type'] as $v) {
    $license_type[$v['paras_value']] = $v['paras_desc'];
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
$isHide=$formUserManageModel->isHide('sales_contract_review',$main[user_bu]);

if($_GET[auto_create]){
    $process->autoCreateSalesContractReview250729(array('form_id'=>$main['form_id'],'user_bu'=>$main['user_bu']));
}
$product = $data['product'];
$licenseProduct = $data['licenseProduct'];
$contract = $data['contract'];
$settle = $data['settle'];
$settleModel = $data['settleModel'];
//发起人办理跳转修改页面
if ($step=='1'&&$operate=="approve"){
    header("Location: modify.php?id=".$main['form_id']);
    exit;
}
//成都走电力流程特殊处理
$flow_manage_param[user_bu]=$main['user_bu'];
if($main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"){
    $flow_manage_param[user_bu]=$main['user_bu']."_".$main['create_user_bu'];
}
// 获取审核数据
$approveData = $process->getApproveData($param);
$attachObj = $attachModel->getAttachById($id);

$flowArr = $process->getFlowStep($systemId, $flow_manage_param[user_bu]);
$new_step=$step;
// if(strpos($main['form_id'],strtolower($main['user_bu']).'_')===0&&$flowArr[$step+1]['approver']=='meterw'){
//     $new_step=$step+1;
// }
//抛单隐藏价格
if(strpos($main['form_id'],strtolower($main['user_bu']).'_')===0){
    //成都走电力抛成都记录
    $fromData = $action->queryMain(array('id'=>$main[from_id]));
    if($main['user_bu']=="CDINHE"&&$fromData['user_bu']=="INHE"&&$fromData['create_user_bu']=="CDINHE"){
    }else{
        $isHide=true;
        $isHide2=true;
    }
}

//审批需要填写其他文本参数
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'editable_fields',
    'user_bu' => $main['user_bu'],
    'paras_group' => 'sales_contract_review',
);
$editable_fields = $dataModel->getCommonParam($selectParam);
$editable_fields_arr=array();
foreach ($editable_fields['editable_fields'] as $ps) {
    $editable_fields_arr[$ps['type_desc']][editable_fields][] = $ps['paras_value'];
}

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
$company=$main["user_bu"];
$product_company=$company;
if(in_array($product_company,array("JXINHE","INHE","INHEJX","HKINHE","INHEIAC","INHEIMC"))){
    $product_company="JXINHE";
}
if(in_array($product_company,array("HKNERGY","INHENERGY"))){
    $product_company="INHENERGY";
}
//其他公司产品下拉列表
foreach ($selectArr['auto_flow_user'] as $v) {
    if(($product_company=="JXINHE"&&$v['user_bu']=="INHE")||$product_company==$v['user_bu']){
        continue;
    }
    $key=$v['user_bu']=="INHE"?"JXINHE":$v['user_bu'];
    $other_company_product[$key]= $v['extra'];
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
    <script language="Javascript" type="text/javascript" src="../detail.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" href="../crm.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <style>
        @media screen and (max-width: 600px) { /*当屏幕尺寸小于600px时，应用下面的CSS样式*/
            .label_condition {
                white-space: normal;
            }
        }

    </style>
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
                confirm_customer_service();
                
                form.render();
            });
        });

        function getNextApprover(formId, actionType) {
            var step = $('#step').val();
            var user_bu = $('#user_bu').val();
            var type = $('#type').val();
            //var soft_count = $('#soft_count').val();
            var E = $('#E').val();
            var S = $('#S').val();
            var N = $('#N').val();
            var G = $('#G').val();
            var O = $('#O').val();
            var D = $('#D').val();
            var is_collective = $('#is_collective').val()=="是"?1:0;
            
            var if_eligible = $('#if_eligible').val();
            var if_chief_contract = $('#if_chief_contract').val();
            var if_not_full_payment_goods = $('#if_not_full_payment_goods').val();
            var if_not_full_payment = $('#if_not_full_payment').val();
            var param = "step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&actionType=" + actionType + "&type=" + type + "&E=" + E + "&S=" + S + "&N=" + N + "&G=" + G+ "&O=" + O+ "&D=" + D+"&is_collective="+is_collective;
            param += "&if_eligible=" + if_eligible;
            param += "&if_chief_contract=" + if_chief_contract;
            param += "&if_not_full_payment_goods=" + if_not_full_payment_goods;
            param += "&if_not_full_payment=" + if_not_full_payment;
            layer.open({
                title: "",
                type: 2,
                area: ['600px', '80%'],
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

        function confirm_customer_service(){
            var layer = layui.layer,
                form = layui.form;
                if($("#editable_customer_service").length>0){
                    var customer_service_name=$("#customer_service_name").val();
                    customer_service_name=ifEmpty(customer_service_name)?"空":customer_service_name;
                    layer.confirm('当前交付经理为:'+customer_service_name+',请确认或者修改', {
                            icon: 3,
                            title: '提示',
                            btn: ['确认', '修改']
                        }, function (index) {
                            layer.close(index);
                        }, function (index) {
                            $("#customer_service_name").focus();
                            $("#customer_service_name").click();
                            layer.close(index);
                        });
                }
                
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
                <input type="hidden" name="soft_count" id="E" value="<?= count($product['electric']) ?>" />
                <input type="hidden" name="soft_count" id="S" value="<?= count($product['soft']) ?>" />
                <input type="hidden" name="soft_count" id="N" value="<?= count($product['inhenergy']) ?>" />
                <input type="hidden" name="soft_count" id="G" value="<?= count($product['inhegrid']) ?>" />
                <input type="hidden" name="soft_count" id="O" value="<?= count($product['other']) ?>" />
                <input type="hidden" name="soft_count" id="D" value="<?= count($product['cdinhe']) ?>" />
                <?if(in_array('customer_service',$editable_fields_arr[$approveInfo[work_flow]][editable_fields])&&$operate=='approve'):?>
                    <input type="hidden" name="editable_customer_service" id="editable_customer_service" value="true" />
                <?endif;?>
                <input type="hidden" name="customer_service2" id="customer_service2" value="<?= $contract['customer_service'] ?>" />
                <input type="hidden" name="customer_service_name2" id="customer_service_name2" value="<?= trim(GetUserNameById($contract['customer_service']),",") ?>" />
                <input type="hidden" name="type" id="type" value="<?= $main['type'] ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $id ?>" />
                <input type="hidden" name="step" id="step" value="<?= $new_step ?>" />
                <table width="100%" align="center">
                    <tr>
                        <td align="center">
                            <img alt="" src="../../../../../images/<?= $_SESSION[LOGIN_USER_ID]=='meterw'&&$main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"?"CDINHE":$main['user_bu'] ?>/<?= $main['type'] ?>_<?= $_SESSION[LOGIN_USER_ID]=='meterw'&&$main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"?"CDINHE":$main['user_bu'] ?>.jpg?v=1" style="width: 34%; height: auto;" title="" />
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
                        <td>
                            <?php include_once("detail/main_info.php"); ?>
                            <?php include_once("detail/product_detail.php"); ?>
                            <?php include_once("detail/contract_info.php"); ?>
                            <?php
                                if ($main['status']=="Y"&&$operate=='detail') {
                                    include_once("../approve_opinion.php");
                                }else{
                                    include_once("detail/approve_opinion.php"); 
                                    // if(strpos($main['form_id'],strtolower($main['user_bu']).'_')===0){
                                    //     include_once("detail/approve_opinion_auto.php"); 
                                    // }else{
                                    //     include_once("detail/approve_opinion.php"); 
                                    // }
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