<?php
require_once("inc_admin_header.php");

$sql="select pay_id";
$sql = $sql.",pay_name as 'Payment Text'"; 
$sql = $sql.",pay_status as 'Status'"; 
$sql = $sql."from pay_method order by pay_sort";

?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Payment Options</h2></td>
	</tr>
	
    <tr>
        <td>

			<?php create_list($sql,"edit_pm.php",20,"tbl_pages",5);?>

        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");

?>
