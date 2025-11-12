var product_types=["Electric","Soft","Inhegrid","Inhenergy","Jxinhe","Cdinhe","Other"];
function removeLine(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
}

function sumProductNumber() {
    var length = $("[id='order_number']").length;
    var i = 0;
    var number = 0;
    for (; i < length; i++) {
        var thisNum = $($("[id='order_number']")[i]).val();
        if (ifEmpty(thisNum)) thisNum = 0;
        number += parseInt(thisNum);
    }
    $("#meter_number").val(number);
}

function findProductDetail(formId) {
    layui.use(['form', 'layer'], function () {
        var layer = layui.layer,
            form = layui.form;
        var url = "action.php?action=Retrieve";
        $.post(url, {
            dataType: 'findProductDetail',
            form_id: formId
        }, function (reData) {
            var reJson = null;
            try {
                reJson = eval('(' + reData + ')');
            } catch (e) {
                layer.alert('Error: ' + e, {
                    icon: 5
                });
                return;
            }
            if (reJson.code == "200") {
                var productData = reJson.data;
                $("#meter_one").empty();
                $("#meter_two").empty();
                $("#meter_three").empty();
                $("#non_meter").empty();
                $("#addMeterCount").val(0);
                $("#addOtherCount").val(0);
                product_company=$("#product_company").val()=="INHE"?$("#product_company").val():"";
                if (!ifEmpty(productData.electric)) {
                    var eleLength = productData.electric.length;
                    for (var i = 1; i <= eleLength; i++) {
                        addLine("addMeter",product_company, productData.electric);
                        form.render();
                    }
                }
                product_types=eval($("#product_detail_types").val());
                product_types.forEach(function(product_type) {
                    if(product_type=='Electric'){
                        return true;
                    }
                    
                    if(($("#user_bu").val()=="GRIDINHE"||$("#user_bu").val()=="IMCINHE")&&product_type!='Other'){
                        return true;
                    }
                    $("#non_meter_"+product_type).empty();
                    $("#add"+product_type+"Count").val(0);
                    var add_type=product_type.toLowerCase();
                    product_company=$("#product_company").val().toLowerCase()==add_type?$("#product_company").val():"";
                    if((product_type=='Soft'&&$("#product_company").val()=='WITLINK')||product_type=='Other'){
                        product_company=$("#product_company").val()
                    }
                    //console.log(productData[add_type]);
                    if (!ifEmpty(productData[add_type])) {
                        var otherLength = productData[add_type].length;
                        for (var j = 1; j <= otherLength; j++) {
                            addLine("add"+product_type,product_company, productData[add_type]);
                            form.render();
                        }
                    }
                  });
                
                sumProductNumber();
                form.render();
            } else {
                layer.alert('ÇëÇóÊý¾Ý´íÎó', {
                    icon: 5
                });
            }
        });
        form.render();
    });
}

function addLine(type,other_company, data) {
    layui.use(['form', 'layer'], function () {
        var layer = layui.layer,
            form = layui.form;
        var meterNum = $("#addMeterCount").val();
        var nonNum = $("#"+type+"Count").val();
        if (type == 'addMeter') {
            meterNum++;
            findLineData(meterNum, type,other_company, data);
        }
        if (type != 'addMeter') {
            nonNum++;
            findLineData(nonNum, type,other_company, data);
        }
        $("#" + type + "Count").val(parseInt($("#" + type + "Count").val()) + 1);
        form.render();
    });
}

var attachStr = [];

function findLineData(number, type,other_company, data) {
    layui.use(['form', 'layer', 'attach'], function () {
        var layer = layui.layer,
            attach = layui.attach,
            form = layui.form;
        var url = "makeLineHtml.php";
        $.ajaxSettings.async = false;
        $.post(url, {
            number: number,
            type: type,
            other_company:other_company
        }, function (reData) {
            var reJson = null;
            try {
                reJson = eval('(' + reData + ')');
                if (type == 'addMeter') {
                    $("#meter_one").append(reJson.meter_one);
                    $("#meter_two").append(reJson.meter_two);
                    $("#meter_three").append(reJson.meter_three);
                    if (!ifEmpty(data)) electricDataInit(number, data);
                } else if (type != 'addMeter') {
                    $("#non_meter_"+type.substring(3)).append(reJson.non_meter);
                    form.render();
                    if (!ifEmpty(data)) otherDataInit(number, data, type.substring(3));
                }
                $(".btn-upload").each(function () {
                    if (attachStr.indexOf(this.id) <= -1) {
                        attachStr.push(this.id);
                        if (this.id.indexOf(number) != -1) {
                            attach.init({
                                elem: "#" + this.id,
                                uploadUrl: "/general/attachment/attach.php?type=" + this.id + "&url=common"
                            });
                        }
                    }
                });
                lineInit();
                form.render();
            } catch (e) {
                layer.alert('Error: ' + e, {
                    icon: 5
                });
                return;
            }
        });
        $.ajaxSettings.async = true;
        form.render();
    });
}

function lineInit() {
    $("[id='non_order_number_Soft'],[id='non_order_number_Inhegrid'],[id='non_order_number_Inhenergy'],[id='non_order_number_Other'],[id='non_order_number_Cdinhe']").each(function () {
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
    $("[id='order_number']").each(function () {
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
        this.onblur = function () {
            sumProductNumber();
        };
    });
}

function electricDataInit(eleNo, data) {
    $("[name='product_name" + eleNo + "']").val(data[eleNo - 1].name);
    $("[name='internal_model" + eleNo + "']").val(data[eleNo - 1].specification);
    $("[name='order_number" + eleNo + "']").val(data[eleNo - 1].number);
}

function otherDataInit(otherNo, data,type) {
    //console.log("[name='non_product_name" +type+ otherNo + "']");
    //console.log(data[otherNo - 1].name);
    $("[name='non_product_name_" +type+ otherNo + "']").val(data[otherNo - 1].name);
    $("[name='non_order_number_" +type+ otherNo + "']").val(data[otherNo - 1].number);
    $("[name='specification_model_" + type+otherNo + "']").val(data[otherNo - 1].specification);
    $("[name='unit_" +type+ otherNo + "']").val(data[otherNo - 1].unit);
    if(type=="Soft"){
        if(data[otherNo - 1].license_product){
            $("select[name='license_product_" +type+ otherNo + "']").eq(0).siblings("div.layui-form-select").find('dl').find('dd[lay-value=' + data[otherNo - 1].license_product + ']').click();
        }
        $("[name='license_years_" +type+ otherNo + "']").val(data[otherNo - 1].license_years);
        if(data[otherNo - 1].license_product){
            $("select[name='license_type_" +type+ otherNo + "']").eq(0).siblings("div.layui-form-select").find('dl').find('dd[lay-value=' + data[otherNo - 1].license_type + ']').click();
        }
    }
}

function selectProductBrand(form){
    form.on('select(product_brand)',function(data){
        var select = 'dd[lay-value=' + data.value + ']';
        var length=$('select[name='+data.elem.getAttribute("name")+']').length;
        for(i=0;i<length;i++){
            if(data.value!=$('select[name='+data.elem.getAttribute("name")+']').eq(i).val()){
                $('select[name='+data.elem.getAttribute("name")+']').eq(i).siblings("div.layui-form-select").find('dl').find(select).click();
            }
        }    
        form.render('select');                   
    });

    // form.on('select(license_product)',function(data){
    //     var select = 'dd[lay-value=' + data.value + ']';
    //     var length=$('select[name='+data.elem.getAttribute("name")+']').length;
    //     for(i=0;i<length;i++){
    //         if(data.value!=$('select[name='+data.elem.getAttribute("name")+']').eq(i).val()){
    //             $('select[name='+data.elem.getAttribute("name")+']').eq(i).siblings("div.layui-form-select").find('dl').find(select).click();
    //         }
    //     }    
    //     form.render('select');                   
    // });

    // form.on('select(license_type)',function(data){
    //     var select = 'dd[lay-value=' + data.value + ']';
    //     var length=$('select[name='+data.elem.getAttribute("name")+']').length;
    //     for(i=0;i<length;i++){
    //         if(data.value!=$('select[name='+data.elem.getAttribute("name")+']').eq(i).val()){
    //             $('select[name='+data.elem.getAttribute("name")+']').eq(i).siblings("div.layui-form-select").find('dl').find(select).click();
    //         }
    //     }
    //     form.render('select');                   
    // });
}
