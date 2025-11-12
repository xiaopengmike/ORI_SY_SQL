/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
CKEDITOR.editorConfig = function( config )
{
   config.toolbarCanCollapse = false;
   config.resize_enabled = false;
   config.autoParagraph = false;
   config.enterMode = CKEDITOR.ENTER_BR;
//   config.docType = '';
   config.pasteFromWordPromptCleanup = true;
   config.pasteFromWordRemoveStyles = true;
   config.language = 'zh-cn';
   config.skin = 'moono';
   config.removePlugins = 'elementspath,magicline';
 //  config.uiColor = '#f1f1f1';
   config.toolbar_Simple = 
   [
      ['Bold','Italic','-','Link','Unlink','-','Font','FontSize','TextColor','BGColor','mutismiley']
   ];
   config.toolbar_Basic = 
   [
    
      { name: 'basicstyles',items : [ 'Bold','Italic','NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Link','Unlink' ]},
      { name: 'clipboard', items : [ 'Font','FontSize','TextColor','BGColor','Image','Smiley','-','Templates','Source','Maximize' ]}
   ];   
   config.toolbar_Flowview = 
   [
      { name: 'basicstyles',items : [ 'Bold','Italic','NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Link','Unlink' ]},
      { name: 'clipboard', items : [ 'Font','FontSize','TextColor','BGColor','Image','Smiley','Table','-','Templates','Source','Maximize' ]}
   ];
   config.toolbar_Feedback = 
   [
      ['Bold','Italic','Underline','-','Link','Unlink','-','Font','FontSize','TextColor','BGColor']
   ];
   config.toolbar_Default =
   [
      { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','RemoveFormat' ] },
      { name: 'colors',      items : [ 'TextColor','BGColor' ] },
      { name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
      { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight' ] },//,'JustifyBlock'
      { name: 'links',       items : [ 'Link','Unlink','PageBreak' ] },
      { name: 'edit',        items : [ 'Undo','Redo' ] },
      { name: 'insert',      items : [ 'Flash','Image','Table','Smiley'] },
      { name: 'document',    items : [ 'Preview','Templates','Source','Maximize' ] }
   ];
   config.templates_files   = ['/module/html_model/view/xml.php?MODEL_TYPE=' + (window.HTML_MODEL_TYPE ? window.HTML_MODEL_TYPE : '')];
   config.font_names = 'ËÎÌå;ÐÂËÎÌå;Î¢ÈíÑÅºÚ;·ÂËÎ_GB2312;·ÂËÎ;ºÚÌå;¿¬Ìå_GB2312;¿¬Ìå;Á¥Êé;Ó×Ô²;Arial;Comic Sans MS;Consolas;Courier New;Fixedsys;Georgia;Tahoma;Times New Roman;Verdana;';
   config.fontSize_sizes = '8pt/8pt;9pt/9pt;10pt/10pt;11pt/11pt;12pt/12pt;13pt/13pt;14pt/14pt;16pt/16pt;18pt/18pt;24pt/24pt;36pt/36pt;48/48pt;'
   config.image_previewText = ' ';

   config.extraPlugins = 'mutismiley';
};

function getEditorInstances(id)
{
   return CKEDITOR.instances["TD_HTML_EDITOR_" + id];
}
function getEditorText(id)
{
   var editor = getEditorInstances(id);
   var element = CKEDITOR.dom.element.createFromHtml('<div>' + editor.getData() + '</div>');
   return element.getText();
}
function getEditorHtml(id)
{
   return getEditorInstances(id).getData();//eval("CKEDITOR.instances.TD_HTML_EDITOR_" + id + "");
}
function setEditorHtml(id, html)
{
   var editor = getEditorInstances(id);
   if(editor)
      editor.setData(html);
}
function checkEditorDirty(id)
{
   return getEditorInstances(id).checkDirty();//eval("CKEDITOR.instances.TD_HTML_EDITOR_" + id + "");
}
function resetEditorDirty(id)
{
   return getEditorInstances(id).resetDirty();//eval("CKEDITOR.instances.TD_HTML_EDITOR_" + id + "");
}
function insertEditorImage(id, src)
{
   if(isUndefined(id))
      id = 'CONTENT';
   
   var editor = getEditorInstances(id);
   if(editor)
      editor.insertHtml('<img src="'+src+'">');
}
function addEditorEvent(editor, event, handler)
{
   editor.document.on(event, handler);
}

function reg_replace(str, to_null, tagName)
{
   var re = new RegExp("<([\\s|/]*?)" + tagName + "([\\s\\S]*?)>", "ig");
   return str.replace(re, (to_null ? "" : "&lt;$1" + tagName + "$2&gt;"));
}

CKEDITOR.on("instanceReady", function(e) {
 /* space press 
   e.editor.on("key", function(evt) {
      if (evt.data.keyCode == '32') {
         return false;
      }
   });
 */  
});

if(typeof(editorLoaded) == 'function')
{
    editorLoaded();
}
