<?php

require_once("inc_admin_header.php");
$cid=$_SESSION["po_company_id"];
$sql="select os.cart_id";
$sql = $sql.",ord_no as 'Order No.'";
$sql = $sql.",ord_date as 'Date'";
$sql = $sql.",(select sum((cart_price_tax * cart_qty) + ship_amt) as ord_total from ord_items where cart_id=os.cart_id) as 'Order Value'";
$sql = $sql.",sum(item_count) as 'Item Count'";
$sql = $sql.",pay_method as 'Payment Type'";
$sql = $sql.",pay_status as 'Payment Status'";
$sql = $sql.",delivery_status as 'Delivery Status'";
$sql = $sql." from ord_summary os right join (ord_details od right join po_details po on od.cart_id=po.cart_id) on os.po_no=po.po_no";

$sql_where = " where user_id=(select owner_id from po_company_mast where po_company_id=$cid)";

$sql = $sql.$sql_where;
//$sql = $sql."and os.cart_id=(select cart_id from po_details where po_company_id=$cid)";
$sql = $sql." group by ord_id order by ord_id desc ";
?>

<table border="0" width="100%">
	<tr>
		<td><h2>View Orders</h2></td>
	</tr>
    <tr>
        <td>
			<?php create_list($sql,"view_order_details.php",20,"tbl_pages",5);?>
        </td>
    </tr>
</table>
<?php
require_once("inc_admin_footer.php");

?> 