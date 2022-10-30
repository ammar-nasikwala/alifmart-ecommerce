<?php
require_once("inc_admin_header.php");

$sql="select os.cart_id";
$sql = $sql.",ord_no as 'Order No.'";
$sql = $sql.",ord_date as 'Date'";
$sql = $sql.",bill_name as 'Placed by'";
$sql = $sql.",sum(ord_total) as 'Order Value'";
$sql = $sql.",sum(item_count) as 'No. of Products'";
$sql = $sql.",pay_method as 'Payment Type'";
$sql = $sql.",pay_status as 'Payment Status'";
$sql = $sql.",delivery_status as 'Delivery Status'";
$sql = $sql." from ord_summary os join ord_details od on os.cart_id=od.cart_id and os.user_id=od.memb_id";

//$sql = "select * from vw_ord_summary";

$sql_where = " where os.cart_id in (select cart_id from ord_coupon) group by ord_id ";

$delivery_status = func_read_qs("delivery_status");
$pay_status = func_read_qs("pay_status");


if($delivery_status<>""){
 $sql_where = $sql_where." and delivery_status = '$delivery_status'";
 $page = "Dispatch Orders";
 }else{
 $page = "View Orders";		
}
if($pay_status<>"")	$sql_where = $sql_where." and `pay_status`='$pay_status'";

$sql = $sql.$sql_where." order by ord_id desc";

?>

<script>
function js_submit(){
	document.getElementById('page').value='';
	document.frm_list.submit();
}

</script>

<table border="0" width="100%">
	<tr>
		<td><h2>View Orders</h2></td>
	</tr>

	<form name="frm_list" method="post" action="order_list.php">
    <tr>
        <td>
		<input type="hidden" name="page" id="page" value="<?=$page?>">

		Delivery:
		<select name="delivery_status" onchange="javascript: js_submit();">
			<?php func_option("All","",func_var($delivery_status))?>
			<?php func_option("Pending","Pending",func_var($delivery_status))?>
			<?php func_option("Ready-to-Ship","Ready-to-Ship",func_var($delivery_status))?>
			<?php func_option("Dispatched","Dispatched",func_var($delivery_status))?>
			
			
		</select>
		 | 
		Payment:
		<select name="pay_status" onchange="javascript: js_submit();">
			<?php func_option("All","",func_var($pay_status))?>
			<?php func_option("Pending","Pending",func_var($pay_status))?>
			<?php func_option("Paid","Paid",func_var($pay_status))?>
		</select>
		</td>
	</tr>
	</form>
	
    <tr>
        <td>
			<?php create_list($sql,"order_details_with_coupon.php",20,"tbl_pages",5);?>
        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");
?>
	
