<?
require_once("inc_admin_header.php");

require_once("cat_tree.php");

$sql="select page_id";
$sql = $sql.",page_heading as 'Page'"; 
$sql = $sql."from cms_pages order by page_id";

?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Page Content</h2></td>
	</tr>
	
    <tr>
        <td>

				<?create_list($sql,"edit_pages.php");?>

        </td>
    </tr>
</table>

<?
require_once("inc_admin_footer.php");

?>
