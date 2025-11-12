<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("数据列表");
include_once("inc/header.inc.php");
include_once("common/Common.php");
require_once("../common/DataModel.php");
$dataModel = new DataModel();

$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'project_star'
);
$selectArr = $dataModel->getCommonParam($selectParam);
$continentArr = $dataModel->getContinentData();
$countryArr = $dataModel->getCountryData();
?>

<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script type="text/javascript" src="<?= COMMON_FILE_URL ?>/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?= COMMON_FILE_URL ?>/common.js"></script>
    <script type="text/javascript" src="<?= COMMON_FILE_URL ?>/layui/layui.js"></script>
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/font.css">
    <link rel="stylesheet" href="<?= COMMON_FILE_URL ?>/css/weadmin.css">
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

</script>
</head>

<body style="background-color: white;">
    <div style="margin:1%">
        <form class="layui-form">
            <div class="demoTable" style="margin:5px;">
                <input type="hidden" class="layui-input" name="form_userid" id="form_userid" value="<?=$from_userid?>" autocomplete="off" style="width: 110px;">
                <input type="hidden" class="layui-input" name="user_bu" id="user_bu" value="<?=$user_bu?>" autocomplete="off" style="width: 110px;">
                客户名称：
                <div class="layui-inline">
                    <input class="layui-input" name="customer_name" id="customer_name" autocomplete="off" style="width: 110px;">
                </div>
                客户简称：
                <div class="layui-inline">
                    <input class="layui-input" name="short_name" id="short_name" autocomplete="off" style="width: 110px;">
                </div>
                大洲：
                <div class="layui-inline" style="width: 100px;">
                    <select id="continent" name="continent" lay-filter="continent" lay-search >
                        <option value=''>全部</option>
                        <?php foreach ($continentArr as $ck => $cv) : ?>
                            <option value="<?= $cv['iso_two'] ?>"><?= $_COOKIE['LANG']=="EN"?$cv['en_name']:$cv['zh_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                国家：
                <div class="layui-inline" style="width: 100px;">
                    <select id="country" name="country" lay-search >
                        <option value=''>全部</option>
                        <?php foreach ($countryArr as $yk => $yv) : ?>
                            <option value="<?= $yv['iso_three'] ?>"><?= $_COOKIE['LANG']=="EN"?$yv['country']:$yv['zh_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input lay-submit lay-filter="reload" id="reload" data-type="reload" value="查询" type=button class="layui-btn" />
            </div>
        </form>
        <!--数据表格-->
        <table class="layui-hide" id="tableList" lay-filter="tableList"></table>
    </div>
    <script>
        var customer_ids = parent.document.getElementById('customer_ids');
        var select_customer = parent.document.getElementById('select_customer');
        customer_ids = $(customer_ids).val().split(",");
        var selectData = new Set(customer_ids);
        if(!(customer_ids[0]==""&&customer_ids.length==1)){
            for(var i=0;i<customer_ids.length;i++){
                selectData.add(customer_ids[i]);
            }
        }
        
        var customerData = parent.customerData;
        layui.use(['form', 'table'], function() {
            var table = layui.table;
            var form = layui.form;
            var $ = layui.$;
            var table_data = new Array();
            //方法级渲染
            var tableIns = table.render({
                id: 'tableList',
                elem: '#tableList',
                url: 'get_customer_data.php?form_userid='+$('#form_userid').val()+"&user_bu="+$('#user_bu').val(),
                cellMinWidth: 90,
                title: '客户名称列表',
                cols: [
                    [{
                        type: 'checkbox',
                        fixed: 'left'
                    }, {
                        field: 'id',
                        title: '编号',
                        width: 1,
                        align: 'center',
                        hide: true
                    }, {
                        field: 'customer_name',
                        title: '客户名称',
                        align: 'center'
                    }, {
                        field: 'short_name',
                        title: '客户简称',
                        width: 120,
                        align: 'center'
                    }, {
                        field: 'continentName',
                        title: '大洲',
                        width: 120,
                        align: 'center'
                    }, {
                        field: 'countryName',
                        title: '国家',
                        width: 120,
                        align: 'center'
                    }]
                ],
                page: true,
                height: 'auto',
                done: function(res, curr, count) {
                    // 选中回显
                    selectEcho(res.data, "id");
                   // $("[data-field='id']").css('display','none');
                   table_data=exportData = res.data;
                }
            });

            var $ = layui.$,
                active = {
                    reload: function() {
                        //执行重载
                        table.reload('tableList', {
                            page: {
                                curr: 1 //重新从第 1 页开始
                            },
                            where: {
                                continent: $('#continent').val(),
                                country: $('#country').val(),
                                customer_name: $('#customer_name').val(),
                                short_name: $('#short_name').val()
                            }
                        }, 'data');
                    }
                };
            
            
            table.on('checkbox(tableList)', function(obj){
                if(obj.checked) {
                    if (obj.type === "all") {
                        for (var i = 0; i < table_data.length; i++) {
                            selectData.add(table_data[i].id + "");//table_data 在步骤3中初始化请注意这块
                            customerData.set(table_data[i].id,table_data[i]);
                        }
                        // 全选当前页
                        //table.checkStatus('tableList').data.map(row => row['id']).forEach(item => selectData.add(item + ""));
                    } else {
                        // 选中
                        selectData.add(obj.data['id'] + "")
                        customerData.set(obj.data['id'],obj.data);
                    }
                } else {
                    if (obj.type === "all") {
                        // 取消全选当前页
                        for (var i = 0; i < table_data.length; i++) {
                            selectData.delete(table_data[i].id + "");//table_data 在步骤3中初始化请注意这块
                            customerData.delete(table_data[i].id);//table_data 在步骤3中初始化请注意这块
                        }
                        //table.cache["tableList"].map(row => row['id']).forEach(item => selectData.delete(item + ""));
                    } else {
                        // 取消选中
                        selectData.delete(obj.data['id'] + "");
                        customerData.delete(obj.data['id']);
                    }
                }
                
                //console.log(customerData);
                //console.log(selectData);
                //parent.returnProductData(customerData,selectData);
            });
            // table.on('checkbox(tableList)', function(obj) {
            //     // 返回参数到原表单上
            //     parent.returnProductData(obj.data);
            // });
            function selectEcho(data, dataField){
                // 选中回显
                var ids = [];
                $($("td[data-field='"+dataField+"'] div.layui-table-cell")).each(function(index, domObj){
                    if (selectData.has($(domObj).text())) {
                        ids.push($(domObj).text());

                        $(".layui-table-fixed").find("tr[data-index="+index+"]").find(".layui-form-checkbox").addClass("layui-form-checked").prev().prop('checked', true);
                        //$(domObj).parent().prev().find(".layui-form-checkbox").addClass("layui-form-checked").prop('checked', true);
                    }
                });
                
                // 真正有效的勾选
                for (var i = 0; i < data.length; i++) {
                    if (ids.indexOf(data[i][dataField] + "") > -1) {
                        data[i]["LAY_CHECKED"]=true;
                    }
                }
        
                // 回显全选checkbox的选中状态
                if(ids.length === parseInt($(".layui-laypage-limits select").val())){
                    $('input[lay-filter="layTableAllChoose"]').prop('checked',true);
                    $('input[lay-filter="layTableAllChoose"]').next().addClass('layui-form-checked');
                }
                form.render('checkbox');
            }

            form.on('submit(reload)', function(data) {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        });
        function addCustomerData(){
            parent.returnProductData(customerData,selectData);
        }
    </script>

</body>

</html>