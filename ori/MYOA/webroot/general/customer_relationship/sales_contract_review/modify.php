<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("销售合同评审");
require_once("inc/header.inc.php");
require_once("common/Common.php");
require_once("ActionModel.php");
require_once("../common/CommonMethodModel.php");
require_once '../common/AttachModel.php';
require_once("../common/UserModel.php");
require_once("../common/ProcessModel.php");
require_once("../common/DataModel.php");
if (empty($id)) {
    Message("提示", "数据不存在！");
    exit;
}
$userId = $_SESSION['LOGIN_USER_ID'];
$user = new UserModel();
$userInfo = $user->getUserInfo($userId);

$model = new CommonMethodModel();
$processModel = new ProcessModel();
$dataModel = new DataModel();
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,currency,yes_or_no,quote_source,sale_model,company,product_brand,product_detail_type,auto_flow_user,
    company,funds_come,product_project_type,license_service,license_product,license_type',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$company_names=array();
foreach ($selectArr['company'] as $v) {
    $company_names[$v['paras_value']]=$v['paras_desc'];
}
$license_service=array();
foreach ($selectArr['license_service'] as $v) {
    $license_service[$v['paras_value']]=$v['paras_desc'];
}
$product_detail_types=array();
foreach ($selectArr['product_detail_type'] as $v) {
    $product_detail_types[]=$v['paras_value'];
}
$project_star = array();
foreach ($selectArr['project_star'] as $v) {
    $project_star[$v['paras_value']] = $v;
}

$param['id'] = $id;
$action = new ActionModel();
$data = $action->queryById($param);
$main = $data['main'];
$company=$main['user_bu'];
$product = $data['product'];
$licenseProduct = $data['licenseProduct'];
$contract = $data['contract'];
$settle = $data['settle'];
$settleModel = $data['settleModel'];
// 获取审核数据
$approveData = $processModel->getApproveData($param);
$attachModel = new AttachModel();
$attachObj = $attachModel->getAttachById($id);
$isChange = '';
$submitAction = 'Submit';
$saveAction = 'Save';
if (strpos($main['type'], '_change') != false) {
    $isChange = '_change';
    $submitAction = 'Change';
    $saveAction = 'ChangeSave';
}
$brand_html = '<div id="product_brand_select" hidden="hidden">';
$brand_html .= '<option value="">请选择</option>';
foreach ($selectArr['product_brand'] as $v) {
    $brand_html .= '<option value="' . $v['paras_value'] . '">' . $v['paras_desc'] . '</option>';
}
$brand_html .= '</div>';

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

$product_company=$main[user_bu];
if(in_array($product_company,array("JXINHE","INHE","INHEJX","HKINHE","INHEIAC","INHEIMC"))){
    $product_company="JXINHE";
}
if(in_array($product_company,array("HKNERGY","INHENERGY"))){
    $product_company="INHENERGY";
}

//其他公司产品下拉列表
$other_company_product_html = '<div id="other_company_product_select" hidden="hidden">';
$other_company_product_html .= '<option value="">请选择</option>';
$auto_flow_user=array();
$other_company=array();
foreach ($selectArr['auto_flow_user'] as $v) {
    $auto_flow_user[]=$v['paras_value'];
    if(($product_company=="JXINHE"&&$v['user_bu']=="INHE")||$product_company==$v['user_bu']){
        continue;
    }
    $other_company[]=$v;
    $other_company_product_html .= '<option value="' . $v['user_bu'] . '">' . $v['extra'] . '</option>';
}
$other_company_product_html .= '</div>';
?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script src="/inc/js/module.js"></script>
    <script language="Javascript" type="text/javascript" src="../shim.min.js"></script>
    <script language="Javascript" type="text/javascript" src="../xlsx.full.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="main.js?v=20241224"></script>
    <script language="Javascript" type="text/javascript" src="../add.js?v=20220607"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <style>
.required_field{
    background-color: #f9fbd5;
    }
</style>
    <script type="text/javascript">

        $(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer', 'laydate', 'attach'], function() {
                var layer = layui.layer,
                    form = layui.form;
                    layer.alert('附件请按商务和技术内容分别上传', {
                        icon: 0,
                        skin: 'layer-ext-demo' //见：扩展说明
                        })
                $.initDate();
                $.initTextarea();
                var attachObj = <?= $attachObj ?>;
                $.initAttachList(attachObj);
                //$.formSubmit();
                form.on('submit(submit)', function (data) {
                        //TODO 慧软CRM合同评审
                        // if($("#name_softlicenseproduct").length>0&&(ifEmpty($("#contract_begin_time").val())||ifEmpty($("#contract_end_time").val()))){
                        //         layer.alert("请填写慧联软通产品明细中合同有效期的开始时间和结束时间", {
                        //             icon: 5
                        //         });
                        //         return false;
                        // }
                        // if($("#name_softlicenseproduct").length>0&&$("#name_soft").length<=0){
                        //         layer.alert("请填写慧联软通合同产品明细", {
                        //             icon: 5
                        //         });
                        //         return false;
                        // }
                        // if($("select[name=has_soft]").val()=="01"&&$("#name_soft").length>0&&$("#name_softlicenseproduct").length<=0){
                        //         layer.alert("请填写慧联软通授权产品明细", {
                        //             icon: 5
                        //         });
                        //         return false;
                        // }
                        // //console.log($("select[name=has_soft]").val());
                        // if($("#name_soft").length>0&&ifEmpty($("select[name=has_soft]").val())){
                        //         layer.alert("请选择是否包含授权产品", {
                        //             icon: 5
                        //         });
                        //         return false;
                        // }
                        if(ifEmpty($("#project_name").val())&&($("#user_bu").val()=='INHEGRID'||$("#user_bu").val()=='INHENERGY'||$("#user_bu").val()=='HKNERGY')){
                            //电网耐吉没有立项的不同提示
                            var msg = $("#user_bu").val()=='INHENERGY'||$("#user_bu").val()=='HKNERGY'?"请问是否无需立项,直接发起合同评审?":"请问是否合同金额50万人民币以下（含），且为非招投标项目，无需立项，直接发起合同评审？";
                            layer.confirm(msg, {
                                btn: ["确定","取消"] //按钮
                            }, function(){
                                var btnVal = data.elem.value;
                                var actionType = $(data.elem).attr("actionType");
                                var url = "action.php?action=" + actionType;
                                // 单击之后提交按钮不可选,防止重复提交
                                if (actionType == 'Submit' || actionType == 'Change'){
                                    $('[name="btn_submit"]').addClass('layui-btn-disabled');
                                    $('[name="btn_submit"]').attr('disabled', 'disabled');
                                    $('[name="btn_save"]').addClass('layui-btn-disabled');
                                    $('[name="btn_save"]').attr('disabled', 'disabled');
                                }
                                
                                $.post(url, $("#mainForm").serializeArray(), function (data) {
                                    var reJson = null;
                                    try {
                                        reJson = eval('(' + data + ')');
                                    } catch (e) {
                                        layer.alert('服务器异常: ' + e, {
                                            icon: 5
                                        });
                                        $('[name="btn_submit"]').removeClass('layui-btn-disabled');
                                        $('[name="btn_submit"]').removeAttr('disabled');
                                        $('[name="btn_save"]').removeClass('layui-btn-disabled');
                                        $('[name="btn_save"]').removeAttr('disabled');
                                        return;
                                    }
                                    if (reJson.code == "200") {
                                        // 返回form_id值
                                        $("#form_id").val(reJson.data);
                                        if (actionType == 'Submit' || actionType == 'Change') {
                                            $.getNextApprover(reJson.data);
                                        } else {
                                            layer.alert(btnVal + "成功!", {
                                                icon: 1
                                            }, function (index, layero) {
                                                parent.layer.close(index);
                                                parent.closeIframe();
                                                window.opener.location.reload();
                                            });
                                        }
                                    } else {
                                        fail_msg=reJson.msg?reJson.msg:btnVal + '失败！';
                                        layer.alert(fail_msg, {
                                            icon: 5
                                        });
                                        $('[name="btn_submit"]').removeClass('layui-btn-disabled');
                                        $('[name="btn_submit"]').removeAttr('disabled');
                                        $('[name="btn_save"]').removeClass('layui-btn-disabled');
                                        $('[name="btn_save"]').removeAttr('disabled');
                                    }
                                });
                            }, function(){
                                
                            });
                        }else{
                            var btnVal = data.elem.value;
                            var actionType = $(data.elem).attr("actionType");
                            var url = "action.php?action=" + actionType;
                            // 单击之后提交按钮不可选,防止重复提交
                            if (actionType == 'Submit' || actionType == 'Change'){
                                $('[name="btn_submit"]').addClass('layui-btn-disabled');
                                $('[name="btn_submit"]').attr('disabled', 'disabled');
                                $('[name="btn_save"]').addClass('layui-btn-disabled');
                                $('[name="btn_save"]').attr('disabled', 'disabled');
                            }
                            
                            $.post(url, $("#mainForm").serializeArray(), function (data) {
                                var reJson = null;
                                try {
                                    reJson = eval('(' + data + ')');
                                } catch (e) {
                                    layer.alert('服务器异常: ' + e, {
                                        icon: 5
                                    });
                                    $('[name="btn_submit"]').removeClass('layui-btn-disabled');
                                    $('[name="btn_submit"]').removeAttr('disabled');
                                    $('[name="btn_save"]').removeClass('layui-btn-disabled');
                                    $('[name="btn_save"]').removeAttr('disabled');
                                    return;
                                }
                                if (reJson.code == "200") {
                                    // 返回form_id值
                                    $("#form_id").val(reJson.data);
                                    if (actionType == 'Submit' || actionType == 'Change') {
                                        $.getNextApprover(reJson.data);
                                    } else {
                                        layer.alert(btnVal + "成功!", {
                                            icon: 1
                                        }, function (index, layero) {
                                            parent.layer.close(index);
                                            parent.closeIframe();
                                            window.opener.location.reload();
                                        });
                                    }
                                } else {
                                    fail_msg=reJson.msg?reJson.msg:btnVal + '失败！';
                                    layer.alert(fail_msg, {
                                        icon: 5
                                    });
                                    $('[name="btn_submit"]').removeClass('layui-btn-disabled');
                                    $('[name="btn_submit"]').removeAttr('disabled');
                                    $('[name="btn_save"]').removeClass('layui-btn-disabled');
                                    $('[name="btn_save"]').removeAttr('disabled');
                                }
                            });
                        }
                    });       
                $("#business_attribution").click(function() {
                    layer.open({
                        title: '选择人员',
                        type: 2,
                        skin: 'layui-layer-rim',
                        area: ['500px', '500px'],
                        fixed: false,
                        maxmin: true,
                        offset: '50px',
                        content: '../common/list/single_type_list.php?domId=business_attribution',
                        moveOut: true
                    });
                    form.render();
                });

                form.on('select(customer_name)', function(data) {
                    var url = "action.php?action=Retrieve";
                    $.post(url, {
                        dataType: 'findContractParty',
                        customer_name: data.value
                    }, function(reData) {
                        var reJson = null;
                        try {
                            reJson = eval('(' + reData + ')');
                        } catch (e) {
                            layer.alert('服务器异常: ' + e, {
                                icon: 5
                            });
                            return;
                        }
                        if (reJson.code == "200") {
                            $("#contract_party").empty();
                            var tpl = "";
                            tpl += "<option value=''>请选择</option>";
                            $.each(reJson.data, function(index, value) {
                                tpl += "<option value='" + value + "'>" + value + "</option>";
                            });
                            $("#contract_party").html(tpl);
                            form.render('select');
                        } else {
                            layer.alert('获取数据失败！', {
                                icon: 5
                            });
                        }
                    });
                    form.render();
                });

                $("[name='project_star']").prop('disabled', true);
                // layui下拉只读显示不置灰
                $.each($("[name='project_star']"), function(i, n) {
                    var optText = $(n).find("option:selected").text();
                    $(n).next().find("input").val(optText);
                    $(n).remove();
                    $('dl').remove();
                });
                form.render();
            });
        });

        function returnProjectInfo(reData) {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $("#project_name").val(reData.project_code);
                $("#project_star").val(reData.project_star);
                var projectStarArr = ["一星级", "二星级", "三星级", "四星级", "五星级", "不符合"];
                var starDesc = projectStarArr[parseInt(reData.project_star)];
                $("[data-type='project_star']").html('<font style="color: red;font-size:20px">' + starDesc + '</font>');
                $("#star_icon").html('<font style="color: red;font-size:20px">' + reData.star_icon + '</font>');
                var customerArr = reData.customer_name.split('||');
                $("#customer_name").empty();
                var tpl = "";
                tpl += "<option value=''>请选择</option>";
                $.each(customerArr, function(index, value) {
                    if (!ifEmpty(value)) {
                        tpl += "<option value='" + value + "'>" + value + "</option>";
                    }
                });
                $("#customer_name").html(tpl);
                layer.closeAll();
                form.render();
            });
        }

        function returnUser(userName, userId, domId,deptId) {
            $("#" + domId).val(userName);
            $("#" + domId).parent().find("[data-type='business_user_id']").val(userId); //取userId隐藏栏位赋值
            $("#" + domId).parent().find("[data-type='business_dept_id']").val(deptId); //取deptId隐藏栏位赋值
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
                                <input lay-submit lay-filter="submit" name="btn_submit" actionType="<?= $submitAction ?>" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input lay-submit lay-filter="submit" name="btn_save" actionType="<?= $saveAction ?>" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="width:80%;margin:1% 9%;">
                <input type="hidden" name="form_id" id="form_id" value="<?= $id ?>" />
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_contract_review" />
                <input type="hidden" name="flowType" value="INHESC" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" id="type" name="type" value="sales_contract_review<?= $isChange ?>" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="title" id="title" value="<?= $main['title'] ?>" />
                <input type="hidden" name="create_user_bu" value="<?= $userInfo['company_user_bu'] ?>" />
                <?= $brand_html ?>
                <?= $license_product_html ?>
                <?= $license_type_html ?>
                <?= $other_company_product_html ?>
                <table width="100%" align="center">
                    <tr>
                        <td align="center">
                            <img alt="" src="../../../../../images/<?= $main['user_bu'] ?>/sales_contract_review<?= $isChange ?>_<?= $main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
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
                            <?php require_once("change/main_info.php"); ?>
                            <?php require_once("change/product_detail.php"); ?>
                            <?php require_once("change/contract_info.php"); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

</html>