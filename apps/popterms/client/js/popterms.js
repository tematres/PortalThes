
var PopTerms = function ()
{
	var width = 450;
	var height = 600;
	var datas = [];

	function size (w, h)
	{
		width = w;
		height = h;
	}

	function initialize ()
	{
		var btns = document.querySelectorAll("[data-popterms-server]");
		var url;
		for (var i=0; i<btns.length; i++)
		{
			datas[i] = btns[i].dataset;
			url = datas[i].poptermsServer;
			url+= "?v=" + datas[i].poptermsVocabulary;
			url+= "&tx=" + i + "&lc=1";
			btns[i].addEventListener("click", openTerms.bind(this, [url])); 
		}
		
	    window.addEventListener("message", function (event)
		{
	        /*
	        // Check origin policy
	        if (event.origin !== '*' && event.origin !== 'null' && event.origin !== "Server URL")
	            return console.warn('Post rechazado: ' + event.origin + ' != Server URL');
	        */
	        var data = event.data;
			writeTerm(data.term, data.index);
	    }, false);
	}
/**
	function popupWindow (url, title, w, h)
	{
	  var left = (screen.width - w) / 2;
	  var top = (screen.height - h) / 2;
	  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	} 
**/
	function popupCenter (url, title, w, h)
	{
		var left = window.screenLeft ? window.screenLeft : window.screenX;
		var top = window.screenTop ? window.screenTop : window.screenY;
	    var x = (window.innerWidth - w) / 2 + left;
	    var y = (window.innerHeight - h) / 2 + top;
		var f = "directories=no,menubar=no,status=no,toolbar=no,location=no,scrollbars=yes,";
		f+= "height=" + h + ",width=" + w + ",left=" + x + ",top=" + y;
	    return window.open(url, title, f);
	}

	function openTerms (url)
	{
		var popup = popupCenter(url, "Vocabulary", width, height);
		popup.focus();
		return false;
	}
	
	function writeTerm (term, index)
	{
		var data = datas[index];
		var elem = document.querySelector(data.poptermsTarget);
		if (!elem) return;
		if (data.poptermsMultiple == "true")
		{
			var separator = data.poptermsSeparator || PopTerms.separator;  
			var str = elem.value || elem.innerHTML;
			if (str.length == 0) separator = "";
			if (elem.value != undefined) elem.value += separator + term;
			else elem.innerHTML += separator + term;
		}
		else
		{
			if (elem.value != undefined) elem.value = term;
			else elem.innerHTML = term;
		}
	}

	document.addEventListener("DOMContentLoaded", function (event)
	{
	    PopTerms.initialize();
	});
  
	var obj = {}
	obj.initialize = initialize;
	obj.size = size;
	obj.separator = ";";
	
	return obj;
}();

