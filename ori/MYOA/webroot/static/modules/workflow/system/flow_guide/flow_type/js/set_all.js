window.onload = function()
{	
	jQuery("a[data-toggle='tab']").parent().click(function(){
		return checkEssentialInfo();
	});
	jQuery(window).resize(function(){
		jQuery('#nav-right-container').css('height',getMainDivHeight());
	});
	jQuery(window).trigger('resize');
	document.getElementById("remind_fld").disabled= document.getElementById("REMIND_ORNOT").checked? false : true;
};

//流转设置会签意见显示控制
function signChange(obj)
{
	if(obj != 1)
		jQuery("#signlook").css('display', 'inline');
	else
		jQuery("#signlook").css('display', 'none');
}

function prcs_checkForm()
{	
	if(jQuery("#set_all_id").val()=="")
	{
		alert(td_lang.general.workflow.msg_179);
		return false;
	}	
	document.getElementById("form1").submit();
}
function getMainDivHeight()
{
	var windowsHeight=jQuery(window).outerHeight(true);
	var bottomHeight=jQuery('.work_bottom').outerHeight(true);
	var MainDivHeight=windowsHeight-(25+bottomHeight+20);
	return MainDivHeight;
}
function checkEssentialInfo()
{
	var flow_name= jQuery('#textarea_id').val();
	if( flow_name=='')
	{
		alert(td_lang.system.workflow.msg_29);
		jQuery('#textarea_id').focus();
		return false;
	}
}
function close_flow()
{
	window.close();
}