// 人事异动js提取，简化页面代码，方便阅读

// 页面初始化js
$(function() {
    $("#tab5").find("textarea").css("resize", "none");
    $("#tab5").find("textarea").css("overflow", "hidden");

    var textarea = $('#textarea_yj');
    makeExpandingArea(textarea);

    $("#YD1").change(function(){
        changeCompany();
    });
    $("#YD2").change(function(){
        changeDept();
    });
    $("#YD3").change(function(){
        changeZw();
    });

    // 金额栏位输入控制
    $("input[data-type='numInput']").each(function(){
        this.onkeydown = function (e) {
            var theEvent = window.event || e;
            var code = theEvent.keyCode || theEvent.which;
            if (code == 13) {
                return false;
            }
        };
        this.onkeyup = function(){
            clearNoNum(this);
        };
        this.onblur = function(){
            this.value = parseFloat(this.value).toFixed(2);
            if(isNaN(this.value) || this.value == "" || this.value == undefined){
                this.value = "0.00";
            }
            sum1();
        }
    });
});

/**
 *  保存并提交
 */
function saveSubmit(){
    // $("#save_submit").val("正在提交...").attr("disabled", true);
    // console.log($("#form1").serialize());
    // $.post("add.php", $("#form1").serialize(), function (ret) {
    //     // if (ret == "success") {
    //     // 	alert("保存成功！");
    //     // }
    //     // else
    //     // {
    //     // 	alert("保存失败!");
    //     // }
    //     // $("#save_submit").val("保存并提交").attr("disabled", false);
    // });
}

function sum1() {
    var m = $("#gz1").val();
    var n = $("#gz2").val();
    var j = $("#gz3").val();
    var k = $("#gz4").val();
    var p = $("#jtbt2").val();
    var d = $("#xjxd2").val();
    var g = $("#ng2").val();
    var s = $("#txbz2").val();
    var duty = $("#duty_allowance2").val();
    var je1 = $("#je").val();
    var sum1 = parseFloat(m) + parseFloat(n) + parseFloat(j) + parseFloat(k) + parseFloat(p) + parseFloat(d) + parseFloat(g) + parseFloat(s) + parseFloat(duty);
    var mu2 = sum1 - parseFloat(je1);
    $("#gz7").val(sum1.toFixed(2));
    $("#sum").val(mu2.toFixed(2));
}

var m = 0;

function removeLine(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
    m--;
}


function add() //附件
{
    m++;

    html = '<tr id=' + m + ' align="center"><td class="TableData">' + m + '</td><td class="TableData" >' +
        '<input type="text" name="name' + m + '" class=BigInput size="25"></td><td class="TableData" >' +
        '<input type="file" name="file' + m + '"><a href="javascript:;" onclick="removeLine(this)" >删除</a></td></tr>';
    $("#add").before(html);
}

function getname() {
    var dept_id = $('#menu_parent').val();

    url = "get.php?ID=" + dept_id;
    $.post(url, {}, function(response) {
        myobj = eval('(' + response + ')');
        $("#get").empty();
        $("#get").append('<option value="">未选择</option>');
        var html = "";
        for (var i = 0; i < myobj.length; i++) {
            html += '<option value="' + myobj[i].ID + '">' + myobj[i].NAME + '</option>';
        }
        $("#get").append(html);
    });
}

function fun() {
    var user_id = $('#name').val();
    var dept_id = $('#menu_parent').val();
    var dept_name = $('#menu_parent_name').val();
    $("#menu_parent1_hidden").val(dept_id);
    $("#menu_parent_name1_hidden").val(dept_name);
    $('#GH').val(user_id);
    url = "get1.php?ID=" + user_id;
    $.post(url, {}, function(response) {
        myobj = eval('(' + response + ')');

        $('#RQ').val(myobj.SJ);
        $('#RQ1').val(myobj.SJ);
        $('#ZHIW').val(myobj.ZW);
        $("#ZW1_hidden").val(myobj.ZW);
        $('#dj').val(myobj.DJ);
        $('#gwgz').val(parseFloat(myobj.GWGZ).toFixed(2));
        $('#jxgz').val(parseFloat(myobj.JXGZ).toFixed(2));
        $('#hsbt').val(parseFloat(myobj.HSBT).toFixed(2));
        $('#zfbt').val(parseFloat(myobj.ZFBT).toFixed(2));
        $('#jtbt').val(parseFloat(myobj.JTBT).toFixed(2));
        $('#xjxd').val(parseFloat(myobj.XJXD).toFixed(2));
        $('#ng').val(parseFloat(myobj.NG).toFixed(2));
        $('#txbz').val(parseFloat(myobj.TXBZ).toFixed(2));
        $('#duty_allowance').val(parseFloat(myobj.DUTY_ALLOWANCE).toFixed(2));
        $('#je').val(parseFloat(myobj.JE).toFixed(2));

        $("#tab3").find("input[id='jm']").val("******");
        // 目前设置仅唐经理可见
        if ($("#managerId").val() == "唐剑") {
            $("#tab3").find("input[id='jm']").remove();
            $("#tab3").find("input[data-type='jm_data']").show();
        }
    });
    fuwu();
}

function fuwu() {
    var m = $('#RQ1').val();
    var n = $('#RQ2').val();
    if(n == "" || n == undefined){
        $("#NX").val("");
        return false;
    }
    var year1 = m.split("-");
    var year2 = n.split("-");
    var mou1 = (year2[0] - 2018) * 12 + parseInt(year2[1]);
    var mou2 = (year1[0] - 2018) * 12 + parseInt(year1[1]);
    var mou = mou1 - mou2;
    var y = parseInt(mou / 12);
    var mu = mou % 12;
    $('#NX').val(y + "年" + mu + "月");

}

function gongz2() {
    var DJ = $("#DJ2").val();
    if (DJ == "" || DJ == undefined) {
        $("#gz1").val("");
        $("#gz2").val("");
        $("#gz4").val("");
        $("#gz3").val("");
        $("#gz7").val("");
        $("#sum").val("");
        return false;
    }
    url = "dangj1.php?ID=" + DJ;
    $.post(url, {}, function(response) {
        myobj = eval('(' + response + ')');
        $("#gz1").val(parseFloat(myobj["GWGZ"]).toFixed(2));
        $("#gz2").val(parseFloat(myobj["JXGZ"]).toFixed(2));
        $("#gz4").val(parseFloat(myobj["ZFBT"]).toFixed(2));
        $("#gz3").val(parseFloat(myobj["HSBT"]).toFixed(2));
        var je = parseFloat(myobj["GWGZ"]) + parseFloat(myobj["JXGZ"]) + parseFloat(myobj["ZFBT"]) + parseFloat(myobj["HSBT"]);

        $("#gz7").val(parseFloat(je).toFixed(2));
        var tf = parseFloat(parseFloat(je) - parseFloat($("#je").val())).toFixed(2);
        $("#sum").val(tf);
    });
}

function changeCompany() {
    if ($("#YD1").attr("checked") == true) {
        $("#GS2").attr("disabled",false);
        var gs1 = $("#GS1_hidden").val();
        $("#GS1").val(gs1);
    } else {
        $("#GS2").attr("disabled",true);
        $("#GS1").val("");
    }
}

function changeDept() {
    if ($("#YD2").attr("checked") == true) {
        $("#menu_parent_name1").attr("disabled",false);
        $("#menu_parent_name2").attr("disabled",false);
        var deptName = $("#menu_parent_name1_hidden").val();
        var deptId = $("#menu_parent1_hidden").val();
        $("#menu_parent_name1").val(deptName);
        $("#menu_parent1").val(deptId);
    } else {
        $("#menu_parent_name1").attr("disabled",true);
        $("#menu_parent_name2").attr("disabled",true);
        $("#menu_parent_name1").val("");
        $("#menu_parent1").val("");
    }
}

function changeZw() {
    if ($("#YD3").attr("checked") == true) {
        $("#ZW").attr("disabled",false);
        $("#ZW2").attr("disabled",false);
        var zw = $("#ZW1_hidden").val();
        $("#ZW").val(zw);
    } else {
        $("#ZW").attr("disabled",true);
        $("#ZW2").attr("disabled",true);
        $("#ZW").val("");
        $("#ZW2").val("");
    }
}