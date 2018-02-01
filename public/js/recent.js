function setStorage() 
{
    var sessionData = localStorage.getItem('itemInfos');
    
    if (sessionData) {
        var args = sessionData.split(',');
        var url = location.href;
        url = encodeURIComponent(url);
        var array = [];
        
        for (var i = 0; i < args.length; i++) {
            if (args[i].match(url)) {
                continue;
            } else {
                array.push(args[i]);
            }
        }
    } else {
        var array = [];
    }
    
    var itemUrl = location.href;
	var itemImg = encodeURIComponent('<li><div class="image_file"><a href="' + itemUrl + '">')
                + encodeURIComponent($('.swiper-slide').html()) 
                + encodeURIComponent('</a><div>');
    var itemName = encodeURIComponent('<div class="product_name"><a href="' + itemUrl + '">') 
                 + encodeURIComponent($('#product_detail_name').html()) 
                 + encodeURIComponent('</a></div></li>');
    array.unshift(itemImg + itemName);
    array = array.slice(0, 4);
    
    localStorage.setItem('itemInfos', array);
    
}

function getStorage() 
{
    var sessionData = localStorage.getItem('itemInfos');

    if (sessionData) {
		sessionData = decodeURIComponent(sessionData);
		sessionData = sessionData.replace(/,/gm,'');
		sessionData = sessionData.replace(/---300/gm, '');
		sessionData = sessionData.replace(/width=".*?"/gm, '');
		sessionData = sessionData.replace(/height=".*?"/gm, '');

		$('#recent').html('<hr><h4 align="center">最近チェックした商品<ul id="item_content">' 
        + sessionData +'</ul></div>');
	}
}