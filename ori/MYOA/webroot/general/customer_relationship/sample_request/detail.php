<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("索样需求申请");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");
require_once("../common/AttachModel.php");
require_once("../common/ProcessModel.php");
require_once("../common/DataModel.php");
require_once '../common/FormUserManageModel.php';
require_once("../common/SystemEnum.php");
require_once("./user_field_permissions.php");
$process = new ProcessModel();
$attachModel = new AttachModel();
$dataModel = new DataModel();
$formUserManageModel = new FormUserManageModel();

$isHide=$formUserManageModel->isHide('sample_request');

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());
$selectParam = array(
    'type' => 'yes_or_no,sample_request_material_flow,sample_request_material_type,company,other_condition,sample_comfirm_end_type,'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$company_names=array();
foreach ($selectArr['company'] as $v) {
    $company_names[$v['paras_value']]=$v['paras_desc'];
}
$sample_comfirm_end_type = array();
foreach ($selectArr['sample_comfirm_end_type'] as $v) {
    $sample_comfirm_end_type[$v['paras_value']] = $v['paras_desc'];
}

$yes_or_no = array();
foreach ($selectArr['yes_or_no'] as $v) {
    $yes_or_no[$v['paras_value']] = $v['paras_desc'];
}
$sample_request_material_flow = array();
foreach ($selectArr['sample_request_material_flow'] as $v) {
    $sample_request_material_flow[$v['paras_value']] = $v['paras_desc'];
}
$sample_request_material_type = array();
foreach ($selectArr['sample_request_material_type'] as $v) {
    $sample_request_material_type[$v['paras_value']] = $v['paras_desc'];
}

$other_condition = array();
foreach ($selectArr['other_condition'] as $v) {
    $other_condition[$v['paras_value']] = $v;
}


$action = new ActionModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$param['id'] = $id;
//获取变更记录
$changeData=$dataModel->getNewChange($id,'sample_request');
$data = $action->queryById($param);
$main = $data['main'];
$whitelist_perm[]=$main[purchasing_engineer];
$param_templ_arr[$main[material_type]]=td_json_decode($main['param_templ']);
$request_main=$main;
$company=$main['user_bu'];
//$process->autoCreateProductOrder(array('form_id'=>$main['form_id'],'user_bu'=>$main['user_bu']));
$orderDetail = $data['orderDetail'];

$detailItem = $data['detailItem'];

//审批需要填写其他文本参数
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'editable_fields',
    'user_bu' => $main['user_bu'],
    'paras_group' => 'sample_request',
);
$editable_fields = $dataModel->getCommonParam($selectParam);
$editable_fields_arr=array();
foreach ($editable_fields['editable_fields'] as $ps) {
    $editable_fields_arr[$ps['type_desc']][editable_fields][] = $ps['paras_value'];
}

//审批需要填写其他文本参数
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'opinion_extra',
    'user_bu' => $main['user_bu'],
    'paras_group' => 'sample_request',
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
    'paras_group' => 'sample_request',
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
    'paras_group' => 'sample_request',
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
// $systemId = "sample_request";
$flowArr = $process->getFlowStep($main['type'], $main['user_bu']);
$new_step=$step;
//流程设置中董事长前面一个步骤为必须经过步骤
if(strpos($main['form_id'],strtolower($main['user_bu']).'_')===0&&$flowArr[$step+1]['approver']=='meterw'){
    $new_step=$step+1;
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


$sql = " select k.*,u.user_name createUserName,d.dept_name deptName,o.approve_user,o.step  from inhe_sample_comfirm k " .
" left join user u on u.user_id = k.user_id " .
" left join department d on d.dept_id = k.organ_id ".
" left join inhe_flow_opinion o on o.form_id=k.form_id and o.status='U' and o.approve_user='$userId' ".
" where sup_form_id='$main[form_id]'" ;
$res = exequery(TD::conn(), $sql);

while($item = mysql_fetch_assoc($res)) {
    $comfirm_data[] = $item;
}
$top_img_company=$main['user_bu'];
//关闭通知
$time = date('Y-m-d H:i:s');
$sql = " update inhe_flow_opinion set approve_time='$time', status='H' where approve_user='$userId' and form_id = '$param[form_id]' and status='U' and work_flow='通知'";
exequery(TD::conn(), $sql);
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
                form.verify({
                    radio_required: function(value,item){
                        var $ = layui.$;
                        var verifyName=$(item).attr('id')
                        , verifyType=$(item).attr('type')
                        ,formElem=$(item).parents('.layui-form')//获取当前所在的form元素，如果存在的话
                        ,verifyElem=formElem.find('input[id='+verifyName+']')//获取需要校验的元素
                        ,isTrue= verifyElem.is(':checked')//是否命中校验
                        ,focusElem = verifyElem.next().find('i.layui-icon');//焦点元素
                        if($("#material_type").val()!='01'&&verifyName=="data_16_1"){
                            return;
                        }
                        if(!isTrue || !value){
                                //定位焦点
                                focusElem.css(verifyType=='radio'?{"color":"#FF5722"}:{"border-color":"#FF5722"});
                                //对非输入框设置焦点
                                focusElem.first().attr("tabIndex","1").css("outline","0").blur(function() {
                                    focusElem.css(verifyType=='radio'?{"color":""}:{"border-color":""});
                                }).focus();
                                return '必填项不能为空！';
                        }
                    },
                    param_required: function(value,item){
                        var $ = layui.$;
                        var verifyName=$(item).attr('id');
                        if($("#material_type").val()!='03'&&verifyName=="data3_10_1"){
                            return;
                        }
                        if($("#material_flow").val()!='01'&&verifyName=="material_type"){
                            return;
                        }
                        
                        if(!value){
                            return "必填项不能为空！";
                        }
                    },
                });
                form.render();
            });
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer', 'attach'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $.initPage('<?= $operate ?>');
                $.verifyChecked();
                var attachObj = <?= $attachObj ?>;
                $.initAttachList(attachObj);
                //$.formSubmit();
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
                                layer.alert("供应商、预计价格、预计回样时间、预计批量采购周期不能为空");
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

            var A = $('#sample_testing_result').val()=="01"?1:0;

            layer.open({
                title: "",
                type: 2,
                area: ['600px', '80%'],
                resize:true,
                content: "next_approver.php?step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&actionType=" + actionType + "&type=" + type + "&A=" + A ,
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
                html += '<td class="TableData" colspan=""><input form-verify="required" type="text" class="layui-input required_field not_disabled" data-type="'+product_type.toLowerCase()+'" id="supplier_name" name="supplier_name_'+ serialNumber + '" /></td>';
                html += '<td class="TableData" colspan=""><input form-verify="required" type="text" class="layui-input required_field not_disabled" data-type="'+product_type.toLowerCase()+'" id="supplier_price" name="supplier_price_'+ serialNumber + '" /></td>';
                html += '<td class="TableData" colspan=""><input form-verify="required" type="text" class="layui-input required_field not_disabled " data-type="'+product_type.toLowerCase()+'" id="supplier_date" name="supplier_date_'+ serialNumber + '" /></td>';
                html += '<td class="TableData" colspan=""><input form-verify="required" type="text" class="layui-input required_field not_disabled" data-type="'+product_type.toLowerCase()+'" id="procurement_cycle" name="procurement_cycle_'+ serialNumber + '" />';
               // html += '<td class="TableData" colspan="8"><textarea  class="layui-input not_disabled" data-type="'+product_type.toLowerCase()+'" id="license_product_remark" name="license_product_remark_'+ serialNumber + '" ></textarea>';
               
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
                <input type="hidden" name="soft_count" id="E" value="<?= count($orderDetail['meter_one'])+count($orderDetail['meter_two'])+count($orderDetail['meter_three']) ?>" />
                <input type="hidden" name="soft_count" id="S" value="<?= in_array('Soft',$non_types)?1:0 ?>" />
                <input type="hidden" name="soft_count" id="N" value="<?= in_array('Inhenergy',$non_types)?1:0 ?>" />
                <input type="hidden" name="soft_count" id="G" value="<?= in_array('Inhegrid',$non_types)?1:0 ?>" />
                <input type="hidden" name="type" id="type" value="<?= $main['type'] ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $id ?>" />
                <input type="hidden" name="step" id="step" value="<?= $new_step ?>" />
                <table width="100%" align="center">
                    <tr>
                        <td align="center">
                        <?php include_once("top_image.php"); ?>
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
                                include_once("templet/detail_".$main[material_type]."_".$main[param_templ_v].".php");
                            ?>
                            <?php
                                if (($main['status']=="Y"||$main['status']=="E")&&$operate=='detail') {
                                    include_once("../approve_opinion.php");
                                }else{
                                    if(strpos($main['form_id'],strtolower($main['user_bu']).'_')===0){
                                        include_once("detail/approve_opinion_auto.php"); 
                                    }else{
                                        include_once("detail/approve_opinion.php"); 
                                    }
                                }
                            ?>
                            
                            <?if($comfirm_data):?>
<table id="tab5"  class="TableBlock" style="width: 100%;margin:auto;">
    <tr>
        <td class="TableHeader" colspan="9" style="color: red;text-align:center">索样确认</td>
    </tr>
    <tr class="TableContent" id="tabHead" style="text-align: center;">
        <td nowrap style="text-align: center;">流程单号</td>
        <td nowrap style="text-align: center;">采购人员</td>
        <td nowrap style="text-align: center;">供应商</td>
        <td nowrap style="text-align: center;">预计价格</td>
        <td nowrap style="text-align: center;">预计回样时间</td>
        <td nowrap style="text-align: center;">预计批量采购周期</td>
        <td nowrap style="text-align: center;">流程结束填写</td>
        <td nowrap style="text-align: center;">办理状态</td>
        <td nowrap style="text-align: center;">操作</td>
    </tr>
    <tbody id="tabBody" name="tabBody" class="TableContent">
    <?php $colNum=0; foreach ($comfirm_data as $dn => $val) : if ($dn == 0) $dn = ''; ?>
        <?php  ++$colNum;  ?>
        <tr align="center" class="TableLine<?= $line % 2 == 0 ? 1 : 2 ?>">
        <td class="TableData"><?= $val["form_id"]?></td>
        <td class="TableData"><?= $val["createUserName"]?></td>
        <td align="center"><?= $val["supplier"] ?></td>
        <td class="TableData"><?= in_array($_SESSION[LOGIN_USER_ID],$whitelist_perm)?$val["supplier_price"]:""?></td>
        <td class="TableData"><?= $val["arrival_time"]?></td>
        <td class="TableData"><?= $val["procurement_cycle"]?></td>
        <td align="center"><?= $sample_comfirm_end_type[$val["end_type"]]?><?= $val["end_remark"]?'：'.$val["end_remark"]:''?></td>
        <td class="TableData">
        <?php if($val["closed"] == 'Y'&&$val["status"] != 'Y'&&$val["status"] != 'R'):?>
                            <font class="<?= SystemEnum::$STATUS_CSS['N'] ?>"><?= '终止' ?></font>
                        <?elseif($val["closed"] == 'Y'&&($val["status"] == 'Y'||$val["status"] != 'R')):?>
                            <font class="<?= SystemEnum::$STATUS_CSS['Y'] ?>"><?= '结束' ?></font>
                        <?else:?>
                            <font class="<?= SystemEnum::$STATUS_CSS[$val["status"]] ?>"><?= SystemEnum::$STATUS[$val["status"]] ?></font>
                        <?php endif; ?>
        </td>
        <td class="TableData">
        <a href="javascript:void(0);" class="newColor" onclick="pageOperate('./sample_comfirm/detail.php?id=<?= $val[form_id] ?>&operate=detail','detail');return false;">查阅</a>&nbsp;
                        <?php if ($val["status"] != 'T') : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('../common/list/process_schedule.php?id=<?= $val[form_id] ?>','smallModel');return false;">查看进度</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['user_id'] || $systemAdmin) && $val["status"] == 'T' || ($val["status"] == 'N'&&$val['step']==1)) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('./sample_comfirm/modify.php?id=<?= $val[form_id] ?>','modify');return false;">修改</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['approve_user']) && ($val["status"] == 'P' || ($val["status"] == 'N'&&$val['step']!=1))) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('./sample_comfirm/detail.php?id=<?= $val[form_id] ?>&operate=approve&step=<?=$val[step]?>','detail');return false;">办理</a>&nbsp;
                        <?php endif; ?>
                        <?php if (($userId == $val['user_id'] || $systemAdmin) && ($val["status"] == 'T' )) : ?>
                            <a href="javascript:void(0);" class="newColor" onclick="pageOperate('./sample_comfirm/action.php?action=Delete&id=<?= $val[form_id] ?>','delete');return false;">删除</a>&nbsp;
                        <?php endif; ?>
        </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<br/>
<?endif;?>
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