jQuery.noConflict();
(function($){
   $(document).ready(function($){   
	   var focuscount=0;
	   var focusmenu;
      //关闭按钮点击事件
      $('div.caption a.close').click(function(){
         if(typeof(top.closeTaskCenter) == 'function')
            top.closeTaskCenter();
         else if(typeof window.external != 'undefined' && typeof(window.external.OA_SMS) != 'undefined')
            window.external.OA_SMS("", "", "CLOSE_WINDOW")
         else
            window.close();
      });
	  //点击新建任务
      $('div.left_button a.new_task').click(function(){
         $('#right_title').html($(this).attr('title'));
         $('#right_content').html(loading);
         $("#right_title").attr("title",td_lang.inc.msg_159);
         var index = $(this).attr('index');
         $.ajax({
            type: 'GET',
            url: 'new_task.php',
            cache: false,
            success: function(data){
            	$('#right_content').html(data);
            	$("#right_title").attr("title",td_lang.inc.msg_159);
            },
            error: function(request, textStatus, errorThrown){
               $('#right_content').html(load_error);
            }
         });
      });
	  //点击收件箱
      $('div.left_button a.inbox').click(function(){
         $('#right_content').html(loading);
			focusmenu=$('span', this);
         var newtitle=$(this).attr('title');
        
         var index = $(this).attr('index');
         $.ajax({
            type: 'GET',
            url: 'getData.php',
            data: {'LEFTMENU': $(this).attr('index')},
            cache: false,
            success: function(data){
            try
				{
					var CATAGORY_ARRAY=eval('('+data+')');//json格式字符串转为json对象
				}
				catch(ex)
				{
					$('#right_content').html(CreateNoTaskHTML());
					$('#right_title').attr('data-type', index).html(newtitle);
					$('.inbox span').text(newtitle)
					$("#right_title").attr("title",td_lang.inc.msg_157);
					return;
				}$('#right_title').html(newtitle);
            var MODULE_BODY="";
				var taskcount=0;
				var INDEX;
				for(var CATAGORY in CATAGORY_ARRAY)
				{
				   if(CATAGORY == "calendar")
						INDEX = "2";
				   else if(CATAGORY == "task")
						INDEX = "3";
				   else if(CATAGORY == "workflow")
						INDEX = "4";
               else INDEX='6';			   
               TASKS=CATAGORY_ARRAY[CATAGORY];			   
				   for(i=0;i<TASKS.length;i++)
				   {
				      MODULE_BODY += CreateItemHTML(INDEX, TASKS[i], '0');
					   taskcount++;
				   }
				}
					focuscount=taskcount;
					$('#right_content').html(MODULE_BODY);
					$('.inbox span').text(newtitle+"("+taskcount+")");
				   $('#right_title').attr('data-type', index).html(newtitle+"("+taskcount+")");
				   $("#right_title").attr("title",td_lang.inc.msg_157);
            },
            error: function(request, textStatus, errorThrown){
               $('#right_content').html(load_error);
            }
         });
      });
      //左侧今日菜单点击事件
      $('a.today').click(function(){    
			$('#right_content').html(loading);
		 	var newtitle=$(this).text();
			focusmenu=$(this);
		 	newtitle=newtitle.split('(')[0];
         var index = $(this).attr('index');
         $.ajax({
            type: 'GET',
            url: 'getData.php',
            data: {'LEFTMENU': $(this).attr('index')},
            cache: false,
            success: function(data){
				try{
				var CATAGORY_ARRAY=eval('('+data+')');//json格式字符串转为json对象
				}
				catch(ex)
				{
					$('#right_content').html(CreateNoTaskHTML()); 
	                $('#right_title').attr('data-type', index).html(newtitle);
	                $('#today').text(newtitle);
	                $("#right_title").attr("title",td_lang.inc.msg_155);
					return;
				}
            var MODULE_BODY="";
				var taskcount=0;
				var INDEX;
				for(var CATAGORY in CATAGORY_ARRAY)
				{
				   if(CATAGORY == "calendar")
						INDEX = "2";
				   else if(CATAGORY == "task")
					 	INDEX = "3";
				   else if(CATAGORY == "workflow")
						INDEX = "4";
               else INDEX='6';			   
                   TASKS=CATAGORY_ARRAY[CATAGORY];			   
				   for(i=0;i<TASKS.length;i++)
				   {
				      MODULE_BODY += CreateItemHTML(INDEX, TASKS[i], '0');
					   taskcount++;
				   }
				}
					focuscount=taskcount;
					$('#right_content').html(MODULE_BODY);
					$('#today').text(newtitle+"("+taskcount+")");
					$('#right_title').attr('data-type', index).html(newtitle+"("+taskcount+")");
					$("#right_title").attr("title",td_lang.inc.msg_155);				
            },
            error: function(request, textStatus, errorThrown){
               $('#right_content').html(load_error);
            }
         });
      });
	  //接下来
	   $('a.next').click(function(){
         $('#right_content').html(loading);
			var newtitle=$(this).text();
			focusmenu=$(this);
	      newtitle=newtitle.split('(')[0];
         var index = $(this).attr('index');
         $.ajax({
            type: 'GET',
            url: 'getData.php',
            data: {'LEFTMENU': $(this).attr('index')},
            cache: false,
            success: function(data){
				try{
				var CATAGORY_ARRAY=eval('('+data+')');//json格式字符串转为json对象
				}
				catch(ex)
				{
					$('#right_content').html(CreateNoTaskHTML()); 
	                $('#right_title').attr('data-type', index).html(newtitle);
	                $('#next').text(newtitle);
	                $("#right_title").attr("title",td_lang.inc.msg_156);
					return;
				}
            var MODULE_BODY="";
				var taskcount=0;
				var INDEX;
				for(var CATAGORY in CATAGORY_ARRAY)
				{
				   if(CATAGORY == "calendar")
						INDEX = "2";
				   else if(CATAGORY == "task")
						INDEX = "3";
				   else if(CATAGORY == "workflow")
						INDEX = "4";
               else INDEX='6';
               TASKS=CATAGORY_ARRAY[CATAGORY];	   
				   for(i=0;i<TASKS.length;i++)
				   {
				      MODULE_BODY += CreateItemHTML(INDEX, TASKS[i], '0');
					   taskcount++;
				   }
				  
				}
				focuscount=taskcount;
            	$('#right_content').html(MODULE_BODY);
					$('#next').text(newtitle+"("+taskcount+")");
					$('#right_title').html(newtitle+"("+taskcount+")");
					$("#right_title").attr("title",td_lang.inc.msg_156);
            },
            error: function(request, textStatus, errorThrown){
               $('#right_content').html(load_error);
            }
         });
      });
      //倒计时	
      $('div.menu a.count').click(function(){
      	
         $('#right_title').html($(this).text()+"<div id='count_op'><a class='add_count' id='add_count' href='javascript:;' title='"+td_lang.inc.msg_143+"'>"+td_lang.inc.msg_159+"</a><a class='flush' id='flush' href='javascript:;' title='"+td_lang.inc.msg_166+td_lang.inc.msg_158+"'>"+td_lang.inc.msg_166+"</a></div>");
         $('#right_content').html(loading);
		   focusmenu=$(this);
         var newtitle=$(this).text();
     	   newtitle=newtitle.split('(')[0];
		   var index = $(this).attr('index');
		   
         $.ajax({
            type: 'GET',
            url: 'count.php',
            cache: false,
            success: function(data){
				try
				{
				   var CATAGORY_ARRAY=eval('('+data+')');//json格式字符串转为json对象
				}
				catch(ex)
				{
					$('#right_content').html(CreateNoTaskHTML()); 
	            $("#right_title").attr('data-type', index).attr("title",td_lang.inc.msg_158);
	            $('#right_title').html(newtitle+"<div id='count_op'><a class='add_count' id='add_count1' href='javascript:;' title='"+td_lang.inc.msg_143+"'>"+td_lang.inc.msg_159+"</a><a class='flush' id='flush' href='javascript:;' title='"+td_lang.inc.msg_166+td_lang.inc.msg_158+"'>"+td_lang.inc.msg_166+"</a></div>");
					return;
				}
				var MODULE_BODY="";
				var taskcount=0;
            var INDEX;
			   for(var CATAGORY in CATAGORY_ARRAY)
				{
				   if(CATAGORY == "public")
						INDEX = "1";
				   else if(CATAGORY == "private")
						INDEX = "2";
				   else
				   	INDEX='6';
               TASKS=CATAGORY_ARRAY[CATAGORY];
               for(i=0;i<TASKS.length;i++)
				   {
					   MODULE_BODY+='<div class="item itemred_'+INDEX+'">';
 					   MODULE_BODY+='<div class="subject1"><div class="text">'+TASKS[i]+'</div></div></div>';
					   taskcount++;
				   }
					
				}
            focuscount=taskcount;
         	$('#right_content').html("<center class='count_body'>"+MODULE_BODY+"</center>");
				$('#count').text(newtitle+"("+taskcount+")");
				$("#right_title").attr('data-type', index).attr("title",td_lang.inc.msg_158);
								
				$.ajax({
		        	 
		        	 type: 'GET',
		             url: 'count_img.php',
		             cache: false,
		             success: function(data){
		              if(data==1)
		            	{
		            	  $("#count").html($('#count').text()+"<span id='count_img' class='count_img' title="+td_lang.inc.msg_167+"></span>");
		            	  //有3日内的事件右侧加上图标
		            	  if($('#right_title').attr('data-type') == index){
		            	  	$('#right_title').html($('#count').text()+"<div id='rock_display'><span id='count_img' class='count_img' title="+td_lang.inc.msg_167+"></span><span id='font_display'>"+td_lang.inc.msg_167+"</span></div><div id='count_op'><a class='add_count' id='add_count' href='javascript:;' title='"+td_lang.inc.msg_143+"'>"+td_lang.inc.msg_159+"</a><a class='flush' id='flush' href='javascript:;' title='"+td_lang.inc.msg_166+td_lang.inc.msg_158+"'>"+td_lang.inc.msg_166+"</a></div>"); 
		            	  }
		            	}
		              else if(data==2)
		            	{
		            	  $("#count").html($('#count').text()+"<span id='count_img1' class='count_img1' title="+td_lang.inc.msg_168+"></span>");
		            	  //有7日内的事件右侧加上图标
		            	  if($('#right_title').attr('data-type') == index){
		            	    $('#right_title').html($('#count').text()+"<div id='rock_display'><span id='count_img1' class='count_img1' title="+td_lang.inc.msg_168+"></span><span id='font_display'>"+td_lang.inc.msg_168+"</span></div><div id='count_op'><a class='add_count' id='add_count' href='javascript:;' title='"+td_lang.inc.msg_143+"'>"+td_lang.inc.msg_159+"</a><a class='flush' id='flush' href='javascript:;' title='"+td_lang.inc.msg_166+td_lang.inc.msg_158+"'>"+td_lang.inc.msg_166+"</a></div>"); 
		            	  }
		            	}  
		             }
		         });	
            },
            error: function(request, textStatus, errorThrown){
               $('#right_content').html(load_error);
            }
         });
       
         
      });
       //'刷新倒计时牌'
       $('#flush').live('click', function(){
   	 	jQuery('div.menu a.count').trigger("click");
      		});
       $('#add_count').live('click', function(){
	         $('#title').html(td_lang.inc.msg_143);//'新建倒计时牌'
	   		 ShowDialog('dialog',10);
	   		 $.get("new_count.php",{TC_ID:1}, function(data){
	               $('#dialog_body').html(data);	
	               $('#CONTENT').focus();			
	            });
      		});
	      $('#add_count1').live('click',function(){
	      $('#title').html(td_lang.inc.msg_143);//'新建倒计时牌'
			 ShowDialog('dialog',10);
			 $.get("new_count.php",{IS_NEW:1}, function(data){ 		 	 
	            $('#dialog_body').html(data);	
	            $('#CONTENT').focus();		
	         });

   		});  
			//新建保存倒计时
			$("#set_count").live('click', function(){
				if($('#CONTENT').val()=="")
				{
				  alert(td_lang.inc.msg_150);
				  return false;
				}
				if($('#END_TIME_D').val()=="")
				{ 			
					alert(td_lang.inc.msg_151);
					return (false);
				}
				var cur_date=$("#CUR_DATE_N").val();
				var end_date=$("#END_TIME_D").val();
				var ROW_ID=$('#ROW_ID').val();
				$.get("new_add.php", {
					ORDER_NO:encodeURI($('#ORDER_NO').val()),
					CONTENT:encodeURI($('#CONTENT').val()),
					STYLES:$('#STYLE').val(),
					END_TIME_D:$('#END_TIME_D').val(),
					ROW_ID:$('#ROW_ID').val(),
					IS_NEW:$('#IS_NEW').val()
				}, 
				function(data){
					if(data == "1" )
	            {  
	            	if(ROW_ID=="")
	            	{
	            		if($('#IS_NEW').val()==1)
	            		{
	            			focuscount=0;
	            			focuscount++;
	            		}
	            		else
					      	focuscount++;
							var rightitle=focusmenu.text();
							rightitle=rightitle.split('(')[0];
					   	$('#right_title').html(rightitle+"("+focuscount+")");
							focusmenu.text(rightitle+"("+focuscount+")");
							}
							$('#count').trigger("click");
				   }
	            else 
	               alert(data);
         });
		 	HideDialog("dialog");        
      });
       $(".edit_count").live('mouseover', function(){
		     $(this).addClass('edit_count1');
			            });
       $(".edit_count").live('mouseout', function(){
		      $(this).removeClass('edit_count1');
			            });
       $(".del_count").live('mouseover', function(){
		     $(this).addClass('del_count1');
			            });
       $(".del_count").live('mouseout', function(){
		      $(this).removeClass('del_count1');
			            });
      //编辑倒计时
       $(".edit_count").live('click', function(){
			        $('#title').html(td_lang.inc.msg_144);//'编辑倒计时牌'
			  			ShowDialog('dialog',10);
			  			$.get("new_count.php",{ROW_ID:$(this).attr('edit_id')}, function(data){
			               $('#dialog_body').html(data);		
			            });
             });
      //删除倒计时。。。。。。。。。。。。。。。。。。。。。。
     $(".del_count").live('click', function(){
      	if(!window.confirm(td_lang.inc.msg_13))//"删除后将不可恢复,确定要删除吗？"
          return;
       $.get("new_add.php",{ROW_ID:$(this).attr('del_id'),IS_DEL:1}, function(data){
              if(data==1)	
              {
              	if(focuscount>0)
               	focuscount--;
				var rightitle=focusmenu.text();
				rightitle=rightitle.split('(')[0];
				if(focuscount<=0)
				{
					$('#right_title').html(rightitle);
					focusmenu.text(rightitle);
				}else
				{	
			   		$('#right_title').html(rightitle+"("+focuscount+")");
					focusmenu.text(rightitle+"("+focuscount+")");
				}
				$('#count').trigger("click");
              	
              }
            });
         });
   
	  //左侧已忽略菜单点击事件
      $('a.ignored').click(function(){
         
         $('#right_content').html(loading);
		 var newtitle=$(this).text();
		 focusmenu=$(this);
		 newtitle=newtitle.split('(')[0];
         var index = $(this).attr('index');
         $.ajax({
            type: 'GET',
            url: 'ignored.php',
            data: {'LEFTMENU': $(this).attr('index')},
            cache: false,
            success: function(data){
				try{
				var CATAGORY_ARRAY=eval('('+data+')');//json格式字符串转为json对象
				}
				catch(ex)
				{
					$('#right_content').html(CreateNoTaskHTML()); 
	                $('#right_title').html(newtitle);
	                $('#ignored').text(newtitle);
	                $("#right_title").attr("title",td_lang.inc.msg_154);
					return;
				}
                var MODULE_BODY="";
				var taskcount=0;
				var INDEX;
				for(var CATAGORY in CATAGORY_ARRAY)
				{
				   
				   if(CATAGORY == "calendar")
					  INDEX = "2";
				   else if(CATAGORY == "task")
					  INDEX = "3";
				   else if(CATAGORY == "workflow")
					  INDEX = "4";
                   else INDEX='5';
                   TASKS=CATAGORY_ARRAY[CATAGORY];
				   
				   for(i=0;i<TASKS.length;i++)
				   {
				      MODULE_BODY += CreateItemHTML(INDEX, TASKS[i], '1');
					   taskcount++;
				   }
				  
				}
				focuscount=taskcount;
            	$('#right_content').html(MODULE_BODY);
				$('#ignored').text(newtitle+"("+taskcount+")");
				$('#right_title').html(newtitle+"("+taskcount+")");
				$("#right_title").attr("title",td_lang.inc.msg_154);
            },
            error: function(request, textStatus, errorThrown){
               $('#right_content').html(load_error);
            }
         });
      });
      //左侧已推迟任务点击事件 ljc
      $('a.delaytime').click(function(){
         $('#right_content').html(loading);
		 var newtitle=$(this).text();
		 focusmenu=$(this);
		 newtitle=newtitle.split('(')[0];
         var index = $(this).attr('index');
         $.ajax({
            type: 'GET',
            url: 'delaytime.php',
            data: {'LEFTMENU': $(this).attr('index')},
            cache: false,
            success: function(data){
            
				try{
				var CATAGORY_ARRAY=eval('('+data+')');//json格式字符串转为json对象
				
				}
				catch(ex)
				{
					$('#right_content').html(CreateNoTaskHTML()); 
	                $('#right_title').html(newtitle);
	                $('#delaytime').text(newtitle);
	               $("#right_title").attr("title",td_lang.inc.msg_153);
					return;
				}
               
                var MODULE_BODY="";
				var taskcount=0;
				var INDEX;
				for(var CATAGORY in CATAGORY_ARRAY)
				{
				   
				   if(CATAGORY == "calendar")
					  INDEX = "2";
				   else if(CATAGORY == "task")
					  INDEX = "3";
				   else if(CATAGORY == "workflow")
					  INDEX = "4";
                   else INDEX='6';
				   
                   TASKS=CATAGORY_ARRAY[CATAGORY];
				   for(i=0;i<TASKS.length;i++)
				   {
				      MODULE_BODY += CreateItemHTML(INDEX, TASKS[i], '2');
					   taskcount++;
				   }
				  
				}
				focuscount=taskcount;
            	$('#right_content').html(MODULE_BODY);
				$('#delaytime').text(newtitle+"("+taskcount+")");
				$('#right_title').html(newtitle+"("+taskcount+")");
				$("#right_title").attr("title",td_lang.inc.msg_153);
				
            },
            error: function(request, textStatus, errorThrown){
               $('#right_content').html(load_error);
            }
         });
      });
      //右侧项目标题的点击事件
      $('#right_content div.subject').live('click', function(){
      $('#title').html(td_lang.inc.msg_146);//查看详情
         if($(this).attr('_ajax'))
         {
            ShowDialog('dialog', 10);
            $.get($(this).attr('_ajax'), function(data){
               $('#dialog_body').html(data);
            });
         }
         else if($(this).attr('_url'))//工作流
         {
            var item = $(this).parent();//先删除
	        item.remove();
			if(focuscount>0)
			   focuscount--;
			var rightitle=focusmenu.text();
			rightitle=rightitle.split('(')[0];
			$('#right_title').html(rightitle+"("+focuscount+")");
			focusmenu.text(rightitle+"("+focuscount+")");
				
			if(top.bTabStyle)//时尚主题完整版界面下打开
               top.openURL('', '', $(this).attr('_url'), '1'); 
            else
               window.open($(this).attr('_url'));
         }
      });
      //日程点击“完成”
	  $('#cal_ok,#task_ok').live('click',function(){
		 var item=$("#list_"+$(this).attr('item_id')); 
         $.get("setData.php", {ACTION:$(this).attr('action'), TC_ID:$(this).attr('item_id') ,SOURCE_ID:$(this).attr('name')}, function(data){
            if(data == "1")
            {   item.remove();
			    if(focuscount>0)
			       focuscount--;
				var rightitle=focusmenu.text();
				rightitle=rightitle.split('(')[0];
				if(focuscount<=0)
				{
					$('#right_title').html(rightitle);
					focusmenu.text(rightitle);	
					$('#right_content').html(CreateNoTaskHTML()); 
				}	
				else
				{
					$('#right_title').html(rightitle+"("+focuscount+")");
					focusmenu.text(rightitle+"("+focuscount+")");
				}					
			}
            else
               alert(data);
         });
		 HideDialog("dialog");
       });
	   //右侧项目的推迟点击事件
      $(".delay").live('click', function(){
      	 $('#title').html(td_lang.inc.msg_145);//'推迟设置'
   		 ShowDialog('dialog',10);
		 $.get("delay_time.php",{TC_ID:$(this).attr('item_id')}, function(data){
               $('#dialog_body').html(data);			
            });
      });
      
      $("#radiotime_2").live('click',function(){
    	  
          var delayday=$("#delayday").val();

     	  var now_date=$("#CUR_DATE").val();
     	  var radio=$(':input:radio:checked').val();
     	  if(delayday!="")
     	  {	  
     	      var delay_time_str=js_strto_time(now_date) + delayday*24*3600;
	  	      var delay_time_daystr=js_date_time(delay_time_str);
	  	      var content=td_lang.inc.msg_160+" "+now_date+" "+td_lang.inc.msg_161+" "+delay_time_daystr;
    	      $("#showDelayTime").html(content);
          }
     	 // else $("#showDelayTime").html("");
       });
      
       $("#delayday").live('blur',function(){
    	  
          var delayday=$("#delayday").val();

     	  var now_date=$("#CUR_DATE").val();
     	  var radio=$(':input:radio:checked').val();
     	  if(radio==2 && delayday!="")
     	  {	  
     	    var delay_time_str=js_strto_time(now_date) + delayday*24*3600;
	  	    var delay_time_daystr=js_date_time(delay_time_str);
	  	    var content=td_lang.inc.msg_160+" "+now_date+" "+td_lang.inc.msg_161+" "+delay_time_daystr;
    	    $("#showDelayTime").html(content);    	 
     	  }
       });
      
      
       $("#radiotime_1").live('click',function(){
    	      	  
     	  var delayhour=$("#delayhour").val();
     	  var now_date=$("#CUR_DATE").val();
     	  if(delayhour!="")
     	  {	  
     	     var delay_time_str=js_strto_time(now_date) + delayhour*3600;
	  	     var delay_time_daystr=js_date_time(delay_time_str);
     	     var content=td_lang.inc.msg_160+" "+now_date+" "+td_lang.inc.msg_161+" "+delay_time_daystr;
     	    $("#showDelayTime").html(content);
     	  }
       });
      $("#delayhour").live('blur',function(){    	  
    	  var delayhour=$("#delayhour").val();
    	  var now_date=$("#CUR_DATE").val();
    	  var radio=$(':input:radio:checked').val();
    	  if(radio==1 && delayhour!="")
    	  {
    		  var delay_time_str=js_strto_time(now_date) + delayhour*3600;
    	  	  var delay_time_daystr=js_date_time(delay_time_str);
         	  var content=td_lang.inc.msg_160+" "+now_date+" "+td_lang.inc.msg_161+" "+delay_time_daystr;
         	  $("#showDelayTime").html(content);  
    	  }	  
      });
      
      $("#radiotime_3").live('click',function(){
    	  var delaydate=$("#delaydate").val();
    	  var now_date=$("#CUR_DATE").val(); 
    	  if(delaydate!="")
    	  {
    		  var content=td_lang.inc.msg_160+" "+now_date+" "+td_lang.inc.msg_161+" "+delaydate;
           	 $("#showDelayTime").html(content);
    	  }
    	  else
    		  $("#showDelayTime").html("");
      });
      
      $("#delaydate").live('blur',function(){
    	  var delaydate=$("#delaydate").val();
    	  var now_date=$("#CUR_DATE").val(); 
    	  var radio=$(':input:radio:checked').val();
    	  if(radio==3 && delaydate!="")
    	  {
    		 var content=td_lang.inc.msg_160+" "+now_date+" "+td_lang.inc.msg_161+" "+delaydate;
          	 $("#showDelayTime").html(content);
    	  }	
    	  else $("#showDelayTime").html("");
      });
      
	  //推迟时间确定事件
      $("#set_time").live('click', function(){
      	//zlp
          var delayday=$("#delayday").val();
    	  
    	  var delayhour=$("#delayhour").val();
    	  var delaydate=$("#delaydate").val();
    	  var now_date=$("#CUR_DATE").val();
    	  var radio=$(':input:radio:checked').val();
    	  var delay_time_str='';
    	  var delay_time_daystr='';  
    	  if(radio==2 && delayday=="" || radio==1 && delayhour=="" || radio==3 && delaydate=="")
    	  {
    		  alert(td_lang.inc.msg_147);
    		  return false;
    	  }
    	  if(radio==2 && delayday!="" && !IsNumber(delayday) || radio==1 &&delayhour!="" && !IsNumber(delayhour))
    	  {
    		  alert(td_lang.inc.msg_148);
    		  return false;
    	  }
    	  if(delaydate!="" && radio==3)
    	  {   	  
    	      var nowdate=now_date.split(" ");    	     	      	  
    	      var v_date=nowdate[0].split("-");
    	      var v_time=nowdate[1].split(":");   
    	      var delay_time=delaydate.split(" ");
    	      var de_date=delay_time[0].split("-");
    	      var de_time=delay_time[1].split(":");
    	      var v_now=new Date(v_date[0],v_date[1],v_date[2],v_time[0],v_time[1],v_time[2]);
    	      var v_delay=new Date(de_date[0],de_date[1],de_date[2],de_time[0],de_time[1],de_time[2]);

    	      if(v_delay!="" && v_delay<v_now )
    	      {
    		      alert(td_lang.inc.msg_149);
    		      return false;
    	      }	 
    	  } 
         var item=$("#list_"+$(this).attr('tc_id')); 
         $.get("setData.php", {
			 ACTION:$(this).attr('action'),
			 TC_ID:$(this).attr('tc_id'),
			 selectradio:$(':input:radio:checked').val(),
			 delayhour:$('#delayhour').val(),
			 delayday:$('#delayday').val(),
			 delaydate:$('#delaydate').val(),
			 curdaydate:$("#CUR_DATE").val()
			 }, function(data){
            if(data == "1")
            {   
            	var rightitle=focusmenu.text();
            	var  newtitle=rightitle.split('(')[0];
            	rightitle=rightitle.split('(')[0];
            	
            	if(newtitle=='接下来')
            	{           		
            		$.ajax({
			            type: 'GET',
			            url: 'getData.php',
			            data: {'LEFTMENU': 'next'},
			            cache: false,
			            success: function(data){
							try{
							var CATAGORY_ARRAY=eval('('+data+')');//json格式字符串转为json对象
							}
							catch(ex)
							{
								$('#right_content').html(CreateNoTaskHTML()); 
				                $('#right_title').html(newtitle);
				                $('#next').text(newtitle);
				                $("#right_title").attr("title",td_lang.inc.msg_156);
								return;
							}
			                var MODULE_BODY="";
							var taskcount=0;
							var INDEX;
							for(var CATAGORY in CATAGORY_ARRAY)
							{
							   
							   if(CATAGORY == "calendar")
								  INDEX = "2";
							   else if(CATAGORY == "task")
								  INDEX = "3";
							   else if(CATAGORY == "workflow")
								  INDEX = "4";
			                   else INDEX='6';
							   
			                   TASKS=CATAGORY_ARRAY[CATAGORY];	   
							   for(i=0;i<TASKS.length;i++)
							   {
							      MODULE_BODY += CreateItemHTML(INDEX, TASKS[i], '0');
								   taskcount++;
							   }
							  
							}

							focuscount=taskcount;
			            	$('#right_content').html(MODULE_BODY);
							$('#next').text(newtitle+"("+taskcount+")");
							$('#right_title').html(newtitle+"("+taskcount+")");
							$("#right_title").attr("title",td_lang.inc.msg_156);
							
			            },
			            error: function(request, textStatus, errorThrown){
			               $('#right_content').html(load_error);
			            }
			         });
            		
            	}
            	else if(newtitle=="今日")
            	{
            		 $.ajax({
            	            type: 'GET',
            	            url: 'getData.php',
            	            data: {'LEFTMENU': 'today'},
            	            cache: false,
            	            success: function(data){
            					try{
            					var CATAGORY_ARRAY=eval('('+data+')');//json格式字符串转为json对象
            					}
            					catch(ex)
            					{
            						$('#right_content').html(CreateNoTaskHTML()); 
            		                $('#right_title').html(newtitle);
            		                $('#today').text(newtitle);
            		                $("#right_title").attr("title",td_lang.inc.msg_155);
            						return;
            					}
            	                var MODULE_BODY="";
            					var taskcount=0;
            					var INDEX;
            					for(var CATAGORY in CATAGORY_ARRAY)
            					{
            					   
            					   if(CATAGORY == "calendar")
            						  INDEX = "2";
            					   else if(CATAGORY == "task")
            						  INDEX = "3";
            					   else if(CATAGORY == "workflow")
            						  INDEX = "4";
            	                   else INDEX='6';
            					   
            	                   TASKS=CATAGORY_ARRAY[CATAGORY];
            					   				   
            					   for(i=0;i<TASKS.length;i++)
            					   {
            					      MODULE_BODY += CreateItemHTML(INDEX, TASKS[i], '0');
            						   taskcount++;
            					   }
            					  
            					}
            					focuscount=taskcount;
            	            	$('#right_content').html(MODULE_BODY);
            					$('#today').text(newtitle+"("+taskcount+")");
            					$('#right_title').html(newtitle+"("+taskcount+")");
            					$("#right_title").attr("title",td_lang.inc.msg_155);
            					
            	            },
            	            error: function(request, textStatus, errorThrown){
            	               $('#right_content').html(load_error);
            	            }
            	         });
            	}           	
            	else
            	{	
            	  item.remove();
			      if(focuscount>0)
			        focuscount--;
			      if(focuscount<=0)
			      {	
			    	focusmenu.text(rightitle);
			    	$('#right_title').html(rightitle);
			    	
			    	$('#right_content').html(CreateNoTaskHTML());
			      }
			      else
			      {	
			    	focusmenu.text(rightitle+"("+focuscount+")");
			    	$('#right_title').html(rightitle+"("+focuscount+")");
			      }
				  var delaytitle=$('a.delaytime').text();
				  if(delaytitle.indexOf('(')>0)
				  {	  
				      tmptitle=delaytitle.split('(')[0];				
				      var tmpnum=delaytitle.split('(')[1].split(')')[0];				
				      tmpnum++;					
				      $('a.delaytime').text(tmptitle+"("+tmpnum+")");
				  }
				  else
				  {
					  $('a.delaytime').text(delaytitle+"(1)");
				  }
			  }
            }
            else
            	{
                 alert(data);
            }
         });
		 HideDialog("dialog");
      });
      //右侧已推迟项目的推迟点击事件ljc
      $(".delay_set").live('click', function(){
      	 $('#title').html(td_lang.inc.msg_145);//'推迟设置'
   		 ShowDialog('dialog',10);
		 $.get("delay_time.php",{TC_ID:$(this).attr('item_id'),IS_DELAY:1}, function(data){
               $('#dialog_body').html(data);			
            });
      });
	  //右侧已推迟时间确定事件ljc
      $("#set_time_delay").live('click', function(){
      		//zlp
        var delayday=$("#delayday").val();
    	  var delayhour=$("#delayhour").val();
    	  var delaydate=$("#delaydate").val();
    	  var now_date=$("#CUR_DATE").val();
    	  var radio=$(':input:radio:checked').val();
    	  if(radio==2 && delayday=="" || radio==1 && delayhour=="" || radio==3 && delaydate=="")
    	  {
    		  alert(td_lang.inc.msg_147);
    		  return false;
    	  }
    	  if(radio==2 && delayday!="" && !IsNumber(delayday) || radio==1 &&delayhour!="" && !IsNumber(delayhour))
    	  {
    		  alert(td_lang.inc.msg_148);
    		  return false;
    	  }
    	  if(delaydate!="" && radio==3)
    	  {   	  
    	      var nowdate=now_date.split(" ");    	     	      	  
    	      var v_date=nowdate[0].split("-");
    	      var v_time=nowdate[1].split(":");   
    	      var delay_time=delaydate.split(" ");
    	      var de_date=delay_time[0].split("-");
    	      var de_time=delay_time[1].split(":");
    	      var v_now=new Date(v_date[0],v_date[1],v_date[2],v_time[0],v_time[1],v_time[2]);
    	      var v_delay=new Date(de_date[0],de_date[1],de_date[2],de_time[0],de_time[1],de_time[2]);

    	      if(v_delay!="" && v_delay<v_now )
    	      {
    		      alert(td_lang.inc.msg_149);
    		      return false;
    	      }	 
    	  }     	 
         var item=$("#list_"+$(this).attr('tc_id')); 
         $.get("setData.php", {
			 ACTION:$(this).attr('action'),
			 TC_ID:$(this).attr('tc_id'),
			 selectradio:$(':input:radio:checked').val(),
			 delayhour:$('#delayhour').val(),
			 delayday:$('#delayday').val(),
			 delaydate:$('#delaydate').val()
			 }, function(data){
            if(data != "1")
            {
                  alert(data);
            }          
         });
		 HideDialog("dialog");
      });
      //右侧项目的忽略、恢复和删除点击事件
      $("#right_content div.option a:not('.delay','.delay_set')").live('click', function(){
         var item = $(this).parent().parent();
		 var action =$(this).attr('action');
		 
		 if(action=="ignore")
		 {
			 if(!window.confirm(td_lang.inc.msg_164))
				 return; 
		 }
		 else if(action=="deleted")
		{
			 if(!window.confirm(td_lang.inc.msg_163))
				 return;
		}
		 else if(action=="delay_reborn" || action=="reborn") 
		{
			 if(!window.confirm(td_lang.inc.msg_165))
				 return;
		}		 
         $.get("setData.php", {ACTION:action, TC_ID:$(this).attr('item_id')}, function(data){
            if(data == "1")
            {   
            	item.remove();
			    if(focuscount>0)
			       focuscount--;
				var rightitle=focusmenu.text();
				rightitle=rightitle.split('(')[0];
			    if(focuscount<=0)
			    {	
			    	focusmenu.text(rightitle);
			    	$('#right_title').html(rightitle);
			    	$('#right_content').html(CreateNoTaskHTML());
			    	
			    }
			    else
			    {
				    focusmenu.text(rightitle+"("+focuscount+")");
				    $('#right_title').html(rightitle+"("+focuscount+")");
			    }
				if(action=="ignore")
				{
					var ignoretitle=$('a.ignored').text();
					if(ignoretitle.indexOf('(')>0)
					{						
					    tmptitle=ignoretitle.split('(')[0];																			
					    var tmpnum=ignoretitle.split('(')[1].split(')')[0];					
				        tmpnum++;					
			    	    $('a.ignored').text(tmptitle+"("+tmpnum+")");
                    }
					else
				    {
						$('a.ignored').text(ignoretitle+"(1)");
				    }		
					
				}
			}
            else
               alert(data);
         });
      });
      	  
      //窗口onresize事件
      $(window).bind('resize', function(){
         $('#right_content').height($(window).height() - 115);
         $('div.item div.subject').each(function(){
            $(this).width($(this).parent().width() - 110);
         });
      });

   });
   
})(jQuery);

function init()
{
	//加载页面后点击所有菜单，最后打开第一个菜单
	jQuery('a.today').trigger("click");
	jQuery('a.next').trigger("click");
	jQuery('a.delaytime').trigger("click"); 
	jQuery('a.ignored').trigger("click");  
	jQuery('div.menu a.count').trigger("click");
	jQuery('div.left_button a:last').trigger("click");
	
	
}
function newTaskWindow(TASK_INDEX,URL)
{
	window.open(URL+"?FROM_TASK_CENTER=1");	
}

function CreateNoTaskHTML()
{
   return '<div class="item item_1"><div class="subject"><div class="text">'+td_lang.general.taskcenter.notask+'</div></div></div>';
}

function CreateItemHTML(INDEX, TASK, OP)
{
   var html = '';
   html+='<div id="list_'+TASK['TCID']+'" class="item item_'+INDEX+'">';
   html+='   <div class="subject" item_id="'+TASK['TCID']+'" '+TASK['ATTR_NAME']+'="'+TASK['URL']+'"><div class="text">'+TASK['CONTENT']+'</div></div>';
   html+='   <div class="option">';
   if(OP == "0")
   {
      html+='      <a href="javascript:;" hidefocus="hidefocus" item_id="'+TASK['TCID']+'" class="delay" action="delay">'+td_lang.general.taskcenter.delay+'</a>';
      html+='      <a href="javascript:;" hidefocus="hidefocus" item_id="'+TASK['TCID']+'" class="ignore" action="ignore">'+td_lang.general.taskcenter.ignore+'</a>';
   }
   else if(OP=="2")
   {
   	 html+='      <a href="javascript:;" hidefocus="hidefocus" item_id="'+TASK['TCID']+'" class="delay_reborn" action="delay_reborn">'+td_lang.general.taskcenter.reborn+'</a>';
       html+='      <a href="javascript:;" hidefocus="hidefocus" item_id="'+TASK['TCID']+'" class="delay_set" action="delay_set">'+td_lang.general.taskcenter.delay+'</a>';
   }
   else
   {
      html+='      <a href="javascript:;" hidefocus="hidefocus" item_id="'+TASK['TCID']+'" class="reborn" action="reborn">'+td_lang.general.taskcenter.reborn+'</a>';
      html+='      <a href="javascript:;" hidefocus="hidefocus" item_id="'+TASK['TCID']+'" class="deleted" action="deleted">'+td_lang.general.taskcenter.deleted+'</a>';
   }
   html+='   </a></div>';
   html+='</div>';
   return html;
}
 jQuery("a.PickColor").live('click',function(){
      document.form1.STYLE.value = jQuery(this).attr("index");
      jQuery(this).siblings().removeClass("PickColorActive");
      jQuery(this).addClass("PickColorActive");
   });
function IsNumber(str)
{
   return str.match(/^[0-9]*$/)!=null;
} 
function js_strto_time(str_time){
    var new_str = str_time.replace(/:/g,'-');
    new_str = new_str.replace(/ /g,'-');
    var arr = new_str.split("-");
    var datum = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],arr[3]-8,arr[4],arr[5]));
     strtotime = datum.getTime()/1000;
   return strtotime;
}

function js_date_time(unixtime) {
    var timestr = new Date(parseInt(unixtime) * 1000); 
    var years=timestr.getFullYear();
    var months=timestr.getMonth()+1>9?timestr.getMonth()+1:"0"+(timestr.getMonth()+1);
    var days=timestr.getDate()>9?timestr.getDate():"0"+timestr.getDate();
    var hours=timestr.getHours()>9?timestr.getHours():"0"+timestr.getHours();
    var minuts=timestr.getMinutes()>9?timestr.getMinutes():"0"+timestr.getMinutes();
    var seconds=timestr.getSeconds()>9?timestr.getSeconds():"0"+timestr.getSeconds();
    var datetime=years+"-"+months+"-"+days+" "+hours+":"+minuts+":"+seconds;
    return datetime;
}  
function onlades() {
  document.form1.CONTENT.focus();
}  