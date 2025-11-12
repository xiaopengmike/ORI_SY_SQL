<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("保函登记管理");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../ActionModel.php");
include_once("../../common/CommonControl.php");
$model = new CommonControlModel();

if(empty($id)){
    // echo "对应数据不存在！";
    Message("提示","对应数据不存在！");
    exit;
}

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$companyNew = $model->getUserBuNew($deptId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'opening_time,bid_type,bid_status,manage_dept,currency',
);
$selectArr = $model->getCommonParam($selectParam);
$opening_time = array();
foreach ($selectArr['opening_time'] as $co) {
    $opening_time[$co['paras_value']] = $co['paras_desc'];
}
$manage_dept = array();
foreach ($selectArr['manage_dept'] as $md) {
    $manage_dept[$md['paras_value']] = $md['paras_desc'];
}
$currency = array();
foreach ($selectArr['currency'] as $md) {
    $currency[$md['paras_value']] = $md['paras_desc'];
}
$bid_type = array();
foreach ($selectArr['bid_type'] as $md) {
    $bid_type[$md['paras_value']] = $md['paras_desc'];
}
$bid_status = array();
foreach ($selectArr['bid_status'] as $md) {
    $bid_status[$md['paras_value']] = $md['paras_desc'];
}

$action = new ActionModel();
$param['id'] = $id;
$data = $action->queryAuthorizationLetter($param);
$attachObj = $action->getAttachById($id);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="../main.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">

    <script type="text/javascript">
        $(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer', 'attach'], function() {
                var layer = layui.layer,
                    form = layui.form,
                    attach = layui.attach;

                init();

                var attachObj = <?= $attachObj ?>;
                $(".btn-upload").each(function() {
                    var id = this.id;
                    if (!ifEmpty(attachObj[id])) {
                        attach.attachReadonly({
                            elem: "#" + this.id,
                            uploadUrl: "/general/attachment/attach.php?type=" + this.id + "&url=common",
                            attachObj: attachObj[id]
                        });
                    }
                });

                form.render();
            });
        });

        // 页面初始化加载
        function init() {
            // textarea初始化样式
            $.each($("textarea"), function(i, n) {
                $(n).css("width", "100%");
                $(n).css("min-height", "60px");
                $(n).css("margin", "auto");
                $(n).css("resize", "none");
                $(n).css("overflow", "hidden");
            });
        }
    </script>

<body>
    <div class="weadmin-body" style="width:90%;margin:1% 4%;">
        <form id="addForm" class="layui-form">
            <input type="hidden" name="form_type" id="form_type" value="contract_protocol" />
            <input type="hidden" name="user_bu" id="user_bu" value="<?= $companyNew ?>" />
            <input type="hidden" name="dept_number" id="dept_number" value="" />
            <table class="layui-table" border="0" cellpadding="0" cellspacing="10" lay-size="sm">
                <tr>
                    <td class="layui-bg-gray" width="10%">项目代号</td>
                    <td width="15%">
                        <?= $data['project_code'] ?>
                    </td>
                    <td class="layui-bg-gray" width="10%">保函类型</td>
                    <td width="15%">
                        <?= $bid_type[$data['bid_type']] ?>
                    </td>
                    <td class="layui-bg-gray" width="10%">保函开立/延期次数</td>
                    <td width="15%">
                        <?= $opening_time[$data['opening_time']] ?>
                    </td>
                    <td class="layui-bg-gray" width="10%">目前状态</td>
                    <td>
                        <?= $bid_status[$data['status']] ?>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">开立/修改生效日期</td>
                    <td>
                        <?= $data['effective_date'] ?>
                    </td>
                    <td class="layui-bg-gray">到期日期</td>
                    <td>
                        <?= $data['end_date'] ?>
                    </td>
                    <td class="layui-bg-gray">国内银行费用</td>
                    <td>
                        <?= $data['bank_charge'] ?>
                    </td>
                    <td class="layui-bg-gray">转开行费用</td>
                    <td>
                        <?= $data['transfer_fee'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">保函币种</td>
                    <td>
                        <?= $currency[$data['currency']] == '' ? $data['currency'] : $currency[$data['currency']] ?>
                    </td>
                    <td class="layui-bg-gray">保函金额（人民币）</td>
                    <td>
                        <?= $data['guarantee_amount'] ?>
                    </td>
                    <td class="layui-bg-gray">保证金金额</td>
                    <td>
                        <?= $data['margin_amount'] ?>
                    </td>
                    <td class="layui-bg-gray">占用额度</td>
                    <td>
                        <?= $data['occupation_quota'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">国内开立银行</td>
                    <td>
                        <?= $data['opening_bank'] ?>
                    </td>
                    <td class="layui-bg-gray">国外转开行（若有）</td>
                    <td>
                        <?= $data['transfer_bank'] ?>
                    </td>
                    <td class="layui-bg-gray">项目所在国</td>
                    <td>
                        <?= $data['project_country'] ?>
                    </td>
                    <td class="layui-bg-gray">保函审批流程流水号</td>
                    <td>
                        <?= $data['flow_no'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">申请部门</td>
                    <td>
                        <?= $data['apply_dept_name'] ?>
                    </td>
                    <td class="layui-bg-gray">业务负责人</td>
                    <td>
                        <?= $data['business_leader'] ?>
                    </td>
                    <td class="layui-bg-gray">商务经办人</td>
                    <td>
                        <?= $data['commerce_manager'] ?>
                    </td>
                    <td class="layui-bg-gray">管理部门</td>
                    <td>
                        <?= $manage_dept[$data['manage_dept']] == '' ? $data['manage_dept'] : $manage_dept[$data['manage_dept']] ?>
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">最终保函申请资料</td>
                    <td colspan="7">
                        <input id="apply_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="附件上传" readonly />
                    </td>
                </tr>
                <tr>
                    <td class="layui-bg-gray">国内银行电文</td>
                    <td colspan="7">
                        <input id="bank_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="附件上传" readonly />
                </tr>
                <tr>
                    <td class="layui-bg-gray">转开行最终保函</td>
                    <td colspan="7">
                        <input id="transfer_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: none;" value="附件上传" readonly />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>