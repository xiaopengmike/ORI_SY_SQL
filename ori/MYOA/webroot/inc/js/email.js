function getNewMailLocation(pos){

    var EMAIL_ID = jQuery("table.mail_list tr.active").attr("email_id");      
    var is_webmail = jQuery("table.mail_list tr.active").attr("is_webmail");

    if(pos == 1)
       return "./new/?REPLAY=0&BTN_CLOSE=1&EMAIL_ID="+EMAIL_ID+"&BOX_ID=0&FIELD=&ASC_DESC=&IS_WEBMAIL="+is_webmail;
    else if(pos == 3)
        return "../new/?FW=1&BTN_CLOSE=1&EMAIL_ID="+EMAIL_ID+"&BOX_ID=0&FIELD=&ASC_DESC=&IS_WEBMAIL="+is_webmail;
    else if(pos == 6)
        return "./new/?FW=1&EMAIL_ID="+EMAIL_ID+"&BOX_ID=0&FIELD=&ASC_DESC=&IS_WEBMAIL="+is_webmail;
    else if(pos == 2)
       return "../new/?BTN_CLOSE=1&REPLAY=1&EMAIL_ID="+EMAIL_ID+"&BOX_ID=0&FIELD=&ASC_DESC=&IS_WEBMAIL="+is_webmail;

    else
 	 return "../new/?BTN_CLOSE=1&REPLAY=0&EMAIL_ID="+EMAIL_ID+"&BOX_ID=0&FIELD=&ASC_DESC=&IS_WEBMAIL="+is_webmail;
 
}

function ResendMailLocation(){
    var email_id = jQuery("table.mail_list tr.active").attr("email_id");
    return "resend.php?EMAIL_ID="+email_id;
}
function FwsendMailLocation(){
    var email_id = jQuery("table.mail_list tr.active").attr("email_id");
    return "sent_fw.php?EMAIL_ID="+email_id;
}
function EditMailLocation(){
    var edit_url = jQuery("table.mail_list tr.active").attr("edit_url");
    return edit_url;
}
function initDocument(){
	
   jQuery("table.mail_list tr").live("click",function(){
      if(jQuery(this).attr("class").indexOf('active') >= 0)
         return;
      
      jQuery("table.mail_list tr").removeClass('active');
      jQuery(this).addClass("active");
		  jQuery("#iFrame2").attr("src", jQuery(this).attr("url"));   
		 if(jQuery('td.unread', this).length > 0)
		 { 
         var count = parseInt(parent.jQuery('#new_count').text());
         count = isNaN(count) ? 0 : count;
         parent.showEmailNew(count-1>0);
		 
         if(count>0&&count!=1)
           parent.jQuery('#new_count').text(count-1);
         else if(count==1)
		     parent.jQuery('#new_count').text("");
         else	 parent.jQuery('#new_count').text();
		  
         jQuery('td.unread', this).removeClass("unread");
         jQuery('td.state', this).addClass("read");
           
         }
        
      
      //控制编辑和再次发送
      var group_count = jQuery(this).attr("group_count");
      var read_flag = jQuery(this).attr("read_flag");
	   var is_webmail = jQuery(this).attr("is_webmail");
	   var no_reply = jQuery(this).attr("no_reply");
	   var read_flag_all=jQuery(this).attr("read_flag_all");

		jQuery("#btn_reply_all").css("display","inline-block");
 
      if(group_count == "1") {
         if(read_flag == "0") {
            jQuery("#btn_edit").css("display","inline-block");
         }else{
            jQuery("#btn_edit").css("display","none");
         }
      } 
      else
      {
      		  if(read_flag_all == "0") {
            jQuery("#btn_edit").css("display","inline-block");
           }else{
            jQuery("#btn_edit").css("display","none");
           }
      		
      }   
      if(no_reply == "1")
         jQuery("#btn_reply").css("display","none");
      else
         jQuery("#btn_reply").css("display","inline-block");
   });
   
   //双击单行
   jQuery("table.mail_list tr").live("dblclick",function(){
      jQuery("table.mail_list tr").removeClass('active');
      jQuery(this).addClass("active");
      window.open(jQuery(this).attr("url")+"&BTN_CLOSE=1");
   });
   
   jQuery("table.mail_list tr").each(function(){
      var obj = this;
      jQuery('a.open', obj).click(function(){
         window.open(jQuery(obj).attr("url")+"&BTN_CLOSE=1");
      });
   });
   
   //jQuery("table.mail_list tr:last > td").addClass("no-bottom-border");
   if(typeof(bReadLastOne) != 'undefined' && bReadLastOne == "1")
      jQuery("table.mail_list tr:last").trigger("click");
   else
      jQuery("table.mail_list tr:first").trigger("click");
   
   //2010-7-5 LP next email
   jQuery("#btn_next").click(function(){
      //返回选中列的索引
      var now_count = jQuery("table.mail_list tr.active").index();
      if(now_count< jQuery("table.mail_list tr").length-1) {
         jQuery("table.mail_list tr").eq((now_count+1)).trigger("click");
      }else{
         var url = jQuery("#pageNext").attr("href");
         url = url.replace("&READ_LAST=0", "");
         url = url.replace("&READ_LAST=1", "");
         if(url.indexOf('email') >= 0)
            url += "&READ_LAST=0";
         var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
         if(is_ie)
            window.event.returnValue = false;
         location = url;
      }
   });
   
   //2010-7-5 LP prev email
   jQuery("#btn_prev").click(function(){
      //返回选中列的索引
      var now_count = jQuery("table.mail_list tr.active").index();
      if(0 < now_count) {
         jQuery("table.mail_list tr").eq((now_count-1)).trigger("click");
      }else{
         var url = jQuery("#pagePrevious").attr("href");
         url = url.replace("&READ_LAST=0", "");
         url = url.replace("&READ_LAST=1", "");
         if(url.indexOf('email') >= 0)
            url += "&READ_LAST=1";
         var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
         if(is_ie)
            window.event.returnValue = false;
         location = url;
      }
   });
   
   //2010-7-6 LP print mail
//   jQuery("#btn_print").click(function(){
//      var EMAIL_ID = jQuery("table.mail_list tr.active").attr("email_id");
//      window.open('../print.php?EMAIL_ID='+EMAIL_ID,'','menubar=1,toolbar=1,status=1,resizable=1');   
//   });
   
   //2010-7-6 LP replay mail
   jQuery("#btn_reply").click(function(){return;
      var EMAIL_ID = jQuery("table.mail_list tr.active").attr("email_id");      
	    var is_webmail = jQuery("table.mail_list tr.active").attr("is_webmail");
	    URL="../new/?REPLAY=0&EMAIL_ID="+EMAIL_ID+"&BOX_ID=0&FIELD=&ASC_DESC=&IS_WEBMAIL="+is_webmail;	    
	    window.location=URL;  
	    //window.open(URL);
      
   });
   
   jQuery("#btn_reply_new").click(function(){
   	  var EMAIL_ID = jQuery("table.mail_list tr.active").attr("email_id");      
	    var is_webmail = jQuery("table.mail_list tr.active").attr("is_webmail");
	    URL="../new/?REPLAY=0&EMAIL_ID="+EMAIL_ID+"&BOX_ID=0&FIELD=&ASC_DESC=&IS_WEBMAIL="+is_webmail;
      window.location=URL;      
   });
   //20010-11-4 fw_mail 点击转发
      jQuery("#btn_fw").click(function(){
   	  var EMAIL_ID = jQuery("table.mail_list tr.active").attr("email_id");      
	    var is_webmail = jQuery("table.mail_list tr.active").attr("is_webmail");
	    URL="../new/?FW=1&EMAIL_ID="+EMAIL_ID+"&BOX_ID=0&FIELD=&ASC_DESC=&IS_WEBMAIL="+is_webmail;
      window.location=URL;      
   });
        
   
   //点击发送按钮
   jQuery("#btn_resend").live("click",function(){
      var email_id = jQuery("table.mail_list tr.active").attr("email_id");
     // window.location = 'resend.php?EMAIL_ID='+email_id;   
   });
    //点击转发按钮
   jQuery("#btn_fw").live("click",function(){
      var email_id = jQuery("table.mail_list tr.active").attr("email_id");
     // window.location = 'resend.php?EMAIL_ID='+email_id;   
   });
   
   
   //点击编辑按钮
   jQuery("#btn_edit").live("click",function(){
      var edit_url = jQuery("table.mail_list tr.active").attr("edit_url");
	  if(!edit_url){alert(td_lang.inc.msg_21);return;}//"页面tr未绑定数据edit_url"
      //window.location = edit_url;   
   });

    //2010-7-15 del mail
    jQuery("#btn_del").click(function(){
	  delete_mail_noconfirm();
   });
}