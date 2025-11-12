<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("合作协议登记表");
include_once("inc/header.inc.php");
include_once("common/Common.php");

include_once("../common/CommonControl.php");
include_once("ActionModel.php");
$model = new CommonControlModel();
$actionModel = new ActionModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userId = $_SESSION['LOGIN_USER_ID'];

$userInfo = $model->getUserInfo($userId);
$companyNew = $model->getUserBuNew($deptId);

$continentArr = $model->getContinentData();
$countryArr = $model->getCountryData();
$param = array(
    'fieldName' => 'form_id',
    'tableName' => 'inhe_contract_protocol_grid',
    'flowType' => 'sctz',  // sctz2020083101
    'reset' => 'day',
    'digit' => '2'
);
// $formId = $model->createFlowId($param);

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
    <script language="Javascript" type="text/javascript" src="main.js"></script>
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
                attach.init({
                    elem: ".btn-upload",
                    uploadUrl: "/general/attachment/attach.php?type=control_library&url=common"
                });

                form.on('submit(submit)', function(data) {
                    var btnVal = data.elem.value;
                    var actionType = $(data.elem).attr("actionType");
                    var url = "action.php?action=" + actionType;
                    $.post(url, $("#addForm").serializeArray(), function(data) {
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
                    $("#star_icon").html(html);

                    form.render();
                });

                form.render();
            });
        });

        // 页面初始化加载
        function init() {
            // textarea初始化样式
            $.each($("textarea"), function(i, n) {
                $(n).css("width", "100%");
                $(n).css("min-height", "20px");
                $(n).css("margin", "auto");
                $(n).css("resize", "none");
                $(n).css("overflow", "hidden");
            });

            // textarea输入后自适应高度设置
            var introduction1 = document.getElementById('introduction1');
            makeExpandingArea(introduction1);
            var introduction2 = document.getElementById('introduction2');
            makeExpandingArea(introduction2);
            var introduction3 = document.getElementById('introduction3');
            makeExpandingArea(introduction3);
        }
    </script>

<body>
    <div class="weadmin-body" style="width:90%;margin:1% 4%;">
        <form id="addForm" class="layui-form">
            <input type="hidden" name="user_bu" id="user_bu" value="<?= $companyNew ?>" />
            <input type="hidden" name="fieldName" value="form_id" />
            <input type="hidden" name="tableName" value="inhe_contract_protocol_grid" />
            <input type="hidden" name="flowType" value="sctz" />
            <input type="hidden" name="reset" value="day" />
            <input type="hidden" name="digit" value="2" />
            <input type="hidden" name="type" value="contract_protocol_grid" />
            <input type="hidden" name="step" value="0" />
            <table width="100%" align="center">
                <tr>
                    <td align="center">
                        <img alt="" src="../../../../../images/contract_protocol_<?= $companyNew ?>.jpg" style="width: 15%; height: auto;" title="" />
                    </td>
                </tr>
            </table>
            <br />
            <!-- <table class="TableBlock" width="100%" align="center"> -->
            <table class="layui-table" border="0" cellpadding="0" cellspacing="10" lay-size="sm">
                <thead>
                    
                    <tr class="layui-bg-body">
                        <td align="center" style="padding: 5px;">
                            <input lay-submit lay-filter="submit" name="btn_submit" actionType="Submit" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                            <input lay-submit lay-filter="submit" name="btn_save" actionType="Save" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                            <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="window.close();return false;" />
                        </td>
                    </tr>
                </thead>
                <!-- <tr>
                    <td>
                        <table id="tab" width="100%">
                            <tr class="layui-bg-body">
                                <td align="center" style="padding: 5px;">
                                    <input lay-submit lay-filter="submit" name="btn_submit" actionType="Submit" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <input lay-submit lay-filter="submit" name="btn_save" actionType="Save" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="window.close();return false;" />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr> -->
            </table>
        </form>
    </div>
</body>

</html>