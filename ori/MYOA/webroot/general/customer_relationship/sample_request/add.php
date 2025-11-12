<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("定制料索样申请");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");
require_once("../common/UserModel.php");
require_once("../common/DataModel.php");
$actionModel = new ActionModel();
$userModel = new UserModel();
$dataModel = new DataModel();
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userInfo = $userModel->getUserInfo($userId);
if (empty($company)) {
    $company = $userModel->getUserBu($deptId);
}
$detailItem = $actionModel->getOrderDetailItem();
$userInfo = $userModel->getUserInfo($userId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'yes_or_no,sample_request_material_flow,sample_request_material_type,company,'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$sample_request_material_type=array();
foreach ($selectArr['sample_request_material_type'] as $v) {
    $sample_request_material_type[]=$v['paras_value'];
}
$company_names=array();
foreach ($selectArr['company'] as $v) {
    $company_names[$v['paras_value']]=$v['paras_desc'];
}
$sample_request_material_flow = array();
foreach ($selectArr['sample_request_material_flow'] as $v) {
    $sample_request_material_flow[$v['paras_value']] = $v['paras_desc'];
}

$top_img_company=$company;
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
    <script language="Javascript" type="text/javascript" src="../add.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css?v=20250721">
    <style>
        .layui-form-checked[lay-skin=primary] i{
            border-color:#597FC0 !important;
            background-color:#FFF !important;
            color:#597FC0 !important;
            font-weight: bold;
        }
        .layui-form-checkbox[lay-skin=primary]:hover i{
            border-color:#597FC0 !important;
        }
        .layui-form-radio>i:hover, .layui-form-radioed>i{
            color: #597FC0 !important;
        }
    </style>
    <script type="text/javascript">
        $(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $.initDate();
                $.initTextarea();
                $.initAttach();
                $.formSubmit();
                lineInit();
               

                 //必填增加背景色
                setTimeout(function(){
                    $('select[lay-verify="required"]').next().find('.layui-select-title input').css({'background-color':'#f9fbd5'});
                },500);
            
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
                form.on('select(material_flow)', function(data) {
                    if(data.value=='01'){
                        $("#material_type_div").show()
                    }else{
                        setTimeout(function () {
                            $('select[name="material_type"]').next().find('.layui-anim').children('dd[lay-value=""]').click();
                        },3000);
                        $("#material_type_div").hide()
                        $(".param_templ").hide();
                    }
                    if(data.value=='03'){
                        $('select[name="mold_opening"]').next().find('.layui-anim').children('dd[lay-value="02"]').click();
                    }else{
                        $('select[name="mold_opening"]').next().find('.layui-anim').children('dd[lay-value=""]').click();
                        
                    }
                    form.render();
                });
                form.on('select(material_type)', function(data) {
                    //data.elem[data.elem.selectedIndex].text //data.value
                    $(".param_templ").hide();
                    $(".pt"+data.value).show();
                    $(".param_templ").find('input,textarea,select').not('[actionType="Approve"],[actionType="Back"],[actionType="Close"]').prop('disabled', true);
                 // layui单选选中不置灰
                    $(".pt"+data.value).find('input,textarea,select').prop('disabled', false);
                    //$(".param_templ").css('display','none');
                    //$(".pt"+data.value).css('display','');
                    if(data.value=='01'){
                        $('select[name="mold_opening"]').next().find('.layui-anim').children('dd[lay-value="02"]').click();
                    }else{
                        $('select[name="mold_opening"]').next().find('.layui-anim').children('dd[lay-value=""]').click();
                        
                    }
                    form.render();
                });
                // 监听radio单选按钮
                form.on('radio(other_info)', function(data){
                    //console.log($(data.elem).parent().find(".other_info")); // 得到radio原始DOM对象
                    //console.log(data.value); // 被点击的radio的value值

                    if(data.value == '其它'){
                        $(data.elem).parent().find(".other_info").removeAttr("readonly");
                    } else {
                        $(data.elem).parent().find(".other_info").attr("readonly","readonly");
                        $(data.elem).parent().find(".other_info").val("");
                    }
                });
                form.render();
            });
        });
    </script>

<body>
    <div style="width: 100%;height:auto;margin:auto;">
        <form id="mainForm" class="layui-form" lay-filter="mainForm">
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
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_sample_request" />
                <input type="hidden" name="flowType" value="syxq" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" id="type" name="type" value="sample_request" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $company ?>" />
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
                <table class="TableBlock" width="100%" align="center">
                    <tr>
                        <td colspan="6">
                            <?php include_once("form_module/customer_info.php"); ?>
                            <?php include_once("form_module/order_detail.php"); ?>
                            <?php 
                            foreach ($selectArr['sample_request_material_type'] as $v) {
                                include_once("templet/".$v[paras_value]."_".$v[extra].".php");
                            }
                            
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

</html>