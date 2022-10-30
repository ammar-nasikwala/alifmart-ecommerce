<?php
require_once("inc_admin_header.php");
?>

<script>
	function Submit_form(){	
		if(chkForm(document.frm)==false){
			return false;
		}else{
			document.frm.act.value="1";
			document.frm.submit();
		}
	}
	
	function js_approve(){
		if(chkForm(document.frm)==false){
			return false;
		}else{
			document.frm.act.value="A";
			document.frm.submit();
		}
	}
</script>

<?php
$_SESSION["user"] = 1;
$_SESSION["sup_id"] = func_read_qs("id");
$admin_login = "1";
if(get_rst("select sup_company from sup_mast where sup_id=".$_SESSION["sup_id"]." and sup_seller_token IS NOT NULL")){
	$not_show_btn = "1";
}
if(func_read_qs("act")=="B"){
	$sup_city = func_read_qs("sup_city");
	$sup_contact_name = func_read_qs("sup_contact_name");
	$sup_email = func_read_qs("sup_email");
	$sup_contact_no = func_read_qs("sup_contact_no");
	get_rst("select city_code from city_mast where city_name='$sup_city'", $row2);
	$city_code = $row2["city_code"];
	if($city_code == ""){
		$city_code = strtoupper(substr($sup_city,0,3));
	}
	get_rst("select count(0)+1 as next_code from sup_mast where sup_seller_token LIKE '$city_code%' and sup_seller_token is NOT null",$row);
	
	$seller_code = $city_code . str_pad($row["next_code"], 4-strlen($row["next_code"]), "0", STR_PAD_LEFT);

	execute_qry("update sup_mast set sup_seller_token = '$seller_code',sup_admin_approval=1 where sup_id=".$_SESSION["sup_id"]);

	$mail_body="Hi ".$sup_contact_name.",<br>";
	$mail_body.="<p>Your seller code has been generated. Please use ".$seller_code. " as your seller code whenever you login to Company-Name seller zone.</p>";
	$mail_body.="<br><br>Regards,<br>Team Company-Name";
	if(send_mail($sup_email,"Company-Name - Seller Code",$mail_body) == false){
		js_alert("There is some problem sending email to your email address.");
	}
	
	send_mail("sell@Company-Name.com","Company-Name - Seller Code",$mail_body);
	$sms_msg = "Hi ".$sup_contact_name.", Your seller code has been generated. Please use ".$seller_code. " as your seller code whenever you login to Company-Name seller zone";			
	$output=send_sms($sup_contact_no,$sms_msg);
	
	if($output == "202"){
		js_alert("There is some problem sending message to you mobile number.");
	}	
	js_alert("Seller Code generated successfully.");
}

require_once("../seller/inc_seller_profile.php");

$_SESSION["sup_id"] = "";

require_once("inc_admin_footer.php");
?>
