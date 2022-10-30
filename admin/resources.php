<?
require_once("inc_admin_header.php");

$sql="select resource_id";
$sql = $sql.",resource_name as 'Resource Name'"; 
$sql = $sql."from resources";

?>

<table border="0" width="100%">
	<tr>
		<td><h2>Resources</h2></td>
	</tr>
	
	<tr>
		<td><a href="edit_resources.php">Create New Resource [+]</a><br><br></td>
	</tr>

    <tr>
        <td>

				<?create_list($sql,"edit_resources.php",20,"tbl_pages",5);?>

        </td>
    </tr>
</table>

<?
require_once("inc_admin_footer.php");

?>
