<?php //saas
require_once("inc_admin_header.php");

$sql="select q.quotation_id";
$sql = $sql.",q.quotation_no as 'Quotation No.'"; 
$sql = $sql.",(select rfq_no from request_for_quotation r where r.quotation_id=q.rfq_id) as 'RFQ No'"; 
$sql = $sql.",q.customer_name as 'Customer Name'"; 
$sql = $sql.",q.customer_email as 'Email'"; 
$sql = $sql." from quotation_mast q where q.quot_status='Sent' and q.po_company_id='".$_SESSION["po_company_id"]."'order by q.quotation_id DESC"; 

?>
<table border="0" width="100%">
	<tr>
		<td><h2>Manage Quotation</h2></td>
	</tr>
    <tr>
        <td>
			<?php create_list($sql,"quotation.php",20,"tbl_pages",5);?>
        </td>
    </tr>
</table>
<?php
require_once("inc_admin_footer.php");

?> 