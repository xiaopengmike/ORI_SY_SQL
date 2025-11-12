(function () {
    jQuery.extend({
        // 附件控件初始化
        initAttach: function () {
            layui.use(['form', 'attach'], function () {
                var form = layui.form,
                    attach = layui.attach;
                // 同页面多附件控件使用，默认控件ID与附件type保持一致
                $(".btn-upload").each(function () {
                    attach.init({
                        elem: "#" + this.id,
                        uploadUrl: "/general/attachment/attach.php?type=" + this.id + "&url=common"
                    });
                });
                form.render();
            });
        },
        initAttachList: function (attachObj) {
            layui.use(['form', 'attach'], function () {
                var form = layui.form,
                    attach = layui.attach;
                $(".btn-upload").each(function () {
                    var id = this.id;
                    if (ifEmpty(attachObj[id])) attachObj[id] = {};
                    attach.attachList({
                        elem: "#" + id,
                        uploadUrl: "/general/attachment/attach.php?type=" + id + "&url=common",
                        attachObj: attachObj[id]
                    });
                });
            });
        },
        // 日期控件初始化
        initDate: function () {
            layui.use(['form', 'laydate'], function () {
                var form = layui.form,
                    laydate = layui.laydate;
                $(".date").each(function () {
                    laydate.render({
                        elem: this,
                        theme: '#027AFF',
                        range: false
                    });
                });
                form.render();
            });
        },
        // 富文本框格式初始化
        initTextarea: function () {
            $.each($("textarea"), function (i, n) {
                $(n).css("width", "100%");
                $(n).css("height", "auto");
                $(n).css("margin", "auto");
                $(n).css("resize", "none");
                $(n).css("overflow", "hidden");
                makeExpandingArea(document.getElementById(n.id));
            });
        },
        // 获取下一步审核人
        getNextApprover: function (formId) {
            var step = $('#step').val();
            var user_bu = $('#user_bu').val();
            var type = $('#type').val();
            layer.open({
                title: "",
                type: 2,
                area: ['600px', '600px'],
                content: "next_approver.php?step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&type=" + type,
                success: function (index,layero) {
                    setTimeout(function () {
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    }, 500);
                },
                cancel: function (index,layero) {
                     if(layero[0].baseURI.indexOf('/add.php')<0&&layero[0].baseURI.indexOf('/change.php')<0){
                        $('[name="btn_submit"]').removeClass('layui-btn-disabled');
                        $('[name="btn_submit"]').removeAttr('disabled');
                        $('[name="btn_save"]').removeClass('layui-btn-disabled');
                        $('[name="btn_save"]').removeAttr('disabled');
                    }else{
                        layer.alert('数据已保存！若未选择下一步待办人员可返回列表修改数据',{ btn:['返回列表']},function(){
                            parent.layer.close(index);
                            parent.closeIframe();
                            window.opener.location.reload();
                        });
                        
                    }
                }
            });
        },
        // 表单提交
        formSubmit: function () {
            layui.use(['form', 'layer'], function () {
                var layer = layui.layer,
                    router = layui.router(),
                    form = layui.form;
                    console.log(window.location.href.indexOf('/add.php'));
                form.on('submit(submit)', function (data) {
                    var btnVal = data.elem.value;
                    var actionType = $(data.elem).attr("actionType");
                    var url = "action.php?action=" + actionType;
                    // 单击之后提交按钮不可选,防止重复提交
                    $('[name="btn_submit"]').addClass('layui-btn-disabled');
                    $('[name="btn_submit"]').attr('disabled', 'disabled');
                    $('[name="btn_save"]').addClass('layui-btn-disabled');
                    $('[name="btn_save"]').attr('disabled', 'disabled');
                    $.post(url, $("#mainForm").serializeArray(), function (data) {
                        var reJson = null;
                        try {
                            reJson = eval('(' + data + ')');
                        } catch (e) {
                            layer.alert('服务器异常: ' + e, {
                                icon: 5
                            });
                            $('[name="btn_submit"]').removeClass('layui-btn-disabled');
                            $('[name="btn_submit"]').removeAttr('disabled');
                            $('[name="btn_save"]').removeClass('layui-btn-disabled');
                            $('[name="btn_save"]').removeAttr('disabled');
                            return;
                        }
                        if (reJson.code == "200") {
                            // 返回form_id值
                            $("#form_id").val(reJson.data);
                            if (actionType == 'Submit' || actionType == 'Change') {
                                if(window.location.href.indexOf('/add.php')>0||window.location.href.indexOf('/change.php')>0){
                                    layer.confirm('数据已保存！请选择下一步待办人员或者返回列表修改数据',{ btn:['下一步','返回列表']},function(){
                                        $.getNextApprover(reJson.data);
                                    },function(index, layero){
                                        parent.layer.close(index);
                                        parent.closeIframe();
                                        window.opener.location.reload();
                                    });
                                }else{
                                    $.getNextApprover(reJson.data);
                                }
                            } else {
                                layer.alert(btnVal + "成功!", {
                                    icon: 1
                                }, function (index, layero) {
                                    parent.layer.close(index);
                                    parent.closeIframe();
                                    window.opener.location.reload();
                                });
                            }
                        } else {
                            fail_msg=reJson.msg?reJson.msg:btnVal + '失败！';
                            layer.alert(fail_msg, {
                                icon: 5
                            });
                            $('[name="btn_submit"]').removeClass('layui-btn-disabled');
                            $('[name="btn_submit"]').removeAttr('disabled');
                            $('[name="btn_save"]').removeClass('layui-btn-disabled');
                            $('[name="btn_save"]').removeAttr('disabled');
                        }
                    });
                });
            });
        },
        // 系统用户选择 - getCustomerSelect
        userListSelect: function (obj) {
            layui.use(['form', 'layer'], function () {
                var layer = layui.layer,
                    form = layui.form;
                layer.open({
                    title: '人员列表选择',
                    type: 2,
                    skin: 'layui-layer-rim',
                    area: ['75%', '80%'],
                    fixed: false,
                    maxmin: true,
                    offset: '50px',
                    content: 'select_list.php?domId=' + obj.id,
                    moveOut: true
                });
                form.render();
            });
        },
        projectStarSelect: function () {
            layui.use(['form', 'layer'], function () {
                var layer = layui.layer,
                    form = layui.form;
                form.on('select(project_level)', function (data) {
                    var starCount = data.value;
                    var html = "";
                    var starShow = "";
                    if (parseInt(starCount) < 5) {
                        for (var i = 0; i < parseInt(starCount) + 1; i++) {
                            starShow += "★";
                        }
                    }
                    html = '<font style="color: red;font-size:20px">' + starShow + '</font>';
                    $("#star_icon").html(html);
                    form.render();
                });
            });
        },
        clearNotNum: function (obj) {
            obj.value = obj.value.replace(/[^\d.]/g, ""); //清除"数字"和"."以外的字符
            obj.value = obj.value.replace(/^\./g, ""); //验证第一个字符是数字而不是
            obj.value = obj.value.replace(/\.{2,}/g, "."); //只保留第一个. 清除多余的
            obj.value = obj.value.replace(".", "$#$").replace(/\./g, "").replace("$#$", ".");
            obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d\d\d).*$/, '$1$2.$3'); //只能输入两个小数
        },
        closeIframe: function () {
            layui.use(['form'], function () {
                var layer = layui.layer,
                    form = layui.form;
                window.close();
                parent.layer.closeAll();
                form.render();
            });
        }
    });
})(jQuery);