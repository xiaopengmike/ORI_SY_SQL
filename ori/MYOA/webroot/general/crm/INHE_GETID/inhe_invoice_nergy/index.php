<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("形式发票取号");
include_once("inc/header.inc.php");
include_once("common/Common.php");
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/attach.js"></script>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></SCRIPT>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">

<script>
    $(function() {
        layui.use(['form', 'layer', 'laydate'], function() {
            var layer = layui.layer,
                laydate = layui.laydate,
                form = layui.form;

            // $("#menu_parent_name").change(function() {
            //     if (ifEmpty(this.value)) {
            //         $("#menu_parent").val("");
            //     }
            // });

            $(".date").each(function() {
                laydate.render({
                    elem: this,
                    theme: '#027AFF',
                    range: false
                });
            });

            loadList('1');

            form.render();
        });
    });

    function loadList(page) {
        $("#tableList").empty();
        $("#tableList").html(
            '<div><img src="/static/images/loading.gif" align="absmiddle" />正在装载...</div>'
        );
        $("#tableList").load("query_page.php?" + $("#tab").serialize() + "&page=" + page, function() {

        });
    }
</script>

<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="page_size" name="page_size" value="10" />
        <table style="margin: 1%">
            <tr>
                <td nowrap align="right">发票号码：</td>
                <td>
                    <input type="text" name="pm_id" id="pm_id" class="layui-input" placeholder="请输入发票号码">
                </td>
                <td nowrap align="right">创建时间：</td>
                <td>
                    <input type="text" name="create_date" id="create_date" class="layui-input date" placeholder="请选择日期时间">
                </td>
                <td>
                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="loadList('1');return false;" style="margin-left: 10px;">查询</button>
                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="pageOperate('add.php','smallModel');return false;">新建</button>
                </td>
            </tr>
        </table>
    </form>
    <div id="tableList" style="width:100%;text-align:center;">

    </div>
</body>

</html>