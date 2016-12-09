//Initial js functions to run when js document is loaded
checkBrowserWidth();

//Load when the page is ready - Using jQuery to run events
$(document).ready(function(){
						   
parseCode('thickbox-code/thickbox.js','ajaxJS');
parseCode('thickbox-code/thickbox.css','ajaxCSS');
parseCode('thickbox-code/thickbox-compressed.js','ajaxJSCompressed');
				  		  
$(window).resize(checkBrowserWidth);
$('div.roundbox').corner('round 9px');
$('div.anchor').corner('round bottom 9px');
$.tabs('container-1');
$.tabs('container-2');
$.tabs('container-3');
$.tabs('container-4');
$.tabs('container-5');
$.tabs('container-6');

$('#overviewBtn').click(function(){
		$('#overviewTitle').ScrollTo(800)
		if(document.getElementById('overview').style.display == "none"){$('#overview').slideDown(500);$('#toggleBtnOverview').html('-')};
		return false;
		});

$('#examplesBtn').click(function(){
		 $('#examplesTitle').ScrollTo(800);
		 if(document.getElementById('examples').style.display == "none"){$('#examples').slideDown(500);$('#toggleBtnExample').html('-')};
		 return false;
		 });

$('#qaBtn').click(function(){
	   $('#qaTitle').ScrollTo(800);
	   if(document.getElementById('qa').style.display == "none"){$('#qa').slideDown(500);$('#toggleBtnQa').html('-')};
	   return false;
	   });

$('#supportBtn').click(function(){
		$('#supportTitle').ScrollTo(800);
		if(document.getElementById('support').style.display == "none"){$('#support').slideDown(500);$('#toggleBtnSupport').html('-')};
		return false;
		});

$('#donateBtn').click(function(){
	   $('#donateTitle').ScrollTo(800);
	   if(document.getElementById('donate').style.display == "none"){$('#donate').slideDown(500);$('#toggleBtnDonate').html('-')};
	   return false;
	   });

$('.BTT').click(function(){$('#pageTop').ScrollTo(800);return false});

$(".toggleBtn").toggle(function(){
	if(document.getElementById(this.rel).style.display == "none"){$("#" + this.rel).slideDown(500);this.innerHTML = "-";return};
	this.innerHTML = "+";
	
    $("#" + this.rel).slideUp(500);
  },function(){
	 if(document.getElementById(this.rel).style.display == "block"){$("#" + this.rel).slideUp(500); this.innerHTML = "+";return};
	 this.innerHTML = "-";
    $("#" + this.rel).slideDown(500);
	
	
  });

}
);


///////////////////////////  ajax call to get code off the server for display dependent code ///////////////////////////////////////
function parseCode(o,placement){
	$.get(o,function(code){										  	   
		  code=code.replace(/&/mg,'&#38;');
		  code=code.replace(/</mg,'&#60;');
		  code=code.replace(/>/mg,'&#62;');
		  code=code.replace(/\"/mg,'&#34;');
		  code=code.replace(/\t/g,'  ');
		  code=code.replace(/\r?\n/g,'<br>');
		  code=code.replace(/<br><br>/g,'<br>');
		  code=code.replace(/ /g,'&nbsp;');
		 $('#'+placement).html(code);
	}
	);
 }


///////////////////////////  resolution dependent code ////////////////////////////////////////////////////////////////////////////////
function checkBrowserWidth(){
	var theWidth = getBrowserWidth();
	if (theWidth == 0){
		var resolutionCookie = document.cookie.match(/(^|;)tmib_res_layout[^;]*(;|$)/);

		if (resolutionCookie != null){
			setStylesheet(unescape(resolutionCookie[0].split("=")[1]));
			}
	
		$(document).load(checkBrowserWidth);
		return false;
	}

	if (theWidth > 900){
		setStylesheet("1024 x 768");
		document.cookie = "tmib_res_layout=" + escape("1024 x 768");
	}else{
		setStylesheet("");
		document.cookie = "tmib_res_layout=";
	}
	return true;
};




function getBrowserWidth(){
	if (window.innerWidth){
		return window.innerWidth;
	}else if (document.documentElement && document.documentElement.clientWidth != 0){
		return document.documentElement.clientWidth;}
	else if (document.body){
		return document.body.clientWidth;
	}
	return 0;
};




function setStylesheet(styleTitle){
	var currTag;

	if (document.getElementsByTagName){
		for (var i = 0; (currTag = document.getElementsByTagName("link")[i]); i++){
			if (currTag.getAttribute("rel").indexOf("style") != -1 && currTag.getAttribute("title")){
				currTag.disabled = true;
				if(currTag.getAttribute("title") == styleTitle){
					currTag.disabled = false;
				}
			}
		}
	}
	
	return true;
};

///////////////////////////  round corners jquery plugin ////////////////////////////////////////////////////////////////////////////////
$.fn.corner = function(o)
{
	o = o || "";
	var width = parseInt((o.match(/(\d+)px/)||[])[1]) || 10;
	var fx = (o.match(/round|bevel|fold|notch/)||["round"])[0];
	var opts = {
		TL:		/top|tl/i.test(o), 		TR:		/top|tr/i.test(o),
		BL:		/bottom|bl/i.test(o),	BR:		/bottom|br/i.test(o)//,
	};
	if ( !opts.TL && !opts.TR && !opts.BL && !opts.BR )
		opts = { TL:1, TR:1, BL:1, BR:1 };
	var strip = document.createElement("div");
	strip.style.overflow = "hidden";
	strip.style.height = "1px";
	strip.style.backgroundColor = "transparent";
	strip.style.borderStyle = "solid";
	return this.each(function(){
		var pad = {
			T: parseInt($.css(this,"paddingTop"))||0,
			R: parseInt($.css(this,"paddingRight"))||0,
			B: parseInt($.css(this,"paddingBottom"))||0,
			L: parseInt($.css(this,"paddingLeft"))||0
		};
		strip.style.borderColor = "#ffffff";
		if ( opts.TL || opts.TR ) {
			strip.style.borderStyle = "none "+(opts.TR?"solid":"none")+" none "+(opts.TL?"solid":"none");
			var t=document.createElement("div");
			t.style.margin = "-"+pad.T+"px -"+pad.R+"px "+(pad.T-width)+"px -"+pad.L+"px";
			t.style.backgroundColor = "transparent";
			for ( var i=0; i < width; i++ ) {
				var w = fx=="round" ? Math.round(width*(1-Math.cos(Math.asin(i/width)))) : i+1;
				var e = strip.cloneNode(false);
				e.style.borderWidth = "0 "+(opts.TR?w:0)+"px 0 "+(opts.TL?w:0)+"px";
				t.insertBefore(e, t.firstChild);
			}
			this.insertBefore(t, this.firstChild);
		}
		if ( opts.BL || opts.BR ) {
			strip.style.borderStyle = "none "+(opts.BR?"solid":"none")+" none "+(opts.BL?"solid":"none");
			var b=document.createElement("div");
			b.style.margin = (pad.B-width)+"px -"+pad.R+"px -"+pad.B+"px -"+pad.L+"px";
			b.style.backgroundColor = "transparent";
			for ( var i=0; i < width; i++ ) {
				var w = fx=="round" ? Math.round(width*(1-Math.cos(Math.asin(i/width)))) : i+1;
				var e = strip.cloneNode(false);
				e.style.borderWidth = "0 "+(opts.BR?w:0)+"px 0 "+(opts.BL?w:0)+"px";
				b.appendChild(e);
			}
			this.appendChild(b);
		}
	});
};

//////////////////// tabs jquery plugin ////////////////////////////////////////////////////////////////////////////
$.tabs = function(containerId, start) {
    var ON_CLASS = 'on';
    var id = '#' + containerId;
    var i = (typeof start == "number") ? start - 1 : 0;
    $(id + '>div:eq(' + i + ')').css({display:"block"});
    $(id + '>ul>li:nth-child(' + (i+1) + ')').addClass(ON_CLASS);
    $(id + '>ul>li>a').click(function() {
        if (!$(this.parentNode).is('.' + ON_CLASS)) {
            var re = /([_\-\w]+$)/i;
            var target = $('#' + re.exec(this.href)[1]);
            if (target.size() > 0) {
                $(id + '>div:visible').css({display:"none"});
                target.css({display:"block"});
                $(id + '>ul>li').removeClass(ON_CLASS);
                $(this.parentNode).addClass(ON_CLASS);
            } else {
                alert('There is no such container.');
            }
        }
        return false;
    });
};

//////////////////// Unobtrustive Code Highlighter By Dan Webb ////////////////////////////////////////////////////////////////////////////
var CodeHighlighter = { styleSets : new Array };

CodeHighlighter.addStyle = function(name, rules) {
	// using push test to disallow older browsers from adding styleSets
	if ([].push) this.styleSets.push({
		name : name, 
		rules : rules,
		ignoreCase : arguments[2] || false
	})
	
	function setEvent() {
		setTimeout('$(document).ready(function(){CodeHighlighter.init()})',1000)
	}
	
	// only set the event when the first style is added
	if (this.styleSets.length==1) setEvent();
}

CodeHighlighter.init = function() {
	if (!document.getElementsByTagName) return; 
	if ("a".replace(/a/, function() {return "b"}) != "b") return; // throw out Safari versions that don't support replace function
	// throw out older browsers
	
	var codeEls = document.getElementsByTagName("CODE");
	// collect array of all pre elements
	codeEls.filter = function(f) {
		var a =  new Array;
		for (var i = 0; i < this.length; i++) if (f(this[i])) a[a.length] = this[i];
		return a;
	} 
	
	var rules = new Array;
	rules.toString = function() {
		// joins regexes into one big parallel regex
		var exps = new Array;
		for (var i = 0; i < this.length; i++) exps.push(this[i].exp);
		return exps.join("|");
	}
	
	function addRule(className, rule) {
		// add a replace rule
		var exp = (typeof rule.exp != "string")?String(rule.exp).substr(1, String(rule.exp).length-2):rule.exp;
		// converts regex rules to strings and chops of the slashes
		rules.push({
			className : className,
			exp : "(" + exp + ")",
			length : (exp.match(/(^|[^\\])\([^?]/g) || "").length + 1, // number of subexps in rule
			replacement : rule.replacement || null 
		});
	}
	
	function parse(text, ignoreCase) {
		// main text parsing and replacement
		return text.replace(new RegExp(rules, (ignoreCase)?"gi":"g"), function() {
			var i = 0, j = 1, rule;
			while (rule = rules[i++]) {
				if (arguments[j]) {
					// if no custom replacement defined do the simple replacement
					if (!rule.replacement) return "<span class=\"" + rule.className + "\">" + arguments[0] + "</span>";
					else {
						// replace $0 with the className then do normal replaces
						var str = rule.replacement.replace("$0", rule.className);
						for (var k = 1; k <= rule.length - 1; k++) str = str.replace("$" + k, arguments[j + k]);
						return str;
					}
				} else j+= rule.length;
			}
		});
	}
	
	function highlightCode(styleSet) {
		// clear rules array
		var parsed;
		rules.length = 0;
		
		// get stylable elements by filtering out all code elements without the correct className	
		var stylableEls = codeEls.filter(function(item) {return (item.className.indexOf(styleSet.name)>=0)});
		
		// add style rules to parser
		for (var className in styleSet.rules) addRule(className, styleSet.rules[className]);
		
			
		// replace for all elements
		for (var i = 0; i < stylableEls.length; i++) {
			// EVIL hack to fix IE whitespace badness if it's inside a <pre>
			if (/MSIE/.test(navigator.appVersion) && stylableEls[i].parentNode.nodeName == 'PRE') {
				stylableEls[i] = stylableEls[i].parentNode;
				
				parsed = stylableEls[i].innerHTML.replace(/(<code[^>]*>)([^<]*)<\/code>/i, function() {
					return arguments[1] + parse(arguments[2], styleSet.ignoreCase) + "</code>"
				});
				parsed = parsed.replace(/\n( *)/g, function() { 
					var spaces = "";
					for (var i = 0; i < arguments[1].length; i++) spaces+= "&nbsp;";
					return "\n" + spaces;  
				});
				parsed = parsed.replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;");
				parsed = parsed.replace(/\n(<\/\w+>)?/g, "<br />$1").replace(/<br \/>[\n\r\s]*<br \/>/g, "<p><br></p>");
				
			} else parsed = parse(stylableEls[i].innerHTML, styleSet.ignoreCase);
			
			stylableEls[i].innerHTML = parsed;
		}
	}
	
	// run highlighter on all stylesets
	for (var i in this.styleSets) highlightCode(this.styleSets[i]);
}

CodeHighlighter.addStyle("javascript",{
	comment : {
		exp  : /(\/\/[^\n]*\n)|(\/\*[^*]*\*+([^\/][^*]*\*+)*\/)/
	},
	brackets : {
		exp  : /\(|\)/
	},
	string : {
		exp  : /'[^']*'|"[^"]*"/
	},
	keywords : {
		exp  : /\b(arguments|break|case|continue|default|delete|do|else|false|for|function|if|in|instanceof|new|null|return|switch|this|true|typeof|var|void|while|with)\b/
	},
	global : {
		exp  : /\b(toString|valueOf|window|element|prototype|constructor|document|escape|unescape|parseInt|parseFloat|setTimeout|clearTimeout|setInterval|clearInterval|NaN|isNaN|Infinity)\b/
	}
});

CodeHighlighter.addStyle("html", {
	comment : {
		exp: /&lt;!\s*(--([^-]|[\r\n]|-[^-])*--\s*)&gt;/
	},
	tag : {
		exp: /(&lt;\/?)([a-zA-Z]+\s?)/, 
		replacement: "$1<span class=\"$0\">$2</span>"
	},
	string : {
		exp  : /'[^']*'|"[^"]*"/
	},
	attribute : {
		exp: /\b([a-zA-Z-:]+)(=)/, 
		replacement: "<span class=\"$0\">$1</span>$2"
	},
	doctype : {
		exp: /&lt;!DOCTYPE([^&]|&[^g]|&g[^t])*&gt;/
	}
});

CodeHighlighter.addStyle("css", {
	comment : {
		exp  : /\/\*[^*]*\*+([^\/][^*]*\*+)*\//
	},
	keywords : {
		exp  : /@\w[\w\s]*/
	},
	selectors : {
		exp  : "([\\w-:\\[.#][^{};>]*)(?={)"
	},
	properties : {
		exp  : "([\\w-]+)(?=\\s*:)"
	},
	units : {
		exp  : /([0-9])(em|en|px|%|pt)\b/,
		replacement : "$1<span class=\"$0\">$2</span>"
	},
	urls : {
		exp  : /url\([^\)]*\)/
	}
 });

//////////////////// scroll ////////////////////////////////////////////////////////////////////////////
jQuery.getPos = function (e)
{
	var l = 0;
	var t  = 0;
	var w = jQuery.intval(jQuery.css(e,'width'));
	var h = jQuery.intval(jQuery.css(e,'height'));
	var wb = e.offsetWidth;
	var hb = e.offsetHeight;
	while (e.offsetParent){
		l += e.offsetLeft + (e.currentStyle?jQuery.intval(e.currentStyle.borderLeftWidth):0);
		t += e.offsetTop  + (e.currentStyle?jQuery.intval(e.currentStyle.borderTopWidth):0);
		e = e.offsetParent;
	}
	l += e.offsetLeft + (e.currentStyle?jQuery.intval(e.currentStyle.borderLeftWidth):0);
	t  += e.offsetTop  + (e.currentStyle?jQuery.intval(e.currentStyle.borderTopWidth):0);
	return {x:l, y:t, w:w, h:h, wb:wb, hb:hb};
};
jQuery.getClient = function(e)
{
	if (e) {
		w = e.clientWidth;
		h = e.clientHeight;
	} else {
		w = (window.innerWidth) ? window.innerWidth : (document.documentElement && document.documentElement.clientWidth) ? document.documentElement.clientWidth : document.body.offsetWidth;
		h = (window.innerHeight) ? window.innerHeight : (document.documentElement && document.documentElement.clientHeight) ? document.documentElement.clientHeight : document.body.offsetHeight;
	}
	return {w:w,h:h};
};
jQuery.getScroll = function (e) 
{
	if (e) {
		t = e.scrollTop;
		l = e.scrollLeft;
		w = e.scrollWidth;
		h = e.scrollHeight;
	} else  {
		if (document.documentElement && document.documentElement.scrollTop) {
			t = document.documentElement.scrollTop;
			l = document.documentElement.scrollLeft;
			w = document.documentElement.scrollWidth;
			h = document.documentElement.scrollHeight;
		} else if (document.body) {
			t = document.body.scrollTop;
			l = document.body.scrollLeft;
			w = document.body.scrollWidth;
			h = document.body.scrollHeight;
		}
	}
	return { t: t, l: l, w: w, h: h };
};

jQuery.intval = function (v)
{
	v = parseInt(v);
	return isNaN(v) ? 0 : v;
};

jQuery.fn.ScrollTo = function(s) {
	o = jQuery.speed(s);
	return this.each(function(){
		new jQuery.fx.ScrollTo(this, o);
	});
};

jQuery.fx.ScrollTo = function (e, o)
{
	var z = this;
	z.o = o;
	z.e = e;
	z.p = jQuery.getPos(e);
	z.s = jQuery.getScroll();
	z.clear = function(){clearInterval(z.timer);z.timer=null};
	z.t=(new Date).getTime();
	z.step = function(){
		var t = (new Date).getTime();
		var p = (t - z.t) / z.o.duration;
		if (t >= z.o.duration+z.t) {
			z.clear();
			setTimeout(function(){z.scroll(z.p.y, z.p.x)},13);
		} else {
			st = ((-Math.cos(p*Math.PI)/2) + 0.5) * (z.p.y-z.s.t) + z.s.t;
			sl = ((-Math.cos(p*Math.PI)/2) + 0.5) * (z.p.x-z.s.l) + z.s.l;
			z.scroll(st, sl);
		}
	};
	z.scroll = function (t, l){window.scrollTo(l, t)};
	z.timer=setInterval(function(){z.step();},13);
};
