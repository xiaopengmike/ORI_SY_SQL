jQuery.noConflict();  
(function($){
	var modal;
	function ShowDialog()
	{
		modal.show();   
	}   
	function HideDialog()
	{
		modal.hide();   
	}
	$(document).ready(function(){   
    modal = $('#task-modal').modal({ show: false }).data('modal');
	var focuscount=0;
	var focusmenu;
	var activePanel;
    
    
    if(typeof window.external != 'undefined' && typeof(window.external.OA_SMS) != 'undefined')
    {
        external.OA_SMS(1200, 600, 'SET_SIZE');
	}
        
	//关闭按钮点击事件
	$('div.caption a.close').click(function(){
		if(typeof(top.closeTaskCenter) == 'function')
			top.closeTaskCenter();
		else if(typeof window.external != 'undefined' && typeof(window.external.OA_SMS) != 'undefined')
			window.external.OA_SMS("", "", "CLOSE_WINDOW")
		else
			window.close();
	});
	//点击收件箱
	$('#inbox').click(function(){   	
        $('#taskbox .right').removeClass('count-active');
		$('#panel-inbox').html(loading);
		focusmenu=$('span', this);
		var newtitle=$(this).attr('title');    
		var index = $(this).attr('index');
		$.ajax({
			type: 'GET',
			url: 'getData.php',
			data: {'LEFTMENU': $(this).attr('index')},
			cache: false,
			success: function(data){   
			var taskcount=0;
			var reqtext = data.split('|~|');	 
			var taskcount=	reqtext[0];
			focuscount=taskcount;
			$('#panel-inbox')[0].innerHTML = reqtext[1];
			if(taskcount>0)
				$('#inbox em').text("("+taskcount+")");
			else
				$('#inbox em').text(" ");
			},
			error: function(request, textStatus, errorThrown){
			$('#panel-inbox').html(load_error);
			}
         });
	});
	//近期
	$('#next').click(function(){ 
		$('#taskbox .right').removeClass('count-active');  	
		$('#panel-next').html(loading);
		focusmenu=$('span', this);
		var newtitle=$(this).attr('title');    
		var index = $(this).attr('index');
		$.ajax({
			type: 'GET',
			url: 'getData.php',
			data: {'LEFTMENU': $(this).attr('index')},
			cache: false,
			success: function(data){    
			var taskcount=0;
			var reqtext = data.split('|~|');	 
			var taskcount=	reqtext[0];
			focuscount=taskcount;
			$('#panel-next')[0].innerHTML = reqtext[1];
			if(taskcount>0)
				$('#next em').text("("+taskcount+")");
			else
				$('#next em').text(" ");
			},
			error: function(request, textStatus, errorThrown){
				$('#panel-next').html(load_error);
			}
		});
	});
	//已推迟
	$('#delaytime').click(function(){ 
		$('#taskbox .right').removeClass('count-active');  	
		$('#panel-delaytime').html(loading);
		focusmenu=$('span', this);
		$.ajax({
			type: 'GET',
			url: 'delaytime.php',
			data: {'LEFTMENU': $(this).attr('index')},
			cache: false,
			success: function(data){    
			var taskcount=0;
			var reqtext = data.split('|~|');	 
			var taskcount=	reqtext[0];
			$('#panel-delaytime')[0].innerHTML = reqtext[1];
			if(taskcount>0)
				$('#delaytime em').text("("+taskcount+")");
			else
				$('#delaytime em').text(" ");
			},
			error: function(request, textStatus, errorThrown){
				$('#panel-delaytime').html(load_error);
			}
		});
	});
	//已忽略
	$('#ignored').click(function(){   	
        $('#taskbox .right').removeClass('count-active');
		$('#panel-ignored').html(loading);
		focusmenu=$('span', this);
		$.ajax({
		type: 'GET',
		url: 'ignored.php',
		data: {'LEFTMENU': $(this).attr('index')},
		cache: false,
		success: function(data){    
		var taskcount=0;
		var reqtext = data.split('|~|');	 
		var taskcount=	reqtext[0];  
		$('#panel-ignored')[0].innerHTML = reqtext[1];
		if(taskcount>0)
			$('#ignored em').text("("+taskcount+")");
		else
			$('#ignored em').text(" ");
		},
		error: function(request, textStatus, errorThrown){
		$('#panel-ignored').html(load_error);
		}
		});
	});
	//倒计时	
	$('#count').click(function(){
        $('#taskbox .right').addClass('count-active');
		$('#count-content').html(loading);
		var newtitle=$(this).text();
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
					$('#count-content').html(CreateNoTaskHTML()); 
	        		$('#count-title').html(newtitle+"<div id='count_op'><a class='add_count' id='add_count1' href='javascript:;' title='"+td_lang.inc.msg_143+"'>"+td_lang.inc.msg_159+"</a><a class='flush' id='flush' href='javascript:;' title='"+td_lang.inc.msg_166+td_lang.inc.msg_158+"'>"+td_lang.inc.msg_166+"</a></div>");
					return;
				}
				$('#count-title').html(newtitle+"<div id='count_op'><a class='add_count' id='add_count1' href='javascript:;' title='"+td_lang.inc.msg_143+"'>"+td_lang.inc.msg_159+"</a><a class='flush' id='flush' href='javascript:;' title='"+td_lang.inc.msg_166+td_lang.inc.msg_158+"'>"+td_lang.inc.msg_166+"</a></div>");
					
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
         		$('#count-content').html("<center class='count_body'>"+MODULE_BODY+"</center>");
				if(taskcount>0)
					$('#count em').text("("+taskcount+")");
				else
					$('#count em').text(" ");
				$("#content-title").attr("title",td_lang.inc.msg_158);							
				$.ajax({ 
					type: 'GET',
					url: 'count_img.php',
					cache: false,
					success: function(data){
                        if(data==1)
                        {
                            
                            $('#count').removeClass('count-7days').addClass('count-3days');
                            //有3日内的事件右侧加上图标
                            $('#count-title').html($('#count').text()+"<div id='rock_display'><span id='count_img' class='count_img' title="+td_lang.inc.msg_167+"></span><span id='font_display'>"+td_lang.inc.msg_167+"</span></div><div id='count_op'><a class='add_count' id='add_count' href='javascript:;' title='"+td_lang.inc.msg_143+"'>"+td_lang.inc.msg_159+"</a><a class='flush' id='flush' href='javascript:;' title='"+td_lang.inc.msg_166+td_lang.inc.msg_158+"'>"+td_lang.inc.msg_166+"</a></div>");                             
                        }
                        else if(data==2)
                        {						
                            $('#count').removeClass('count-3days').addClass('count-7days');
                            //有7日内的事件右侧加上图标
                            $('#count-title').html($('#count').text()+"<div id='rock_display'><span id='count_img1' class='count_img1' title="+td_lang.inc.msg_168+"></span><span id='font_display'>"+td_lang.inc.msg_168+"</span></div><div id='count_op'><a class='add_count' id='add_count' href='javascript:;' title='"+td_lang.inc.msg_143+"'>"+td_lang.inc.msg_159+"</a><a class='flush' id='flush' href='javascript:;' title='"+td_lang.inc.msg_166+td_lang.inc.msg_158+"'>"+td_lang.inc.msg_166+"</a></div>"); 
                           
                        }
                    }
                });	
            },
            error:function(request, textStatus, errorThrown){
              $('#count-content').html(load_error);
            }
         });
	});
    $('#taskbox a[index="important"]').click(function(){   	
        $('#taskbox .right').removeClass('count-active');        
    });
	//'刷新倒计时牌'	
	$('#count-title').on('click','#flush', function(){
		$('#count').trigger("click");
	});
	//'新建倒计时'
	$('#count-title').on('click', '#add_count',function(){
		$('#task-modal .modal-header h3').html(td_lang.inc.msg_143);//'新建倒计时牌'
		ShowDialog('dialog',10);
		$.get("new_count.php",{TC_ID:1}, function(data){
		$('#dialog_body').html(data);	
		$('#CONTENT').focus();			
		});
	});
	$('#count-title').on('click','#add_count1',function(){
		$('#task-modal .modal-header h3').html(td_lang.inc.msg_143);//'新建倒计时牌'
		ShowDialog('dialog',10);
		$.get("new_count.php",{IS_NEW:1}, function(data){ 		 	 
		$('#dialog_body').html(data);	
		$('#CONTENT').focus();		
		});
	});  
	//新建保存倒计时
	$('#task-modal').on('click',"#set_count", function(){
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
		var YEAR = $("#YEAR").prop('checked');
		if(YEAR)
		{
		    ANNUAL = 1;
		}
		else
		{
		    ANNUAL = 0;
		}
		$.get("new_add.php", {
			ORDER_NO:$('#ORDER_NO').val(),
			CONTENT:$('#CONTENT').val(),
			STYLES:$('#STYLE').val(),
			END_TIME_D:$('#END_TIME_D').val(),
			ROW_ID:$('#ROW_ID').val(),
			IS_NEW:$('#IS_NEW').val(),
			ANNUAL:ANNUAL
		}, 
		function(data){
			if(data == "1" )
			{  
				$('#count').trigger("click");
			}
			else 
				alert(data);
		});  
		HideDialog();  
	});
	//鼠标事件
	$("#taskbox").on('mouseover',".edit_count",function(){
		$(this).addClass('edit_count1');
	});
	$("#taskbox").on('mouseout',".edit_count", function(){
		$(this).removeClass('edit_count1');
	});
	$("#taskbox").on('mouseover',".del_count", function(){
		$(this).addClass('del_count1');
	});
	$("#taskbox").on('mouseout',".del_count", function(){
		$(this).removeClass('del_count1');
	});
	//编辑倒计时
	$("#taskbox").on('click',".edit_count", function(){
		$('#task-modal .modal-header h3').html(td_lang.inc.msg_144);//'编辑倒计时牌'
		ShowDialog('dialog',10);
		$.get("new_count.php",{ROW_ID:$(this).attr('edit_id')}, function(data){
			$('#dialog_body').html(data);		
		});
	});
	//删除倒计时。。。。。。。。。。。。。。。。。。。。。。
	$("#taskbox").on('click',".del_count", function(){
		if(!window.confirm(td_lang.inc.msg_13))//"删除后将不可恢复,确定要删除吗？"
			return;
		$.get("new_add.php",{ROW_ID:$(this).attr('del_id'),IS_DEL:1}, function(data){
			if(data==1)	
			{
				$('#count').trigger("click");
			}
		});
	});	
	//右侧项目标题的点击事件
	$('#taskbox').on('click', '.taskblock li', function(){
		activePanel = $(this).parents('.tab-pane');
		$('#task-modal .modal-header h3').html(td_lang.inc.msg_146);//查看详情
		if($(this).attr('_ajax'))
		{
			ShowDialog('dialog', 10);
			$.get($(this).attr('_ajax'), function(data){
			$('#dialog_body').html(data);
		});
		}
		else if($(this).attr('_url'))//工作流
		{
			var $list = $(this),
                url = this.getAttribute('_url'),
                id = 'workflow-'+$list.attr('data-run-id')+'-'+$list.attr('data-flow-id')+'-'+$list.attr('data-prcs-id'),
                text = $list.find('.task-item-info p').text();               
                
			if(top.bTabStyle)//时尚主题完整版界面下打开
            {
                url += '&target=top&callback=flowCallback';   
                top.flowCallback = function(ret){
                    flowCallback(ret);
                };
				top.openURL(id, text, url); 
			}
            else
            {
                url += '&target=opener&callback=flowCallback';  
				window.open(url);
			}
        }
	});
	//日程点击“完成”
	$('#task-modal').on('click','#cal_ok,#task_ok',function(){
		var item=$("#list_"+$(this).attr('item_id')); 
        var p_id=activePanel.attr('id');
		$.get("setData.php", {ACTION:$(this).attr('action'), TC_ID:$(this).attr('item_id') ,SOURCE_ID:$(this).attr('data-cal-name')}, function(data){
		if(data == "1")
		{   
			//console.log(p_id);
			if(p_id=="panel-inbox")
			{
				$('#inbox').trigger("click");
			}
			if(p_id=="panel-next")
			{
				$('#next').trigger("click");
			}
			if(p_id=="panel-delaytime")
			{
				$('#delaytime').trigger("click");
			}
			if(p_id=="panel-ignored")
			{
				$('#ignored').trigger("click");
			}			
		}
		else
			alert(data);
		});
		activePanel ="";
        HideDialog();
	});
    //工作流办理事件
	$('#taskbox .tab-pane').on('click', '[action="show_workflow"]', function(){
		$('#task-modal .modal-header h3').html(td_lang.inc.msg_146);//查看详情
        var $list = $(this).parents('li:first'),
            url = $list.attr('_url'),
            id = 'workflow-'+$list.attr('data-run-id')+'-'+$list.attr('data-flow-id')+'-'+$list.attr('data-prcs-id'),
            text = $list.find('.task-item-info p').text();
		if(url)//工作流
		{			     
			if(top.bTabStyle)//时尚主题完整版界面下打开
            {
                url += '&target=top&callback=flowCallback';   
                top.flowCallback = function(ret){
                    flowCallback(ret);
                };
				top.openURL(id, text, url); 
			}
            else
            {
                url += '&target=opener&callback=flowCallback';  
				window.open(url);
			}
		}
        return false;
	});
	//急件箱、近期、已推迟、已忽略日程快速点击“完成”
	$('#taskbox .tab-pane').on('click','[action="cal_ok_quick"],[action="task_ok_quick"]',function(){
		var item=$("#list_"+$(this).attr('item_id')); 
		activePanel = $(this).parents('.tab-pane');
        var p_id=activePanel.attr('id');
		$.get("setData.php", {ACTION:$(this).attr('action'), TC_ID:$(this).attr('item_id') ,SOURCE_ID:$(this).attr('data-cal-name')}, function(data){
		if(data == "1")
		{   
			//console.log(p_id);
			if(p_id=="panel-inbox")
			{
				$('#inbox').trigger("click");
			}
			if(p_id=="panel-next")
			{
				$('#next').trigger("click");
			}
			if(p_id=="panel-delaytime")
			{
				$('#delaytime').trigger("click");
			}
			if(p_id=="panel-ignored")
			{
				$('#ignored').trigger("click");
			}			
		}
		else
			alert(data);
		});
		activePanel ="";
        return false;
	});
	//右侧项目的推迟点击事件
	$('#taskbox .tab-pane').on('click', '[action="delay"]', function(){
		activePanel = $(this).parents('.tab-pane');
        $('#task-modal .modal-header h3').html(td_lang.inc.msg_145);//'推迟设置' 
		ShowDialog('dialog',10);
		$.get("delay_time.php",{TC_ID:$(this).attr('item_id')}, function(data){
			$('#dialog_body').html(data);			
		});
        return false;
	});
	$('#task-modal').on('click', '#radiotime_2', function(){
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
	});	
	$('#task-modal').on('blur', '#delayday', function(){
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
	$('#task-modal').on('click', '#radiotime_1', function(){
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
	$('#task-modal').on('blur', '#delayhour', function(){      
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
	$('#task-modal').on('click', '#radiotime_3', function(){     
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
	$('#task-modal').on('blur', '#delaydate', function(){   
		var delaydate=$("#delaydate").val();
		var now_date=$("#CUR_DATE").val(); 
		var radio=$(':input:radio:checked').val();
		if(radio==3 && delaydate!="")
		{
			var content=td_lang.inc.msg_160+" "+now_date+" "+td_lang.inc.msg_161+" "+delaydate;
			$("#showDelayTime").html(content);
		}	
		else 
			$("#showDelayTime").html("");
	});   
	//推迟时间确定事件
	$('#task-modal').on('click', '#set_time', function(){    
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
				var p_id=activePanel.attr('id');
				if(p_id=="panel-inbox")
				{
					$('#delaytime').trigger("click");
					$('#next').trigger("click");
					$('#inbox').trigger("click");
				}
				if(p_id=="panel-next")
				{
					$('#delaytime').trigger("click");   
					$('#next').trigger("click");
				}
				if(p_id=="panel-delaytime")
				{
					$('#delaytime').trigger("click");
				}			
			
			}
		});
		HideDialog();
	});
	//右侧已推迟项目的推迟点击事件ljc
	$('#taskbox').on('click', '[action="delay_set"]', function(){    
		activePanel = $(this).parents('.tab-pane'); 
		$('#task-modal .modal-header h3').html(td_lang.inc.msg_145);//'推迟设置' 
		ShowDialog('dialog',10);
		$.get("delay_time.php",{TC_ID:$(this).attr('item_id'),IS_DELAY:1}, function(data){
			$('#dialog_body').html(data);			
		});
		return false;
	});
	//右侧已推迟时间确定事件ljc
	$('#task-modal').on('click', '#set_time_delay', function(){   
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
		var p_id=activePanel.attr('id');
		if(data == "1")
		{   
			if(p_id=="panel-delaytime")
			{
				$('#delaytime').trigger("click");
			}
		}			         
	});
	HideDialog();
});
	//右侧项目的忽略、恢复和删除点击事件
	$("#taskbox").on('click'," button:not('[action='delay'],[action='delay_set']')",  function(e){ 
		var item = $(this).parent().parent();
		var action =$(this).attr('action');	
		activePanel = $(this).parents('.tab-pane');	
		var p_id=activePanel.attr('id'); 
		if(action=="ignore")
		{
			if(!window.confirm(td_lang.inc.msg_164))
			return false;
		}
		else if(action=="deleted")
		{
			if(!window.confirm(td_lang.inc.msg_163))
			return false;
		}
		else if(action=="delay_reborn" || action=="reborn") 
		{
			if(!window.confirm(td_lang.inc.msg_165))
			return false;
		}		 
		$.get("setData.php", {ACTION:action, TC_ID:$(this).attr('item_id')}, function(data){
		if(data == "1")
		{   
			if(action=="deleted")
			{
				$('#ignored').trigger("click");
			}
			if(p_id=="panel-inbox")
			{
				$('#ignored').trigger("click");
				$('#inbox').trigger("click");		
			}
			if(p_id=="panel-next")
			{
				$('#ignored').trigger("click");
				$('#next').trigger("click");	
			}
			if(p_id=="panel-delaytime")
			{
				$('#inbox').trigger("click");
				$('#next').trigger("click");
				$('#delaytime').trigger("click");	
			}
			if(p_id=="panel-ignored" && action=="reborn")
			{
				$('#inbox').trigger("click");
				$('#next').trigger("click");
				$('#ignored').trigger("click");	
			}
			
		}
		else
			alert(data);
		});
		return false;
		
	});
      	  
    
   });
   
})(jQuery);

function init()
{
	//加载页面后点击所有菜单，最后打开第一个菜单
	jQuery('#inbox').trigger("click");
	jQuery('#next').trigger("click");
	jQuery('#delaytime').trigger("click"); 
	jQuery('#ignored').trigger("click");  
	jQuery('#count').trigger("click");
	jQuery('#inbox').trigger("click");
    jQuery('#panel-important').on('click', 'ul li', function(){
        var $a = jQuery(this).find('a:first');
        open($a.attr('href'));
        return false;
    });	
}
function newTaskWindow(TASK_INDEX,URL)
{
	window.open(URL+"?FROM_TASK_CENTER=1");	
}

function CreateNoTaskHTML()
{
	return '<div class="notask">'+td_lang.general.taskcenter.notask+'</div>';
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
jQuery(document).on('click', "a.PickColor", function()
{
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
//工作流转交后的callback   {  status: 'success', flowId: '35', prcsId: '1', runId: '1', prcsName: '',msg: '转交成功'  }
function flowCallback(ret){
    if(ret && ret.status == 'success'){
        jQuery('.task-item[data-run-id='+ret.runId+'][data-flow-id='+ret.flowId+'][data-prcs-id='+ret.prcsId+']').remove();
        jQuery('.nav .active a').trigger("click");
    }
    else if(ret && ret.status == 'turned')
    {
        jQuery('.task-item[data-run-id='+ret.runId+'][data-flow-id='+ret.flowId+'][data-prcs-id='+ret.prcsId+']').remove();
        jQuery('.nav .active a').trigger("click");
        window.reload();
    }
}