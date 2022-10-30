function clearForm()
	{		
		document.frm.sup_email.value="";
		document.frm.sup_pwd.value="";		
		document.frm.sup_cpwd.value="";	
		document.frm.sup_contact_name.value="";	
		document.frm.sup_company.value="";	
		document.frm.sup_contact_no.value="";
		document.frm.sup_contact_per_name.value="";
		$("select").val("0");
		document.getElementById("msg_email").innerHTML="";
		document.getElementById("msg_phone").innerHTML="";
		document.getElementById("msg_pwd").innerHTML="";
	}
	
		function DoCal(objDOI,DOIVal){	
			var sRtn;
			var err=0
			sRtn = showModalDialog("calendar.htm",DOIVal,"center=yes;dialogWidth=200pt;dialogHeight=200pt;status=no");
				
			if(String(sRtn)!="undefined"){
					objDOI.value=sRtn;	
			}
		}
	
function validate_key(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}	
function validate(){
	v_valid = check_email(document.frm.sup_email)
	p_valid = check_phno(document.frm.sup_contact_no)
	
	if(chkForm(document.frm)==false){
		return false;
	}else if(v_valid != "Available"){
		alert(v_valid)
		return false;	
	}else if(p_valid != "Available"){
		alert(p_valid)
		return false;	
	}else{
		if(document.getElementById("captcha_code").value=="") {
			alert('Please enter the CAPTCHA code');
			frm.captcha.focus();
			return false;
		}
		display_gif();
		docment.frm.submit();
	//sms_msg = call_ajax("ajax.php","process=get_sms_msg&memb_email=" + document.frm.sup_email.value + "&memb_fname=" + document.frm.sup_contact_name.value);
	return true;
	}
}
function check_email(obj){
	v_valid="";
	if(obj.value!=""){
		var obj_lbl = document.getElementById("msg_email")
		v_valid = call_ajax("ajax.php","process=check_seller_email&sup_email=" + obj.value)
	
		obj_lbl.innerHTML = v_valid 
		if(obj_lbl.innerHTML=="Available"){
		obj_lbl.style.color="#1E6F00"
		}else{
			obj_lbl.style.color="#FF5588"
		}
	}
		return v_valid
}
function check_phno(obj){
	p_valid="";
	if(obj.value!=""){
		var obj_lbl = document.getElementById("msg_phone")
		p_valid = call_ajax("ajax.php","process=check_sup_tel&sup_contact_no=" + obj.value)
		obj_lbl.innerHTML =p_valid
		if(obj_lbl.innerHTML=="Available"){
			obj_lbl.style.color="#1E6F00"
		}else{
			obj_lbl.style.color="#FF5588"
		}
	}
	return p_valid
}
function check_pwd(obj){
	var obj_lbl = document.getElementById("msg_pwd")
	obj_lbl.innerHTML = call_ajax("ajax.php","process=check_pwd&pwd=" + obj.value)
	if(obj_lbl.innerHTML=="Ok"){
		obj_lbl.style.color="#1E6F00"
	}else{
		obj_lbl.style.color="#FF5588"
	}	
}
var toggleElement="";
var toggleImage="";
function toggleMe(a){
	var e=document.getElementById(a);
	var i = document.getElementById(a + '_image');
	if(toggleElement!="" && toggleElement!=e){
		toggleElement.style.display="none"
		toggleImage.src = 'images/dhtmlgoodies_plus.gif';
	}
	if(!e)return true;
	if(e.style.display=="none"){
	e.style.display="block"
	i.src = 'images/dhtmlgoodies_minus.gif';
	toggleElement=e;
	toggleImage=i;
	} else {
	e.style.display="none"
	i.src = 'images/dhtmlgoodies_plus.gif';
	toggleElement="";
	toggleImage="";
	}
	return false;
}
var showmeElement="";
var showmeLink="";
function showHideMe(a){
	var e=document.getElementById(a);
	var i = document.getElementById(a + '_link');
	
	if(toggleElement!=""){
			toggleElement.style.display="none"
			toggleImage.src = 'images/dhtmlgoodies_plus.gif';
			toggleElement=""
			toggleImage=""
	}
	
	if(showmeElement!="" && showmeElement!=e){
		showmeElement.style.display="none"
		showmeLink.innerHTML = 'Show more..'
	}
	
	if(!e)return true;
	if(e.style.display=="none"){
	e.style.display="block"
	i.innerHTML = 'Show less..'
	showmeElement=e;
	showmeLink=i;
	}
	else{
	e.style.display="none"
	i.innerHTML = 'Show more..'
	
	showmeElement="";
	showmeLink="";
	}
	return true;
}
function refreshCaptcha(){
	document.getElementById('captchaimg').src = '/captcha.php?' + Math.random();
	document.getElementById('captcha_code').value = '';
}
	