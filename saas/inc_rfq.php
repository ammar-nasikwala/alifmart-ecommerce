<?php //saaas
ob_start();
require("inc_admin_header.php");

require("request_for_quotation.php");

require_once("inc_admin_footer.php");
?>
<script type="text/javascript" src="<?=$url_root?>/lib/ajax.js"></script>
<script>

$('#req_quotation').on('click','#del', function(){
	$rowno=$("#req_quotation tr").length;
	if($rowno == 2){
		alert("Can not delete a single row");
		return false;
	}else{
		$(this).parent().parent().remove();
		$("input[name='item_no[]']").each(function(ind) {
			$(this).val(ind + 1);
		});
		$('#req_quotation tr').each(function() {
			newId =  $(this).index() + 1;
			newId = newId-1;
			$(this).attr('rowCount_'+newId,newId);      
		});
	}
});		
$('#req_quotation').on('click', '#add_row', function(){
	var id = document.getElementById("req_id").value;
	if(id == ""){
		$rowno=$("#req_quotation tr").length;
		var appendTxt = "<tr id='rowCount_"+$rowno+"'><td><input type='text' name='item_no[]' style='width: 15px;' value='"+$rowno+"' readonly></td><td><textarea type='text' style='width:150px; height: 25px' name='item_desc[]'/></td><td><input type='text' name='brand_name[]'/></td><td><input type='text' style='width:50px' name='item_qty[]'/></td><td><input type='text' style='width:90px' name='item_price[]' onkeypress='validate_key(event)'/></td><td><input name='uploadfile[]' type='file' style='width: 170px;'></td><td><input type='button' class='check_delivery btn btn-warning' id='del' value='--'></td></tr>";
		$("#req_quotation tr:last").after(appendTxt);	
		$rowno=$rowno+1;
	}else{
		$rowno=$("#req_quotation tr").length;
		var appendTxt = "<tr id='rowCount_"+$rowno+"'><td><input type='text' name='item_no[]' style='width: 15px;' value='"+$rowno+"' readonly></td><td><textarea type='text' style='width:150px; height: 25px' name='item_desc[]'/></td><td><input type='text' name='brand_name[]'/></td><td><input type='text' style='width:50px' name='item_qty[]'/></td><td><input type='text' style='width:90px' name='item_price[]' onkeypress='validate_key(event)'/></td><td><input name='uploadfile[]' type='file' style='width: 170px;'></td><td><input type='text' name='rfq_status[]' id='rfq_status' style='width: 90px' value='New' readonly></td><td><input type='button' class='check_delivery btn btn-warning' id='del' value='--'></td></tr>";
		$("#req_quotation tr:last").after(appendTxt);	
		$rowno=$rowno+1;
	}
});
function validate(){
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
	display_gif();
	document.frm_saas_rfq.submit();
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