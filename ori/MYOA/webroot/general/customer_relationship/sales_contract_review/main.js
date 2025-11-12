var product_types=["Electric","Soft","Inhegrid","Inhenergy","Jxinhe","Other"];

var product_types_lowercase=[];
var lineCountID=[];
var priceCountID=[];
$(function(){
    if($("#product_detail_types").val()){
        product_types=eval($("#product_detail_types").val());
        product_types.forEach(function(product_type) {
            lineCountID.push('#add'+product_type+'DetailCount');
            priceCountID.push('#price_'+product_type.toLowerCase());
            priceCountID.push('#number_'+product_type.toLowerCase());
            product_types_lowercase.push(product_type.toLowerCase());
            $('#upload_'+product_type).change(function(){
                var layer = layui.layer;
                addLineExcel(this,layer);
                //最后数据加载完 让loading层消失
            });
          });
        priceCount();
    }
    
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

function addLine(domId,product_type,cur_user_bu) {
    createLine(domId,product_type,cur_user_bu);
}

var detailNum = 0;
var otherDetail = 0;
var softDetail = 0;


function createLine(type,product_type,cur_user_bu) {
    var layer = layui.layer,
        form = layui.form;
    var html = '';
   
        // detailNum++;
    count_product_number = $("#" + type + "Count").val();
    serialNumber = parseInt(count_product_number) + 1;
    html = '<tr align="center" data-productType="'+product_type+'">';
    html += '<td class="TableData"><input type="hidden" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="serial_number_'+product_type.toLowerCase()+'" name="serial_number_'+product_type.toLowerCase()+'' + serialNumber + '" value="' + serialNumber + '" />' + serialNumber + '</td>';
    html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="name_'+product_type.toLowerCase()+'" name="name_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="specification_'+product_type.toLowerCase()+'" name="specification_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="unit_'+product_type.toLowerCase()+'" name="unit_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="number_'+product_type.toLowerCase()+'" name="number_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="price_'+product_type.toLowerCase()+'" name="price_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    if(cur_user_bu==$("#product_company").val()||product_type=='Other'){
        html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" readonly id="total_amount_'+product_type.toLowerCase()+'" name="total_amount_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
        html += '<td class="TableData" ><select  lay-search  data-type="'+product_type.toLowerCase()+'" other_company_product_'+product_type.toLowerCase()+'="other_company_product_'+product_type.toLowerCase()+'" id="other_company_product_'+product_type.toLowerCase()+'" name="other_company_product_'+product_type.toLowerCase()+'' + serialNumber + '" >'+$("#other_company_product_select").html()+'</select>';
    }else{
        html += '<td class="TableData" colspan="2"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" readonly id="total_amount_'+product_type.toLowerCase()+'" name="total_amount_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    }
    if(product_type=='Electric'){
        html += '<td class="TableData" colspan="2"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="tech_requirement_'+product_type.toLowerCase()+'" name="tech_requirement_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
        html += '<td class="TableData" colspan="2"><select lay-search lay-verify="required" data-type="'+product_type.toLowerCase()+'" brand_'+product_type.toLowerCase()+'="brand_'+product_type.toLowerCase()+'" id="brand_'+product_type.toLowerCase()+'" name="brand_'+product_type.toLowerCase()+'' + serialNumber + '" >'+$("#product_brand_select").html()+'</select>';
    }else if(product_type=='Soft'){
        //TODO 慧软CRM合同评审
        html += '<td class="TableData"><select lay-search  data-type="'+product_type.toLowerCase()+'" license_product_'+product_type.toLowerCase()+'="license_product_'+product_type.toLowerCase()+'" id="license_product_'+product_type.toLowerCase()+'" name="license_product_'+product_type.toLowerCase()+'' + serialNumber + '" >'+$("#license_product_select").html()+'</select>';
        html += '<td class="TableData"><input type="number" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="license_years_'+product_type.toLowerCase()+'" name="license_years_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
        html += '<td class="TableData"><select lay-search  data-type="'+product_type.toLowerCase()+'" license_type_'+product_type.toLowerCase()+'="license_type_'+product_type.toLowerCase()+'" id="license_type_'+product_type.toLowerCase()+'" name="license_type_'+product_type.toLowerCase()+'' + serialNumber + '" >'+$("#license_type_select").html()+'</select>';
        html += '<td class="TableData"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="tech_requirement_'+product_type.toLowerCase()+'" name="tech_requirement_'+product_type.toLowerCase()+'' + serialNumber + '" />';
    }else{
        html += '<td class="TableData" colspan="4"><input type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="tech_requirement_'+product_type.toLowerCase()+'" name="tech_requirement_'+product_type.toLowerCase()+'' + serialNumber + '" />';
    }
    html += '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>';
    html += '</tr>';
    
    
    $("#" + type + "Count").val(parseInt($("#" + type + "Count").val()) + 1);
    $("#" + type).before(html);
    priceCount();
    form.render();
}
function removeLicenseLine(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
}
function addLicenseLine(type,product_type) {
    var layer = layui.layer,
        form = layui.form;
    var html = '';
   
        // detailNum++;
    count_product_number = $("#" + type + "Count").val();
    serialNumber = parseInt(count_product_number) + 1;
    html = '<tr align="center">';
    html += '<td class="TableData" colspan=""><input type="hidden" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="serial_number_'+product_type.toLowerCase()+'" name="serial_number_'+product_type.toLowerCase()+'' + serialNumber + '" value="' + serialNumber + '" />' + serialNumber + '</td>';
    html += '<td class="TableData" colspan=""><select lay-search lay-verify="required" type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="name_'+product_type.toLowerCase()+'" name="license_product_'+product_type.toLowerCase()+'' + serialNumber + '" >'+$("#license_product_select").html()+'</select></td>';
    html += '<td class="TableData" colspan="3"><input lay-verify="required" type="number" placeholder="填数字（单位：月，永久请填999）" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="name_'+product_type.toLowerCase()+'" name="license_years_'+product_type.toLowerCase()+'' + serialNumber + '" /></td>';
    html += '<td class="TableData" colspan="5"><select lay-verify="required" type="text" class="layui-input" data-type="'+product_type.toLowerCase()+'" id="tech_requirement_'+product_type.toLowerCase()+'" name="license_type_'+product_type.toLowerCase()+'' + serialNumber + '" >'+$("#license_type_select").html()+'</select>';
    html += '<a href="javascript:;" onclick="removeLicenseLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>';
    html += '</tr>';
    
    
    $("#" + type + "Count").val(parseInt($("#" + type + "Count").val()) + 1);
    $("#" + type).before(html);
   // priceCount();
    form.render();
}

function sumAmount(obj) {
    var productType = $(obj).attr("data-type");
    if (ifEmpty(productType)) return '';
    var length = $("[id='total_amount_" + productType + "']").length;
    var i = 0;
    var amount;
    var totalAmount = 0;
    for (; i < length; i++) {
        amount = 0;
        amount = $($("[id='total_amount_" + productType + "']")[i]).val();
        if (ifEmpty(amount)) amount = 0;
        totalAmount = parseFloat(totalAmount) + parseFloat(amount);
    }
    totalAmount = parseFloat(totalAmount).toFixed(4);
    $("[name='sum_amount_" + productType + "']").val(totalAmount);
    sunAllAmount();
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
    var price = $(obj).parent().parent().find("[id='price_" + productType + "']").val();
    var personalAmount = 0;
    if (ifEmpty(price) || ifEmpty(number)) {
        personalAmount = 0;
    } else {
        personalAmount = parseFloat(price) * parseFloat(number);
    }
    personalAmount = parseFloat(personalAmount).toFixed(4);
    $(obj).parent().parent().find("[id='total_amount_" + productType + "']").val(personalAmount);
}

function sunAllAmount() {
    var arr = product_types_lowercase;
    var sumAmount = 0;
    var allAmount = 0;
    for (var x in arr) {
        type = arr[x];
        sumAmount = $("[name='sum_amount_" + type + "']").val();
        if (ifEmpty(sumAmount)) sumAmount = 0;
        allAmount = parseFloat(parseFloat(allAmount) + parseFloat(sumAmount)).toFixed(4);
    }
    $("#total_amount").val(allAmount);
}

function returnUser(userName, userId, domId) {
    $("#" + domId).val(userName);
    $("#" + domId).parent().find("[data-type='user_id']").val(userId); //取userId隐藏栏位赋值
}
function addLineExcel(event,layer) {
    var tishi = layer.msg('加载中..', {
                icon: 16
                ,shade: 0.3
                ,time: false
            });
    try {
        new Uint8Array([1,2]).slice(0,2);
    } catch (e) {
        console.log("[Uint8Array"+e.description+"]这里使用【Array.slice】。");
        //IE或有些浏览器不支持Uint8Array.slice()方法。改成使用Array.slice()方法
        Uint8Array.prototype.slice = Array.prototype.slice;

    }
    //var file = event.target.files[0];
    var file = $(event)[0].files[0];
    var fileReader = new FileReader();
    fileReader.readAsArrayBuffer(file);// 以二进制方式打开文件

    fileReader.onload = function(ev) {
        try {	  
            var data = new Uint8Array(ev.target.result);
            var workbook = XLSX.read(data, { type: 'array' });
        } catch (e) {
                console.error(e);
                return alert('文件类型不正确!');
        }
        
        // 假设我们读取第一个工作表
        var firstSheetName = workbook.SheetNames[0];
        var worksheet = workbook.Sheets[firstSheetName];

        // 将工作表转换为 JSON 格式
        var jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
        //console.log(jsonData[1]);
        var productType=$(event).attr("productType");
        serialNumber=0;
        for(var i=1,l=jsonData.length;i<l;i++){
            if(ifEmpty(jsonData[i][0])){
                continue;
            }
            addLine('add'+productType+'Detail',productType);
            serialNumber = $("#add" + productType + "DetailCount").val();
            //console.log(serialNumber);
            $("input[name='name_"+productType.toLowerCase()+serialNumber+"']").val(jsonData[i][0]);
            $("input[name='specification_"+productType.toLowerCase()+serialNumber+"']").val(jsonData[i][1]);
            $("input[name='unit_"+productType.toLowerCase()+serialNumber+"']").val(jsonData[i][2]);
            $("input[name='number_"+productType.toLowerCase()+serialNumber+"']").val(jsonData[i][3]);
            $("input[name='price_"+productType.toLowerCase()+serialNumber+"']").val(jsonData[i][4]);
            $("input[name='total_amount_"+productType.toLowerCase()+serialNumber+"']").val(jsonData[i][5]);
            
            //$("input[name='brand_"+productType.toLowerCase()+serialNumber+"']").val(jsonData[i][7]);
            if(productType=="Electric"){
                $("input[name='tech_requirement_"+productType.toLowerCase()+serialNumber+"']").val(jsonData[i][6]);
                $("select[name='brand_"+productType.toLowerCase()+serialNumber+"']").next().find('.layui-anim').children("dd:contains('"+jsonData[i][7]+"')").click();
            }else if(productType=="Soft"){
                $("select[name='license_product_"+productType.toLowerCase()+serialNumber+"']").next().find('.layui-anim').children("dd:contains('"+jsonData[i][6]+"')").click();
                $("input[name='license_years_"+productType.toLowerCase()+serialNumber+"']").val(jsonData[i][7]);
                $("select[name='license_type_"+productType.toLowerCase()+serialNumber+"']").next().find('.layui-anim').children("dd:contains('"+jsonData[i][8]+"')").click();
                $("input[name='tech_requirement_"+productType.toLowerCase()+serialNumber+"']").val(jsonData[i][9]);
            }else{
                $("input[name='tech_requirement_"+productType.toLowerCase()+serialNumber+"']").val(jsonData[i][6]);
            }
            personalAmount($("input[name='price_"+productType.toLowerCase()+serialNumber+"']"));
        }
        
        sumAmount($("input[name='price_"+productType.toLowerCase()+serialNumber+"']"));
        sumNumber($("input[name='price_"+productType.toLowerCase()+serialNumber+"']"));
        
        // 显示在网页上
        //$(event).parent().find("#output").text(JSON.stringify(jsonData, null, 2))
        //document.getElementById('output').textContent = JSON.stringify(jsonData, null, 2);
        $(event).val("");
        layer.close(tishi);
    };
   
    //reader.readAsArrayBuffer(file);
}
function clear_product(productType){
    $("tr[data-productType="+productType+"]").find("a[onclick='removeLine(this)']").click();
}