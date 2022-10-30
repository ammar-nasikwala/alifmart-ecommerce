<?php
require_once("inc_admin_header.php");

$cid=$_SESSION["po_company_id"];
$sql="select p.quotation_id"; 
$sql = $sql.",p.po_no as 'PO No'"; 
$sql = $sql.",(select quotation_no from quotation_mast q where q.quotation_id=p.quotation_id) as 'Quotation No'"; 
$sql = $sql.",p.po_status as 'Status'"; 
$sql = $sql.",p.po_update_date as 'Last Modified'"; 
$sql = $sql."from po_details p where p.po_company_id=$cid order by p.quotation_id desc";

?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Purchase Order</h2></td>
	</tr>

    <tr>
        <td>

				<?php create_list($sql,"edit_po.php",20,"tbl_pages",5);?>

        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");

?>
