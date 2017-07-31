var hiscookiename = 'jieqiHistoryBooks'; //cookie名字
var hiscookievalue = Storage.get(hiscookiename); //取cookie

//把cookie解析成阅读记录数组，需要加载 /scripts/json2.js
var bookary = [];
try{
	bookary = JSON.parse(hiscookievalue);
	if(!bookary) bookary = [];
}catch(e){
}

//如果有记录最近阅读章节就跳转到对应章节，否则显示第一章
function read_chapter(aid){
	var cid = 0;
	if(bookary.length > 0){
		for(var i = bookary.length - 1; i >= 0; i--){
			if(bookary[i].articleid == aid){
				if(bookary[i].chapterid > 0) cid = bookary[i].chapterid;
				break;
			}
		}
	}
	if(cid > 0){
		//window.location.href = "/modules/article/reader.php?aid="+aid+"&cid="+cid;
		window.location.href = "/book/"+aid+"/"+cid+"/";
	}else{
		window.location.href = "/modules/article/firstchapter.php?aid="+aid;
	}
}

//针对书架的继续阅读
function read_bookcase(aid, cid, bid){
	if(bookary.length > 0){
		for(var i = bookary.length - 1; i >= 0; i--){
			if(bookary[i].articleid == aid){
				if(bookary[i].chapterid > 0) cid = bookary[i].chapterid;
				break;
			}
		}
	}
	if(cid > 0){
		window.location.href = "/modules/article/readbookcase.php?bid="+bid+"&aid="+aid+"&cid="+cid;
	}else{
		window.location.href = "/modules/article/firstchapter.php?aid="+aid;
	}
}