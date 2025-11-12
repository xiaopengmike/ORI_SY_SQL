/*
 * @Author: Tsao
 * @Author: Tsao
 * @Date:   2019年6月4日
 * @lastModify 
 * +----------------------------------------------------------------------
 * | 通用common方法，用于搭建页面时需要的常用的方法
 * +----------------------------------------------------------------------
 */
layui.define(['layer'], function (exports) {
	var layer = layui.layer,
		laydate = layui.laydate;
	var comPage = {
		loadDataList: function ($data, after) {
			options = $.extend({
				elemList:"#dataList",
				url: "",
				data: {}
			}, $data);
			if (options.url == "" || after == null || typeof after !== 'function') {
				console.error("options error");
				return;
			}
			$(options.elemList).layerPage(options.url, options.data, function (redata) {
				after(redata);
			});
		},
		addData: function (url, w, h, opts) {
			$.popUpBox(url, w, h, opts);
		},
		editOrdetail: function (url, w, h, id, opts) {
			$.popUpBox(popUpEditBox);
		},
		deleteData: function (id, url) {
			layer.confirm("确定删除该数据吗？", {
				icon: 3,
				title: '提示'
			}, function (index) {
				layer.close(index);
				$.post(url, {
					id: id
				}, function (data, status) {
					var jsonOb = eval("(" + data + ")");
					if (jsonOb.code == "10000") {
						layer.alert("删除成功", {
							icon: 1
						}, function () {
							this.loadDataList();
							layer.closeAll();
						});
					} else {
						layer.alert(jsonOb.msg, {
							icon: 5
						});
					}
				});
			});
		}
	}
	window.pageAdd = function (url, title, w, h) {
		$.popUpBox(url, title, w, h);
	}
	window.editOrdetail = function (url, w, h, id, opts) {
		$.popUpBox(popUpEditBox);
	}
	exports('comPage', comPage);
});