<?php
require_once("inc_admin_header.php");
$_SESSION["tree"] = "no";
require_once("cat_tree.php");
?>

<script>

function js_sel(obj,id,path){
	window.location.href="edit_cats.php?id="+id;
}
</script>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Categories</h2></td>
	</tr>
	
	<tr>
		<td><a href="edit_cats.php">Create New Category [+]</a><br><br></td>
	</tr>

    <tr>
        <td>

				<?php create_cat_tree();?>

        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");

?>
