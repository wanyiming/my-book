var hiscookiename = 'jieqiHistoryBooks'; //cookie名字
var hisbookmax = 100;  //最多保留几条阅读记录
var hiscookievalue = Storage.get(hiscookiename); //取cookie

//把cookie解析成阅读记录数组，需要加载 /scripts/json2.js

var bookary = [];
try{
    bookary = JSON.parse(hiscookievalue);
    if(!bookary) bookary = [];
}catch(e){
}

var bookindex = -1; //当前的书是不是已经存在
for(var i = 0; i < bookary.length; i++){
    if(bookary[i].articleid == articleid){
        bookindex = i;
        break;
    }
}
if(bookindex < 0){
    //新的书加入阅读记录
    //历史记录达到最大值，删除一条
    if(bookary.length >= hisbookmax){
        bookary.shift();
    }
    bookary.push({articleid:articleid, articlename:articlename, chapterid:chapterid, chaptername:chaptername});
    hiscookievalue = JSON.stringify(bookary);
    Storage.set(hiscookiename, hiscookievalue);
}else if(chapterid > 0){
    //书已经存在，判断章节是否需要更新
    bookary[bookindex].chapterid = chapterid;
    bookary[bookindex].chaptername = chaptername;
    hiscookievalue = JSON.stringify(bookary);
    Storage.set(hiscookiename, hiscookievalue);
}