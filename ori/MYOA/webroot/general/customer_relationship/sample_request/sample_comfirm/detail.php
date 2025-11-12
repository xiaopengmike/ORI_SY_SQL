<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("定制料索样确认");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");
require_once("../../common/AttachModel.php");
require_once("../../common/ProcessModel.php");
require_once("../../common/DataModel.php");
require_once '../../common/FormUserManageModel.php';
require_once("../user_field_permissions.php");
$process = new ProcessModel();
$attachModel = new AttachModel();
$dataModel = new DataModel();
$formUserManageModel = new FormUserManageModel();

$isHide=$formUserManageModel->isHide('sample_comfirm');

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,check_opinion,yes_or_no,company,other_condition,sample_request_material_flow,sample_request_material_type,sample_comfirm_end_type,'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$company_names=array();
foreach ($selectArr['company'] as $v) {
    $company_names[$v['paras_value']]=$v['paras_desc'];
}
$sample_request_material_flow = array();
foreach ($selectArr['sample_request_material_flow'] as $v) {
    $sample_request_material_flow[$v['paras_value']] = $v['paras_desc'];
}
$sample_request_material_type = array();
foreach ($selectArr['sample_request_material_type'] as $v) {
    $sample_request_material_type[$v['paras_value']] = $v['paras_desc'];
}

$sample_comfirm_end_type = array();
foreach ($selectArr['sample_comfirm_end_type'] as $v) {
    $sample_comfirm_end_type[$v['paras_value']] = $v['paras_desc'];
}
$yes_or_no = array();
foreach ($selectArr['yes_or_no'] as $v) {
    $yes_or_no[$v['paras_value']] = $v['paras_desc'];
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
$changeData=$dataModel->getNewChange($id,'sample_comfirm');
$data = $action->queryById($param);
$main = $data['main'];
$company=$main['user_bu'];
//$process->autoCreateProductOrder(array('form_id'=>$main['form_id'],'user_bu'=>$main['user_bu']));
$orderDetail = $data['orderDetail'];

$detailItem = $data['detailItem'];

//审批需要填写其他文本参数
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'editable_fields',
    'user_bu' => $main['user_bu'],
    'paras_group' => 'sample_comfirm',
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
    'paras_group' => 'sample_comfirm',
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
    'paras_group' => 'sample_comfirm',
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
    'paras_group' => 'sample_comfirm',
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
// $systemId = "sample_comfirm";
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
$sql = " select k.*,u.user_name createUserName,d.dept_name deptName from inhe_sample_request k " .
" left join user u on u.user_id = k.user_id " .
" left join department d on d.dept_id = k.organ_id where form_id='$main[sup_form_id]'" ;
$res = exequery(TD::conn(), $sql);

if($item = mysql_fetch_assoc($res)) {
    $whitelist_perm[]=$item[purchasing_engineer];
    $request_main = $item;
}
$param_templ_arr[$request_main[material_type]]=td_json_decode($request_main['param_templ']);

$top_img_company=$main['user_bu'];
//关闭通知
$time = date('Y-m-d H:i:s');
$sql = " update inhe_flow_opinion set approve_time='$time', status='H' where approve_user='$userId'  and form_id = '$param[form_id]' and status='U' and work_flow='通知'";
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
    <script language="Javascript" type="text/javascript" src="../../detail.js?v.20220312"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" href="../../crm.css">
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
                //自定义验证规则
                form.verify({
                    radio_required: function(value,item){
                        var $ = layui.$;
                        var verifyName=$(item).attr('name')
                        , verifyType=$(item).attr('type')
                        ,formElem=$(item).parents('.layui-form')//获取当前所在的form元素，如果存在的话
                        ,verifyElem=formElem.find('input[name='+verifyName+']')//获取需要校验的元素
                        ,isTrue= verifyElem.is(':checked')//是否命中校验
                        ,focusElem = verifyElem.next().find('i.layui-icon');//焦点元素
                        if(!isTrue || !value){
                                //定位焦点
                                focusElem.css(verifyType=='radio'?{"color":"#FF5722"}:{"border-color":"#FF5722"});
                                //对非输入框设置焦点
                                focusElem.first().attr("tabIndex","1").css("outline","0").blur(function() {
                                    focusElem.css(verifyType=='radio'?{"color":""}:{"border-color":""});
                                }).focus();
                                return '必选项请选择！';
                        }
                        
                    },
                    param_required: function(value,item){
                        var $ = layui.$;
                        var verifyName=$(item).attr('id');
                        // if($("#material_type").val()!='03'&&verifyName=="data3_10_1"){
                        //     return;
                        // }
                        // if($("#material_flow").val()!='01'&&verifyName=="material_type"){
                        //     return;
                        // }
                        
                        if(!value){
                            return "必填项不能为空！";
                        }
                    },
                    upload_attach: function(value,item){
                        var $ = layui.$;
                        var verifyName=$(item).attr('name')
                        , verifyType=$(item).attr('type')
                        ,formElem=$(item).parents('.layui-form')//获取当前所在的form元素，如果存在的话
                        ,verifyElem=formElem.find('input[name='+verifyName+']')//获取需要校验的元素
                        ,isTrue= verifyElem.parent().find("a").length>0//是否存在文件
                        ,focusElem = verifyElem.next().find('i.layui-icon');//焦点元素
                        //console.log(verifyElem);
                        //console.log(verifyElem.parent().find("input[name='attach[]']").length);
                        if(!isTrue){
                                //定位焦点
                                focusElem.css(verifyType=='radio'?{"color":"#FF5722"}:{"border-color":"#FF5722"});
                                //对非输入框设置焦点
                                focusElem.first().attr("tabIndex","1").css("outline","0").blur(function() {
                                    focusElem.css(verifyType=='radio'?{"color":""}:{"border-color":""});
                                }).focus();
                                return '请上传附件';
                        }
                        
                    },
                });
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
                                layer.alert("供应商不能为空");
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
            var S = $('#S').val();
            var N = $('#N').val();
            var G = $('#G').val();
            var is_collective = $('#is_collective').val()=="是"?1:0;
            var request_id = $('#request_id').val();
            layer.open({
                title: "",
                type: 2,
                area: ['600px', '80%'],
                resize:true,
                content: "next_approver.php?step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&actionType=" + actionType + "&type=" + type+ "&A=" + A ,
                success: function(layero, index) {
                    setTimeout(function() {
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    }, 500)
                }
            });
        }
        function End(url){
            //console.log(111);
            layer.confirm("确定终止流程吗？", {
                icon: 3,
                title: '提示'
            }, function (index) {
                $.post(url, {}, function (data) {
                        var jsonOb = eval("(" + data + ")");
                        if (jsonOb.status == "success") {
                            layer.alert("流程已终止", {
                                icon: 1
                            }, function () {
                                //parent.location.reload();
                                //location.href =window.location.href;
                                window.close();
                                layer.closeAll();
                            });
                        } else {
                            layer.alert(jsonOb.msg, {
                                icon: 5
                            });
                        }
                    });
                layer.close(index);
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
                                    <?php if ($curOperate == 'approve') : ?>
                                        <input  onclick="End('action.php?action=End&form_id=<?= $id ?>');return false;" name="btn_end"  value="终止" type=button class="layui-btn layui-btn-oa layui-btn-sm not_disabled" />
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
                <input type="hidden" name="request_id" id="request_id" value="<?= $main['sup_form_id'] ?>" />
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
                                if (($main['status']=="Y"||$main['status']=="E")&&$operate=='detail') {
                                    include_once("../../approve_opinion.php");
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
                                include_once("../../back_record.php");
                            }
                            ?>
                            <?php
                            if (count($passRecord) > 0) {
                                include_once("../../pass_record.php");
                            }
                            ?>
                        </td>
                    </tr>
                </table>
                <?php include_once("../../common/list/change_html.php"); ?>
            </div>
            <!--endprint-->
        </form>
    </div>
</body>

</html>