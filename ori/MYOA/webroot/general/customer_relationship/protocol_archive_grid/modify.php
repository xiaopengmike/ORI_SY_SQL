<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("客户信息登记表");
include_once("inc/header.inc.php");
include_once("common/Common.php");

include_once("/MYOA/webroot/general/hr/UserCommonModel.php");
$userModel = new UserCommonModel();

$deptId = $_SESSION['LOGIN_DEPT_ID'];
$companyNew = $userModel->getUserBuNew($deptId);

?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
<script language="Javascript" type="text/javascript" src="customer_data.js"></script>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">

<style>
    .border-solid {
        border: 1px solid;
    }
</style>
<script type="text/javascript">
    $(function() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;

            init();

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
                            closeModel();
                        });
                    } else {
                        layer.alert(btnVal + '失败！', {
                            icon: 5
                        });
                    }
                });
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
        var summarize = document.getElementById('summarize');
        makeExpandingArea(summarize);

    }
</script>

<body>
    <br />
    <div class="weadmin-body">
        <form id="form1" class="layui-form">
            <input type="hidden" name="data_count" id="data_count" value="" />
            <input type="hidden" name="user_bu" id="user_bu" value="<?= $companyNew ?>" />
            <table width="70%" align="center">
                <td align="center">
                    <img alt="" src="../../../../../images/client_change_<?= $companyNew ?>.jpg" style="width: 34%; height: auto;" title="" />
                </td>
            </table>
            <br />
            <table class="TableBlock" width="70%" align="center">
                <tr>
                    <td>
                        <table id="tab1" width="100%" style="text-align: center;">
                            <tr>
                                <td nowrap class="TableContent" width="10%">客户名称</td>
                                <td nowrap class="TableData" width="15%">
                                    <input type="text" name="customer_name" id="customer_name" class="layui-input" placeholder="请输入客户名称" />
                                </td>
                                <td nowrap class=" TableContent" width="10%">简称</td>
                                <td nowrap class="TableData" width="15%">
                                    <input type="text" name="short_name" id="short_name" class="layui-input" placeholder="请输入客户简称" />
                                </td>
                                <td nowrap class=" TableContent" width="10%">大洲</td>
                                <td nowrap class="TableData" width="15%">
                                    <input type="text" name="continent" id="continent" class="layui-input" placeholder="请输入大洲名称" />
                                </td>
                                <td nowrap class=" TableContent" width="10%">国家</td>
                                <td nowrap class="TableData">
                                    <input type="text" name="country" id="country" class="layui-input" placeholder="请输入国家名称" />
                                </td>
                            </tr>
                            <tr>
                                <td nowrap class=" TableContent">网址</td>
                                <td nowrap class="TableData" colspan="3">
                                    <input type="text" name="website" id="website" class="layui-input" placeholder="请输入网址" />
                                </td>
                                <td nowrap class=" TableContent">地址</td>
                                <td nowrap class="TableData" colspan="3">
                                    <input type="text" name="address" id="address" class="layui-input" placeholder="请输入客户地址" />
                                </td>
                            </tr>
                            <tr>
                                <td nowrap class=" TableContent">客户概述</td>
                                <td nowrap class="TableData" colspan="7">
                                    <textarea class="layui-textarea" id="summarize" name="summarize" placeholder="请输入客户概述"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap class="TableHeader" colspan="8">联系人</td>
                            </tr>
                            <tr>
                                <td nowrap class="TableContent">姓名</td>
                                <td nowrap class="TableContent">电话1</td>
                                <td nowrap class="TableContent">电话2</td>
                                <td nowrap class="TableContent">邮箱</td>
                                <td nowrap class="TableContent" colspan="3">地址</td>
                                <td nowrap class="TableContent">备注</td>
                            </tr>
                            <tr>
                                <input type="hidden" id="addContactCount" name="addContactCount" value="1" />
                                <td nowrap class="TableData"><input type="text" class="layui-input" id="contacts_name" name="contacts_name" /></td>
                                <td nowrap class="TableData"><input type="text" class="layui-input" id="phone_first" name="phone_first" /></td>
                                <td nowrap class="TableData"><input type="text" class="layui-input" id="phone_second" name="phone_second" /></td>
                                <td nowrap class="TableData"><input type="text" class="layui-input" id="email" name="email" /></td>
                                <td nowrap class="TableData" colspan="3"><input type="text" class="layui-input" id="contacts_address" name="contacts_address" /></td>
                                <td nowrap class="TableData"><input type="text" class="layui-input" id="remark" name="remark" /></td>
                            </tr>
                            <tr align="left" id="addContact">
                                <td nowrap class="TableData" colspan="8">
                                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addContact');return false;">添加一行</button>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap class="TableContent">客户关联公司<br />（合同相对方）</td>
                                <td nowrap class="TableContent">简称</td>
                                <td nowrap class="TableContent">国家</td>
                                <td nowrap class="TableContent" colspan="3">地址</td>
                                <td nowrap class="TableContent">联系人姓名</td>
                                <td nowrap class="TableContent">电话</td>
                            </tr>
                            <tr>
                                <input type="hidden" id="addCompanyCount" name="addCompanyCount" value="1" />
                                <td nowrap class="TableData"><input type="text" name="company_name" id="company_name" class="layui-input" /></td>
                                <td nowrap class="TableData"><input type="text" name="company_short_name" id="company_short_name" class="layui-input" /></td>
                                <td nowrap class="TableData"><input type="text" name="company_country" id="company_country" class="layui-input" /></td>
                                <td nowrap class="TableData" colspan="3"><input type="text" name="company_address" id="company_address" class="layui-input" /></td>
                                <td nowrap class="TableData"><input type="text" name="contact_name" id="contact_name" class="layui-input" /></td>
                                <td nowrap class="TableData"><input type="text" name="phone" id="phone" class="layui-input" /></td>
                            </tr>
                            <tr align="left" id="addCompany">
                                <td nowrap class="TableData" colspan="8">
                                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="addLine('addCompany');return false;">添加一行</button>
                                </td>
                            </tr>
                        </table>
                        <table id="tab2" width="100%">
                            <?php include_once("detail.php"); ?>
                        </table>

                        <table id="tab3" width="100%">
                            <tr class="layui-bg-body layui-bg-gray" id="tHeader">
                                <td colspan="4" align="center" style="padding: 5px;">
                                    <input lay-submit lay-filter="submit" name="btn_submit" actionType="Submit" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <input lay-submit lay-filter="submit" name="btn_save" actionType="Save" value="保存" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                    <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
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