﻿/*

Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.

For licensing, see LICENSE.html or http://ckeditor.com/license

*/



(function(){var a={exec:function(c){if(CKEDITOR.getClipboardData()===false||!window.clipboardData){c.openDialog('pastetext');return;}c.insertText(window.clipboardData.getData('Text'));}};CKEDITOR.plugins.add('pastetext',{init:function(c){var d='pastetext',e=c.addCommand(d,a);c.ui.addButton('PasteText',{label:c.lang.pasteText.button,command:d});CKEDITOR.dialog.add(d,CKEDITOR.getUrl(this.path+'dialogs/pastetext.js'));if(c.config.forcePasteAsPlainText)c.on('beforePaste',function(f){if(c.mode=='wysiwyg'){setTimeout(function(){e.exec();},0);f.cancel();}},null,null,20);},requires:['clipboard']});var b;CKEDITOR.getClipboardData=function(){if(!CKEDITOR.env.ie)return false;var c=CKEDITOR.document,d=c.getBody();if(!b){b=c.createElement('div',{attributes:{id:'cke_hiddenDiv'},styles:{position:'absolute',visibility:'hidden',overflow:'hidden',width:'1px',height:'1px'}});b.setHtml('');b.appendTo(d);}var e=false,f=function(){e=true;};d.on('paste',f);var g=d.$.createTextRange();g.moveToElementText(b.$);g.execCommand('Paste');var h=b.getHtml();b.setHtml('');d.removeListener('paste',f);return e&&h;};})();CKEDITOR.editor.prototype.insertText=function(a){a=CKEDITOR.tools.htmlEncode(a);a=a.replace(/(?:\r\n)|\n|\r/g,'<br>');this.insertHtml(a);};CKEDITOR.config.forcePasteAsPlainText=false;

