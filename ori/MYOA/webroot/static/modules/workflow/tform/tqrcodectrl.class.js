define('TQRCodeCtrl', function(require, exports, module){
    var $ = jQuery;
    var Base = require('base');
    var TQRCodeCtrl = Base.extend({

        initialize: function(config) {
            TQRCodeCtrl.superclass.initialize.call(this, config);
			this.$config = config;
			this.$selobj = $('[id=' + config.id +']').eq(0);
        }

    });
    exports.TQRCodeCtrl = window.TQRCodeCtrl = TQRCodeCtrl;
});

