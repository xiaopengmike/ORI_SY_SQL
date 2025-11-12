$(function () {
    getHkSelector("province");
    getHkSelector("pro_select");

    $("#province").change(function () {
        var proVal = this.value;
        if (proVal == "") {
            $("#city").empty();
            $("#city").append("<option value=''>请选择</option>");
            return false;
        }
        var url = "hk_selector.php";
        $.post(url, {}, function (data) {
            var obj = eval('(' + data + ')');

            $("#city").empty();
            $("#city").append("<option value=''>请选择</option>");
            var citySelectHtml = "";
            $.each(obj.citySelector[proVal], function (n, value) {
                citySelectHtml += "<option value='" + n + "'>" + value + "</option>";
            });
            $("#city").append(citySelectHtml);
        });
    });

    $("#city").change(function () {
        var pro = $("#province option:selected").text();
        var city = $("#city option:selected").text();
        $("#HK").val(pro + city);
    });

    $("#jg_select").change(function () {
        var jg = $("#jg_select").val();
        if (jg == "1") {
            $("input[name='JG']").val("");
            $("input[name='JG']").hide();
            $("select[name='pro_select']").val("");
            $("select[name='pro_select']").show();
            // 外籍时使用手动输入户口所在地
            $("select[name='province']").show();
            $("select[name='city']").show();
            $("#HK").val("");
            $("#HK").hide();
        } else if (jg == "2") {
            $("select[name='pro_select']").val("");
            $("select[name='pro_select']").hide();
            $("input[name='JG']").val("");
            $("input[name='JG']").show();
            // 外籍时使用手动输入户口所在地
            $("select[name='province']").hide();
            $("select[name='city']").hide();
            $("#HK").val("");
            $("#HK").show();
        } else {
            $("select[name='pro_select']").val("");
            $("select[name='pro_select']").hide();
            $("input[name='JG']").val("");
            $("input[name='JG']").hide();
            // 外籍时使用手动输入户口所在地
            $("select[name='province']").show();
            $("select[name='city']").show();
            $("#HK").val("");
            $("#HK").hide();
        }
    });

    $("#pro_select").change(function () {
        var province = $("#pro_select option:selected").text();
        $("#JG").val(province);
    });

    $("input[data-type='numInput']").each(function () {
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

});

function getHkSelector(id) {
    var url = "hk_selector.php";
    $.post(url, {}, function (data) {
        var obj = eval('(' + data + ')');

        $("#" + id).empty();
        $("#" + id).append("<option value=''>请选择</option>");
        var proSelectHtml = "";
        $.each(obj.proSelector, function (n, value) {
            proSelectHtml += "<option value='" + n + "'>" + value + "</option>";
        });
        $("#" + id).append(proSelectHtml);
    });
}

function CheckForm() {
    var m = document.getElementById("SFZ").value;
    if (m.length < 18) {
        alert("身份证号有误");
        return false;
    }
    if (document.getElementById("TIME1").value == "") {
        alert("出生日期不能为空");
        return false;
    }
    if (document.getElementById("HYZK").value == "") {
        alert("婚姻状况不能为空");
        return false;
    }
    if (document.getElementById("YX").value == "") {
        alert("毕业院校不能为空");
        return false;
    }
    if (document.getElementById("HK").value == "") {
        alert("户口所在地不能为空");
        return false;
    }
    if ($("#city").val() == "" || $("#city").val() == undefined) {
        alert("户口所在地不能为空");
        return false;
    }
    if (document.getElementById("SFZ").value == "") {
        alert("身份证号码不能为空");
        return false;
    }

    if (document.getElementById("DZ").value == "") {
        alert("现住地址不能为空");
        return false;
    }
    if (document.getElementById("phone").value == "") {
        alert("手机不能为空");
        return false;
    }
    var flag = false;
    $("#tab").find("input,select").each(function () {
        $(this).css("border-color", "#C0BBB4");
        if ($(this).attr("is_verify") == 'false') {
            flag = false;
            return true;
        }
        if (this.value == '' || this.value == undefined || this.value == null) {
            alert("必填项不能为空！");
            $(this).css("border-color", "red");
            $(this).focus();
            flag = true;
            return false;
        }
    });

    if (flag) {
        return false;
    }
    return true;
}

function imgPreview(fileDom) {
    //判断是否支持FileReader
    if (window.FileReader) {
        var reader = new FileReader();
    } else {
        alert("您的设备不支持图片预览功能，如需该功能请升级您的设备！");
    }

    //获取文件
    var file = fileDom.files[0];
    var imageType = /^image\//;
    //是否是图片
    if (!imageType.test(file.type)) {
        alert("请选择图片！");
        return;
    }
    //读取完成
    reader.onload = function (e) {
        //获取图片dom
        var img = document.getElementById("preview");
        //图片路径设置为读取的图片
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function removeLine(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
    j--;
}
var j = 0;
var p = 0;

function add() {
    p++; //每个行ID递增
    j++; //插入的第几行

    var html = "";
    html = '<tr><td class="TableData"><INPUT type="text" name="NAME_' + p + '" class=BigInput size="15">' +
        '</td><td class="TableData"><INPUT type="text"name="GX_' + p + '" class=BigInput size="15"></td><td colspan="3" class="TableData">' +
        '<INPUT type="text"name="DW_' + p + '" class=BigInput size="25"></td><td class="TableData">' +
        '<INPUT type="text"name="ZW_' + p + '" class=BigInput size="15"></td><td colspan="3" class="TableData">' +
        '<INPUT type="text"name="DZ_' + p + '" class=BigInput size="30"></td><td colspan="2" class="TableData">' +
        '<INPUT type="text"name="LXDH_' + p + '" class=BigInput size="15">' +
        '<a href="javascript:;" onclick="removeLine(this)" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td><td style="display:none"><input type="text" name="num" value=' + p + '></td></tr>';

    $("#add").before(html);
}

var a = 0;

function add1(obj) {
    a++;
    var html = "";
    html = '<tr><td class="TableData">' +
        '<INPUT type="text" name="YZ_' + a + '" maxlength="20" class=BigInput size="15" ></td>' +
        '<td colspan="3" class="TableData" >' +
        '<INPUT type="text" name="TS_' + a + '" maxlength="20" class=BigInput size="15" ></td>' +
        '<td colspan="2" class="TableData" >' +
        '<INPUT type="text" name="YD_' + a + '" maxlength="20" class=BigInput size="15" ></td>' +
        '<td colspan="3" class="TableData" >' +
        '<INPUT type="text" name="XZ_' + a + '" maxlength="20" class=BigInput size="15" ></td>' +
        '<td colspan="2" class="TableData" >' +
        '<INPUT type="text" name="KSZL_' + a + '" maxlength="20" class=BigInput size="15" ></td></tr>';
    $("#dataCount").val(a);
    $("#add1").before(html);
}

function removeLine2(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
    m--;
}
var m = 0;
var n = 0;

function add2() {
    n++; //每个行ID递增
    m++; //插入的第几行
    var html = "";

    html = '<tr><td class="TableData"><input type="text" name="TIME1_' + n + '" size="15" maxlength="10" class="BigInput"  onClick="WdatePicker()"/>' +
        '</td><td class="TableData"><input type="text" name="TIME2_' + n + '" size="15" maxlength="10" class="BigInput"  onClick="WdatePicker()"/></td>' +
        '<td colspan="4" class="TableData">' +
        '<INPUT type="text"name="SHCOOL_' + n + '" class=BigInput size="30"></td><td colspan="3" class="TableData">' +
        '<INPUT type="text"name="ZY_' + n + '" class=BigInput size="25"></td><td colspan="2" class="TableData">' +
        '<INPUT type="text"name="XL_' + n + '" class=BigInput size="20">' +

        '<a href="javascript:;" onclick="removeLine2(this)" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td><td style="display:none"><input type="text" name="num2" value=' + n + '></td></tr>';

    $("#add2").before(html);
}

function removeLine3(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
    a--;
}
var a = 0;
var b = 0;

function add3() {
    b++; //每个行ID递增
    a++; //插入的第几行
    var html = '<tr><td class="TableData"><input type="text" name="KS1_' + b + '" size="15" maxlength="10" class="BigInput" onClick="WdatePicker()"/>' +
        '</td><td class="TableData"><input type="text" name="KS2_' + b + '" size="15" maxlength="10" class="BigInput" onClick="WdatePicker()"/></td>' +
        '<td colspan="3" class="TableData">' +
        '<INPUT type="text"name="GZDW_' + b + '" class=BigInput size="30"></td><td colspan="2" class="TableData">' +
        '<INPUT type="text"name="GZZW_' + b + '" class=BigInput size="25"></td><td colspan="2" class="TableData">' +
        '<INPUT type="text"name="ZMR_' + b + '" class=BigInput size="20"></td><td colspan="2" class="TableData">' +
        '<INPUT type="text"name="LXDH_' + b + '" class=BigInput size="20">' +
        '<a href="javascript:;" onclick="removeLine3(this)" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td><td style="display:none"><input type="text" name="num3" value=' + b + '></td></tr>';

    $("#add3").before(html);

}

function removeLine4(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
    t--;
}
var t = 0;
// var mn=0;
function fujian() {
    var mn = $("#tab").find("[id='attach_num']").length;
    mn++;
    t++;

    var html = "";
    html = '<tr id=' + mn + '><td colspan="2" class="TableData" id="attach_num">' + mn + '</td><td class="TableData" colspan=5>' +
        '<input type="text" name="filename_' + mn + '" class=BigInput size="25"></td><td colspan="4" class="TableData">' +
        '<input type="file" name="file_' + mn + '"><a href="javascript:;" onclick="removeLine4(this)" >删除</a></td></tr>';
    $("#fujianAdd").before(html);

}

// 身份证格式校验
function verifyFormat(sfz) {
    if (sfz == '' || sfz == null || sfz == undefined) {
        $("#SFZ").val("");
        return false;
    }
    var flag = idCardNoUtil.checkIdCardNo(sfz);
    if (!flag) {
        // alert("身份证号码格式错误！");
        if (confirm("身份证号码格式错误，外籍人员请点取消！")) {
            sfz = "";
            $("#SFZ").val(sfz);
            return false;
        }
        // sfz = "";
        // $("#SFZ").val(sfz);
        return false;
    }
}

function updateData() {
    // if (!CheckForm()) {
    //     return false;
    // }
    var url = "update.php?";
    $.post(url, $("#form1").serializeArray(), function (data) {
        var result = eval('(' + data + ')');
        if (result.status == "success") {
            alert("提交成功");
            parent.location.reload();
            closeModel();
        } else {
            alert("提交失败");
            return false;
        }
    });
    return false;
}