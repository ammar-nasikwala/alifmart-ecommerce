<?
require_once("inc_admin_header.php");

$sql="select banner_id";
$sql = $sql.",banner_alt_text as 'Banner Text'";
$sql = $sql.",banner_status as 'Banner Status'";
$sql = $sql.",banner_sort as 'Sort Index'";
$sql = $sql."from banner_mast order by banner_sort";

?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Banners</h2></td>
	</tr>
	
	<tr>
		<td><a href="edit_banners.php">Create New Banner [+]</a><br><br></td>
	</tr>

    <tr>
        <td>

				<?create_list($sql,"edit_banners.php",20,"tbl_pages",5);?>

        </td>
    </tr>
</table>

<?
require_once("inc_admin_footer.php");

?>
