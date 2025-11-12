(function(){
    jQuery.extend({
        popUpBox:function(url,title,w,h){
            if(title == null || title == '') {
                title = false;
            };
            if(url == null || url == '') {
                url = "404.html";
            };
            if(w == null || w == '') {
                w = ($(window).width() * 0.9);
            };
            if(h == null || h == '') {
                h = ($(window).height() - 50);
            };
            opts = {
                    type: 2,
                    area: [w + 'px', h + 'px'],
                    fix: false, //不固定
                    maxmin: true,
                    shadeClose: true,
                    shade: 0.4,
                    title: title,
                    content: url
                };
            layui.use('layer',function(){
                var layer = layui.layer;
                layer.open(opts);
            });
        },
        popUpEditBox:function(url,title,id,w,h){
            if(title == null || title == '') {
                title = false;
            };
            if(url == null || url == '') {
                url = "404.html";
            };
            if(w == null || w == '') {
                w = ($(window).width() * 0.9);
            };
            if(h == null || h == '') {
                h = ($(window).height() - 50);
            };
            opts = {
                    type: 2,
                    area: [w + 'px', h + 'px'],
                    fix: false, //不固定
                    maxmin: true,
                    shadeClose: true,
                    shade: 0.4,
                    title: title,
                    content: url,
                    success: function(layero, index) {
                        //向iframe页的id=house的元素传值  // 参考 https://yq.aliyun.com/ziliao/133150
                        var body = layer.getChildFrame('body', index);
                        body.contents().find("#dataId").val(id);
                        console.log(id);
                    },
                    error: function(layero, index) {
                        alert("layui open error");
                    }
                };
            layui.use('layer',function(){
                var layer = layui.layer;
                layer.open(opts);
            });
        }
    });
})(jQuery);
