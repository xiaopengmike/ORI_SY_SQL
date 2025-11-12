// CRM公共JS函数

function loadList(page) {
    $("#tableList").empty();
    $("#tableList").html(
        '<div><img src="/static/images/loading.gif" align="absmiddle" />正在装载...</div>'
    );
    $("#tableList").load("query_page.php?" + $("#tab").serialize() + "&page=" + page, function () {
        initTextarea();
        cbxCheckAll("check-all", "cb_list");
        checkMorePage("cb_list", page);
    });
}

function initTextarea() {
    $.each($("textarea"), function (i, n) {
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
    $("input[name='" + cbxAllNm + "']").change(function () {
        var cbxAllIsChecked = $(this).attr("checked"); // true or false
        $("input[name='" + cbxItemNm + "']").each(function (i, item) {
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
    $("input[name='" + cbxItemNm + "']").change(function () {
        var isChecked = $(this).attr("checked");
        if (isChecked) {
            ids += this.id + ",";
        } else {
            ids = ids.replace(this.id + ",", "");
        }
        $("#checkedIds").val(ids);
    });
}

function importData() {
    // 导入参数构造
    var dataType = "数据导入";
    var templetName = "数据导入模板";
    var formatType = "3"; // 
    var tableName = "table_name";
    var isExtra = 1;
    var paras = "dataType=" + dataType + "&templetName=" + templetName + "&formatType=" + formatType + "&tableName=" + tableName + "&isExtra=" + isExtra;
    var url = "import/pre_import.php?" + paras;

    var height = screen.availHeight * 0.5;
    window.open(url, "", "height=" + height + ",width=700,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left=" + (screen.availWidth - 700) / 2 + ",resizable=yes");
}

function exportData() {
    window.open("query_page.php?" + $("#tab").serialize() + "&export=1");
}

// 查询变更历史数据
function historyPage(formId) {
    layui.use(['form', 'layer'], function () {
        var layer = layui.layer,
            form = layui.form;
        var index = layer.open({
            type: 2,
            area: ['600px', '500px'],
            content: 'history.php?formId=' + formId
        });
        // layui.layer.full(index);
        form.render();
    });
}

function addByProcessSort(sort, sortName, processType, userBu) {
    layui.use(['form', 'layer'], function () {
        var layer = layui.layer,
            form = layui.form;
        if (sort == userBu) {
            // 若新建归属公司流程不弹出提示直接打开页面
            pageOperate('add.php?company=' + sort, 'add');
            return false;
        } else {
            var content = '<div style="padding: 50px;line-height: 22px;font-weight: 360px;text-align:center"><b>';
            content += '您确认新建<font style="color:#FF5722">' + sortName + '</font>' + processType + '吗？';
            content += '</b></div>';
            layer.open({
                type: 1, // 0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                // title: false, //不显示标题栏
                title: '提醒',
                closeBtn: false,
                area: '352px;',
                // shade: 0.8,
                id: 'process_add_id', //设定一个id，防止重复弹出
                btn: ['确认', '取消'],
                btnAlign: 'c',
                // moveType: 1, //拖拽模式，0或者1
                content: content,
                success: function (layero) {
                    var btn = layero.find('.layui-layer-btn');
                    btn.find('.layui-layer-btn0').attr({
                        onclick: "pageOperate('add.php?company=" + sort + "', 'add');return false;",
                        target: '_blank'
                    });
                }
            });
            form.render();
        }
        form.render();
    });
}
//下拉框必填项，增加背景样式，注意 form.render();如果不指定内容，将会造成以下功能失效，select重新渲染
function require_select(){
     //必填增加背景色
     setTimeout(function(){
        $('select[lay-verify="required"]').next().find('.layui-select-title input').css({'background-color':'#f9fbd5'});
    },500);
}