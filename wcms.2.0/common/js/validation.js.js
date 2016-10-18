function validCheck(form, fields)
{
	var lah = new checking(form);
	if (fields) lah.setCheckFields(fields);
	var wmf = lah.go();
	if (wmf == false) { alert(lah.getErrorMessage());	}
  return wmf;
};

checking = function(form)
{
	this.FUNC_MAP = {
		url       : "this.func_url",
		email     : "this.func_email",
		korean    : "this.func_korean",
		engonly   : "this.func_engonly",
		num       : "this.func_num",
		money	  : "this.func_money",
		decnum    : "this.func_decnum",
		residentno: "this.func_residentno",
		idcode    : "this.func_idcode",
		foreignerno:"this.func_foreignerno",
		bizno     : "this.func_bizno",
		phone     : "this.func_phone",
		homephone : "this.func_homephone",
		mobile	  : "this.func_mobile",
		userid    : "this.func_userid",
		nick      : "this.func_nick",
		filename  : "this.func_filename",
		date	  : "this.func_date"
	};
	this.ERR_MSG = {
		system   : "checking Error: ",
		required : "필수 사항 입니다.",
		requirenum:"이 항목들 중에 {requirenum}개 이상의 항목이 입력되어야 합니다.",
		notequal : "입력된 내용이 일치하지 않습니다.",
		invalid  : "입력된 내용이 형식에 어긋납니다.",
		minbyte  : "입력된 내용의 길이가 {minbyte}Byte 이상이어야 합니다.",
		maxbyte  : "입력된 내용의 길이가 {maxbyte}Byte를 초과할 수 없습니다.",
		mincheck : "{mincheck}개의 항목이상으로 선택하세요.",
		maxcheck : "{maxcheck}개의 항목이하로 선택하세요.",
		minselect: "{minselect}개의 항목이상으로 선택하세요.",
		maxselect: "{maxselect}개의 항목이하로 선택하세요.",
		imageonly: "이미지 파일만 첨부할 수 있습니다."
	};
	this.ERR_DO = {
		text   : "select focus",
		select : "focus",
		check  : "focus",
		radio  : "focus",
		file   : "focus",
		hidden : ""
	};
	this.ERR_SYS = '_SYSERR_';
	this.fields = form.elements;
	this.form = form;
	this.errMsg = "";
};

checking.prototype.setForm = function(form) {
	this.form = form;
};

checking.prototype.setFunc = function(map, func) {
	if (typeof(this.FUNC_MAP[map]) == "string") return;
	this.FUNC_MAP[map] = func;
};

checking.prototype.setCheckFields = function(fields) {
	this.fields = [];
	if(typeof(fields) == 'string') {
			this.fields = [this.form.elements[fields]];
	} else {
			for(var i=0, s=fields.length; i<s; i++)
					this.fields[this.fields.length] = this.form.elements[fields[i]];
	}
};

checking.prototype.setUnCheckFields = function(fields) {
	this.fields = [];
	var _isUnCheckEl;

	if(typeof(fields) == 'string') {
			fields = [fields];
	for (var i=0, s=this.form.elements.length; i<s; i++) {
			_isUnCheckEl = false;
			for (var j=0, t=fields.length; j<t; j++) {
					if (this.form.elements[i] == this.form.elements[fields[j]]) {
							_isUnCheckEl = true;
							break;
					}
			}
			if (!_isUnCheckEl) this.fields[this.fields.length] = this.form.elements[i];
	}
	}
};

checking.prototype.setParam = function(el, name, value) {
	el.setAttribute(name, value);
};

checking.prototype.delParam = function(el, name) {
	el.removeAttribute(name);
};

checking.prototype.go = function() {
    for (var i=0,s=this.fields.length; i<s; i++) {
        var el = this.fields[i];
        if (!this.isValidElement(el)) continue;

        var elType = this.getType(el);
        var trim = el.getAttribute("TRIM");
        var required = el.getAttribute("REQ");
        var requirenum = el.getAttribute("REQUIRENUM");
        var minbyte = parseInt(el.getAttribute("MINBYTE"),10);
        var maxbyte = parseInt(el.getAttribute("MAXBYTE"),10);
        var mincheck = parseInt(el.getAttribute("MINCHECK"),10);
        var maxcheck = parseInt(el.getAttribute("MAXCHECK"),10);
        var minselect = parseInt(el.getAttribute("MINSELECT"),10);
        var maxselect = parseInt(el.getAttribute("MAXSELECT"),10);
        var option = el.getAttribute("OPT");
        var match = el.getAttribute("MATCH");
        var span = el.getAttribute("SPAN");
        var glue = el.getAttribute("GLUE");
        var pattern = el.getAttribute("PATTERN");
        var imageonly = el.getAttribute("IMAGEONLY");

        if (trim != null && (elType == "text" || elType == "hidden")) {
            switch (trim) {
            case "trim":
                el.value = el.value.replace(/^\s+/, "").replace(/\s+$/, "");
                break;
            case "compress":
                el.value = el.value.replace(/\s+/, "");
                break;
            case "ltrim":
                el.value = el.value.replace(/^\s+/, "");
                break;
            case "rtrim":
                el.value = el.value.replace(/\s+$/, "");
                break;
            }
        }

        var elEmpty = this.isEmpty(el, elType);

        if (required != null) {
            if (required == "required") {
                if (elEmpty) return this.raiseError(el, "required");
            } else {
                requirenum = parseInt(requirenum, 10);
                var _num = 0;
                var _name = [];
                if (requirenum > 0) {
                    for (var j=0; j<this.form.elements.length; j++) {
                        var _el = this.form.elements[j];
                        if (required == _el.getAttribute("REQ")) {
                            if(!this.isEmpty(_el, this.getType(_el))) _num++;
                            _name[_name.length] = this.getName(_el);
                        }
                    }
                    if(_num < requirenum)
                        return this.raiseError(el, "requirenum", _name.join(", "));
                }
            }
        }
        if ((minbyte > 0 || maxbyte > 0) && (elType == "text" || elType == "hidden")) {
            var _tmp = el.value;
            var _len = el.value.length;
            for (j=0; j<_tmp.length; j++) {
                if (_tmp.charCodeAt(j) > 128) _len++;
            }
            if (minbyte > 0 && _len < minbyte) return this.raiseError(el, "minbyte");
            if (maxbyte > 0 && _len > maxbyte) return this.raiseError(el, "maxbyte");
        }
        if (match != null && elType != "file") {
            if (typeof this.form.elements[match] == "undefined")
                return this.raiseError(this.ERR_SYS, "Element '"+ match +"' is not found.");
            else if (el.value != this.form.elements[match].value)
                return this.raiseError(el, "notequal");
        }
        if (option != null && !elEmpty && elType != "file") {
            var _options = option.split(" ");
            for (var j in _options) {
                var _func = eval(this.FUNC_MAP[_options[j]]);
                if (span != null) {
                    var _value = [];
                    for (var k=0; k<parseInt(span,10); k++) {
                        try {
                            _value[k] = this.fields[i+k].value;
                        } catch (e) {
                            return this.raiseError(this.ERR_SYS,  (i+k) +"th Element is not found.");
                        }
                    }
                    try {
                        var _result = _func(el, _value.join(glue == null ? "" : glue));
                    } catch (e) {
                        return this.raiseError(this.ERR_SYS,  "function map '"+ _options[j] +"' is not exist.");
                    }
                    if (_result !== true) return this.raiseError(el, _result);
                } else {
                    try {
                        var _result = _func(el);
                    } catch (e) {
                        return this.raiseError(this.ERR_SYS,  "function map '"+ _options[j] +"' is not exist.");
                    }
                    if (_result !== true) return this.raiseError(el, _result);
                }
            }
        }
        if (pattern != null && !elEmpty && elType != "file") {
            try {
                pattern = new RegExp(pattern);
            } catch (e) {
                return this.raiseError(this.ERR_SYS, "Invalid Regular Expression '"+ pattern +"'");
            }
            if (!pattern.test(el.value)) return this.raiseError(el, "invalid");
        }
        if ((mincheck > 0 || maxcheck > 0) && elType == "check") {
            var _checks = this.form.elements[el.name];
            var _num = 0;
            if (typeof _checks.length != "undefined") {
                for (var j=0; j<_checks.length; j++) {
                    if (_checks[j].checked) _num++;
                }
            } else {
                if (_checks.checked) _num++;
            }
            if (mincheck > 0 && _num < mincheck) return this.raiseError(el, "mincheck");
            if (maxcheck > 0 && _num > maxcheck) return this.raiseError(el, "maxcheck");
        }
        if ((minselect > 0 || maxselect > 0) && elType == "multiselect") {
            var _num = 0;
            for (var j=0; j<el.options.length; j++) {
                if (el.options[j].selected) _num++;
            }
            if (minselect > 0 && _num < minselect) return this.raiseError(el, "minselect");
            if (maxselect > 0 && _num > maxselect) return this.raiseError(el, "maxselect");
        }
        if (imageonly != null && elType == "file") {
            var fn = el.value;
            if (fn != "") {
                var dotIndex = fn.lastIndexOf(".");
                var ext = fn.substring(dotIndex+1).toLowerCase();
                if(ext != "jpg" && ext != "jpeg" && ext != "gif" && ext != "png")
                    return this.raiseError(el, "imageonly");
            }
        }
    }
    return true;
};

checking.prototype.isValidElement = function(el) {
    return el.name && el.tagName.match(/^input|select|textarea$/i) && !el.disabled;
};

checking.prototype.isEmpty = function(el, type) {
    switch (type) {
    case "file": case "text": case "hidden":
        if (el.value == null || el.value == "") return true;
        break;
    case "select": case "multiselect":
        if (el.selectedIndex == -1 || el[el.selectedIndex].value == null ||
                el[el.selectedIndex].value == "")
            return true;
        break;
    case "check": case "radio":
        var elCheck = this.form.elements[el.name];
        var elChecked = false;
        if (typeof elCheck.length != "undefined") {
            for (var j=0; j<elCheck.length; j++) {
                if (elCheck[j].checked == true) elChecked = true;
            }
        } else {
            if (elCheck.checked == true) elChecked = true;
        }
        if (elChecked == false) return true;
        break;
    }
    return false;
};

checking.prototype.getType = function(el) {
    switch (el.tagName.toLowerCase()) {
    case "select":
        return el.multiple == true ? "multiselect" : "select";
    case "textarea": return "text";
    case "input":
        switch (el.type.toLowerCase()) {
        case "radio": return "radio";
        case "checkbox": return "check";
        case "file": return "file";
        case "text": case "password": return "text";
        case "hidden": return "hidden";
        }
        break;
    }
};

checking.prototype.raiseError = function(el, type, elName) {
    if (el == this.ERR_SYS) {
        this.errMsg = this.ERR_MSG["system"] + type;
        return false;
    }
    var pattern = /\{([a-zA-Z0-9_]+)\}/i;
    var msg = this.ERR_MSG[type] ? this.ERR_MSG[type] : type;
    var elType = this.getType(el);
    var elName = elName ? elName : this.getName(el);
    var errDo = el.getAttribute("ERRDO") ? el.getAttribute("ERRDO") : this.ERR_DO[elType];
    var _errDos = errDo ? errDo.split(" ") : [];

    if (el.getAttribute("ERRMSG") != null) msg = el.getAttribute("ERRMSG");
    if (pattern.test(msg) == true) {
        while (pattern.exec(msg)) msg = msg.replace(pattern, el.getAttribute(RegExp.$1));
    }
    for (var i in _errDos) {
        switch (_errDos[i]) {
        case "delete": el.value = ""; break;
        case "select": el.select(); break;
        case "focus":  el.focus(); break;
        }
    }
    this.errMsg = "\'"+ elName +"\'은(는) "+ msg +"\n";
    return false;
};

checking.prototype.getErrorMessage = function() {
    return this.errMsg;
};

checking.prototype.getName = function(el) {
    return el.getAttribute("TITLE") == null || el.getAttribute("TITLE") == ""
        ? el.name : el.getAttribute("TITLE");
};

checking.prototype.func_url = function(el,value) {
    var value = value ? value : el.value;
    var pattern = /^[\/\:\.가-힣a-zA-Z0-9_-]+\.[a-zA-Z]+$/;
    return pattern.test(value) ? true : "invalid";
};

checking.prototype.func_email = function(el,value) {
    var value = value ? value : el.value;
    var pattern = /^[_a-zA-Z0-9-\.]+@[\.a-zA-Z0-9-]+\.[a-zA-Z]+$/;
    return pattern.test(value) ? true : "invalid";
};

checking.prototype.func_korean = function(el) {
    var pattern = /^[가-힣]+$/;
    return pattern.test(el.value) ? true : "반드시 한글로만 작성하셔야 합니다";
};

checking.prototype.func_engonly = function(el) {
    var pattern = /^[a-zA-Z]+$/;
    return pattern.test(el.value) ? true : "invalid";
};

checking.prototype.func_num = function(el) {
    var pattern = /^[0-9]+$/;
    return pattern.test(el.value) ? true : "반드시 숫자로만 입력해야 합니다";
};

checking.prototype.func_money = function(el) {
    var pattern = /^[0-9\,]+$/;
    return pattern.test(el.value) ? true : "반드시 숫자와 콤마로 입력해야 합니다";
};

checking.prototype.func_decnum = function(el) {
    var pattern = /^[0-9\.]+$/;
    return pattern.test(el.value) ? true : "반드시 실수와 정수로만 입력해야 합니다";
};

checking.prototype.func_residentno = function(el,value) {
    var pattern = /^(\d{6})-?(\d{5}(\d{1})\d{1})$/;
    var num = value ? value : el.value;
    if (!pattern.test(num)) return "invalid";
    num = RegExp.$1 + RegExp.$2;
    if (RegExp.$3 == 7 || RegExp.$3 == 8 || RegExp.$4 == 9)
        if ((num[7]*10 + num[8]) %2) return "invalid";

    var sum = 0;
    var last = num.charCodeAt(12) - 0x30;
    var bases = "234567892345";
    for (var i=0; i<12; i++) {
        if (isNaN(num.substring(i,i+1))) return "invalid";
        sum += (num.charCodeAt(i) - 0x30) * (bases.charCodeAt(i) - 0x30);
    }
    var mod = sum % 11;
    if(RegExp.$3 == 7 || RegExp.$3 == 8 || RegExp.$4 == 9)
        return (11 - mod + 2) % 10 == last ? true : "invalid";
    else
        return (11 - mod) % 10 == last ? true : "invalid";
};

checking.prototype.func_idcode = function(el,value) {
    var pattern = /^([0-9]{6})-?([0-9]{7})$/;
    var num = value ? value : el.value;
    if (!pattern.test(num)) return "invalid";
    num = RegExp.$1 + RegExp.$2;

    var sum = 0;
    var last = num.charCodeAt(12) - 0x30;
    var bases = "234567892345";
    for (var i=0; i<12; i++) {
        if (isNaN(num.substring(i,i+1))) return "invalid";
        sum += (num.charCodeAt(i) - 0x30) * (bases.charCodeAt(i) - 0x30);
    }
    var mod = sum % 11;
    return (11 - mod) % 10 == last ? true : "invalid";
};

checking.prototype.func_foreignerno = function(el,value) {
    var pattern = /^(\d{6})-?(\d{5}[7-9]\d{1})$/;
    var num = value ? value : el.value;
    if (!pattern.test(num)) return "invalid";
    num = RegExp.$1 + RegExp.$2;
    if ((num[7]*10 + num[8]) %2) return "invalid";

    var sum = 0;
    var last = num.charCodeAt(12) - 0x30;
    var bases = "234567892345";
    for (var i=0; i<12; i++) {
        if (isNaN(num.substring(i,i+1))) return "invalid";
        sum += (num.charCodeAt(i) - 0x30) * (bases.charCodeAt(i) - 0x30);
    }
    var mod = sum % 11;
    return (11 - mod + 2) % 10 == last ? true : "invalid";
};

checking.prototype.func_bizno = function(el,value) {
    var pattern = /([0-9]{3})-?([0-9]{2})-?([0-9]{5})/;
    var num = value ? value : el.value;
    if (!pattern.test(num)) return "invalid";
    num = RegExp.$1 + RegExp.$2 + RegExp.$3;
    var cVal = 0;
    for (var i=0; i<8; i++) {
        var cKeyNum = parseInt(((_tmp = i % 3) == 0) ? 1 : ( _tmp  == 1 ) ? 3 : 7);
        cVal += (parseFloat(num.substring(i,i+1)) * cKeyNum) % 10;
    }
    var li_temp = parseFloat(num.substring(i,i+1)) * 5 + "0";
    cVal += parseFloat(li_temp.substring(0,1)) + parseFloat(li_temp.substring(1,2));
    return parseInt(num.substring(9,10)) == 10-(cVal % 10)%10 ? true : "invalid";
};

checking.prototype.func_phone = function(el,value) {
    var pattern = /^(0[2-8][0-5]?|01[01346-9])-?([1-9]{1}[0-9]{2,3})-?([0-9]{4})$/;
    var pattern15xx = /^(1544|1566|1577|1588|1644|1688)-?([0-9]{4})$/;
    var num = value ? value : el.value;
    return pattern.exec(num) || pattern15xx.exec(num) ? true : "invalid";
};

checking.prototype.func_homephone = function(el,value) {
    var pattern = /^(0[2-8][0-5]?)-?([1-9]{1}[0-9]{2,3})-?([0-9]{4})$/;
    var pattern15xx = /^(1544|1566|1577|1588|1644|1688)-?([0-9]{4})$/;
    var num = value ? value : el.value;
    return pattern.exec(num) || pattern15xx.exec(num) ? true : "invalid";
};

checking.prototype.func_mobile = function(el,value) {
    var pattern = /^(01[01346-9])-?([1-9]{1}[0-9]{2,3})-?([0-9]{4})$/;
    var num = value ? value : el.value;
    return pattern.exec(num) ? true : "invalid";
};

checking.prototype.func_userid = function(el,value) {
	var pattern = /^[a-zA-Z0-9\_]{4,15}$/;
    return pattern.exec(el.value) ? true : "4자이상 15자 미만으로 입력하십시오.\n  [영문], [숫자], [ _ ] 문자만 사용할 수 있습니다";
};

checking.prototype.func_nick = function(el,value) {
	var pattern = /^[가-힣a-zA-Z0-9]{3,15}$/;
    return pattern.exec(el.value) ? true : "3자이상 15자 미만으로 입력하십시오.\n  [한글] [영문] [숫자] 문자만 사용할 수 있습니다";
};

checking.prototype.func_filename = function(el,value) {
    var pattern = /^[\.가-힝a-zA-Z0-9_-]{1,40}$/;
    return pattern.exec(el.value) ? true : "1자이상 20자 미만으로 입력하십시오.\n  [한글] [영문] [숫자] [ _ ] [ - ] 문자만 사용할 수 있습니다";
};

checking.prototype.func_date = function(el) {
    var pattern = /^[12][0-9]{3}\-[01]?[0-9]\-[0-3]?[0-9]$/;
    return pattern.exec(el.value) ? true : "년도-월-일(2008-01-01) 형식으로 입력하셔야 합니다.";
};
