(function () {
    // send find start make 
    jQuery.extend({
        // 发起变更申请
        startChangeForm: function (type) {
            if ($("input[name='cb_list']:checked").length === 0) {
                var msg=$("#LANG").val()=="EN"?"Please select the record you want to change!":"请选择您要变更的记录！";
                alert(msg);
                return false;
            }
            if ($("input[name='cb_list']:checked").length > 1) {
                var msg=$("#LANG").val()=="EN"?"Only 1 record can be selected for change!":"只能选择1个记录进行变更！";
                alert(msg);
                return false;
            }
            var checkBol = false;
            var ids = "";
            $("input[name='cb_list']:checked").each(function (i, item) {
                //状态检查，只有完成审核流程的记录可以变更
                var status = item.title;
                if (status != "Y" && status != "R" && status != "E") {
                    checkBol = true;
                    return;
                }
                ids += ($("input[name='cb_list']:checked").length - 1) === i ? $(item).attr("id") : $(item).attr("id") + ",";
            });
            if (checkBol) {
                var msg=$("#LANG").val()=="EN"?"The record you selected has not completed the approval process, please reselect!":"您选择的记录未完成审核流程，请重新选择！";
                alert(msg);
                return false;
            }
            var url = "change.php?id=" + ids;
            // if(type=='trial_production_order'){
            //     url = "trial_change.php?id=" + ids;
            // }
            // if(type=='rd_production_order'){
            //     url = "rd_change.php?id=" + ids;
            // }
            pageOperate(url, 'add');
            return false;
        },
        // 关闭弹窗
        closeIframe: function () {
            layui.use(['form'], function () {
                var layer = layui.layer,
                    form = layui.form;
                window.close();
                parent.layer.closeAll();
                form.render();
            });
        },
        // 列表数据加载
        loadList: function (page, url) {
            var type = $("#type").val();
            $("#tableList").empty();
            $("#tableList").html(
                '<div><img src="/static/images/loading.gif" align="absmiddle" />正在装载...</div>'
            );
            if(ifEmpty(url)) url = "query_page.php";
            $("#tableList").load(url + "?" + $("#tab").serialize() + "&page=" + page + "&type=" + type, function () {
                // $.cbxCheckAll("check-all", "cb_list");  // 列表全选
                // $.checkMorePage("cb_list", page);  // 跨页面多选
            });
        },
        // 列表全选
        cbxCheckAll: function (cbxAllNm, cbxItemNm) {
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
        },
        // 实现跨页面多选
        checkMorePage: function (cbxItemNm, page) {
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
        },
        // 实现列排序
        sortList: function (obj) {
            //排序逻辑
            myObj = $(obj).parent();
            var sortWay = $(obj).attr("sort-way");
            var sortField = $(obj).parent().attr("sort-field");
            $(obj).parent().attr("lay-sort", sortField);
            $("#sort_way").val(sortWay);
            $("#sort_field").val(sortField);
            $.loadList('1');
        },
        // 查看变更历史记录
        historyPage: function (formId) {
            layui.use(['form', 'layer'], function () {
                var layer = layui.layer,
                    form = layui.form;
                var index = layer.open({
                    type: 2,
                    area: ['600px', '500px'],
                    content: 'history.php?formId=' + formId
                });
                // layui.layer.full(index);  // 全屏页面
                form.render();
            });
        },
        // 实现跨公司发起流程
        addByProcessSort: function (sort, sortName, processType, userBu, url) {
            if (ifEmpty(url)) url = 'add.php';
            layui.use(['form', 'layer'], function () {
                var layer = layui.layer,
                    form = layui.form;
                if (sort == userBu) {
                    // 若新建归属公司流程不弹出提示直接打开页面
                    pageOperate(url + '?company=' + sort, 'add');
                    return false;
                } else {
                    var content = '<div style="padding: 50px;line-height: 22px;font-weight: 360px;text-align:center"><b>';
                    content += '您确认新建<font style="color:#FF5722">' + sortName + '</font>' + processType + '吗？';
                    content += '</b></div>';
                    layer.open({
                        type: 1, // 0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        title: '提醒',
                        closeBtn: false,
                        area: '352px;',
                        id: 'process_add_id', //设定一个id，防止重复弹出
                        btn: ['确认', '取消'],
                        btnAlign: 'c',
                        content: content,
                        success: function (layero) {
                            var btn = layero.find('.layui-layer-btn');
                            btn.find('.layui-layer-btn0').attr({
                                onclick: "pageOperate('" + url + "?company=" + sort + "', 'add');return false;",
                                target: '_blank'
                            });
                        }
                    });
                    form.render();
                }
                form.render();
            });
        },
        // 选择流程发起流程
        selectAddByProcessSort: function (sort, sortName, processType, userBu, url) {
            if ($("input[name='cb_list']:checked").length === 0) {
                alert("请选择您要新建的合同记录！");
                return false;
            }
            if ($("input[name='cb_list']:checked").length > 1) {
                alert("只能选择1个记录进行新建！");
                return false;
            }
            var ids = "";
            $("input[name='cb_list']:checked").each(function (i, item) {
                ids += ($("input[name='cb_list']:checked").length - 1) === i ? $(item).attr("id") : $(item).attr("id") + ",";
            });
            if (ifEmpty(url)) url = 'selectAdd.php';
            layui.use(['form', 'layer'], function () {
                var layer = layui.layer,
                    form = layui.form;
                if (sort == userBu) {
                    // 若新建归属公司流程不弹出提示直接打开页面
                    pageOperate(url + '?company=' + sort+ "&id="+ids, 'add');
                    return false;
                } else {
                    var content = '<div style="padding: 50px;line-height: 22px;font-weight: 360px;text-align:center"><b>';
                    content += '您确认新建<font style="color:#FF5722">' + sortName + '</font>' + processType + '吗？';
                    content += '</b></div>';
                    layer.open({
                        type: 1, // 0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        title: '提醒',
                        closeBtn: false,
                        area: '352px;',
                        id: 'process_add_id', //设定一个id，防止重复弹出
                        btn: ['确认', '取消'],
                        btnAlign: 'c',
                        content: content,
                        success: function (layero) {
                            var btn = layero.find('.layui-layer-btn');
                            btn.find('.layui-layer-btn0').attr({
                                onclick: "pageOperate('" + url + "?company=" + sort + "&id="+ids+"', 'add');return false;",
                                target: '_blank'
                            });
                        }
                    });
                    form.render();
                }
                form.render();
            });
        },
        importData: function () {
            // 导入参数构造
            var dataType = "数据导入";
            var templetName = "名词翻译数据导入表";
            var formatType = "3";
            var tableName = "inhe_control_library";
            var isExtra = 1;
            var paras = "dataType=" + dataType + "&templetName=" + templetName + "&formatType=" + formatType + "&tableName=" + tableName + "&isExtra=" + isExtra;
            var url = "import/pre_import.php?" + paras;
            var height = screen.availHeight * 0.5;
            window.open(url, "", "height=" + height + ",width=700,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left=" + (screen.availWidth - 700) / 2 + ",resizable=yes");
        },
        exportData: function () {
            window.open("query_page.php?" + $("#tab").serialize() + "&export=1");
        }
    });
})(jQuery);