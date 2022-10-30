function open_cat(obj) {
	div_cat_tree.style.display = "";
	v_top = ((parseInt(div_cat_tree.offsetHeight) - parseInt(div_cat_inner.offsetHeight)) / 2)
	v_top = v_top + parseInt(getScrollTop())
	
	//div_cat_inner.style.top = v_top	 + "px"
	div_cat_inner.style.top = "125px"
	v_left = ((parseInt(div_cat_tree.offsetWidth) - parseInt(div_cat_inner.offsetWidth)) / 2)
	div_cat_inner.style.left = v_left + "px"        
	
	show_large(obj);

}

function show_large(obj_i){
	//var thumb = obj.src;
	//var large = thumb.replace("/thumb", "");
	var div_inner = document.getElementById("div_cat_inner");
	var large = document.getElementById("pop_large_" + obj_i).src
	div_large = document.getElementById("img_pop_large")
	div_large.src = large;
	//alert(div_cat_inner.offsetHeight);
	if(parseInt(div_large.offsetWidth)>parseInt(div_cat_inner.offsetWidth)){
		div_large.style.width = parseInt(div_cat_inner.offsetWidth)-50 + "px"
	}
	if(parseInt(div_large.offsetHeight)>parseInt(div_cat_inner.offsetHeight)){
		div_large.style.height = parseInt(div_cat_inner.offsetHeight)-20 + "px"
		div_inner.style.overflow = "scroll";
	}
	else{
		div_inner.style.overflow = "none";
	}
}

function js_close_tree() {
	div_cat_tree.style.display = "none";
}

function js_sel(obj, v_id, v_path) {
	p_obj = obj.parentNode
	span_cat_path.innerHTML = v_path;
	document.getElementById("hdn_cat_id").value = v_id
	js_close_tree()
}

   function isNumberKey(evt)
   {
	  var charCode = (evt.which) ? evt.which : evt.keyCode;
	  if (charCode != 46 && charCode > 31 
		&& (charCode < 48 || charCode > 57))
		 return false;

	  return true;
   }
   //-->	
   
function js_add_to_cart(v_sup_id,v_sup_name,v_price,item_wish){
	document.frmdetails.sup_id.value = v_sup_id;
	document.frmdetails.sup_name.value = v_sup_name;
	document.frmdetails.prod_price.value = v_price;
	document.frmdetails.item_wish.value = item_wish;
	if(document.getElementById("memb_pin").value=="" || document.getElementById("memb_pin").value.length!=6){
		alert("Please enter valid Shipping pincode");
		return false;
	}
	pincode=document.getElementById("memb_pin").value;
	if(check_delivery() == false){ return false; }
	document.frmdetails.submit();
}
function js_add_to_cart_advrt(v_sup_id,v_sup_name,v_price,item_wish){
	document.frmdetails.sup_id.value = v_sup_id;
	document.frmdetails.sup_name.value = v_sup_name;
	document.frmdetails.prod_price.value = v_price;
	document.frmdetails.item_wish.value = item_wish;
	document.frmdetails.submit();
}
function swap_thumb(obj){
	document.getElementById("img_large").src = obj.src;
}

function validate(){
	var cn = 1
	c = parseInt(document.frmdetails.addon_count.value)
	if(c==1){
		cn = 0
		if(document.frmdetails.addons.value!=''){
			cn=1
		}
	}
	else if(c>1) {
		cn=0
		for(i=0;i<c;i++){
			if(document.frmdetails.addons[i].value!=''){
				cn=cn+1
			}
		}
	}
	
	if(cn < c){
		alert("Please select an option from the dropdown(s)");
		return false;
	}else{
		return true;
	}
	
}

// Set the horizontal and vertical position for the popup

PositionX = 100;
PositionY = 100;

// Set these value approximately 20 pixels greater than the
// size of the largest image to be used (needed for Netscape)

defaultWidth  = 5000;
defaultHeight = 5000;

// Set autoclose true to have the window close automatically
// Set autoclose false to allow multiple popup windows

var AutoClose = true;

// Do not edit below this line...
// ================================
if(parseInt(navigator.appVersion.charAt(0))>=4)
{
	var isNN=(navigator.appName=="Netscape")?1:0;
	var isIE=(navigator.appName.indexOf("Microsoft")!=-1)?1:0;}
	var optNN='scrollbars=no,width='+defaultWidth+',height='+defaultHeight+',left='+PositionX+',top='+PositionY;
	var optIE='scrollbars=no,width=150,height=100,left='+PositionX+',top='+PositionY;
	function popImage(obj,imageTitle)
	{
		imageURL = obj.src;
		if(isNN)
		{
			imgWin=window.open('about:blank','',optNN);
		}
		if(isIE)
		{
			imgWin=window.open('about:blank','',optIE);
		}
		with (imgWin.document)
		{
			writeln('<html><head><title>Loading...</title><style>body{margin:5px;}</style></head>');
			writeln('<sc'+'ript>');
			writeln('var isNN,isIE;');
			writeln('if(parseInt(navigator.appVersion.charAt(0))>=4){');
			writeln('isNN=(navigator.appName=="Netscape")?1:0;');
			writeln('isIE=(navigator.appName.indexOf("Microsoft")!=-1)?1:0;}');
			writeln('function reSizeToImage(){');
			writeln('if(isIE){');
			writeln('window.resizeTo(100,100);');
			writeln('width=100-(document.body.clientWidth-(document.images[0].width + 260));');
			writeln('height=100-(document.body.clientHeight-(document.images[0].height + 260));');
			writeln('window.resizeTo(width,height);}');
			writeln('if(isNN){');
			writeln('window.innerWidth=(document.images["George"].width)+100;');
			writeln('window.innerHeight=document.images["George"].height;}}');
			writeln('function doTitle(){document.title="'+imageTitle+'";}');
			writeln('</sc'+'ript>');
			if(!AutoClose) writeln('</head><body bgcolor=000000 scroll="no" onload="reSizeToImage();doTitle();self.focus()">')
			else writeln('</head><body bgcolor=FFFFFF scroll="no" onload="reSizeToImage();doTitle();self.focus()" onblur="self.close()">');
			writeln('<div align="center"><img name="George" src="'+imageURL+'" border=0></div><div align="right"><a href="javascript: window.close();" style="STYLE="font-size:10; color:#575757; font-family: Verdana; text-decoration: underline;">close window</a></div></body></html>');
		close();
	}
}

function FormatNumber(Expression, NumDigitsAfterDecimal)
{
	var iNumDecimals = NumDigitsAfterDecimal;
	var dbInVal = Expression;
	var bNegative = false;
	var iInVal = 0;
	var strInVal
	var strWhole = "", strDec = "";
	var strTemp = "", strOut = "";
	var iLen = 0;

	if(dbInVal < 0)
	{
		bNegative = true;
		dbInVal *= -1;
	}

	dbInVal = dbInVal * Math.pow(10, iNumDecimals)
	iInVal = parseInt(dbInVal);
	if((dbInVal - iInVal) >= .5)
	{
		iInVal++;
	}
	strInVal = iInVal + "";
	strWhole = strInVal.substring(0, (strInVal.length - iNumDecimals));
	strDec = strInVal.substring((strInVal.length - iNumDecimals), strInVal.length);
	while (strDec.length < iNumDecimals)
	{
		strDec = "0" + strDec;
	}
	iLen = strWhole.length;
	if(iLen >= 3)
	{
		while (iLen > 0)
		{
			strTemp = strWhole.substring(iLen - 3, iLen);
			if(strTemp.length == 3)
			{
				strOut = "," + strTemp + strOut;
				iLen -= 3;
			}
			else
			{
				strOut = strTemp + strOut;
				iLen = 0;
			}
		}
		if(strOut.substring(0, 1) == ",")
		{
			strWhole = strOut.substring(1, strOut.length);
		}
		else
		{
			strWhole = strOut;
		}
	}
	if(bNegative)
	{
		return "-" + strWhole + "." + strDec;
	}
	else
	{
		return strWhole + "." + strDec;
	}
}

function calc_price(x){
	var opt_price = 0
	c = parseInt(document.frmdetails.addon_count.value)
	if(c==1){
		opt_price = parseFloat(x.value)
	}
	else{
		for(i=0;i<c;i++){
			//alert(document.frmdetails.addons[i-1].value)
			opt_price_next = parseFloat(document.frmdetails.addons[i].value)
			if(isNaN(opt_price_next))	 opt_price_next = 0
			opt_price = parseFloat(opt_price) + parseFloat(opt_price_next)
		
		}
	}
	
	if(isNaN(opt_price))		 opt_price = 0

	var new_price = FormatNumber(parseFloat(document.frmdetails.prod_price.value) + opt_price,2)
	document.getElementById("obj_price").innerHTML = "&pound;" + new_price

	document.frmdetails.cart_price.value = new_price
	new_price = parseFloat(new_price)

	var sv_vat_percent = parseFloat(document.frmdetails.vat_percent.value)
	try{
		document.getElementById("obj_price_vat").innerHTML = "&pound;" + FormatNumber((new_price+(new_price * sv_vat_percent/100)),2)
	}
	catch(e){}
}
