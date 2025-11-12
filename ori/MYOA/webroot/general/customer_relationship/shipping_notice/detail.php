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
    $yes_or_no[$v['paras_value']] = $v;
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
    $other_condition[$v['paras_value']] = $v;
}
$notice_type = array();
foreach ($selectArr['inhegrid_notice_type'] as $v) {
    $notice_type[$v['paras_value']] = $v['paras_desc'];
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
$param['id'] = $id;
$data = $action->queryById($param);
$main = $data['main'];
$isHide=$formUserManageModel->isHide('shipping_notice',$main[user_bu]);

if($_GET[auto_create]){
    $process->autoCreateShippingNotice(array('form_id'=>$main['form_id'],'user_bu'=>$main['user_bu']));
}
$company=$main[user_bu];
$product = $data['productDetail'];
//审批需要填写其他参数
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'opinion_extra',
    'user_bu' => $main['user_bu'],
    'paras_group' => 'shipping_notice',
);
$opinion_extra = $dataModel->getCommonParam($selectParam);
$opinion_extra_arr=array();
foreach ($opinion_extra['opinion_extra'] as $ps) {
    $opinion_extra_arr[$ps['type_desc']][$ps['paras_value']] = $ps;
}
// 获取审核数据
$approveData = $process->getApproveData($param);
//成都走电力流程特殊处理
$flow_manage_param[user_bu]=$main['user_bu'];
if($main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"){
    $flow_manage_param[user_bu]=$main['user_bu']."_".$main['create_user_bu'];
}
$attachObj = $attachModel->getAttachById($id);
//$flowArr = $process->getFlowStep($systemId, $main['user_bu']);
$flowArr = $process->getFlowStep($main['type'], $flow_manage_param['user_bu']);
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
//自动生成订单处理
if(strpos($main['form_id'],strtolower($main['user_bu']).'_')>=0&&$operate == 'detail'){
    $sql = " select * from inhe_flow_manage where type='$main[type]' and common_opinion='2' group by user_bu";
    $res = exequery(TD::conn(), $sql);
    $approverInfo=array();
    while ($item = mysql_fetch_assoc($res)) {
        $approverInfo[$item['user_bu']] = $item;
    }
    $step = empty($approverInfo)?'':$approverInfo[$main['user_bu']]['step'];
}



//TODO 慧软CRM合同评审
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
/*
//取合同授权信息
$sql = " select distinct d.* from inhe_contract_review k left join inhe_contract_detail d on k.form_id=d.form_id where k.form_id='$main[order_form_id]'";
$res = exequery(TD::conn(), $sql);
$contract=array();
if ($item = mysql_fetch_assoc($res)) {
    $contract = $item;
}
$sql = " select * from inhe_contract_review_pn k where k.form_id='$main[order_form_id]'";
$res = exequery(TD::conn(), $sql);
$licenseProduct=array();
while($item = mysql_fetch_assoc($res)) {
    $licenseProduct[] = $item;
}
*/
?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="main.js"></script>
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
                //$.formSubmit();
                
                form.render();
            });
            layui.use(['form', 'laydate'], function () {
                var form = layui.form,
                    laydate = layui.laydate;
                $(".date").each(function () {
                    laydate.render({
                        elem: this,
                        theme: '#027AFF',
                        range: false
                    });
                });
                $(".datetime").each(function () {
                    laydate.render({
                        elem: this,
                        theme: '#027AFF',
                        type: 'datetime',
                        range: false,
                        done: function(value, date){ //监听日期被切换
                            
                        }
                    });
                });
                $(".license_effect_time").each(function (index,element) {
                    
                    laydate.render({
                        elem: this,
                        theme: '#027AFF',
                        type: 'datetime',
                        range: false,
                        done: function(value, date){ //监听日期被切换
                           
                            //执行函数
                            var dateTemp=value;
                            var days=$(element).parent().parent("tr").find(".license_days").val();
                            var newTime=getNewTime(dateTemp, days);
                            $(element).parent().parent("tr").find(".license_expire_time").val(newTime);
                        }
                    });
                });
                form.render();
            });
            onchange();
            layui.use(['form', 'layer'], function () {
                var layer = layui.layer,
                    form = layui.form;
                form.on('submit(submit)', function (data) {
                    var btnVal = data.elem.value;
                    var actionType = $(data.elem).attr("actionType");
                    $subFlag=true;
                    if(actionType=="Approve"){
                        $("input[form-verify=required]").each(function(){
                            if(ifEmpty($(this).val())){
                                layer.alert("必填项不能为空");
                                $(this).focus();
                                $subFlag=false;
                                return false;
                            }
                        })
                    }
                    if($subFlag==false){
                        return false; 
                    }
                    
                    var url = "action.php?action=" + actionType;
                    var formId = $("#form_id").val();
                    $.post(url, $("#mainForm").serializeArray(), function (data) {
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
                            getNextApprover(formId, actionType);
                            return false;
                        } else {
                            layer.alert(btnVal + '失败！', {
                                icon: 5
                            });
                        }
                    });
                });
            });

        });

        function getNextApprover(formId, actionType) {
            var step = $('#step').val();
            var user_bu = $('#user_bu').val();
            var type = $('#type').val();
            var soft_count = $('#soft_count').val()>0&&$('#product_count').val()==1?1:0;
            var G = $('#G').val()>0&&$('#product_count').val()==1?1:0;
            var E = ($('#E').val()>0&&$('#O').val()>0&&$('#product_count').val()==2)||(($('#E').val()>0||$('#O').val()>0)&&$('#product_count').val()==1)?1:0;
            var if_match_contract = $('#if_match_contract').val();
            var if_chief_shipping = $('#if_chief_shipping').val();
            var if_insure = $('#if_insure').val();
            var param = "step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&actionType=" + actionType + "&type=" + type+ "&soft_count=" + soft_count+ "&G=" + G+ "&E=" + E;
            param += "&if_match_contract=" + if_match_contract;
            param += "&if_chief_shipping=" + if_chief_shipping;
            param += "&if_insure=" + if_insure;
            //获取当前页面的高度
            var height = $(document.body).height();
            //获取当前页面的宽度
            var width = $(document.body).width();
            width=width<600?width*0.9:600;
            layer.open({
                title: "",
                type: 2,
                area: [width+"px","80%"],
                content: "next_approver.php?" + param,
                success: function(layero, index) {
                    setTimeout(function() {
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    }, 500);
                }
            });
        }

        function getNewTime(time, days) {
           
            var dateTemp = time.substr(0,10).split("-");
            var nDate = new Date(dateTemp[1] + '-' + dateTemp[2] + '-' + dateTemp[0]); //转换为MM-DD-YYYY格式  
            var millSeconds = Math.abs(nDate) + (days * 24 * 60 * 60 * 1000);
            var rDate = new Date(millSeconds);
            var year = rDate.getFullYear();
            var month = rDate.getMonth() + 1;
            if (month < 10) month = "0" + month;
            var date = rDate.getDate();
            if (date < 10) date = "0" + date;
            return (year + "-" + month + "-" + date+" "+time.substr(-8));
        }
        function onchange() {
            $(".license_days").each(function () {
                
                
                this.onkeydown = function (e) {
                    var theEvent = window.event || e;
                    var code = theEvent.keyCode || theEvent.which;
                    if (code == 13) {
                        return false;
                    }
                };
                this.onchange = function () {
                    var dateTemp=$(this).parent().parent("tr").find(".license_effect_time").val();
                    var days=$(this).parent().parent("tr").find(".license_days").val();
                    console.log(dateTemp);
                    var newTime=getNewTime(dateTemp, days);
                    $(this).parent().parent("tr").find(".license_expire_time").val(newTime);
                };
                this.onblur = function () {
                    var dateTemp=$(this).parent().parent("tr").find(".license_effect_time").val();
                    var days=$(this).parent().parent("tr").find(".license_days").val();
                    var newTime=getNewTime(dateTemp, days);
                    $(this).parent().parent("tr").find(".license_expire_time").val(newTime);
                };
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
                                <?php if ($main['status'] == 'Y' || $main['status'] == 'R' || $main['status'] == 'E') : ?>
                                    <button type="button" class="layui-btn layui-btn-sm layui-btn-oa" onclick="$.printDetail();return false;">打印</button>
                                <?php endif; ?>
                                <input name="btn_close" value="关闭" type=button actionType="Close" class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <!--startprint-->
            <div class="weadmin-body" style="width:90%;margin:1% 4%;">
                <input type="hidden" name="product_count" id="product_count" value="<?= count($product) ?>" />
                <input type="hidden" name="soft_count" id="soft_count" value="<?= count($product['soft']) ?>" />
                <input type="hidden" name="G" id="G" value="<?= count($product['inhegrid']) ?>" />
                <input type="hidden" name="E" id="E" value="<?= count($product['electric']) ?>" />
                <input type="hidden" name="O" id="O" value="<?= count($product['other']) ?>" />
                <input type="hidden" name="type" id="type" value="<?= $main['type'] ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $id ?>" />
                <input type="hidden" name="step" id="step" value="<?= $new_step ?>" />
                <table width="100%" align="center">
                    <tr>
                        <td align="center">
                            <img alt="" src="../../../../../images/<?= $_SESSION[LOGIN_USER_ID]=='meterw'&&$main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"?"CDINHE":$main['user_bu'] ?>/<?= $main['type'] ?>_<?= $_SESSION[LOGIN_USER_ID]=='meterw'&&$main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"?"CDINHE":$main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
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
                            <?php include_once("detail/customer_info.php"); ?>
                            <?php include_once("detail/product_detail.php"); ?>
                            <?php
                                if ($main['status']=="Y"&&$operate=='detail') {
                                    include_once("../approve_opinion.php");
                                }else{
                                    if(strpos($main['form_id'],strtolower($main['user_bu']).'_')===0){
                                        include_once("detail/approve_opinion_auto.php"); 
                                    }else{
                                        include_once("detail/approve_opinion.php"); 
                                    }
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
            <!--endprint-->
        </form>
    </div>
</body>

</html>