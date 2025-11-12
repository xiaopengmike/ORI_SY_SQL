<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_org.php");
require_once("inc/header.inc.php");
$HTML_PAGE_TITLE = _("销售合同评审");
require_once("common/Common.php");
require_once("ActionModel.php");
require_once("../common/CommonMethodModel.php");
require_once("../common/UserModel.php");
require_once("../common/DataModel.php");
$commonModel = new CommonMethodModel();
$userModel = new UserModel();
$actionModel = new ActionModel();
$dataModel = new DataModel();
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$date = date('Y-m-d');
if (empty($company)) {
    $company = $userModel->getUserBu($deptId);
}
$continentArr = $dataModel->getContinentData();
$countryArr = $dataModel->getCountryData();
$userInfo = $userModel->getUserInfo($userId);
$param = array(
    'fieldName' => 'form_id',
    'tableName' => 'inhe_contract_review',
    'flowType' => 'INHESC',  // INHESC2020061601
    'reset' => 'day',
    'digit' => '2'
);

// 下拉列表数据
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,check_opinion,currency,yes_or_no,quote_source,sale_model,
    product_brand,product_detail_type,company,funds_come,product_project_type,license_product,license_type',
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
$settleModel = $actionModel->querySettleModel(array('user_bu' => $company));
//客户列表
$paramArr = array(
    'user_id' => $userId
);
$customerSelect = $dataModel->getCustomerList($paramArr);
//品牌下拉列表
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

$param['id'] = $id;
$action = new ActionModel();
$data = $action->queryById($param);
$main = $data['main'];
$main['type']='sales_contract_review_change';
$product = $data['product'];
$contract = $data['contract'];
$settle = $data['settle'];
$settleModel = $data['settleModel'];
// 获取审核数据
$attachModel = new AttachModel();
$attachObj = $attachModel->getAttachById($id);

?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
    <meta http-equiv="expires" content="0">
    <script src="/inc/js/module.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="main.js?v=20240805"></script>
    <script language="Javascript" type="text/javascript" src="../add.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <style>
.required_field{
    background-color: #f9fbd5;
    }
</style>
    <script>
        $(function() {
            layui.extend({
                attach: '/common/js/extends/attach'
            }).use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $.initDate();
                $.initTextarea();
                $.initAttach();
                $.formSubmit();
                form.verify({
                    formIdReg:[
                    /(^[a-zA-Z0-9_][A-Za-z0-9-_. ]+$)/
                    ,'合同编号不能为空，请先通过销售合同取号获取合同编号'
                    ]
                });
                $("#form_id").click(function() {
                    var index = layui.layer.open({
                        title: "合同编号列表",
                        type: 2,
                        content: "contract_number_list.php"+"?company=<?=$company?>",
                        area: ['600px', '650px'],
                        success: function(layero, index) {
                            setTimeout(function() {
                                layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                    tips: 3
                                });
                            }, 500)
                        }
                    });
                });

                $("#project_name").click(function() {
                    var index = layui.layer.open({
                        title: "项目代号列表",
                        type: 2,
                        content: "project_list.php",
                        area: ['600px', '650px'],
                        success: function(layero, index) {
                            setTimeout(function() {
                                layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                    tips: 3
                                });
                            }, 500)
                        }
                    });
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
                //电网提交增加判断
                if($("#user_bu").val()=='INHEGRID'){
                    form.on('submit(submit)', function (data) {
                        if(ifEmpty($("#project_name").val())){
                            layer.confirm("请问是否合同金额50万人民币以下（含），且为非招投标项目，无需立项，直接发起合同评审？", {
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
                }
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
                $("#customer_name").empty();
                var tpl = "";
                tpl += "<option value=''>请选择</option>";
                if (!ifEmpty(reData.customer_name)) {
                    var customerArr = reData.customer_name.split('||');
                    $.each(customerArr, function(index, value) {
                        if (!ifEmpty(value)) {
                            tpl += "<option value='" + value + "'>" + value + "</option>";
                        }
                    });
                }else{
                    <?php foreach ($customerSelect as $cusId => $cusName) : ?>
                        <?if($company!='INHEGRID'){continue;}?>
                        tpl +='<option value="<?= $cusName ?>" ><?= $cusName ?></option>'
                    <?php endforeach; ?>
                }
                
                
                
                
                $("#customer_name").html(tpl);
                layer.closeAll();
                form.render();
            });
        }

        function returnContractNumber(reData) {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $("#form_id").val(reData.sc_no);
                $("#customer_order_no").val(reData.sc_no);
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
                                <input lay-submit lay-filter="submit" name="btn_submit" actionType="Submit" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input lay-submit lay-filter="submit" name="btn_save" actionType="Save" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="window.close();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="width:80%;margin:1% 9%;">
                <!-- <input type="hidden" id="form_id" name="form_id" /> -->
                <input type="hidden" name="pageName" value="selectAddPhp" /><!-- 用于判断是新增页面-->
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_contract_review" />
                <input type="hidden" name="flowType" value="INHESC" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" id="type" name="type" value="sales_contract_review" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $company ?>" />
                <?= $brand_html ?>
                <table width="100%" align="center">
                    <tr>
                        <td align="center">
                            <img alt="" src="../../../../../images/<?= $company ?>/sales_contract_review_<?= $company ?>.jpg" style="width: 34%; height: auto;" title="" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <?php include_once("instructions.php"); ?>
                        </td>
                    </tr>
                </table>
                <br />
                <table class="TableBlock" width="100%" style="text-align: center;">
                    <tr>
                        <td>
                            <?php include_once("selectAdd/main_info.php"); ?>
                            <?php include_once("change/product_detail.php"); ?>
                            <?php include_once("change/contract_info.php"); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

</html>