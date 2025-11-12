function removeLine(obj,detail) {
    var order_number=$(obj).parent().parent().find("[id='order_number']").val();
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
    if(detail=="p_order_number"){
        $("[id='p_order_number']").each(function () {
            var cur_order_number=$(this).val();
            console.log(cur_order_number);
            console.log(order_number)
            if(order_number==cur_order_number){
                $(this).parent().parent().remove();
            }
        });
    }
    // detailNum--;
    // var count = $("#" + domId).val();
    // count--;
    // $("#" + domId).val(count);
}

var attachStr = [];

function addLine(type,obj) {
    layui.use(['form', 'layer', 'attach'], function () {
        var layer = layui.layer,
            attach = layui.attach,
            form = layui.form;
        var html = '';
        if (type == 'addReceipt') {
            $.post('action.php?action=Retrieve', {
                dataType: 'getCommonParam',
                is_used: '1',
                type: 'currency'
            }, function (data) {
                var reJson = eval('(' + data + ')');
                var reData = reJson.data.currency;
                var cLength = reData.length;
                if (ifEmpty(cLength)) cLength = 0;
                var cn = 0;
                detailNum = $("#" + type + "Count").val();
                serialNumber = parseInt(detailNum) + 1;
                html = '<tr align="center">';
                html += '<td class="TableData"><input type="hidden" class="layui-input" id="serial_number" name="serial_number' + serialNumber + '" value="' + serialNumber + '" />' + serialNumber + '</td>';
                html += '<td class="TableData"><input type="text" class="layui-input" id="project_code" name="project_code' + serialNumber + '" /></td>';
                html += '<td class="TableData"><input type="text" class="layui-input" id="project_number" name="project_number' + serialNumber + '" /></td>';
                html += '<td class="TableData"><input type="text" class="layui-input mandatory" id="order_number" name="order_number' + serialNumber + '" /></td>';
                html += '<td class="TableData"><input type="text" class="layui-input" id="customer_name" name="customer_name' + serialNumber + '" /></td>';
                html += '<td class="TableData"><input type="text" class="layui-input" id="contract_amount" name="contract_amount' + serialNumber + '" /></td>';
                html += '<td class="TableData"><div class="layui-input-inline"><select id="currency_detail" name="currency_detail' + serialNumber + '" lay-search>';
                html += '<option value="">请选择</option>';
                for (; cn < cLength; cn++) {
                    html += '<option value="' + reData[cn].paras_value + '">' + reData[cn].paras_desc + '</option>';
                }
                html += '</select>';
                html += '<td class="TableData"><input type="text" class="layui-input" id="rmb_amount" name="rmb_amount' + serialNumber + '" /></td>';
                html += '<td class="TableData"><input type="text" class="layui-input" id="receipt_amount" lay-verify="required"  name="receipt_amount' + serialNumber + '" onblur="calUnReceipted(this.name);return false;"  /></td>';
                html += '<td class="TableData"><input type="text" class="layui-input" id="other_cost" name="other_cost' + serialNumber + '" /></td>';
                html += '<td class="TableData"><input type="text" class="layui-input" id="receipted" readonly name="receipted' + serialNumber + '" /></td>';
                html += '<td class="TableData"><input type="text" class="layui-input" id="un_receipt" readonly name="un_receipt' + serialNumber + '" /></td>';
                html += '<td class="TableData"><input type="text" class="layui-input" id="contract_party" name="contract_party' + serialNumber + '" /></td>';
                html += '<td class="TableData"><select lay-verify="required"  id="if_third_pay" name="if_third_pay' + serialNumber + '" lay-search>';
                html += '<option value="">请选择</option>';
                    html += '<option value="是">是</option>';
                    html += '<option value="否">否</option>';
                html += '</select>';
               // '<input type="text" class="layui-input" id="if_third_pay" name="if_third_pay' + serialNumber + '" />'
                html+='</td>';
                html += '<td class="TableData"><input type="text" class="layui-input" id="remark" name="remark' + serialNumber + '" />';
                html += '<a href="javascript:;" onclick="addLine(\'addProduct\',this)">添加明细</a><a href="javascript:;" onclick="removeLine(this,\'p_order_number\')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>';
                html += '</tr>';
            });
        } else if (type == 'addAttach') {
            softDetail = $("#" + type + "Count").val();
            serialNumber = parseInt(softDetail) + 1;
            html = '<tr align="center"><input type="hidden" name="type' + serialNumber + '" id="type" value="add" />';
            html += '<td class="TableData"><input type="hidden" class="layui-input" id="serial_number_attach" name="serial_number_attach' + serialNumber + '" value="' + serialNumber + '" />' + serialNumber + '</td>';
            html += '<td class="TableData" colspan=4><input type="text" class="layui-input" id="attach_name" name="attach_name' + serialNumber + '" /></td>';
            html += '<td class="TableData" colspan=10><input id="sales_receipt_attach' + serialNumber + '" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />';
            html += '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>';
            html += '</tr>';
        } else if (type == 'addProduct') {
            softDetail = $("#" + type + "Count").val();
            serialNumber = parseInt(softDetail) + 1;
            var w_order_number = $(obj).parent().parent().find("[id='order_number']").val();
            if(ifEmpty(w_order_number)){
                alert("请先选择订单编号！");return;
            }
            html = '<tr align="center"><input type="hidden" name="p_type' + serialNumber + '" id="p_type" value="add" />';
            html += '<td class="TableData"><input type="hidden" class="layui-input" id="serial_number_product" name="serial_number_product' + serialNumber + '" value="' + serialNumber + '" />' + serialNumber + '</td>';
            html += '<td class="TableData" colspan=4><input type="text"readonly class="layui-input " id="p_order_number" name="p_order_number' + serialNumber + '" value='+w_order_number+' /></td>';
            html += '<td class="TableData" colspan=4><div class="layui-input-inline mandatory"><select lay-search type="text" class="layui-input" id="p_product_type" name="p_product_type' + serialNumber + '" >'+$("#receipt_product_select").html()+'</select></div></td>';
            html += '<td class="TableData" colspan=3><div class="layui-input-inline mandatory"><input type="number" class="layui-input" id="p_contract_amount" name="p_contract_amount' + serialNumber + '" /></div></td>';
            html += '<td class="TableData" colspan=3><div class="layui-input-inline mandatory"><input type="number" class="layui-input" id="p_receipt_amount" lay-verify="required"  name="p_receipt_amount' + serialNumber + '" onblur="check_p_receipt_amount(this.name);return false;"  /></div>';
            
            html += '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>';
            html += '</tr>';
        }

        $("#" + type + "Count").val(serialNumber);
        $("#" + type).append(html);
        $(".btn-upload").each(function () {
            if (attachStr.indexOf(this.id) <= -1) {
                attachStr.push(this.id);
                if (this.id.indexOf(serialNumber) != -1) {
                    attach.init({
                        elem: "#" + this.id,
                        uploadUrl: "/general/attachment/attach.php?type=" + this.id + "&url=common"
                    });
                }
            }
        });
        $("[id='order_number']").each(function () {
            this.onkeydown = function (e) {
                var theEvent = window.event || e;
                var code = theEvent.keyCode || theEvent.which;
                if (code == 13) {
                    return false;
                }
            };
            this.onfocus = function () {
                getOrderList(this);
            };
        });
        form.render();
    });
}

function getOrderList(obj) {
    var layer = layui.layer,
        form = layui.form;
    var index = layui.layer.open({
        title: "取订单编号列表",
        type: 2,
        content: "order_list.php?domName=" + obj.name,
        area: ['600px','95%'],
        success: function (layero, index) {
            setTimeout(function () {
                layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                    tips: 3
                });
            }, 500);
        }
    });
    form.render();
}

function returnOrderData(reData, domName) {
    layui.use(['form', 'layer'], function () {
        var layer = layui.layer,
            form = layui.form;
        $("[name='" + domName + "']").parent().parent().find("[id='customer_name']").val(reData.customer_name);
        $("[name='" + domName + "']").parent().parent().find("[id='project_code']").val(reData.project_name);
        $("[name='" + domName + "']").parent().parent().find("[id='project_number']").val(reData.project_number);
        $("[name='" + domName + "']").parent().parent().find("[id='order_number']").val(reData.form_id);
        $("[name='" + domName + "']").parent().parent().find("[id='order_number']").attr("value",reData.form_id);
        $("[name='" + domName + "']").parent().parent().find("[id='contract_party']").val(reData.contract_party);
        $("[name='" + domName + "']").parent().parent().find("[id='contract_amount']").val(reData.total_amount);
        $("[name='" + domName + "']").parent().parent().find("[id='currency_detail']").val(reData.currency);
        sumReceipted(reData.form_id, domName);
        layer.closeAll();
        form.render();
    });
}

function sumReceipted(orderNumber, domName) {
    // 获取已收款原币金额
    var url = "action.php?action=Retrieve";
    $.post(url, {
        dataType: 'sumReceiptedAmount',
        orderNumber: orderNumber
    }, function (data) {
        var reJson = eval('(' + data + ')');
        var reData = reJson.data;
        $("[name='" + domName + "']").parent().parent().find("[id='receipted']").val(reData.receipted_amount);
        calUnReceipted(domName);
    });
}

function calUnReceipted(domName) {
    var layer = layui.layer,
        form = layui.form;
    // 计算未收款=合同金额-已收款金额
    var total = $("[name='" + domName + "']").parent().parent().find("[id='contract_amount']").val();
    var receipted = $("[name='" + domName + "']").parent().parent().find("[id='receipted']").val();
    var receiptAmount = $("[name='" + domName + "']").parent().parent().find("[id='receipt_amount']").val();
    if (ifEmpty(total)) total = 0;
    if (ifEmpty(receipted)) receipted = 0;
    if (ifEmpty(receiptAmount)) receiptAmount = 0;
    var unReceipt = 0;
    unReceipt = parseFloat(parseFloat(total) - parseFloat(receipted) - parseFloat(receiptAmount)).toFixed(2);
    if (!ifEmpty(unReceipt)) {
        if (unReceipt < 0) {
            layer.confirm('收款金额超出合同总金额，请确认金额无误！', {
                icon: 3,
                title: '提示',
                btn: ['金额正确', '重新输入']
            }, function (index) {
                layer.close(index);
            }, function (index) {
                $("[name='" + domName + "']").parent().parent().find("[id='receipt_amount']").val('');
                unReceipt = parseFloat(parseFloat(total) - parseFloat(receipted)).toFixed(2);
                layer.close(index);
            });
        }
        $("[name='" + domName + "']").parent().parent().find("[id='un_receipt']").val(unReceipt);
    }
    form.render();
}

function selectUserByDeptId() {}