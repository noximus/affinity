﻿/*

Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.

For licensing, see LICENSE.html or http://ckeditor.com/license

*/



(function(){var a={exec:function(c){c.container.focusNext(true);}},b={exec:function(c){c.container.focusPrevious(true);}};CKEDITOR.plugins.add('tab',{requires:['keystrokes'],init:function(c){var d=c.keystrokeHandler.keystrokes;d[9]='tab';d[CKEDITOR.SHIFT+9]='shiftTab';var e=c.config.tabSpaces,f='';while(e--)f+='\xa0';c.addCommand('tab',{exec:function(g){if(!g.fire('tab'))if(f.length>0)g.insertHtml(f);else return g.execCommand('blur');return true;}});c.addCommand('shiftTab',{exec:function(g){if(!g.fire('shiftTab'))return g.execCommand('blurBack');return true;}});c.addCommand('blur',a);c.addCommand('blurBack',b);}});})();CKEDITOR.dom.element.prototype.focusNext=function(a){var j=this;var b=j.$,c=j.getTabIndex(),d,e,f,g,h,i;if(c<=0){h=j.getNextSourceNode(a,CKEDITOR.NODE_ELEMENT);while(h){if(h.isVisible()&&h.getTabIndex()===0){f=h;break;}h=h.getNextSourceNode(false,CKEDITOR.NODE_ELEMENT);}}else{h=j.getDocument().getBody().getFirst();while(h=h.getNextSourceNode(false,CKEDITOR.NODE_ELEMENT)){if(!d)if(!e&&h.equals(j)){e=true;if(a){if(!(h=h.getNextSourceNode(true,CKEDITOR.NODE_ELEMENT)))break;d=1;}}else if(e&&!j.contains(h))d=1;if(!h.isVisible()||(i=h.getTabIndex())<(0))continue;if(d&&i==c){f=h;break;}if(i>c&&(!f||!g||i<g)){f=h;g=i;}else if(!f&&i===0){f=h;g=i;}}}if(f)f.focus();};CKEDITOR.dom.element.prototype.focusPrevious=function(a){var j=this;var b=j.$,c=j.getTabIndex(),d,e,f,g=0,h,i=j.getDocument().getBody().getLast();while(i=i.getPreviousSourceNode(false,CKEDITOR.NODE_ELEMENT)){if(!d)if(!e&&i.equals(j)){e=true;if(a){if(!(i=i.getPreviousSourceNode(true,CKEDITOR.NODE_ELEMENT)))break;d=1;}}else if(e&&!j.contains(i))d=1;if(!i.isVisible()||(h=i.getTabIndex())<(0))continue;if(c<=0){if(d&&h===0){f=i;break;}if(h>g){f=i;g=h;}}else{if(d&&h==c){f=i;break;}if(h<c&&(!f||h>g)){f=i;g=h;}}}if(f)f.focus();};CKEDITOR.config.tabSpaces=0;

