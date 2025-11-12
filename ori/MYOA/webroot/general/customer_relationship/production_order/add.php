<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("生产通知单");
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
    'type' => 'yes_or_no,inspection_type,display_mode,production_mode,test_report,auto_flow_user,
    project_star,packed_in_sequence,product_brand,form_type,product_detail_type,company,SGC_TYPE'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$product_detail_types=array();
foreach ($selectArr['product_detail_type'] as $v) {
    $product_detail_types[]=$v['paras_value'];
}
$company_names=array();
foreach ($selectArr['company'] as $v) {
    $company_names[$v['paras_value']]=$v['paras_desc'];
}
$project_star = array();
foreach ($selectArr['project_star'] as $v) {
    $project_star[$v['paras_value']] = $v['paras_desc'];
}
$form_type_arr = array();
foreach ($selectArr['form_type'] as $v) {
    $form_type_arr[$v['paras_value']] = $v['paras_desc'];
}
$projectObj = json_encode($dataModel->array_iconv($project_star));

$product_company=$company;
if(in_array($product_company,array("JXINHE","INHE","INHEJX","HKINHE","INHEIAC","INHEIMC"))){
    $product_company="INHE";
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
    if($product_company==$v['user_bu']){
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
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="main.js?v=20250816"></script>
    <script language="Javascript" type="text/javascript" src="../add.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
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
                $("#order_num,#sale_order_no").click(function() {
                    var index = layui.layer.open({
                        title: "项目代号列表",
                        type: 2,
                        content: "product_list.php?company="+'<?=$company?>',
                        area: ['800px', '650px'],
                        success: function(layero, index) {
                            setTimeout(function() {
                                layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                    tips: 3
                                });
                            }, 500)
                        }
                    });
                });
                selectProductBrand(form);
                 //必填增加背景色
                setTimeout(function(){
                    $('select[lay-verify="required"]').next().find('.layui-select-title input').css({'background-color':'#f9fbd5'});
                },500);
            
                form.verify({
                    SGC: function (value) {
                        var is_sgc = $('#is_sgc').val();
                        if(is_sgc=="03"&&ifEmpty(value)){
                        return "选择有SGC码，则需填写具体SGC码";
                        }
                    },
                });

                form.render();
            });
        });

        function returnProductData(reData) {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                var projectStar = '';
                $("#c_past_pcode").val(reData.c_past_pcode);
                $("#past_pcode").val(reData.past_pcode);
                $("#customer_name").val(reData.customer_name);
                $("#project_code").val(reData.project_name);
                $("#project_num").val(reData.project_number);
                $("#order_num").val(reData.form_id);
               
                $("#sale_order_no").val(reData.customer_order_no);
                $("#contract_party").val(reData.contract_party);
                $("#delivery_time").val(reData.delivery_time);
                $("#contract_begin_time").val(reData.contract_begin_time);
                $("#contract_end_time").val(reData.contract_end_time);
                var starObj = <?= $projectObj ?>;
                if (!ifEmpty(starObj[reData.project_star])) {
                    projectStar = starObj[reData.project_star];
                }
                $("[name='project_star']").val(reData.project_star);
                $("#project_star").html('<font style="color: red;font-size:20px">' + projectStar + '</font>');
                if (ifEmpty(reData.star_icon)) reData.star_icon = '';
                $("#star_icon").html('<font style="color: red;font-size:20px">' + reData.star_icon + '</font>');
                $("#company").val(reData.contract_belong);
                $("#spare_requirement").val(reData.spare_requirement);
                form_id=reData.change_form_id?reData.change_form_id:reData.form_id;
                
                $("#order_form_id").val(form_id);
                findProductDetail(form_id);
                layer.closeAll();
                form.render();
            });
        }
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
                <input type="hidden" name="tableName" value="inhe_production_order" />
                <input type="hidden" name="flowType" value="sctz" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" id="type" name="type" value="<?= $form_type=="11"?'production_order':'trial_production_order';?>" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $company ?>" />
                <input type="hidden" name="product_company" id="product_company" value="<?= $product_company ?>" />
                <input type="hidden" name="create_user_bu" value="<?= $userInfo['company_user_bu'] ?>" />
                <?= $other_company_product_html ?>
                <table width="100%" align="center">
                    <tr>
                    <td align="center">
                            <?if($form_type=="01"):?>
                            <img alt="" id="page_title" src="../../../../../images/<?= $company ?>/trial_production_order_<?= $company ?>.jpg" style="width: 34%; height: auto;" title="" />
                            <?elseif($form_type=="02"):?>
                                <img alt="" id="page_title" src="../../../../../images/<?= $company ?>/stock_production_order_<?= $company ?>.jpg" style="width: 34%; height: auto;" title="" />
                                <?elseif($form_type=="03"):?>
                                    <img alt="" id="page_title" src="../../../../../images/<?= $company ?>/Replenishment_production_order_<?= $company ?>.jpg" style="width: 34%; height: auto;" title="" />
                                    <?elseif($form_type=="04"):?>
                                    <img alt="" id="page_title" src="../../../../../images/<?= $company ?>/risk_production_order_<?= $company ?>.jpg" style="width: 34%; height: auto;" title="" />
                                    <?else:?>
                                    <img alt="" id="page_title" src="../../../../../images/<?= $company ?>/production_order_<?= $company ?>.jpg" style="width: 34%; height: auto;" title="" />
                                    <?endif;?>
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
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

</html>