﻿/*

Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.

For licensing, see LICENSE.html or http://ckeditor.com/license

*/



(function(){var a={elements:{$:function(b){var c=b.attributes._cke_realelement,d=c&&new CKEDITOR.htmlParser.fragment.fromHtml(decodeURIComponent(c)),e=d&&d.children[0];if(e){var f=b.attributes.style;if(f){var g=/(?:^|\s)width\s*:\s*(\d+)/.exec(f),h=g&&g[1];g=/(?:^|\s)height\s*:\s*(\d+)/.exec(f);var i=g&&g[1];if(h)e.attributes.width=h;if(i)e.attributes.height=i;}}return e;}}};CKEDITOR.plugins.add('fakeobjects',{requires:['htmlwriter'],afterInit:function(b){var c=b.dataProcessor,d=c&&c.htmlFilter;if(d)d.addRules(a);}});})();CKEDITOR.editor.prototype.createFakeElement=function(a,b,c,d){var e=this.lang.fakeobjects,f={'class':b,src:CKEDITOR.getUrl('images/spacer.gif'),_cke_realelement:encodeURIComponent(a.getOuterHtml()),alt:e[c]||e.unknown};if(c)f._cke_real_element_type=c;if(d)f._cke_resizable=d;return this.document.createElement('img',{attributes:f});};CKEDITOR.editor.prototype.createFakeParserElement=function(a,b,c,d){var e=new CKEDITOR.htmlParser.basicWriter();a.writeHtml(e);var f=e.getHtml(),g=this.lang.fakeobjects,h={'class':b,src:CKEDITOR.getUrl('images/spacer.gif'),_cke_realelement:encodeURIComponent(f),alt:g[c]||g.unknown};if(c)h._cke_real_element_type=c;if(d)h._cke_resizable=d;return new CKEDITOR.htmlParser.element('img',h);};CKEDITOR.editor.prototype.restoreRealElement=function(a){var b=decodeURIComponent(a.getAttribute('_cke_realelement'));return CKEDITOR.dom.element.createFromHtml(b,this.document);};

