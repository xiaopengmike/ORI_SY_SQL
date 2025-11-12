<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("销售收款登记");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../common/DataModel.php");
include_once("../common/UserModel.php");
$model = new DataModel();
$userModel = new UserModel();
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$selectArr = $model->getCommonParam($selectParam);
if (empty($company)) {
    $company = $userModel->getUserBu($deptId);
}
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'currency'
);
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
                $.initAttach();
                $.formSubmit();
                // 取订单编号，项目编号，项目代号，客户名称，订单编号，对应原币金额，原币币种，合同相对方名称
                $("#order_number").click(function() {
                    getOrderList(this);
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
            <div class="weadmin-body" style="width:90%;margin:1% 4%;">
                <input type="hidden" id="form_id" name="form_id" />
                <input type="hidden" id="type" name="type" value="sales_receipt" />
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_sales_receipt" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $company ?>" />
                <input type="hidden" name="flowType" value="xssk" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="dept_number" id="dept_number" value="" />
                <table width="100%" align="center">
                    <tr>
                    <td align="center">
                        <img alt="" src="../../../../../images/<?= $company ?>/sales_receipt_<?= $company ?>.jpg" style="width: 28%; height: auto;" />
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
                                            <input type="text" name="payer_name" id="payer_name" lay-verify="required" class="layui-input" autocomplete="off" />
                                        </td>
                                        <td class="TableContent" width="10%">原币金额</td>
                                        <td class="TableData" width="15%">
                                            <input type="text" name="original_amount" id="original_amount" lay-verify="number" class="layui-input" autocomplete="off" />
                                        </td>
                                        <td class="TableContent" width="10%">原币币种</td>
                                        <td class="TableData" width="15%" align="left">
                                            <div class="layui-input-inline">
                                                <select id="currency" name="currency" lay-search>
                                                    <option value=''>请选择</option>
                                                    <?php foreach ($selectArr['currency'] as $v) : ?>
                                                        <option value="<?= $v['paras_value'] ?>"><?= $v['paras_desc'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td class="TableContent" width="10%">项目编号</td>
                                        <td class="TableData">
                                            <input type="text" name="project_number" id="project_number" lay-verify="required" class="layui-input" autocomplete="off" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="TableContent">人民币金额</td>
                                        <td class="TableData">
                                            <input type="text" name="rmb_amount" id="rmb_amount" lay-verify="number" class="layui-input" autocomplete="off" />
                                        </td>
                                        <td class="TableContent">付款方式</td>
                                        <td class="TableData">
                                            <input type="text" name="payment_term" id="payment_term" class="layui-input" autocomplete="off" />
                                        </td>
                                        <td class="TableContent">到款时间</td>
                                        <td class="TableData">
                                            <input type="text" name="receipt_date" id="receipt_date" class="layui-input date" lay-verify="required" autocomplete="off" />
                                        </td>
                                        <td class="TableContent" width="10%">到款银行</td>
                                        <td class="TableData">
                                            <input type="text" name="receipt_bank" id="receipt_bank" lay-verify="required" class="layui-input" autocomplete="off" />
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td class="TableContent">业务部门</td>
                                        <td class="TableData" align="left">
                                            <div class="layui-input-inline">
                                                <input type="text" id="menu_parent_name" class="layui-input" data-type="dept_select" name="dept_name" autocomplete="off" />
                                                <input type="hidden" id="menu_parent" name="dept_id">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?if($company!="INHENERGY"&&1==2):?>
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
                                        <td class="TableContent">备注<?if(in_array($company,array('INHENERGY','HKNERGY'))):?>(预付款请填写比率)<?endif;?></td>
                                    </tr>
                                </thead>
                                <!-- 扣减手续费、其他费用	已收款原币金额	未收款原币金额	合同相对方名称	是否第三方支付	备注	 -->
                                <tbody id="addReceipt">
                                    <input type="hidden" name="addReceiptCount" id="addReceiptCount" value="1" />
                                    <tr align="center">
                                        <td class="TableData"><input type="hidden" name="serial_number1" id="serial_number" class="layui-input" value="1" readonly />1</td>
                                        <td class="TableData"><input type="text" name="project_code1" id="project_code" class="layui-input" autocomplete="off" /></td>
                                        <td class="TableData"><input type="text" name="project_number1" id="project_number" class="layui-input" autocomplete="off" /></td>
                                        <td class="TableData"><input type="text" name="order_number1" id="order_number" class="layui-input mandatory" autocomplete="off" /></td>
                                        <td class="TableData"><input type="text" name="customer_name1" id="customer_name" class="layui-input" autocomplete="off" /></td>
                                        <td class="TableData"><input type="text" name="contract_amount1" id="contract_amount" class="layui-input" autocomplete="off" /></td>
                                        <td class="TableData">
                                            <div class="layui-input-inline">
                                                <select id="currency_detail" name="currency_detail1" lay-search>
                                                    <option value=''>请选择</option>
                                                    <?php foreach ($selectArr['currency'] as $v) : ?>
                                                        <option value="<?= $v['paras_value'] ?>"><?= $v['paras_desc'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td class="TableData"><input type="text" name="rmb_amount1" id="rmb_amount" class="layui-input" autocomplete="off" /></td>
                                        <td class="TableData"><input type="text" name="receipt_amount1" id="receipt_amount" class="layui-input" autocomplete="off" onblur="calUnReceipted(this.name);return false;" /></td>
                                        <td class="TableData"><input type="text" name="other_cost1" id="other_cost" class="layui-input" autocomplete="off" /></td>
                                        <td class="TableData"><input type="text" name="receipted1" id="receipted" class="layui-input" autocomplete="off" readonly /></td>
                                        <td class="TableData"><input type="text" name="un_receipt1" id="un_receipt" class="layui-input" autocomplete="off" readonly /></td>
                                        <td class="TableData"><input type="text" name="contract_party1" id="contract_party" class="layui-input" autocomplete="off" /></td>
                                        <td class="TableData">
                                        <select  id="if_third_pay" name="if_third_pay1" >
                                                <option value="">请选择</option>
                                                <option value="是" <?if($v['if_third_pay']=="是"):?><?endif;?>>是</option>
                                                <option value="否"  <?if($v['if_third_pay']=="否"):?><?endif;?>>否</option>
                                        </select>
                                            
                                        </td>
                                        <td class="TableData"><input type="text" name="remark1" id="remark" class="layui-input" autocomplete="off" /></td>
                                    </tr>
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
                                <!-- <tr align="left">
                                    <td class="TableData" colspan="15">
                                        <input value="添加" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addAttach');return false;" />
                                    </td>
                                </tr> -->
                            </table>
                            <?endif;?>
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
                                    <!-- <tr align=" center">
                                        <td class="TableData" colspan="2" style="min-height: 20px"></td>
                                    </tr>
                                    <tr align=" center">
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