var hiscookiename = 'jieqiHistoryBooks'; //cookie����
var hiscookievalue = Storage.get(hiscookiename); //ȡcookie

//��cookie�������Ķ���¼���飬��Ҫ���� /scripts/json2.js
var bookary = [];
try{
	bookary = JSON.parse(hiscookievalue);
	if(!bookary) bookary = [];
}catch(e){
}

//����м�¼����Ķ��½ھ���ת����Ӧ�½ڣ�������ʾ��һ��
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

//�����ܵļ����Ķ�
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