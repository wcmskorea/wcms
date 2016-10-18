var agent = navigator.userAgent.toLowerCase();
var ie = /msie/;
var Config = {
	btn : null,
	btnList : {
		font:["글자체","font.gif"], size:["글자크기","size.gif"], undo:["되돌리기","undo.gif"], redo:["재실행","redo.gif"], bold:["굵게","bold.gif"], italic:["기울리기","italic.gif"], strike:["취소선","strike.gif"], left:["왼쪽 맞춤","left.gif"], center:["가운데 맞춤","center.gif"], right:["오른쪽 맞춤","right.gif"], justify:["혼합정렬","justify.gif"], clean:["스타일 지움","clean.gif"], del:["선택삭제","del.gif"], color:["글자색","color.gif"], hilite:["글자 배경색","hilite.gif"], link:["링크 삽입","link.gif"], unlink:["링크 해제","unlink.gif"], ul1:["번호달기","ul1.gif"], ul2:["기호달기","ul2.gif"], outdent:["내어쓰기","outdent.gif"], indent:["들여쓰기","indent.gif"], hr:["수평선 삽입","hr.gif"], all:["전체선택","selectall.gif"], save:["저장하기","save.gif"], sup:["윗첨자","sup.gif"], sub:["아래첨자","sub.gif"], underline:["밑줄","underline.gif"], source :["소스보기","html.gif"], media:["미디어파일","media.gif"], table:["테이블","table.gif"], image:["멀티미디어","image.gif"], preview:["미리보기","preview.gif"], dirImg:["파일삽입","image.gif"], plus:["창 늘리기","plus.gif"], minus:["창 줄이기","minus.gif"], code:["코드삽입","code.gif"], bar:["구분선","bar.gif"]
	},
	template : {
		all:["source","bar","save","preview","bar","all","undo","redo","bar", "font","size","bar","color","hilite","bar","bold","italic", "underline","strike","bar","sup","sub","bar","clean","del","bar","outdent","indent", "bar","ul1","ul2","bar","left","center","right","justify","bar","hr","link", "unlink","bar","table","image","media"],
		content:["source","bar","save","preview","bar","undo","redo","bar", "font","size","bar","color","hilite","bar","bold","italic","underline","strike","bar","outdent","indent", "bar","ul1","ul2","bar","left","center","right","justify","bar","hr","link","unlink","bar","table","image","media"],
		bbs:["preview","bar","font","size","color","hilite","bar","bold","italic","underline","strike","bar","left","center","right","justify","bar","ul1","ul2","bar","link","table","image","media","bar","source"],
		html:["source","bar","font","size","bar","bold","italic","underline","strike","color","hilite","bar","left","center","right","justify","bar","hr","link","bar","table","image","media","bar","preview"],
		simple:["font","size","bar","bold","italic","underline","strike","color","hilite"],
		bottom:["plus","bar","minus"]
	}
};
var util = { _editor : null,	_colorTable : null,	_fontTable : null, _sizeTable : null,	_linkTable : null,	isIE : (ie.test(agent)) ? 1 : 0, _sendPost : null,	order : "" };

function editor(id, content, height)
{
	if(typeof(document.execCommand)=="undefined") { return; }
	this._id				= id;
	this._max				= "";
	this.height			= height+"px";
	this.rootPath		= "/";
	this.imagePath	= "image/editor";
	this._button		= Config.template["bbs"];
	this.textarea		= document.getElementById(content);
	this._div				= document.createElement("div");
	this._divButton	= document.createElement("div");
	this._iframe		= document.createElement("iframe");
	this._text			= document.createElement("textarea");
	this._divBottom	= document.createElement("div");
	this._div.id		= "editorDiv_"+id;
	this._divButton.id	= "editorDivButton_"+id;
	this._iframe.id	= "editorIframe_"+id;
	this._text.id		= "editorText_"+id;
	this.mode				= "editor";
	this.sel_html		= "";
	this.sel				= null;
	this.range			= null;
	this.charset		= "euc-kr";
};

editor.prototype.init = function() {
	this.textarea.style.display="none";

	var s="<style>";
	s += "#colorTable a {padding:0 6px 0 6px;height:9px;font:10px;text-decoration:none} a.box {border:1px solid #f5f5f5;padding:0 6px 0 6px;height:9px;font:9px;text-decoration:none}";
	s += "</style>";
	document.write(s);

	this._text.style.width= "99%";
	this._text.style.height= this.height;
	this._text.style.display="none";
	this._text.style.font = "12px 돋움";
	this._text.style.background = "#fff url("+this.rootPath+this.imagePath+"/source_bg.gif) 0 -2px";
	this._text.style.lineHeight = "19px";

	this._divButton.className	= "editorButton";
	this.textarea.parentNode.insertBefore(this._div, this.textarea);
	this.displayButton();

	this._iframe.style.width = '100%';
	this._iframe.style.cursor= 'auto';
	this._iframe.style.height= this.height;
	this._iframe.scrolling	 = "auto";
	this._iframe.frameBorder = "0";

	this._divBottom.className	= "editorBottom";
	this.displayBottom();

	this._div.appendChild(this._divButton);
	this._div.appendChild(this._iframe);
	this._div.appendChild(this._text);
	this._div.appendChild(this._divBottom);

	this._doc = this._iframe.contentWindow.document;
	this._doc.designMode = "on";
	this.css = "table,th,td {border:1px dotted #000;}";
	this._doc.open();
	this._doc.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko"><head><link rel="stylesheet" href="'+this.rootPath+'common/css/default.css" type="text/css" charset="'+this.charset+'" media="all" /><link rel="stylesheet" href="'+this.cssPath+'" type="text/css" charset="'+this.charset+'" media="all" /><style type="text/css">'+this.css+'</style></head><body class="textContent">'+this.textarea.value+'</body></html>');
	this._doc.close();

	var self=this;
	this.addEvent(this._doc, "mousedown", this.hideDiv);
};

editor.prototype.trim = function(s)
{
	return s.replace(/^\s+|\s+$/g,'');
};

editor.prototype.displayButton = function() {
	var str=order="";
	var button=elm=null;
	var self=this;
	var arr=this._button;

	for(var i=0;i<arr.length;i++) {
		order = this.trim(arr[i]);
		arrName = Config.btnList[order];
		if(order=="bar") {
			elm					= document.createElement("img");
			elm.src			= this.rootPath+this.imagePath+"/"+arrName[1];
			elm.width		= 2;
			elm.height	= 18;
			elm.hspace	= 2;
			this._divButton.appendChild(elm);
		} else {
			elm				= document.createElement("img");
			elm.id		= "editorButton_"+order;
			elm.src		= this.rootPath+this.imagePath+"/"+arrName[1];
			elm.alt		= arrName[0];
			elm.title	= arrName[0];
			elm.commandExec		= order;
			elm.style.cursor="pointer";
			elm.style.border="1px solid #efefef";
			elm.onclick = function() { self.commandExec(this, this.commandExec); };
			elm.onmouseover = function() {
				this.style.border="1px solid #efefef";this.style.backgroundColor="#d2d2d2";
			};
			elm.onmouseout = function() {
				this.style.border="1px solid #efefef";this.style.backgroundColor="";
			};
			this._divButton.appendChild(elm);
		}
	}
};

editor.prototype.displayBottom = function() {
	var str=order="";
	var button=elm=null;
	var self=this;
	var arr=Config.template["bottom"];

	for(var i=0;i<arr.length;i++) {
		order = this.trim(arr[i]);
		arrName = Config.btnList[order];
		if(order=="bar") {
			elm					= document.createElement("img");
			elm.src			= this.rootPath+this.imagePath+"/"+arrName[1];
			elm.width		= 2;
			elm.height	= 18;
			elm.hspace	= 2;
			elm.align		="absmiddle";
			this._divBottom.appendChild(elm);
		} else {
			elm					= document.createElement("img");
			elm.id			= "editorBotton_"+order;
			elm.src			= this.rootPath+this.imagePath+"/"+arrName[1];
			elm.align		= "absmiddle";
			elm.alt			= arrName[0];
			elm.height	= 16;
			elm.commandExec		= order;
			elm.style.cursor	= "pointer";
			elm.style.border	= "1px solid #efefef";
			elm.onclick = function() { self.commandExec(this, this.commandExec); };
			elm.onmouseover = function() {
				this.style.border="1px solid #efefef";this.style.backgroundColor="#d2d2d2";
			};
			elm.onmouseout = function() {
				this.style.border="1px solid #efefef";this.style.backgroundColor="";
			};
			this._divBottom.appendChild(elm);
		}
	}
};

editor.prototype.commandExec = function(button, order, value) {
	var self = (this) ? this : util._editor;
	var doc = self._doc;
	if(self.mode=="text" && order!="source") {alert("'에디터모드'에서만 사용가능 합니다!");	return;}
	self.focus();
	self.hideDiv();
	self.button = button;

	switch(order) {
		case "innerForm":
			self.innerHTML(value);
		break;
		case "hyperlink":
			var link_text = (self.sel_html) ? self.sel_html : self._linkText.value;
			var html = "<a href='"+self._linkText.value+"' target='_blank' style='text-decoration:underline'>"+link_text+"</a>";
			self.innerURL(html);
		break;
		case "color": case "hilite": case "font": case "size": case "link":
			var div=null;
			if(order=="color") {
				order = "forecolor";
				this.colorTable(order);
				div=util._colorTable;
			} else if(order=="hilite") {
				order = "hilitecolor";
				this.colorTable(order);
				div=util._colorTable;
			} else if(order=="font") {
				order = "fontname";
				this.fontTable();
				div=util._fontTable;
			} else if(order=="size") {
				order = "fontsize";
				this.sizeTable();
				div=util._sizeTable;
			} else if(order=="link") {
				order = "hyperlink";
				this.setLink();
				div = util._linkTable;
				this._linkText.value = "http://";
				self.setSelection();
			}
			util._editor = self;
			util.order = order;
			this.showDiv(div);
		break;
		case "source":
			if(self.mode=="editor")
			{
				self._text.value = self.getHtml();
				self._iframe.style.display = "none";
				self._text.style.display = "block";
				button.src = this.rootPath+this.imagePath+"/editor.gif";
				button.onmouseout = null;
				self.mode="text";
				this.focus();
			}
			else if(self.mode=="text")
			{
				self._text.style.display = "none";
				self._iframe.style.display = "block";
				button.src = this.rootPath+this.imagePath+"/html.gif";
				button.onmouseout = function()
					{ this.style.backgroundColor=""; };
				self.mode="editor";
				this.focus();
			}
		break;
		case "preview":
			var winLeft = (screen.width-800)/2;
			var winTop = (screen.height-600)/2;
			var w=window.open("","preview","width="+800+",height="+600+",top="+winTop+",left="+winLeft+",status=1,scrollbars=1,resizable=1");
			w.document.open();
			w.document.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko"><head><link rel="stylesheet" href="'+this.rootPath+'common/css/default.css" type="text/css" charset="'+this.charset+'" media="all" /><link rel="stylesheet" href="'+this.cssPath+'" type="text/css" charset="'+this.charset+'" media="all" /><style type="text/css">'+this.css+'</style></head><body onclick="self.close()" style="cursor:pointer;" class="textContent">'+self.getHtml()+'</body></html>');
			w.document.close();
		break;
		case "table":
			this.newWindow(this.rootPath+"modules/mdEditor/table.php?id="+this._id,"table","400","220","no","no");
		break;
		case "image":
			this.newWindow(this.rootPath+"modules/mdEditor/upImg.php?id="+this._id,"image","350","200","no","no");
		break;
		case "media":
			this.newWindow(this.rootPath+"modules/mdEditor/upMedia.php?id="+this._id,"media","350","200","no","no");
		break;
		case "plus": case "minus":
			this.framResize(order);
		break;
		case "code":
			self.innerHTML('<div style="border:2px #d2d2d2 dotted; background:#eee; padding:.5em; text-align:justify;"></code>');
		break;
		default:
			if(order=="strike")				order = "strikethrough";
			else if(order=="ul1")			order = "insertorderedlist";
			else if(order=="ul2")			order = "insertunorderedlist";
			else if(order=="hr")			order = "inserthorizontalrule";
			else if(order=="clean")		order = "removeformat";
			else if(order=="save")		order = "saveas";
			else if(order=="all")			order = "selectall";
			else if(order=="sup")			order = "superscript";
			else if(order=="sub")			order = "subscript";
			else if(order=="del")			order = "delete";
			else if(order=="justify") order = "justifyfull";
			else if(order=="center"||order=="left"||order=="right") order = "justify"+order;
			else if(order=="hilitecolor" && util.isIE) order = "backcolor";
			doc.execCommand(order, false, value);
		break;
	}
};

editor.prototype.focus = function() {
	if(this.mode=="text") { this._text.focus(); }
	else this._iframe.contentWindow.focus();
};

editor.prototype.getHtml = function()
{
	var html = "";
	var doc = this._doc;
	if(this.mode=="text") {
		doc.body.innerHTML = this._text.value;
	}
	else
	{
		for(i in doc.links) { if(!doc.links[i].target) doc.links[i].target = "_self"; }
	}
	html = doc.body.innerHTML;
	html = html.replace(/<(body|script|style|xml|iframe)(.*?)>/gi,"");
	html = html.replace(/<(\/?)(body|script|style|xml|iframe)>/gi,"");
	html = html.replace(/br/gi,"br /");
	html = html.replace(/p/gi,"p");
	html = html.replace(/div/gi,"div");
	html = html.replace(/ul/gi,"ul");
	html = html.replace(/ol/gi,"ol");
	html = html.replace(/li/gi,"li");
	html = html.replace(/dt/gi,"dt");
	html = html.replace(/dd/gi,"dd");
	html = html.replace(/dl/gi,"dl");
	this.textarea.value = html;
	return html;
};

editor.prototype.setSelection = function()
{
	var _iframe=this._iframe;
	var sel=null,range=null,html="";

	if(this._doc.selection) {
		sel = this._doc.selection;
		range = sel.createRange();
		html = range.htmlText;
	} else if(_iframe.contentWindow.getSelection) {
		sel=_iframe.contentWindow.getSelection();
		if (typeof(sel)!="undefined") { range=sel.getRangeAt(0);
		} else { range=this._doc.createRange(); }
		if(sel.rangeCount > 0 && window.XMLSerializer)
		{
			html=new XMLSerializer().serializeToString(range.cloneContents());
		}
	}
	this.sel = sel;
	this.range = range;
	this.sel_html = html;
};

editor.prototype.innerHTML = function(html)
{
	if(this.mode=="text") { return; }
	if(util.isIE) {
		this.range = this._doc.selection.createRange();
		this.range.pasteHTML(html);
	} else {
		this._doc.execCommand("inserthtml", false, html);
	}
};

editor.prototype.innerURL = function(html)
{
	if(this.mode=="text") { return; }
	if(util.isIE) { this.range.pasteHTML(html);
	}	else { this._doc.execCommand("inserthtml", false, html); }
};

editor.prototype.layerTop = function(el) {
	var top = el.offsetTop;
	var parent = el.offsetParent;
	while(parent) {	top += parent.offsetTop; parent = parent.offsetParent; }
	return top;
};

editor.prototype.layerLeft = function(el) {
	var left = el.offsetLeft + 1;
	var parent = el.offsetParent;
	while(parent) {	left += parent.offsetLeft; parent = parent.offsetParent; }
	return left;
};

editor.prototype.getDiv = function(id, html) {
	var div = document.createElement("div");
	div.id = id;
	div.className = "editorDiv";
	div.style.position = "absolute";
	div.style.backgroundColor = "#f5f5f5";
	div.style.display = "none";
	div.style.border = "1px solid #ccc";
	div.style.padding = "5px";
	div.style.margin = "2px 0";
	div.innerHTML = html;
	return div;
};

editor.prototype.showDiv = function(div) {
	var button = util._editor.button;
	div.style.top= this.layerTop(button) + button.offsetHeight + "px";
	div.style.left = this.layerLeft(button) + "px";
	div.style.display="";
};

editor.prototype.hideDiv = function() {
	arrTable = new Array("color","font","size","link");
	for(var i=0; i<arrTable.length; i++) {
		try{ eval('util._'+arrTable[i]+'Table.style.display="none"'); }
		catch(e) { }
	}
};

editor.prototype.addEvent = function (object, type, listener) {
	if(object.addEventListener) { object.addEventListener(type, listener, false); }
	else if(object.attachEvent) { object.attachEvent("on"+type, listener); }
};

editor.prototype.fontTable = function() {
	if(this._fontTable) { return; }
	var font = new Array("굴림","돋움","궁서","arial","verdana");
	var s="";
	var pattern=/^[가-힣]+$/;
	for(var i=0; i<font.length; i++) {
		txt = (pattern.test(font[i])) ? "가나다라마바사":"abcdefghijkl";
		s += "<a href='javascript:;' onclick=\"util._editor.commandExec(null, util.order,'"+font[i]+"');\" style='font:10pt "+font[i]+";line-height:170%'>"+txt+" ("+font[i]+")</a><br />";
	}
	var div = this.getDiv("_fontTable",s);
	div.style.padding = "5px";
	document.body.appendChild(div);
	util._fontTable = div;
};

editor.prototype.sizeTable = function() {
	if(this._sizeTable) { return; }
	var size = new Array(8,10,12,14,18,24,36);
	var s="";
	for(var i=0; i<size.length; i++) {
		s += "<a href='javascript:;' onclick=\"util._editor.commandExec(null, util.order,'"+(i+1)+"');\" style='font:"+size[i]+"pt 굴림;'>가나다라 ("+size[i]+")</a><br />";
	}
	var div = this.getDiv("_sizeTable",s);
	div.style.padding = "5px";
	document.body.appendChild(div);
	util._sizeTable = div;
};

editor.prototype.colorTable = function(order) {
	if(this._colorTable) { return; }
	var colSample = "가나다라마바";
	var col_SelList1 = new Array('#008000','#993366','#cc9900','#9b18c1','#ff9900','#0000ff','#ff0000','#177fcd','#ff3399','#8e8e8e');
	var col_SelList2 = new Array('#ffdaed','#ff0000','#99dcff','#0000ff','#a6ff4d','#009966','#e4ff75','#8e8e8e','#e4e4e4','#333333');
	var col= new Array();
	col[0] = new Array("#ffffff","#e5e4e4","#d9d8d8","#c0bdbd","#a7a4a4","#8e8a8b","#827e7f","#767173","#5c585a","#000000");
	col[1] = new Array("#fefcdf","#fef4c4","#feed9b","#fee573","#ffed43","#f6cc0b","#e0b800","#c9a601","#ad8e00","#8c7301");
	col[2] = new Array("#ffded3","#ffc4b0","#ff9d7d","#ff7a4e","#ff6600","#e95d00","#d15502","#ba4b01","#a44201","#8d3901");
	col[3] = new Array("#ffd2d0","#ffbab7","#fe9a95","#ff7a73","#ff483f","#fe2419","#f10b00","#d40a00","#940000","#6d201b");
	col[4] = new Array("#ffdaed","#ffb7dc","#ffa1d1","#ff84c3","#ff57ac","#fd1289","#ec0078","#d6006d","#bb005f","#9b014f");
	col[5] = new Array("#fcd6fe","#fbbcff","#f9a1fe","#f784fe","#f564fe","#f546ff","#f328ff","#d801e5","#c001cb","#8f0197");
	col[6] = new Array("#e2f0fe","#c7e2fe","#add5fe","#92c7fe","#6eb5ff","#48a2ff","#2690fe","#0162f4","#013add","#0021b0");
	col[7] = new Array("#d3fdff","#acfafd","#7cfaff","#4af7fe","#1de6fe","#01deff","#00cdec","#01b6de","#00a0c2","#0084a0");
	col[8] = new Array("#edffcf","#dffeaa","#d1fd88","#befa5a","#a8f32a","#8fd80a","#79c101","#3fa701","#307f00","#156200");
	col[9] = new Array("#d4c89f","#daad88","#c49578","#c2877e","#ac8295","#c0a5c4","#969ac2","#92b7d7","#80adaf","#9ca53b");

	var s="";

	s = "<div style='padding:3px;'>";
	for(var i=0; i<10; i++) {
		for(var j=0; j<10; j++) {
			color = col[i][j];
			s += "<a href='javascript:;' onclick=\"util._editor.commandExec(null, util.order,'"+color+"');\" style='background-color:"+color+";' class=box>&nbsp;</a>";
		}
		s += "<br />";
	}
	s += "</div>";
	var div = this.getDiv("colorTable",s);
	document.body.appendChild(div);
	util._colorTable = div;
};

editor.prototype.setLink = function() {
	if(util._linkTable) { return; }
	var id = "_linkText";
	var s = "<input type='text' class='input_text' value='http://' style='width:230px;font:8pt 돋움;color:gray' id='"+id+"' /><p style='margin-top:3px' align=right>";
	s += "링크주소(URL)를 입력하세요 <img src='"+this.rootPath+"image/button/btn_confirm.gif' border=0 onclick=\"util._editor.commandExec(null, util.order, '');\" style='cursor:pointer' align=absmiddle>";

	var div = this.getDiv("_linkTable",s);
	div.style.padding = "15px";
	div.style.font		= "8pt 돋움";
	document.body.appendChild(div);
	util._linkTable = div;
	this._linkText = document.getElementById(id);
};

editor.prototype.newWindow = function(mypage,myname,w,h,tool,scroll){
	var winl = (screen.width-w)/2;
	var wint = (screen.height-h)/2;
	var settings  ='height='+h+',';
		settings +='width='+w+',';
		settings +='top='+wint+',';
		settings +='left='+winl+',';
		settings +='toolbar='+tool+',';
		settings +='status=yes,';
		settings +='scrollbars='+scroll+',';
		settings +='resizable=no';
	win=window.open(mypage,myname,settings);
	if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
};

editor.prototype.framResize = function(type) {
	switch(type)
	{
		case "plus" :
			var height = parseInt(this.height.replace(/px$/,""))+200;
			this._iframe.style.height = height+"px";
			this._text.style.height = height+"px";
			this.height = height+"px";
		break;
		case "minus" :
			var height = parseInt(this.height.replace(/px$/,""))-200;
			if( parseInt(this._iframe.style.height.replace(/px$/,"")) > 100 )
			{
				this._iframe.style.height = height+"px";
				this._text.style.height = height+"px";
				this.height = height+"px";
			} else
			{
				alert('더이상 줄일 수 없습니다.');
			}
		break;
	}
};
