<?php
include_once("../../inc/auth.php");
include_once("../../inc/utility_all.php");
include_once("../../inc/utility_org.php");
include_once("lang.php");
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['客户信息登记表'][$_COOKIE['LANG']]);
include_once("../../inc/header.inc.php");
include_once("../../common/Common.php");
include_once("../../common/CommonMethodModel.php");
include_once("../../common/DataModel.php");
require_once("../../common/UserModel.php");
$userModel = new UserModel();
$dataModel = new DataModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
if (empty($company)) {
    $company = $userModel->getUserBu($deptId);
}
//判断是否耐吉
$is_nergy=($company=="INHENERGY"||$company=="HKNERGY")?"INHENERGY":$company;

$continentArr = $dataModel->getContinentData();
$countryArr = $dataModel->getCountryData();
$userInfo = $userModel->getUserInfo($userId);
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
<script language="Javascript" type="text/javascript" src="main.js?v=20230710"></script>
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
            //findCustomerExists();
            form.render();
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
        });
    });
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
                                <input lay-submit lay-filter="submit" name="btn_submit" actionType="Submit" value="<?=$LG_CUSTOMER_DATA['提交'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input lay-submit lay-filter="submit" name="btn_save" actionType="Save" value="<?=$LG_CUSTOMER_DATA['保存'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input name="btn_close" value="<?=$LG_CUSTOMER_DATA['关闭'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="margin-top: 30px">
                <input type="hidden" id="type" name="type" value="customer_data" />
                <input type="hidden" id="form_id" name="form_id" />
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_customer_data" />
                <input type="hidden" name="flowType" value="khxx" />
                <input type="hidden" name="digit" value="4" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $company ?>" />
                <input type="hidden" id="LANG" value="<?=$_COOKIE[LANG]?>" />
                <table width="70%" align="center">
                    <tr>
                    <td align="center">
                        <img alt="" src="../../../../../images/<?= $company ?>/customer_data_<?= $company ?>.jpg" style="width: 34%; height: auto;" />
                    </td>
                    </tr>
                    <tr>
                        <td>
                        <?php include_once("instructions.php"); ?>
                        </td>
                    </tr>
                </table>
                <br />
                <table class="TableBlock" width="70%" align="center">
                    <tr>
                        <td>
                            <table id="tab1" width="100%" style="text-align: center;">
                                <tr>
                                    <td class="TableContent" width="10%"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['客户名称'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" width="15%">
                                        <input type="text" name="customer_name" id="customer_name" class="layui-input mandatory required_field" lay-verify="required" placeholder="<?=$LG_CUSTOMER_DATA['请输入客户名称'][$_COOKIE['LANG']]?>" />
                                    </td>
                                    <td class=" TableContent" width="10%"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['简称'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" width="15%">
                                        <input type="text" name="short_name" id="short_name" class="layui-input" placeholder="<?=$LG_CUSTOMER_DATA['请输入简称'][$_COOKIE['LANG']]?>"  />
                                    </td>
                                    <td class=" TableContent" width="10%"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['大洲'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" width="15%" align="left">
                                        <div class="layui-input-inline mandatory required_field">
                                            <select id="continent" name="continent" lay-filter="continent" lay-search lay-verify="required">
                                                <option value=''><?=$LG_CUSTOMER_DATA['请选择'][$_COOKIE['LANG']]?></option>
                                                <?php foreach ($continentArr as $ck => $cv) : ?>
                                                    <option value="<?= $cv['iso_two'] ?>"><?= $_COOKIE['LANG']=="EN"?$cv['en_name']:$cv['zh_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="TableContent" width="10%"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['国家'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" align="left">
                                        <div class="layui-input-inline mandatory">
                                            <select id="country" name="country" lay-search lay-verify="required">
                                                <option value=''><?=$LG_CUSTOMER_DATA['请选择'][$_COOKIE['LANG']]?></option>
                                                <?php foreach ($countryArr as $yk => $yv) : ?>
                                                    <option value="<?= $yv['iso_three'] ?>"><?= $_COOKIE['LANG']=="EN"?$yv['country']:$yv['zh_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" TableContent"><?=$LG_CUSTOMER_DATA['网址'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="3">
                                        <input type="text" name="website" id="website" class="layui-input" placeholder="<?=$LG_CUSTOMER_DATA['最多150个字符'][$_COOKIE['LANG']]?>" lay-verify="str_length" lay-max="150" lay-name="网址" placeholder="请输入网址" />
                                    </td>
                                    <td class=" TableContent"><?=$LG_CUSTOMER_DATA['地址'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="3">
                                        <input type="text" name="address" id="address" class="layui-input" placeholder="<?=$LG_CUSTOMER_DATA['请输入客户地址'][$_COOKIE['LANG']]?>" />
                                    </td>
                                </tr>
                                <?if($is_nergy=="INHENERGY"):?>
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
                                <!-- <td class="TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['是否有效'][$_COOKIE['LANG']]?></td>
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
                                <td class="TableData" colspan="2"></td> -->
                                <input type="hidden" name="is_vaild" id="is_vaild" class="layui-input" value="01" />
                                <td class="TableData" colspan="4"></td>
                                <!-- <td class="TableContent"><?=$LG_CUSTOMER_DATA['星级'][$_COOKIE['LANG']]?></td>
                                <td class="TableData" >
                                    <div class="layui-input-inline mandatory">
                                        <select id="project_level" name="project_star" lay-filter="project_level" lay-search lay-verify="required">
                                            <option value=''><?=$LG_CUSTOMER_DATA['请选择'][$_COOKIE['LANG']]?></option>
                                            <?php foreach ($selectArr['project_level'] as $val) : ?>
                                                <option value="<?= $val['paras_value'] ?>" <?php if ($main['project_level'] == $val['paras_value']) : ?>selected<?php endif; ?>><?= $LG_CUSTOMER_DATA[$val['paras_desc']][$_COOKIE['LANG']] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </td>
                                <td class="TableContent"><?=$LG_CUSTOMER_DATA['星级图标'][$_COOKIE['LANG']]?></td>
                                <td class="TableData" colspan="3">
                                    <div id="star_icon"></div>
                                </td> -->
                                <tr>
                                <?endif;?>
                                <tr>
                                    <td class=" TableContent"><span class="xing">*</span><?=$LG_CUSTOMER_DATA['客户概述'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="7">
                                        <textarea class="layui-textarea" id="summarize" name="summarize" placeholder="<?=$LG_CUSTOMER_DATA['请输入客户概述'][$_COOKIE['LANG']]?>" lay-verify="required"></textarea>
                                    </td>
                                </tr>
 
                                <?if($is_nergy=="INHENERGY"):?>
                                <tr>
                                    <td class=" TableContent"><?=$LG_CUSTOMER_DATA['客户关注'][$_COOKIE['LANG']]?></td>
                                    <td class="TableData" colspan="7">
                                        <textarea class="layui-textarea" id="focus" name="focus" placeholder="<?=$LG_CUSTOMER_DATA['请输入客户关注点'][$_COOKIE['LANG']]?>"></textarea>
                                    </td>
                                </tr>
                                <?endif;?>
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
                                <tr> 
                                    <td class="TableData"><input type="text" class="layui-input <?if($is_nergy=="INHENERGY"):?>mandatory<?endif;?>"  <?if($is_nergy=="INHENERGY"):?>lay-verify="required"<?endif;?> id="contacts_name" name="contacts_name1" /></td>
                                    <td class="TableData"><input type="text" class="layui-input <?if($is_nergy=="INHENERGY"):?>mandatory<?endif;?>" id="phone_one" name="phone_one1" /></td>
                                    <td class="TableData"><input type="text" class="layui-input" id="phone_two" name="phone_two1" /></td>
                                    <td class="TableData"><input type="text" class="layui-input <?if($is_nergy=="INHENERGY"):?>mandatory<?endif;?>" id="email" name="email1" /></td>
                                    <td class="TableData"><input type="text" class="layui-input" id="position" name="position1" /></td>
                                    <td class="TableData" colspan="2"><input type="text" class="layui-input" id="contacts_address" name="contacts_address1" /></td>
                                    <td class="TableData"><input type="text" class="layui-input" id="remark" name="remark1" /><a href="javascript:;" onclick="removeLine(this)" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$LG_CUSTOMER_DATA['删除'][$_COOKIE['LANG']]?></a></td>
                                </tr>
                                <input type="hidden" id="addContactCount" name="addContactCount" value="1" />
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
                                <tr>
                                    <input type="hidden" id="addCompanyCount" name="addCompanyCount" value="1" />
                                    <td class="TableData"><input type="text" name="company_name1" id="company_name" class="layui-input" /></td>
                                    <td class="TableData"><input type="text" name="company_short_name1" id="company_short_name" class="layui-input" /></td>
                                    <td class="TableData"><input type="text" name="company_country1" id="company_country" class="layui-input" /></td>
                                    <td class="TableData" colspan="3"><input type="text" name="company_address1" id="company_address" class="layui-input" /></td>
                                    <td class="TableData"><input type="text" name="contact_name1" id="contact_name" class="layui-input" /></td>
                                    <td class="TableData"><input type="text" name="phone1" id="phone" class="layui-input" /></td>
                                </tr>
                                <tr align="left" id="addCompany">
                                    <td class="TableData" colspan="8">
                                        <input value="<?=$LG_CUSTOMER_DATA['添加'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addCompany');return false;" />
                                    </td>
                                </tr>
                            </table>
                            <table id="tab3" width="100%">
                                <tr>
                                    <input type="hidden" name="dept_belong" value="<?= $userInfo['deptName'] ?>" />
                                    <td class="TableContent" style="text-align: left;width: 25%"><?=$LG_CUSTOMER_DATA['登记单号'][$_COOKIE['LANG']]?>： </td>
                                    <td class="TableContent" style="text-align: left;width: 25%"><?=$LG_CUSTOMER_DATA['所属部门'][$_COOKIE['LANG']]?>： <?= $userInfo['deptName'] ?></td>
                                    <td class="TableContent" style="text-align: left;width: 25%"><?=$LG_CUSTOMER_DATA['登记人'][$_COOKIE['LANG']]?>： <?= $_SESSION['LOGIN_USER_NAME'] ?></td>
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