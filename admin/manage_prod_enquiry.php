<?php
require_once("inc_admin_header.php");

$sql="select id";
$sql = $sql.",prod_name as 'Product Name'"; 
$sql = $sql.",email as 'Email'"; 
$sql = $sql.",enq_status as 'Status'"; 
$sql = $sql."from prod_enquiry where quotation_id IS NOT NULL";

$title = "Prop";
?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Product Enquiry</h2></td>
	</tr>

    <tr>
        <td>
			<?php create_list($sql,"edit_prod_enquiry.php",20,"tbl_pages",5);?>
        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");
?>