define('TFileUploadCtrl',function(require, exports, module){
    var $ = jQuery;
    var Base = require('base');
    var TFileUploadCtrl = Base.extend({
    	
        initialize: function(config) 
        {
        	TFileUploadCtrl.superclass.initialize.call(this, config);
           
        }
    });
    exports.TFileUploadCtrl = window.TFileUploadCtrl = TFileUploadCtrl;
});

