var $ = function(id) {return document.getElementById(id);};
var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
var imageType = "gif,jpg,jpeg,png,bmp,iff,jp2,jpx,jb2,jpc,xbm,wbmp";

function showDetail(url) {
    var myleft = (screen.availWidth - 500) / 2;
    window.open(url, "", "height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=120,left=" + myleft + ",resizable=yes");
}
function AddFile(file_type)
{
   var file=getEventSrc();
   var prefix=file.id.substring(0,file.id.lastIndexOf("_"));
   if(!prefix)
      prefix="ATTACHMENT";
   
   var attach_div = document.getElementById(prefix+"_div");
   var form_el = GetParentEl(file,"form");//alert(addFileLink.name)
   var addFileLink = GetParentEl(file,"a");
   if(!attach_div || !form_el || !addFileLink)
   {
      alert(td_lang.inc.msg_3);//"参数无效"
      return;
   }
   //console.log(file.id);
   //console.log(file.files[0]);
   /*if(!upload_check_size(max_size,file.files[0].size)){
	   file.value='';
	   var max_msg=max_size<1024*1024?max_size/1024+"K":max_size/1024/1024+"M";
	   var max_msg=max_size>=1024*1024*1024?max_size/1024/1024/1024+"G":max_msg; 
	  alert("\u8d85\u8fc7\u6700\u5927\u4e0a\u4f20\u9650\u5236"+max_msg+"\u3002");//"超过最大上传限制"
	  if(max_msg2){showDetail(max_msg2)}
      return;
   }*/
   if(!upload_limit_check(file.value) || file_type == "image" && !upload_image_check(file.value))
   {
      var attach = CreateFileEl(file.id, file.onchange);
      addFileLink.removeChild(file);
      addFileLink.appendChild(attach);
      return;
   }
   
   var id=parseInt(file.id.substring(prefix.length+1));
   var el=form_el.children ? form_el.children : form_el.childNodes;
   
   for(var i=0; i<el.length;i++)
   {
      if(el[i].tagName && el[i].tagName.toLowerCase()=="input" && el[i].type.toLowerCase()=="file" && el[i].id!=file.id && el[i].value==file.value)
      {
         alert(td_lang.inc.msg_4);//"该文件已经添加"
         addFileLink.removeChild(file);
         var attach = CreateFileEl(file.id, file.onchange);
         addFileLink.appendChild(attach);
         return;
      }
   }
   
   var attach_name = file.value.substring(file.value.lastIndexOf("\\")+1);
   attach_div.innerHTML+="<span id='"+prefix+"_span_"+id+"' title='"+file.value+"'><img src='/images/attach.png' align='absMiddle'>"+attach_name+"<img src='/images/remove.png' onclick='RemoveFile(this)' align='absMiddle' style='cursor:hand;'>;<br></span>";
  file.style.zIndex = "-1";
   form_el.appendChild(file);
   
   id++;
   var attach = CreateFileEl(prefix+'_'+id, file.onchange);   
   addFileLink.appendChild(attach);
   document.getElementById(prefix+"_upload_div").style.display="";
   
   if(file_type=="image")
   {      
      InsertImage("file://"+file.value.replace(/\\/g,"/"));
   }
}
function ShowAddFile123(postfix,show_sel_attach)
{
   if(isUndefined(postfix)) postfix="";
   document.write('<span id="ATTACHMENT'+postfix+'_div"></span><span id="ATTACHMENT'+postfix+'_upload_div" style="display:none;"></span><div id="SelFileDiv'+postfix+'"></div><a class="addfile" href="javascript:;">'+td_lang.inc.msg_5+'<input class="addfile" type="file" name="ATTACHMENT'+postfix+'_0" id="ATTACHMENT'+postfix+'_0" size="1" hideFocus="true" onchange="AddFile();" /></a>');//添加附件
   if(show_sel_attach!='0')
      document.write('&nbsp;|&nbsp;<a href="#" onclick="sel_attach(\'SelFileDiv'+postfix+'\',\'ATTACH_DIR'+postfix+'\',\'ATTACH_NAME'+postfix+'\',\'DISK_ID'+postfix+'\');" class="selfile">'+td_lang.inc.msg_6+'</a><input type="hidden" value="" name="ATTACH_NAME'+postfix+'"><input type="hidden" value="" name="ATTACH_DIR'+postfix+'"><input type="hidden" value="" name="DISK_ID'+postfix+'">');//从文件柜和网络硬盘选择附件
}

function RemoveFile(img)
{
   var span = GetParentEl(img,"span");
   var file = document.getElementById(span.id.replace("_span_","_"));
   if(span && span.parentElement)
      span.parentElement.removeChild(span);
   
   if(file && file.parentElement)
      file.parentElement.removeChild(file);
}

// JavaScript Document