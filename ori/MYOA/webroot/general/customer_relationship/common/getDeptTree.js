var setting = {
    view: {
        dblClickExpand: false,
        showLine: false,
        showIcon: false
    },
    data: {
        simpleData: {
            enable: true
        }
    },
    callback: {
        onClick: onClick
    }
};
var zTree;
var treeNodes;
var code;

$(function () {
    $.ajax({
        async: false,
        cache: false,
        type: "POST",
        dataType: "text",
        url: "/common/getCompanyTree.php", //恳求的action路径
        error: function () { //恳求失败处理惩罚函数
            alert("请求失败");
        },
        success: function (data) { //恳求成功后处理惩罚函数。
            //alert(data);

            // console.log(data); // 火狐在后台打印的日志。 
            treeNodes = data; //把后台封装好的简单Json格局赋给treeNodes
        }
    });

    //将string类型转换成json 
    treeNodes = eval("(" + treeNodes + ")");

    zTree = $.fn.zTree.init($("#treeMenu"), setting, treeNodes);

});

function onClick(event, treeId, treeNode, clickFlag) {
    var zTree = $.fn.zTree.getZTreeObj("treeMenu");
    zTree.expandNode(treeNode);
    var str = treeNode.id;
    str = getAllChildrenNodes(treeNode, str);
    alert(str); //所有叶子节点ID 


    //alert(treeNode.id);

    //alert(treeNode.id);
}

function getAllChildrenNodes(treeNode, result) {
    if (treeNode.isParent) {
        var childrenNodes = treeNode.children;
        if (childrenNodes) {
            for (var i = 0; i < childrenNodes.length; i++) {
                result += ',' + childrenNodes[i].id;
                result = getChildNodes(childrenNodes[i], result);
            }
        }
    }
    return result;
}