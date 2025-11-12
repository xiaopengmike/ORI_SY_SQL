$(function() {
            layui.extend({
                attach: '<?= COMMON_FILE_URL ?>/js/extends/attach'
            }).use(['form', 'layer', 'laydate', 'attach', 'table'], function() {
                var layer = layui.layer,
                    form = layui.form,
                    attach = layui.attach,
                    laydate = layui.laydate,
                    table = layui.table;

                $(".date").each(function() {
                    laydate.render({
                        elem: this,
                        theme: '#027AFF',
                        range: false
                    });
                });

                init();
				
                // 同页面多附件控件使用，默认控件ID与附件type保持一致
                $(".btn-upload").each(function() {
                    attach.init({
                        elem: "#"+this.id,
                        uploadUrl: "/general/attachment/attach.php?type="+this.id+"&url=common"
                    });
                });

                form.on('submit(submit)', function(data) {
                    var btnVal = data.elem.value;
                    var actionType = $(data.elem).attr("actionType");
                    var url = "../action.php?action=" + actionType;
                    $.post(url, $("#addForm").serializeArray(), function(data) {
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
                            layer.alert(btnVal + "成功!", {
                                icon: 1
                            }, function(index, layero) {
                                var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                                parent.layui.table.reload('testReload');
                                parent.layer.close(index); //再执行关闭
                            });
                        } else {
                            layer.alert(btnVal + '失败！', {
                                icon: 5
                            });
                        }
                    });
                    form.render();
                });

                $('[data-type="customer_name"]').each(function() {
                    this.onfocus = function() {
                        
                        // 获取客户数据
                        // getCustomerSelect(this);
                    }
                    form.render();
                });

                form.on('select(user_name)', function(data) {
                    $("#real_name").val(data.elem[data.elem.selectedIndex].text);

                    form.render();
                });
				
				form.on('select(project_level)', function(data) {
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

                form.render();
            });
        });
		
		// 页面初始化加载
        function init() {
            // textarea初始化样式
            $.each($("textarea"), function(i, n) {
                $(n).css("width", "100%");
                $(n).css("min-height", "60px");
                $(n).css("margin", "auto");
                $(n).css("resize", "none");
                $(n).css("overflow", "hidden");

                // textarea输入后自适应高度设置
                makeExpandingArea(document.getElementById(n.name));
            });
        }
		
		
		function selectUserByDeptId(deptId, number, defaultVal) {
            layui.use(['form', 'layer'], function() {
                var layer = layui.layer,
                    form = layui.form;
                if (deptId != '') {
                    $.post('../user_info.php?deptId=' + deptId, {}, function(data) {
                        var obj = eval('(' + data + ')');

                        $("#user_name" + number).empty();
                        $("#user_name" + number).append("<option value=''>请选择</option>");
                        var selected = "";
                        for (i = 0; i < obj.length; i++) {
                            if (defaultVal == obj[i].user_id) {
                                selected = " selected ";
                            }
                            $("#user_name" + number).append("<option value='" + obj[i].user_id + "' " + selected + ">" + obj[i].user_name + "</option>");
                        }
                    });
                }
                form.render();
            });
        }
		
		form.on('select(user_name)', function(data) {
                    $("#real_name").val(data.elem[data.elem.selectedIndex].text);

                    form.render();
                });