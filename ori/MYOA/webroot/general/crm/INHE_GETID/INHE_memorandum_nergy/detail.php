<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("详情页面");
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("ActionModel.php");

$model = new ActionModel();
$result = $model->query($id);
$mainRes = $result['mainResult'];  // 项目主要信息
$subRes = $result['subResult'];  // 项目比例详细信息

?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/attach.js"></script>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="pm_manage.js"></SCRIPT>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">

<script type="text/javascript">
    $(function() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;

            $.each($("textarea"), function(i, n) {
                $(n).css("height", n.scrollHeight + "px");
                // $(n).css("width", n.scrollWidth + "px");
                $(n).css("resize", "none");
                $(n).css("overflow", "hidden");
            })

            form.render();
        });
    });
</script>

<body>
    <div class="weadmin-body">
        <form id="form1" class="layui-form">
            <input type="hidden" name="dept_number" id="dept_number" value="" />
            <input type="hidden" name="data_count" id="data_count" value="" />
            <table class="TableBlock" width="70%" align="center" style="margin-top: 20px;">
                <tr>
                    <td>
                        <table id="tab1" width="100%">
                            <tr>
                                <td nowrap class="TableContent" width="20%">项目代号：</td>
                                <td nowrap class="TableData">
                                    <?= $mainRes['PM_ID'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap class="TableContent" width="20%">审核人员：</td>
                                <td nowrap class="TableData">
                                    <?= $mainRes['approver'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap class="TableContent" style="text-align: left" colspan="2">项目比例关系：</td>
                            </tr>
                        </table>
                        <table id="tab2" width="100%">
                            <tr align="center">
                                <td nowrap class="TableContent" width="20%">序号</td>
                                <td nowrap class="TableContent" width="40%">人员</td>
                                <td nowrap class="TableContent">比例</td>
                            </tr>
                            <?php foreach ($subRes as $key => $val) : ?>
                                <tr align="center">
                                    <td nowrap class="TableData" width="20%"><?= $val['user_item'] ?></td>
                                    <td nowrap class="TableData" width="40%"><?= $val['user_name'] ?></td>
                                    <td nowrap class="TableData"><?= $val['proportion'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <table id="tab3" width="100%">
                            <tr>
                                <td nowrap class="TableContent" style="text-align: left">审核流程：</td>
                            </tr>
                            <tr>
                                <td nowrap class="TableData" style="text-align: left">
                                    <input disabled type="radio" name="reviewed" value="是" title="同意" <?php if ($mainRes['Reviewed'] == "是") : ?>checked<?php endif; ?> />
                                    <input disabled type="radio" name="reviewed" value="否" title="不同意" <?php if ($mainRes['Reviewed'] == "否") : ?>checked<?php endif; ?> />
                                </td>
                            </tr>
                            <tr>
                                <td class="TableData" style="text-align: left">
                                    <textarea disabled name="reviewed_content" class="layui-textarea TableData"><?= $mainRes['Reviewed_content'] ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="TableData" style="text-align: left">
                                    <?= $mainRes['Reviewed_date'] ?>
                                </td>
                            </tr>
                            <tr class="layui-bg-body layui-bg-gray" id="tHeader">
                                <td colspan="4" align="center" style="padding: 5px;">
                                    <input name="btn_close" value="关闭" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="closeModel();return false;" />
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