<?php
require_once("inc_admin_header.php");

$sql="select tax_id";
$sql = $sql.",tax_name as 'Tax Type'"; 
$sql = $sql.",tax_percent as 'Percentage'"; 
$sql = $sql."from tax_mast order by tax_name";

?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Tax</h2></td>
	</tr>
	
	<tr>
		<td><a href="edit_tax.php">Create Tax Type [+]</a><br><br></td>
	</tr>

    <tr>
        <td>

				<?create_list($sql,"edit_tax.php",20,"tbl_pages",5);?>

        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");

?>
