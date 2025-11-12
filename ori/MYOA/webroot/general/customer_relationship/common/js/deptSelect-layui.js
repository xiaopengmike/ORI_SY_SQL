    if(!Array.indexOf){
        Array.prototype.indexOf=function(obj){
            for(var i=0;i<this.length;i++){
                if(this[i]==obj){
                    return i;
                }
            }
            return -1;
        }
    }   

/**
 * 业务经理系统下拉组件2.0版
 *  -2016.07.15更新内容
 *  1.后台代码优化。
 *  2.添加了权限控制开关。
 * -2016.06.27更新内容
 *  1.部门组件使用接口取数，与php服务器解耦。
 *  2.接口的同步调用改为异步调用防止阻塞ui线程。
 */


(function ($) {
    jQuery.fn.extend({
        newDeptSelect: function (opts) {
            opts = jQuery.extend({
                selectLevel: 3, //下拉菜单级别数
                selectName: "", //下拉菜单提交名
                isMultipleSelection: false, //是否是多选下拉框
                MultipleSelectDivId: "", //多选型下拉框存值divid
                deptType: "", //部门类别,只限制第一级菜单的部门类别
                selectValue: "", //初始化值
                isEmploy: false, //是否选取必须聘任内控副行长的机构：
                isResumption: false, //是否只选取必须统计业务经理
                hasSearchPosition: false, //是否包含按职位搜索
                hasGroup: false, //是否包含群组
                permissionPartyOrgId:"",//权限内党组织id
                serveQualification: "", //需要筛选人员的资格使用逗号分隔
                isDetailMode: false, //是否是详情展示模式
                preFunc: function () {
                    return false;
                },
                nextFunc: function (obj, selectedJsonObj) {
                    return true;
                },
                hasRoot: false//第一级是否需要包含深圳分行
            }, opts || {});

            //执行预处理方法
            opts.preFunc();

            /*** 值初始化 ***/

            //下拉菜单的元素集合数组
            var levelSelectJsonObjArray = new Array();
            //多级下拉菜单id数组
            var selectIdArray = new Array();
            //下拉菜单容器id数组
            var spanIdArray = new Array();
            //查找input框的id
            var searchInputId = "search" + Math.round(Math.random() * 10000);
            //查找按钮的id
            var searchButtonId = "searchButton" + Math.round(Math.random() * 10000);
            //添加按钮的id
            var addButtonId = "addButton" + Math.round(Math.random() * 10000);
            //被选中的option的详细数据，json格式
            var selectedJsonObj = [];
            //多选框选中值name的前缀字符串
            var multipleSelectName = "";
            var rootDeptIds=Array();

            selectIdArray[0] = "selectId" + Math.round(Math.random() * 10000);
            spanIdArray[0] = $(this).attr('id');
            //多选框存值id
            var selectUserDiv = ""
            //判断是否是多选型下拉框
            if (opts.isMultipleSelection) {
                //若有传name则给添加到多选框中的元素的nama属性加前缀
                if (opts.selectName != "") {
                    multipleSelectName = opts.selectName + "_";
                } else {
                    //对于没有穿name的设置selectName的默认值
                    opts.selectName = "selectDeptName";
                }
                //给多选型下拉框的选中值div赋默认值
                if (opts.MultipleSelectDivId == "") {
                    selectUserDiv = multipleSelectName + "selectUserDiv";
                } else {
                    selectUserDiv = opts.MultipleSelectDivId;
                }
            }

            //选中值数组
            var initArray = "";

            /*** 根目录下拉菜单初始化 ***/
            if (opts.isDetailMode) {
                detailModeRootIni();
            } else {
                commonModeRootIni();
            }


            /*** 下拉组件初始化 ***/
            function commonModeRootIni() {
                /*** 下拉菜单选中值初始化 ***/


                $("#" + spanIdArray[0]).html('<img src="images/loading01.gif" align="absmiddle" />正在装载...');
                getDeptEhrInfo('3200000022', 1, "", function (returnJson) {
                    //根目录过滤器
                    if (opts.hasSearchPosition) {
                        addSearchEhrPositionOption(returnJson);
                    }
                    if (opts.selectLevel == 1) {
                        //添加请选择option
                        addEmptyOption(returnJson)
                    } else if (opts.selectLevel == 2) {
                        //添加按部门搜索option
                        addSearchDeptOption(returnJson);
                        //添加请选择option
                        addEmptyOption(returnJson)
                    } else if (opts.selectLevel == 3) {
                        //添加按部门搜索option
                        addSearchDeptOption(returnJson);
                        //添加按人员搜索option
                        addSearchEhrOption(returnJson);
                        //添加请选择option
                        addEmptyOption(returnJson);
                    }
                    if (!opts.hasRoot) {
                        rootDeptFilter(returnJson);
                    }

                    
                    rootDeptIds=returnJson.upDetpIds;
                    if ( rootDeptIds.indexOf(opts.selectValue) !=-1) {
                        initArray = Array();
                        initArray.push(opts.selectValue);
                    } else if (opts.selectValue != "") {
                        initArray = initData(opts.selectValue);
                        for(var i=0;i<rootDeptIds.length;i++){
                            var tempIndex=initArray.indexOf(rootDeptIds[i]);
                            initArray=initArray.splice(tempIndex+1,initArray.length-tempIndex-1);
                        }
                    }
                    buildSelectHtml(returnJson, 1);
                    var innerHtml = '<input type="button" style="display:none"   id="' + searchButtonId + '" value="搜索" />';
                    if (opts.isMultipleSelection) {
                        innerHtml = innerHtml + '<input type="button"  id="' + addButtonId + '" value="添加" />';
                    }

                    $("#" + spanIdArray[0]).append(innerHtml);
                    $("#" + searchButtonId).click(function () {
                        searchButtonIdEvent();
                    })
                    $("#" + addButtonId).click(function () {
                        addUser();
                    })
                    $("#" + selectIdArray[0]).attr("name", opts.selectName);
                    opts.nextFunc(this, selectedJsonObj);
                });
            }

            /*** 用于详情页面的组件初始化 ***/
            function detailModeRootIni() {
                $("#" + spanIdArray[0]).html('<img src="images/loading01.gif" align="absmiddle" />正在装载...');
                if (opts.selectLevel == 3) {
                    getDeptEhrInfo('', 3, opts.selectValue, function (returnJson) {
                        levelSelectJsonObjArray[0] = returnJson
                        selectedJsonObj = levelSelectJsonObjArray[0].data[0];
                        $("#" + spanIdArray[0]).html(selectedJsonObj.name);
                        opts.nextFunc(this, selectedJsonObj);
                    })
                } else if (opts.selectLevel == 2) {
                    getDeptEhrInfo('', 2, opts.selectValue, function (returnJson) {
                        levelSelectJsonObjArray[0] = returnJson
                        selectedJsonObj = levelSelectJsonObjArray[0].data[0];
                        $("#" + spanIdArray[0]).html(selectedJsonObj.name);
                        opts.nextFunc(this, selectedJsonObj);
                    });
                } else {
                    getDeptEhrInfo('', 1, opts.selectValue, function (returnJson) {
                        levelSelectJsonObjArray[0] = returnJson
                        selectedJsonObj = levelSelectJsonObjArray[0].data[0];
                        $("#" + spanIdArray[0]).html(selectedJsonObj.name);
                        opts.nextFunc(this, selectedJsonObj);
                    });
                }
            }


            /**
             * 下拉菜单change事件
             */
            function commonSelectChange(obj) {
                opts.preFunc();
                var selectLevel = $(obj).attr('selectLevel');
                var selectedOption = $(obj).find("option:selected");
                var dataLevel = $(selectedOption).attr("dataLevel");
                var dataType = $(selectedOption).attr("dataType");
                var selectValue = $(selectedOption).val();


                var rootSelectedOption = $("#" + selectIdArray[0]).find("option:selected");
                var rootDataLevel = $(rootSelectedOption).attr("dataLevel");

                var parentObj = $("#" + selectIdArray[selectLevel * 1 - 2]);
                var parentObjValue = $(parentObj).find("option:selected").val();
                if (dataLevel != opts.selectLevel && dataType != "groupInfo" && dataType != "positionInfo" && selectValue != "" && parentObjValue != selectValue) {
                    $("#" + spanIdArray[selectLevel]).html('<img src="images/loading01.gif" align="absmiddle" />正在装载...');
                }
                if (dataLevel != opts.selectLevel && dataType != "groupInfo" && dataType != "positionInfo" && selectValue != '3200000022') {
                    if (dataLevel == "-1") {
                        $("#" + searchButtonId).removeAttr("style");
                        buildSearchInput();
                    } else if (dataType != "groupInfo") {
                        if (rootDataLevel != "-1") {
                            $("#" + searchButtonId).attr("style", "display:none");
                        }
                        var returnJson = {};
                        if (parentObjValue == selectValue || rootDeptIds.indexOf(selectValue)!=-1) {
                            if (dataLevel * 1 + 2 <= opts.selectLevel) {
                                getDeptEhrInfo(selectValue, dataLevel * 1 + 2, "", function (returnJson) {
                                    addEmptyOption(returnJson);
                                    buildSelectHtml(rootDeptFilter(returnJson), selectLevel * 1 + 1);
                                });
                            }else{
                                $("#" + spanIdArray[selectLevel]).html("");
                            }
                        } else {
                            getDeptEhrInfo(selectValue, dataLevel * 1 + 1, "", function (returnJson) {
                                addEmptyOption(returnJson);
                                buildSelectHtml(rootDeptFilter(returnJson), selectLevel * 1 + 1);
                            });
                        }
                    }
                } else {
                    $("#" + spanIdArray[selectLevel]).html("");
                    if (dataType == "groupInfo") {
                        $("#" + searchButtonId).attr("style", "display:none");
                    }
                }
                moveNameAttr(obj, opts.selectName);

                //获取被name标识的option
                var selectedObj = $("#" + spanIdArray[0]).find("[name='" + opts.selectName + "']");
                var selectedObjLevel = $(selectedObj).attr("selectLevel");
                var selectedObjValue = $(selectedObj).val();
                //构造被选中的元素信息json数组
                selectedJsonObj = [];

                //查找到选中值的json
                if (selectedObjValue != null && selectedObjValue != "") {
                    if (levelSelectJsonObjArray[selectedObjLevel - 1] != null) {
                        for (var i = 0; i < levelSelectJsonObjArray[selectedObjLevel - 1].data.length; i++) {
                            var json = levelSelectJsonObjArray[selectedObjLevel - 1].data[i];
                            if (json != null) {
                                if (json.id == selectedObjValue) {
                                    selectedJsonObj = levelSelectJsonObjArray[selectedObjLevel - 1].data[i]
                                    break;
                                }
                            }
                        }
                    }
                }
                opts.nextFunc(obj, selectedJsonObj);

            }

            /**
             * 创建搜索框方法
             */
            function buildSearchInput() {
                selectIdArray[1] = searchInputId;
                spanIdArray[2] = "span" + Math.round(Math.random() * 10000);
                var inputInnerHtml = '<input class="layui-input" style="width:150px;float:left" id="' + searchInputId + '" /><span id="' + spanIdArray[2] + '"></span>';
                $("#" + spanIdArray[1]).html(inputInnerHtml);
            }

            /**
             * 搜索按钮事件
             */
            function searchButtonIdEvent() {

                var searchKey = $("#" + searchInputId).val();
                var rootSelectedDataType = $("#" + selectIdArray[0]).find("option:selected").attr("datatype");
                $("#" + spanIdArray[2]).html('<img src="images/loading01.gif" align="absmiddle" />正在装载...');
                if (searchKey != "") {

                    var returnJson = "";
                    if (rootSelectedDataType == "searchEhr") {
                        getDeptEhrInfo('', 3, searchKey, function (returnJson) {
                            convertToDetailName(returnJson);
                            addEmptyOption(returnJson);
                            buildSelectHtml(returnJson, 3);
                            if (returnJson.data.length == 1) {
                                alert("无符合条件的人员");
                            }
                        });
                    } else if (rootSelectedDataType == "searchDept") {
                        getDeptEhrInfo('', 2, searchKey, function (returnJson) {
                            rootDeptFilter(returnJson);
                            addEmptyOption(returnJson);
                            buildSelectHtml(returnJson, 3);
                        });
                    } else if (rootSelectedDataType == "searchPosition") {
                        getDeptEhrInfo('searchPosition', 2, searchKey, function (returnJson) {
                            rootDeptFilter(returnJson);
                            addEmptyOption(returnJson);
                            buildSelectHtml(returnJson, 3);
                        });
                    }
                } else {

                    $("#" + spanIdArray[2]).html('');
                }
                $("#" + selectIdArray[0]).removeAttr("name");
            }

            /**
             * 获取下拉菜单数据
             * @param string id 下拉菜单值
             * @param {type} dataLevel 下拉菜单数据级别
             * @param {type} searchKey 模糊查询值
             * @returns json 下拉菜单数据json数组对象
             */
            function getDeptEhrInfo(id, dataLevel, searchKey, func) {
                var getUrl = 'sf_evaluate_new/select-data/get-dept-select?id=' + id + '&&dataLevel=' + dataLevel + '&&searchKey=' + searchKey;
                if (opts.hasGroup) {
                    getUrl = getUrl + '&&hasGroup=1';
                }
                if (opts.deptType != "" && dataLevel == 1) {
                    getUrl = getUrl + '&&deptType=' + opts.deptType;
                }
                if (opts.isEmploy != "" && opts.isEmploy) {
                    getUrl = getUrl + '&&isEmploy=1';
                }
                if (opts.isResumption != "" && opts.isResumption) {
                    getUrl = getUrl + '&&isResumption=1';
                }

                if (opts.permissionPartyOrgId != "") {
                    getUrl = getUrl + '&&permissionPartyOrgId=' + opts.permissionPartyOrgId;
                }

                if (id == "searchPosition") {
                    getUrl = getUrl + '&&searchPosition=1';
                }
                if (opts.serveQualification != "") {
                    getUrl = getUrl + '&&serveQualification=' + opts.serveQualification;
                }
                $.ajax({
                    url: getUrl,
                    cache: false,
                    async: true,
                    type: "GET",
                    data: "",
                    datatype: "json",
                    success: function (result) {
                        var jsonObj = JSON.parse(result);
                        func(jsonObj);
                    }
                })

            }

            /**
             * 获取选中值的下拉菜单数组
             */
            function initData(selectedValue) {
                var getUrl = 'sf_evaluate_new/select-data/init-select-data?selectedValue=' + selectedValue + '&&selectLevel=' + opts.selectLevel;
                if (opts.permissionPartyOrgId != "") {
                    getUrl = getUrl + '&&permissionPartyOrgId=' + opts.permissionPartyOrgId;
                }

                var resultJson = "";
                $.ajax({
                    url: getUrl,
                    cache: false,
                    async: false,
                    type: "GET",
                    data: "",
                    datatype: "json",
                    success: function (result) {
                        resultJson = JSON.parse(result);
                    }
                })
                return resultJson;
            }

            /**
             * 通过json数据build出下拉菜单html
             * @param json jsonData 下拉菜单json数组
             * @param {type} selectLevel 需要创造的下拉菜单级别
             * @returns {String} 返回html字符串
             */
            function buildSelectHtml(jsonData, selectLevel) {
                selectIdArray[selectLevel - 1] = "selectId" + Math.round(Math.random() * 10000);
                spanIdArray[selectLevel] = "span" + Math.round(Math.random() * 10000);
                var innerHtml = "";

                if (jsonData.length != 0) {
                    innerHtml = innerHtml + '<select  id="' + selectIdArray[selectLevel - 1] + '" selectLevel=' + selectLevel + ' width="30%">';
                    for (var i = 0; i < jsonData.data.length; i++) {
                        if (jsonData.data[i] != null) {
                            if (jsonData.data[i].dataLevel == "-1") {
                                innerHtml = innerHtml + '<option style="color: #A20030" dataLevel= "' + jsonData.data[i].dataLevel + '" value="' + jsonData.data[i].dataType + '" dataType="' + jsonData.data[i].dataType + '">' + jsonData.data[i].name + '</option>';
                            } else {
                                innerHtml = innerHtml + '<option dataType="' + jsonData.data[i].dataType + '" dataLevel= "' + jsonData.data[i].dataLevel + '" value="' + jsonData.data[i].id + '">' + jsonData.data[i].name + '</option>';
                            }
                        }
                    }
                    innerHtml = innerHtml + '</select><span id="' + spanIdArray[selectLevel] + '"></span>';
                }
                $("#" + spanIdArray[selectLevel - 1]).html(innerHtml);
                
                
                $("#" + selectIdArray[selectLevel - 1]).change(function () {
                    commonSelectChange(this);
                })
                levelSelectJsonObjArray[selectLevel - 1] = jsonData;
                if (initArray != "" && initArray.length > selectLevel - 1 && initArray[selectLevel - 1] != "") {
                    $("#" + selectIdArray[selectLevel - 1]).val(initArray[selectLevel - 1]);
                    $("#" + selectIdArray[selectLevel - 1]).change();
                }
                layui.use('form', function(){
                    var form = layui.form;
                    form.render();
                    form.on('select',function(o){
                        $(o.elem).change();
                    })
                });
                
                return true;
            }

            /**
             * 在菜单变化时移动name属性到正确级别的select上
             * @param obj 变化菜单的js对象
             * @param string name 下拉菜单的name属性
             */
            function moveNameAttr(obj, name) {
                var selectLevel = $(obj).attr("selectLevel");
                var objValue = $(obj).val();
                var parentObj = "";
                var rootObj = "";

                //找到上级select对象
                if (selectLevel == "1") {
                    parentObj = $("#" + selectIdArray[0]);
                } else {
                    rootObj = $("#" + selectIdArray[0]);
                    parentObj = $("#" + selectIdArray[selectLevel * 1 - 2]);
                }
                if (objValue == "") {
                    //判断是否是根目录
                    if (selectLevel != "1") {
                        if (parentObj.parent().find("input").length > 0) {
                            $(rootObj).attr("name", name);
                            $(obj).removeAttr("name");
                        } else {
                            $(parentObj).attr("name", name);
                            $(obj).removeAttr("name");
                        }
                    } else {
                        $(obj).attr("name", name);
                    }
                } else {
                    $(parentObj).removeAttr("name", name);
                    $(obj).attr("name", name);
                }
            }

            /**
             * 添加员工、部门或群组
             */
            function addUser() {
                var currentSelect = $("[name='" + opts.selectName + "']");
                var userValue = $(currentSelect).val();
                if (userValue == null || userValue == "" || userValue == "searchEhr" || userValue == "searchDept" || userValue == "searchPosition" || $(currentSelect).is('input')) {
                    alert("请选择部门、群组、或人员");
                    return;
                }
                if ($("nobr").find("[value=" + userValue + "]").length != 0) {
                    alert("已添加该部门、群组、或人员");
                    return;
                }

                var innerHtml = "";
                var userType = $(currentSelect).find("option:selected").attr("dataType");
                if (userType == "deptInfo") {
                    innerHtml = "<span class='stl-item'><nobr><a class='stl-item-link' href='javascript:void(0)'> " + $(currentSelect).find("option:selected").text() + "</a>" +
                        "<input  name='" + multipleSelectName + "fillPowerDeptId[]' class='stl-item-hidden-field-c' value='" + userValue + "' type='hidden'><img class='stl-remove-img' src='images/remove.gif' onclick='javascript:$(this).parent().parent().remove();' align='absMiddle' height='12' width='12'></nobr>"
                        + "</span>";
                } else if (userType == "ehrInfo") {
                    innerHtml = "<span class='stl-item'><nobr><a class='stl-item-link' href='javascript:void(0)'> " + $(currentSelect).find("option:selected").text() + "</a>" +
                        "<input  name='" + multipleSelectName + "fillPowerEhrId[]' class='stl-item-hidden-field-c' value='" + userValue + "' type='hidden'><img class='stl-remove-img' src='images/remove.gif' onclick='javascript:$(this).parent().parent().remove();' align='absMiddle' height='12' width='12'></nobr>"
                        + "</span>";
                } else if (userType == "groupInfo") {
                    innerHtml = "<span class='stl-item'><nobr><a class='stl-item-link' href='javascript:void(0)'> " + $(currentSelect).find("option:selected").text() + "</a>" +
                        "<input  name='" + multipleSelectName + "fillPowerGroupId[]' class='stl-item-hidden-field-c' value='" + userValue + "' type='hidden'><img class='stl-remove-img' src='images/remove.gif' onclick='javascript:$(this).parent().parent().remove();' align='absMiddle' height='12' width='12'></nobr>"
                        + "</span>";
                } else {
                    innerHtml = "<span class='stl-item'><nobr><a class='stl-item-link' href='javascript:void(0)'> " + $(currentSelect).find("option:selected").text() + "</a>" +
                        "<input  name='" + multipleSelectName + "fillPowerPositionName[]' class='stl-item-hidden-field-c' value='" + userValue + "' type='hidden'><img class='stl-remove-img' src='images/remove.gif' onclick='javascript:$(this).parent().parent().remove();' align='absMiddle' height='12' width='12'></nobr>"
                        + "</span>";
                }

                //加载多选判断方法
                var flag = opts.nextFunc(currentSelect, selectedJsonObj);
                if (flag === true) {
                    $("#" + selectUserDiv).append(innerHtml);
                } else {
                    alert(flag);
                }
            }


            /*** 自定义数据过滤器 ***/

            //删除json数据中的深圳市分行
            function rootDeptFilter(jsonData) {
                if (jsonData.length != 0) {
                    for (var i = 0; i < jsonData.data.length; i++) {
                        if (jsonData.data[i].id == '3200000022') {
                            delete jsonData.data[i];
                        }
                    }
                }
                return jsonData;
            }

            //添加请选择option
            function addEmptyOption(jsonData) {
                if (jsonData.length != 0) {
                    var optionObj = { "id": "", "name": "请选择" };
                    jsonData.data.splice(0, 0, optionObj);
                }
            }

            //添加按部门或机构号搜索option
            function addSearchDeptOption(jsonData) {
                if (jsonData.length != 0) {
                    var optionObj = { "id": "", "dataType": "searchDept", "dataLevel": "-1", "name": "<按部门名称或五位机构号搜索>" };
                    jsonData.data.splice(0, 0, optionObj);
                }
            }

            //添加按人员名称或者工号搜索
            function addSearchEhrOption(jsonData) {
                if (jsonData.length != 0) {
                    var optionObj = { "id": "", "dataType": "searchEhr", "dataLevel": "-1", "name": "<按人员名称或者分行工号搜索>" };
                    jsonData.data.splice(0, 0, optionObj);
                }
            }
            //添加按职位搜索
            function addSearchEhrPositionOption(jsonData) {
                if (jsonData.length != 0) {
                    var optionObj = { "id": "", "dataType": "searchPosition", "dataLevel": "-1", "name": "<搜索职位>" };
                    jsonData.data.splice(0, 0, optionObj);
                }
            }

            //将人员json数组对象的name转换成名称和部门的形式
            function convertToDetailName(jsonData) {
                for (var i = 0; i < jsonData.data.length; i++) {
                    if (jsonData.data[i].addition.dept_name != null) {
                        jsonData.data[i].name = jsonData.data[i].addition.person_name + "<" + jsonData.data[i].addition.dept_name.replace(/\//, "-") + ">";

                    }
                }
            }
        }
    });
})(jQuery);