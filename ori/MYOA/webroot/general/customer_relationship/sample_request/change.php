<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("生产通知变更单");
require_once("inc/header.inc.php");
require_once("common/Common.php");
require_once("ActionModel.php");
require_once("../common/AttachModel.php");
require_once("../common/UserModel.php");
require_once("../common/DataModel.php");
if (empty($id)) {
    Message("提示", "表单数据不存在！");
    exit;
}
$attachModel = new AttachModel();
$userModel = new UserModel();
$dataModel = new DataModel();
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s', time());
$userInfo = $userModel->getUserInfo($userId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,check_opinion,yes_or_no,inspection_type,display_mode,production_mode,
            test_report,packed_in_sequence,product_brand,product_detail_type,company,SGC_TYPE'
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
$project_star = array();
foreach ($selectArr['project_star'] as $v) {
    $project_star[$v['paras_value']] = $v;
}
$action = new ActionModel();
$param['id'] = $id;
$data = $action->queryById($param);
$main = $data['main'];
//存在变更，则取上次变更信息继续变更
if($main['version']>0){
    $item=$dataModel->getLastChange($main['form_id'],$main['version'],'outsourcing_excipients_change');
    if($item['form_id']){
        $data = $action->queryById(array("id"=>$item['form_id']));
        $main = $data['main'];
    }
}
$company=$main['user_bu'];
$orderDetail = $data['orderDetail'];
$sub_process=array();
foreach($orderDetail['non_meter'] as $key=>$val){
    $sub_process[$val['non_type']]=$val['sub_process'];
}
$detailItem = $data['detailItem'];
$attachObj = $attachModel->getAttachById($id);
if ($main['delivery_time'] == '0000-00-00') $main['delivery_time'] = '';
$projectObj = json_encode($dataModel->array_iconv($project_star));
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
    <script type="text/javascript">
        $(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer', 'laydate', 'attach'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $.initDate();
                $.initTextarea();
                var attachObj = <?= $attachObj ?>;
                $.initAttachList(attachObj);
                $.formSubmit();
                $("#order_num").click(function() {
                    var index = layui.layer.open({
                        title: "项目代号列表",
                        type: 2,
                        content: "product_list.php",
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
                $("#customer_name").val(reData.customer_name);
                $("#project_code").val(reData.project_name);
                $("#project_num").val(reData.project_number);
                $("#order_num").val(reData.form_id);
                $("#contract_party").val(reData.contract_party);
                var starObj = <?= $projectObj ?>;
                if (!ifEmpty(starObj[reData.project_star])) {
                    projectStar = starObj[reData.project_star].paras_desc;
                }
                $("[name='project_star']").val(reData.project_star);
                $("#project_star").html('<font style="color: red;font-size:20px">' + projectStar + '</font>');
                if (ifEmpty(reData.star_icon)) reData.star_icon = '';
                $("#star_icon").html('<font style="color: red;font-size:20px">' + reData.star_icon + '</font>');
                $("#company").val(reData.contract_belong);
                $("#spare_requirement").val(reData.spare_requirement);
                findProductDetail(reData.form_id);
                layer.closeAll();
                form.render();
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
                                <input lay-submit lay-filter="submit" name="btn_submit" actionType="Change" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input lay-submit lay-filter="submit" name="btn_save" actionType="ChangeSave" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="width:90%;margin:1% 4%;">
                <input type="hidden" name="form_id" id="form_id" />
                <input type="hidden" name="related_id" id="related_id" value="<?= $id ?>" />
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_outsourcing_excipients" />
                <input type="hidden" name="flowType" value="flbg" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" id="type" name="type" value="outsourcing_excipients_change" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <table width="100%" align="center">
                    <tr>
                        <td align="center">
                            <img alt="" src="../../../../../images/<?= $main['user_bu'] ?>/production_order_change_<?= $main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
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
                            <?php require_once("change/customer_info.php"); ?>
                            <?php require_once("change/order_detail.php"); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

</html>