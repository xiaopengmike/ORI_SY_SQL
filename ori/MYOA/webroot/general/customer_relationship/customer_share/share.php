<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("lang.php");
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['客户信息共享'][$_COOKIE['LANG']]);
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../common/CommonMethodModel.php");
include_once("../common/DataModel.php");
require_once("../common/UserModel.php");
$dataModel = new DataModel();
$userModel = new UserModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = "customer_data";
$adminData = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $adminData['isAdmin'];
$systemAdmin = $adminData['systemAdmin'];

if (empty($id)) {
    Message("提示", "该客户不存在！");
    exit;
}
// if($isAdmin){
//     $whereStr .= " and (k.user_id = '" . $userId . "' ";
//     if (!empty($adminData['userBu'])) {
//         $whereStr .= " or k.user_bu in (" . $adminData['userBu'] . ") ";
//     }
//     if (!empty($adminData['deptIds'])) {
//         $whereStr .= " or k.organ_id in(" . $adminData['deptIds'] . ") ";
//     }
//     if (!empty($adminData['userIds'])) {
//         $whereStr .= " or k.user_id in(" . $adminData['userIds'] . ") ";
//     }
//     $whereStr .= " ) ";
//     $query=" select * from inhe_customer_data k  where k.form_id='$id' ".$whereStr;
//     $res = exequery(TD::conn(), $query);
//     $customer = mysql_fetch_assoc($res);
// }

// if(empty($customer)){
//     Message("提示", "无权共享该客户！");
//     exit;
// }

$customerArr = $dataModel->getCustomerShare(array('id' => $id));

if (empty($company)) {
    $userCompany = $userModel->getUserBu($deptId);
    $company = $customerArr['user_bu'];
}
//不是共享管理员的非耐吉客户或者非耐吉人员不能使用该共享功能
if (($customerArr['user_bu']!="INHENERGY"||$customerArr['user_bu']!="HKNERGY"||$userCompany!="INHENERGY"||$userCompany!="HKNERGY")&&$systemAdmin!=1) {
    Message("提示", "暂无共享权限，请联系商务共享！");
    exit;
}
$continentArr = $dataModel->getContinentData();
$countryArr = $dataModel->getCountryData();
$userInfo = $userModel->getUserInfo($userId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'customer_type,project_level,customer_source'
);
$selectArr = $dataModel->getCommonParam($selectParam);



?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
<script language="Javascript" type="text/javascript" src="main.js"></script>
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
            findCustomerExists();
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
    function getUserSelect(obj) {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;

            layer.open({
                title: '业务人员选择',
                type: 2,
                skin: 'layui-layer-rim',
                area: ['75%', '80%'],
                fixed: false,
                maxmin: true,
                offset: '50px',
                content: '../common/list/type_list.php?domId=' + obj.id,
                moveOut: true
            });
            form.render();
        });
    }

    function returnUser(userName, userId, domId) {
        $("#" + domId).val(userName);
        $("#" + domId).parent().find("[data-type='user_id']").val(userId); //取userId隐藏栏位赋值
    }

    // 页面初始化加载
    function init() {
        // textarea初始化样式
        $.each($("textarea"), function(i, n) {
            $(n).css("width", "100%");
            $(n).css("min-height", "20px");
            $(n).css("margin", "auto");
            $(n).css("resize", "none");
            $(n).css("overflow", "hidden");
            makeExpandingArea(document.getElementById(n.id));
        });
    }
</script>

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
                <input type="hidden" id="type" name="type" value="customer_share" />
                <input type="hidden" id="form_id" name="form_id" />
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_customer_share_main" />
                <input type="hidden" name="flowType" value="khgx" />
                <input type="hidden" name="digit" value="4" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $company ?>" />
                <input type="hidden" id="LANG" value="<?=$_COOKIE[LANG]?>" />
                <input type="hidden" name="customer_name" id="customer_name" value="<?= $customerArr['customer_name'] ?>" />
                <input type="hidden" name="customer_id" id="customer_id" value="<?= $customerArr['id'] ?>" />
                <input type="hidden" name="dept_belong" value="<?= $userInfo['deptName'] ?>" />
                <table width="70%" align="center">
                    <tr>
                    <td align="center">
                        <img alt="" src="../../../../../images/<?= $company ?>/customer_data_<?= $company ?>.jpg" style="width: 34%; height: auto;" />
                    </td>
                    </tr>
                    
                </table>
                <br />
                <table class="TableBlock" width="70%" align="center">
                <tr>
                                <td class="TableContent" width="25%"><?=$LG_CUSTOMER_DATA['客户名称'][$_COOKIE['LANG']]?></td>
                                <td class="TableData">
                                    <?= $customerArr['customer_name'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="TableContent"><?=$LG_CUSTOMER_DATA['客户关联公司'][$_COOKIE['LANG']]?><br />（<?=$LG_CUSTOMER_DATA['合同相对方'][$_COOKIE['LANG']]?>）</td>
                                <td class="TableData">
                                    <?php foreach ($customerArr['contract_party'] as $contract_party) : ?>
                                        <?= $contract_party ?>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="TableContent" width="25%"><?=$LG_CUSTOMER_DATA['共享业务人员'][$_COOKIE['LANG']]?></td>
                                <td class="TableData">
                                    <input type="hidden" data-type="user_id" name="user_id" id="user_id" />
                                    <!-- <input type="text" name="user_name" id="user_name" onclick="getUserSelect(this);return false;" class="layui-input border-solid" placeholder="请选择业务人员" lay-verify="required" value="<?= $shareUserArr ?>" /> -->
                                    <textarea class="layui-textarea" id="user_name" name="user_name" placeholder="请选择业务人员" onclick="getUserSelect(this);return false;"><?= $shareUserArr ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="TableContent" width="25%"><?=$LG_CUSTOMER_DATA['备注'][$_COOKIE['LANG']]?></td>
                                <td class="TableData">
                                   
                                    <textarea class="layui-textarea" id="remark" name="remark" placeholder="共享备注" ></textarea>
                                </td>
                            </tr>
                </table>
            </div>
        </div>
    </form>
</body>

</html>