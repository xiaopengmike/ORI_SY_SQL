<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("销售收款登记");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../common/DataModel.php");
$model = new DataModel();
require_once '../common/AttachModel.php';
$attachModel = new AttachModel();
require_once '../common/ProcessModel.php';
$process = new ProcessModel();
require_once 'ActionModel.php';

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());
$systemId = "sales_receipt";

$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'currency,receipt_product_type'
);
$selectArr = $model->getCommonParam($selectParam);
$currency = array();
foreach ($selectArr['currency'] as $v) {
    $currency[$v['paras_value']] = $v;
}
$receipt_product_type = array();
foreach ($selectArr['receipt_product_type'] as $v) {
    $receipt_product_type[$v['paras_value']] = $v;
}
//授权产品下拉列表
$receipt_product_html = '<div id="receipt_product_select" hidden="hidden">';
$receipt_product_html .= '<option value="">请选择</option>';
foreach ($selectArr['receipt_product_type'] as $v) {
    $receipt_product_html .= '<option value="' . $v['paras_value'] . '">' . $v['paras_desc'] . '</option>';
}
$receipt_product_html .= '</div>';

$action = new ActionModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$param['id'] = $id;
$data = $action->queryById($param);
$main = $data['main'];
$company=$main['user_bu'];
$detail = $data['detail'];
$attach = $data['attach'];
$product = $data['product'];
// 获取审核数据
$approveData = $process->getApproveData($param);
$attachObj = $attachModel->getAttachById($id);
$flowArr = $process->getFlowStep($systemId, $main['user_bu']);
$curOperate = $flowArr[$step]['operate'];
$backRecord = $process->findBackRecord($param);
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
    <script language="Javascript" type="text/javascript" src="main.js?v=20250211"></script>
    <script language="Javascript" type="text/javascript" src="../add.js"></script>
    <script language="Javascript" type="text/javascript" src="../detail.js?v=20241018"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/dtreeck.css">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/dtreeck.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <script type="text/javascript">
        $(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                var attachObj = <?= $attachObj ?>;
                layui.use(['form', 'attach'], function() {
                    var form = layui.form,
                        attach = layui.attach;
                    $(".btn-upload").each(function() {
                        var id = this.id;
                        var title = this.title;
                        if (!ifEmpty(attachObj[id]) && title != 'add') {
                            attach.attachReadonly({
                                elem: "#" + this.id,
                                uploadUrl: "/general/attachment/attach.php?type=" + this.id + "&url=common",
                                attachObj: attachObj[id]
                            });
                        } else {
                            if (ifEmpty(attachObj[id])) attachObj[id] = {};
                            attach.attachList({
                                elem: "#" + id,
                                uploadUrl: "/general/attachment/attach.php?type=" + id + "&url=common",
                                attachObj: attachObj[id]
                            });
                        }
                    });
                });

                formSubmit();
                $("[id='order_number']").each(function() {
                    this.onkeydown = function(e) {
                        var theEvent = window.event || e;
                        var code = theEvent.keyCode || theEvent.which;
                        if (code == 13) {
                            return false;
                        }
                    };
                    this.onfocus = function() {
                        getOrderList(this);
                    };
                });
                //自定义验证规则
                form.verify({
                    
                    upload_attach: function(value,item){
                        var $ = layui.$;
                        var verifyName=$(item).attr('name')
                        , verifyType=$(item).attr('type')
                        ,formElem=$(item).parents('.layui-form')//获取当前所在的form元素，如果存在的话
                        ,verifyElem=formElem.find('input[name='+verifyName+']')//获取需要校验的元素
                        ,isTrue= verifyElem.parent().find("a").length>0//是否存在文件
                        ,focusElem = verifyElem.next().find('i.layui-icon');//焦点元素
                        //console.log(verifyType);
                        //console.log(verifyElem.parent().find("input[name='attach[]']").length);
                        if(!isTrue){
                                //定位焦点
                                focusElem.css(verifyType=='radio'?{"color":"#FF5722"}:{"border-color":"#FF5722"});
                                //对非输入框设置焦点
                                focusElem.first().attr("tabIndex","1").css("outline","0").blur(function() {
                                    focusElem.css(verifyType=='radio'?{"color":""}:{"border-color":""});
                                }).focus();
                                return '请上传第三方委托函和受托函';
                        }
                        
                    },
                });
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
                area: ['600px', '80%'],
                content: "next_approver.php?step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&actionType=" + actionType + "&type=" + type,
                success: function(layero, index) {
                    setTimeout(function() {
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    }, 500)
                }
            });
        }
         // 审核操作：提交或退回
         function formSubmit () {
           
            layui.use(['form', 'layer'], function () {
                var layer = layui.layer,
                    form = layui.form;
                form.on('submit(submit)', function (data) {
                    
                    var btnVal = data.elem.value;
                    var actionType = $(data.elem).attr("actionType");
                    if(actionType=="Approve"){
                        if($("#addProductCount").length>0&&$("#p_receipt_amount").length<=0
                        &&($("#user_bu").val()!="INHENERGY"&&$("#user_bu").val()!="INHEJX")){
                            layer.alert("请添加收款类型明细", {
                                    icon: 3,
                                    title: '提示',
                                    btn: ['关闭']
                                }, function (index) {
                                    
                                    layer.close(index);
                                });
                                return false;
                        }
                        var result=check_submit_product();
                        if(result[0]!=true){
                            if(result[2]==1){
                                layer.alert(result[1], {
                                    icon: 3,
                                    title: '提示',
                                    btn: ['重新输入']
                                }, function (index) {
                                    //$("[name='" + result[0] + "']").parent().parent().find("[id='p_receipt_amount']").val('');
                                    layer.close(index);
                                });
                                return false;
                            }
                            layer.confirm(result[1], {
                                icon: 3,
                                title: '提示',
                                btn: ['金额正确', '重新输入']
                            }, function (index) {
                                layer.close(index);
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
                            }, function (index) {
                                //$("[name='" + result[0] + "']").parent().parent().find("[id='p_receipt_amount']").val('');
                                layer.close(index);
                            });
                            return false;
                        }
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
        }
        //合同产品类型检查单行本次收款是否大于合同类型合计
        function check_p_receipt_amount(domName,result){
            var layer = layui.layer,
                form = layui.form;
            // 计算未收款=合同金额-已收款金额
            var total = $("[name='" + domName + "']").parent().parent().parent().find("[id='p_contract_amount']").val();
            var receiptAmount = $("[name='" + domName + "']").parent().parent().parent().find("[id='p_receipt_amount']").val();
            var serial_number_product = $("[name='" + domName + "']").parent().parent().parent().find("[id='serial_number_product']").val();
            if (ifEmpty(total)) total = 0;
            if (ifEmpty(receiptAmount)) receiptAmount = 0;
            var unReceipt = 0;
            unReceipt = parseFloat(parseFloat(total) - parseFloat(receiptAmount)).toFixed(2);
           
            if (!ifEmpty(unReceipt)) {
                if (unReceipt < 0) {
                    
                    if(ifEmpty(result)){
                        layer.alert('收款类型明细序号（'+serial_number_product+'）:本次收款超出合同类型合计，请确认金额无误！', {
                            icon: 3,
                            title: '提示',
                            btn: ['重新输入']
                        },function (index) {
                            $("[name='" + domName + "']").parent().parent().parent().find("[id='p_receipt_amount']").val('');
                            //unReceipt = parseFloat(parseFloat(total) - parseFloat(receipted)).toFixed(2);
                            layer.close(index);
                        });
                    }else{
                        result[0]=domName;
                        result[1]='收款类型明细序号（'+serial_number_product+'）:本次收款超出合同类型合计，请确认金额无误！';
                        result[2]=1;
                    }
                    return result;
                }
            }
            
            return result;
        }
        //合同产品类型检查所有行本次收款合计是否大于本次收款金额
        function check_receipt_amount(domName,result){
            var layer = layui.layer,
                form = layui.form;
            var p_order_number=$("[name='" + domName + "']").parent().parent().parent().find("[id='p_order_number']").val();
            
            total_p_receipt_amount=[];
            total_p_receipt_amount["'"+p_order_number+"'"]=0;
            $("[id='p_receipt_amount']").each(function () {
                var cur_order_number=$(this).parent().parent().parent().find("[id='p_order_number']").val();
                var cur_val=ifEmpty($(this).val())?0:$(this).val();
                if(p_order_number==cur_order_number){
                    if(ifEmpty(total_p_receipt_amount["'"+p_order_number+"'"])){
                        total_p_receipt_amount["'"+p_order_number+"'"]=parseFloat(cur_val);
                    }else{
                        total_p_receipt_amount["'"+p_order_number+"'"]=parseFloat(total_p_receipt_amount["'"+p_order_number+"'"])+parseFloat(cur_val);
                    }
                }
            });
            
            total_receipt_amount=[];
            total_receipt_amount["'"+p_order_number+"'"]=0;
            $("[id='receipt_amount']").each(function () {
                var cur_val=ifEmpty($(this).val())?0:$(this).val();
                var order_number=$(this).parent().parent().find("[id='order_number']").val();
                if(ifEmpty(total_receipt_amount["'"+order_number+"'"])){
                    total_receipt_amount["'"+order_number+"'"]=parseFloat(cur_val);
                }else{
                    total_receipt_amount["'"+order_number+"'"]=parseFloat(total_receipt_amount["'"+order_number+"'"])+parseFloat(cur_val);
                }
            });

            var unReceipt = 0;
            unReceipt = parseFloat(parseFloat(total_receipt_amount["'"+p_order_number+"'"]) - parseFloat(total_p_receipt_amount["'"+p_order_number+"'"])).toFixed(2);
            
            if (!ifEmpty(unReceipt)) {
                if (unReceipt != 0) {
                    if(ifEmpty(result)){
                        layer.confirm('合同（'+p_order_number+'）:收款类型明细收款金额总和不等于合同本次收款金额，请确认金额无误！', {
                            icon: 3,
                            title: '提示',
                            btn: ['金额正确', '重新输入']
                        }, function (index) {
                            layer.close(index);
                        }, function (index) {
                            $("[name='" + domName + "']").parent().parent().find("[id='p_receipt_amount']").val('');
                            layer.close(index);
                        });
                    }else{
                        result[0]=domName;
                        result[1]='合同（'+p_order_number+'）:收款类型明细收款金额总和不等于合同本次收款金额，请确认金额无误！';
                        result[2]=2;
                       
                    }
                    
                    return result;
                }   
            }
            return result;
        }   

        function check_submit_product(){
            var result=[];
            result[0]=true;
            result[1]="";
            $("[id='p_receipt_amount']").each(function () {
                result[0]=true;
                result[1]="";
                result=check_p_receipt_amount($(this).attr("name"),result);
                console.log(result);
                if(result[0]!=true){
                    return false;
                }
                result=check_receipt_amount($(this).attr("name"),result);
                if(result[0]!=true){
                    return result;
                }
            });
            // $("[id='p_receipt_amount']").each(function () {
            //     result[0]=true;
            //     result[1]="";
            //     result=check_receipt_amount($(this).attr("name"),result);
            //     if(result[0]!=true){
            //         return result;
            //     }
            // });
            return result;
        }
    </script>
</head>

<body>
    <form id="mainForm" class="layui-form">
        <div style="width: 100%;height:auto;margin:auto;" align="center">
            <div class="r" style="height: auto;">
                <div style="text-align: left;" class="layui-btn-group">
                    <table id="tab" width="100%">
                        <tr class="layui-bg-body">
                            <td style="padding: 5px;">
                                <?php if ($operate == 'approve' || count($approveInfo) > 0) : ?>
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
            <div class="weadmin-body" style="margin-top: 30px">
                <input type="hidden" name="type" id="type" value="<?= $main['type'] ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $id ?>" />
                <input type="hidden" name="step" id="step" value="<?= $step ?>" />
                <?= $receipt_product_html ?>
                <table width="70%" align="center">
                    <td align="center">
                        <img alt="" src="../../../../../images/<?= $main['user_bu'] ?>/<?= $main['type'] ?>_<?= $main['user_bu'] ?>.jpg" style="width: 28%; height: auto;" />
                    </td>
                </table>
                <br />
                <table class="TableBlock" width="90%" align="center">
                    <tr>
                        <td>
                            <table id="tab" width="100%" style="text-align: center;">
                                <thead>
                                    <tr class="TableHeader">
                                        <td colspan="8" style="text-align: center;">财务部门填写</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="TableContent" width="10%">付款方名称</td>
                                        <td class="TableData" width="15%"><?= $main['payer_name'] ?></td>
                                        <td class="TableContent" width="10%">原币金额</td>
                                        <td class="TableData" width="15%"><?= $main['original_amount'] ?></td>
                                        <td class="TableContent" width="10%">原币币种</td>
                                        <td class="TableData" width="15%"><?= $currency[$main['currency']]['paras_desc'] ?></td>
                                        <td class="TableContent" width="10%">项目编号</td>
                                        <td class="TableData"><?= $main['project_number'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="TableContent">人民币金额</td>
                                        <td class="TableData"><?= $main['rmb_amount'] ?></td>
                                        <td class="TableContent">付款方式</td>
                                        <td class="TableData"><?= $main['payment_term'] ?></td>
                                        <td class="TableContent">到款时间</td>
                                        <td class="TableData"><?= $main['receipt_date'] ?></td>
                                        <td class="TableContent">到款银行</td>
                                        <td class="TableData"><?= $main['receipt_bank'] ?></td>
                                        
                                    </tr>
                                    <tr>
                                        <td class="TableContent">业务部门</td>
                                        <td class="TableData">
                                            <div class="layui-input-inline"><?= $main['business_dept'] ?></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="tab1" width="100%">
                                <thead>
                                    <tr class="TableHeader">
                                        <td colspan="15" style="text-align: center;">业务填写</td>
                                    </tr>
                                    <tr>
                                        <td nowrap class="TableContent" width="3%">序号</td>
                                        <td nowrap class="TableContent" width="3%">项目代号</td>
                                        <td nowrap class="TableContent" width="3%">项目编号</td>
                                        <td nowrap class="TableContent" width="9%">订单编号</td>
                                        <td class="TableContent">客户名称</td>
                                        <td nowrap class="TableContent" width="6%">合同金额</td>
                                        <td nowrap class="TableContent" width="6%">币种</td>
                                        <td class="TableContent">对应人民币金额</td>
                                        <td class="TableContent">本次收款金额</td>
                                        <td class="TableContent">扣减手续费、其他费用</td>
                                        <td class="TableContent">已收款原币金额</td>
                                        <td class="TableContent">未收款原币金额</td>
                                        <td class="TableContent">合同相对方名称</td>
                                        <td class="TableContent">是否第三方支付</td>
                                        <td class="TableContent">备注<?if(in_array($company,array('INHENERGY','HKNERGY'))):?>(预付款请填写比率)<?endif;?></td>
                                    </tr>
                                </thead>
                                <tbody id="addReceipt">
                                    <input type="hidden" name="addReceiptCount" id="addReceiptCount" value="<?= count($detail); ?>" />
                                    <?php  $if_third_pay=0; ?>
                                    <?php foreach ($detail as $k => $v) { ?>
                                        <?if($v['if_third_pay']=="是"){$if_third_pay=1;}?>
                                        <?php if ($step == 2) { ?>
                                            <tr align="center">
                                                <td class="TableData"><input type="hidden" name="serial_number<?= $k + 1 ?>" id="serial_number" class="layui-input" value="<?= $v['serial_number'] ?>" readonly /><?= $v['serial_number'] ?></td>
                                                <td class="TableData"><input type="text" name="project_code<?= $k + 1 ?>" id="project_code" class="layui-input" autocomplete="off" value="<?= $v['project_code'] ?>" /></td>
                                                <td class="TableData"><input type="text" name="project_number<?= $k + 1 ?>" id="project_number" class="layui-input" autocomplete="off" value="<?= $v['project_number'] ?>" /></td>
                                                <td class="TableData"><input type="text" name="order_number<?= $k + 1 ?>" id="order_number" class="layui-input mandatory" autocomplete="off" value="<?= $v['order_number'] ?>" /></td>
                                                <td class="TableData"><input type="text" name="customer_name<?= $k + 1 ?>" id="customer_name" class="layui-input" autocomplete="off" value="<?= $v['customer_name'] ?>" /></td>
                                                <td class="TableData"><input type="text" name="contract_amount<?= $k + 1 ?>" id="contract_amount" class="layui-input" autocomplete="off" value="<?= $v['contract_amount'] ?>" /></td>
                                                <td class="TableData">
                                                    <div class="layui-input-inline">
                                                        <select id="currency_detail" name="currency_detail<?= $k + 1 ?>" lay-search>
                                                            <option value=''>请选择</option>
                                                            <?php foreach ($currency as $val) : ?>
                                                                <option value="<?= $val['paras_value'] ?>" <?php if ($v['currency'] == $val['paras_value']) : ?>selected<?php endif; ?>><?= $val['paras_desc'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td class="TableData"><input type="text" name="rmb_amount<?= $k + 1 ?>" id="rmb_amount" class="layui-input" autocomplete="off" value="<?= $v['rmb_amount'] ?>" /></td>
                                                <td class="TableData"><input type="text" name="receipt_amount<?= $k + 1 ?>" id="receipt_amount" lay-verify="required" class="layui-input" autocomplete="off" onblur="calUnReceipted(this.name);return false;" value="<?= $v['receipt_amount'] ?>" /></td>
                                                <td class="TableData"><input type="text" name="other_cost<?= $k + 1 ?>" id="other_cost" class="layui-input" autocomplete="off" value="<?= $v['other_cost'] ?>" /></td>
                                                <td class="TableData"><input type="text" name="receipted<?= $k + 1 ?>" id="receipted" class="layui-input" autocomplete="off" readonly value="<?= $v['receipted'] ?>" /></td>
                                                <td class="TableData"><input type="text" name="un_receipt<?= $k + 1 ?>" id="un_receipt" class="layui-input" autocomplete="off" readonly value="<?= $v['un_receipt'] ?>" /></td>
                                                <td class="TableData"><input type="text" name="contract_party<?= $k + 1 ?>" id="contract_party" class="layui-input" autocomplete="off" value="<?= $v['contract_party'] ?>" /></td>
                                                <td class="TableData"><select lay-verify="required"  id="if_third_pay" name="if_third_pay<?= $k + 1 ?>" >
                                                <option value="">请选择</option>
                                                <option value="是" <?if($v['if_third_pay']=="是"):?>selected='selected'<?endif;?>>是</option>
                                                <option value="否"  <?if($v['if_third_pay']=="否"):?>selected='selected'<?endif;?>>否</option>
                                                </select>
                                                    <!-- <input lay-verify="required" type="text" name="if_third_pay<?= $k + 1 ?>" id="if_third_pay" class="layui-input" autocomplete="off" value="<?= $v['if_third_pay'] ?>" /></td> -->
                                                <td class="TableData"><input type="text" name="remark<?= $k + 1 ?>" id="remark" class="layui-input" autocomplete="off" value="<?= $v['remark'] ?>" />
                                                <a href="javascript:;" onclick="addLine('addProduct',this)">添加明细</a><a href="javascript:;" onclick="removeLine(this,'p_order_number')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
                                                </td>
                                            </tr>
                                        <?php } else { ?>
                                            <tr align="center">
                                                <td class="TableData"><?= $v['serial_number'] ?></td>
                                                <td class="TableData"><?= $v['project_code'] ?></td>
                                                <td class="TableData"><?= $v['project_number'] ?></td>
                                                <td class="TableData"><?= $v['order_number'] ?></td>
                                                <td class="TableData"><?= $v['customer_name'] ?></td>
                                                <td class="TableData"><?= $v['contract_amount'] ?></td>
                                                <td class="TableData"><?= $currency[$v['currency']]['paras_desc'] ?></td>
                                                <td class="TableData"><?= $v['rmb_amount'] ?></td>
                                                <td class="TableData"><?= $v['receipt_amount'] ?></td>
                                                <td class="TableData"><?= $v['other_cost'] ?></td>
                                                <td class="TableData"><?= $v['receipted'] ?></td>
                                                <td class="TableData"><?= $v['un_receipt'] ?></td>
                                                <td class="TableData"><?= $v['contract_party'] ?></td>
                                                <td class="TableData"><?= $v['if_third_pay'] ?></td>
                                                <td class="TableData"><?= $v['remark'] ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                                <?php if ($step == 2) { ?>
                                    <tr align="left">
                                        <td class="TableData" colspan="15">
                                            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addReceipt');return false;" />
                                        </td>
                                    </tr>
                                <?php } ?>
                               
                                    <tr class="">
                                            <td colspan="15" class="TableContent" style="text-align: center;">收款类型明细</td>
                                    </tr>
                                    <tr align="center">
                                        <td class="TableContent">序号</td>
                                        <td class="TableContent" colspan="4">订单编号</td>
                                        <td class="TableContent" colspan="4">产品类型</td>
                                        <td class="TableContent" colspan="3">合同类型合计</td>
                                        <td class="TableContent" colspan="3">本次收款</td>
                                    </tr>
                                    <tbody id="addProduct">
                                        <? if ($step == 2) :?>
                                            <? foreach ($product as $k => $v): ?>
                                                <tr align="center">
                                                <td class="TableData"><input type="hidden" name="serial_number_product<?= $k + 1 ?>" id="serial_number_product" class="layui-input" value="<?= $v['serial_number'] ?>" readonly /><?= $v['serial_number'] ?></td>
                                                <td class="TableData" colspan="4"><input type="text" name="p_order_number<?= $k + 1 ?>" id="p_order_number" readonly class="layui-input" autocomplete="off" value="<?= $v['order_number'] ?>" /></td>
                                                <td class="TableData" colspan="4">
                                                <div class="layui-input-inline mandatory">
                                                <select lay-search lay-verify="required" id="p_product_type" name="p_product_type<?= $k + 1 ?>" lay-search>
                                                    <option value="">请选择</option>
                                                    <?php foreach ($selectArr['receipt_product_type'] as $val) : ?>
                                                        <option value="<?= $val['paras_value'] ?>" <?php if ($val['paras_value'] == $v['product_type']) : ?>selected<?php endif; ?>><?= $val['paras_desc'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                        </div>
                                                </td>
                                                <td class="TableData" colspan="3"><div class="layui-input-inline "><input type="number" name="p_contract_amount<?= $k + 1 ?>" id="p_contract_amount" class="layui-input mandatory" autocomplete="off" value="<?= $v['contract_amount'] ?>" /></div>
                                                <td class="TableData" colspan="3">
                                                <div class="layui-input-inline mandatory">
                                                <input type="number" name="p_receipt_amount<?= $k + 1 ?>" id="p_receipt_amount" class="layui-input " autocomplete="off" value="<?= $v['receipt_amount'] ?>" onblur="check_p_receipt_amount(this.name);return false;" />
                                                        </div>
                                                <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
                                                </td>
                                            </tr>
                                            <?endforeach;?>
                                        <?else:?>
                                            <? foreach ($product as $k => $v): ?>
                                            <tr align="center">
                                                <td class="TableData"><?= $v['serial_number'] ?></td>
                                                <td class="TableData" colspan="4"><?= $v['order_number'] ?></td>
                                                <td class="TableData" colspan="4"><?= $receipt_product_type[$v['product_type']]['paras_desc'] ?></td>
                                                <td class="TableData" colspan="3"><?= $v['contract_amount'] ?></td>
                                                <td class="TableData" colspan="3"><?= $v['receipt_amount'] ?></td>

                                            </tr>
                                            <?endforeach;?>
                                        <?endif;?>
                                    </tbody>
                                    <? if ($step == 2) :?>
                                        <input type="hidden" name="addProductCount" id="addProductCount" value="<?= count($product); ?>" />
                                        <tr align="left">
                                            <td class="TableData" colspan="15">
                                               
                                            </td>
                                        </tr>
                                        <!-- <tr align="left">
                                            <td class="TableData" colspan="15">
                                                <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addProduct');return false;" />
                                            </td>
                                        </tr> -->
                                    <?endif;?>
                                    <tr class="">
                                            <td colspan="15" class="TableContent" style="text-align: center;">附档明细</td>
                                    </tr>
                                <tr>
                                    <td class="TableContent">序号</td>
                                    <td class="TableContent" colspan="4">附档名称</td>
                                    <td class="TableContent" colspan="10">附档</td>
                                </tr>
                                <tbody id="addAttach">
                                    <?php if ($step == 2) { ?>
                                        <input type="hidden" name="addAttachCount" id="addAttachCount" value="<?= count($attach); ?>" />
                                    <?php } ?>
                                    <?php foreach ($attach['add'] as $k => $v) { ?>
                                        <?php if ($step == 2) { ?>
                                            <tr align="center">
                                                <td class="TableData"><input type="hidden" name="serial_number_attach<?= $k + 1 ?>" id="serial_number_attach" class="layui-input" value="<?= $v['serial_number'] ?>" /><?= $v['serial_number'] ?></td>
                                                <td class="TableData" colspan="4"><input type="text" name="attach_name<?= $k + 1 ?>" id="attach_name" class="layui-input" autocomplete="off" value="<?= $v['attach_name'] ?>" /></td>
                                                <td class="TableData" colspan="10">
                                                    <input type="hidden" name="type<?= $k + 1 ?>" id="type" value="add" />
                                                    <input id="sales_receipt_attach<?= $k + 1 ?>" title="add" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
                                                    <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
                                                </td>
                                            </tr>
                                        <?php } else { ?>
                                            <tr align="center">
                                                <td class="TableData"><?= $v['serial_number'] ?></td>
                                                <td class="TableData" colspan="4"><?= $v['attach_name'] ?></td>
                                                <td class="TableData" colspan="10">
                                                    <input id="sales_receipt_attach<?= $k + 1 ?>" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                                <?php if ($step == 2) { ?>
                                    <tr align="left">
                                        <td class="TableData" colspan="15">
                                            <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addAttach');return false;" />
                                        </td>
                                    </tr>
                                <?php } ?>
                                
                            </table>
                            <table id="tab1" width="100%">
                                <thead>
                                    <tr class="TableHeader">
                                        <td colspan="2" style="text-align: center;">跟单审核</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr align="center">
                                        <td class="TableContent" rowspan="3" width="15%">跟单</td>
                                    </tr>
                                    <tr align="center">
                                        <td class="TableData">
                                            <textarea class="layui-textarea TextDetails <?php if ((3 == $step)) : ?>mandatory<?php endif; ?>" id="opinion3" name="opinion3" data-type="opinion" <?php if ((3 != $step)) : ?>disabled<?php endif; ?>><?= $approveData[3]['opinion'] ?></textarea>
                                        </td>
                                    </tr>
                                    <tr align="left">
                                        <td class="TableData" style="min-height: 20px"><?= $approveData[3]['signature'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="tab2" width="100%">
                                <thead>
                                    <tr class="TableHeader">
                                        <td colspan="3" style="text-align: center;">业务员补充资料</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr align="center">
                                        <td class="TableContent" rowspan="3" width="15%">业务员</td>
                                    </tr>
                                    <tr align="center">
                                        <td class="TableData" colspan="2">
                                            <textarea class="layui-textarea TextDetails <?php if ((5 == $step)) : ?>mandatory<?php endif; ?>" id="opinion5" name="opinion5" data-type="opinion" <?php if ((5 != $step)) : ?>disabled<?php endif; ?>><?= $approveData[5]['opinion'] ?></textarea>
                                        </td>
                                    </tr>
                                    <tr align="left">
                                        <td class="TableData" colspan="2" style="min-height: 20px"><?= $approveData[5]['signature'] ?></td>
                                    </tr>
                                    <tr align="center">
                                        <td class="TableContent">序号</td>
                                        <td class="TableContent">附档名称</td>
                                        <td class="TableContent">附档</td>
                                    </tr>
                                    <?php if ($step == 5) { ?>
                                        <tr align="center">
                                            <input type="hidden" name="addAttachCount" id="addAttachCount" value="1" />
                                            <input type="hidden" name="type1" id="type" value="addition" />
                                            <td class="TableData"><input type="hidden" name="serial_number_attach1" id="serial_number_attach" class="layui-input" value="1" />1</td>
                                            <td class="TableData"><input type="text" <?if($if_third_pay==1):?>lay-verify="required"<?endif;?> name="attach_name1" id="attach_name" class="layui-input mandatory" value="<?= $attach['addition'][0]['attach_name'] ?>" /></td>
                                            <td class="TableData">
                                                <input id="business_addition_attach" name="business_addition_attach" <?if($if_third_pay==1):?>lay-verify="upload_attach"<?endif;?> title="add" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
                                            </td>
                                        </tr>
                                    <?php } else { ?>
                                        <tr align="center">
                                            <td class="TableData">1</td>
                                            <td class="TableData"><?= $attach['addition'][0]['attach_name'] ?></td>
                                            <td class="TableData">
                                                <input id="business_addition_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="上传" readonly />
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            <!--endprint-->
        </div>
    </form>
    <br />
    <?php
    include_once("common/mydtree_new.php");
    ?>
</body>

</html>