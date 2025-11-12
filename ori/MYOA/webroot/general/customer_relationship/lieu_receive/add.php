<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("代收货登记");
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
    'type' => 'yes_or_no,inspection_type,display_mode,production_mode,test_report,project_star,packed_in_sequence,product_brand,product_detail_type,company,SGC_TYPE'
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
$projectObj = json_encode($dataModel->array_iconv($project_star));

$sql = " select * from inhe_outexcipients_detail k where id in ($excipients_ids) order by id ";
$res = exequery(TD::conn(), $sql);
$non_lieu_serial_number=1;
while ($item = mysql_fetch_assoc($res)) {
    $excipients_detail[non_lieu_serial_number] = $non_lieu_serial_number;
    $excipients_detail[non_lieu_name] = $item[non_excipients_name];
    $excipients_detail[non_lieu_model] = $item[non_excipients_model];
    $excipients_detail[non_lieu_specification] = $item[non_excipients_specification];
    $excipients_detail[non_lieu_number] = $item[non_excipients_number];
    $excipients_detail[non_lieu_attachment] = $item[non_excipients_attachment];
    $excipients_detail[non_lieu_excipients_id] = $item[id];
    $lieureceive_detail[] = $excipients_detail;
    $non_lieu_serial_number=$non_lieu_serial_number+1;
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
    <script language="Javascript" type="text/javascript" src="main.js"></script>
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
                <input type="hidden" name="tableName" value="inhe_lieu_receive" />
                <input type="hidden" name="flowType" value="dsdj" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" id="type" name="type" value="lieu_receive" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $company ?>" />
                <input type="hidden" name="excipients_form_id" id="excipients_form_id" value="<?= $excipients_formid ?>" />
                
                <table width="100%" align="center">
                    <tr>
                        <td align="center">
                            <img alt="" src="../../../../../images/<?= $company ?>/lieu_receive_<?= $company ?>.jpg" style="width: 34%; height: auto;" title="" />
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