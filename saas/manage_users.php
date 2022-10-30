<?php
require_once("inc_admin_header.php");

$cid=$_SESSION["po_company_id"];
$sql="select id"; 
$sql = $sql.",user_name as 'User Name'"; 
$sql = $sql.",email as 'Email'";
$sql = $sql.",contact_no as 'Contact Number'"; 
$sql = $sql."from po_memb_mast where po_company_id=$cid";			//list of sub-users
?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Users</h2></td>
	</tr>
	
	<tr>
		<td><a href="edit_users.php">Create New User [+]</a><br><br></td>
	</tr>

    <tr>
        <td>

			<?php create_list($sql,"edit_users.php",20,"tbl_pages",5);?>

        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");

?>
