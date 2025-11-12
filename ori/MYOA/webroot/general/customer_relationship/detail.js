(function () {
    jQuery.extend({
        // 发起变更申请
        initPage: function (operate) {
            $('#mainForm').find('input,textarea,select,td[class="TableData"]').not('[actionType="Approve"],[actionType="Back"],[actionType="Close"],[type="button"],[type="radio"]').attr('style', 'color:#0066cc');
            if (operate == 'detail') {
                $('#mainForm').find('input,textarea,select,td[class="TableData"]').not('[actionType="Approve"],[actionType="Back"],[actionType="Close"]').prop('disabled', true);
                 // layui单选选中不置灰
                 $('#mainForm').find('input[type=radio]:checked').prop('disabled', false);
                 $('#mainForm').find('.not_disabled').prop('disabled', false);//设置需要使用的组件样式，不能设置disabled
                // layui下拉只读显示不置灰
                $.each($("select"), function (i, n) {
                    var optText = $(n).find("option:selected").text();
                    $(n).next().find("input").val(optText);
                    $(n).remove();
                    $('dl').remove();
                });
            } else if (operate == 'approve') {
                $('#mainForm').find('input,textarea').not('[actionType="Approve"],[actionType="Back"],[actionType="Close"],[type="radio"],[id="approve_opinion"],[id="opinion_extra"],[type="hidden"]').prop('disabled', true);
                $('.not_disabled').prop('disabled', false);
            }
            // textarea初始化样式
            $.each($("textarea"), function (i, n) {
                // 解决历史数据空格和换行导致的显示问题；会导致换行失败，暂不处理
                // var text = '';
                // text = n.value.replace(/\r\n/g, "<br>").replace(/\n/g, "").replace(/\s/g, "").replace(/<br>/g, '\r\n');
                // // text = n.value.replace(/\r\n/g, "<br>").replace(/\n/g, "<br>").replace(/\s/g, "").replace(/<br>/g, '\r\n');
                // $("#" + n.id).val(text);
                $(n).css("width", "100%");
                $(n).css("height", n.scrollHeight + "px");
                $(n).css("margin", "auto");
                $(n).css("resize", "none");
                $(n).css("overflow", "hidden");
                $(n).css("readonly", "readonly");
                makeExpandingArea(document.getElementById(n.id));
            });
        },
        getNextApprover: function (formId, actionType) {
            var step = $('#step').val();
            var user_bu = $('#user_bu').val();
            var type = $('#type').val();
            layer.open({
                title: "",
                type: 2,
                area: ['600px', '80%'],
                content: "next_approver.php?step=" + step + "&user_bu=" + user_bu + "&form_id=" + formId + "&actionType=" + actionType + "&type=" + type,
                success: function (layero, index) {
                    setTimeout(function () {
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    }, 500);
                }
            });
        },
        // 验证layui自定义checked必填，主要用于同意不同意使用
        verifyChecked: function () {
            layui.use(['form', 'layer'], function () {
                var layer = layui.layer,
                    form = layui.form;
                var msg = '同意与否不能为空！';
                //自定义验证规则,定义radio和checkbox必填
                form.verify({
                    otherReq: function (value, item) {
                        var $ = layui.$;
                        var verifyName = $(item).attr('name'),
                            verifyType = $(item).attr('type'),
                            formElem = $(item).parents('.layui-form'), //获取当前所在的form元素，如果存在的话
                            verifyElem = formElem.find('input[name=' + verifyName + ']'), //获取需要校验的元素
                            isTrue = verifyElem.is(':checked'), //是否命中校验
                            focusElem = verifyElem.next().find('i.layui-icon'); //焦点元素
                        if (!isTrue || !value) {
                            //定位焦点
                            focusElem.css(verifyType == 'radio' ? {
                                "color": "#FF5722"
                            } : {
                                "border-color": "#FF5722"
                            });
                            //对非输入框设置焦点
                            focusElem.first().attr("tabIndex", "1").css("outline", "0").blur(function () {
                                focusElem.css(verifyType == 'radio' ? {
                                    "color": ""
                                } : {
                                    "border-color": ""
                                });
                            }).focus();
                            return msg;
                        }
                    },
                    opinionRequired: function (value, item) {
                        var verifyMsg = $(item).attr('verifyMsg');
                        if (!value) {
                            return verifyMsg?verifyMsg:"必填项不能为空！";
                        }
                    }
                });
            });
        },
        // 审核操作：提交或退回
        formSubmit: function () {
            layui.use(['form', 'layer'], function () {
                var layer = layui.layer,
                    form = layui.form;
                form.on('submit(submit)', function (data) {
                    var btnVal = data.elem.value;
                    var actionType = $(data.elem).attr("actionType");
                    var url = "action.php?action=" + actionType;
                    var formId = $("#form_id").val();
                    $.post(url, $("#mainForm").serializeArray(), function (data) {
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
                            getNextApprover(formId, actionType);
                            return false;
                        } else {
                            layer.alert(btnVal + '失败！', {
                                icon: 5
                            });
                        }
                    });
                });
            });
        },
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
                    if (!ifEmpty(attachObj[id])&&this.className.indexOf('not_disabled')==-1) {
                        attach.attachReadonly({
                            elem: "#" + this.id,
                            uploadUrl: "/general/attachment/attach.php?type=" + this.id + "&url=common",
                            attachObj: attachObj[id]
                        });
                    }
                    if (this.className.indexOf('not_disabled')!=-1) {
                        if (ifEmpty(attachObj[id])) attachObj[id] = {};
                        attach.attachList({
                            elem: "#" + this.id,
                            uploadUrl: "/general/attachment/attach.php?type=" + this.id + "&url=common",
                            attachObj: attachObj[id]
                        });
                    }
                });
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
                    // $("#star_icon").html(html);
                    $(data.elem).parent().parent().parent().find('[data-type="star_icon"]').html(html);
                    form.render();
                });
            });
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
        // 打印PDF
        printDetail: function () {
            $('.noprint').hide();
            bdhtml = window.document.body.innerHTML; //获取当前页的html代码  
            sprnstr = "<!--startprint-->"; //设置打印开始区域  
            eprnstr = "<!--endprint-->"; //设置打印结束区域  
            prnhtml = bdhtml.substring(bdhtml.indexOf(sprnstr) + 18); //从开始代码向后取html  
            prnhtml = prnhtml.substring(0, prnhtml.indexOf(eprnstr)); //从结束代码向前取html 
            window.document.body.innerHTML = prnhtml;
            //去掉页眉页脚
            if (!!window.ActiveXObject || "ActiveXObject" in window) { //是否ie
                remove_ie_header_and_footer();
            }
            window.print();
            // 完成操作后刷新当前详情页面
            window.location.href = window.location.href;
        }
    });
})(jQuery);