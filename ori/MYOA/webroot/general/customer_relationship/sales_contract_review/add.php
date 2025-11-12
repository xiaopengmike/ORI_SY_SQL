<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("销售合同评审");
require_once("inc/header.inc.php");
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
    'type' => 'project_star,check_opinion,currency,yes_or_no,quote_source,sale_model,auto_flow_user,
    company,product_brand,product_detail_type,company,funds_come,product_project_type,license_service,license_product,license_type',
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

$product_company=$company;
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
foreach ($selectArr['auto_flow_user'] as $v) {
    $auto_flow_user[]=$v['paras_value'];
    if(($product_company=="JXINHE"&&$v['user_bu']=="INHE")||$product_company==$v['user_bu']){
        continue;
    }
    $other_company_product_html .= '<option value="' . $v['user_bu'] . '">' . $v['extra'] . '</option>';
}
$other_company_product_html .= '</div>';
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
    <script language="Javascript" type="text/javascript" src="../shim.min.js"></script>
    <script language="Javascript" type="text/javascript" src="../xlsx.full.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/js/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/js/extends/multiSelect.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="main.js?v=20241224"></script>
    
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
            //document.getElementById('upload_Electric').addEventListener('change', addLineExcel, false);
            layui.extend({
                attach: '/common/js/extends/attach',
            }).use(['form', 'layer','multiSelect'], function() {
                var layer = layui.layer,
                    form = layui.form;
                    var multiSelect = layui.multiSelect;
                    //multiSelect.render();

                    layer.alert('附件请按商务和技术内容分别上传', {
                        icon: 0,
                        skin: 'layer-ext-demo' //见：扩展说明
                        })
                    //layer.msg('一段提示信息',{time:10000});
                
                $.initDate();
                $.initTextarea();
                $.initAttach();
                //$.formSubmit();
                $.tips();
                form.verify({
                    formIdReg:[
                    /(^[a-zA-Z0-9_][A-Za-z0-9-_.\/ ]+$)/
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
                        content: "project_list.php"+"?company=<?=$company?>",
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
                        customer_name: data.elem[data.elem.selectedIndex].text //data.value
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
                            var reg = new RegExp("\"", "g");//g,表示全部替换。
                            $.each(reJson.data, function(index, value) {
                                //console.log(value.replace(reg,"&quot;"));
                                value=value.replace(reg,"&quot;")
                                tpl += '<option value="' + value + '">' + value + '</option>';
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
                if(1==1){
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
                }
                //必填增加背景色
                setTimeout(function(){
                    $('select[lay-verify="required"]').next().find('.layui-select-title input').css({'background-color':'#f9fbd5'});
                },500);
                form.render();
            });
            // 在插件外部或插件内部添加监听
            layui.use('form', function(form) {
                // 监听 Layui 表单渲染完成事件
                form.on('render(select)', function(data) {
                    // 仅处理带有 multiple 的 select
                    if (o(data.elem).attr('multiple')) {
                    var multiSelect = layui.multiSelect;
                    multiSelect.render(); // 此时容器已生成，可正常添加 multi 类
                    }
                });
            });
        });

        function returnProjectInfo(reData) {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $("#c_past_pcode").val(reData.c_past_pcode);
                $("#past_pcode").val(reData.past_pcode);
                $("#project_name").val(reData.project_code);
                $("#project_star").val(reData.project_star);
                var projectStarArr = ["一星级", "二星级", "三星级", "四星级", "五星级", "不符合"];
                var starDesc = projectStarArr[parseInt(reData.project_star)];
                $("[data-type='project_star']").html('<font style="color: red;font-size:20px">' + starDesc + '</font>');
                $("#star_icon").html('<font style="color: red;font-size:20px">' + reData.star_icon + '</font>');
                $("#customer_name").empty();
                var tpl = "";
                tpl += "<option value=''>请选择</option>";
                var reg = new RegExp("\"", "g");//g,表示全部替换。
                if (!ifEmpty(reData.customer_name)) {
                    var customerArr = reData.customer_name.split('||');
                    $.each(customerArr, function(index, value) {
                        if (!ifEmpty(value)) {
                            value=value.replace(reg,"&quot;")
                            tpl += '<option value="' + value + '">' + value + '</option>';
                        }
                    });
                }else{
                    <?php foreach ($customerSelect as $cusId => $cusName) : ?>
                        <?if($company!='INHEGRID'&&$company!='INHENERGY'&&$company!='HKNERGY'){continue;}?>
                        var cusName="<?= str_replace("\"","&quot;", $cusName)?>";
                        tpl +='<option value="'+cusName+'" ><?= addcslashes($cusName, "'\"")?></option>'
                    <?php endforeach; ?>
                }
                // if(($("#user_bu").val()!=reData.user_bu&&$("#user_bu").val()!='INHEIAC')||($("#user_bu").val()=='INHEIAC'&&reData.user_bu!='INHE'&&reData.user_bu!='INHEIAC')){
                //     tpl = "<option value=''>请选择</option>";
                //     tpl += "<option value='" + reData.user_bu_name + "'>"+reData.user_bu_name+"</option>";
                // }
                if($("#user_bu").val()!=reData.user_bu&&false){
                    var commom_company=true;
                    if($("#user_bu").val()=='INHEIAC'&&reData.user_bu=='INHE'){
                        commom_company=false;
                    }
                    if($("#user_bu").val()=='INHE'&&reData.user_bu=='INHEIAC'){
                        commom_company=false;
                    }
                    if($("#user_bu").val()=='INHENERGY'&&reData.user_bu=='HKNERGY'){
                        commom_company=false;
                    }
                    if($("#user_bu").val()=='HKNERGY'&&reData.user_bu=='INHENERGY'){
                        commom_company=false;
                    }                  
                    if(commom_company){
                        tpl = "<option value=''>请选择</option>";
                        tpl += "<option value='" + reData.user_bu_name + "'>"+reData.user_bu_name+"</option>";
                    }
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
                //$("#customer_order_no").val(reData.sc_no);
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
                <input type="hidden" name="pageName" value="addPhp" /><!-- 用于判断是新增页面-->
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_contract_review" />
                <input type="hidden" name="flowType" value="INHESC" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" id="type" name="type" value="sales_contract_review" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $company ?>" />
                <input type="hidden" name="product_company" id="product_company" value="<?= $product_company ?>" />
                <input type="hidden" name="create_user_bu" value="<?= $userInfo['company_user_bu'] ?>" />
                <?= $brand_html ?>
                <?= $license_product_html ?>
                <?= $license_type_html ?>
                <?= $other_company_product_html ?>
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
                            <?php include_once("form_module/main_info.php"); ?>
                            <?php include_once("form_module/product_detail.php"); ?>
                            <?php include_once("form_module/contract_info.php"); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

</html>