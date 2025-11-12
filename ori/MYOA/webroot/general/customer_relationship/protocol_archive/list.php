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
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">

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
            initTextarea();
            cbxCheckAll("check-all", "cb_list");
            checkMorePage("cb_list", page);
        });
    }

    function initTextarea() {
        $.each($("textarea"), function(i, n) {
            $(n).css("height", n.scrollHeight + "px");
            $(n).css("width", "100%");
            $(n).css("margin", "auto");
            $(n).css("resize", "none");
            $(n).css("overflow", "hidden");
            // $(n).css("border", "0px");
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

    function exportData() {
        window.open("query_page.php?" + $("#tab").serialize() + "&export=1");
    }

    function batchUpdate() {
        // if ($("input[name='cb_list']:checked").length === 0) {
        //     alert("请选择您要更新的名词！");
        //     return false;
        // }
        // var checkBol = false;
        // var ids = "";
        // $("input[name='cb_list']:checked").each(function(i, item) {
        //     //状态检查
        //     // var status = $(item).attr("status");
        //     // 未提交和已退回的单可删除
        //     // if (status != "0" && status != "4") {
        //     //     checkBol = true;
        //     //     return;
        //     // }
        //     ids += ($("input[name='cb_list']:checked").length - 1) === i ? $(item).attr("id") : $(item).attr("id") + ",";
        // });

        var ids = $("#checkedIds").val();
        if (ifEmpty(ids)) {
            alert("请选择您要更新的名词！");
            return false;
        }

        // if (checkBol) {
        //     alert("您选择的记录中包含不可删除的记录，请重新选择！");
        //     return false;
        // }
        var url = "batch_update.php?ids=" + ids;
        // location = url;
        pageOperate(url, 'popupBox');
        return false;
    }
</script>

<body class="bodyColorNew">
    <form id="tab" class="layui-form">
        <input type="hidden" id="sort_way" name="sort_way" value="" />
        <input type="hidden" id="sort_field" name="sort_field" value="" />
        <input type="hidden" id="page_size" name="page_size" value="10" />
        <input type="hidden" id="checkedIds" name="checkedIds" value="" />
        <table style="margin: 1%">
            <tr>
                <td nowrap align="right">流程单号</td>
                <td>
                    <input type="text" name="form_id" id="form_id" class="layui-input" placeholder="请输入登记单号">
                </td>
                <td nowrap align="right">项目代号</td>
                <td>
                    <input type="text" name="name" id="name" class="layui-input" placeholder="请输入客户名称">
                </td>
                <td nowrap align="right">制单人</td>
                <td>
                    <input type="text" name="create_user_name" id="create_user_name" class="layui-input" placeholder="请输入制单人">
                </td>
                <td nowrap align="right">制单时间</td>
                <td>
                    <input type="text" name="create_time" id="create_time" class="layui-input date" placeholder="请选择制单时间">
                </td>
                <td nowrap align="right">状态：</td>
                <td>
                    <div class="layui-input-inline">
                        <select id="status" name="status">
                            <option value=''>请选择</option>
                            <option value='T'>待提交</option>
                            <option value='P'>审核中</option>
                            <option value='Y'>已审核</option>
                            <option value='N'>退回</option>
                        </select>
                    </div>
                </td>
                <td>
                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="loadList('1');return false;" style="margin-left: 10px;">查询</button>
                    <button class="layui-btn layui-btn-oa layui-btn-sm" onclick="exportData();return false;">导出</button>
                </td>
            </tr>
        </table>
    </form>
    <div id="tableList" style="width:100%;text-align:center;">

    </div>
</body>

</html>