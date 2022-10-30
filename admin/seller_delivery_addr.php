<?
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

<?

$_SESSION["sup_id"] = func_read_qs("id");
$admin_login = "1";

require_once("../seller/inc_delivery_addr_upd_form.php");
$_SESSION["sup_id"] = "";

require_once("inc_admin_footer.php");

?>
