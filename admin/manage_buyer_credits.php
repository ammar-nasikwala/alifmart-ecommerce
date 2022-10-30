<?php
require_once("inc_admin_header.php");

$txt_key = func_read_qs("txt_key");
$page = func_read_qs("page");

$sql="select memb_id";
$sql = $sql.",memb_email as 'Email'"; 
$sql = $sql.",CONCAT(memb_fname,' ',memb_sname) as 'Full Name'"; 
$sql = $sql.",memb_act_status as 'Account Activated'";
$sql = $sql.",memb_credit_status as 'Credit Status'";
$sql = $sql."from member_mast ";

if($txt_key<>""){
	$sql = $sql." where memb_email like '%$txt_key%'";
	$search_arr = explode(" ",$txt_key);
	if(count($search_arr) > 1){
		$sql = $sql." OR memb_fname like '%".$search_arr[0]."%'";
		$sql = $sql." OR memb_sname like '%".$search_arr[1]."%'";
	}else{
		$sql = $sql." OR memb_fname like '%$txt_key%'";
		$sql = $sql." OR memb_sname like '%$txt_key%'";
	}	
}
$sql = $sql."order by memb_fname";

?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Buyer Credits</h2></td>
	</tr>
	
	<tr>
		<td><br><br></td>
		<td align="right">
	<form name="frm_list" method="post" action="manage_buyers.php">
		<input type="hidden" name="page" id="page" value="<?=$page?>">
		<input type="text" class="height30" name="txt_key" value="<?=$txt_key?>"> 
		<input type="submit" name="btn_filter" class="btn btn-warning" value=" Filter " onclick="javascript: document.getElementById('page').value='';">
	</form>		
	<br>
	</td>		
	</tr>

    <tr>
        <td colspan="10">
			<?php create_list($sql,"edit_buyer_credits.php",20,"tbl_pages",5);?>
        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");
?>
