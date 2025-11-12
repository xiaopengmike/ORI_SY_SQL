$(function () {
    $("#tab2").find("textarea").css("resize", "none");
    $("#tab2").find("textarea").css("overflow", "hidden");
    $("#tab6").find("textarea").css("resize", "none");
    $("#tab6").find("textarea").css("overflow", "hidden");
    var textarea = document.getElementById('textarea');
    makeExpandingArea(textarea);

    buzhou();

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

function saveData() {
    if (!CheckForm()) {
        return false;
    }
    var sub = $("#save").val();
    // 中文字符串需转码
    var url = "add.php?" + $("#form1").serialize() + "&sub=" + encodeURIComponent(sub);
    openWin(url);
    return false;
}

function submitData() {
    if (!CheckForm()) {
        return false;
    }
    var bz = 1;
    if (!getNextApprover(bz)) {
        return false;
    }
    var sub = $("#save_submit").val();
    var url = "add.php?" + $("#form1").serialize() + "&sub=" + encodeURIComponent(sub);
    openWin(url);
    return false;
}
var checkSubmitFlg = false;
function CheckForm() {
    var flag = false;

    if ($("#NAME").val() == "") {
        alert("应聘者姓名不能为空");
        return false;
    }
    if ($("#YPGW").val() == "") {
        alert("应聘岗位不能为空");
        return false;
    }
    if ($("#menu_parent_name").val() == "") {
        alert("部门不能为空");
        return false;
    }
    if ($("#date").val() == "") {
        alert("出生日期不能为空");
        return false;
    }
    if ($("#XB").val() == "") {
        alert("性别不能为空");
        return false;
    }
    if ($("#native_place").val() == "") {
        alert("籍贯不能为空");
        return false;
    }
    if ($("#yx").val() == "") {
        alert("毕业院校不能为空");
        return false;
    }
    if ($("#TIME2").val() == "") {
        alert("预计入职时间不能为空");
        return false;
    }
    if ($("#TPDZ").val() == "") {
        alert("相片不能为空");
        return false;
    }
    if ($("#school_type option:selected").val()=="") {
        alert("请选择学校类型");
        return false;
    }
    if ($("#is_job_allowance option:selected").val()=="") {
        alert("请选择是否有职务津贴");
        return false;
    }
    if ($("#is_job_allowance  option:selected").val()=="Y") {
        if ($("#job_allowance").val()<=0) {
            alert("转正职务津贴不能为空");
            return false;
        }
        
    }else{
        if ($("#trial_job_allowance").val()>0||$("#job_allowance").val()>0) {
            alert("是否有职位津贴为无，请清空职务津贴");
            return false;
        }
    }
    

    $("#tab").find("input,select").each(function () {
        $(this).css("border-color", "#C0BBB4");
        if ($(this).attr("is_verify") == 'false') {
            flag = false;
            return true;
        }
        if ($(this).attr("is_verify") == 'true') {
            if (this.value == '' || this.value == undefined || this.value == null) {
                alert("必填项不能为空！");
                $(this).css("border-color", "red");
                $(this).focus();
                flag = true;
                return false;
            }
        }
    });

    if (flag) {
        return false;
    }
    if (!checkSubmitFlg) {  
        checkSubmitFlg = true;
        $("#save").attr("readonly","readonly");
        $("#save_submit").attr("readonly","readonly");
        return true; 
    }else{ 
        alert("不能重复提交或保存"); 
        return false; 
    } 
    return true;
}

function getNextApprover(bz) {
    var type = "F01"; // 面试记录表
    var companyId = $("#company").val();
    var comUrl = "/common/next_approver.php?type=" + type + "&companyId=" + companyId + "&bz=" + bz;
    var isNext = true;
    $.post(comUrl, {}, function (data) {
        var obj = eval('(' + data + ')');
        var comMsg = "下一步经办人为" + obj.roleName + "（" + obj.userName + "），请确认是否提交？";
        if (window.confirm(comMsg)) {
            isNext = true;
        } else {
            isNext = false;
        }
    });
    if (!isNext) {
        return false;
    }

    return true;
}

function delete_attach(ATTACHMENT_ID, ATTACHMENT_NAME) {
    var msg = sprintf("确定要删除文件 '%s' 吗？", ATTACHMENT_NAME);
    if (window.confirm(msg)) {
        URL = "delete_attach.php?PLAN_ID=<?= $PLAN_ID ?>&ATTACHMENT_ID=" + ATTACHMENT_ID + "&ATTACHMENT_NAME=" + URLSpecialChars(ATTACHMENT_NAME);
        window.location = URL;
    }
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

    var html = '<tr style="text-align:center;vertical-align:middle"><td class="TableData"><input type="text" name="PLAN_sj1' + p + '" size="10" class="BigInput" onClick="WdatePicker()"/>' +
        '至<input type="text" name="PLAN_sj2' + p + '" size="10" class="BigInput" onClick="WdatePicker()"/></td>' +
        '<td class="TableData"><INPUT type="text"name="PLAN_dw' + p + '" class=BigInput size="20"></td><td class="TableData">' +
        '<INPUT type="text"name="PLAN_zw' + p + '" class=BigInput size="15"></td><td class="TableData">' +
        '<textarea id="zz_textarea_' + p + '" data-type="work_experience" cols="40" class=BigInput name="PLAN_zz' + p + '" style="OVERFLOW:hidden;resize:none;height:auto;"></textarea></td><td class="TableData"><INPUT type="text"name="PLAN_sc' + p + '" class=BigInput size="7">' +
        '<a href="javascript:;" onclick="removeLine(this)" >删除</a></td><td style="display:none"><input type="text" name="num" value=' + p + '></td></tr>';
    $("#add").before(html);

    var textarea = document.getElementById('zz_textarea_' + p);
    makeExpandingArea(textarea);
}

function removeLine1(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
    k--;
}

var k = 0;
var m = 0;

function add1() //附件（毕业证书
{
    m++;
    k++;

    var html = '<tr id=' + m + ' align="center"><td class="TableData">' + m + '</td><td class="TableData" >' +
        '<input type="text" name="name' + m + '" class=BigInput size="25"></td><td class="TableData">' +
        '<input type="file" name="file' + m + '"><a href="javascript:;" onclick="removeLine1(this)" >删除</a></td></tr>';

    $("#add1").before(html);
}

function removeLine2(obj) {
    var table = obj.parentNode.parentNode.parentNode;
    table.removeChild(obj.parentNode.parentNode);
    f--;
}

var f = 0;
var num = 0;

function add2() //评价
{
    num++;
    f++;

    var html = '<tr align="center"><td class="TableData">' + num + '</td><td class="TableData" >' +
        '<input type="text" name="mc' + num + '" class=BigInput size="25"></td><td class="TableData">' +
        '<input type="file" name="myfile' + num + '"><a href="javascript:;" onclick="removeLine2(this)" >删除</a></td></tr>';
    $("#add2").before(html);
}

function buzhou() {
    var table = $("#tab");
    // 刪除原公司类别审批流程行
    var indexS = document.getElementById('yijian').rowIndex;
    var indexE = document.getElementById('ruzhi').rowIndex;
    for (var n = indexS + 1; n < indexE; n++) {
        table.deleteRow(indexS + 1);
    }

    var ID = $("#company").val();
    url = "yijian.php?ID=" + ID;
    // 改使用post请求返回数据进行处理
    $.post(url, {}, function (data) {
        var myobj = eval('(' + data + ')');
        var m = 0;
        var html = "";
        for (var i = 0; i < myobj.length; i++) {
            m++;
            html += '<tr id="hos">' +
                '<td nowrap class="TableContent" align="center">' + myobj[i].role + '</td>' +
                '<td class="TableData" >' +
                '<textarea readonly name="YJ' + myobj[i].BZ + '" cols="100" rows="3" class="BigInput" value="" style="resize:none;overflow:hidden"></textarea>' +
                '</td>' +
                '</tr>';
        }
        $("#yijian").after(html);
        $("#prcs_num").val(myobj.length);
    });
}

function sum() {
    var a = $("#gwgz").val();
    var b = $("#jxgz").val();
    var c = $("#hsbt").val();
    var d = $("#zfbt").val();
    var f = $("#jtbt").val();
    var v = $("#xjxd").val();
    var p = $("#ng").val();
    var h = $("#txbz").val();
    var duty = $("#duty_allowance").val();
    var sum = parseFloat(a) + parseFloat(b) + parseFloat(c) + parseFloat(d) + parseFloat(f) + parseFloat(v) + parseFloat(p) + parseFloat(h) + parseFloat(duty);

    $("#je").val(parseFloat(sum).toFixed(2));
}

function sum1() {
    var m = $("#gz1").val();
    var n = $("#gz2").val();
    var j = $("#gz3").val();
    var k = $("#gz4").val();
    var f = $("#jtbt1").val();
    var v = $("#xjxd1").val();
    var u = $("#ng1").val();
    var o = $("#txbz1").val();
    var duty = $("#duty_allowance1").val();
    var sum = parseFloat(m) + parseFloat(n) + parseFloat(j) + parseFloat(k) + parseFloat(f) + parseFloat(v) + parseFloat(u) + parseFloat(o) + parseFloat(duty);

    $("#gz7").val(parseFloat(sum).toFixed(2));
}

function gongh() {
    var rzgh = $("#ZH").val();
    $("#RZGH").val(rzgh);
}

function nianl() {

    var date = new Date();
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var mou1 = year * 12 + month;
    var RQ = document.getElementById('date').value;
    var year1 = RQ.split("-");
    var mou2 = year1[0] * 12 + parseInt(year1[1]);
    var mou = mou1 - mou2;
    var y = parseInt(mou / 12);

    document.getElementById('age').value = y + "岁";
}

function gongz1(obj) {
    var DJ = $(obj).val();
    var dom = obj.parentNode.parentNode;
    if (DJ == '' || DJ == undefined) {
        $(dom).find("input").each(function () {
            this.value = 0;
        });
        return false;
    }
    var per = 1;
    var perFlag = $(obj.parentNode).find("[name='perFlag']").val();
    if (perFlag == "1") {
        per = 0.9;
    }

    url = "dangj1.php?ID=" + DJ;
    $.post(url, {}, function (response) {
        var myobj = eval('(' + response + ')');

        var total = parseFloat(myobj.GWGZ) + parseFloat(myobj.JXGZ) + parseFloat(myobj.ZFBT) + parseFloat(myobj.HSBT);
        var je = parseFloat(total * per).toFixed(2);
        var gwgz = parseFloat((parseFloat(je) - parseFloat(myobj.HSBT) - parseFloat(myobj.ZFBT)) / 2).toFixed(2);

        $("#hsbt").val(parseFloat(myobj.HSBT).toFixed(2));
        $("#zfbt").val(parseFloat(myobj.ZFBT).toFixed(2));
        $("#gwgz").val(parseFloat(gwgz).toFixed(2));
        $("#jxgz").val(parseFloat(gwgz).toFixed(2));
        $("#je").val(je);
    });
}

function gongz2(obj) {
    var DJ = $(obj).val();
    var dom = obj.parentNode.parentNode;
    if (DJ == '' || DJ == undefined) {
        $(dom).find("input").each(function () {
            this.value = 0;
        });
        return false;
    }
    url = "dangj1.php?ID=" + DJ;
    $.post(url, {}, function (response) {
        var myobj = eval('(' + response + ')');

        $("#gz1").val(parseFloat(myobj["GWGZ"]).toFixed(2));
        $("#gz2").val(parseFloat(myobj["JXGZ"]).toFixed(2));
        $("#gz4").val(parseFloat(myobj["ZFBT"]).toFixed(2));
        $("#gz3").val(parseFloat(myobj["HSBT"]).toFixed(2));
        var je = parseFloat(myobj["GWGZ"]) + parseFloat(myobj["JXGZ"]) + parseFloat(myobj["ZFBT"]) + parseFloat(myobj["HSBT"]);

        $("#gz7").val(parseFloat(je).toFixed(2));
    });
}

function openWin(url) {
    var winObj = window.open(url, "", "height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=120,left=" + (screen.availWidth - 500) / 2 + ",resizable=yes");
    var loop = setInterval(function () {
        if (winObj.closed) {
            clearInterval(loop);
            parent.location.reload();
            // window.top.close();
            closeModel();
        }
    }, 1);
}

function closeModel() {
    window.close();
    window.opener.location.href = window.opener.location.href;
}