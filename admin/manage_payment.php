<?php
require_once("inc_admin_header.php");

$_SESSION["page_id"] = 0;
$_SESSION["page_id"] = func_read_qs("page");

if(($_SESSION["txt_key"]."" == "" || $_SESSION["txt_key"] <> func_read_qs("txt_key")) && func_read_qs("txt_key") <> ""){
 $_SESSION["txt_key"] = func_read_qs("txt_key"); 
}
 
if(func_read_qs("page") == "" && func_read_qs("txt_key") == ""){$_SESSION["txt_key"] = "";}
if(func_read_qs("page") <> ""){
	$txt_key = trim($_SESSION["txt_key"]);
}else{
	$txt_key = trim(func_read_qs("txt_key"));
}
$page = func_read_qs("page");
$sql="select invoice_id";
$sql = $sql.",ord_no as 'Order ID'"; 
$sql = $sql.",(select sup_company from sup_mast S where S.sup_id=I.sup_id) as 'Seller'";
$sql = $sql.",pay_total as 'Pay Amount'"; 
$sql = $sql.",pay_status as 'Payment Status'";
$sql = $sql."from invoice_mast I";
$sql_where = "";
if($txt_key<>""){
	$sql_where = $sql_where." where ord_no like '%$txt_key%'";
	$sql_where = $sql_where." or sup_id in(select sup_id from sup_mast where sup_company like '%$txt_key%')";
}

$sql = $sql.$sql_where." order by invoice_id desc";
?>

<table border="0" width="100%">
	<tr>
		<td colspan="10"><h2>Manage Seller Payments</h2></td>
	</tr>
	
	<form name="frm_list" method="post" action="">
	<input type="hidden" name="act" value="1">
	<tr>

		<td align="right" style="padding-bottom:10px;">	
		<input type="hidden" name="page" id="page" value="<?=$page?>">
		<input type="text" class="form-control textbox-mid" name="txt_key" value="<?=$txt_key?>"> 
		<input type="submit" class="btn btn-warning" name="btn_filter" value=" Filter " onclick="javascript: document.getElementById('page').value='';">
		
		<br>
		</td>
	</tr>
</form>
    <tr>
        <td colspan="10">
			<?php create_list($sql,"edit_payments.php",20,"tbl_pages",5);?>
        </td>
    </tr>
</table>


<?php require_once("inc_admin_footer.php"); ?>
