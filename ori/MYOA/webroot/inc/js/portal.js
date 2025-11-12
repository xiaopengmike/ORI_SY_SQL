
jQuery.noConflict();
(function($){
   $(document).ready(function($){
      $('#top a.oa_general').click(function(){
         if(from_ispirit)
            window.external.OA_SMS("/general/?ISPIRIT=1", "MAX", "OPEN_URL");
         else
            window.open('/general/?ISPIRIT=1');
      });
      
      $('#top a.close').click(function(){
         if(typeof(top.closePortal) == 'function')
            top.closePortal();
         else if(from_ispirit)
            window.external.OA_SMS("", "", "CLOSE_WINDOW")
         else
            window.close();
      });
      
      $('#center .block').click(function(){
         var id = $(this).attr('portal_id');
         var title = $(this).attr('title');
         var url = $(this).attr('url');
         
         if(from_ispirit)
            window.external.OA_SMS(url, "MAX", "OPEN_URL");
         else if(typeof(top.openURL) == 'function')
            top.openURL('portal_' + id, title, url);
         else
            window.open(url, 'portal_' + id);
      });
   });
})(jQuery);