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

if($id){
    $customerArr = $dataModel->getCustomerShare(array('id' => $id));
}


if (empty($company)) {
    $userCompany = $userModel->getUserBu($deptId);
    $company = $customerArr['user_bu']?$customerArr['user_bu']:$userCompany;
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
<script src="/inc/js/module.js"></script>
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
           // makeCountrySelect();
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
        $("#customer_name").click(function() {
            var user_bu=$("#user_bu").val();
            var index = layui.layer.open({
                title: "客户列表",
                type: 2,
                content: "./customer_list.php?user_bu="+user_bu,
                area: ['600px', '650px'],
                success: function(layero, index) {
                    setTimeout(function() {
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    }, 500)
                }
            });
        });
    });
    function returnParentInfo(reData){
        layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                $("#customer_name").val(reData.customer_name);
                $("#customer_id").val(reData.id);
                var user_bu=$("#user_bu").val();
                if(user_bu!=reData.user_bu){
                    $("#user_bu").val(reData.user_bu);
                    top_img_src="../../../../../images/"+reData.user_bu+"/customer_share_"+reData.user_bu+".jpg";
                    $("#top_img").attr("src", top_img_src);;
                }
                
        });
    }
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
                <input type="hidden" name="dept_belong" value="<?= $userInfo['deptName'] ?>" />
                <table width="70%" align="center">
                    <tr>
                    <td align="center">
                        <img alt="" id="top_img" src="../../../../../images/<?= $company ?>/customer_share_<?= $company ?>.jpg" style="width: 34%; height: auto;" />
                    </td>
                    </tr>
                    
                </table>
                <br />
                <table class="TableBlock" width="70%" align="center">
                <tr>
                    <td class="TableContent" width="25%"><?=$LG_CUSTOMER_DATA['客户名称'][$_COOKIE['LANG']]?></td>
                    <td class="TableData">
                        <input type="hidden" name="user_id" id="user_id" value="<?=$userId?>">
                        <input type="hidden" name="customer_id" id="customer_id" value="<?=$main['customer_id']?>">
                        <input class="layui-input mandatory" placeholder="点击选择客户" name="customer_name" id="customer_name" value="<?=$main['customer_name']?>" readonly lay-verify="required">
                    </td>
                </tr>
                <!-- <tr>
                    <td class="TableContent" width="25%"><?=$LG_CUSTOMER_DATA['共享业务人员'][$_COOKIE['LANG']]?></td>
                    <td class="TableData">
                    <input type="hidden" name="user_id" id="user_id" value="<?=$main['user_ids']?>">
                    <textarea placeholder="请选择业务人员" onClick="SelectUserSingle('','user_id', 'user_id_name')" class="layui-textarea mandatory" id="user_id_name" name="user_id_name" value="<?= $main['user_id_name']?>" readonly lay-verify="required"><?= $main['user_id_name']?></textarea>
                    <a href="#" class="orgAdd" onClick="SelectUserSingle('','user_id', 'user_id_name')" title="添加收件人">添加</a>
                            <a href="#" class="orgClear" onClick="ClearUser('user_id', 'user_id_name')" title="清空收件人">清空</a><br>
                    </td>
                </tr> -->
                <tr>
                    <td class="TableContent" width="25%"><?=$LG_CUSTOMER_DATA['备注'][$_COOKIE['LANG']]?></td>
                    <td class="TableData">
                        
                        <textarea class="layui-textarea mandatory" id="remark" name="remark" placeholder="备注说明" lay-verify="required"></textarea>
                    </td>
                </tr>
                </table>
            </div>
        </div>
    </form>
</body>

</html>