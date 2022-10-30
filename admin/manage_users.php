<?
require_once("inc_admin_header.php");

require_once("cat_tree.php");

$sql="select user_id";
$sql = $sql.",user_type as 'Type'"; 
$sql = $sql.",user_name as 'Full Name'"; 
$sql = $sql.",user_email as 'Email'"; 
$sql = $sql."from user_mast order by user_type";

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

				<?create_list($sql,"edit_users.php",20,"tbl_pages",5);?>

        </td>
    </tr>
</table>

<?
require_once("inc_admin_footer.php");

?>
