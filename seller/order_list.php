<?php
session_start();
require_once("inc_admin_header.php");

$sup_id=0;
if(isset($_SESSION["sup_id"])){
	$sup_id=$_SESSION["sup_id"];
}else{	?>
	<script>
		alert("Your session timed out. Please login again.");
		window.location.href="index.php";
	</script>
	<?php
}

$dls = func_read_qs("dls");
if(func_read_qs("ds") <> ""){
$_SESSION["ds"] = func_read_qs("ds");
}
$sql="select ord_id";
$sql = $sql.",ord_no as 'Order No.'";
$sql = $sql.",ord_date as 'Date'";
$sql = $sql.",bill_name as 'Placed by'";
$sql = $sql.",ord_total as 'Order Value'";
$sql = $sql.",item_count as 'No. of Products'";
$sql = $sql.",pay_status as 'Payment Status'";
$sql = $sql.",delivery_status as 'Delivery Status'";
$sql = $sql.",new_seller_price as 'Re-assigned'";

if($dls == 1){
	$sql = $sql." from ord_summary os join ord_details od on os.cart_id=od.cart_id where os.sup_id=$sup_id and delivery_status='Dispatched'";
}else{
	$sql = $sql." from ord_summary os join ord_details od on os.cart_id=od.cart_id where os.sup_id=$sup_id";
}
if($dls <> 1){
	if($_SESSION["ds"] == 0){
		$sql = $sql." and pay_status='Paid' and delivery_status <> 'Dispatched' and delivery_status <> 'Delivered' and buyer_action is null";
	}
}

if(!empty($_GET['delivery_status'])){
	$sql = $sql." AND delivery_status='Pending' ";
}
$sql = $sql." order by ord_id desc";

?>

<table border="0" width="100%">
	<tr>
		<td><h2>Order List</h2></td>
	</tr>
	
    <tr>
        <td>

			<?php	
				if($dls == 1){
					create_list($sql,"order_delivery_status.php",20,"tbl_pages",5);
				}else{
					create_list($sql,"order_details.php",20,"tbl_pages",5);
				}	
			?>

        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");
?>
