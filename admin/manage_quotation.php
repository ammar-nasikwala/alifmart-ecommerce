<?php
require_once("inc_admin_header.php");

$sql="select quotation_id";
$sql = $sql.",customer_name as 'Customer'"; 
$sql = $sql.",customer_email as 'Email'"; 
$sql = $sql.",quot_status as 'Status'";
$sql = $sql." from quotation_mast order by quotation_id DESC"; 


?>
<table border="0" width="100%">
	<tr>
		<td><h2>Manage Quotation</h2></td>
	</tr>
	<form name="frm_list" method="post" action="manage_quotation.php">
	<input type="hidden" name="act" value="1">
	<tr>
		<td><a href="quotation.php">Create New Quotation [+]</a></td>
    <tr>
        <td>
			<?php create_list($sql,"quotation.php",20,"tbl_pages",5);?>
        </td>
    </tr>
	</tr>
</table>
<?php
require_once("inc_admin_footer.php");

?> 