<?php
require_once("../../inc/auth.php");
require_once("../../inc/utility_all.php");
require_once("../../inc/utility_org.php");
include_once("lang.php");
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['客户信息修改'][$_COOKIE['LANG']]);
require_once("../../inc/header.inc.php");
require_once("../../common/Common.php");
require_once("ActionModel.php");
require_once("../../common/DataModel.php");
require_once("../../common/UserModel.php");

if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$param['id'] = $id;
$action = new ActionModel();
$data = $action->queryById($param);
$main = $data['main'];
$contacts = $data['contacts'];
$company = $data['company'];
$dataModel = new DataModel();
$continentArr = $dataModel->getContinentData();
$countryArr = $dataModel->getCountryData();
$userModel = new UserModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$userInfo = $userModel->getUserInfo($userId);
$isChange = '';
$submitAction = 'Submit';
$saveAction = 'Save';
if (strpos($main['type'], '_change') != false) {
    $isChange = '_change';
    $submitAction = 'Change';
    $saveAction = 'ChangeSave';
}
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'customer_type,project_level,customer_source,yes_or_no'
);
$selectArr = $dataModel->getCommonParam($selectParam);

?>
<script type="text/javascript" src="../../inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
<script language="Javascript" type="text/javascript" src="main.js?v=20230711"></script>
<script language="Javascript" type="text/javascript" src="../add.js"></script>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
<script type="text/javascript">
    $(function() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;
            $.initTextarea();
            $.formSubmit();
            makeCountrySelect();
            if('<?=$isChange?>'==''){
                //findCustomerExists();
            }
            
            form.on('select(project_level)', function (data) {
                    var starCount = data.value;
                    var html = "";
                    var starShow = "";
                    if (parseInt(starCount) < 5) {
                        for (var i = 0; i < parseInt(starCount) + 1; i++) {
                            starShow += "★";
                        }
                    }
                    html = '<font style="color: red;font-size:20px">' + starShow + '</font>';
                    $("#star_icon").html(html);
                    form.render('select','project_level');
                });
            form.render();
        });
    });
    function getNextApprover(formId, actionType) {
            var step = $('#step').val();
            var user_bu = $('#user_bu').val();
            var type = $('#type').val();
            var vaild = 0;
            if(user_bu=="INHENERGY"||user_bu=="HKNERGY"){
                vaild = $('#ori_is_vaild').val()!=$('#is_vaild').val()?1:2;
            }
            var param = "step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&actionType=" + actionType + "&type=" + type;
            param += "&vaild=" + vaild;
            layer.open({
                title: "",
                type: 2,
                area: ['600px', '80%'],
                content: "next_approver.php?" + param,
                success: function(layero, index) {
                    setTimeout(function() {
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    }, 500)
                }
            });
        }
</script>
<style>
.xing {
height: 20px;
display: inline-block;
vertical-align: middle;
line-height: 26px;
font-size: 20px;
color:red
}
</style>
<body>
    <form id="mainForm" class="layui-form">
        <div style="width: 100%;height:auto;margin:auto;">
            <div class="r" style="height: auto;">
                <div style="text-align: left;" class="layui-btn-group">
                    <table id="tab2" width="100%">
                        <tr class="layui-bg-body layui-bg-gray" id="tHeader">
                            <td align="center" style="padding: 5px;">
                                <input lay-submit lay-filter="submit" name="btn_submit" actionType="<?= $submitAction ?>" value="<?=$LG_CUSTOMER_DATA['提交'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input lay-submit lay-filter="submit" name="btn_save" actionType="<?= $saveAction ?>" value="<?=$LG_CUSTOMER_DATA['保存'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input name="btn_close" value="<?=$LG_CUSTOMER_DATA['关闭'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="margin-top: 30px">
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_customer_data" />
                <input type="hidden" name="flowType" value="khxxb" />
                <input type="hidden" name="digit" value="4" />
                <input type="hidden" name="type" id="type" value="customer_data<?= $isChange ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="form_id" id="form_id" value="<?= $main['form_id'] ?>" />
                <input type="hidden" name="title" id="title" value="<?= $main['title'] ?>" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" id="LANG" value="<?=$_COOKIE[LANG]?>" />
                <table width="70%" align="center">
                    <td align="center">
                        <img alt="" src="../../../../../images/<?= $main['user_bu'] ?>/customer_data<?= $isChange ?>_<?= $main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
                    </td>
                </table>
                <br />
                <table class="TableBlock" width="70%" align="center">
                <tr>
                        <td>
                            <table id="tab1" width="100%" style="text-align: center;">
                                <tr>
                                    <td class="TableContent" width="10%"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['客户名称'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" width="15%">
                                        <input type="text" name="customer_name" id="customer_name" class="layui-input mandatory" <?if($isChange):?>readonly<?endif;?> lay-verify="required" value="<?= $main['customer_name'] ?>" />
                                    </td>
                                    <td class=" TableContent" width="10%"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['简称'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" width="15%">
                                        <input type="text" name="short_name" id="short_name" class="layui-input" value="<?= $main['short_name'] ?>" />
                                    </td>
                                    <td class=" TableContent" width="10%"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['大洲'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" width="15%">
                                        <div class="layui-input-inline mandatory">
                                            <select id="continent" name="continent" lay-filter="continent" lay-search lay-verify="required">
                                                <option value=''><?=$LG_CUSTOMER_DATA['请选择'][$_COOKIE['LANG']]?></option>
                                                <?php
                                                foreach ($continentArr as $v) {
                                                    $selected = "";
                                                    if ($v['iso_two'] == $main['continent']) {
                                                        $selected = " selected ";
                                                    }
                                                ?>
                                                    <option value="<?= $v['iso_two'] ?>" <?= $selected ?>><?= $_COOKIE['LANG']=="EN"?$v['en_name']:$v['zh_name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td class=" TableContent" width="10%"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['国家'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData">
                                        <div class="layui-input-inline mandatory">
                                            <select id="country" name="country" lay-search lay-verify="required">
                                                <option value=''><?=$LG_CUSTOMER_DATA['请选择'][$_COOKIE['LANG']]?></option>
                                                <?php
                                                foreach ($countryArr as $v) {
                                                    $selected = "";
                                                    if ($v['iso_three'] == $main['country']) {
                                                        $selected = " selected ";
                                                    }
                                                ?>
                                                    <option value="<?= $v['iso_three'] ?>" <?= $selected ?>><?= $_COOKIE['LANG']=="EN"?$v['country']:$v['zh_name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" TableContent"><?=$LG_CUSTOMER_DATA['网址'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="3">
                                        <input type="text" name="website" id="website" class="layui-input" value="<?= $main['website'] ?>" />
                                    </td>
                                    <td class=" TableContent"><?=$LG_CUSTOMER_DATA['地址'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="3">
                                        <!-- <textarea class="layui-textarea TextDetails" id="address" name="address"><?= $main['address'] ?></textarea> -->
                                        <input type="text" name="address" id="address" class="layui-input" value="<?= $main['address'] ?>" />
                                    </td>
                                </tr>
                                <?if($main[user_bu]=="INHENERGY"||$main[user_bu]=="HKNERGY"):?>
                                <tr>
                                <td class="TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['类型'][$_COOKIE['LANG']]?></td>
                                <td class="TableData">
                                <select name="customer_type" id="customer_type" class="normalSelect" lay-verify="required">
                                <option value=""><?=$LG_CUSTOMER_DATA['请选择'][$_COOKIE['LANG']]?></option>
                                    <?php foreach($selectArr['customer_type'] as $customer_type):?>
                                    <option value='<?=$customer_type['paras_value']?>' <?php if ($main['customer_type'] == $customer_type['paras_value']) : ?>selected<?php endif; ?>><?=$LG_CUSTOMER_DATA[$customer_type['paras_desc']][$_COOKIE['LANG']]?></option>
                                        <?php endforeach;?>
                                    </select>
                                </td>
                                <td class="TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['客户来源'][$_COOKIE['LANG']]?></td>
                                <td class="TableData" >
                                    <div class="layui-input-inline mandatory">
                                        <select id="customer_source" name="customer_source" lay-verify="required">
                                            <option value=''><?=$LG_CUSTOMER_DATA['请选择'][$_COOKIE['LANG']]?></option>
                                            <?php foreach ($selectArr['customer_source'] as $val) : ?>
                                                <option value="<?= $val['paras_value'] ?>" <?php if ($main['customer_source'] == $val['paras_value']) : ?>selected<?php endif; ?>><?= $LG_CUSTOMER_DATA[$val['paras_desc']][$_COOKIE['LANG']] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </td>
                                <?if($isChange):?>
                                <td class="TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['是否有效'][$_COOKIE['LANG']]?></td>
                                <td class="TableData" >
                                    <div class="layui-input-inline mandatory">
                                        <select id="is_vaild" name="is_vaild" lay-verify="required">
                                            <option value=''><?=$LG_CUSTOMER_DATA['请选择'][$_COOKIE['LANG']]?></option>
                                            <?php foreach ($selectArr['yes_or_no'] as $val) : ?>
                                                <option value="<?= $val['paras_value'] ?>" <?php if ($main['is_vaild'] == $val['paras_value']) : ?>selected<?php endif; ?>><?= $LG_CUSTOMER_DATA[$val['paras_desc']][$_COOKIE['LANG']] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </td>
                                <td class="TableData" colspan="2"></td>
                                <?else:?>
                                    <input type="hidden" name="is_vaild" id="is_vaild" class="layui-input" value="01" />
                                    <td class="TableData" colspan="4"></td>
                                <?endif;?>
                                <!-- <td class="TableContent"><?=$LG_CUSTOMER_DATA['星级'][$_COOKIE['LANG']]?></td>
                                <td class="TableData" >
                                    <div class="layui-input-inline mandatory">
                                        <select id="project_level" name="project_star" lay-filter="project_level" lay-search lay-verify="required">
                                            <option value=''><?=$LG_CUSTOMER_DATA['请选择'][$_COOKIE['LANG']]?></option>
                                            <?php foreach ($selectArr['project_level'] as $val) : ?>
                                                <option value="<?= $val['paras_value'] ?>" <?php if ($main['project_star'] == $val['paras_value']) : ?>selected<?php endif; ?>><?= $LG_CUSTOMER_DATA[$val['paras_desc']][$_COOKIE['LANG']]?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </td>
                                <td class="TableContent"><?=$LG_CUSTOMER_DATA['星级图标'][$_COOKIE['LANG']]?></td>
                                <td class="TableData" colspan="3">
                                    <div id="star_icon">
                                        <font style="color: red;font-size:20px"><?= $project_star[$main['project_star']]['extra'] ?></font>  
                                    </div>
                                </td> -->
                                <tr>
                                <?endif;?>
                                <tr>
                                    <td class=" TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['客户概述'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="7">
                                        <textarea class="layui-textarea TextDetails" id="summarize" name="summarize" lay-verify="required"><?= $main['summarize'] ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" TableContent"><?=$LG_CUSTOMER_DATA['客户关注'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="7">
                                        <textarea class="layui-textarea TextDetails" id="focus" name="focus"><?= $main['focus'] ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="TableHeader" colspan="8"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['联系人'][$_COOKIE['LANG']]?></td>
                                </tr>
                                <tr>
                                    <td class="TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['姓名'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['电话1'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['电话2'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['邮箱'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['职位'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent" colspan="2"><?=$LG_CUSTOMER_DATA['地址'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['备注'][$_COOKIE['LANG']]?></td>
                                </tr>
                                <input type="hidden" id="addContactCount" name="addContactCount" value="<?= count($contacts); ?>" />
                                <?php $i = 0;
                                foreach ($contacts as $tk => $tv) :
                                    $i = $tk + 1; ?>
                                    <tr>
                                        <td class="TableData">
                                            <input type="text" class="layui-input" id="contacts_name" name="contacts_name<?= $i ?>" value="<?= $tv['name'] ?>" />
                                        </td>
                                        <td class="TableData">
                                            <input type="text" class="layui-input" id="phone_one" name="phone_one<?= $i ?>" value="<?= $tv['phone_one'] ?>" />
                                        </td>
                                        <td class="TableData">
                                            <input type="text" class="layui-input" id="phone_two" name="phone_two<?= $i ?>" value="<?= $tv['phone_two'] ?>" />
                                        </td>
                                        <td class="TableData">
                                            <input type="text" class="layui-input" id="email" name="email<?= $i ?>" value="<?= $tv['email'] ?>" />
                                        </td>
                                        <td class="TableData">
                                            <input type="text" class="layui-input" id="position" name="position<?= $i ?>" value="<?= $tv['position'] ?>" />
                                        </td>
                                        <td class="TableData" colspan="2">
                                            <input type="text" class="layui-input" id="contacts_address" name="contacts_address<?= $i ?>" value="<?= $tv['address'] ?>" />
                                        </td>
                                        <td class="TableData">
                                            <input type="text" class="layui-input" id="remark" name="remark<?= $i ?>" value="<?= $tv['remark'] ?>" />
                                            <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$LG_CUSTOMER_DATA['删除'][$_COOKIE['LANG']]?></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr align="left" id="addContact">
                                    <td class="TableData" colspan="8">
                                        <input value="<?=$LG_CUSTOMER_DATA['添加'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addContact');return false;" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['客户关联公司'][$_COOKIE['LANG']]?><br />（<?=$LG_CUSTOMER_DATA['合同相对方'][$_COOKIE['LANG']]?>）</td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['简称'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['国家'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent" colspan="3"><?=$LG_CUSTOMER_DATA['地址'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['联系人姓名'][$_COOKIE['LANG']]?></td>
                                    <td class="TableContent"><?=$LG_CUSTOMER_DATA['电话'][$_COOKIE['LANG']]?></td>
                                </tr>
                                <input type="hidden" id="addCompanyCount" name="addCompanyCount" value="<?= count($company); ?>" />
                                <?php $j = 0;
                                foreach ($company as $ck => $cv) :
                                    $j = $ck + 1; ?>
                                    <tr style="height: 50px;">
                                        <td class="TableData">
                                            <input type="text" name="company_name<?= $j ?>" id="company_name" class="layui-input" value="<?= $cv['contract_party'] ?>" />
                                        </td>
                                        <td class="TableData">
                                            <input type="text" name="company_short_name<?= $j ?>" id="company_short_name" class="layui-input" value="<?= $cv['short_name'] ?>" />
                                        </td>
                                        <td class="TableData">
                                            <input type="text" name="company_country<?= $j ?>" id="company_country" class="layui-input" value="<?= $cv['country'] ?>" />
                                        </td>
                                        <td class="TableData" colspan="3">
                                            <input type="text" name="company_address<?= $j ?>" id="company_address" class="layui-input" value="<?= $cv['address'] ?>" />
                                        </td>
                                        <td class="TableData">
                                            <input type="text" name="contact_name<?= $j ?>" id="contact_name" class="layui-input" value="<?= $cv['contact_name'] ?>" />
                                        </td>
                                        <td class="TableData">
                                            <input type="text" name="phone<?= $j ?>" id="phone" class="layui-input" value="<?= $cv['phone'] ?>" />
                                            <a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$LG_CUSTOMER_DATA['删除'][$_COOKIE['LANG']]?></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr align="left" id="addCompany">
                                    <td class="TableData" colspan="8">
                                        <input value="<?=$LG_CUSTOMER_DATA['添加'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addCompany');return false;" />
                                    </td>
                                </tr>
                            </table>
                            <?if($isChange):?>
                            <table id="tab3" width="100%">
                                <tr>
                                    <td class="TableContent"  style="text-align: left;width: 25%"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['变更说明'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" >
                                    
                                        <textarea class="layui-textarea" id="change_explain" name="change_explain" placeholder="变更说明" lay-verify="required"><?=$main[change_explain]?></textarea>
                                    </td>
                                </tr>
                            </table>
                            <?endif;?>
                            <table id="tab3" width="100%">
                                <tr>
                                    <input type="hidden" name="dept_belong" value="<?= $userInfo['deptName'] ?>" />
                                    <td class="TableContent" style="text-align: left;width: 25%"><?=$LG_CUSTOMER_DATA['登记单号'][$_COOKIE['LANG']]?>： </td>
                                    <td class="TableContent" style="text-align: left;width: 25%"><?=$LG_CUSTOMER_DATA['所属部门'][$_COOKIE['LANG']]?>： <?= $userInfo['deptName'] ?></td>
                                    <td class="TableContent" style="text-align: left;width: 25%"><?=$LG_CUSTOMER_DATA['登记人'][$_COOKIE['LANG']]?>： <?= $userInfo['USER_NAME'] ?></td>
                                    <td class="TableContent" style="text-align: left;"><?=$LG_CUSTOMER_DATA['登记时间'][$_COOKIE['LANG']]?>： <?= date('Y-m-d H:i:s', time()); ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
</body>

</html>