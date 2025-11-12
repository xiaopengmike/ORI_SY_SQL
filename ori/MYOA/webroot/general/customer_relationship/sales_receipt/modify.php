<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("销售收款登记");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../common/DataModel.php");
include_once("../common/UserModel.php");
require_once '../common/AttachModel.php';
require_once '../common/ProcessModel.php';
require_once 'ActionModel.php';
$action = new ActionModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
}
$model = new DataModel();
$userModel = new UserModel();
$attachModel = new AttachModel();
$process = new ProcessModel();
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$systemId = "sales_receipt";
$selectArr = $model->getCommonParam($selectParam);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'currency'
);
$currency = array();
foreach ($selectArr['currency'] as $v) {
    $currency[$v['paras_value']] = $v;
}
$param['id'] = $id;
$data = $action->queryById($param);
$main = $data['main'];
$detail = $data['detail'];
$attach = $data['attach'];
// 获取审核数据
$approveData = $process->getApproveData($param);
$attachObj = $attachModel->getAttachById($id);
$flowArr = $process->getFlowStep($systemId, $main['user_bu']);
$curOperate = $flowArr[$step]['operate'];
$backRecord = $process->findBackRecord($param);
?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="main.js?v=20241018"></script>
    <script language="Javascript" type="text/javascript" src="../add.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/dtreeck.css">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/dtreeck.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
    <script type="text/javascript">
        $(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $.initDate();
                $.initTextarea();
                var attachObj = <?= $attachObj ?>;
                $.initAttachList(attachObj);
                $.formSubmit();
                // 取订单编号，项目编号，项目代号，客户名称，订单编号，对应原币金额，原币币种，合同相对方名称
                $("[id='order_number']").each(function() {
                    this.onkeydown = function(e) {
                        var theEvent = window.event || e;
                        var code = theEvent.keyCode || theEvent.which;
                        if (code == 13) {
                            return false;
                        }
                    };
                    this.onfocus = function() {
                        getOrderList(this);
                    };
                });
                form.render();
            });
        });
    </script>
</head>

<body>
    <form id="mainForm" class="layui-form">
        <div style="width: 100%;height:auto;margin:auto;" align="center">
            <div class="r" style="height: auto;" align="left">
                <div style="text-align: left;" class="layui-btn-group">
                    <table id="tab2" width="100%">
                        <tr class="layui-bg-body TableContent" id="tHeader">
                            <td align="center" style="padding: 5px;">
                                <input lay-submit lay-filter="submit" name="btn_submit" actionType="Submit" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input lay-submit lay-filter="submit" name="btn_save" actionType="Save" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="margin-top: 30px">
                <input type="hidden" name="type" id="type" value="<?= $main['type'] ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $id ?>" />
                <input type="hidden" name="step" id="step" value="<?= $step ?>" />
                <input type="hidden" name="title" id="title" value="<?= $main['title'] ?>" />
                <input type="hidden" name="dept_number" id="dept_number" value="" />
                <table width="70%" align="center">
                    <td align="center">
                        <img alt="" src="../../../../../images/<?= $main['user_bu'] ?>/sales_receipt_<?= $main['user_bu'] ?>.jpg" style="width: 28%; height: auto;" />
                    </td>
                </table>
                <br />
                <table class="TableBlock" width="90%" align="center">
                    <tr>
                        <td>
                            <table id="tab" width="100%" style="text-align: center;">
                                <thead>
                                    <tr class="TableHeader">
                                        <td colspan="8" style="text-align: center;">财务部门填写</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="TableContent" width="10%">付款方名称</td>
                                        <td class="TableData" width="15%">
                                            <input type="text" name="payer_name" id="payer_name" class="layui-input" value="<?= $main['payer_name'] ?>" />
                                        </td>
                                        <td class="TableContent" width="10%">原币金额</td>
                                        <td class="TableData" width="15%">
                                            <input type="text" name="original_amount" id="original_amount" class="layui-input" value="<?= $main['original_amount'] ?>" />
                                        </td>
                                        <td class="TableContent" width="10%">原币币种</td>
                                        <td class="TableData" width="15%" align="left">
                                            <div class="layui-input-inline">
                                                <select id="currency" name="currency" lay-search>
                                                    <option value=''>请选择</option>
                                                    <?php foreach ($currency as $val) : ?>
                                                        <option value="<?= $val['paras_value'] ?>" <?php if ($main['currency'] == $val['paras_value']) : ?>selected<?php endif; ?>><?= $val['paras_desc'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td class="TableContent" width="10%">人民币金额</td>
                                        <td class="TableData">
                                            <input type="text" name="rmb_amount" id="rmb_amount" class="layui-input" value="<?= $main['rmb_amount'] ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="TableContent">项目编号</td>
                                        <td class="TableData">
                                            <input type="text" name="project_number" id="project_number" class="layui-input" value="<?= $main['project_number'] ?>" />
                                        </td>
                                        <td class="TableContent">付款方式</td>
                                        <td class="TableData">
                                            <input type="text" name="payment_term" id="payment_term" class="layui-input" value="<?= $main['payment_term'] ?>" />
                                        </td>
                                        <td class="TableContent">到款时间</td>
                                        <td class="TableData">
                                            <input type="text" name="receipt_date" id="receipt_date" class="layui-input date" value="<?= $main['receipt_date'] ?>" />
                                        </td>
                                        <td class="TableContent" width="10%">到款银行</td>
                                        <td class="TableData">
                                            <input type="text" name="receipt_bank" id="receipt_bank" lay-verify="required" class="layui-input" autocomplete="off" value="<?= $main['receipt_bank'] ?>" />
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td class="TableContent">业务部门</td>
                                        <td class="TableData" align="left">
                                            <div class="layui-input-inline">
                                                <input type="text" id="menu_parent_name" class="layui-input" data-type="dept_select" name="dept_name" autocomplete="off" value="<?= $main['business_dept'] ?>" />
                                                <input type="hidden" id="menu_parent" name="dept_id">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="tab1" width="100%">
                                <thead>
                                    <tr class="TableHeader">
                                        <td colspan="15" style="text-align: center;">业务填写</td>
                                    </tr>
                                    <tr>
                                        <td nowrap class="TableContent" width="3%">序号</td>
                                        <td nowrap class="TableContent" width="3%">项目代号</td>
                                        <td nowrap class="TableContent" width="3%">项目编号</td>
                                        <td nowrap class="TableContent" width="9%">订单编号</td>
                                        <td class="TableContent">客户名称</td>
                                        <td nowrap class="TableContent" width="6%">合同金额</td>
                                        <td nowrap class="TableContent" width="6%">币种</td>
                                        <td class="TableContent">对应人民币金额</td>
                                        <td class="TableContent">本次收款金额</td>
                                        <td class="TableContent">扣减手续费、其他费用</td>
                                        <td class="TableContent">已收款原币金额</td>
                                        <td class="TableContent">未收款原币金额</td>
                                        <td class="TableContent">合同相对方名称</td>
                                        <td class="TableContent">是否第三方支付</td>
                                        <td class="TableContent">备注</td>
                                    </tr>
                                </thead>
                                <tbody id="addReceipt">
                                    <input type="hidden" name="addReceiptCount" id="addReceiptCount" value="<?= count($detail); ?>" />
                                    <?php foreach ($detail as $k => $v) { ?>
                                        <tr align="center">
                                            <td class="TableData"><input type="hidden" name="serial_number<?= $k + 1 ?>" id="serial_number" class="layui-input" value="<?= $v['serial_number'] ?>" readonly /><?= $v['serial_number'] ?></td>
                                            <td class="TableData"><input type="text" name="project_code<?= $k + 1 ?>" id="project_code" class="layui-input" autocomplete="off" value="<?= $v['project_code'] ?>" /></td>
                                            <td class="TableData"><input type="text" name="project_number<?= $k + 1 ?>" id="project_number" class="layui-input" autocomplete="off" value="<?= $v['project_number'] ?>" /></td>
                                            <td class="TableData"><input type="text" name="order_number<?= $k + 1 ?>" id="order_number" class="layui-input mandatory" autocomplete="off" value="<?= $v['order_number'] ?>" /></td>
                                            <td class="TableData"><input type="text" name="customer_name<?= $k + 1 ?>" id="customer_name" class="layui-input" autocomplete="off" value="<?= $v['customer_name'] ?>" /></td>
                                            <td class="TableData"><input type="text" name="contract_amount<?= $k + 1 ?>" id="contract_amount" class="layui-input" autocomplete="off" value="<?= $v['contract_amount'] ?>" /></td>
                                            <td class="TableData">
                                                <div class="layui-input-inline">
                                                    <select id="currency_detail" name="currency_detail<?= $k + 1 ?>" lay-search>
                                                        <option value=''>请选择</option>
                                                        <?php foreach ($currency as $val) : ?>
                                                            <option value="<?= $val['paras_value'] ?>" <?php if ($v['currency'] == $val['paras_value']) : ?>selected<?php endif; ?>><?= $val['paras_desc'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="TableData"><input type="text" name="rmb_amount<?= $k + 1 ?>" id="rmb_amount" class="layui-input" autocomplete="off" value="<?= $v['rmb_amount'] ?>" /></td>
                                            <td class="TableData"><input type="text" name="receipt_amount<?= $k + 1 ?>" id="receipt_amount" class="layui-input" autocomplete="off" onblur="calUnReceipted(this.name);return false;" value="<?= $v['receipt_amount'] ?>" /></td>
                                            <td class="TableData"><input type="text" name="other_cost<?= $k + 1 ?>" id="other_cost" class="layui-input" autocomplete="off" value="<?= $v['other_cost'] ?>" /></td>
                                            <td class="TableData"><input type="text" name="receipted<?= $k + 1 ?>" id="receipted" class="layui-input" autocomplete="off" readonly value="<?= $v['receipted'] ?>" /></td>
                                            <td class="TableData"><input type="text" name="un_receipt<?= $k + 1 ?>" id="un_receipt" class="layui-input" autocomplete="off" readonly value="<?= $v['un_receipt'] ?>" /></td>
                                            <td class="TableData"><input type="text" name="contract_party<?= $k + 1 ?>" id="contract_party" class="layui-input" autocomplete="off" value="<?= $v['contract_party'] ?>" /></td>
                                            <td class="TableData"><input type="text" name="if_third_pay<?= $k + 1 ?>" id="if_third_pay" class="layui-input" autocomplete="off" value="<?= $v['if_third_pay'] ?>" /></td>
                                            <td class="TableData"><input type="text" name="remark<?= $k + 1 ?>" id="remark" class="layui-input" autocomplete="off" value="<?= $v['remark'] ?>" />
                                                <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tr align="left">
                                    <td class="TableData" colspan="15">
                                        <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addReceipt');return false;" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="TableContent">序号</td>
                                    <td class="TableContent" colspan="4">附档名称</td>
                                    <td class="TableContent" colspan="10">附档</td>
                                </tr>
                                <tbody id="addAttach">
                                    <input type="hidden" name="addAttachCount" id="addAttachCount" value="1" />
                                    <tr align="center">
                                        <td class="TableData"><input type="hidden" name="serial_number_attach1" id="serial_number_attach" class="layui-input" value="1" />1</td>
                                        <td class="TableData" colspan="4"><input readonly type="text" name="attach_name" id="attach_name" class="layui-input" autocomplete="off" /></td>
                                        <td class="TableData" colspan="10">
                                            <!-- <input id="sales_receipt_attach1" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" /> -->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="tab1" width="100%">
                                <thead>
                                    <tr class="TableHeader">
                                        <td colspan="2" style="text-align: center;">跟单审核</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr align="center">
                                        <td class="TableContent" rowspan="3" width="15%">跟单</td>
                                    </tr>
                                    <tr align="center">
                                        <td class="TableData">
                                            <textarea readonly id="approve_opinion" class="layui-textarea" id="opinion<?= $fv['step'] ?>" name="opinion<?= $fv['step'] ?>" data-type="opinion" <?php if (($fv['step'] != $step)) : ?>disabled<?php endif; ?>><?= $approveData[$fv['step']]['opinion'] ?></textarea>
                                        </td>
                                    </tr>
                                    <tr align="center">
                                        <td class="TableData" style="min-height: 20px"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="tab2" width="100%">
                                <thead>
                                    <tr class="TableHeader">
                                        <td colspan="3" style="text-align: center;">业务员补充资料</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr align="center">
                                        <td class="TableContent" rowspan="3" width="15%">业务员</td>
                                    </tr>
                                    <tr align="center">
                                        <td class="TableData" colspan="2">
                                            <textarea readonly id="business_opinion" name="business_opinion" class="layui-textarea"></textarea>
                                        </td>
                                    </tr>
                                    <tr align=" center">
                                        <td class="TableData" colspan="2" style="min-height: 20px"></td>
                                    </tr>
                                    <!-- <tr align=" center">
                                        <td class="TableContent">序号</td>
                                        <td class="TableContent">附档名称</td>
                                        <td class="TableContent">附档</td>
                                    </tr>
                                    <tr align=" center">
                                        <td class="TableData"><input type="hidden" name="serial_number1" id="serial_number1" class="layui-input" autocomplete="off" value="1" />1</td>
                                        <td class="TableData"><input type="text" name="attach_name" id="attach_name" class="layui-input" autocomplete="off" /></td>
                                        <td class="TableData">
                                            <input id="sales_receipt_attach" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />
                                        </td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
    <br />
    <?php
    include_once("common/mydtree_new.php");
    ?>
</body>

</html>