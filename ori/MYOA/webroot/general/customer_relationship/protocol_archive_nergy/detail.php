<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("协议存档管理");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");

include_once("../common/CommonControl.php");
$model = new CommonControlModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];
$userName = $_SESSION['LOGIN_USER_NAME'];
$currentTime = date('Y-m-d H:i:s',time());

$companyNew = $model->getUserBuNew($deptId);
$userInfo = $model->getUserInfo($userId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star,check_opinion,yes_or_no,display_mode,production_mode,test_report',
);
$selectArr = $model->getCommonParam($selectParam);
$psArr = array();
foreach($selectArr['project_star'] as $ps){
    $psArr[$ps['paras_value']] = $ps;
}
$checkArr = array();
foreach($selectArr['check_opinion'] as $co){
    $checkArr[$co['paras_value']] = $co;
}
$yes_or_no = array();
foreach($selectArr['yes_or_no'] as $co){
    $yes_or_no[$co['paras_value']] = $co;
}

$action = new ActionModel();
if(empty($id)){
    // echo "对应数据不存在！";
    Message("提示","对应数据不存在！");
    exit;
}
$param['id'] = $id;
$data = $action->queryById($param);
$main = $data['main'];
$orderDetail = $data['orderDetail'];
$detailItem = $data['detailItem'];
// 获取审核数据
$approveData = $action->getApproveData($param);
// var_export($checkArr);exit;

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!-- <script type="text/javascript" src="/inc/js_lang.php"></script>
    <script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script> -->
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js" charset="utf-8"></script>
    <script language="Javascript" type="text/javascript" src="main.js"></script>
    <!-- <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css"> -->
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">

    <script type="text/javascript">
        $(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer', 'laydate', 'attach'], function() {
                var layer = layui.layer,
                    form = layui.form,
                    attach = layui.attach,
                    laydate = layui.laydate;

                $(".date").each(function() {
                    laydate.render({
                        elem: this,
                        theme: '#027AFF',
                        range: false
                    });
                });

                init();
                // attach.init({
                //     elem: ".btn-upload",
                //     uploadUrl: "/general/attachment/attach.php?type=control_library"
                // });

                form.on('submit(submit)', function(data) {
                    var btnVal = data.elem.value;
                    var backStep = data.elem.title;
                    $("#back_step").val(backStep);
                    var actionType = $(data.elem).attr("actionType");
                    var url = "action.php?action=" + actionType;
                    // return false;
                    $.post(url, $("#detailForm").serializeArray(), function(data) {
                        var reJson = null;
                        try {
                            reJson = eval('(' + data + ')');
                        } catch (e) {
                            layer.alert('服务器异常: ' + e, {
                                icon: 5
                            });
                            return;
                        }
                        if (reJson.code == "200") {
                            layer.alert(btnVal + "成功!", {

                                icon: 1
                            }, function(index, layero) {
                                window.close();
                            });
                        } else {
                            layer.alert(btnVal + '失败！', {
                                icon: 5
                            });
                        }
                    });
                });

                $('[data-type="customer_name"]').each(function() {
                    this.onfocus = function() {
                        // 获取客户数据
                        // getCustomerSelect(this);
                    }
                });

                form.on('select(project_level)', function(data) {
                    var starCount = data.value;
                    var html = "";
                    var starShow = "";
                    if (parseInt(starCount) < 5) {
                        for (var i = 0; i < parseInt(starCount) + 1; i++) {
                            starShow += "★";
                        }
                    }
                    html = '<font style="color: red;font-size:20px">' + starShow + '</font>';
                    // $("#star_icon").html(html);
                    $(data.elem).parent().parent().parent().find('[data-type="star_icon"]').html(html);

                    form.render();
                });

                //自定义验证规则,定义radio和checkbox必填
                form.verify({
                    otherReq: function(value, item) {
                        var $ = layui.$;
                        var verifyName = $(item).attr('name'),
                            verifyType = $(item).attr('type'),
                            formElem = $(item).parents('.layui-form') //获取当前所在的form元素，如果存在的话
                            ,
                            verifyElem = formElem.find('input[name=' + verifyName + ']') //获取需要校验的元素
                            ,
                            isTrue = verifyElem.is(':checked') //是否命中校验
                            ,
                            focusElem = verifyElem.next().find('i.layui-icon'); //焦点元素
                        if (!isTrue || !value) {
                            //定位焦点
                            focusElem.css(verifyType == 'radio' ? {
                                "color": "#FF5722"
                            } : {
                                "border-color": "#FF5722"
                            });
                            //对非输入框设置焦点
                            focusElem.first().attr("tabIndex", "1").css("outline", "0").blur(function() {
                                focusElem.css(verifyType == 'radio' ? {
                                    "color": ""
                                } : {
                                    "border-color": ""
                                });
                            }).focus();
                            return '必填项不能为空';
                        }
                    }
                });

                form.render();
            });
        });

        // 页面初始化加载
        function init() {
            if ('<?= $operate ?>' == 'detail') {
                $('#detailForm').find('input,textarea,select,td[class="TableData"]').not('[actionType="Approve"],[actionType="Back"],[actionType="Close"]').prop('style', 'color:#0066cc');
                $('#detailForm').find('input,textarea,select').not('[actionType="Approve"],[actionType="Back"],[actionType="Close"]').prop('disabled', true);
                $('[data-type="attach"]').hide();

                // layui下拉只读显示不置灰
                $.each($("select"), function(i, n) {
                    var optText = $(n).find("option:selected").text();
                    $(n).next().find("input").val(optText);
                    $(n).remove();
                    $('dl').remove();
                });
            } else if ('<?= $operate ?>' == 'approve') {
                $('#detailForm').find('input,textarea,select,td[class="TableData"]').not('[actionType="Approve"],[actionType="Back"],[actionType="Close"]').prop('style', 'color:#0066cc');
                $('#tab1,#tab2,#tab3').find('input,textarea,select').not('[actionType="Approve"],[actionType="Back"],[actionType="Close"]').prop('disabled', true);
                $('[data-type="attach"]').hide();
            }

            // textarea初始化样式
            $.each($("textarea"), function(i, n) {
                $(n).css("width", "100%");
                // $(n).css("min-height", "20px");
                $(n).css("height", n.scrollHeight + "px");
                $(n).css("margin", "auto");
                $(n).css("resize", "none");
                $(n).css("overflow", "hidden");
                $(n).css("readonly", "readonly");
            });

            // textarea输入后自适应高度设置
            // var introduction1 = document.getElementById('introduction1');
            // makeExpandingArea(introduction1);
            // var introduction2 = document.getElementById('introduction2');
            // makeExpandingArea(introduction2);
            // var introduction3 = document.getElementById('introduction3');
            // makeExpandingArea(introduction3);
        }
    </script>
</head>

<body>
    <div class="weadmin-body" style="width:80%;margin:1% 9%;">
        <form id="detailForm" class="layui-form">
            <input type="hidden" name="user_bu" id="user_bu" value="<?= $companyNew ?>" />
            <input type="hidden" name="form_id" id="form_id" value="<?= $id ?>" />
            <input type="hidden" name="step" id="step" value="<?= $main['step'] ?>" />
            <input type="hidden" name="back_step" id="back_step" value="0" />
            <table width="100%" align="center">
                <tr>
                    <td align="center">
                        <img alt="" src="../../../../../images/contract_protocol_<?= $companyNew ?>.jpg" style="width: 15%; height: auto;" title="" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>
                            填表说明：<br />
                            1、选择联系人，可自动带出客户信息登记表中“客户名称”所对应的“客户所在国”、“联系人”“联系人电话”“联系人电邮”“客户背景”等字段，并填入的本表相应字段中。系统自动填写后可以修改。
                        </p>
                    </td>
                </tr>
            </table>
            <br />
            <table class="TableBlock" width="100%" align="center">
                <tr>
                    <td colspan="6">
                        <?php include_once("detail/customer_info.php"); ?>
                        <?php include_once("detail/order_detail.php"); ?>
                        <?php include_once("detail/approve_opinion.php"); ?>

                        <table id="tab" width="100%">
                            <tr class="layui-bg-body">
                                <td colspan="4" align="center" style="padding: 5px;">
                                    <?php if ($operate == 'approve') : ?>
                                        <input lay-submit lay-filter="submit" name="btn_submit" actionType="Approve" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                        <input lay-submit lay-filter="submit" name="btn_back" title="-1" actionType="Back" value="退回上一步" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                        <input lay-submit lay-filter="submit" name="btn_back" title="0" actionType="Back" value="退回申请人" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <?php endif; ?>
                                    <input name="btn_close" value="关闭" type=button actionType="Close" class="layui-btn layui-btn-oa layui-btn-sm" onclick="window.close();return false;" />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>