define('TExtDataSelectionCtrl', function(require, exports, module){
    var $ = jQuery;
    var Base = require('base');
    var TExtDataSelectionCtrl = Base.extend({

        initialize: function(config) {
            TExtDataSelectionCtrl.superclass.initialize.call(this, config);
            
            var el = $('img[name="' + config.id + '"]:first');   //选择按钮jQuery对象
            if(el.length <= 0)
            {
                return;
            }
            
            var title_str = el.attr('DATA_CONTROL');    //表单字段显示名称(title)
            if(!title_str)
            {
                return;
            }
            
            var arr_title = title_str.split('`');
            
            var map_name_str = '';
            for(var i=0; i<arr_title.length; i++)   //循环所有映射绑定的表单字段title
            {
                var title = arr_title[i];
                if(!title)
                {
                    map_name_str += ',';
                    continue;
                }
                
                var obj = $('[title="' + title + '"]:first');   //查找title对应的元素
                if(obj.length > 0)
                {
                    obj.attr('readonly', true);
                    var name = obj.attr('name');
                    if(name && name.substr(0, 5) == 'DATA_')
                    {
                        map_name_str += name + ',';
                    }
                }
            }
            
            el.click(function(){
                if(typeof(data_picker) == 'function')
                {
                    data_picker(el.get(0), map_name_str, 'ext_data');
                }
                return false;
            });
        }
    });
    exports.TExtDataSelectionCtrl = window.TExtDataSelectionCtrl = TExtDataSelectionCtrl;
});

