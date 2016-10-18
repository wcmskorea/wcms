var getOp7Up,getOp6Dn,getIE4Up,getIE4,getIE5,getIE6,getNN4,getUA=navigator.userAgent.toLowerCase();
var nowZoom = 100;

if(window.opera){
  var i=getUA.indexOf('opera');
  if(i!=-1){
    var v=parseInt(getUA.charAt(i+6));
    getOp7Up=v>=7;
    getOp6Dn=v<7;
  }
}
function getNum()
{
  for(var i=0; i<arguments.length; ++i){if(isNaN(arguments[i]) || typeof(arguments[i])!='number') return false;}
  return true;
}
function getStr(s)
{
  for(var i=0; i<arguments.length; ++i){if(typeof(arguments[i])!='string') return false;}
  return true;
}
function getDef()
{
  for(var i=0; i<arguments.length; ++i){if(typeof(arguments[i])=='undefined') return false;}
  return true;
}
function toGetElementById(e)
{
  if(typeof(e)!='string') return e;
  if(document.getElementById) e=document.getElementById(e);
  else if(document.all) e=document.all[e];
  else e=null;
  return e;
}
function getClientWidth()
{
  var w=0;
  if(getOp6Dn) w=window.innerWidth;
  else if(document.compatMode == 'CSS1Compat' && !window.opera && document.documentElement && document.documentElement.clientWidth)
    w=document.documentElement.clientWidth;
  else if(document.body && document.body.clientWidth)
    w=document.body.clientWidth;
  else if(getDef(window.innerWidth,window.innerHeight,document.height)) {
    w=window.innerWidth;
    if(document.height>window.innerHeight) w-=16;
  }
  return w;
}
function getClientHeight()
{
  var h=0;
  if(getOp6Dn) h=window.innerHeight;
  else if(document.compatMode == 'CSS1Compat' && !window.opera && document.documentElement && document.documentElement.clientHeight)
    h=document.documentElement.clientHeight;
  else if(document.body && document.body.clientHeight)
    h=document.body.clientHeight;
  else if(getDef(window.innerWidth,window.innerHeight,document.width)) {
    h=window.innerHeight;
    if(document.width>window.innerWidth) h-=16;
  }
  return h;
}
function getBodyHeight() {
  var cw = getClientHeight();
  var sw = window.document.body.scrollHeight;
  return cw>sw?cw:sw;
}
function getScrollTop(e, bWin)
{
  var offset=0;
  if (!getDef(e) || bWin || e == document || e.tagName.toLowerCase() == 'html' || e.tagName.toLowerCase() == 'body') {
    var w = window;
    if (bWin && e) w = e;
    if(w.document.documentElement && w.document.documentElement.scrollTop) offset=w.document.documentElement.scrollTop;
    else if(w.document.body && getDef(w.document.body.scrollTop)) offset=w.document.body.scrollTop;
  }
  else {
    e = toGetElementById(e);
    if (e && getNum(e.scrollTop)) offset = e.scrollTop;
  }
  return offset;
}
function getTop(e, iY)
{
  if(!(e=toGetElementById(e))) return 0;
  var css=getDef(e.style);
  if(css && getStr(e.style.top)) {
    if(getNum(iY)) e.style.top=iY+'px';
    else {
      iY=parseInt(e.style.top);
      if(isNaN(iY)) iY=0;
    }
  }
  else if(css && getDef(e.style.pixelTop)) {
    if(getNum(iY)) e.style.pixelTop=iY;
    else iY=e.style.pixelTop;
  }
  return iY;
}

//-----------------------------------------------------------------------------
// flash
//-----------------------------------------------------------------------------
function flashWrite(url,w,h,id,vars){
	w = w.toLowerCase().replace("px","");
	h = h.toLowerCase().replace("px","");
	// 플래시 코드 정의
	var flashStr=
	'<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="'+w+'" height="'+h+'" id="'+id+'" align="middle">'+
	'<param name="movie" value="'+url+'" />'+
	'<param name="allowscriptaccess" value="always">'+
	'<param name="wmode" value="transparent" />'+
	'<param name="menu" value="false" />'+
	'<param name="quality" value="high" />'+
	'<param name="flashVars" value="'+vars+'" />'+
	'<embed src="'+url+'" wmode="transparent" menu="false" flashVars="'+vars+'" quality="high" width="'+w+'" height="'+h+'" name="'+id+'" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />'+
	'</object>';

	// 플래시 코드 출력
	document.write(flashStr);

}
//-----------------------------------------------------------------------------
// div onclick 시 URL이동
//-----------------------------------------------------------------------------
function go_url(url, target) {
	if(!url || url == '#none') { return false; }
	if(target == "_blank")
	{
		window.open(url,'newwin1','');
	} else {
		document.location.href = url;
	}
}
//-----------------------------------------------------------------------------
// 삭제여부
//-----------------------------------------------------------------------------
function delThis(num)
{
	return confirm("정말 삭제하시겠습니까?");
}
//-----------------------------------------------------------------------------
// div Tab Control
//-----------------------------------------------------------------------------
function activeTab(obj) {
    var tab_id = obj.id;
    var cObj = obj.parentNode.firstChild;

    while(cObj) {
		if(cObj.nodeName == "LI" && cObj.id) {
			var cTabID= cObj.id;
			if(cTabID.indexOf('tab')<0) continue;
			var cContentID = cTabID.replace(/^tab/,'tabBody');
			if(tab_id == cTabID) {
					cObj.className = "tab on";
					toGetElementById(cContentID).className = "tabBody show";
			} else {
					cObj.className = "tab";
					toGetElementById(cContentID).className = "tabBody hide";
			}
		}
		cObj = cObj.nextSibling;
    }

}
//---------------------------
// Textarea의 바이트수 (글자수 제한)
//---------------------------
function byteCheck(obj, target, bytesLimit) {
	var msgVal = obj.value;
	var bytesLen = 0;
	var curMsgLen = '';
	var curBytesLen = 0;
	var realVal = '';
	var realLen = 0;
	for(var i = 0; i < msgVal.length; i++) {
		var oneChar = msgVal.charAt(i);
		if ( escape(oneChar).length > 4 )
			bytesLen += 2;
		else if ( oneChar != '\r' || oneChar != '\n' )
		bytesLen++;

		if ( bytesLen <= bytesLimit ) {
			curMsgLen = i + 1;
			curBytesLen = bytesLen;
		}
	}
	if ( bytesLen > bytesLimit )
	{
		alert("더이상 입력하실 수 없습니다. 초과된 글은 삭제합니다.");
		obj.value = msgVal.substr(0, curMsgLen);		// 초과 입력시 초과된 만큼 잘라줌
		realLen = curBytesLen;
	}
	else
	{
		realLen = bytesLen;
	}
	toGetElementById(target).innerText = realLen;
}

function contentReSize(type)
{
	frm = document.getElementById('reply');
	switch(type)
	{
		case "+" :
			frm.style.pixelHeight += 50;
			break;
		case "-" :
			if( frm.style.pixelHeight > 82 )
				frm.style.pixelHeight -= 50;
			break;
	}
}
function getCookie(uName) {

	var flag = document.cookie.indexOf(uName+'=');
	if (flag != -1) {
		flag += uName.length + 1;
		end = document.cookie.indexOf(';', flag);

		if (end == -1) end = document.cookie.length;
		return unescape(document.cookie.substring(flag, end));
	}
}
function setCookie(name, value, expire) {
	var today = new Date();
	var expireday = new Date(today.getTime() + expire);
	document.cookie = name + "=" + escape(value)+ "; path=/; expires=" + expireday.toGMTString() + ";";
}

function autolink(id) { //페이지내의 오토링크걸기
var container = document.getElementById(id);
var doc = container.innerHTML;
var regURL = new RegExp("(http|https|ftp|telnet|news|irc)://([-/.a-zA-Z0-9_~#%$?&=:200-377()]+)","gi");
var regEmail = new RegExp("([xA1-xFEa-z0-9_-]+@[xA1-xFEa-z0-9-]+.[a-z0-9-]+)","gi");
container.innerHTML = doc.replace(regURL,"<a href='$1://$2' target='_blank'>$1://$2</a>").replace(regEmail,"<a href='mailto:$1'>$1</a>");
}

function new_window(mypage,myname,w,h,tool,scroll){	//가운데창
  var winl = (screen.width-w)/2;
  var wint = (screen.height-h)/2;
  var settings  ='height='+h+',';
      settings +='width='+w+',';
      settings +='top='+wint+',';
      settings +='left='+winl+',';
      settings +='toolbar='+tool+',';
	  settings +='scrollbars='+scroll+',';
      settings +='status=yes,resizable=yes';
  win=window.open(mypage,myname,settings);
  if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
}
function new_window2(mypage,myname,w,h,l,tool,scroll){	//구석창
  var settings  ='height='+h+',';
      settings +='width='+w+',';
      settings +='top=0,';
      settings +='left='+l+',';
      settings +='toolbar='+tool+',';
	  settings +='scrollbars='+scroll+',';
      settings +='resizable=no';
  win=window.open(mypage,myname,settings);
  if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
}
function new_window3(mypage,myname,w,h,t,l,tool,scroll){	//위치지정
  var settings  ='height='+h+',';
      settings +='width='+w+',';
      settings +='top='+t+',';
      settings +='left='+l+',';
      settings +='toolbar='+tool+',';
	  settings +='scrollbars='+scroll+',';
      settings +='resizable=no';
  win=window.open(mypage,myname,settings);
  if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
}
function disableKeys()
{
	if((event.ctrlKey == true && (event.keyCode == 78 || event.keyCode == 82)) ||
		(event.keyCode >= 112 && event.keyCode <= 123))
	{
		event.keyCode = 0;
		event.cancelBubble = true;
		event.returnValue = false;
	}
}
function checkKeys() {// 숫자만 입력 받게
	if(( event.keyCode >= 97 && event.keyCode <= 122) || (event.keyCode >=65 && event.keyCode <=90))
	{
	event.keyCode=0;
	}
}
//----------------------------------------------------------------------------
//리스트 체크박스 체크
//----------------------------------------------------------------------------
function list_toggle(frm){
	for(var i=0; i<frm.elements.length; i++){
		var e = frm.elements[i];

		if(e.type == "checkbox" & e.disabled == false){
			if(e.checked != true){
				e.checked = "checked";
			} else {
				e.checked = "";
			}
		}
	}
}
function list_disabled(frm){
	for(var i=0; i<frm.elements.length; i++){
		var e = frm.elements[i];

		if(e.type == "checkbox" & e.value != '0'){
			if(e.disabled != true){
				e.disabled = "disabled";
			} else {
				e.disabled = "";
			}
		}
	}
}

//------------------
// Form value값 리셋
//------------------
function resetAll(frm){
	for(var i=0; i<frm.elements.length; i++){
		frm.elements[i].value ='';
	}
}

//-----------------------------------------------------------------------------
//실시간 입력창에 콤마 찍기
//-----------------------------------------------------------------------------
function checkComma(form) {
	var num = form.value;
		if (form.value.length >= 3) {
			re = /^$|,/g;
			num = num.replace(re, "");
			fl="";
		if(isNaN(num)) { alert("문자는 사용할 수 없습니다.");return 0; }
		if(num==0) return num;
		if(num<0){
			num=num*(-1);
			fl="-";
		}
		else{
			num=num*1; //처음 입력값이 0부터 시작할때 이것을 제거한다.
		}
			num = new String(num);
			temp="";
			co=3;
			num_len=num.length;
	while (num_len>0){
		num_len=num_len-co;
		if(num_len<0){co=num_len+co;num_len=0;}
		temp=","+num.substr(num_len,co)+temp;
		}
	form.value =  fl+temp.substr(1);
	}
}

//-------------
//체크박스 체크
//-------------
function checkElement(Name) {
	var frm = document.getElementById(Name);
	if(frm.checked == true) {
		frm.checked = false;
	} else {
		frm.checked = true;
	}
}
//-------------
//클립보드 복사
//-------------
function clipCopy(name, el){
	var doc = document.body.createTextRange();
	doc.moveToElementText(document.all(el));
	doc.select();
	doc.execCommand('copy');
	alert("'"+name+"'가(이) 복사 되었습니다.\n\n원하는곳에 '붙여넣기' 나 'Ctrl + v' 하세요.");
}

function overClass(el)
{
	var pattern = /(_on)$/;
	var code = el.className;
	var tag = "_on";
	if(pattern.test(code) == true)
	{
		code = code.replace(tag, "");
		el.className = code;
	}
	else
	{
		el.className = code+"_on";
	}
}

//-----------------------------------------------------------------------------
// 카피할때 출처 삽입하기
//-----------------------------------------------------------------------------
function insertOrigin(title, from, url) {
	if (window.event) {
		window.event.returnValue 	= true;
		__insert_origin_title__ 	= title;
		__insert_origin_url__ 		= url;
		__insert_origin_from__ 		= from;
		window.setTimeout("_procInsertOrigin()", 25);
	}
}
function _procInsertOrigin() {
	if (window.clipboardData) {
		var obj = document.frames["hdFrame"].document.body;
		var rng = obj.createTextRange();
		var printFrom = '';
		if (__insert_origin_from__ != "") {
			printFrom = " from " + __insert_origin_from__;
		}
		var footerHTML = "<p style='margin:15px 0 0 0;'>";
		footerHTML +="<a href='"+ __insert_origin_url__ +"' target='_new' title='제목 부분을 클릭하면\n원 게시물을 볼 수 있습니다.'>[출처] "+ __insert_origin_title__ + printFrom;
		footerHTML += "</a></p>";
		rng.execCommand("Paste");
		obj.insertAdjacentHTML("BeforeEnd",  footerHTML);
		rng = obj.createTextRange();
		rng.execCommand("SelectAll");
		rng.execCommand("Cut");
	}
}

function zoomOut() {
	nowZoom = nowZoom - 10;
	if(nowZoom <= 0) nowZoom = 10;
	document.body.style.zoom = nowZoom + "%";
}
function zoomIn() {
	nowZoom = nowZoom + 10;
	if(nowZoom > 200) nowZoom = 200;
	document.body.style.zoom = nowZoom + "%";
}
function zoomReset(){
	nowZoom = 100; //reset
	document.body.style.zoom = nowZoom + "%";
}

//꼼마찍기
function number_format (number, decimals, dec_point, thousands_sep) {
    // Formats a number with grouped thousands
    //
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/number_format    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// *     example 1: number_format(1234.56);
    // *     returns 1: '1,235'
    // *     example 2: number_format(1234.56, 2, ',', ' ');
    // *     returns 2: '1 234,56'
    // *     example 3: number_format(1234.5678, 2, '.', '');    // *     returns 3: '1234.57'
    // *     example 4: number_format(67, 2, ',', '.');
    // *     returns 4: '67,00'
    // *     example 5: number_format(1000);
    // *     returns 5: '1,000'    // *     example 6: number_format(67.311, 2);
    // *     returns 6: '67.31'
    // *     example 7: number_format(1000.55, 1);
    // *     returns 7: '1,000.6'
    // *     example 8: number_format(67000, 5, ',', '.');    // *     returns 8: '67.000,00000'
    // *     example 9: number_format(0.9, 0);
    // *     returns 9: '1'
    // *    example 10: number_format('1.20', 2);
    // *    returns 10: '1.20'    // *    example 11: number_format('1.20', 4);
    // *    returns 11: '1.2000'
    // *    example 12: number_format('1.2000', 3);
    // *    returns 12: '1.200'
    // *    example 13: number_format('1 000,50', 2, '.', ' ');    // *    returns 13: '100 050.00'
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');    }
    return s.join(dec);
}

function showFaq(el) {
  var elem, vis;
  if(document.getElementById)
    elem = document.getElementById(el);
  else if(document.all)
      elem = document.all[el];
  else if(document.layers)
    elem = document.layers[el];
  vis = elem.style;
  if(vis.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)
    vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';
  vis.display = (vis.display==''||vis.display=='block')?'none':'block';
}

/* 날짜 출력 */
function getDate(iYear, iMonth, iDay, seperator, fix)
{
	//현재 날짜 객체를 얻어옴.
	var gdCurDate = new Date();
	var Config = {
		Lastest : { month : ['',31,28,31,30,31,30,31,31,30,31,30,31] }
	}
	//현재 날짜에 날짜 게산.
	gdCurDate.setYear( gdCurDate.getFullYear() + iYear );
	gdCurDate.setMonth( gdCurDate.getMonth() + iMonth );
	gdCurDate.setDate( gdCurDate.getDate() + iDay );
	//실제 사용할 연, 월, 일 변수 받기.
	var giYear = gdCurDate.getFullYear();
	var giMonth = (fix && !iYear && !iMonth && !iDay) ? gdCurDate.getMonth() : gdCurDate.getMonth()+1;
	var giDay = gdCurDate.getDate();
	var giLast = Config.Lastest['month'][giMonth];
	//월, 일의 자릿수를 2자리로 맞춘다.
	giMonth = "0" + giMonth;
	giMonth = giMonth.substring(giMonth.length-2,giMonth.length);
	giDay   = "0" + giDay;
	giDay   = giDay.substring(giDay.length-2,giDay.length);
	//display 형태 맞추기.
	if(fix) 
	{
		if(!iYear && !iMonth && !iDay) {
			return giYear + seperator + giMonth + seperator + giLast;
		} else {
			return giYear + seperator + giMonth + seperator + "01";
		}
	} else {
		return giYear + seperator + giMonth + seperator + giDay;
	}
}