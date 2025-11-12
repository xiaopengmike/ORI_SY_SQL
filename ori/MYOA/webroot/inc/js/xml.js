function XML()
{
   this.lastError = 0;
   this.async = false;
   this.xmlDOM = null;
}

XML.prototype.loadFromURL = function(url)
{
   var xhttp = this.newXMLHttpRequest();
   if(xhttp)
   {
      var xmlDOM = null;
      xhttp.open("GET", url, this.async);
      xhttp.onreadystatechange=function(){
         if(xhttp.readyState==4){
            xmlDOM = xhttp.responseXML;
         }
      };
      xhttp.send(null);
      this.xmlDOM = xmlDOM;
   }
   else
   {
      this.xmlDOM = this.createXMLDom();
      if(this.xmlDOM)
      {
         this.xmlDOM.async = this.async;
         this.xmlDOM.load(url);
         
         if(window.DOMParser)
            this.lastError = this.xmlDOM.documentElement.childNodes[0].nodeValue;
         else
            this.lastError = this.xmlDOM.parseError.reason;
      }
   }
}

XML.prototype.loadFromString = function(str)
{
   if(window.DOMParser)
   {
      var parser = new DOMParser();
      this.xmlDOM = parser.parseFromString(str,"text/xml");
      this.lastError = this.xmlDOM.documentElement.childNodes[0].nodeValue;
   }
   else
   {
      this.xmlDOM = this.createXMLDom();
      if(this.xmlDOM)
      {
         this.xmlDOM.async = this.async;
         this.xmlDOM.loadXML(str);
         this.lastError = this.xmlDOM.parseError.reason;
      }
      else
      {
         this.lastError = "Create XMLDom Failed!";
      }
   }
}

XML.prototype.getRoot = function()
{
   if(!this.xmlDOM || this.lastError)
      return null;
   
   return this.xmlDOM.documentElement; //xml文档根节点
}

XML.prototype.createXMLDom = function() //创建XMLDOM对象函数，跨浏览器解决方案
{
   if(window.ActiveXObject) //IE
   {
      var DomType = new Array("Microsoft.XMLDOM","msxml.domdocument","msxml2.domdocument","msxml2.domdocument.3.0","msxml2.domdocument.4.0","msxml2.domdocument.5.0");
      for(var i=0;i<DomType.length;i++)
      {
         try{
            var a = new ActiveXObject(DomType[i]);
            if(!a) continue;
            return a;
         }
         catch(ex){}
      }
   }
   else if(document.implementation && document.implementation.createDocument) //FireFox,Opera,safari
   {
      try{
         return document.implementation.createDocument("","",null);
      }
      catch(ex){}
   }
   else
   {
       return null;
   }
}

XML.prototype.newXMLHttpRequest = function()
{
	var a=null;
	if(window.XMLHttpRequest) // for IE7, Firefox, Opera, etc.
	{
		a=new XMLHttpRequest();
	}
	else if(window.ActiveXObject)// for IE6, IE5
	{
		a=new ActiveXObject("Msxml2.XMLHTTP");
		if(!a)
		{
			a=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return a;
}
