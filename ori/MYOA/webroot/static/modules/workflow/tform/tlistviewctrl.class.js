define('TListViewCtrl', function(require, exports, module){
    var $ = jQuery;
    var Base = require('base');
    var TListViewCtrl = Base.extend({

        initialize: function(config) {
            TListViewCtrl.superclass.initialize.call(this, config);
            
            var lv_table_id = "LV_" + config['id'];
            var read_only;
            if(config['add_op'] == 1)
            {
                var btn_add_id  = "add_btn_" + config ['id']
                var btn_add_obj = $('#'+ btn_add_id);   
                if(btn_add_obj)
                {
                    btn_add_obj.click(function(){
                    
                        tb_addnew(lv_table_id, '0', '', '1');
                    
                    });
                    
                }
                
                var btn_calc_id  = "calc_btn_" + config ['id'];
                var btn_calc_obj = $('#'+ btn_calc_id);  
                
                if(btn_calc_obj)
                {   
                    btn_calc_obj.click(function(){
                        
                        tb_cal(lv_table_id, config['lv_cal']);
                    
                    });
                }
               /* var btn_add = $('#');

                tb_addnew(lv_tb_id,read_only,row_value,is_del);
                var btn_del = 
                add_btn_\"".$this->_attr ['name']*/
            }
            
            if(config['init_value'] != "")
            {
                var arr_value = config['init_value'].split("\r\n");
                var value_count = arr_value.length;
                if(arr_value[value_count - 1] == "")
                {
                    value_count--;
                }
                for(var i = 0; i < value_count; i++)
                {
                    if(config['edit_op'] == 1)
                    {
                        tb_addnew(lv_table_id, 0, arr_value[i], config['delete_op']);
                        
                    }else
                    {
                        tb_addnew(lv_table_id, 1, arr_value[i], config['delete_op']);

                    }
                }
            }
            
            this.bindCalc(lv_table_id, config['lv_cal']);
        },
        
        bindCalc:function(lv_table_id, lv_cal){
             var func = function(){
                tb_cal(lv_table_id, lv_cal);
                var func = arguments.callee; 
                setTimeout(func, 500); 
             };  
            
            func();
        }

    });
    exports.TListViewCtrl = window.TListViewCtrl = TListViewCtrl;
});

