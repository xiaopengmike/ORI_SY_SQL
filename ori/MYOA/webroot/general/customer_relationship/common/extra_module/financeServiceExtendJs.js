jQuery.extend({
    /**
     * 数字增加千位符
     * @param {int|float} num 数字
     * @returns {String}
     */
    commafy: function (num) {
        num = num + "";
        var tempNum = num.split(".")[0];
        var re = /(-?\d+)(\d{3})/;
        while (re.test(tempNum)) {
            tempNum = tempNum.replace(re, "$1,$2");
        }
        return num.split(".").length > 1 ? tempNum + "." + num.split(".")[1] : tempNum;
    },
    /**
     * 获取托管业务线信息
     * @param {string} currObj 当前selectId
     * @param {string} childObj	下级selectId
     * @param {string} threeChildObj 三级selectId
     * @returns {undefined}
     */
    initCustodyBusinessProControl: function (currObj, childObj, threeChildObj) {
        var url = "apiextension/TgContract/GetProductList";
        $FMS.Ajax.ajaxproxypost(url, null, {"nodeName": "托管业务线"}, function (data) {
            if (data.Statu.Code === 200)
            {
                var currVal = $("#" + currObj).val();
                $("#" + currObj).empty();
                $("#" + currObj).append("<option value=''>--请选择--</option>");
                $.each(data.Data, function (i, val) {
                    $("#" + currObj).append("<option value='" + val + "'>" + val + "</option>");
                });
                $("#" + currObj + " option[value='" + currVal + "']").attr("selected", "selected");

                if (childObj !== undefined && currVal !== "") {
                    $("#" + currObj).change();
                }
            }
        });

        if (childObj !== undefined) {
            $("#" + currObj).change(function () {
                var childVal = $("#" + childObj).val();
                $("#" + childObj).empty();
                $("#" + childObj).append("<option value=''>--请选择--</option>");
                if ($(this).val() === "")
                {
                    if (threeChildObj !== undefined) {
                        $("#" + threeChildObj).empty();
                        $("#" + threeChildObj).append("<option value=''>--请选择--</option>");
                    }
                    return;
                }
                $FMS.Ajax.ajaxproxypost(url, null, {"nodeName": $(this).val()}, function (data) {
                    if (data.Statu.Code === 200)
                    {
                        $.each(data.Data, function (i, val) {
                            $("#" + childObj).append("<option value='" + val + "'>" + val + "</option>");
                        });
                        $("#" + childObj + " option[value='" + childVal + "']").attr("selected", "selected");

                        if (threeChildObj !== undefined) {
                            if ($("#" + childObj).val() === "") {
                                $("#" + threeChildObj).empty();
                                $("#" + threeChildObj).append("<option value=''>--请选择--</option>");
                            } else {
                                $("#" + childObj).change();
                            }
                        }
                    }
                });
            });
        }

        if (threeChildObj !== undefined) {
            $("#" + childObj).change(function () {
                var threeChildVal = $("#" + threeChildObj).val();
                $("#" + threeChildObj).empty();
                $("#" + threeChildObj).append("<option value=''>--请选择--</option>");
                if ($(this).val() === "") {
                    return;
                }
                $FMS.Ajax.ajaxproxypost(url, null, {"nodeName": $(this).val()}, function (data) {
                    if (data.Statu.Code === 200)
                    {
                        $.each(data.Data, function (i, val) {
                            $("#" + threeChildObj).append("<option value='" + val + "'>" + val + "</option>");
                        });
                        $("#" + threeChildObj + " option[value='" + threeChildVal + "']").attr("selected", "selected");
                    }
                });
            });
        }
    },
    /**
     * 初始化指令状态下拉框
     * @param {string} currObj 元素ID
     * @returns {undefined}
     */
    initStatusControl:function(currObj){
        var statusArr = [{key: "--请选择--", value: ""},{key: "待修正", value: "0"},{key: "待金机转发", value: "1"},
            {key: "待接受", value: "3"},{key: "已接受", value: "4"},{key: "已作废", value: "5"},{key: "已结束", value: "6"},{key: "已删除", value: "9"}];
        $.each(statusArr, function (i, item) {
            $("#" + currObj).append("<option value='" + item.value + "'>" + item.key + "</option>");
        });
    },
    /**
     * 初始化交易类型下拉框
     * @param {type} currObj 元素ID
     * @returns {undefined}
     */
    initPayTypeControl:function(currObj){
        var payTypeArr = [{groupName: "付费", values: [{key: "付费"}, {key: "托管费"}, {key: "佣金"}]},
            {groupName: "银行间交易", values: [{key: "成交单"}, {key: "DVP调入"}, {key: "DVP调出"}, {key: "DVP分销"}]},
            {groupName: "资金划转", values: [{key: "申购款"}, {key: "赎回款"}, {key: "划款"}, {key: "银期转账"}, {key: "收款"}, {key: "转债申购"}, {key: "境外转账"}, {key: "三方存管"}, {key: "待遇支付"}]},
            {groupName: "投资", values: [{key: "新股（债）申购"}, {key: "定存"}, {key: "普通分销"}, {key: "基金申购"}, {key: "换汇"}, {key: "跨境汇出"}, {key: "转托管"}, {key: "分红"}, {key: "基金赎回"}, {key: "其他投资资产申购"}]},
            {groupName: "跨境", values: [{key: "跨境汇入"}, {key: "跨境本金汇出"}, {key: "跨境收益汇出"}, {key: "债券兑息或兑付"}, {key: "交易所T+0买入"}, {key: "交易所T+0卖出"}, {key: "支付税款"}, {key: "证券份额确认"}, {key: "内部转账"}]}];
        $.each(payTypeArr, function (i, item) {
            $("#"+currObj).append("<optgroup label='" + item.groupName + "'></optgroup>");
            $.each(item.values, function (j, cItem) {
                $("#"+currObj+" optgroup").eq(i).append("<option value='" + cItem.key + "'>" + cItem.key + "</option>");
            });
        });
    },
    /**
     * 
     * @param {string} sDate 开始时间
     * @param {string} eDate 结束时间
     * @param {int} stepNum 0:支付转发  1：金机转发  2：清算接收
     * @returns {String}
     */
    getOpTimeCheck: function (sDate, eDate, stepNum) {
        //预设处理时间（分钟）
        var arrSetTime = new Array(30, 30, 30);

        var startDate = new Date(sDate.replace(/-/g, "/"));
        var num = startDate.getTime();

        var endDate;
        if (eDate !== "") {
            endDate = new Date(eDate.replace(/-/g, "/"));
        } else {
            endDate = new Date();
        }
        var endNum = endDate.getTime();

        //累计相差秒数
        var val = Math.floor((endNum - num) / 1000);

        //计算相差天数
        var days = Math.floor(val / (24 * 3600));

        //计算出小时数
        var leave1 = val % (24 * 3600);
        var hours = Math.floor(leave1 / 3600);

        //计算分钟
        var leave2 = leave1 % 3600;
        var minutes = Math.floor(leave2 / 60);

        //计算秒数
        var seconds = Math.round(leave2 % 60);

        var retStr = "（" + (days > 0 ? days + "天" : "") + (hours > 0 ? hours + "小时" : "") + (minutes > 0 ? minutes + "分" : "") + (seconds > 0 ? seconds + "秒" : "0秒") + "）";
        retStr = days > 0 || hours > 0 || minutes > arrSetTime[stepNum] ? "<span style='background-color: red;color: blanchedalmond'>" + retStr + "</span>" : "<span style='background-color: green;color: blanchedalmond'>" + retStr + "</span>";
        return retStr;
    },
    /**
     * 日期展示处理
     * @param {type} createDate
     * @returns {String}
     */
    shortDate: function (createDate) {
        var retStr = "";
        var arrDate = createDate.split(" ");
        var currDate = new Date().Format("yyyy-MM-dd");
        if (arrDate[0] === currDate) {
            retStr = arrDate.length > 1 ? "T日 " + arrDate[1].substr(0, 5) : "T日";
        } else {
            retStr = createDate.substr(0, 16);
        }
        return retStr;
    },
    /**
     * 时间取日期展示处理
     * @param {type} retTime
     * @returns {String}
     */
    retDate: function (retTime) {
        var retStr = "";
        var arrDate = retTime.split(" ");
        retStr = arrDate[0];

        return retStr;
    },
    /**
     * 数据有效性验证
     * @param {string} payDate 支付日期(yyyy-MM-dd)
     * @returns {string}
     */
    getDataValid: function (payDate) {
        var currDate = new Date().Format("yyyy-MM-dd");
        return currDate <= payDate ? true : false;
    },
    /**
     * 计算两个日期间相差的时间
     * @param {string} sDate 开始日期
     * @param {string} eDate 结束日期
     * @returns {string}
     */
    getMinuts: function (sDate, eDate) {
        var startDate = new Date(sDate.replace(/-/g, "/"));
        var num = startDate.getTime();

        var endDate;
        if (eDate !== "") {
            endDate = new Date(eDate.replace(/-/g, "/"));
        } else {
            endDate = new Date();
        }
        var endNum = endDate.getTime();

        //累计相差秒数
        var val = Math.floor((endNum - num) / 1000);

        //计算相差天数
        var days = Math.floor(val / (24 * 3600));

        //计算出小时数
        var leave1 = val % (24 * 3600);
        var hours = Math.floor(leave1 / 3600);

        //计算分钟
        var leave2 = leave1 % 3600;
        var minutes = Math.floor(leave2 / 60);

        //计算秒数
        var seconds = Math.round(leave2 % 60);

        var retStr = "累计耗时：" + (days > 0 ? days + "天" : "") + (hours > 0 ? hours + "小时" : "") + (minutes > 0 ? minutes + "分" : "") + (seconds > 0 ? seconds + "秒" : "0秒");
        return retStr;
    },
    /**
     * 获取指令状态描述
     * @param {int} status
     * @returns {String}
     */
    getStatusDesc: function (status) {
        var statusDesc = "";
        var statusArr = new Array("待修正", "待金机转发", "待支付转发", "待接受", "已接受", "已作废", "已结束", "已删除");
        statusDesc = status < 7 ? statusArr[status] : statusArr[statusArr.length - 1];
        statusDesc = status === 4 || status === 6 ? "<span style='color: blanchedalmond'>" + statusDesc + "</span>" : statusDesc;
        return statusDesc;
    },
    /**
     * 展示相同记录信息
     * @param {type} obj
     * @param {type} id
     * @param {type} assetCode
     * @param {type} payDate
     * @param {type} payMoney
     * @param {type} payType
     * @returns {undefined}
     */
    showRepeatInfo: function (obj, id, assetCode, payDate, payMoney, payType) {
        var params = "id=" + id + "&assetCode=" + assetCode + "&payDate=" + payDate + "&payMoney=" + payMoney + "&payType=" + encodeURI(payType);
        $FMS.Ajax.ajaxproxypost("apiextension/JrInstructForward/GetRepeatList?" + params, "form_instruct", {}, function (data) {
            if (data.Statu.Code === 200 && data.Data.length > 0) {
                var html = "<div class='category'>共【" + data.Data.length + "】条相同记录:</div>";
                $.each(data.Data, function (i, val) {
                    html += "<div class='category'>" + (i + 1) + "、指令编号【" + val + "】</div>";
                });
                $(obj).attr("title", html);
                $(obj).tooltip();
            }
        });
    },
    /**
     * 全选/反选
     * @param {string} cbxAllNm 全选checkbox名称
     * @param {string} cbxItemNm 子checkbox名称
     * @returns {undefined}
     */
    cbxCheckAll: function (cbxAllNm, cbxItemNm) {
        $("input[name='" + cbxAllNm + "']").change(function () {
            var cbxAllIsChecked = $(this).attr("checked");
            $("input[name='" + cbxItemNm + "']").each(function (i, item) {
                if (cbxAllIsChecked === "checked") {
                    $(item).attr("checked", "checked");
                } else {
                    $(item).removeAttr("checked");
                }
            });
        });
    },
    /**
     * 生成附件列表
     * @param {type} fmt
     * @returns {unresolved}
     */
    loadAttach: function (attachList,containId){
        if(attachList.length == 0) return;
        var htmlArr = new Array();
        $(attachList).each(function(i,s){
            htmlArr.push('<div class="jquery-upload-item" id="' + s.Id + '"><img src="./images/attach.bmp" align="absmiddle">');
            htmlArr.push('<a href="javascript:void(0);" url="' + s.Aname + '" target="_blank" delid="' + s.Id + '" onclick="DownLoadFileProxy(event,this);">' + s.Aname + '</a> | ' + (s.Alength/1024).toFixed(2) + ' KB ');
            htmlArr.push('</div>');
        });
        $("#"+containId).append(htmlArr.join(""));
    },
    checkPhoneNum: function (obj){
	 var reg = new RegExp("^[0-9]*$");
	 var value = $(obj).val().replace(/,/g, "");
	 if(!reg.test(value)){
		alert("非法的联系方式");
		window.setTimeout(function(){$(obj).focus();$(obj).val("");},0);
	 }
    }
});

Date.prototype.Format = function (fmt) {
    var o = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "H+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S": this.getMilliseconds()
    };
    if (/(y+)/.test(fmt)) {
        fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    }
    for (var k in o) {
        if (new RegExp("(" + k + ")").test(fmt)) {
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        }
    }
    return fmt;
};

