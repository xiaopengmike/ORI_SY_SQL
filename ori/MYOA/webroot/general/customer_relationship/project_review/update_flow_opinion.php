<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("客户信息共享");
include_once("inc/header.inc.php");
include_once("common/Common.php");
if (empty($id)) {
    Message("提示", "该流程不存在！");
    exit;
}

$sql="SELECT * FROM inhe_flow_opinion WHERE id='$id' and type='$type'";

$main=array();
$res = exequery(TD::conn(), $sql);
if($item = mysql_fetch_assoc($res)) {
    $main=$item;
}

?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
<script src="<?= MYOA_JS_SERVER ?>/general/workflow/list/js/work_operation.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></script>

<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
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
                            parent.closeIframe();
                            window.opener.location.reload();
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
            makeExpandingArea(document.getElementById(n.id));
        });
    }
</script>

<body>
    <br />
    <div class="weadmin-body">
        <form id="mainForm" class="layui-form">
            <input type="hidden" name="form_type" id="form_type" value="change_approve_user" />
            <input type="hidden" name="step" id="step" value="<?= $main['step'] ?>" />
            <input type="hidden" name="type" id="type" value="<?= $main['type'] ?>" />
            <input type="hidden" name="form_id" id="form_id" value="<?= $main['form_id'] ?>" />
            <input type="hidden" name="id" id="id" value="<?= $main['id'] ?>" />
            <table class="TableBlock" width="70%" style="margin-top: 3%;" align="center">
                <tr>
                    <td>
                        <table id="tab1" width="100%" style="text-align: left;">
                            <tr>
                                <td class="TableContent" width="25%">流程标题</td>
                                <td class="TableData">
                                    <?= $main['title'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="TableContent">流程步骤</td>
                                <td class="TableData">
                                    <?= $main['work_flow'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="TableContent" width="25%">移交人员</td>
                                <td class="TableData">
                                    <input type="hidden" data-type="user_id" name="user_id" id="user_id" />
                                    <!-- <input type="text" name="user_name" id="user_name" onclick="getUserSelect(this);return false;" class="layui-input border-solid" placeholder="请选择业务人员" lay-verify="required" value="<?= $shareUserArr ?>" /> -->
                                    <textarea readonly lay-verify="required" class="layui-textarea" id="user_name" name="user_name" placeholder="请选择业务人员" onclick="LoadDialogWindow('/module/user_select_single/?TO_ID=user_id&TO_NAME=user_name',self,200, 200, 450, 300);return false;"><?= $shareUserArr ?></textarea>
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