define('TCheckboxCtrl', function(require, exports, module){
    var $ = jQuery;
    var Base = require('base');//iCheck初始化单选、复选框
    var TCheckboxCtrl = Base.extend({
        initialize: function(config){
            TCheckboxCtrl.superclass.initialize.call(this, config);
			this.$config = config;
            $('input').iCheck({ 
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            });
        }
    });
    exports.TCheckboxCtrl = window.TCheckboxCtrl = TCheckboxCtrl;
});