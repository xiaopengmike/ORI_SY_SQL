function removeLine(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
}

function addLine(domId) {
    var html = "";
    html = createLine(domId);
    $("#" + domId).before(html);
}

var contactNum = 0;
var companyNum = 0;

function createLine(data) {
    console.log(data);
    var html = '';
    html += '<tr>';
    html += '<td  class="TableData">' + data.customer_name + '</td>';
    html += '<td  class="TableData">' + data.short_name + '</td>';
    html += '</tr>';
    return html;
}

// 发起客户信息变更
function shareCustomer(id) {
    if(id == "" || id == null || id == undefined || id == "NaN"){
        if ($("input[name='cb_list']:checked").length === 0) {
            var msg=$("#LANG").val()=="EN"?"Please select the customer you want to share!":"请选择您要共享的客户！";
            alert(msg);
            return false;
        }
        if ($("input[name='cb_list']:checked").length > 1) {
            var msg=$("#LANG").val()=="EN"?"Only 1 customer can be selected for sharing":"只能选择1个客户进行共享！";
            alert(msg);
            return false;
        }
        var id = "";
        $("input[name='cb_list']:checked").each(function (i, item) {
            id = $(item).attr("id");
        });
    
    }
    
    var url = "share.php?id=" + id;

    var index = layui.layer.open({
        title: "客户信息共享",
        type: 2,
        area: ['700px', '600px'],
        content: url,
        success: function (layero, index) {
            setTimeout(function () {
                layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                    tips: 3
                });
            }, 500);
        }
    });
    pageOperate(url, 'add');

    return false;
}

function customerExists(customerName) {
    layui.use(['form', 'layer'], function () {
        var layer = layui.layer,
            form = layui.form;
        if(ifEmpty(customerName)) return false;
        var url = "action.php?action=Retrieve";
        $.post(url, {
            dataType: 'findCustomerNameExists',
            customer_name: customerName
        }, function (data) {
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
                if (reJson.data) {
                    $("#customer_name").val('');
                    layer.alert('客户已存在!', {
                        icon: 5
                    });
                }
                form.render();
            } else {
                layer.alert('获取客户失败！', {
                    icon: 5
                });
            }
        });
        form.render();
    });
}

function makeCountrySelect() {
    layui.use(['form', 'layer'], function () {
        var layer = layui.layer,
            form = layui.form;
        form.on('select(continent)', function (data) {
            var url = "action.php?action=Retrieve";
            $.post(url, {
                dataType: 'getCountryData',
                continent: data.value
            }, function (data) {
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
                    // 刷新国家下拉数据选项
                    $("#country").empty();
                    var LANG=$("#LANG").val();
                    var select=LANG=="EN"?"select":"请选择";
                    var tpl = "";
                    tpl += "<option value=''>"+select+"</option>";
                    $.each(reJson.data, function (index, value) {
                        var zh_name=LANG=="EN"?value.country:value.zh_name
                        tpl += "<option value='" + value.iso_three + "'>" + zh_name + "</option>";
                    });
                    $("#country").html(tpl);
                    form.render('select');
                } else {
                    layer.alert('获取国家失败！', {
                        icon: 5
                    });
                }
            });
            form.render();
        });
    });
}

function findCustomerExists() {
    $("#customer_name").blur(function () {
        var customerName = this.value;
        customerExists(customerName);
    });
}