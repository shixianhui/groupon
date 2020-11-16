function openwinx(url,name,w,h)
{
    window.open(url,name,"top=100,left=400,width=" + w + ",height=" + h + ",toolbar=no,menubar=no,scrollbars=yes,resizable=no,location=no,status=no");
}

function Dialog(url,name,w,h)
{
	return showModalDialog(url, name, 'dialogWidth:'+w+'px; dialogHeight:'+h+'px; help: no; scroll: yes; status: no');
}

function redirect(url)
{
	if(url.lastIndexOf('/.') > 0)
	{
		url = url.replace(/\/(\.[a-zA-Z]+)([0-9]+)$/g, "/$2$1");
	}
	else if(url.match(/\/([a-z]+).html([0-9]+)$/)) {
		url = url.replace(/\/([a-z]+).html([0-9]+)$/, "/$1-$2.html");
	}
	else if(url.match(/-.html([0-9]+)$/)) {
		url = url.replace(/-.html([0-9]+)$/, "-$1.html");
	}

	if(url.indexOf('://') == -1 && url.substr(0, 1) != '/' && url.substr(0, 1) != '?') url = $('base').attr('href')+url;
	location.href = url;
}

//添加收藏夹
function myAddPanel(title,url)
{
    if ((typeof window.sidebar == 'object') && (typeof window.sidebar.addPanel == 'function'))
    {
        window.sidebar.addPanel(title,url,"");
    }
    else
    {
        window.external.AddFavorite(url,title);
    }
}
function confirmurl(url,message)
{
	if(confirm(message)) redirect(url);
}

function confirmform(form,message)
{
	if(confirm(message)) form.submit();
}

function getcookie(name)
{
    //name = cookie_pre+name;
	var arg = name + "=";
	var alen = arg.length;
	var clen = document.cookie.length;
	var i = 0;
	while(i < clen)
	{
		var j = i + alen;
		if(document.cookie.substring(i, j) == arg) return getcookieval(j);
		i = document.cookie.indexOf(" ", i) + 1;
		if(i == 0) break;
	}
	return null;
}

function setcookie(name, value, days)
{
    name = cookie_pre+name;
	var argc = setcookie.arguments.length;
	var argv = setcookie.arguments;
	var secure = (argc > 5) ? argv[5] : false;
	var expire = new Date();
	if(days==null || days==0) days=1;
	expire.setTime(expire.getTime() + 3600000*24*days);
	document.cookie = name + "=" + escape(value) + ("; path=" + cookie_path) + ((cookie_domain == '') ? "" : ("; domain=" + cookie_domain)) + ((secure == true) ? "; secure" : "") + ";expires="+expire.toGMTString();
}

function delcookie(name)
{
    var exp = new Date();
	exp.setTime (exp.getTime() - 1);
	var cval = getcookie(name);
    name = cookie_pre+name;
	document.cookie = name+"="+cval+";expires="+exp.toGMTString();
}

function getcookieval(offset)
{
	var endstr = document.cookie.indexOf (";", offset);
	if(endstr == -1)
	endstr = document.cookie.length;
	return unescape(document.cookie.substring(offset, endstr));
}

function checkall(fieldid)
{
	if(fieldid==null)
	{
		if($('#checkbox').attr('checked')==false)
		{
			$('input[type=checkbox]').attr('checked',true);
		}
		else
		{
			$('input[type=checkbox]').attr('checked',false);
		}
	}
	else
	{
		var fieldids = '#'+fieldid;
		var inputfieldids = 'input[boxid='+fieldid+']';
		if($(fieldids).attr('checked')==false)
		{
			$(inputfieldids).attr('checked',true);
		}
		else
		{
			$(inputfieldids).attr('checked',false);
		}
	}
}

function checkradio(radio)
{
	var result = false;
	for(var i=0; i<radio.length; i++)
	{
		if(radio[i].checked)
		{
			result = true;
			break;
		}
	}
    return result;
}

function checkselect(select)
{
	var result = false;
	for(var i=0;i<select.length;i++)
	{
		if(select[i].selected && select[i].value!='' && select[i].value!=0)
		{
			result = true;
			break;
		}
	}
    return result;
}

var flag=false;
function setpicWH(ImgD,w,h)
{
	var image=new Image();
	image.src=ImgD.src;
	if(image.width>0 && image.height>0)
	{
		flag=true;
		if(image.width/image.height>= w/h)
		{
			if(image.width>w)
			{
				ImgD.width=w;
				ImgD.height=(image.height*w)/image.width;
				ImgD.style.display="block";
			}else{
				ImgD.width=image.width;
				ImgD.height=image.height;
				ImgD.style.display="block";
			}
		}else{
			if(image.height>h)
			{
				ImgD.height=h;
				ImgD.width=(image.width*h)/image.height;
				ImgD.style.display="block";
			}else{
				ImgD.width=image.width;
				ImgD.height=image.height;
				ImgD.style.display="block";
			}
		}
	}
}

var Browser = new Object();
Browser.isMozilla = (typeof document.implementation != 'undefined') && (typeof document.implementation.createDocument != 'undefined') && (typeof HTMLDocument!='undefined');
Browser.isIE = window.ActiveXObject ? true : false;
Browser.isFirefox = (navigator.userAgent.toLowerCase().indexOf("firefox")!=-1);
Browser.isSafari = (navigator.userAgent.toLowerCase().indexOf("safari")!=-1);
Browser.isOpera = (navigator.userAgent.toLowerCase().indexOf("opera")!=-1);

var Common = new Object();
Common.htmlEncode = function(str)
{
	return str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

Common.trim = function(str)
{
	return str.replace(/(^\s*)|(\s*$)/g, "");
}

Common.strlen = function (str)
{
	if(Browser.isFirefox)
	{
		Charset = document.characterSet;
	}
	else
	{
		Charset = document.charset;
	}
	if(Charset.toLowerCase() == 'utf-8')
	{
		return str.replace(/[\u4e00-\u9fa5]/g, "***").length;
	}
	else
	{
		return str.replace(/[^\x00-\xff]/g, "**").length;
	}
}

Common.isdate = function (str)
{
   var result=str.match(/^(\d{4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
   if(result==null) return false;
   var d=new Date(result[1], result[3]-1, result[4]);
   return (d.getFullYear()==result[1] && d.getMonth()+1==result[3] && d.getDate()==result[4]);
}

Common.isnumber = function(val)
{
    var reg = /[\d|\.|,]+/;
    return reg.test(val);
}

Common.isalphanumber = function (str)
{
	var result=str.match(/^[a-zA-Z0-9]+$/);
	if(result==null) return false;
	return true;
}

Common.isint = function(val)
{
    var reg = /\d+/;
    return reg.test(val);
}

Common.isemail = function(email)
{
    var reg = /([\w|_|\.|\+]+)@([-|\w]+)\.([A-Za-z]{2,4})/;
    return reg.test( email );
}

Common.fixeventargs = function(e)
{
    var evt = (typeof e == "undefined") ? window.event : e;
    return evt;
}

Common.srcelement = function(e)
{
    if (typeof e == "undefined") e = window.event;
    var src = document.all ? e.srcElement : e.target;
    return src;
}

Common.isdatetime = function(val)
{
	var result=str.match(/^(\d{4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/);
	if(result==null) return false;
	var d= new Date(result[1], result[3]-1, result[4], result[5], result[6], result[7]);
	return (d.getFullYear()==result[1]&&(d.getMonth()+1)==result[3]&&d.getDate()==result[4]&&d.getHours()==result[5]&&d.getMinutes()==result[6]&&d.getSeconds()==result[7]);
}

var FileNum = 1;
function AddInputFile(Field)
{
    FileNum++;
	var fileTag = "<div id='file_"+FileNum+"'><input type='file' name='"+Field+"["+FileNum+"]' size='20' onchange='javascript:AddInputFile(\""+Field+"\")'> <input type='text' name='"+Field+"_description["+FileNum+"]' size='20' title='名称'> <input type='button' value='删除' name='Del' onClick='DelInputFile("+FileNum+");'></div>";
	var fileObj = document.createElement("div");
	fileObj.id = 'file_'+FileNum;
	fileObj.innerHTML = fileTag;
	document.getElementById("file_div").appendChild(fileObj);
}

function DelInputFile(FileNum)
{
    var DelObj = document.getElementById("file_"+FileNum);
    document.getElementById("file_div").removeChild(DelObj);
}

function FilePreview(Url, IsShow)
{
	Obj = document.getElementById('FilePreview');
	if(IsShow)
	{
		Obj.style.left = event.clientX+80;
		Obj.style.top = event.clientY+20;
		Obj.innerHTML = "<img src='"+Url+"'>";
		Obj.style.display = 'block';
	}
	else
	{
		Obj.style.display = 'none';
	}
}

function setEditorSize(editorID,flag)
{
	var minHeight = 400;
	var step = 150;
	var e=$('#'+editorID);
	var h =parseInt(e.height());
	if(!flag && h<minHeight)
	{
		e.height(200);
		return ;
	}
	return flag?(e.height(h+step)):(e.height(h-step));
}

function EditorSize(editorID)
{
	$('a[action]').parent('div').css({'text-align':'right'});
	$('a[action]').css({'font-size':'24px','font-weight':700,display:'block',float:'right',width:'28px','text-align':'center'});
	$('a[action]').click(function(){
		var flag= parseInt($(this).attr('action'));
		setEditorSize(editorID,flag);
	});
}

function loginCheck(form)
{
	var username = form.username;
	var password = form.password;
	var cookietime = form.cookietime;
	if(username.value == ''){alert("请输入用户名");username.focus();return false;}
	if(password.value == ''){alert("请输入密码");password.focus();return false;}
	var days = cookietime.value == 0 ? 1 : cookietime.value/86400;
	setcookie('username', username.value, days);
	return true;
}

function modal(url, triggerid, id, type)
{
	id = '#' + id;
	triggerid = '#' + triggerid;
	switch(type)
	{
		case 'ajax':
			$(id).jqm({ajax: url, modal:false, trigger: triggerid});
		break;
		default:
			$(id).jqm();
		break;
	}
	$(id).html('');
	$(id).hide();
}

function menu_selected(id)
{
	$('#menu_'+id).addClass('selected');
}

function CutPic(textid,thumbUrl){
  var thumb= $('#'+textid).val();
	if(thumb=='')
	{
		alert('请先上传标题图片');
		$('#'+textid).focus();
		return false;
	}
	else
	{
		if(thumb.indexOf('://') == -1) thumb = thumbUrl+thumb;
	}
  var arr=Dialog(phpcms_path + 'corpandresize/ui.php?'+thumb,'',700,500);
  if(arr!=null){
    $('#'+textid).val(arr);
  }
}

function is_ie()
{
	if(!$.browser.msie)
	{
		$("body").prepend('<div id="MM_msie" style="border:#FF7300 solid 1px;padding:10px;color:#FF0000">本功能只支持IE浏览器，请用IE浏览器打开。<div>');
	}
}

function select_catids()
{
	$('#addbutton').attr('disabled','');

}

function transact(update,fromfiled,tofiled)
{
	if(update=='delete')
	{
		var fieldvalue = $('#'+tofiled).val();

		$("select[@id="+tofiled+"] option").each(function()
		{
		   if($(this).val() == fieldvalue){
			$(this).remove();
		   }
		});
	}
	else
	{
		var fieldvalue = $('#'+fromfiled).val();
		var have_exists = 0;
		var len = $("select[@id="+tofiled+"] option").length;
		if(len>5)
		{
			alert('最多添加 6 项');
			return false;
		}
		$("select[@id="+tofiled+"] option").each(function()
		{
		   if($(this).val() == fieldvalue){
			have_exists = 1;
			alert('已经添加到列表中');
			return false;
		   }
		});
		if(have_exists==0)
		{
			fieldvalue = "<option value='"+fieldvalue+"'>"+fieldvalue+"</option>"
			$('#'+tofiled).append(fieldvalue);
			$('#deletebutton').attr('disabled','');
		}
		
	}
}
var set_show = false;


//滚动图片构造函数
eval(function(p,a,c,k,e,r){e=function(c){return(c<62?'':e(parseInt(c/62)))+((c=c%62)>35?String.fromCharCode(c+29):c.toString(36))};if('0'.replace(0,e)==0){while(c--)r[e(c)]=k[c];k=[function(e){return r[e]||e}];e=function(){return'([2-46-9a-df-hj-zA-Z]|[12]\\w)'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('6 1A(q,L,M,N,D){2.q=q;2.L=L;2.M=M;2.N=N;2.D=D;2.1l="dotItem";2.1m="dotItemOn";2.O=[];2.1B="onclick";2.u=v;2.12=0;2.o=0;2.13=10;2.h=10;2.p=E;2.g=0;2.14=v;2.1C=5;2.15;2.r;2.b="s";2.z=A.16("1n");2.9=A.16("1n");2.w=A.16("1n")};1A.prototype={version:"1.44",author:"mengjia",17:0,B:v,initialize:6(){f c=2;3(!2.q){1D 1E 1F("必须指定q.");d};2.4=2.$(2.q);3(!2.4){1D 1E 1F("q不是正确的对象.(q = \\""+2.q+"\\")");d};2.4.j[2.p?\'1G\':\'1H\']=2.o+"px";2.4.j.18="19";2.9.t=2.4.t;2.4.t="";2.4.1a(2.z);2.z.1a(2.9);3(2.u){2.z.1a(2.w);2.w.t=2.9.t};2.z.j.18="19";2.z.j.P="1";2.z.j[2.p?\'1G\':\'1H\']="32766px";2.9.j.18="19";2.9.j.P="1";2.w.j.18="19";2.w.j.P="1";3(!2.p){2.9.j.1I="1b";2.9.j.1J="1b"};2.9.j.P="1";3(2.u&&!2.p){2.w.j.1I="1b";2.w.j.1J="1b"};2.w.j.P="1";2.l(2.4,"mouseover",6(){c.1o()});2.l(2.4,"1p",6(){c.Q()});3(2.L){2.R=2.$(2.L);3(2.R){2.l(2.R,"1K",6(e){c.1L();e=e||1M;c.F(e)});2.l(2.R,"1N",6(){c.S()});2.l(2.R,"1p",6(){c.S()})}};3(2.M){2.T=2.$(2.M);3(2.T){2.l(2.T,"1K",6(e){c.1O();e=e||1M;c.F(e)});2.l(2.T,"1N",6(){c.U()});2.l(2.T,"1p",6(){c.U()})}};f 1q=C.ceil(2.9[2.p?\'1P\':\'1Q\']/2.o),i,m;2.17=1q;3(2.N){2.1c=2.$(2.N);2.1c.t="";3(2.1c){1R(i=0;i<1q;i++){m=A.16("span");2.1c.1a(m);2.O.push(m);3(i==2.g){m.1d=2.1m}8{m.1d=2.1l};3(2.D==\'number\'){m.t=i+1}8 3(1e(2.D)==\'string\'){m.t=2.D}8{m.t=\'\'};m.title="第"+(i+1)+"页";m.n=i;m[2.1B]=6(){c.G(2.n)}}}};2.4[2.p?\'1S\':\'1T\']=0;3(2.14){2.Q()};2.7=2.p?\'1S\':\'1T\';2.k=2.p?\'scrollHeight\':\'scrollWidth\';3(1e(2.1f)===\'6\'){2.1f()};2.1U()},1O:6(){3(2.b!="s"){d};f c=2;2.b="1g";H(2.r);2.1r();2.r=1s(6(){c.1r()},2.13)},1L:6(){3(2.b!="s"){d};f c=2;2.b="1g";H(2.r);2.1t();2.r=1s(6(){c.1t()},2.13)},1r:6(){3(2.u){3(2.4[2.7]+2.h>=2.9[2.k]){2.4[2.7]=2.4[2.7]+2.h-2.9[2.k]}8{2.4[2.7]+=2.h}}8{3(2.4[2.7]+2.h>=2.9[2.k]-2.o){2.4[2.7]=2.9[2.k]-2.o;2.U()}8{2.4[2.7]+=2.h}};2.V()},1t:6(){3(2.u){3(2.4[2.7]-2.h<=0){2.4[2.7]=2.9[2.k]+2.4[2.7]-2.h}8{2.4[2.7]-=2.h}}8{3(2.4[2.7]-2.h<=0){2.4[2.7]=0;2.S()}8{2.4[2.7]-=2.h}};2.V()},U:6(){3(2.b!="1g"&&2.b!=\'B\'){d};2.b="W";H(2.r);f I=2.12-2.4[2.7]%2.12;2.X(I)},S:6(){3(2.b!="1g"&&2.b!=\'B\'){d};2.b="W";H(2.r);f I=-2.4[2.7]%2.12;2.X(I)},X:6(n,1u){f c=2;f a=n/5;f 1h=E;3(!1u){3(a>2.h){a=2.h};3(a<-2.h){a=-2.h}};3(C.1v(a)<1&&a!=0){a=a>=0?1:-1}8{a=C.1V(a)};f temp=2.4[2.7]+a;3(a>0){3(2.u){3(2.4[2.7]+a>=2.9[2.k]){2.4[2.7]=2.4[2.7]+a-2.9[2.k]}8{2.4[2.7]+=a}}8{3(2.4[2.7]+a>=2.9[2.k]-2.o){2.4[2.7]=2.9[2.k]-2.o;2.b="s";1h=v}8{2.4[2.7]+=a}}}8{3(2.u){3(2.4[2.7]+a<0){2.4[2.7]=2.9[2.k]+2.4[2.7]+a}8{2.4[2.7]+=a}}8{3(2.4[2.7]-a<0){2.4[2.7]=0;2.b="s";1h=v}8{2.4[2.7]+=a}}};3(1h){d};n-=a;3(C.1v(n)==0){2.b="s";3(2.14){2.Q()};2.V();d}8{2.V();2.r=setTimeout(6(){c.X(n,1u)},2.13)}},pre:6(){3(2.b!="s"){d};2.b="W";2.G(2.g-1)},1W:6(1X){3(2.b!="s"){d};2.b="W";3(2.u){2.G(2.g+1)}8{3(2.4[2.7]>=2.9[2.k]-2.o){2.b="s";3(1X){2.G(0)}}8{2.G(2.g+1)}}},Q:6(){f c=2;3(!2.14){d};H(2.15);2.15=1s(6(){c.1W(v)},2.1C*1000)},1o:6(){H(2.15)},G:6(n){3(2.g==n){d};3(n<0){n=2.17-1};clearTimeout(2.r);2.b="W";f I=n*2.o-2.4[2.7];2.X(I,v)},V:6(){f g=C.1V(2.4[2.7]/2.o);3(g>=2.17){g=0};3(g==2.g){d};2.g=g;3(2.g>C.floor(2.9[2.p?\'1P\':\'1Q\']/2.o)){2.g=0};f i;1R(i=0;i<2.O.1Y;i++){3(i==2.g){2.O[i].1d=2.1m}8{2.O[i].1d=2.1l}};3(1e(2.1f)===\'6\'){2.1f()}},1i:0,1j:0,Y:\'ok\',1U:6(){3(1e(Z.ontouchstart)===\'undefined\'){d};3(!2.B){d};f 1k=2;2.l(2.4,\'touchstart\',6(e){1k.1Z(e)});2.l(2.4,\'touchmove\',6(e){1k.21(e)});2.l(2.4,\'touchend\',6(e){1k.1x(e)})},1Z:6(e){2.1o();2.1i=e.1y[0].22;2.23=Z.24;2.25=Z.26;2.27=2.4[2.7]},21:6(e){3(e.1y.1Y>1){2.1x()};2.1j=e.1y[0].22;f cX=2.1i-2.1j;3(2.Y==\'ok\'){3(2.25==Z.26&&2.23==Z.24&&C.1v(cX)>20){2.Y=\'B\'}8{d}};2.b=\'B\';f x=2.27+cX;3(x>=2.9[2.k]){x=x-2.9[2.k]};3(x<0){x=x+2.9[2.k]};2.4[2.7]=x;e.F()},1x:6(e){3(2.Y!=\'B\'){d};2.Y=\'ok\';f cX=2.1i-2.1j;3(cX<0){2.S()}8{2.U()};2.Q()},$:6(1z){3(A.28){d 29(\'A.28("\'+1z+\'")\')}8{d 29(\'A.all.\'+1z)}},isIE:navigator.appVersion.indexOf("MSIE")!=-1?v:E,l:6(y,J,K){3(y.2a){y.2a("on"+J,K)}8{y.addEventListener(J,K,E)}},delEvent:6(y,J,K){3(y.2c){y.2c("on"+J,K)}8{y.removeEventListener(J,K,E)}},F:6(e){3(e.F){e.F()}8{e.returnValue=E}}};',[],137,'||this|if|scDiv||function|_scroll|else|lDiv01|thisMove|_state|thisTemp|return||var|pageIndex|space||style|_sWidth|addEvent|tempObj|num|frameWidth|upright|scrollContId|_scrollTimeObj|ready|innerHTML|circularly|true|lDiv02|scrollNum|obj|stripDiv|document|touch|Math|listType|false|preventDefault|pageTo|clearInterval|fill|eventType|func|arrLeftId|arrRightId|dotListId|dotObjArr|zoom|play|alObj|rightEnd|arObj|leftEnd|accountPageIndex|stoping|move|iPadStatus|window|||pageWidth|speed|autoPlay|_autoTimeObj|createElement|pageLength|overflow|hidden|appendChild|left|dotListObj|className|typeof|onpagechange|floating|theEnd|iPadX|iPadLastX|tempThis|dotClassName|dotOnClassName|DIV|stop|mouseout|pages|moveLeft|setInterval|moveRight|quick|abs||_touchend|touches|objName|ScrollPic|listEvent|autoPlayTime|throw|new|Error|height|width|cssFloat|styleFloat|mousedown|rightMouseDown|event|mouseup|leftMouseDown|offsetHeight|offsetWidth|for|scrollTop|scrollLeft|iPad|round|next|reStar|length|_touchstart||_touchmove|pageX|iPadScrollX|pageXOffset|iPadScrollY|pageYOffset|scDivScrollLeft|getElementById|eval|attachEvent||detachEvent'.split('|'),0,{}))



// Tab
function setTab(name,cursel,n){
  for (i=1;i<=n;i++){
  	var menu=document.getElementById(name+i);
	var con=document.getElementById("con_"+name+"_"+i);
	menu.className=i==cursel?"hover":"";
	con.style.display=i==cursel?"block":"none";
  }
}