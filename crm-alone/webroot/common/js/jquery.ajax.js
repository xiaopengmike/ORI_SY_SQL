(function () {
    jQuery.fn.extend({
        layerPage: function (url, opts, callback) {
            var trCount = $(this).siblings().find("tr").length;
            var obj = $(this).siblings().find("tr")[trCount - 1];
            var col = $(obj).find("th").length;
            if ("col" in opts && opts.col != "" && opts.col != 0) {
                col = opts.col;
            }
            opts = jQuery.extend({
                page: "1",
                perPage: "10",
                limits: [10, 20, 30, 40, 50],
                limitareo: false,
                showlimit: false,
                ispage: true
            }, opts || {});
            if ($("#limitareo").find("input[name='limitareo']").length > 0) {
                opts.perPage = $("#limitareo").find("input[name='limitareo']").val();
            }
            if ($("span[class='layui-laypage-limits']").find("select").length > 0) {
                opts.perPage = $("span[class='layui-laypage-limits']").find("select").val();
            }
            if (opts.limitareo && opts.showlimit) {
                opts.limitareo = false;
                opts.showlimit = true;
            }


            var thisid = $(this).attr('id');
            var layout = ['count', 'first', 'prev', 'page', 'next', 'last', 'skip'];
            if (opts.showlimit) {
                layout = ['count', 'first', 'prev', 'page', 'next', 'last', 'limit', 'skip'];
            }
            $(this).html('<tr class="alt2"><td colspan="' + col + '" align="center"><img src="/static/images/loading.gif" align="absmiddle" />正在装载...</td></tr>');
            $.post(url, opts, function (data) {
                // console.log(data);
                var reJson = null;
                try {
                    reJson = eval('(' + data + ')');
                } catch (e) {
                    layui.use('layer', function () {
                        var layer = layui.layer;
                        layer.alert('服务器异常: ' + e, {
                            icon: 5
                        });
                    });
                    return;
                }

                if (!opts.ispage) {
                    if (reJson.data.length > 0) {
                        $("#pageareo").html("");
                        $("#" + thisid).html($("#" + thisid + "_tmpl").tmpl(reJson.data));
                    } else {
                        if ($("#pageareo").length == 0) {
                            $("#" + thisid).parent().parent().append('<table style="width: 100%;"><tr><td><div id="pageareo" style="width:98%;margin:6px auto 6px auto;"></div></td></tr></table>');
                        }
                        $("#" + thisid).html("");
                        $("#pageareo").css({
                            'text-align': "center"
                        }).text("暂无数据");
                    }
                } else {
                    if ($("#pageareo").length == 0) {
                        $("#" + thisid).parent().parent().append('<table style="width: 100%;"><tr><td><div id="pageareo" style="width:98%;margin:6px auto 6px auto;"></div></td></tr></table>');
                    }
                    if (reJson.count != 0) {
                        $("#" + thisid).html($("#" + thisid + "_tmpl").tmpl(reJson.data));
                        layui.use(['form', 'laypage'], function () {
                            var laypage = layui.laypage;
                            var form = layui.form;
                            laypage.render({
                                elem: 'pageareo',
                                count: reJson.count,
                                first: '首页',
                                last: '尾页',
                                curr: opts.page,
                                limit: opts.perPage,
                                limits: opts.limits,
                                limitareo: opts.limitareo,
                                theme: "mypage",
                                layout: layout,
                                jump: function (obj, first) {
                                    if (!first) {
                                        if (opts.limitareo) {
                                            obj.curr = Math.ceil(obj.count / opts.perPage) < obj.curr ? Math.ceil(obj.count / opts.perPage) : obj.curr;
                                            opts = jQuery.extend(opts, {
                                                page: obj.curr,
                                                perPage: opts.perPage
                                            } || {});
                                        } else {
                                            opts = jQuery.extend(opts, {
                                                page: obj.curr,
                                                perPage: obj.limit
                                            } || {});
                                        }
                                        $("#" + thisid).layerPage(url, opts, callback);
                                    }
                                    form.render();
                                }
                            });
                            if ($("#limitareo").length == 0 && opts.limitareo) {
                                $("#pageareo").append(' <div id="limitareo" style="float:right;"><div style="float:left;margin: 7px;"><span>每页显示</span></div><div style="float:left;width:40px"><input type="text" class="layui-input" name="limitareo" value="' + opts.perPage + '"/></div><div style="float:right;margin: 7px;"><span>条</span></div></div>');
                                $("#limitareo").find("input[name='limitareo']").blur(function () {
                                    opts.perPage = $(this).val();
                                });
                            }
                        });
                    } else {
                        $("#" + thisid).html("");
                        $("#pageareo").css({
                            'text-align': "center"
                        }).text("暂无数据");
                    }
                }

                if (callback != undefined) {
                    return callback(reJson);
                }
            });

        }
    });
})(jQuery);