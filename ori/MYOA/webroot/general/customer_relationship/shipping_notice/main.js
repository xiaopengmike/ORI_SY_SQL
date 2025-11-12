function removeLine(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
    delCount();
    sumAllAmount();
    // detailNum--;
    // var count = $("#" + domId).val();
    // count--;
    // $("#" + domId).val(count);
}

function addLine(domId) {
    var noName = createLine(domId);
    return noName;
}

var detailNum = 0;
var otherDetail = 0;
var softDetail = 0;

function createLine(type) {
    var html = '';
    var noName = "";

    if (type == 'addEleDetail') {
        detailNum = $("#" + type + "Count").val();
        serialNumber = parseInt(detailNum) + 1;
        // detailNum++;
        html = '<tr align="center">';
        html += '<td class="TableData"><input type="hidden" class="layui-input" value="' + serialNumber + '" name="serial_number_electric' + serialNumber + '"/>' + serialNumber + '</td>';
        html += '<td class="TableData"><input type="text" class="layui-input" data-type="electric" id="no_electric" name="no_electric' + serialNumber + '" onclick="findProductList(this);return false;" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="name_electric" name="name_electric' + serialNumber + '"/></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="specification_electric" name="specification_electric' + serialNumber + '"/></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="number_electric" name="number_electric' + serialNumber + '" data-type="electric" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="price_electric" name="price_electric' + serialNumber + '" data-type="electric" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="total_amount_electric" name="total_amount_electric' + serialNumber + '" data-type="electric" /></td>';
        
        html += '<td class="TableData"><input type="text" class="layui-input" id="code_electric" name="code_electric' + serialNumber + '"/></td>';
        html += '<td class="TableData" colspan="7"><input type="text" class="layui-input" id="order_no_electric" name="order_no_electric' + serialNumber + '"/>';
        html += '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;É¾³ý</a></td>';
        html += '</tr>';
        noName = "no_electric" + serialNumber;
    } else if (type == 'addOtherDetail') {
        otherDetail = $("#" + type + "Count").val();
        serialNumber = parseInt(otherDetail) + 1;
        // otherDetail++;
        html = '<tr align="center">';
        html += '<td class="TableData"><input type="hidden" class="layui-input" value="' + serialNumber + '" name="serial_number_other' + serialNumber + '"/>' + serialNumber + '</td>';
        html += '<td class="TableData"><input type="text" class="layui-input" data-type="other" id="no_other" name="no_other' + serialNumber + '" onclick="findProductList(this);return false;" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="name_other" name="name_other' + serialNumber + '"/></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="specification_other" name="specification_other' + serialNumber + '"/></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="number_other" name="number_other' + serialNumber + '" data-type="other" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="price_other" name="price_other' + serialNumber + '" data-type="other" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="total_amount_other" name="total_amount_other' + serialNumber + '" data-type="other" /></td>';
        
        html += '<td class="TableData"><input type="text" class="layui-input" id="code_other" name="code_other' + serialNumber + '"/></td>';
        html += '<td class="TableData" colspan="7"><input type="text" class="layui-input" id="order_no_other" name="order_no_other' + serialNumber + '"/>';
        html += '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;É¾³ý</a></td>';
        html += '</tr>';
        noName = "no_other" + serialNumber;
    }else if (type == 'addCdinheDetail') {
        otherDetail = $("#" + type + "Count").val();
        serialNumber = parseInt(otherDetail) + 1;
        // otherDetail++;
        html = '<tr align="center">';
        html += '<td class="TableData"><input type="hidden" class="layui-input" value="' + serialNumber + '" name="serial_number_cdinhe' + serialNumber + '"/>' + serialNumber + '</td>';
        html += '<td class="TableData"><input type="text" class="layui-input" data-type="cdinhe" id="no_cdinhe" name="no_cdinhe' + serialNumber + '" onclick="findProductList(this);return false;" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="name_cdinhe" name="name_cdinhe' + serialNumber + '"/></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="specification_cdinhe" name="specification_cdinhe' + serialNumber + '"/></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="number_cdinhe" name="number_cdinhe' + serialNumber + '" data-type="cdinhe" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="price_cdinhe" name="price_cdinhe' + serialNumber + '" data-type="cdinhe" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="total_amount_cdinhe" name="total_amount_cdinhe' + serialNumber + '" data-type="cdinhe" /></td>';
        
        html += '<td class="TableData"><input type="text" class="layui-input" id="code_cdinhe" name="code_cdinhe' + serialNumber + '"/></td>';
        html += '<td class="TableData" colspan="7"><input type="text" class="layui-input" id="order_no_cdinhe" name="order_no_cdinhe' + serialNumber + '"/>';
        html += '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;É¾³ý</a></td>';
        html += '</tr>';
        noName = "no_cdinhe" + serialNumber;
    }else if (type == 'addInhegridDetail') {
        inhegridDetail = $("#" + type + "Count").val();
        serialNumber = parseInt(inhegridDetail) + 1;
        // inhegridDetail++;
        html = '<tr align="center">';
        html += '<td class="TableData"><input type="hidden" class="layui-input" value="' + serialNumber + '" name="serial_number_inhegrid' + serialNumber + '"/>' + serialNumber + '</td>';
        html += '<td class="TableData"><input type="text" class="layui-input" data-type="inhegrid" id="no_inhegrid" name="no_inhegrid' + serialNumber + '" onclick="findProductList(this);return false;" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="name_inhegrid" name="name_inhegrid' + serialNumber + '"/></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="specification_inhegrid" name="specification_inhegrid' + serialNumber + '"/></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="number_inhegrid" name="number_inhegrid' + serialNumber + '" data-type="inhegrid" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="price_inhegrid" name="price_inhegrid' + serialNumber + '" data-type="inhegrid" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="total_amount_inhegrid" name="total_amount_inhegrid' + serialNumber + '" data-type="inhegrid" /></td>';
        
        html += '<td class="TableData"><input type="text" class="layui-input" id="code_inhegrid" name="code_inhegrid' + serialNumber + '"/></td>';
        html += '<td class="TableData" colspan="7"><input type="text" class="layui-input" id="order_no_inhegrid" name="order_no_inhegrid' + serialNumber + '"/>';
        html += '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;É¾³ý</a></td>';
        html += '</tr>';
        noName = "no_inhegrid" + serialNumber;
    }else if (type == 'addInhenergyDetail') {
        inhenergyDetail = $("#" + type + "Count").val();
        serialNumber = parseInt(inhenergyDetail) + 1;
        // inhenergyDetail++;
        html = '<tr align="center">';
        html += '<td class="TableData"><input type="hidden" class="layui-input" value="' + serialNumber + '" name="serial_number_inhenergy' + serialNumber + '"/>' + serialNumber + '</td>';
        html += '<td class="TableData"><input type="text" class="layui-input" data-type="inhenergy" id="no_inhenergy" name="no_inhenergy' + serialNumber + '" onclick="findProductList(this);return false;" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="name_inhenergy" name="name_inhenergy' + serialNumber + '"/></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="specification_inhenergy" name="specification_inhenergy' + serialNumber + '"/></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="number_inhenergy" name="number_inhenergy' + serialNumber + '" data-type="inhenergy" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="price_inhenergy" name="price_inhenergy' + serialNumber + '" data-type="inhenergy" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="total_amount_inhenergy" name="total_amount_inhenergy' + serialNumber + '" data-type="inhenergy" /></td>';
        
        html += '<td class="TableData"><input type="text" class="layui-input" id="code_inhenergy" name="code_inhenergy' + serialNumber + '"/></td>';
        html += '<td class="TableData" colspan="7"><input type="text" class="layui-input" id="order_no_inhenergy" name="order_no_inhenergy' + serialNumber + '"/>';
        html += '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;É¾³ý</a></td>';
        html += '</tr>';
        noName = "no_inhenergy" + serialNumber;
    } else if (type == 'addSoftDetail') {
        softDetail = $("#" + type + "Count").val();
        serialNumber = parseInt(softDetail) + 1;
        // softDetail++;
        html = '<tr align="center">';
        html += '<td class="TableData"><input type="hidden" class="layui-input" value="' + serialNumber + '" name="serial_number_soft' + serialNumber + '"/>' + serialNumber + '</td>';
        html += '<td class="TableData"><input type="text" class="layui-input" data-type="soft" id="no_soft" name="no_soft' + serialNumber + '" onclick="findProductList(this);return false;" /></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="name_soft" name="name_soft' + serialNumber + '"/></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="specification_soft" name="specification_soft' + serialNumber + '"/></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="number_soft" name="number_soft' + serialNumber + '" data-type="soft"/></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="price_soft" name="price_soft' + serialNumber + '" data-type="soft"/></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="total_amount_soft" name="total_amount_soft' + serialNumber + '" data-type="soft"/></td>';
        
        html += '<td class="TableData"><input type="text" class="layui-input" id="code_soft" name="code_soft' + serialNumber + '"/></td>';
        html += '<td class="TableData"><select class="layui-input" id="license_product_soft" name="license_product_soft' + serialNumber + '">'+$("#license_product_select").html()+'</select></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="license_years_soft" name="license_years_soft' + serialNumber + '"/></td>';
        html += '<td class="TableData"><select class="layui-input" id="license_type_soft" name="license_type_soft' + serialNumber + '">'+$("#license_type_select").html()+'</select></td>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="license_key" name="license_key' + serialNumber + '"/>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="license_term" name="license_term' + serialNumber + '"/>';
        html += '<td class="TableData"><input type="text" class="layui-input date" id="license_key_date" name="license_key_date' + serialNumber + '"/>';
        html += '<td class="TableData"><input type="text" class="layui-input" id="order_no_soft" name="order_no_soft' + serialNumber + '"/>';
        html += '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;É¾³ý</a></td>';
        html += '</tr>';
        noName = "no_soft" + serialNumber;
    }
    $("#" + type + "Count").val(serialNumber);
    $("#" + type).before(html);
    lineInit();
    changeNumber();
    form.render();
    return noName;
}

function lineInit() {
    $("[id='number_electric'],[id='number_soft'],[id='number_inhegrid'],[id='number_inhenergy'],[id='number_other'],[id='number_cdinhe']").each(function () {
        this.onkeydown = function (e) {
            var theEvent = window.event || e;
            var code = theEvent.keyCode || theEvent.which;
            if (code == 13) {
                return false;
            }
        };
        this.onkeyup = function () {
            clearNoNum(this);
        };
    });
}
function sumNumber(type) {
    var length = $("[id='number_" + type + "']").length;
    var i = 0;
    var number = 0;
    for (i; i < length; i++) {
        number += parseInt($($("[id='number_" + type + "']")[i]).val());
    }
    if (isNaN(number)) number = 0;
    $("[name='all_number_" + type + "']").val(number);
}

function sumAmount(type) {
    var length = $("[id='total_amount_" + type + "']").length;
    var i = 0;
    var amount = 0;
    for (i; i < length; i++) {
        thisAmount = $($("[id='total_amount_" + type + "']")[i]).val();
        if (ifEmpty(thisAmount)) thisAmount = 0;
        amount = (parseFloat(amount) + parseFloat(thisAmount)).toFixed(4);
    }
    if (isNaN(amount)) amount = 0;
    $("[name='all_amount_" + type + "']").val(amount);
    sumAllAmount();
}

function sumAllAmount() {
    var eleAmount = $("[name='all_amount_electric']").val();
    var otherAmount = $("[name='all_amount_other']").val();
    var softAmount = $("[name='all_amount_soft']").val();
    var gridAmount = $("[name='all_amount_inhegrid']").val();
    var nergyAmount = $("[name='all_amount_inhenergy']").val();
    var cdinheAmount = $("[name='all_amount_cdinhe']").val();
    if (ifEmpty(eleAmount)) eleAmount = 0;
    if (ifEmpty(otherAmount)) otherAmount = 0;
    if (ifEmpty(softAmount)) softAmount = 0;
    if (ifEmpty(gridAmount)) gridAmount = 0;
    if (ifEmpty(nergyAmount)) nergyAmount = 0;
    if (ifEmpty(cdinheAmount)) cdinheAmount = 0;
    var allAmount = (parseFloat(eleAmount) + parseFloat(otherAmount) + parseFloat(softAmount)+ parseFloat(gridAmount)+ parseFloat(nergyAmount)+ parseFloat(cdinheAmount)).toFixed(4);
    if (isNaN(allAmount)) allAmount = 0;
    $("[name='all_amount']").val(allAmount);
}

function changeNumber() {
    var typeArr = ['electric', 'other', 'soft','inhegrid','inhenergy','cdinhe'];
    typeArr.forEach(function (type) {
        $("input[id='number_" + type + "']").each(function () {
            this.onchange = function () {
                var number = this.value;
                var price = $(this).parent().parent().find("[id='price_" + type + "']").val();
                var amount = number * parseFloat(price);
                if (isNaN(amount)) amount = 0;
                $(this).parent().parent().find("[id='total_amount_" + type + "']").val(parseFloat(amount).toFixed(4));
                sumNumber(type);
                sumAmount(type);
                sumAllAmount(type);
            };
        });
        $("input[id='price_" + type + "']").each(function () {
            this.onchange = function () {
                var price = this.value;
                var number = $(this).parent().parent().find("[id='number_" + type + "']").val();
                var amount = number * parseFloat(price);
                if (isNaN(amount)) amount = 0;
                $(this).parent().parent().find("[id='total_amount_" + type + "']").val(parseFloat(amount).toFixed(4));
                sumAmount(type);
                sumAllAmount(type);
            };
        });
    });
}

function delCount(){
    var typeArr = ['electric', 'other', 'soft','inhegrid','inhenergy','cdinhe'];
    typeArr.forEach(function (type) {
        if($("input[id='number_" + type + "']").length==0){
            $("[name='all_amount_" + type + "']").val(0);
            $("[name='all_number_" + type + "']").val(0);
        }
        $("input[id='number_" + type + "']").each(function () {
            
                var number = this.value;
                var price = $(this).parent().parent().find("[id='price_" + type + "']").val();
                var amount = number * parseFloat(price);
                if (isNaN(amount)) amount = 0;
                $(this).parent().parent().find("[id='total_amount_" + type + "']").val(parseFloat(amount).toFixed(4));
                sumNumber(type);
                sumAmount(type);
                sumAllAmount(type);
        });
        $("input[id='price_" + type + "']").each(function () {
                var price = this.value;
                var number = $(this).parent().parent().find("[id='number_" + type + "']").val();
                var amount = number * parseFloat(price);
                if (isNaN(amount)) amount = 0;
                $(this).parent().parent().find("[id='total_amount_" + type + "']").val(parseFloat(amount).toFixed(4));
                sumAmount(type);
                sumAllAmount(type);  
        });
    });
}