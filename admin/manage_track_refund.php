<?php 
require_once("inc_admin_header.php");

	$sql="select tr.cart_item_id,tr.sup_id";
	$sql = $sql.",tr.ord_no as 'Order No.'";
	$sql = $sql.",(select prod_name from prod_mast where prod_id=oi.prod_id) as 'Product Name'";
	$sql = $sql.",oi.buyer_date as 'Order Date'";
	$sql = $sql.",tr.refund_amt as 'Refund Amount'";
	$sql = $sql." from track_refund tr join ord_items oi on tr.cart_item_id=oi.cart_item_id and tr.sup_id=oi.sup_id";
	$sql_where = " where (oi.item_buyer_action='Cancelled' or oi.item_buyer_action='Returned' or oi.item_buyer_action='Rejected') and tr.pay_status='Paid' order by oi.cart_item_id desc";
	
	$sql = $sql.$sql_where;
?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Refund Orders</h2></td>
	</tr>
	
    <tr>
        <td>
			<?php create_ord_list($sql,"edit_track_refund.php",20,"tbl_pages",5);?>
        </td>
    </tr>
	
</table>
<?php
require_once("inc_admin_footer.php");

?> 