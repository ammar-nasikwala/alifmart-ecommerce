<?php
require_once("inc_admin_header.php");

$sql="select distinct zone_name";
$sql = $sql.",zone_name as 'Zone Name'"; 
$sql = $sql."from ship_zone order by zone_name";

?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage zone-wise shipping charges</h2></td>
	</tr>
	
	<tr>
		<td><a href="edit_shipping.php">Create New Shipping Zone [+]</a><br><br></td>
	</tr>

    <tr>
        <td>

			<?php create_list($sql,"edit_shipping.php",20,"tbl_pages",5);?>

        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");

?>
