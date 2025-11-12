
jQuery.noConflict();
(function($){
   $(document).ready(function($){
   	//展开/折叠事件
      $('a.expand').click(function(){
         var id = $(this).attr('index');
         $('#module_' + id + '_body').toggle();
         $('#module_' + id).toggleClass('listColorCollapsed');
         $(this).toggleClass('collapse');
      });
      
      //标题栏事件
      $('div.head').hover(
         function(){
            var id = $(this).attr('index');
            $('#module_' + id + '_op').show();
         },
         function(){
            var id = $(this).attr('index');
            $('#module_' + id + '_op').hide();
         }
      );
      
      //更多类型
      $('div.module_body').hover(
         function(){
            var id = this.id.substr(0, this.id.length-4);
            var type_link = $('#' + id + 'type_link');
            if(type_link.length > 0 && type_link.attr('scrollHeight') > type_link.attr('clientHeight'))
               $('#' + id + 'type_op').css('display', 'inline');
         },
			function(){
			   var id = this.id.substr(0, this.id.length-4);
			   $('#' + id + 'type_op').hide();
			}
	   );
	   
	   $('div.moduleTypeOp > a').click(function(){
	      var id = $(this).attr('index');
	      var type_link = $('#module_' + id + '_type_link');
	      var scrollTo = type_link.scrollTop() + type_link.attr('clientHeight');
	      scrollTo = scrollTo >= type_link.attr('scrollHeight') ? 0 : scrollTo;
	      type_link.animate({scrollTop: scrollTo}, 300);
	   });
   });
})(jQuery);

var $ = function(id) {return document.getElementById(id);};

function NextPage(id, page)
{
   var ul = jQuery('#module_'+id+'_ul')
   ul.animate({scrollTop: ul.scrollTop() + ul.height()*page}, 500, function(){
      if(ul.scrollTop() <= 0)
         jQuery('#module_' + id + '_link_pre').addClass('PageLinkDisable');
      else
         jQuery('#module_' + id + '_link_pre').removeClass('PageLinkDisable');
      
      if(ul.scrollTop() + ul.height() >= ul.attr('scrollHeight'))
         jQuery('#module_' + id + '_link_next').addClass('PageLinkDisable');
      else
         jQuery('#module_' + id + '_link_next').removeClass('PageLinkDisable');
   });
}

function _get(url, query_string, callback)
{
   var params = {};
   var query_array = query_string.split('&');
   for(var i=0; i < query_array.length; i++)
   {
      var pos = query_array[i].indexOf("=");
      var key = query_array[i].substr(0, pos);
      var value = query_array[i].substr(pos+1);
      params[key] = value;
   }
   
   jQuery.ajax({
      type: 'GET',
      url: './more/' + url,
      data: params,
      complete: function(request, status){
         callback(request);
      },
      error: function(request, textStatus, errorThrown){
         callback(request);
      }
   });
}

function openURL(name, url)
{
   parent.openURL('', name, url);
}