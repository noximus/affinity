﻿/*

Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.

For licensing, see LICENSE.html or http://ckeditor.com/license

*/



CKEDITOR.plugins.add('colorbutton',{requires:['panelbutton','floatpanel','styles'],init:function(a){var b=a.config,c=a.lang.colorButton,d;if(!CKEDITOR.env.hc){e('TextColor','fore',c.textColorTitle);e('BGColor','back',c.bgColorTitle);}function e(g,h,i){a.ui.add(g,CKEDITOR.UI_PANELBUTTON,{label:i,title:i,className:'cke_button_'+g.toLowerCase(),modes:{wysiwyg:1},panel:{css:[CKEDITOR.getUrl(a.skinPath+'editor.css')]},onBlock:function(j,k){var l=j.addBlock(k);l.autoSize=true;l.element.addClass('cke_colorblock');l.element.setHtml(f(j,h));var m=l.keys;m[39]='next';m[9]='next';m[37]='prev';m[CKEDITOR.SHIFT+9]='prev';m[32]='click';}});};function f(g,h){var i=[],j=b.colorButton_colors.split(','),k=CKEDITOR.tools.addFunction(function(o,p){if(o=='?')return;a.focus();g.hide();var q=new CKEDITOR.style(b['colorButton_'+p+'Style'],o&&{color:o});a.fire('saveSnapshot');if(o)q.apply(a.document);else q.remove(a.document);a.fire('saveSnapshot');});i.push('<a class="cke_colorauto" _cke_focus=1 hidefocus=true title="',c.auto,'" onclick="CKEDITOR.tools.callFunction(',k,",null,'",h,"');return false;\" href=\"javascript:void('",c.auto,'\')"><table cellspacing=0 cellpadding=0 width="100%"><tr><td><span class="cke_colorbox" style="background-color:#000"></span></td><td colspan=7 align=center>',c.auto,'</td></tr></table></a><table cellspacing=0 cellpadding=0 width="100%">');for(var l=0;l<j.length;l++){if(l%8===0)i.push('</tr><tr>');var m=j[l],n=a.lang.colors[m]||m;i.push('<td><a class="cke_colorbox" _cke_focus=1 hidefocus=true title="',n,'" onclick="CKEDITOR.tools.callFunction(',k,",'#",m,"','",h,"'); return false;\" href=\"javascript:void('",n,'\')"><span class="cke_colorbox" style="background-color:#',m,'"></span></a></td>');}if(b.colorButton_enableMore)i.push('</tr><tr><td colspan=8 align=center><a class="cke_colormore" _cke_focus=1 hidefocus=true title="',c.more,'" onclick="CKEDITOR.tools.callFunction(',k,",'?','",h,"');return false;\" href=\"javascript:void('",c.more,"')\">",c.more,'</a></td>');i.push('</tr></table>');return i.join('');};}});CKEDITOR.config.colorButton_enableMore=false;CKEDITOR.config.colorButton_colors='000,800000,8B4513,2F4F4F,008080,000080,4B0082,696969,B22222,A52A2A,DAA520,006400,40E0D0,0000CD,800080,808080,F00,FF8C00,FFD700,008000,0FF,00F,EE82EE,A9A9A9,FFA07A,FFA500,FFFF00,00FF00,AFEEEE,ADD8E6,DDA0DD,D3D3D3,FFF0F5,FAEBD7,FFFFE0,F0FFF0,F0FFFF,F0F8FF,E6E6FA,FFF';CKEDITOR.config.colorButton_foreStyle={element:'span',styles:{color:'#(color)'},overrides:[{element:'font',attributes:{color:null}}]};

CKEDITOR.config.colorButton_backStyle={element:'span',styles:{'background-color':'#(color)'}};

