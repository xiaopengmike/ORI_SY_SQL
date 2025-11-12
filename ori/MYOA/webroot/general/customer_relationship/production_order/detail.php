<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("生产通知单");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");
require_once("../common/AttachModel.php");
require_once("../common/ProcessModel.php");
require_once("../common/DataModel.php");
require_once '../common/FormUserManageModel.php';
$process = new ProcessModel();
$attachModel = new AttachModel();
$dataModel = new DataModel();
$formUserManageModel = new FormUserManageModel();



$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,check_opinion,yes_or_no,inspection_type,display_mode,production_mode,auto_flow_user,
              test_report,packed_in_sequence,product_brand,form_type,product_detail_type,company,other_condition,SGC_TYPE,license_product,license_type'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$company_names=array();
foreach ($selectArr['company'] as $v) {
    $company_names[$v['paras_value']]=$v['paras_desc'];
}
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
$yes_or_no = array();
foreach ($selectArr['yes_or_no'] as $v) {
    $yes_or_no[$v['paras_value']] = $v['paras_desc'];
}
$display_mode = array();
foreach ($selectArr['display_mode'] as $v) {
    $display_mode[$v['paras_value']] = $v['paras_desc'];
}
$production_mode = array();
foreach ($selectArr['production_mode'] as $v) {
    $production_mode[$v['paras_value']] = $v['paras_desc'];
}
$test_report = array();
foreach ($selectArr['test_report'] as $v) {
    $test_report[$v['paras_value']] = $v['paras_desc'];
}
$inspection_type = array();
foreach ($selectArr['inspection_type'] as $v) {
    $inspection_type[$v['paras_value']] = $v['paras_desc'];
}
$packed_in_sequence = array();
foreach ($selectArr['packed_in_sequence'] as $v) {
    $packed_in_sequence[$v['paras_value']] = $v['paras_desc'];
}
$product_brand = array();
foreach ($selectArr['product_brand'] as $v) {
    $product_brand[$v['paras_value']] = $v['paras_desc'];
}
$other_condition = array();
foreach ($selectArr['other_condition'] as $v) {
    $other_condition[$v['paras_value']] = $v;
}

$is_sgc = array();
foreach ($selectArr['SGC_TYPE'] as $v) {
    $is_sgc[$v['paras_value']] = $v['paras_desc'];
}
$license_product = array();
foreach ($selectArr['license_product'] as $v) {
    $license_product[$v['paras_value']] = $v['paras_desc'];
}
$license_type = array();
foreach ($selectArr['license_type'] as $v) {
    $license_type[$v['paras_value']] =$v['paras_desc'];
}

$action = new ActionModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$param['id'] = $id;
//获取变更记录
$changeData=$dataModel->getNewChange($id,'production_order');
$data = $action->queryById($param);
$main = $data['main'];
$isHide=$formUserManageModel->isHide('production_order',$main['user_bu']);
$company=$main['user_bu'];
if($_GET[auto_create]){
    $process->autoCreateProductOrderNew(array('form_id'=>$main['form_id'],'user_bu'=>$main['user_bu']));
}
//发起人办理跳转修改页面
if ($step=='1'&&$operate=="approve"){
    header("Location: modify.php?id=".$main['form_id']);
    exit;
}
$orderDetail = $data['orderDetail'];
$sub_process=array();
foreach($orderDetail['non_meter'] as $key=>$val){
    $sub_process[$val['non_type']]=$val['sub_process'];
}
$non_types=array();
foreach($orderDetail['non_meter'] as $key){
    array_push($non_types,$key['non_type']);
}
$non_types=array_unique($non_types);

$detailItem = $data['detailItem'];

//审批需要填写其他文本参数
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'opinion_extra',
    'user_bu' => $main['user_bu'],
    'paras_group' => 'production_order',
);
$opinion_extra = $dataModel->getCommonParam($selectParam);
$opinion_extra_arr=array();
foreach ($opinion_extra['opinion_extra'] as $ps) {
    $opinion_extra_arr[$ps['type_desc']][$ps['paras_value']] = $ps;
}

//审批需要填写其他附件参数
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'opinion_attachment',
    'user_bu' => $main['user_bu'],
    'paras_group' => 'production_order',
);
$opinion_attachment = $dataModel->getCommonParam($selectParam);
$opinion_attachment_arr=array();
foreach ($opinion_attachment['opinion_attachment'] as $ps) {
    $opinion_attachment_arr[$ps['type_desc']][$ps['paras_value']] = $ps['paras_desc'];
}

//审批需要填写其他选择参数
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'opinion_select',
    'user_bu' => $main['user_bu'],
    'paras_group' => 'production_order',
);
$opinion_select = $dataModel->getCommonParam($selectParam);
$opinion_select_arr=array();
foreach ($opinion_select['opinion_select'] as $ps) {
    $selectParam = array(
        'is_used' => '1', // 可用参数
        'type' => $ps['paras_value']
    );
    $opinion_select_data = $dataModel->getCommonParam($selectParam);
    $opinion_select_arr[$ps['type_desc']][$ps['paras_value']] = array("name"=>$ps['paras_desc'],"data"=>$opinion_select_data);
}

// 获取审核数据
$approveData = $process->getApproveData($param);
// $systemId = "production_order";
//成都走电力流程特殊处理
$flow_manage_param[user_bu]=$main['user_bu'];
if($main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"){
    $flow_manage_param[user_bu]=$main['user_bu']."_".$main['create_user_bu'];
}
$flowArr = $process->getFlowStep($main['type'], $flow_manage_param['user_bu']);
$new_step=$step;
//流程设置中董事长前面一个步骤为必须经过步骤,此功能是为了抛单跳过董事长重复审批
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
        $isHide2=true;//代表抛单
    }
}
$curOperate = $flowArr[$step]['operate'];
$attachObj = $attachModel->getAttachById($id);
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

$sql = " select distinct d.* from inhe_contract_review k left join inhe_contract_detail d on k.form_id=d.form_id where k.form_id='$main[order_num]'";
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

$product_company=$company;
if(in_array($product_company,array("JXINHE","INHE","INHEJX","HKINHE","INHEIAC","INHEIMC"))){
    $product_company="INHE";
}
if(in_array($product_company,array("HKNERGY","INHENERGY"))){
    $product_company="INHENERGY";
}


//其他公司产品下拉列表
//其他公司产品下拉列表
foreach ($selectArr['auto_flow_user'] as $v) {
    if($product_company==$v['user_bu']){
        continue;
    }
    $other_company_product[$v['user_bu']]= $v['extra'];
}

//TODO 慧软CRM合同评审
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
    <script language="Javascript" type="text/javascript" src="../detail.js?v.20220312"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" href="../crm.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <style>
        input[form-verify=required]{
            
        }
    </style>
    <script type="text/javascript">
        $(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer', 'attach'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $.initPage('<?= $operate ?>');
                $.verifyChecked();
                var attachObj = <?= $attachObj ?>;
                $.initAttachList(attachObj);
               // $.formSubmit();
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
                
                form.render();
            });

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
            var E = $('#E').val();
            var S = $('#S').val();
            var N = $('#N').val();
            var G = $('#G').val();
            var O = $('#O').val();
            var D = $('#D').val();
            var is_collective = $('#is_collective').val()=="是"?1:0;
            var EP = $('#if_external_procure').val();
            //获取当前页面的高度
            var height = $(document.body).height();
            //获取当前页面的宽度
            var width = $(document.body).width();
            width=width<600?width*0.9:600;
            layer.open({
                title: "",
                type: 2,
                area: [width+"px","80%"],
                resize:true,
                offset: 'auto', // 自动居中
                content: "next_approver.php?step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&actionType=" + actionType + "&type=" + type + "&E=" + E + "&S=" + S + "&N=" + N + "&G=" + G+ "&O=" + O+ "&D=" + D+"&is_collective="+is_collective+"&EP="+EP,
                success: function(layero, index) {
                    setTimeout(function() {
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    }, 500)
                }
            });
        }
        function removeLine(obj) {
            var table = obj.parentNode.parentNode.parentNode;
            table.removeChild(obj.parentNode.parentNode);
        }

        function createLine(type,product_type) {
            var layer = layui.layer,
                form = layui.form;
                attach = layui.attach;
                laydate = layui.laydate;
            var html = '';
        
                // detailNum++;
            var count_product_number = $("#" + type + "Count").val();
            serialNumber = parseInt(count_product_number) + 1;
            html = '<tr align="center">';
            if(product_type=='LicenseProduct'){
                html += '<td class="TableData" colspan=""><input form-verify="required" type="text" class="layui-input required_field not_disabled" data-type="'+product_type.toLowerCase()+'" id="license_product_name" name="license_product_name_'+ serialNumber + '" /></td>';
                html += '<td class="TableData" colspan=""><input form-verify="required" type="text" class="layui-input required_field not_disabled" data-type="'+product_type.toLowerCase()+'" id="license_product_code" name="license_product_code_'+ serialNumber + '" /></td>';
                html += '<td class="TableData" colspan="8"><textarea  class="layui-input not_disabled" data-type="'+product_type.toLowerCase()+'" id="license_product_remark" name="license_product_remark_'+ serialNumber + '" ></textarea>';
               
            }else if(product_type=='LicenseAccept'){
                html += '<td class="TableData"><input not_disabled="true" form-verify="required" id="acceptance_date" name="acceptance_date_'+ serialNumber + '" class="required_field layui-input not_disabled date" value="<?=$dr["acceptance_date"]?>" /></td>';
                html += '<td class="TableData"> <input form-verify="required" id="acceptance_report_'+ serialNumber + '" type="button" class="required_field layui-btn layui-bg-gray btn-upload not_disabled" style="display: inline!important;" value="上传" /></td>';
                html += '<td class="TableData"><input not_disabled="true" form-verify="required" id="server_end_date" name="server_end_date_'+ serialNumber + '" class="required_field layui-input not_disabled date" value="<?=$dr["server_end_date"]?>" /></td>';
                html += '<td class="TableData" colspan="7"><textarea  class="layui-input not_disabled" data-type="'+product_type.toLowerCase()+'" id="remark" name="remark_'+ serialNumber + '" ></textarea>';
            }
            html += '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>';
            html += '</tr>';
            
            
            $("#" + type + "Count").val(parseInt($("#" + type + "Count").val()) + 1);
            $("#" + type).before(html);
            if(product_type=='LicenseAccept'){
                // 同页面多附件控件使用，默认控件ID与附件type保持一致
                attach.init({
                    elem: "#acceptance_report_" + serialNumber,
                    uploadUrl: "/general/attachment/attach.php?type=" + "acceptance_report_" + serialNumber + "&url=common"
                });
            }
            $(".date").each(function () {
                laydate.render({
                    elem: this,
                    theme: '#027AFF',
                    range: false
                });
            });
            form.render();
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
                <input type="hidden" name="" id="E" value="<?= count($orderDetail['meter_one'])+count($orderDetail['meter_two'])+count($orderDetail['meter_three']) ?>" />
                <input type="hidden" name="soft_count" id="S" value="<?= in_array('Soft',$non_types)?1:0 ?>" />
                <input type="hidden" name="" id="N" value="<?= in_array('Inhenergy',$non_types)?1:0 ?>" />
                <input type="hidden" name="" id="G" value="<?= in_array('Inhegrid',$non_types)?1:0 ?>" />
                <input type="hidden" name="" id="O" value="<?= in_array('Other',$non_types)?1:0 ?>" />
                <input type="hidden" name="" id="D" value="<?= in_array('Cdinhe',$non_types)?1:0 ?>" />
                <input type="hidden" name="type" id="type" value="<?= $main['type'] ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $id ?>" />
                <input type="hidden" name="licenseProduct" id="licenseProduct" value="<?= count($licenseProduct) ?>" />
                <input type="hidden" name="step" id="step" value="<?= $new_step ?>" />
                <table width="100%" align="center">
                    <tr>
                        <td align="center">
                        <?php if ($main['form_type'] == '02') { ?>
                                <img id="page_title" alt="" src="../../../../../images/<?= $_SESSION[LOGIN_USER_ID]=='meterw'&&$main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"?"CDINHE":$main['user_bu'] ?>/<?= str_replace('trial', 'stock', $main['type']) ?>_<?= $_SESSION[LOGIN_USER_ID]=='meterw'&&$main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"?"CDINHE":$main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
                            <?php } elseif($main['form_type'] == '03') { ?>
                                <img  id="page_title" alt="" src="../../../../../images/<?= $_SESSION[LOGIN_USER_ID]=='meterw'&&$main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"?"CDINHE":$main['user_bu'] ?>/<?= str_replace('trial', 'Replenishment', $main['type']) ?>_<?= $_SESSION[LOGIN_USER_ID]=='meterw'&&$main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"?"CDINHE":$main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
                            <?php } elseif($main['form_type'] == '04') { ?>
                                <img  id="page_title" alt="" src="../../../../../images/<?= $_SESSION[LOGIN_USER_ID]=='meterw'&&$main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"?"CDINHE":$main['user_bu'] ?>/<?= str_replace('trial', 'risk', $main['type']) ?>_<?= $_SESSION[LOGIN_USER_ID]=='meterw'&&$main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"?"CDINHE":$main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
                            <?php } else { ?>
                                <img  id="page_title" alt="" src="../../../../../images/<?= $_SESSION[LOGIN_USER_ID]=='meterw'&&$main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"?"CDINHE":$main['user_bu'] ?>/<?= str_replace('rd_', 'trial_', $main['type']) ?>_<?= $_SESSION[LOGIN_USER_ID]=='meterw'&&$main['user_bu']=="INHE"&&$main['create_user_bu']=="CDINHE"?"CDINHE":$main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
                                
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <?php include_once("instructions.php"); ?>
                        </td>
                    </tr>
                </table>
                <br />
                <table class="TableBlock" width="100%" align="center" border="2">
                    <tr>
                        <td>
                            <?php include_once("detail/customer_info.php"); ?>
                            <?php include_once("detail/order_detail.php"); ?>
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