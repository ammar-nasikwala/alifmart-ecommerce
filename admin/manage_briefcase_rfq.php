<?php
require_once("inc_admin_header.php");

$sql="select quotation_id";
$sql = $sql.",rfq_no as 'Rfq No.'";
$sql = $sql.",customer_name as 'Customer'"; 
$sql = $sql.",customer_email as 'Email'";
$sql = $sql.",creation_date as 'Date Of Rfq Raised'";
$sql = $sql." from request_for_quotation where po_company_id is not null order by quotation_id DESC"; 


?>
<table border="0" width="100%">
	<tr>
		<td><h2>Manage RFQ</h2></td>
	</tr>
	<form name="frm_list" method="post" action="manage_rfq.php">
	<input type="hidden" name="act" value="1">
	<tr>
		<td><a href="inc_rfq.php">Create New RfQ [+]</a></td>
    <tr>
        <td>
			<?php create_list($sql,"inc_rfq.php",20,"tbl_pages",5);?>
        </td>
    </tr>
	</tr>
</table>
<?php
require_once("inc_admin_footer.php");

?> 