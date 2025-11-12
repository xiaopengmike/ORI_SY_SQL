<?php
require_once("inc/auth.inc.php");
$HTML_PAGE_TITLE = '立项申请表';
require_once("inc/header.inc.php");
require_once("common/Common.php");
require_once("../common/CommonMethodModel.php");
require_once("../common/UserModel.php");
require_once("../common/DataModel.php");
$model = new CommonMethodModel();
$userModel = new UserModel();
$dataModel = new DataModel();
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
if (empty($company)) {
    $company = $userModel->getUserBu($deptId);
}
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'yes_or_no,project_type,project_level,product_detail_type'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$product_detail_types=array();
foreach ($selectArr['product_detail_type'] as $v) {
    $product_detail_types[]=$v['paras_value'];
}
$userInfo = $userModel->getUserInfo($userId);
$paramArr = array(
    'user_id' => $userId
);
$customerSelect = $dataModel->getCustomerList($paramArr);
$projectCodeSelect = $dataModel->getTempCode($paramArr);
// 判断制单人是否为项目经理，若为项目经理，是否需经过项目经理默认为否，其他默认为是
$ifProjectManager = false;
$ifProjectManager = $dataModel->verifyProjectManger($userId);
?>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
<script language="Javascript" type="text/javascript" src="main.js"></script>
<script language="Javascript" type="text/javascript" src="../add.js"></script>
<link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
<link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
<style>
.required_field{
    background-color: #f9fbd5;
    }
</style>
<script type="text/javascript">
    $(function() {
        layui.extend({
            attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
        }).use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;
                form.verify({
                    checknum:function(value,item){
                        var numreg=/(^$)|^[1-9]\d*$/;
                        if(!numreg.test(value)){
                            return "只能填写正整数";
                        }
                    }
                })
            $.initDate();
            $.initTextarea();
            $.initAttach();
            $.formSubmit();
            $.projectStarSelect();
             // 招标项目
             $('.is_bidding_project').css('display','none');
             form.on('radio(is_bidding_project)', function(data) {
                var cusId = data.value;
                //console.log(cusId);
                if(cusId=='01'){
                    $('.is_bidding_project').css('display','');
                    $('.is_bidding').attr("lay-verify","required");
                }else{
                    $('.is_bidding_project').css('display','none');
                    $('.is_bidding').removeAttr("lay-verify");
                }
            });
            // 返回客户登记数据
            form.on('select(customer_name)', function(data) {
                var cusId = data.value;
                var cusCount = data.elem.title;
                var url = "action.php?action=Retrieve";
                if(data.elem.name=='name1'){
                    var select = 'dd[lay-value=' + data.value + ']';
                    $('select[name=name3]').siblings("div.layui-form-select").find('dl').find(select).click();
                }
                $.post(url, {
                    dataType: 'getCustomerDetail',
                    id: cusId
                }, function(data) {
                    var reJson = null;
                    var text = '';
                    reJson = eval('(' + data + ')');
                    if (reJson.code == '200') {
                        // 回显数据
                        $("#customer_id" + cusCount).val(reJson.data['short_name']);
                        $("#country" + cusCount).val(reJson.data['country_desc']);
                        $("#contact" + cusCount).val(reJson.data['contact_name']);
                        $("#phone" + cusCount).val(reJson.data['contact_phone']);
                        $("#email" + cusCount).val(reJson.data['email']);
                        text = '';
                        // 解决历史数据空格和换行导致的显示问题
                        text = reJson.data['summarize'].replace(/\r\n/g, "<br>").replace(/\n/g, "<br>").replace(/\s/g, "").replace(/<br>/g, '\r\n');
                        $("#introduction" + cusCount).val(text);
                    }
                    $.initTextarea();
                    form.render('select','customer_name');
                });
            });
            // 项目代号取号及验证存在
            $("#project_code").blur(function() {
                var url = "action.php?action=Retrieve";
                // 校验系统项目代号
                if (this.value != '') {
                    $.post(url, {
                        dataType: 'verifyProjectCode',
                        projectCode: this.value
                    }, function(data) {
                        var reJson = null;
                        reJson = eval('(' + data + ')');
                        if (reJson.code == '200') {
                            if (reJson.data.code=='200') {
                                layer.msg('项目代号已存在！');
                                $("#project_code").val('');
                            }
                        }
                    });
                }
            }).click(function() {
                var index = layui.layer.open({
                    title: "取立项项目代号",
                    type: 2,
                    area: ['600px', '500px'],
                    content: "get_project_code.php",
                    success: function(layero, index) {
                        setTimeout(function() {
                            layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                tips: 3
                            });
                        }, 500)
                    }
                });
            });
             //必填增加背景色
             setTimeout(function(){
                $('select[lay-verify="required"]').next().find('.layui-select-title input').css({'background-color':'#f9fbd5'});
            },500);
            form.render();
        });
    });
</script>
<!-- <style>
.is_bidding_project{
    display: none;
}
</style> -->
<body>
    <div style="width: 100%;height:auto;margin:auto;">
        <form id="mainForm" class="layui-form">
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
                <input type="hidden" id="form_id" name="form_id" />
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_project_main" />
                <input type="hidden" name="flowType" value="lxdj" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" id="type" name="type" value="project_review" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $company ?>" />
                <input type="hidden" name="deptName" value="<?= $userInfo['deptName'] ?>" />
                <input type="hidden" name="create_user_bu" value="<?= $userInfo['company_user_bu'] ?>" />
                <table width="100%" align="center">
                    <tr>
                        <td align="center">
                            <img alt="" src="../../../../../images/<?= $company ?>/project_review_<?= $company ?>.jpg" style="width: 34%; height: auto;" />
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
                        <td class="TableContent" width="10%" align="right">立项部门：</td>
                        <td class="TableData" width="18%"><?= $userInfo['deptName'] ?></td>
                        <td class="TableContent" width="5%" align="right">制单人：</td>
                        <td class="TableData" width="15%"><?= $_SESSION['LOGIN_USER_NAME'] ?></td>
                        <td class="TableContent" width="15%" align="right">日期：</td>
                        <td class="TableData" width="15%"><?= date('Y-m-d H:i:s', time()) ?></td>
                        <td class="TableContent" >是否招标项目：</td>
                        <td class="TableData" >
                            <input type="radio" name="is_bidding_project" lay-filter="is_bidding_project" title="是" value="01">
                            <input type="radio" name="is_bidding_project" lay-filter="is_bidding_project" title="否" value="02" checked>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8">
                            <?php include_once("form_module/inhenergy/customer_info.php"); ?>
                            <?php include_once("form_module/inhenergy/project_situation.php"); ?>
                            <?php include_once("form_module/inhenergy/bidding_strategy.php"); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>