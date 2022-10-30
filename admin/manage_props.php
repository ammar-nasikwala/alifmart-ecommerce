<?
require_once("inc_admin_header.php");

$sql="select prop_id";
$sql = $sql.",prop_name as 'Property Name'"; 
$sql = $sql."from prop_mast ";

$title = "Prop";
?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage <?=$title?>erties</h2></td>
	</tr>
	
	<tr>
		<td><a href="edit_props.php">Create New <?=$title?>erty [+]</a><br><br></td>
	</tr>

    <tr>
        <td>

				<?create_list($sql,"edit_props.php",20,"tbl_pages",5);?>

        </td>
    </tr>
</table>

<?
require_once("inc_admin_footer.php");

?>
