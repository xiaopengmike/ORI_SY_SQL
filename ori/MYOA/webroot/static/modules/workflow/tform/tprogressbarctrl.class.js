define('TProgressBarCtrl', function(require, exports, module){
    var $ = jQuery;
    var Base = require('base');
    var TProgressBarCtrl = Base.extend({

        initialize: function(config) {
            TProgressBarCtrl.superclass.initialize.call(this, config);
			this.$config = config;
			this.$add_img = $('#img_add_' + config.id);
			this.$less_img = $('#img_less_' + config.id);
			this.bindEvent();
			
        },
        bindEvent: function()
        {
        	var self = this,
        	item_id = this.$config.item_id,
        	sign_type = this.$config.sign_type;
        	
        	this.$add_img.click(function(){
        		
        		if(sign_type == "")
				{
					sign_type = 1;
				}
				
				//取出进度并得出新的进度
				var pro_val = Math.round(jQuery("#DATA_"+item_id).attr('value'));
				
				pro_val = parseInt(pro_val)+parseInt(sign_type);
				
				if(pro_val > 100)
				{
					pro_val = 100;
				}
				if(pro_val < 0)
				{
					pro_val = 0;
				}
				
				jQuery("#DATA_"+item_id).attr("value",parseInt(pro_val));
				jQuery("#numpro_"+item_id).html(""+pro_val+"%");
				
				var progress_style = Math.round(145 - 145 * pro_val/100);
				
				var pro_obj = jQuery("#bar_"+item_id);
				pro_obj.css("backgroundPositionX", "-"+progress_style+'px');
            });
            
            this.$less_img.click(function(){
        		if(sign_type == "")
				{
					sign_type = 1;
				}
				//取出进度并得出新的进度
				var pro_val_old = jQuery("#DATA_"+item_id).attr('value');
				var pro_val = Math.round(jQuery("#DATA_"+item_id).attr('value'));
				pro_val = parseInt(pro_val)-parseInt(sign_type);
				
				if(pro_val > 100)
				{
					pro_val = 100;
				}
				if(pro_val < 0)
				{
					pro_val = 0;
				}
				
				jQuery("#DATA_"+item_id).attr("value",parseInt(pro_val));
				jQuery("#numpro_"+item_id).html(""+pro_val+"%");
				
				var progress_style = Math.round(145 - 145 * pro_val/100);
				
				var pro_obj = jQuery("#bar_"+item_id);
				pro_obj.css("backgroundPositionX", "-"+progress_style+'px');
            });
        }
        
    });
    exports.TProgressBarCtrl = window.TProgressBarCtrl = TProgressBarCtrl;
});

