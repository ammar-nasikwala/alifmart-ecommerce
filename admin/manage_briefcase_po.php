<?php
require_once("inc_admin_header.php");

$cid=$_SESSION["company_id"];
$sql="select id"; 
$sql = $sql.",po_no as 'Purchase Order No'"; 
$sql = $sql.",po_status as 'Status'"; 
$sql = $sql.",po_update_date as 'Last Modified'"; 
$sql = $sql."from po_details order by id DESC";

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

<?
require_once("inc_admin_footer.php");

?>
