/**
 *  common.js 公共方法类
 *  @author ysr
 * 
 */


/**
 *  控制input输入框输入小数并保留2位小数
 *
 * @param {参数值} obj
 */
function clearNoNum(obj) {
    obj.value = obj.value.replace(/[^\d.]/g, ""); //清除"数字"和"."以外的字符
    obj.value = obj.value.replace(/^\./g, ""); //验证第一个字符是数字而不是
    obj.value = obj.value.replace(/\.{2,}/g, "."); //只保留第一个. 清除多余的
    obj.value = obj.value.replace(".", "$#$").replace(/\./g, "").replace("$#$", ".");
    obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3'); //只能输入两个小数
}


/**
 * 根据百分率获取对应的分数
 *
 * @param {百分比率} rate
 * @param {销售排名} ranking
 * @param {评分类型} type
 */
function getScoreByRate(rate, ranking, type) {
    var score = 0;
    rate = parseFloat(rate);
    ranking = parseFloat(ranking);
    if (isNaN(rate)) rate = 0;
    if (isNaN(ranking)) ranking = 0;
    switch (type) {
        case '1':
            if (rate < 85) {
                score = 70;
            } else {
                if (ranking <= 0) {
                    score = 70;
                } else if (ranking <= 10) {
                    score = 100;
                } else if (ranking <= 20) {
                    score = 90;
                } else if (ranking <= 80) {
                    score = 80;
                } else {
                    score = 70;
                }
            }
            break;
        case '2':
            if (rate >= 100) {
                score = 100;
            } else if (rate >= 80) {
                score = 90;
            } else if (rate >= 50) {
                score = 80;
            } else {
                score = 70;
            }
            break;
    }
    return score;
}

function sumBusiness() {
    // 得分1取三者最大值
    var num1 = parseFloat($("#DF1").val());
    var num2 = parseFloat($("#DF2").val());
    var num3 = parseFloat($("#DF3").val());
    var arr = [num1, num2, num3];
    var shf1 = parseFloat(Math.max.apply(null, arr)).toFixed(2);
    document.getElementById("SHF1").value = shf1;
    var hdf1 = parseFloat(parseFloat(shf1) * parseFloat($("#qz1").val()) * 0.01).toFixed(2);
    document.getElementById("HDF1").value = hdf1;

    var df4 = $("#DF4").val();
    var df5 = $("#DF5").val();
    var hdf2 = 0;
    var hdf3 = 0;
    if (df4 != '' && df4 != undefined) {
        hdf2 = parseFloat(parseFloat(df4) * parseFloat($("#qz4").val()) * 0.01).toFixed(2);
        document.getElementById("SHF2").value = df4;
        document.getElementById("HDF2").value = hdf2;
    }

    if (df5 != '' && df5 != undefined) {
        hdf3 = parseFloat(parseFloat(df5) * parseFloat($("#qz5").val()) * 0.01).toFixed(2);
        document.getElementById("SHF3").value = df5;
        document.getElementById("HDF3").value = hdf3;
    }

    sum = (parseFloat(hdf1) + parseFloat(hdf2) + parseFloat(hdf3)).toFixed(2);
    document.getElementById("HDF").value = sum;
}

//  对15位或者18位身份证格式校验：
//  调用idCardNoUtil.checkIdCardNo(idCardNo)传入身份证号码，实现校验
var idCardNoUtil = {
    /*省,直辖市代码表*/
    provinceAndCitys: {
        11: "北京",
        12: "天津",
        13: "河北",
        14: "山西",
        15: "内蒙古",
        21: "辽宁",
        22: "吉林",
        23: "黑龙江",
        31: "上海",
        32: "江苏",
        33: "浙江",
        34: "安徽",
        35: "福建",
        36: "江西",
        37: "山东",
        41: "河南",
        42: "湖北",
        43: "湖南",
        44: "广东",
        45: "广西",
        46: "海南",
        50: "重庆",
        51: "四川",
        52: "贵州",
        53: "云南",
        54: "西藏",
        61: "陕西",
        62: "甘肃",
        63: "青海",
        64: "宁夏",
        65: "新疆",
        71: "台湾",
        81: "香港",
        82: "澳门",
        91: "国外"
    },

    /*每位加权因子*/
    powers: ["7", "9", "10", "5", "8", "4", "2", "1", "6", "3", "7", "9", "10", "5", "8", "4", "2"],

    /*第18位校检码*/
    parityBit: ["1", "0", "X", "9", "8", "7", "6", "5", "4", "3", "2"],

    /*性别*/
    genders: {
        male: "男",
        female: "女"
    },

    /*校验地址码*/
    checkAddressCode: function (addressCode) {
        var check = /^[1-9]\d{5}$/.test(addressCode);
        if (!check) return false;
        if (idCardNoUtil.provinceAndCitys[parseInt(addressCode.substring(0, 2))]) {
            return true;
        } else {
            return false;
        }
    },

    /*校验日期码*/
    checkBirthDayCode: function (birDayCode) {
        var check = /^[1-9]\d{3}((0[1-9])|(1[0-2]))((0[1-9])|([1-2][0-9])|(3[0-1]))$/.test(birDayCode);
        if (!check) return false;
        var yyyy = parseInt(birDayCode.substring(0, 4), 10);
        var mm = parseInt(birDayCode.substring(4, 6), 10);
        var dd = parseInt(birDayCode.substring(6), 10);
        var xdata = new Date(yyyy, mm - 1, dd);
        if (xdata > new Date()) {
            return false; //生日不能大于当前日期
        } else if ((xdata.getFullYear() == yyyy) && (xdata.getMonth() == mm - 1) && (xdata.getDate() == dd)) {
            return true;
        } else {
            return false;
        }
    },

    /*计算校检码*/
    getParityBit: function (idCardNo) {
        var id17 = idCardNo.substring(0, 17);
        /*加权 */
        var power = 0;
        for (var i = 0; i < 17; i++) {
            power += parseInt(id17.charAt(i), 10) * parseInt(idCardNoUtil.powers[i]);
        }
        /*取模*/
        var mod = power % 11;
        return idCardNoUtil.parityBit[mod];
    },

    /*验证校检码*/
    checkParityBit: function (idCardNo) {
        var parityBit = idCardNo.charAt(17).toUpperCase();
        if (idCardNoUtil.getParityBit(idCardNo) == parityBit) {
            return true;
        } else {
            return false;
        }
    },

    /*校验15位或18位的身份证号码*/
    checkIdCardNo: function (idCardNo) {
        //15位和18位身份证号码的基本校验
        var check = /^\d{15}|(\d{17}(\d|x|X))$/.test(idCardNo);
        if (!check) return false;
        //判断长度为15位或18位 
        if (idCardNo.length == 15) {
            return idCardNoUtil.check15IdCardNo(idCardNo);
        } else if (idCardNo.length == 18) {
            return idCardNoUtil.check18IdCardNo(idCardNo);
        } else {
            return false;
        }
    },

    //校验15位的身份证号码
    check15IdCardNo: function (idCardNo) {
        //15位身份证号码的基本校验
        var check = /^[1-9]\d{7}((0[1-9])|(1[0-2]))((0[1-9])|([1-2][0-9])|(3[0-1]))\d{3}$/.test(idCardNo);
        if (!check) return false;
        //校验地址码
        var addressCode = idCardNo.substring(0, 6);
        check = idCardNoUtil.checkAddressCode(addressCode);
        if (!check) return false;
        var birDayCode = '19' + idCardNo.substring(6, 12);
        //校验日期码
        return idCardNoUtil.checkBirthDayCode(birDayCode);
    },

    //校验18位的身份证号码
    check18IdCardNo: function (idCardNo) {
        //18位身份证号码的基本格式校验
        var check = /^[1-9]\d{5}[1-9]\d{3}((0[1-9])|(1[0-2]))((0[1-9])|([1-2][0-9])|(3[0-1]))\d{3}(\d|x|X)$/.test(idCardNo);
        if (!check) return false;
        //校验地址码
        var addressCode = idCardNo.substring(0, 6);
        check = idCardNoUtil.checkAddressCode(addressCode);
        if (!check) return false;
        //校验日期码
        var birDayCode = idCardNo.substring(6, 14);
        check = idCardNoUtil.checkBirthDayCode(birDayCode);
        if (!check) return false;
        //验证校检码  
        return idCardNoUtil.checkParityBit(idCardNo);
    },

    formateDateCN: function (day) {
        var yyyy = day.substring(0, 4);
        var mm = day.substring(4, 6);
        var dd = day.substring(6);
        return yyyy + '-' + mm + '-' + dd;
    },

    //获取信息
    getIdCardInfo: function (idCardNo) {
        var idCardInfo = {
            gender: "", //性别
            birthday: "" // 出生日期(yyyy-mm-dd)
        };
        if (idCardNo.length == 15) {
            var aday = '19' + idCardNo.substring(6, 12);
            idCardInfo.birthday = idCardNoUtil.formateDateCN(aday);
            if (parseInt(idCardNo.charAt(14)) % 2 == 0) {
                idCardInfo.gender = idCardNoUtil.genders.female;
            } else {
                idCardInfo.gender = idCardNoUtil.genders.male;
            }
        } else if (idCardNo.length == 18) {
            var aday = idCardNo.substring(6, 14);
            idCardInfo.birthday = idCardNoUtil.formateDateCN(aday);
            if (parseInt(idCardNo.charAt(16)) % 2 == 0) {
                idCardInfo.gender = idCardNoUtil.genders.female;
            } else {
                idCardInfo.gender = idCardNoUtil.genders.male;
            }

        }
        return idCardInfo;
    },

    /*18位转15位*/
    getId15: function (idCardNo) {
        if (idCardNo.length == 15) {
            return idCardNo;
        } else if (idCardNo.length == 18) {
            return idCardNo.substring(0, 6) + idCardNo.substring(8, 17);
        } else {
            return null;
        }
    },

    /*15位转18位*/
    getId18: function (idCardNo) {
        if (idCardNo.length == 15) {
            var id17 = idCardNo.substring(0, 6) + '19' + idCardNo.substring(6);
            var parityBit = idCardNoUtil.getParityBit(id17);
            return id17 + parityBit;
        } else if (idCardNo.length == 18) {
            return idCardNo;
        } else {
            return null;
        }
    }
};

/**
 * 选定员工回显部门显示
 *
 */
function gongh(userId) {
    $.post('user_info.php?userId=' + userId, {}, function (data) {
        var obj = eval(data);

        $("#menu_parent_name").val(obj[0].dept_name);
        $("#menu_parent").val(obj[0].dept_id);
    });
}

/**
 * 选定部门返回对应员工
 *
 */
function selectUsers() {
    var deptId = $("#menu_parent").val();
    var scope = $("#scope").val();
    if (deptId != '') {
        $.post('user_info.php?deptId=' + deptId + '&scope=' + scope, {}, function (data) {
            var obj = eval('(' + data + ')');

            $("#name").empty();
            $("#name").append("<option value=''>未选择</option>");
            for (i = 0; i < obj.length; i++) {
                $("#name").append("<option value='" + obj[i].user_id + "'>" + obj[i].user_name + "</option>");
            }
        });
    }
}

// textarea输入框自适应控制
function makeExpandingArea(el) {
    var timer = null;
    //由于ie8有溢出堆栈问题，故调整了这里
    var setStyle = function (el, auto) {
        if (auto) el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    }
    var delayedResize = function (el) {
        if (timer) {
            clearTimeout(timer);
            timer = null;
        }
        timer = setTimeout(function () {
            setStyle(el)
        }, 200);
    }
    if (el.addEventListener) {
        el.addEventListener('input', function () {
            setStyle(el, 1);
        }, false);
        setStyle(el)
    } else if (el.attachEvent) {
        el.attachEvent('onpropertychange', function () {
            setStyle(el)
        })
        setStyle(el)
    }
    if (window.VBArray && window.addEventListener) { //IE9
        el.addEventListener("onkeydown", function () {
            var key = window.event.keyCode;
            if (key == 8 || key == 46) delayedResize(el);

        });
        el.addEventListener("oncut", function () {
            delayedResize(el);
        }); //处理粘贴
    }
}


function showDetail(url) {
    var myleft = (screen.availWidth - 500) / 2;
    window.open(url, "", "height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=120,left=" + myleft + ",resizable=yes");
}

/**
 *  新开全屏窗口
 * @param {*} url  访问路径
 */
function openFullModel(url) {
    window.open(
        url,
        "",
        "width=" + (window.screen.availWidth - 10) + ",height=" + (window.screen.availHeight - 30) + ",top=0,left=0,resizable=yes,status=yes,menubar=no,scrollbars=yes"
    );
}

function printDetail() {
    bdhtml = window.document.body.innerHTML; //获取当前页的html代码  
    sprnstr = "<!--startprint-->"; //设置打印开始区域  
    eprnstr = "<!--endprint-->"; //设置打印结束区域  
    prnhtml = bdhtml.substring(bdhtml.indexOf(sprnstr) + 18); //从开始代码向后取html  
    prnhtml = prnhtml.substring(0, prnhtml.indexOf(eprnstr)); //从结束代码向前取html 
    window.document.body.innerHTML = prnhtml;

    //去掉页眉页脚
    if (!!window.ActiveXObject || "ActiveXObject" in window) { //是否ie
        remove_ie_header_and_footer();
    }

    window.print();
    // 完成操作后刷新当前详情页面
    window.location.href = window.location.href;
}

function remove_ie_header_and_footer() {
    var hkey_path;
    hkey_path = "HKEY_CURRENT_USER\\Software\\Microsoft\\Internet Explorer\\PageSetup\\";
    try {
        var RegWsh = new ActiveXObject("WScript.Shell");
        RegWsh.RegWrite(hkey_path + "header", "&w&b页码，&p/&P");
        RegWsh.RegWrite(hkey_path + "footer", "&u&b&d");
    } catch (e) {

    }
}

// 提示窗口打开关闭操作
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

// 关闭弹窗操作
function closeModel() {
    window.close();
    window.opener.location.href = window.opener.location.href;
}

// 刷新当前页面
function refreshPage() {
    window.location.href = window.location.href;
}

function ifEmpty(val) {
    if (val == "" || val == null || val == undefined || val == "NaN") {
        return true;
    }
    return false;
}

function deleteData(url) {
    // if (confirm("确定要删除该记录吗？删除后将不可恢复！")){
    layer.confirm("确定删除该数据吗？", {
        icon: 3,
        title: '提示'
    }, function (index) {
        layer.close(index);
        $.post(url, {}, function (data) {
            var jsonOb = eval("(" + data + ")");
            if (jsonOb.status == "success") {
                layer.alert("删除成功", {
                    icon: 1
                }, function () {
                    parent.location.reload();
                    layer.closeAll();
                });
            } else {
                layer.alert(jsonOb.msg, {
                    icon: 5
                });
            }
        });
    });
    // }
}

function popupBox(url) {
    var height = screen.availHeight * 0.7;
    window.open(url, "", "height="+height+",width=700,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left=" + (screen.availWidth - 700) / 2 + ",resizable=yes");
}

function pageOperate(url, type) {
    switch (type) {
        case 'add':
            openFullModel(url);
            break;
        case 'modify':
            openFullModel(url);
            break;
        case 'detail':
            openFullModel(url);
            break;
        case 'delete':
            deleteData(url);
            break;
        case 'approve':
            openFullModel(url);
            break;
        case 'smallModel':
            showDetail(url);
            break;
        case 'popupBox':
            popupBox(url);
            break;
        default:
            alert("操作请求错误！");
            break;
    }
}


// 校验表单数据
function CheckForm() {

    return true;
}

function insertData(obj) {
    if (!CheckForm()) {
        return false;
    }
    var sub = obj.value;
    var url = "insert_data.php?sub=" + encodeURIComponent(sub);
    $.post(url, $("#addForm").serializeArray(), function (data) {
        var result = eval('(' + data + ')');
        if (result.status == "success") {
            alert(sub + "成功");
            parent.location.reload();
            closeModel();
        } else {
            alert(sub + "失败");
            return false;
        }
    });
    return false;
}

function updateData(obj) {
    if (!CheckForm()) {
        return false;
    }
    var sub = obj.value;
    var url = "update_data.php?sub=" + encodeURIComponent(sub);
    $.post(url, $("#form1").serializeArray(), function (data) {
        var result = eval('(' + data + ')');
        if (result.status == "success") {
            alert(sub + "成功");
            parent.location.reload();
            closeModel();
        } else {
            alert(sub + "失败");
            return false;
        }
    });
    return false;
}

function approveDate(obj) {
    if (!CheckForm()) {
        return false;
    }
    var sub = obj.value;
    var url = "approve_data.php?sub=" + encodeURIComponent(sub);
    $.post(url, $("#form1").serializeArray(), function (data) {
        var result = eval('(' + data + ')');
        if (result.status == "success") {
            alert(sub + "成功");
            parent.location.reload();
            closeModel();
        } else {
            alert(sub + "失败");
            return false;
        }
    });
    return false;
}

/**
 * 全选/反选
 * @param {string} cbxAllNm 全选checkbox名称
 * @param {string} cbxItemNm 子checkbox名称
 * @returns {undefined}
 */
function cbxCheckAll(cbxAllNm, cbxItemNm) {
    $("input[name='" + cbxAllNm + "']").change(function () {
        var cbxAllIsChecked = $(this).attr("checked"); // true or false
        $("input[name='" + cbxItemNm + "']").each(function (i, item) {
            if (cbxAllIsChecked) {
                $(item).attr("checked", "checked");
            } else {
                $(item).removeAttr("checked");
            }
        });
    });
}

function createInterviewLink(id, userId) {
    var url = "/common/getInterviewInfo.php?userId="+userId;
    $.post(url, {}, function (data) {
        var myobj = eval('(' + data + ')');
        var linkHtml = "<a href=\"javascript:;\" onclick=\"openFullModel('/general/hr/manage/Interview/detail.php?flowId=" + myobj.flow_id + "&isLink=1');\">"
                    +"面试记录详情<font color=red>(点击查看)</font></a>&nbsp;";
        $("#"+id).append(linkHtml);
    });
}

function importData(templetName, formatType, tableName, isExtra) {
    var myleft = (screen.availWidth - 500) / 2;
    // 导入参数构造
    var dataType = "";
    var paras = "dataType=" + dataType + "&templetName=" + templetName + "&formatType=" + formatType + "&tableName=" + tableName + "&isExtra=" + isExtra;
    var url = "/common/excel/import/pre_import.php?" + paras;
    window.open(url, "", "height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=120,left=" + myleft + ",resizable=yes");
}

function importDataNew(templateName, formatType, tableName, isExtra, importUrl) {
    // 导入参数构造
    var dataType = "数据导入";
    // var templateName = "control_library_template";
    // var formatType = "3";
    // var tableName = "inhe_control_library";
    // var isExtra = 1;
    var paras = "dataType=" + dataType + "&templateName=" + templateName + "&formatType=" + formatType + "&tableName=" + tableName + "&isExtra=" + isExtra;
    // var url = "import/pre_import.php?" + paras;
    var url = importUrl + "?" + paras;

    var height = screen.availHeight * 0.5;
    window.open(url, "", "height=" + height + ",width=700,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left=" + (screen.availWidth - 700) / 2 + ",resizable=yes");
}

function closeIframe() {
    layui.use(['form', 'layer'], function () {
        var layer = layui.layer,
            form = layui.form;

        window.close();
        parent.layer.closeAll();

        form.render();
    });
}

function disableButton(obj,type){
    var DISABLED = 'layui-btn-disabled';
    if(type==false){
        obj.addClass(DISABLED); // 添加样式
        obj.attr('disabled', 'disabled');  // 添加属性
    }else{
        obj.removeClass(DISABLED); // 删除样式
        obj.removeAttr('disabled');  // 删除属性
    }
}