<?php
require_once("inc_admin_header.php");
$txt_key = func_read_qs("txt_key");
$page = func_read_qs("page");

$sql = "select sm.sup_id";
$sql = $sql.",sup_company as 'Establishment Name'";
$sql = $sql.",sd.subdomain_name as 'Subdomain Name'";
$sql = $sql.",sd.subdomain_status as 'Subdomain Status'";
$sql = $sql."from sup_mast sm Left join subdomain_mast sd on sm.sup_id=sd.sup_id";
$sql = $sql." where sup_mou_accept=1";
if($txt_key<>""){
	$sql = $sql." and sup_company like '%$txt_key%'";
}
$sql = $sql." order by sup_company";
?>
<table border="0" width="100%">
	<tr>
		<td><h2>Manage Seller Subdomain</h2></td>
	</tr>
	<form name="frm_subdomain" method="post" action="manage_seller_subdomain.php">
	<input type="hidden" name="page" id="page" value="<?=$page?>">
	
	<input type="submit" class="btn btn-warning" name="btn_filter" style="float:right;" value=" Filter " onclick="javascript: document.getElementById('page').value='';">
	<input type="text" class="form-control" style="width: 150px; float:right; " name="txt_key" value="<?=$txt_key?>"> 	
	<input type="hidden" name="act" value="1">
        <td>
			<?php create_list($sql,"edit_seller_subdomain.php",20,"tbl_pages",5);?>
        </td>
    </tr>
	</tr>
</table>
<?php
require_once("inc_admin_footer.php");
?> 