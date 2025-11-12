var product_types=["Quotation"];

var product_types_lowercase=[];
var lineCountID=[];
var priceCountID=[];
$(function(){
    
    product_types=["Quotation"];
    product_types.forEach(function(product_type) {
        lineCountID.push('#add'+product_type+'DetailCount');
        priceCountID.push('#quotation_price_'+product_type.toLowerCase());
        priceCountID.push('#number_'+product_type.toLowerCase());
        product_types_lowercase.push(product_type.toLowerCase());
        });
    priceCount();
    
    
});
function priceCount(){
    $(priceCountID.join(',')).each(function () {
        this.onkeydown = function (e) {
            var theEvent = window.event || e;
            var code = theEvent.keyCode || theEvent.which;
            if (code == 13) {
                return false;
            }
        };
        this.onkeyup = function () {
            $.clearNotNum(this);
        };
        this.onblur = function () {
            personalAmount(this);
            sumAmount(this);
            sumNumber(this);
        };
    });
}
function removeLine(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
    //更新数量和金额
    personalAmount(obj.parentNode.children[0]);
    sumAmount(obj.parentNode.children[0]);
    sumNumber(obj.parentNode.children[0]);
    // detailNum--;
    // var count = $("#" + domId).val();
    // count--;
    // $("#" + domId).val(count);
}

function addLine(domId,product_type) {
    createLine(domId,product_type);
}

var detailNum = 0;
var otherDetail = 0;
var softDetail = 0;


function createLine(type,product_type,count_product_number) {
    var layer = layui.layer,
        form = layui.form;
    var html = '';
   
        // detailNum++;
    count_product_number = $("#" + type + "Count").val();
    serialNumber = parseInt(count_product_number) + 1;
    html = '<tr align="center">';
    html += '<td class="TableData"><input type="hidden" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="serial_number_'+product_type.toLowerCase()+'" name="serial_number_'+product_type.toLowerCase()+'' + serialNumber + '" value="' + serialNumber + '" />' + serialNumber + '</td>';
    html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="product_type_'+product_type.toLowerCase()+'" name="product_type_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="product_name_'+product_type.toLowerCase()+'" name="product_name_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="remarks_'+product_type.toLowerCase()+'" name="remarks_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="cost_'+product_type.toLowerCase()+'" name="cost_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="quotation_scheme_'+product_type.toLowerCase()+'" name="quotation_scheme_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'"  id="quotation_price_'+product_type.toLowerCase()+'" name="quotation_price_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'"  id="number_'+product_type.toLowerCase()+'" name="number_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" readonly id="count_price_'+product_type.toLowerCase()+'" name="count_price_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'"  id="proposal_price_'+product_type.toLowerCase()+'" name="proposal_price_'+product_type.toLowerCase()+'' + serialNumber + '" />';
   
    html += '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>';
    html += '</tr>';
    
    
    $("#" + type + "Count").val(parseInt($("#" + type + "Count").val()) + 1);
    $("#" + type).before(html);
    priceCount();
    form.render();
}

function sumAmount(obj) {
    var productType = $(obj).attr("data-type");
    if (ifEmpty(productType)) return '';
    var length = $("[id='count_price_" + productType + "']").length;
    var i = 0;
    var amount;
    var totalAmount = 0;
    for (; i < length; i++) {
        amount = 0;
        amount = $($("[id='count_price_" + productType + "']")[i]).val();
        if (ifEmpty(amount)) amount = 0;
        totalAmount = parseFloat(totalAmount) + parseFloat(amount);
    }
    totalAmount = parseFloat(totalAmount).toFixed(4);
    $("[name='sum_amount_" + productType + "']").val(totalAmount);
}

function sumNumber(obj) {
    var productType = $(obj).attr("data-type");
    if (ifEmpty(productType)) return '';
    var length = $("[id='number_" + productType + "']").length;
    var j = 0;
    var number;
    var totalNumber = 0;
    for (; j < length; j++) {
        number = 0;
        number = $($("[id='number_" + productType + "']")[j]).val();
        if (ifEmpty(number)) number = 0;
        totalNumber = parseInt(totalNumber) + parseInt(number);
    }
    totalNumber = parseInt(totalNumber);
    $("[name='sum_num_" + productType + "']").val(totalNumber);
}

function personalAmount(obj) {
    var productType = $(obj).attr("data-type");
    if (ifEmpty(productType)) return '';
    var number = $(obj).parent().parent().find("[id='number_" + productType + "']").val();
    var price = $(obj).parent().parent().find("[id='quotation_price_" + productType + "']").val();
    var personalAmount = 0;
    if (ifEmpty(price) || ifEmpty(number)) {
        personalAmount = 0;
    } else {
        personalAmount = parseFloat(price) * parseFloat(number);
    }
    personalAmount = parseFloat(personalAmount).toFixed(4);
    $(obj).parent().parent().find("[id='count_price_" + productType + "']").val(personalAmount);
}


function returnUser(userName, userId, domId) {
    $("#" + domId).val(userName);
    $("#" + domId).parent().find("[data-type='user_id']").val(userId); //取userId隐藏栏位赋值
}