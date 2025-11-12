<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("lang.php");
$HTML_PAGE_TITLE = _("客户信息共享");
include_once("inc/header.inc.php");
include_once("common/Common.php");
if (empty($id)) {
    Message("提示", "该客户不存在！");
    exit;
}
include_once("../common/DataModel.php");
$dataModel = new DataModel();
$customerArr = $dataModel->getCustomerShare(array('id' => $id));
$shareUserArr = $dataModel->queryShareList(array('id' => $id));

?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
<script language="Javascript" type="text/javascript" src="main.js"></script>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/dtreeck.css">
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/dtreeck.js"></script>
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
                $.post(url, $("#mainForm").serializeArray(), function(data) {
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
                            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                            parent.layer.close(index); //再执行关闭
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
    <br />
    <div class="weadmin-body">
        <form id="mainForm" class="layui-form">
            <input type="hidden" name="form_type" id="form_type" value="customer_share" />
            <input type="hidden" name="customer_name" id="customer_name" value="<?= $customerArr['customer_name'] ?>" />
            <input type="hidden" name="customer_id" id="customer_id" value="<?= $customerArr['id'] ?>" />
            <table class="TableBlock" width="70%" style="margin-top: 3%;" align="center">
                <tr>
                    <td>
                        <table id="tab1" width="100%" style="text-align: left;">
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
                            
                        </table>
                        <table id="tab_last" width="100%">
                            <tr class="layui-bg-body layui-bg-gray" id="tHeader">
                                <td colspan="4" align="center" style="padding: 5px;">
                                    <input lay-submit lay-filter="submit" name="btn_submit" actionType="Submit" value="提交" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <br />
    <?php
    include_once("common/mydtree_new.php");
    ?>
</body>

</html>