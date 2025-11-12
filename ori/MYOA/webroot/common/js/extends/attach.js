layui.define(["upload", "layer"], function (exports) {
    "use strict";

    var layer = layui.layer,
        upload = layui.upload,
        attach = {
            ModuleName: "\u9644\u4ef6",
            opt: {
                elem: ".btn-upload",
                uploadUrl: "",
                exts: "",
                field: "myfile",
                attachObj: {},
                before: function () {},
                after: function () {},
                error: function () {},
            },
            init: function ($data) {
                var options = $.extend(this.opt, $data);
                if (options.after != null && typeof options.after !== 'function') {
                    console.error("options error");
                    return;
                }
                upload.render({
                    elem: options.elem,
                    url: options.uploadUrl,
                    accept: 'file',
                    field: options.field,
                    before: function () {
                        layer.msg('\u6587\u4ef6\u4e0a\u4f20\u4e2d...', {
                            icon: 16,
                            // shade: 0.01,
                            shade: [0.8, '#393D49'], // 遮罩
                            // time: 1000  // 自动关闭
                        });
                    },
                    done: function (res, index, upload) {
                        //关闭上传等待动画
                        layer.close(layer.msg());
                        options.elem = this.elem.selector; // 更新：取选择器ID
                        if (res.type) {
                            var attachHtml = '<div id="div_' + res.id + '" style="background-color: beige;padding: 5px 0 5px 0;">' +
                                '<i class="layui-icon" style="font-size: 25px; color: #1E9FFF;">&#xe61e;</i>' +
                                '<a style="color:#01AAED" href="/general/attachment/attach.php?action=download&id=' + res.id + '" >' + res.name + '</a>' +
                                '<a class="layui-btn layui-btn-sm layui-btn-normal" id="a_' + res.id + '"  style="margin-left: 10px;z-index:10000" href="javascript:void(0);"><i class="layui-icon" style="font-size: 30px; color: rgb(255, 255, 255);">&#xe640;</i> </a>' +
                                '<input type="text" name="attach[]" value="' + res.id + '" class="layui-hide">' +
                                '</div>';
                            if ($(options.elem).parent().parent().find(".layui-upload-wrap").length > 0) {
                                ;
                                $(options.elem).parent().parent().append(attachHtml);
                            } else {
                                $(options.elem).parent().append(attachHtml);
                            }
                            $("#a_" + res.id).on("click", function () {
                                $.get("/general/attachment/attach.php?action=delete&id=" + res.id, function (redata) {
                                    $("#div_" + res.id).remove();
                                });
                            });
                            options.after();
							if(res.name.indexOf('?')>=0){
								layer.alert(res.name+'\u65e0\u6cd5\u8bc6\u522b\u7279\u6b8a\u5b57\u7b26\u002c\u8bf7\u91cd\u65b0\u4fee\u6539\u6587\u4ef6\u540d\u4e0a\u4f20\u002c\u4e0d\u7136\u65e0\u6cd5\u67e5\u8be2\u548c\u4e0b\u8f7d\u8be5\u6587\u4ef6\uff01', {
									icon: 5
								});
							}
                        } else {
                            layer.alert(res.msg, {
                                icon: 5
                            });
                        }
                    }
                });

                return;
            },
            attachList: function ($data) {
                var options = $.extend(this.opt, $data);
                if (options.after != null && typeof options.after !== 'function') {
                    console.error("options error");
                    return;
                }
                // var tempAttachJson = eval('(' + options.attachObj + ')');
                var tempAttachJson = options.attachObj;

                for (var i = 0; i < tempAttachJson.length; i++) {
                    var attachHtml = '<div id="div_' + tempAttachJson[i].id + '" style="background-color: beige;padding: 5px 0 5px 0;">' +
                        '<i class="layui-icon" style="font-size: 25px; color: #1E9FFF;">&#xe61e;</i>' +
                        '<a style="color:#01AAED" href="/general/attachment/attach.php?action=download&id=' + tempAttachJson[i].id + '" >' + tempAttachJson[i].name + '</a>' +
                        '<a class="layui-btn layui-btn-sm layui-btn-normal" id="a_' + tempAttachJson[i].id + '" info="' + tempAttachJson[i].id + '" style="margin-left: 10px;z-index:10000" href="javascript:void(0);"><i class="layui-icon" style="font-size: 30px; color: rgb(255, 255, 255);">&#xe640;</i> </a>' +
                        '<input type="text" name="attach[]" value="' + tempAttachJson[i].id + '" class="layui-hide">' +
                        '</div>';
                    if ($(options.elem).parent().parent().find(".layui-upload-wrap").length > 0) {
                        $(options.elem).parent().parent().append(attachHtml);
                    } else {
                        $(options.elem).parent().append(attachHtml);
                    }
                   // console.log($("#a_" + tempAttachJson[i].id));return false;
                    $("#a_" + tempAttachJson[i].id).on("click", function () {
                        var tempId = $(this).attr("info");
                        $.get("/general/attachment/attach.php?action=delete&id=" + tempId, function (redata) {
                            $("#div_" + tempId).remove();
                        });
                    });
                }
                upload.render({
                    elem: options.elem,
                    url: options.uploadUrl,
                    accept: 'file',
                    field: options.field,
                    before: function () {
                        layer.msg('\u6587\u4ef6\u4e0a\u4f20\u4e2d...', {
                            icon: 16,
                            shade: [0.8, '#393D49'], // 遮罩
                        });
                    },
                    done: function (res, index, upload) {
                        //关闭上传等待动画
                        layer.close(layer.msg());
                        options.elem = this.elem.selector; // 更新：取选择器ID
                        if (res.type) {
                            var attachHtml = '<div id="div_' + res.id + '" style="background-color: beige;padding: 5px 0 5px 0;">' +
                                '<i class="layui-icon" style="font-size: 25px; color: #1E9FFF;">&#xe61e;</i>' +
                                '<a style="color:#01AAED" href="/general/attachment/attach.php?action=download&id=' + res.id + '" >' + res.name + '</a>' +
                                '<a class="layui-btn layui-btn-sm layui-btn-normal" id="a_' + res.id + '"  style="margin-left: 10px;z-index:10000" href="javascript:void(0);"><i class="layui-icon" style="font-size: 30px; color: rgb(255, 255, 255);">&#xe640;</i> </a>' +
                                '<input type="text" name="attach[]" value="' + res.id + '" class="layui-hide">' +
                                '</div>';
                            if ($(options.elem).parent().parent().find(".layui-upload-wrap").length > 0) {
                                ;
                                $(options.elem).parent().parent().append(attachHtml);
                            } else {
                                $(options.elem).parent().append(attachHtml);
                            }
                            $("#a_" + res.id).on("click", function () {
                                $.get("/general/attachment/attach.php?action=delete&id=" + res.id, function (redata) {
                                    $("#div_" + res.id).remove();
                                });
                            });
                            options.after();
							if(res.name.indexOf('?')>=0){
								layer.alert(res.name+'\u65e0\u6cd5\u8bc6\u522b\u7279\u6b8a\u5b57\u7b26\u002c\u8bf7\u91cd\u65b0\u4fee\u6539\u6587\u4ef6\u540d\u4e0a\u4f20\u002c\u4e0d\u7136\u65e0\u6cd5\u67e5\u8be2\u548c\u4e0b\u8f7d\u8be5\u6587\u4ef6\uff01', {
									icon: 5
								});
							}
                        } else {
                            layer.alert(res.msg, {
                                icon: 5
                            });
                        }
                    }
                });

                return;
            },
            attachReadonly: function ($data) {
                var options = $.extend(this.opt, $data);
                if (options.after != null && typeof options.after !== 'function') {
                    console.error("options error");
                    return;
                }
                // var tempAttachJson = eval('(' + options.attachObj + ')');
                var tempAttachJson = options.attachObj;

                for (var i = 0; i < tempAttachJson.length; i++) {
                    
                    var filePath = tempAttachJson[i].filename;
                    
                    var index= filePath.lastIndexOf(".");
                    
                    var ext = filePath.substr(index+1);
                    var see_url=ext=='xls'||ext=='doc'||ext=='xlsx'||ext=='docx'?'/module/quick_preview/index_crm.php?id='+tempAttachJson[i].id:tempAttachJson[i].path.substr(15);
                   
                    var attachHtml = '<div id="div_' + tempAttachJson[i].id + '" style="background-color: beige;padding: 5px 0 5px 0;">' +
                        '<i class="layui-icon" style="font-size: 25px; color: #1E9FFF;">&#xe61e;</i>' +
                        '<a onmouseover="showMenu(this.id);" id="' + tempAttachJson[i].id + '" style="color:#01AAED" href="/general/attachment/attach.php?action=download&id=' + tempAttachJson[i].id + '" >' + tempAttachJson[i].name + '</a>' +
                        '<input type="text" name="attach[]" value="' + tempAttachJson[i].id + '" class="layui-hide">' +
                        '</div>';
                        attachHtml +='<div id="' + tempAttachJson[i].id + '_menu" class="attach_div" title="' + tempAttachJson[i].filename + '" style="position: absolute; z-index: 50; display: none; clip: rect(auto, auto, auto, auto); left: 354px; top: 1309px;"><a href="/general/attachment/attach.php?action=download&id=' + tempAttachJson[i].id + '">\u4e0b\u8f7d</a>'+
                        '<a target="_bank" href="' + see_url + '">\u67e5\u770b</a>'+
                        '</div>';
                    if ($(options.elem).parent().parent().find(".layui-upload-wrap").length > 0) {
                        $(options.elem).parent().parent().append(attachHtml);
                    } else {
                        $(options.elem).parent().append(attachHtml);
                    }
                }

                return;
            }
        };
    exports('attach', attach);
});