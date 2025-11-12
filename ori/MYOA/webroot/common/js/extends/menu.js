/*
 * @Author: https://github.com/WangEn
 * @Date:   2018-01-01\
 * @editer: Tsao
 * @lastModify 2018-3-27 15:00:35
 * +----------------------------------------------------------------------
 * | 基于Layui http://www.layui.com/
 * +----------------------------------------------------------------------
 */

layui.define(['layer'], function(exports) {
	var layer = layui.layer;
	var menu = {
		initMenu : function(){
			$.post("wisdom_party/menu/get-menu",{systemId:"wisdom_party"},function(menuDate){
				menuObj = eval("("+menuDate+")");
				var leftMenu = buildMenu(menuObj,"");
				if(leftMenu==""){
					$("#nav").html("当前用户没有权限！");
				}else{
					$("#nav").html(leftMenu);
				}
			});
		}
	}
	function buildMenu(menuObj,leftMenu) {
		if (menuObj && menuObj.length) {
			for (var i = 0; i < menuObj.length; i++) {
				leftMenu += '<li><a _href="' + (menuObj[i].target_url == "" ? "javascript:;" : menuObj[i].target_url) +
					'"><cite>' + menuObj[i].module_name + '</cite><i class="iconfont nav_right">&#xe697;</i></a>';
				if(menuObj[i].childList){
					leftMenu += '<ul class="sub-menu">';
					leftMenu = buildMenu(menuObj[i].childList,leftMenu);
					leftMenu += '</ul>';
				}
			}
			leftMenu += '</li>';
		}
		return leftMenu;
	}
	exports('menu', menu);
});