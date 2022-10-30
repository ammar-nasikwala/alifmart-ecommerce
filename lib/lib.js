function getScrollTop()
{
    var scrollTop;
    if(typeof(window.pageYOffset) == 'number')
    {
        // DOM compliant, IE9+
        scrollTop = window.pageYOffset;
    }
    else
    {
        // IE6-8 workaround
        if(document.body && document.body.scrollTop)
        {
            // IE quirks mode
            scrollTop = document.body.scrollTop;
        }
        else if(document.documentElement && document.documentElement.scrollTop)
        {
            // IE6+ standards compliant mode
            scrollTop = document.documentElement.scrollTop;
        }
    }
    return scrollTop;
}

function setScrollTop(v_top)
{
	//alert(v_top);
    //var scrollTop;
    if(typeof(window.pageYOffset) == 'number')
    {
        // DOM compliant, IE9+
        //scrollTop = window.pageYOffset;
		window.pageYOffset = v_top + "px";
    }
    else
    {
        // IE6-8 workaround
        if(document.body && document.body.scrollTop)
        {
            // IE quirks mode
            //scrollTop = document.body.scrollTop;
			document.body.scrollTop = v_top + "px";
        }
        else if(document.documentElement && document.documentElement.scrollTop)
        {
            // IE6+ standards compliant mode
            //scrollTop = document.documentElement.scrollTop;
			document.documentElement.scrollTop = v_top + "px";
        }
    }
    //return scrollTop;
}

function add_option(obj,opt_value,opt_txt){
	var opt = document.createElement("option");
	
	obj.options.add(opt);
    opt.text = opt_txt;
    opt.value = opt_value;	
}

function remove_option(obj){
	if(obj.selectedIndex>-1){
		obj.remove(obj.selectedIndex)
	}
}
