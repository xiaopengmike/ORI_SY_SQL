if (!Array.indexOf) {
    Array.prototype.indexOf = function (obj) {
        for (var i = 0; i < this.length; i++) {
            if (this[i] == obj) {
                return i;
            }
        }
        return -1;
    }
}

(function ($) {
    jQuery.fn.extend({
        newDeptSelect: function (opts) {
            opts = jQuery.extend({
                commonUrl: "/common", //默认路径
                toshow : "treediv", // 默认弹出层ID
                // target: ["menu_parent_name"], // 默认父级菜单名称
                // targetId: ["menu_parent"], // 默认父级菜单ID
                target: "menu_parent_name", // 默认父级菜单名称
                targetId: "menu_parent", // 默认父级菜单ID
                closed: "closed" // 关闭弹窗控件ID
            }, opts || {});

            var toshow = opts.toshow; //要显示的层的id
            var closed = opts.closed;

            // -------------------------------生成弹出层的代码----------------------------------------
            xOffset = 0; //向右偏移量
            yOffset = 25; //向下偏移量
            // var mc="";

            // for(var n=0; n<opts.target.length; n++){
            //     var mc="";
                var target = opts.target; //目标控件----也就是想要点击后弹出树形菜单的那个控件id
                // if(n>0)  mc=n;
                // console.log(target);

                $("#" + target).click(function () {
                    $("#" + toshow)
                        .css("position", "absolute")
                        .css("left", $("#" + target).position().left + xOffset + "px")
                        .css("top", $("#" + target).position().top + yOffset + "px").show();
                });
            // }
            //关闭层
            $("#"+closed).click(function () {
                $("#" + toshow).hide();
            });

            //判断鼠标在不在弹出层范围内
            function checkIn(id) {
                var yy = 20; //偏移量
                var str = "";
                var x = window.event.clientX;
                var y = window.event.clientY;
                var obj = $("#" + id)[0];
                if (x > obj.offsetLeft && x < (obj.offsetLeft + obj.clientWidth) && y > (obj.offsetTop - yy) && y < (obj.offsetTop + obj.clientHeight)) {
                    return true;
                } else {
                    return false;
                }
            }
            //点击body关闭弹出层
            $(document).click(function () {
                var is = checkIn(toshow);
                if (!is) {
                    $("#" + toshow).hide();
                }
            });

            //-------------------------------生成弹出层的代码-------------------------------------------

            //树代码
            mydtree = new dTree('mydtree', opts.commonUrl + '/imgmenu/', 'no', 'no');
            // ,'"+opts.target+"','"+opts.targetId+"','"+opts.toshow+"'
            mydtree.add(0,
                -1,
                "部门",
                "javascript:setvalue('0','部门')",
                "部门",
                "_self",
                false);
            url = opts.commonUrl + "/gethint.php";
            var myobj;
            //在全局或某个需要的函数内设置Ajax异步为false，也就是同步.
            $.ajaxSetup({async : false});
            $.post(url,{},function(response){
				myobj=eval(response);
				for(var i = 0; i < myobj.length; i++){
					BM=myobj[i].NAME;
					var ID=parseInt(myobj[i].ID);
					var PARENT=parseInt(myobj[i].PARENT);
					mydtree.add(ID,PARENT,BM,"javascript:setvalue('"+ID+"','"+BM+"')",BM,'_self',false);
				}
			});
			document.write(mydtree);
        }
    });
})(jQuery);

function setvalue(id, name, mc="") {
    // console.log(mc);
    // var mc="";
    // $("#"+menu_parent_name).val(name);
    // $("#"+menu_parent).val(id);
    // $("#"+treediv).hide();
    $("#menu_parent_name"+mc).val(name);
    $("#menu_parent"+mc).val(id);
    $("#treediv").hide();
}
