<?php
ob_start();
require("inc_admin_header.php");

require("request_for_quotation.php");

require_once("inc_admin_footer.php");
?>
<script type="text/javascript" src="<?=$url_root?>/lib/ajax.js"></script>
<script>

$('#req_quotation').on('click','#del', function(){
	$(this).parent().parent().remove();
    $("input[name='item_no[]']").each(function(ind) {
    $(this).val(ind + 1);
	$("tr").attr("id", "rowCount_"+(ind+1));
    });
});
				
$('#req_quotation').on('click', '#add_row', function(){
	$rowno=$("#req_quotation tr").length;
	var appendTxt = "<tr id='rowCount_"+$rowno+"'><td><input type='text' name='item_no[]' style='width: 15px;' readonly></td><td><textarea type='text' style='width:150px; height: 25px' name='item_desc[]'/></td><td><input type='text' name='brand_name[]'/></td><td><input type='text' style='width:50px' name='item_qty[]'/></td><td><input type='text' style='width:90px' name='item_price[]' onkeypress='validate_key(event)'/></td><td><input name='uploadfile[]' type='file' style='width: 170px;'></td><td><select id='rfq_status' name='rfq_status[]'><option value='New'>New</option><option value='Assigned'>Assigned</option><option value='Processing'>Processing</option><option value='Closed'>Closed</option><select></td><td><textarea name='vendor[]' id='vendor' style='width: 120px; height: 25px; '></textarea></td><td><input type='button' class='check_delivery btn btn-warning' id='del' value='--'></td></tr>";
	$rowno=$rowno+1;
	$("#req_quotation tr:last").after(appendTxt);	
    $("input[name='item_no[]']").each(function(ind) {
    $(this).val(ind + 1);
	});
});
function validate(){
	var email = document.getElementById('customer_email');
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if(email.value !=""){
				if (!filter.test(email.value)) {
					alert("Please enter valid email.")
					document.getElementById("customer_email").style.borderColor = "red";
					email.focus;
					return false;
				}
			}
			if(document.getElementById("rfq_description").value==""){
				alert("Please enter short description of the enquiry");
				return false;
			}
			fCode = document.getElementsByName("item_desc[]");

			for ( var i = 0; i < fCode.length; i++ ){
				if ( fCode[i].value == "" ) {
					alert("Please enter product details.");
					fCode[i].focus();
					return false;
				}
			}
			qty = document.getElementsByName("item_qty[]");
			for ( var j = 0; j < qty.length; j++ ){
				if ( qty[j].value == "" || qty[j].value == 0) {
					alert("Please enter quantity.");
					qty[j].focus();
					return false;
				}
			}
				document.frm_req_quotation.submit();
				return true;
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


</script>