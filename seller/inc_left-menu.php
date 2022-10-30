<div id="leftpanel">

<?php get_rst("select addr_id from sup_ext_addr where sup_id='".$_SESSION["sup_id"]."'",$row);
$addr_id=$row["addr_id"];?>

<div class="glossymenu">

<a id="menu-home" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/seller/mainmenu.php"){ ?>-active<?php } ?>" href="mainmenu.php" title="Seller Home">Dashboard</a>

<a id="menu-profile" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/seller/seller_profile.php"){ ?>-active<?php } ?>" href="seller_profile.php" title="Seller Profile">My Profile</a>

<a id="menu-details" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/seller/upd_seller_details.php"){ ?>-active<?php } ?>" href="upd_seller_details.php" title="Update Details">Update Details</a>

<a id="menu-address" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/seller/seller_delivery_addr.php"){ ?>-active<?php } ?>" href="seller_delivery_addr.php?id=<?=$addr_id?>" title="Manage My Addresses">Manage My Addresses</a>

<a id="menu-prods" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/seller/manage_prods.php"){ ?>-active<?php } ?>" href="manage_prods.php" title="Manage Products">Manage Products</a>

<a id="menu-address" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/seller/edit_subdomain.php"){ ?>-active<?php } ?>" href="edit_subdomain.php" title="Manage Subdomain">Manage Subdomain</a>

<a id="menu-orders" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/seller/order_list.php" or $_SERVER["SCRIPT_NAME"]="/seller/order_pending.php"){ ?><?php } ?> submenuheader" href="" title="Order Details">Orders</a>
<div class="submenu">
<ul>
<li>&nbsp;</li>
<li><a href="order_list.php?ds=1" title="Page Contents">View Orders</a></li>
<li>&nbsp;</li>
<li><a href="order_list.php?ds=0" title="Dispatch Orders">Dispatch Orders</a></li>
<li>&nbsp;</li>
<?php $sup_lmd = $_SESSION["sup_lmd"];
if($sup_lmd <> 1){ ?>
<li><a href="order_list.php?dls=1" title="Update Delivery Status">Update Delivery Status</a></li>
<li>&nbsp;</li>
<?php }?>
<li><a href="track_payment.php" title="Track Payment">Track Payment</a></li>
<li>&nbsp;</li>
</ul>
</div>
<a id="menu-resources" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/seller/resources.php"){ ?>-active<?php } ?>" href="resources.php" title="Learning Portal">Learning Portal</a>
<a id="menu-support" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/seller/support.php"){ ?>-active<?php } ?>" href="support.php" title="Support">Support</a>
<a id="menu-feedback" class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/seller/feedback.php"){ ?>-active<?php } ?>" href="feedback.php" title="Feedback">Feedback</a>

</div>



</div>

<?php
	$sup_id = $_SESSION["sup_id"];
	get_rst("select sup_mou_accept from sup_mast where sup_id=0$sup_id",$row);
	if($row["sup_mou_accept"] <> 1)
	{ ?>
		<script> document.getElementById("menu-prods").className += " disabled" 
			document.getElementById("menu-orders").className += " disabled" </script>
<?php } ?>