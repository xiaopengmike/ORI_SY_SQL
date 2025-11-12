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

function createLine(type) {
    var html = '';
    var deleted=$("#LANG").val()=="EN"?"Delete":"删除";
    if (type == 'addContact') {
        contactNum = $("#addContactCount").val();
        contactNum++;
        html = '<tr>';
        html += '<td  class="TableData"><input type="text" class="layui-input" id="contacts_name" name="contacts_name' + contactNum + '" /></td>';
        html += '<td  class="TableData"><input type="text" class="layui-input" id="phone_one" name="phone_one' + contactNum + '" /></td>';
        html += '<td  class="TableData"><input type="text" class="layui-input" id="phone_two" name="phone_two' + contactNum + '" /></td>';
        html += '<td  class="TableData"><input type="text" class="layui-input" id="email" name="email' + contactNum + '" /></td>';
        html += '<td  class="TableData"><input type="text" class="layui-input" id="position" name="position' + contactNum + '" /></td>';
        html += '<td  class="TableData" colspan="2"><input type="text" class="layui-input" id="contacts_address" name="contacts_address' + contactNum + '" /></td>';
        html += '<td  class="TableData"><input type="text" class="layui-input" id="remark" name="remark' + contactNum + '" />';
        html += '<a href="javascript:;" onclick="removeLine(this)" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+deleted+'</a></td>';
        html += '</tr>';
    } else if (type == 'addCompany') {
        companyNum = $("#addCompanyCount").val();
        companyNum++;
        html += '<tr>';
        html += '<td  class="TableData"><input type="text" name="company_name' + companyNum + '" id="company_name" class="layui-input" /></td>';
        html += '<td  class="TableData"><input type="text" name="company_short_name' + companyNum + '" id="company_short_name" class="layui-input" /></td>';
        html += '<td  class="TableData"><input type="text" name="company_country' + companyNum + '" id="company_country" class="layui-input" /></td>';
        html += '<td  class="TableData" colspan="3"><input type="text" name="company_address' + companyNum + '" id="company_address" class="layui-input" /></td>';
        html += '<td  class="TableData"><input type="text" name="contact_name' + companyNum + '" id="contact_name" class="layui-input" /></td>';
        html += '<td  class="TableData"><input type="text" id="phone" name="phone' + companyNum + '" class="layui-input" />';
        html += '<a href="javascript:;" onclick="removeLine(this)" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+deleted+'</a></td>';
        html += '</tr>';
    }
    $("#" + type + "Count").val(parseInt($("#" + type + "Count").val()) + 1);

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