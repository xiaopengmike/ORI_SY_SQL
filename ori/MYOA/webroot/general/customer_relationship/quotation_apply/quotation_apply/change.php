<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("报价申请变更");
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
    'type' => 'project_star,check_opinion,currency,yes_or_no,quote_source,sale_model,company,price_terms',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$company_names=array();
foreach ($selectArr['company'] as $v) {
    $company_names[$v['paras_value']]=$v['paras_desc'];
}


$param['id'] = $id;
$action = new ActionModel();
$data = $action->queryById($param);
$main = $data['main'];
//存在变更，则取上次变更信息继续变更
if($main['version']>0){
    $item=$dataModel->getLastChange($main['form_id'],$main['version'],'quotation_apply_change');
    if($item['form_id']){
        $data = $action->queryById(array("id"=>$item['form_id']));
        $main = $data['main'];
    }
}
$main['type']='quotation_apply_change';
$company=$main['user_bu'];
$product = $data['product'];

// 获取审核数据
$approveData = $processModel->getApproveData($param);
$attachModel = new AttachModel();
$attachObj = $attachModel->getAttachById($id);
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
                form.render();
            });
        });
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
                                <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="width:80%;margin:1% 9%;">
                <input type="hidden" name="form_id" id="form_id" />
                <!-- <input type="hidden" name="related_id" id="related_id" value="<?= $main['related_id']?$main['related_id']:$id ?>" /> -->
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_quotation_apply" />
                <input type="hidden" name="flowType" value="qpbg" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" id="type" name="type" value="quotation_apply_change" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <?= $brand_html ?>
                <table width="100%" align="center">
                    <tr>
                        <td align="center">
                            <img alt="" src="../../../../../images/<?= $main['user_bu'] ?>/quotation_apply_change_<?= $main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
                        </td>
                    </tr>
                </table>
                <br />
                <table class="TableBlock" width="100%" align="center">
                    <tr>
                        <td>
                            <?php require_once("change/main_info.php"); ?>
                            <?php require_once("change/product_detail.php"); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

</html>