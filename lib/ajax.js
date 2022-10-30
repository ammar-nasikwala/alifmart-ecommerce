var q_sep = "¿"

function call_ajax(ajax_file, params) {
    
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.open("POST", ajax_file, false);

    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	params = Replace(params," ","[space]");
	if(params.indexOf(q_sep)>0){
		params = Replace(params,"&","[ampersand]");
		params = Replace(params,q_sep,"&");
	}
	
	xmlhttp.send(params);

    return parseAjaxResponse(xmlhttp.responseText);
}


function parseAjaxResponse(txt) {
    var v_txt = txt.split("<response>")
    if (v_txt.length > 1) {
        var v_resp = v_txt[1].split("</response>")
        var v_resp_txt=v_resp[0]
		v_resp_txt = Replace(v_resp_txt,"[space]"," ")
		v_resp_txt = Replace(v_resp_txt,"[ampersand]","&")
		return v_resp_txt
    } else {
        alert(txt)
        return txt
    }

}

function func_trim(stringToTrim) {
    return stringToTrim.replace(/^\s+|\s+$/g, "");
	
}

function Replace(Expression, Find, Replace)
{
	var temp = Expression;
	var a = 0;

	for (var i = 0; i < Expression.length; i++) 
	{
		a = temp.indexOf(Find);
		if (a == -1)
			break
		else
			temp = temp.substring(0, a) + Replace + temp.substring((a + Find.length));
	}

	return temp;
}