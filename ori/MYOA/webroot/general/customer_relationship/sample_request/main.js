
function removeLine(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
    // detailNum--;
    // var count = $("#" + domId).val();
    // count--;
    // $("#" + domId).val(count);
}


function addLine(type, data) {
    layui.use(['form', 'layer'], function () {
        var layer = layui.layer,
            form = layui.form;
        var nonNum = $("#"+type+"Count").val();
        nonNum++;
        findLineData(nonNum, type, data);
        
        $("#" + type + "Count").val(parseInt($("#" + type + "Count").val()) + 1);
        form.render();
    });
}

var attachStr = [];

function findLineData(number, type, data) {
    layui.use(['form', 'layer', 'attach'], function () {
        var layer = layui.layer,
            attach = layui.attach,
            form = layui.form;
        var url = "makeLineHtml.php";
        $.ajaxSettings.async = false;
        $.post(url, {
            number: number,
            type: type
        }, function (reData) {
            var reJson = null;
            try {
                reJson = eval('(' + reData + ')');
                if (type == 'addNonExcipients') {
                    $("#non_excipients_"+type.substring(3)).append(reJson.non_excipients);
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
    $("[id='non_order_number_Soft'],[id='non_order_number_Inhegrid'],[id='non_order_number_Inhenergy'],[id='non_order_number_Other']").each(function () {
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


function otherDataInit(otherNo, data,type) {
    //console.log("[name='non_product_name" +type+ otherNo + "']");
    //console.log(data[otherNo - 1].name);
    $("[name='non_product_name_" +type+ otherNo + "']").val(data[otherNo - 1].name);
    $("[name='non_order_number_" +type+ otherNo + "']").val(data[otherNo - 1].number);
    $("[name='specification_model_" + type+otherNo + "']").val(data[otherNo - 1].specification);
    $("[name='unit_" +type+ otherNo + "']").val(data[otherNo - 1].unit);
}