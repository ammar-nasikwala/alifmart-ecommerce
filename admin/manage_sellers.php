<?php
require_once("inc_admin_header.php");
$_SESSION["manage_sellers"]=1;
$txt_key = func_read_qs("txt_key");
$page = func_read_qs("page");

$sql="select sup_id";
$sql = $sql.",sup_company as 'Establishment'"; 
$sql = $sql.",sup_business_type as 'Biz Type'"; 
$sql = $sql.",sup_contact_name as 'Authorised Person'"; 
$sql = $sql.",sup_active_status as 'Account Activated'";
$sql = $sql.",sup_admin_approval as 'Admin Approved'"; 
$sql = $sql.",sup_mou_accept as 'MoU Accepted'"; 
$sql = $sql.",sup_alias as 'Seller Alias'"; 
$sql = $sql.",sup_lmd as 'LMD'"; 
$sql = $sql."from sup_mast ";
$sql = $sql."where sup_delete_account IS NULL ";
if($txt_key<>""){
	$sql = $sql." AND (sup_email like '%$txt_key%'";
	$sql = $sql." OR sup_company like '%$txt_key%'";
	$sql = $sql." OR sup_business_type like '%$txt_key%'";
	$sql = $sql." OR sup_contact_name like '%$txt_key%')";
}

$sql = $sql."order by sup_reg_date desc, sup_company";
?>

<table border="0" width="100%">
	<tr>
		<td colspan="2"><h2>Manage Sellers</h2></td>
	</tr>
	
	<tr>
		<td><a href="../seller.php" target="_blank">Create New Seller [+]</a><br><br></td>
		<td align="right">
	<form name="frm_list" method="post" action="manage_sellers.php">
		<input type="hidden" name="page" id="page" value="<?=$page?>">
		<input type="text" class="height30" name="txt_key" value="<?=$txt_key?>"> 
		<input type="submit" class="btn btn-warning" name="btn_filter" value=" Filter " onclick="javascript: document.getElementById('page').value='';">
	</form>		
		<br>
		</td>		
	</tr>

    <tr>
        <td colspan="10">
			<?php create_list($sql,"edit_sellers.php",20,"tbl_pages",5, "", "table_form", 1);?>
        </td>
    </tr>
</table>

<?php require_once("inc_admin_footer.php"); ?>
