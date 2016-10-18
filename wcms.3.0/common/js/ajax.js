/*
Ajax를 jQuery와 연동
*/
(function($){
	if (!$) return;
	var droot = "/";

	/* jquery form vaildate submit */
	$.submit = function(frm)
	{
		return true;

	};

	/* ajax 전송 */
	$.ajaxPost = function(divName, file, data)
	{
		var data = (data) ? $.uriEncode(data) : null;
		var req = $.xmlRequest();
		var pattern = /\?/;
		var url = pattern.exec(file) ? file : file+'?'+data;
		if(req) {
			req.open("GET", url, true);
			req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");
			req.onreadystatechange = function()	{
				if (req.readyState == 4) {
					$(divName).html(req.responseText);
				}
			}
//			var data = (data) ? $.uriEncode(data) : null;
			req.send(null);
		}
	};
	$.xmlRequest = function()
	{
		var req = null;
		if (window.XMLHttpRequest) {
			req = new XMLHttpRequest();
			if (req.overrideMimeType)
				req.overrideMimeType('text/xml; charset=utf-8');
		} else if (window.ActiveXObject) {
			try {
				req = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e1) {
				try	{
					req = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e2) { }
			}
		}
		return req;
	};

	/* 데이터 체크 */
	$.uriEncode = function(data)
	{
		var data = data.replace('&amp;','&');
		var rand = Math.round(Math.random() * (new Date().getTime()));
		if(data != ""){
			//&와=로 일단 분해해서 encode
			var encdata = '';
			var datas = data.split('&');
			for(i=1;i<datas.length;i++)
			{
				var dataq = datas[i].split('=');
				if(i > 1) { encdata += '&'; }
				encdata += encodeURIComponent(dataq[0])+'='+encodeURIComponent(dataq[1].replace(':@:','='));
			}
		} else {
			encdata = "";
		}
		encdata += '&rand='+rand;
		//alert(encdata);
		return encdata;
	};

	/* 페이지 insert */
	$.insert = function(div, file, data, height)
	{
		var h = (height-16)/2;
		var html = '<div style="height:'+height+'px;"><div style="padding-top:'+h+'px;text-align:center;"><img src="'+droot+'common/image/ajax_small.gif" width="16" height="16" /></div></div>';
		$(div).html(html);
		$.ajaxPost(div, file, data);
	};

	/* 다이알로그창 */
	$.dialog = function(file, data, w, h)
	{
		$.dialogRemove();
		//$('select').hide();
		var winh = parseInt(getClientWidth() - w) / 2;
		var winv = parseInt(getScrollTop() + ((getClientHeight()-h)/2)-50);
		winh = (winh < 0) ? 0 : winh;
		winv = (winv < 30) ? 30 : winv;
		var html = '<img src="'+droot+'common/image/ajax_small.gif" width="16" height="16" style="margin-top:'+parseInt((h/2)-8)+'px" />';
		$('<div id="ajax_header"></div>').css('height',getBodyHeight()).appendTo('body');
		$('<div id="ajax_body"><div id="ajax_display"></div></div>')
			.css({
				'position':'absolute', 'top':winv+'px', 'left':winh+'px', 'border':'2px #333 solid',
				'width':w+'px', 'height':h+'px', 'text-align':'center', 'background':'url(/common/image/background/bg_pattern.png)', 'z-index':'2004'
			}).appendTo('body');
		$('<div id="ajax_close" onclick="$.dialogRemove()"><span><img src="'+droot+'common/image/icon/icon_close.gif" width="40" height="16" alt="Close" title="close( Press \'ECS\' )" /></span></div>')
			.css({'top' : '-22px', 'right' : '0px', 'z-index':'2005'}).appendTo('#ajax_body');

		$('#ajax_display').css({'width':w+'px',	'height':h+'px', 'text-align':'center', 'overflow':'auto'}).html(html);
		$.ajaxPost('#ajax_display', file, data);

		$("#ajax_body").draggable();
		$.dialogScroll("ajax_body", h);
	};

	/* 메세지창 */
	$.message = function(file, data)
	{
		var	w = 400;
		var h = 100;
		var winh = parseInt(getClientWidth() - w) / 2;
		var winv = parseInt(getScrollTop() + ((getClientHeight()-h)/2)-50);
		var html = '<img src="'+droot+'common/image/ajax_small.gif" width="16" height="16" style="margin-top:'+parseInt((h/2)-8)+'px" />';

		$.dialogRemove();
		$('<div id="ajax_header"></div>').css('height',getBodyHeight()).appendTo('body');
		$('<div id="ajax_body"><div id="ajax_display"></div></div>')
			.css({
				'position':'absolute','top':winv+'px','left':winh+'px','border':'2px #333 solid',
				'width':w+'px','height':h+'px','text-align':'center','background':'#e5e5e5','z-index':'2004'
			}).appendTo('body');
		$('<div id="ajax_close" onclick="$.dialogRemove()"><img src="'+droot+'common/image/icon/icon_close.gif" width="40" height="16" alt="Close" title="close( Press \'ECS\' )" /></div>')
			.css({'top' : '-22px', 'right' : '0px'}).appendTo('#ajax_body');

		$('#ajax_display').css({'width':w+'px','height':h+'px','text-align':'center','overflow':'auto'}).html(html);
		$.ajaxPost('#ajax_display', file, data);

		$("#ajax_body").draggable();
		$.dialogScroll("ajax_body", h);
	};

	/* 폼전송 */
	$.checkFarm = function(frm, file, type, div, w, h)
	{
		var data = '';
		//if(validCheck(frm) == true)
		//{
			for(var i=0; i<frm.elements.length; i++){
				var e = frm.elements[i];
				if(e.disabled == false & e.type.toLowerCase() != 'checkbox' & e.type.toLowerCase() != 'radio'){
					data += "&"+e.name+"="+e.value.replace('=',':@:');
				}
				if(e.checked == true & e.type.toLowerCase() == 'checkbox'){
					data += "&"+e.name+"="+e.value;
				}
				if(e.checked == true & e.type.toLowerCase() == 'radio'){
					data += "&"+e.name+"="+e.value;
				}
			}
			switch(type) {
				case "dialog":
					$.dialog(file, data, w, h);
				break;
				case "insert":
					$.insert(div, file, data, h);
				break;
				case "msg":
					$.message(file, data);
				break;
				default:
					$.message(file, data);
				break;
			}
		//}
		return false;
	};

	/* 다이알로그창 없애기 */
	$.dialogRemove = function()
	{
		$("#ajax_body").remove();
		$("#ajax_close").remove();
		$("#ajax_header").remove();
		$('select').show();
		return true;
	};

	/* 다이알로그 스크롤 */
	$.dialogScroll = function(ename, height)
	{
		if(toGetElementById(ename)) {
			var el = toGetElementById(ename);
			var yMenuFrom, yMenuTo, yOffset, timeoutNextCheck;
					yMenuFrom   = parseInt(getTop(ename), 10);
					yMenuTo     = getScrollTop();
			if(yMenuTo <0 ) yMenuTo = 0;
			timeoutNextCheck = 100;
			//임시로 주석처리를 함
			//if(yMenuFrom > parseInt(yMenuTo + 10) || yMenuFrom < parseInt(yMenuTo - 10)) {
			//	yOffset = Math.ceil(Math.  abs(yMenuTo - yMenuFrom) / 5);
			//	if (yMenuTo < yMenuFrom) yOffset = -yOffset;
			//	//el.style.top	= parseInt (el.style.top, 10) + yOffset;
			//	el.style.top	= parseInt(getScrollTop() + ((getClientHeight()-height)/2)-50);
			//	/* el.style.left = parseInt (getBodyWidth() - w) / 2; */
			//}

			//$("#posit").html(yMenuTo);
			setTimeout ("$.dialogScroll('"+ename+"', "+height+")", timeoutNextCheck);
		}
		else {
			false;
		}
	};

	/* 탭방식 - Ajax */
	$.tabMenu = function(obj, div, file, data, height)
	{
		var obj = document.getElementById(obj);
		var tab_id = obj.id;
		var cObj = obj.parentNode.firstChild;

		while(cObj)
		{
			if(cObj.nodeName == "LI" && cObj.id)
			{
				var cTabID= cObj.id;
				if(cTabID.indexOf('tab') < 0) continue;
				var cContentID = cTabID.replace(/^tab/,'tabBody');
				if(tab_id == cTabID) {
					cObj.className = "tab on";
					toGetElementById(cContentID).className = "tabBody show";
				} else {
					$('#'+cContentID).children().remove();
					cObj.className = "tab";
					toGetElementById(cContentID).className = "tabBody hide";
				}
			}
			cObj = cObj.nextSibling;
		}
		if(div) {
			var h = (height-16)/2;
			var html = '<div style="height:'+height+'px;"><div style="padding-top:'+h+'px;text-align:center;"><img src="'+droot+'common/image/ajax_small.gif" width="16" height="16" alt="Roading" /></div></div>';
			$(div).html(html);
			$.ajaxPost(div, file, data);
		}
	};

	/* 탭방식 - None Ajax */
	$.tabPage = function(obj)
	{
		var obj = document.getElementById(obj);
		var tab_id = obj.id;
		var cObj = obj.parentNode.firstChild;

		while(cObj)
		{
			if(cObj.nodeName == "LI" && cObj.id)
			{
				var cTabID= cObj.id;
				if(cTabID.indexOf('tab') < 0) continue;
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
	};

	/* 셀 insert */
	$.cell = function(div, file, data)
	{
		//var el  = toGetElementById(el).getElementsByTagName("DIV");
		//for (var i=0; i<el.length; i++) el[i].style.display = "none";
		$(div).toggle();
		$.ajaxPost(div, file, data);
	}

	/* 메뉴방식 */
	$.menus = function(div, file, el)
	{
		$.each($('div.leftMenu'), function(){
			$(this).hide();
			$("#" + this.id + "_a").removeClass("active");
		});
		$(el).toggleClass("active");
		$(div).animate({height:'toggle',opacity:'toggle'}, 'fast');
		if(file) { $.insert(div, file, null); }
	};

	/* TOP 메뉴 */
	$.changeTopMenu = function()
	{
		$("div.localNavi > ul > .navi").mouseover(function(e){
			if($(this).hasClass("on") === false)
			{
				$("div.localNavi > ul > .navi > .naviSub").hide();
				$("div.localNavi > ul > .navi").removeClass("on");
				$('#' + this.id + 'sub').stop().fadeTo('slow', 1).show();
				$(this).addClass("on");
			}
		}).focusin(function(e){
			if($(this).hasClass("on") === false)
			{
				$("div.localNavi > ul > .navi > .naviSub").hide();
				$("div.localNavi > ul > .navi").removeClass("on");
				$('#' + this.id + 'sub').stop().fadeTo('slow', 1).show();
				$(this).addClass("on");
			}
		});
	};

	/* 언어설정 : select 메뉴 */
	$.changeLang = function()
	{
		$(".langBtn").bind("mouseenter", function(e){
			$(".langBtn > span").css("color","#990000");
			$(".lang").toggle("fast");
		});
		$(".langBtn").bind("mouseleave", function(e){
			$(".langBtn > span").css("color","#444");
			$(".lang").hide("fast");
		});
		$(".langItem").bind("mouseenter", function(e){
			$(this).css({"background":"#666", "color":"#fff"});
		});
		$(".langItem").bind("mouseleave", function(e){
			$(this).css({"background":"#fff", "color":"#444"});
		});
	};


	/* 통합회원 아이디 체크 */
	$.checkOverLap = function(enc, type)
	{
		if(!type) { return false; }
		switch(type) {
			case 'Tid':
				$("#checkId").html('<img src="'+droot+'common/image/ajax_small.gif" width="16" height="16" align="absmiddle" />');
				$.get(droot+"modules/mdMember/widgets/checkTid.php", {type:enc, idx:$("#id").val()}, function(data){
					$("#checkId").html(data);
				});
				break;
			case 'Id':
				$("#checkId").html('<img src="'+droot+'common/image/ajax_small.gif" width="16" height="16" align="absmiddle" />');
				$.get(droot+"modules/mdMember/widgets/checkId.php", {type:enc, idx:$("#id").val()}, function(data){
					$("#checkId").html(data);
				});
				break;
			case 'Nick':
				$("#checkNick").html('<img src="'+droot+'common/image/ajax_small.gif" width="16" height="16" align="absmiddle" />');
				$.get(droot+"modules/mdMember/widgets/checkNick.php", {type:enc, idx:$("#nick").val()}, function(data){
					$("#checkNick").html(data);
				});
				break;
			case 'Email':
				$("#checkEmail").html('<img src="'+droot+'common/image/ajax_small.gif" width="16" height="16" align="absmiddle" />');
				$.get(droot+"modules/mdMember/widgets/checkEmail.php", {type:enc, idx:$("#email").val()}, function(data){
					$("#checkEmail").html(data);
				});
				break;
			case 'Mobile':
				$("#checkMobile").html('<img src="'+droot+'common/image/ajax_small.gif" width="16" height="16" align="absmiddle" />');
				$.get(droot+"modules/mdMember/widgets/checkMobile.php", {type:enc, idx:$("#mobile").val()}, function(data){
					$("#checkMobile").html(data);
				});
				break;
			case 'Cate':
				$("#checkCate").html('<img src="'+droot+'common/image/ajax_small.gif" width="16" height="16" align="absmiddle" />');
				$.get("./modules/categoryCheck.php", {type:enc, parent:$("#cateCode1").val(), cate:$("#cateCode2").val(), cated:$("#cated").val(), skin:$("#skin").val()}, function(data){
					$("#checkCate").html(data);
				});
				break;
			case 'Pwd':
				$("#checkPwd").html('<img src="'+droot+'common/image/ajax_small.gif" width="16" height="16" align="absmiddle" />');
				$.get(droot+"modules/mdMember/widgets/checkPwd.php", {type:enc, idx:$("#passwd").val()}, function(data){
					$("#checkPwd").html(data);
				});
				break;
		}
	};

	/* 주소검색 */
	$.insertAddress = function(d1, d2)
	{
		$("#zipcode").val(d1);
		$("#address01").val(d2);
		$("#address02").select();
		$.dialogRemove();
	};
	/* 주문페이지 주소검색 [수령인] */
	$.insertOrderAddress = function(d1, d2)
	{
		$("#getZipcode").val(d1);
		$("#getAddress01").val(d2);
		$("#getAddress02").select();
		$.dialogRemove();
	};
	/* 쿠폰 상품검색페이지에서 상품선택 */
	$.insertCouponProduct = function(d1)
	{
		var prev = $("#productSelect").val();
		if(prev=="")
			prev = d1;
		else
			prev = prev + ',' + d1;
		$("#productSelect").val(prev);
	};
	/* 쿠폰 상품검색페이지에서 카테고리 선택 */
	$.insertCouponCategory = function(d1)
	{
		var prev = $("#categorySelect").val();
		if(prev=="")
			prev = d1;
		else
			prev = prev + ',' + d1;
		$("#categorySelect").val(prev);
	};

	/* 콤마찍기 */
	$.setComma = function(str)
	{
		str = ""+str+"";
		var retValue = "";
		for(i=0; i<str.length; i++) {
			if(i > 0 && (i%3)==0) {
				retValue = str.charAt(str.length - i -1) + "," + retValue;
			} else {
				retValue = str.charAt(str.length - i -1) + retValue;
			}
		}
		return retValue;
	};

	/* 디스플레이 설정창 열기 */
	$.setDisplay = function(skin, position, cate)
	{
		$("#skinSelector").slideDown("fast", function(){
			$.insert("#skinSelector", droot+"_Admin/modules/displayList.php?type=displayList&mode=design&skin="+skin+"&position="+position+"&cate="+cate, "", 30);
		});
		return true;
	};

	/* 빠른 문자상담 띄우기 */
	$.sms = function()
	{
		var w = 220;
		var h = 440;
		var topMargin = 120;
		var winh = parseInt(getClientWidth() - w) / 2;
		var winv = parseInt(getClientHeight() - h) / 2;
		var html = '<img src="'+droot+'common/image/ajax_small.gif" width="16" height="16" style="margin-top:'+parseInt((h/2)-8)+'px" />';

		$.dialogRemove();
		$('<div id="ajax_header"></div>').css('height',getBodyHeight()).appendTo('body');
		$('<div id="ajax_body"><div id="ajax_display"></div></div>')
			.css({'position':'absolute', 'top':winv+'px', 'left':winh+'px', 'width':w+'px', 'height':h+'px', 'text-align':'center', 'z-index':'2004'}).appendTo('body');
		$('<div id="ajax_close" onclick="$.dialogRemove()"><img src="'+droot+'common/image/icon/icon_close.gif" width="40" height="16" title="Close( Press \'ECS\' )" /></div>').css({'top' : '-22px', 'right' : '0px'}).appendTo('#ajax_body');
		$('#ajax_display').css({'width':w+'px',	'height':h+'px', 'text-align':'center', 'overflow':'auto'}).html(html);
		$.ajaxPost('#ajax_display', droot+'modules/mdSms/widgets/popMobile01.php', '');
		$("#ajax_body").draggable();
		$.dialogScroll("ajax_body", topMargin);
	};

	/* 로그인 다이알로그 창 */
	$.login = function()
	{
		$.dialog(droot + 'index.php', '&cate=000002001&type=loginAjax&mode=dialog', 300, 158);
	};

	/* 스크롤 애니메이션 이동 */
	$.goScroll = function(pos)
	{
		$('html, body').animate({scrollTop:pos}, 'slow');
	};

})(jQuery);

/* 입력폼의 리사징 버튼 (다음에디터) */
jQuery.fn.resizehandle = function() {
	return this.each(function()
	{
		var me = jQuery(this);
		me.after(
			jQuery('<div class="resizeBar"><img id="tx_resize_holder" src="/addon/editor/images/icon/editor/skin/01/btn_drag01.gif" width="58" height="12" unselectable="on" alt="" /></div>').bind('mousedown', function(e) {
			var h = me.height();
			var y = e.clientY;
			var movehandler = function(e) {
				  me.height(Math.max(40, e.clientY + h - y));
			};
			var uphandler = function(e) {
				jQuery('html').unbind('mousemove',movehandler).unbind('mouseup',uphandler);
			};
			jQuery('html') .bind('mousemove', movehandler).bind('mouseup', uphandler);
			})
		);
	});
}