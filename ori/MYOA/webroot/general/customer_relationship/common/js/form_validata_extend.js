;
layui.use(["form"],function(){
    var form = layui.form ;
    function lengthInBytes(s) {
        var ret = 0;
        for (var i=0;i<s.length;i++){
            if (s.charCodeAt(i) > 127){
                ret += 2;
            }
            else{
                ret++;	
            }
        }
        return ret;
    }
    form.verify({
        numberNotRequired : function(value){
            if (value != "") {
                var reg = new RegExp("^([0-9])*$");
                  if (!reg.test(val)) {
                      return "必须输入有效数字";
                  } 
              }
        },
        phoneNotRequired : function(value){
            //格式校验
            if (value != "") {
                var reg = new RegExp("^((0[0-9]{2,3}\\-)?([2-9][0-9]{6,7})+(\\-[0-9]{1,4})?|(\\+86|86)?[0-9]{11}|\\(0[0-9]{2,3}\\)[2-9][0-9]{5,6})$");
                if (!reg.test(value)) {
                    return "手机或座机格式输入错误";
                } 
            }
        },
        moneyNotRequired : function(value){
            if (value !="") {
                var reg = new RegExp("^([0-9]|\\.|\\,)*$");
                if (!reg.test(value)) {
                    return "栏位应输入金额";
                }
            }
        },
        lengthValidata : function(value,item){
            var valueLength = lengthInBytes(value);
            var maxlength = $(item).attr("maxlength");
            var minilength = $(item).attr("minilength");
            if(maxlength != undefined){
                if(valueLength > maxlength){
                    return "栏位输入不能大于【"+maxlength+"】个字符！";
                }
            }
            if(minilength != undefined){
                if(valueLength < minilength){
                    return "栏位输入不能少于【"+minilength+"】个字符！";
                }
            }
        }
    })
})
