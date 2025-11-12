define('TFieldManager', ['TFieldLoader'], function(require, exports, module){    
    var $ = jQuery;
    var Base = require('base');
    var Fields = require('TFieldLoader');
    var TFieldManager = Base.extend({
        attrs: {
            runId: null,
            flowId: null,
            wrapper: 'body',
            dblclickHandle: function(){}
        },
        initialize: function(config, store) {
            TFieldManager.superclass.initialize.call(this, config);
            this.register = store || [];
            this.instances = {};
            this.initFields();
            this.bindEvent();
        },
        
        regist: function(cfg) {
            this.register.push(cfg);
        },
        
        factory: function(cfg) {
            var klass = Fields[cfg.type], field;
            if(klass){
                field = new klass(cfg);
            }
            return field;
        },
        
        initFields: function() {
            var register = this.register,
                instances = this.instances,
                factory = this.factory;
            $.each(register, function(i, cfg){
                var instance = factory(cfg);
                if(instance && instance.get('id')){
                    instances[instance.get('id')] = instance;
                }
            });
        },
        
        bindEvent: function() {
            var self = this, 
                runId = this.get('runId'), 
                flowId = this.get('flowId'),
                dblclickHandle = this.get('dblclickHandle');
            $(this.get('wrapper'))
                .find('input,textarea')
                .filter(function(){
                    var $this = $(this);                    
                    return !($this.is(':reset,:file,:button,.AUTO,.FETCH,:disabled') || !(this.name ? this.name.match(/DATA_/gi) : false) || this.readOnly || this.readOnly === '' || $this.parents('.LIST_VIEW').size()) 
                })
                .bind('dblclick', function(e){ 
                    dblclickHandle(this, runId, flowId);
                })
                .filter('input:text,textarea')
                .bind('keypress', function(e){ 
                    if(e.which == 10){
                        dblclickHandle(this, runId, flowId);
                    }
                });
            //backspace
            $('body').keydown(function(e){
                var target = e.target,
                    $target = $(target),
                    preventPress = false;
                if( e.which == 8 ){
                    preventPress = $target.is('input,textarea') 
                                    ? ( $target.is(':disabled') || target.readOnly || target.readOnly === '' )
                                    : true;
                    return !preventPress;
                }
            }); 
            
        }
    });
    exports.TFieldManager = window.TFieldManager = TFieldManager;
});


