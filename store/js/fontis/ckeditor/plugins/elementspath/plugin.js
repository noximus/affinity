﻿/*

Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.

For licensing, see LICENSE.html or http://ckeditor.com/license

*/



(function(){var a={toolbarFocus:{exec:function(c){var d=c._.elementsPath.idBase,e=CKEDITOR.document.getById(d+'0');if(e)e.focus();}}},b='<span class="cke_empty">&nbsp;</span>';CKEDITOR.plugins.add('elementspath',{requires:['selection'],init:function(c){var d='cke_path_'+c.name,e,f=function(){if(!e)e=CKEDITOR.document.getById(d);return e;},g='cke_elementspath_'+CKEDITOR.tools.getNextNumber()+'_';c._.elementsPath={idBase:g};c.on('themeSpace',function(h){if(h.data.space=='bottom')h.data.html+='<div id="'+d+'" class="cke_path">'+b+'</div>';});c.on('selectionChange',function(h){var i=CKEDITOR.env,j=h.data.selection,k=j.getStartElement(),l=[],m=this._.elementsPath.list=[];while(k){var n=m.push(k)-1,o;if(k.getAttribute('_cke_real_element_type'))o=k.getAttribute('_cke_real_element_type');else o=k.getName();var p='';if(i.opera||i.gecko&&i.mac)p+=' onkeypress="return false;"';if(i.gecko)p+=' onblur="this.style.cssText = this.style.cssText;"';l.unshift('<a id="',g,n,'" href="javascript:void(\'',o,'\')" tabindex="-1" title="',c.lang.elementsPath.eleTitle.replace(/%1/,o),'"'+(CKEDITOR.env.gecko&&CKEDITOR.env.version<10900?' onfocus="event.preventBubble();"':'')+' hidefocus="true" '+" onkeydown=\"return CKEDITOR._.elementsPath.keydown('",this.name,"',",n,', event);"'+p," onclick=\"return CKEDITOR._.elementsPath.click('",this.name,"',",n,');">',o,'</a>');if(o=='body')break;k=k.getParent();}f().setHtml(l.join('')+b);});c.on('contentDomUnload',function(){f().setHtml(b);});c.addCommand('elementsPathFocus',a.toolbarFocus);}});})();CKEDITOR._.elementsPath={click:function(a,b){var c=CKEDITOR.instances[a];c.focus();var d=c._.elementsPath.list[b];c.getSelection().selectElement(d);return false;},keydown:function(a,b,c){var d=CKEDITOR.ui.button._.instances[b],e=CKEDITOR.instances[a],f=e._.elementsPath.idBase,g;c=new CKEDITOR.dom.event(c);switch(c.getKeystroke()){case 37:case 9:g=CKEDITOR.document.getById(f+(b+1));if(!g)g=CKEDITOR.document.getById(f+'0');g.focus();return false;case 39:case CKEDITOR.SHIFT+9:g=CKEDITOR.document.getById(f+(b-1));if(!g)g=CKEDITOR.document.getById(f+(e._.elementsPath.list.length-1));g.focus();return false;case 27:e.focus();return false;case 13:case 32:this.click(a,b);return false;}return true;}};

