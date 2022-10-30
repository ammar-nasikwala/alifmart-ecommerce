<?php
require_once("inc_admin_header.php");

$sql="select brand_id";
$sql = $sql.",brand_name as 'Brand Name'"; 
$sql = $sql."from brand_mast order by brand_name";

?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Brands</h2></td>
	</tr>
	
	<tr>
		<td><a href="edit_brands.php">Create New brand [+]</a><br><br></td>
	</tr>

    <tr>
        <td>
			<?php create_list($sql,"edit_brands.php",20,"tbl_pages",5);?>
        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");
?>
