<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("立项变更申请单");
require_once("inc/header.inc.php");
require_once("common/Common.php");
require_once("ActionModel.php");
require_once("../common/AttachModel.php");
require_once("../common/DataModel.php");
require_once("../common/UserModel.php");
$attachModel = new AttachModel();
$dataModel = new DataModel();
$userModel = new UserModel();
$currentTime = date('Y-m-d H:i:s', time());
$userId = $_SESSION['LOGIN_USER_ID'];
$userInfo = $userModel->getUserInfo($userId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,check_opinion,project_type,yes_or_no,project_level,product_detail_type',
);
$selectArr = $dataModel->getCommonParam($selectParam);
$product_detail_types=array();
foreach ($selectArr['product_detail_type'] as $v) {
    $product_detail_types[]=$v['paras_value'];
}
$project_star = array();
foreach ($selectArr['project_star'] as $v) {
    $project_star[$v['paras_value']] = $v;
}
$action = new ActionModel();
if (empty($id)) {
    Message("提示", "对应数据不存在！");
    exit;
}
$param['id'] = $id;
$data = $action->queryById($param);
$main = $data['main'];
$customerData = $data['customer'];
$project = $data['project'];
$projectDetail = $data['projectDetail'];
$bidding = $data['bidding'];
// 获取审核数据
$dataModel = new DataModel();
$customerSelect = $dataModel->getCustomerList2();
$attachModel = new AttachModel();
$attachObj = $attachModel->getAttachById($id);
$isChange = "_change";
?>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="../add.js"></script>
    <script language="Javascript" type="text/javascript" src="main.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
    <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
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
                var attachObj = <?= $attachObj ?>;
                $.initAttachList(attachObj);
                $.projectStarSelect();
                $.formSubmit();
                 // 招标项目
                 console.log($('input[name=is_bidding_project]:checked').val());
                  if($('input[name=is_bidding_project]:checked').val()=='01'){
                        $('.is_bidding_project').css('display','table-row');
                        $('.is_bidding').attr("lay-verify","required");
                    }else{
                        $('.is_bidding_project').css('display','none'); 
                        $('.is_bidding').removeAttr("lay-verify");
                    }
                    form.on('radio(is_bidding_project)', function(data) {
                    var cusId = data.value;
                    console.log(cusId);
                    if(cusId=='01'){
                        $('.is_bidding_project').css('display','table-row'); 
                        $('.is_bidding').attr("lay-verify","required");
                    }else{
                        $('.is_bidding_project').css('display','none'); 
                        $('.is_bidding').removeAttr("lay-verify");
                    }
                });
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
                 //必填增加背景色
                 setTimeout(function(){
                    $('select[lay-verify="required"]').next().find('.layui-select-title input').css({'background-color':'#f9fbd5'});
                },500);
                form.render();
                var changeStar = <?= empty($changeStar)?0:$changeStar; ?>;
                if(changeStar==1){
                    console.log(changeStar);
                    $("input[name=is_bidding_project]").attr('disabled','disabled');
                    $("input[name=is_bidding_project]").append('<input name="is_bidding_project" value="'+$("input[name=is_bidding_project]:checked").val()+'" type="hidden"/>');
                    $("#tab1,#tab2,#tab3").find('input').attr('readonly','readonly');
                    $("#tab1,#tab2,#tab3").find('input[type=button]').attr('disabled','disabled');
                    $("#tab1,#tab2,#tab3").find('select').attr('disabled','disabled');
                    $.each($("#tab1,#tab2,#tab3").find('select'),function(index, obj){
                        html='<input name="'+obj.name+'" value="'+obj.value+'" type="hidden"/>';
                        $("#tab3").after(html);
                        console.log(obj);
                    })
                    $("#tab1,#tab2,#tab3").find('textarea').attr('readonly','readonly');
                    $("#tab21").find('a').attr('disabled','disabled');
                    $("#tab21").find('a').attr("style","pointer-events:none");
                    $(".btn-upload").attr("style","display:none");
                     //星级变更移除某些必填项
                     $("#partner_analysis,#relationship,#market_analysis,#strategy_analysis,#key_to_success,#cooperate_history,#is_collective,#past_pcode,#c_past_pcode").removeAttr("lay-verify");
                }
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
                                <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="window.close();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="width:80%;margin:1% 9%;">
                <!-- scbg2020102702/ALP02/生产通知变更单 -->
                <input type="hidden"  name="change_star" value="<?=$changeStar?>" />
                <input type="hidden" id="form_id" name="form_id" />
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_project_main" />
                <input type="hidden" name="flowType" value="lxbg" />
                <input type="hidden" name="reset" value="day" />
                <input type="hidden" name="digit" value="2" />
                <input type="hidden" id="type" name="type" value="project_review_change" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="related_id" id="related_id" value="<?= $id ?>" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $main['user_bu'] ?>" />
                <input type="hidden" name="deptName" value="<?= $userInfo['deptName'] ?>" />
                <input type="hidden" name="create_user_bu" value="<?= $main['create_user_bu'] ?>" />
                <table width="100%" align="center">
                    <tr>
                        <td align="center">
                            <img alt="" src="../../../../../images/<?= $main['user_bu'] ?>/project_review_change_<?= $main['user_bu'] ?>.jpg" style="width: 34%; height: auto;" title="" />
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
                        <td class="TableContent" width="10%" align="right">部门：</td>
                        <td class="TableData" width="18%"><?= $userInfo['deptName'] ?></td>
                        <td class="TableContent" width="5%" align="right">制单人：</td>
                        <td class="TableData" width="15%"><?= $userInfo['USER_NAME'] ?></td>
                        <td class="TableContent" width="15%" align="right">日期：</td>
                        <td class="TableData" width="15%" ><?= $currentTime ?></td>
                        <td class="TableContent" >是否招标项目：</td>
                        <td class="TableData" >
                            <input type="radio" name="is_bidding_project" <?= $main['is_bidding_project']=='01'?'checked':'' ?> lay-filter="is_bidding_project" title="是" value="01">
                            <input type="radio" name="is_bidding_project" <?= $main['is_bidding_project']=='02'?'checked':'' ?>  lay-filter="is_bidding_project" title="否" value="02">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8">
                            <?php include_once("change/inhenergy/customer_info.php"); ?>
                            <?php include_once("change/inhenergy/project_situation.php"); ?>
                            <?php include_once("change/inhenergy/bidding_strategy.php"); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

</html>