
function getStockImage()
{
   jQuery("#stock_img").src= "http://www.google.com.hk/finance/chart?cht=c&q=SHA%3A000001%2CSHE%3A399001&chlc=rg&tlf=24h&auto=1&zx=xk7dzk-mgnrvg";
	window.setTimeout(getStockImage,300000);
}
function getStockData()
{
	jQuery.get("/inc/stock.php",{},function(data){
			eval(data);
			var content = "";
			var color = "";
			for(var i in hq)
			{
				var arr = hq[i].split(",");
				content += "<span>"+arr[0]+"："+arr[1]+"&nbsp;&nbsp;&nbsp;"
				if(arr[2]>0)
					color = "red";
				else
					color = "green"
					content += "<span class=\""+color+"\">"+arr[2]+"("+arr[3]+"%)</span></span><br/>";
			}
			jQuery("#stock_hq").empty().append(content);
	});
	window.setTimeout(getStockData,60000);
}
function getNav()
{
	for(var i=0; i<=19 ; i++){
		document.write("<a href=\"javascript:getWhpj(\'"+i+"\');\">"+(i+1)+"</a>");
	}
}
function getWhpj(page)
{
   jQuery("#whpj").html('<img src="/images/loading.gif" align="absMiddle"> '+td_lang.inc.msg_43);//加载中，请稍候……
   jQuery.ajax({
      type: 'GET',
      url: '/inc/whpj.php',
      data: {'PAGE':page},
      dataType: 'text',
      success: function(data){
         jQuery("#whpj").html(data);
      },
      error: function (request, textStatus, errorThrown){
         jQuery("#whpj").html( td_lang.inc.msg_58+ request.status);//'获取数据错误：'
      }
   });
}

jQuery.noConflict();
(function($){
   $(document).ready(function($){
    	$("#slider").flashSlider({width:280,height:180,speed:800,pause:8000});
    	getStockImage();
    	getStockData();
    	
    	$('div.box > div.box_title').hover(
    	   function(){
    	      $('a.scroll', this).show();
    	   },
    	   function(){
    	      $('a.scroll', this).hide();
    	   }
    	);
    	
    	$('div.box > div.box_title > a.scroll').click(function(){
    	   var scroll = $(this).attr('scroll');
    	   var ul = $('#box_ul_' + $(this).attr('index'));
    	   ul.animate({scrollTop: ul.scrollTop() + ul.height() * scroll}, 600);
    	});
   });
})(jQuery);
