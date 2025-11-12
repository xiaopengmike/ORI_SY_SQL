<?php
// include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("客户信息列表");
include_once("inc/header.inc.php");
include_once("common/Common.php");

?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/attach.js"></script>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></SCRIPT>
<SCRIPT language="Javascript" type="text/javascript" src="main.js"></SCRIPT>
    <script language="Javascript" type="text/javascript" src="../list.js"></script>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
<style>
    .drop-btn {
        cursor: pointer;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        z-index: 9999;
        background-color: lightgray;
        min-width: 154px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #027AFF;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }
</style>
<script>
    $(function() {
        layui.use(['form', 'layer', 'laydate'], function() {
            var layer = layui.layer,
                form = layui.form,
                laydate = layui.laydate;

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
            cbxCheckAll("check-all", "cb_list");
            checkMorePage("cb_list", page);
        });
    }

    function sortList(obj) {
        //排序逻辑
        myObj = $(obj).parent();
        var sortWay = $(obj).attr("sort-way");
        var sortField = $(obj).parent().attr("sort-field");
        $(obj).parent().attr("lay-sort", sortField);

        $("#sort_way").val(sortWay);
        $("#sort_field").val(sortField);

        loadList('1');
    }

    function cbxCheckAll(cbxAllNm, cbxItemNm) {
        var ids = $("#checkedIds").val();
        $("input[name='" + cbxAllNm + "']").change(function() {
            var cbxAllIsChecked = $(this).attr("checked"); // true or false
            $("input[name='" + cbxItemNm + "']").each(function(i, item) {
                if (cbxAllIsChecked) {
                    $(item).attr("checked", "checked");
                    ids += item.id + ",";
                } else {
                    $(item).removeAttr("checked");
                    ids = ids.replace(item.id + ",", "");
                }
            });
            $("#checkedIds").val(ids);
        });
    }

    function checkMorePage(cbxItemNm, page) {
        var ids = $("#checkedIds").val();
        $("input[name='" + cbxItemNm + "']").change(function() {
            var isChecked = $(this).attr("checked");
            if (isChecked) {
                ids += this.id + ",";
            } else {
                ids = ids.replace(this.id + ",", "");
            }
            $("#checkedIds").val(ids);
        });
    }
</script>

<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="type" name="type" value="project_number_participant" />
        <input type="hidden" id="sort_way" name="sort_way" value="" />
        <input type="hidden" id="sort_field" name="sort_field" value="" />
        <input type="hidden" id="page_size" name="page_size" value="10" />
        <input type="hidden" id="checkedIds" name="checkedIds" value="" />
        <table style="margin: 1%">
            <tr>
                <td align="right">流程单号</td>
                <td>
                    <input type="text" name="form_id" id="form_id" class="layui-input" placeholder="请输入流程单号">
                </td>
                <td align="right">项目代号</td>
                <td>
                    <input type="text" name="project_code"" id=" project_code" class="layui-input" placeholder="请输入项目代号">
                </td>
                <td align="right">项目编号</td>
                <td>
                    <input type="text" name="project_number" id="project_number" class="layui-input" placeholder="请输入项目编号">
                </td>
                <td align="right">客户名称</td>
                <td>
                    <input type="text" name="customer_name" id="customer_name" class="layui-input" placeholder="请输入客户名称">
                </td>
                <td>
                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="loadList('1');return false;" style="margin-left: 10px;">查询</button>
                    <!-- <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="exportData();return false;">导出</button> -->
                </td>
            </tr>
        </table>
    </form>
    <div id="tableList" style="width:100%;text-align:center;">

    </div>
</body>

</html>