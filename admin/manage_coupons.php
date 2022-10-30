<?php
require_once("inc_admin_header.php");

$sql="select coupon_id";
$sql = $sql.",coupon_code as 'Coupon Code'"; 
$sql = $sql.",min_ord_value as 'Minimum Order Value'"; 
$sql = $sql.",disc_per as 'Discount Percent'"; 
$sql = $sql.",max_discount_value as 'Maximum Order Discount Amount'"; 
$sql = $sql.",active as 'Active Status'"; 
$sql = $sql."from offer_coupon ";

$title = "Prop";
?>

<table border="0" width="100%">
	<tr>
		<td><h2>Manage Offer Coupons</h2></td>
	</tr>
	
	<tr>
		<td><a href="edit_coupons.php">Create New Offer Coupon [+]</a><br><br></td>
	</tr>

    <tr>
        <td>
			<?php create_list($sql,"edit_coupons.php",20,"tbl_pages",5);?>
        </td>
    </tr>
</table>

<?php
require_once("inc_admin_footer.php");
?>
