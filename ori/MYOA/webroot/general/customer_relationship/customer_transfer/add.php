<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("lang.php");
$HTML_PAGE_TITLE = _($LG_CUSTOMER_DATA['客户信息共享'][$_COOKIE['LANG']]);
include_once("inc/header.inc.php");
include_once("common/Common.php");
include_once("../common/CommonMethodModel.php");
include_once("../common/DataModel.php");
require_once("../common/UserModel.php");
$dataModel = new DataModel();
$userModel = new UserModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$systemId = "customer_transfer";
$adminData = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $adminData['isAdmin'];
$systemAdmin = $adminData['systemAdmin'];

if (empty($company)) {
    $company = $userModel->getUserBu($deptId);
}

$continentArr = $dataModel->getContinentData();
$countryArr = $dataModel->getCountryData();
$userInfo = $userModel->getUserInfo($userId);
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'customer_type,project_level,customer_source,customer_transfer_type'
);
$selectArr = $dataModel->getCommonParam($selectParam);



?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
<script language="Javascript" type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
<script language="Javascript" type="text/javascript" src="main.js"></script>
<script language="Javascript" type="text/javascript" src="../add.js"></script>
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="<?= COMMON_FILE_URL ?>/common.css">
<script>
// // 重写map方法，解决ie10及以下模式不兼容map对象和Set对象
function isIE() { //ie?

if (!!window.ActiveXObject || "ActiveXObject" in window)
    return true;
else
    return false;
}


//如果是 ie 则添加 Map 与 ?Set类
if(isIE()){
//实现Map
function Map(){
    this.elements = new Array();
    //获取Map元素个数
    this.size = function() {
        return this.elements.length;
    },
        //判断Map是否为空
        this.isEmpty = function() {
            return (this.elements.length < 1);
        },
        //删除Map所有元素
        this.clear = function() {
            this.elements = new Array();
        },
        //向Map中增加元素（key, value)?
        this.put = function(_key, _value) {
            if (this.containsKey(_key) == true) {
                if(this.containsValue(_value)){
                    if(this.remove(_key) == true){
                        this.elements.push( {
                            key : _key,
                            value : _value
                        });
                    }
                }else{
                    this.elements.push( {
                        key : _key,
                        value : _value
                    });
                }
            } else {
                this.elements.push( {
                    key : _key,
                    value : _value
                });
            }
        },
        //删除指定key的元素，成功返回true，失败返回false
        this.remove = function(_key) {
            var bln = false;
            try {
                for (i = 0; i < this.elements.length; i++) {
                    if (this.elements[i].key == _key){
                        this.elements.splice(i, 1);
                        return true;
                    }
                }
            }catch(e){
                bln = false;
            }
            return bln;
        },
        //向Map中增加元素（key, value)?
        this.set = function(_key, _value) {
            if (this.containsKey(_key) == true) {
                if(this.containsValue(_value)){
                    if(this.remove(_key) == true){
                        this.elements.push( {
                            key : _key,
                            value : _value
                        });
                    }
                }else{
                    this.elements.push( {
                        key : _key,
                        value : _value
                    });
                }
            } else {
                this.elements.push( {
                    key : _key,
                    value : _value
                });
            }
        },
        //删除指定key的元素，成功返回true，失败返回false
        this.delete = function(_key) {
            var bln = false;
            try {
                for (i = 0; i < this.elements.length; i++) {
                    if (this.elements[i].key == _key){
                        this.elements.splice(i, 1);
                        return true;
                    }
                }
            }catch(e){
                bln = false;
            }
            return bln;
        },


        //获取指定key的元素值value，失败返回null
        this.get = function(_key) {
            try{
                for (i = 0; i < this.elements.length; i++) {
                    if (this.elements[i].key == _key) {
                        return this.elements[i].value;
                    }
                }
            }catch(e) {
                return null;
            }
        },


        //获取指定索引的元素（使用element.key，element.value获取key和value），失败返回null
        this.element = function(_index) {
            if (_index < 0 || _index >= this.elements.length){
                return null;
            }
            return this.elements[_index];
        },


        //判断Map中是否含有指定key的元素
        this.containsKey = function(_key) {
            var bln = false;
            try {
                for (i = 0; i < this.elements.length; i++) {
                    if (this.elements[i].key == _key){
                        bln = true;
                    }
                }
            }catch(e) {
                bln = false;
            }
            return bln;
        },

        //判断Map中是否含有指定value的元素
        this.containsValue = function(_value) {
            var bln = false;
            try {
                for (i = 0; i < this.elements.length; i++) {
                    if (this.elements[i].value == _value){
                        bln = true;
                    }
                }
            }catch(e) {
                bln = false;
            }
            return bln;
        };
        // 重写forEach方法
        this.forEach = function forEach(callback, context) {
            context = context || window;

            //IE6-8下自己编写回调函数执行的逻辑
            var newAry = new Array();
            for (var i = 0; i < this.elements.length; i++) {
            if (typeof callback === 'function') {
                var val = callback.call(context, this.elements[i].value, this.elements[i].key, this.elements);
                newAry.push(this.elements[i].value);
                }
            }
            return newAry;
        };
        //获取Map中所有key的数组（array）
        this.keys = function() {
            var arr = new Array();
            for (i = 0; i < this.elements.length; i++) {
                arr.push(this.elements[i].key);
            }
            return arr;
        };


        //获取Map中所有value的数组（array）
        this.values = function() {
            var arr = new Array();
            for (i = 0; i < this.elements.length; i++) {
                arr.push(this.elements[i].value);
            }
            return arr;
        };
};

// 实现Set
function Set() {
    var items = {};
    this.size = 0;
    // 实现has方法
    // has(val)方法
    this.has = function(val) {
        // 对象都有hasOwnProperty方法，判断是否拥有特定属性
        return items.hasOwnProperty(val);
    };

    // 实现add
    this.add = function(val) {
        if (!this.has(val)) {
            items[val] = val;
            this.size++;    // 累加集合成员数量
            return true;
        }
        return false;
    };

    // delete(val)方法
    this.delete = function(val) {
        if (this.has(val)) {
            delete items[val];  // 将items对象上的属性删掉
            this.size--;
            return true;
        }
        return false;
    };
    // clear方法
    this.clear = function() {
        items = {};     // 直接将集合赋一个空对象即可
        this.size = 0;
    };

    // keys()方法
    this.keys = function() {
        return Object.keys(items);  // 返回遍历集合的所有键名的数组
    };
    // values()方法
    this.values = function() {
        return Object.values(items);  // 返回遍历集合的所有键值的数组
    };

    // forEach(fn, context)方法
    this.forEach = function(fn, context) {
        for (var i = 0; i < this.size; i++) {
            var item = Object.keys(items)[i];
            fn.call(context, item, item, items);
        }
    };

    // 并集
    this.union = function (other) {
        var union = new Set();
        var values = this.values();
        for (var i = 0; i < values.length; i++) {
            union.add(values[i]);
        }
        values = other.values();    // 将values重新赋值为新的集合
        for (var i = 0; i < values.length; i++) {
            union.add(values[i]);
        }

        return union;
    };
    // 交集
    this.intersect = function (other) {
        var intersect = new Set();
        var values = this.values();
        for (var i = 0; i < values.length; i++) {
            if (other.has(values[i])) {     // 查看是否也存在于other中
                intersect.add(values[i]);   // 存在的话就像intersect中添加元素
            }
        }
        return intersect;
    };

    // 差集
    this.difference = function (other) {
        var difference = new Set();
        var values = this.values();
        for (var i = 0; i < values.length; i++) {
            if (!other.has(values[i])) {    // 将不存在于other集合中的添加到新的集合中
                difference.add(values[i]);
            }
        }
        return difference;
    };


}

};

if(!Array.from){
Array.from = function (el) {
return Array.apply(this, el);
}
}

</script>
<script type="text/javascript">
    var customerData= new Map();
    $(function() {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;
            $.initTextarea();
            $("#select_customer").css("resize", "auto");
            $("#select_customer").css("overflow", "auto");
            $.formSubmit();
            makeCountrySelect();
            findCustomerExists();
            form.render();
            $("#select_customer").click(function() {
                var from_userid=$("#from_userid").val();
                var user_bu=$("#user_bu").val();
                if(ifEmpty(from_userid)){
                    alert("请先选择原业务人员");
                    return false;
                }
               // clear_customers();
                    var index = layui.layer.open({
                        title: "客户信息列表",
                        btn:['确认','取消'],
                        type: 2,
                        content: "customer_list.php?from_userid="+from_userid+"&user_bu="+user_bu,
                        area: ['800px', '650px'],
                        yes: function(index,layero) {
                            var iframe = window['layui-layer-iframe'+index];//得到iframe页的窗口对象，执行iframe页的方法：
                            iframe.addCustomerData();
                        },
                        success: function(index,layero) {
                            
                            setTimeout(function() {
                                layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                    tips: 3
                                });
                            }, 500)
                        }
                    });
                });
            form.on('select(project_level)', function (data) {
                    var starCount = data.value;
                    var html = "";
                    var starShow = "";
                    if (parseInt(starCount) < 5) {
                        for (var i = 0; i < parseInt(starCount) + 1; i++) {
                            starShow += "★";
                        }
                    }
                    html = '<font style="color: red;font-size:20px">' + starShow + '</font>';
                    $("#star_icon").html(html);
                    form.render('select','project_level');
                });
        });
        
    });
    function returnProductData(customers,reData) {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;
            var customer_names=new Array();
            var customer_ids=new Array();
            customerData=customers;
            customers.forEach(function(customer,index,arr){
                //console.log(customer);
                customer_names.push(customer.customer_name);
                customer_ids.push(customer.id);
            });
            // for(customer of customers.values()){
            //     customer_names.push(customer.customer_name);
            // }
           // $("#addCompany").before(html);
           // console.log(customer_names);
            //$("#customer_ids").val(Array.from(reData).join(","));
            $("#customer_ids").val(customer_ids.join(","));
            $("#select_customer").val(customer_names.join("\n"));
            layer.closeAll();
            form.render();
        });
    }

    function getUserSelect(obj) {
        layui.use(['form', 'layer'], function() {
            var layer = layui.layer,
                form = layui.form;

            layer.open({
                title: '业务人员选择',
                type: 2,
                skin: 'layui-layer-rim',
                area: ['75%', '80%'],
                fixed: false,
                maxmin: true,
                offset: '50px',
                content: '../common/list/type_list.php?domId=' + obj.id,
                moveOut: true
            });
            form.render();
        });
    }

    function returnUser(userName, userId, domId) {
        $("#" + domId).val(userName);
        $("#" + domId).parent().find("[data-type='user_id']").val(userId); //取userId隐藏栏位赋值
    }

    // 页面初始化加载
    function init() {
        // textarea初始化样式
        $.each($("textarea"), function(i, n) {
            $(n).css("width", "100%");
            $(n).css("min-height", "20px");
            $(n).css("margin", "auto");
            $(n).css("resize", "none");
            $(n).css("overflow", "hidden");
            makeExpandingArea(document.getElementById(n.id));
        });
    }
    function clear_customers(){
        //console.log(111);
        $("#customer_ids").val("");
        $("#select_customer").val("");
        $("#select_customer").text("");
    }
</script>

<body>
    <form id="mainForm" class="layui-form">
        <div style="width: 100%;height:auto;margin:auto;">
            <div class="r" style="height: auto;">
                <div style="text-align: left;" class="layui-btn-group">
                    <table id="tab2" width="100%">
                        <tr class="layui-bg-body layui-bg-gray" id="tHeader">
                            <td align="center" style="padding: 5px;">
                                <input lay-submit lay-filter="submit" name="btn_submit" actionType="Submit" value="<?=$LG_CUSTOMER_DATA['提交'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input lay-submit lay-filter="submit" name="btn_save" actionType="Save" value="<?=$LG_CUSTOMER_DATA['保存'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" />
                                <input name="btn_close" value="<?=$LG_CUSTOMER_DATA['关闭'][$_COOKIE['LANG']]?>" type=button class="layui-btn layui-btn-oa layui-btn-sm" onclick="$.closeIframe();return false;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br />
            <div class="weadmin-body" style="margin-top: 30px">
                <input type="hidden" id="type" name="type" value="customer_transfer" />
                <input type="hidden" id="form_id" name="form_id" />
                <input type="hidden" name="fieldName" value="form_id" />
                <input type="hidden" name="tableName" value="inhe_customer_transfer" />
                <input type="hidden" name="flowType" value="khzy" />
                <input type="hidden" name="digit" value="4" />
                <input type="hidden" name="step" id="step" value="1" />
                <input type="hidden" name="user_bu" id="user_bu" value="<?= $company ?>" />
                <input type="hidden" id="LANG" value="<?=$_COOKIE[LANG]?>" />
                <input type="hidden" name="user_name" id="user_name" value="<?= $_SESSION['LOGIN_USER_NAME'] ?>" />
                <input type="hidden" name="dept_belong" value="<?= $userInfo['deptName'] ?>" />
                <table width="70%" align="center">
                    <tr>
                    <td align="center">
                        <img alt="" src="../../../../../images/<?= $company ?>/customer_transfer_<?= $company ?>.jpg" style="width: 34%; height: auto;" />
                    </td>
                    </tr>
                    
                </table>
                <br />
                <table class="TableBlock" width="70%" align="center">
                    <tr>
                        <td class="TableContent" width="25%">原业务人员</td>
                        <td class="TableData" style="text-align: left;">
                        <?if($systemAdmin=='1'):?>
                        <textarea placeholder="原业务人员" onchange="clear_customers()" onClick="SelectUserSingle('','','from_userid', 'from_userid_name');clear_customers();" class="layui-textarea mandatory" id="from_userid_name" name="from_userid_name" value="<?= $main['from_userid_name']?$main['from_userid_name']:$_SESSION['LOGIN_USER_NAME']?>" readonly lay-verify="required"><?= $main['from_userid_name']?$main['from_userid_name']:$_SESSION['LOGIN_USER_NAME']?></textarea>
                        <a href="#" class="orgAdd" onClick="SelectUserSingle('','','from_userid', 'from_userid_name');clear_customers();" title="添加收件人">添加</a>
                                    <a href="#" class="orgClear" onClick="ClearUser('from_userid', 'from_userid_name');clear_customers();" title="清空收件人">清空</a><br>
                                    工号(<span style="color:red;">未离职人员添加人员自动填写工号，已离职人员，手动填写工号即可</span>)：<input type="text" onchange="clear_customers()" class="layui-input mandatory" name="from_userid" id="from_userid" value="<?=$main['from_userid']?$main['from_userid']:$_SESSION['LOGIN_USER_ID']?>">
                        </td>
                        <?else:?>
                            <input type="hidden" name="from_userid" id="from_userid" value="<?=$main['from_userid']?$main['from_userid']:$_SESSION['LOGIN_USER_ID']?>">
                        <textarea placeholder="原业务人员"  class="layui-textarea mandatory" id="from_userid_name" name="from_userid_name" value="<?= $main['from_userid_name']?$main['from_userid_name']:$_SESSION['LOGIN_USER_NAME']?>" readonly lay-verify="required"><?= $main['from_userid_name']?$main['from_userid_name']:$_SESSION['LOGIN_USER_NAME']?></textarea>
                        <?endif;?>
                    </tr>
                    <tr>
                        <td class="TableContent" width="25%">新业务人员</td>
                        <td class="TableData" style="text-align: left;">
                        <input type="hidden" name="to_userid" id="to_userid" value="<?=$main['to_userid']?>">
                        <textarea placeholder="新业务人员" onClick="SelectUserSingle('','','to_userid', 'to_userid_name')" class="layui-textarea mandatory" id="to_userid_name" name="to_userid_name" value="<?= $main['to_userid_name']?>" readonly lay-verify="required"><?= $main['to_userid_name']?></textarea>
                        <a href="#" class="orgAdd" onClick="SelectUserSingle('','','to_userid', 'to_userid_name')" title="添加收件人">添加</a>
                                    <a href="#" class="orgClear" onClick="ClearUser('to_userid', 'to_userid_name')" title="清空收件人">清空</a><br>
                        </td>
                    </tr>
                    <!-- <tr>
                        <td class="TableContent" width="25%">客户类型</td>
                        <td class="TableData" >
                            <div class="layui-input-inline mandatory">
                                <select id="transfer_type" name="transfer_type" lay-verify="required">
                                    <?php foreach ($selectArr['customer_transfer_type'] as $val) : ?>
                                        <option value="<?= $val['paras_value'] ?>" <?php if ($main['transfer_type'] == $val['paras_value']) : ?>selected<?php endif; ?>><?= $val['paras_desc'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </td>
                    </tr> -->
                    <tr>
                        <td class="TableContent" width="25%">选择客户</td>
                        <input type="hidden" name="customer_ids" id="customer_ids" value="<?=$main['customer_ids']?>">
                  
                        <td class="TableData" style="text-align: left;">
                        <textarea class="layui-textarea mandatory" id="select_customer" name="select_customer" placeholder="点击选择客户" style="resize: auto;overflow: auto;" readonly lay-verify="required"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="TableContent" width="25%">转移原因</td>
                        <td class="TableData">
                            
                            <textarea class="layui-textarea mandatory" id="remark" name="remark" placeholder="转移原因" lay-verify="required"></textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
</body>

</html>