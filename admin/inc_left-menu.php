
<div id="leftpanel">
	<div class="glossymenu">
		<?php if($_SESSION["admin"]=="1"){?>
			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_cats.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_brands.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_prods.php"){ ?><?php } ?> submenuheader" href="manage_cats.php?submenuheader=1" 
			title="Product Structure">Product Structure</a>
			<div class="submenu">
				<ul>
					<li> </li>
					<li><a href="manage_props.php" title="Manage Properties">Manage Properties</a></li>
					<li> </li>
					<li><a href="manage_cats.php" title="Manage Categories">Manage Categories</a></li>
					<li> </li>
					<li><a href="manage_brands.php" title="Manage Brands">Manage Brands</a></li>
					<li> </li>
					<li><a href="manage_prods.php" title="Manage Products">Manage Products</a></li>
					<li> </li>
					<li><a href="import_prods.php" title="Import Products">Import Products</a></li>
					<li> </li>
					<li><a href="import_prices.php" title="Import Products Prices">Import Products Price</a></li>
					<li> </li>
					<li><a href="manage_tax.php" title="Tax">Manage Tax</a></li>
					<li> </li>	
				</ul>
			</div>

			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_pages.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_banners.php"){ ?><?php } ?> submenuheader" href="" 
			title="Site Content">Site Content</a>
			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="manage_pages.php" title="Page Contents">Manage Page Content</a></li>
			<li> </li>
			<li><a href="manage_banners.php" title="Banner Images">Home Page Banner</a></li>
			<li> </li>
			<li><a href="resources.php" title="Manage Learning Portal">Manage Learning Portal</a></li>
			<li> </li>
			</ul>
			</div>

			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_users.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_buyers.php"){ ?><?php } ?> submenuheader" href="" 
			title="Manage Users">Users</a>
			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="manage_users.php" title="Back Office users">Manage Back Office Users</a></li>
			<li> </li>
			<li><a href="manage_buyers.php" title="Manage Buyers">Manage Buyers</a></li>
			<li> </li>
			<li><a href="manage_sellers.php" title="Manage Sellers">Manage Sellers</a></li>
			<li> </li>
			<li><a href="manage_verification.php" title="Manage Seller Verification">Manage Seller Verification</a></li>
			<li> </li>
			<li><a href="manage_seller_addr.php" title="Manage Seller Pick-up Location">Manage Seller Pick-up Location</a></li>
			<li> </li>
			<li><a href="manage_seller_subdomain.php" title="Manage Seller Subdomain">Manage Seller Subdomain</a></li>
			<li> </li>
			<li><a href="manage_buyer_credits.php" title="Manage Buyer Credits">Manage Buyer Credits</a></li>
			<li> </li>
			</ul>
			</div>

			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_shipping.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_pm.php"){ ?><?php } ?> submenuheader" href="" 
			title="Checkout Options">Checkout Options</a>
			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="manage_shipping.php" title="Manage Shipping Charges">Manage Shipping Charges</a></li>
			<li> </li>
			<li><a href="manage_pm.php" title="Payment Options">Payment Options</a></li>
			<li> </li>
			</ul>
			</div>
			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/order_list.php" or $_SERVER["SCRIPT_NAME"]="/admin/order_details.php"){ ?><?php } ?> submenuheader" href="" 
			title="Manage Orders">Orders</a>
			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="order_list.php?ds=1" title="View Orders">View Orders</a></li>
			<li> </li>
			<li><a href="order_list.php?ds=0" title="Dsipatch Orders">Dispatch Orders</a></li>
			<li> </li>
			<li><a href="manage_bt_details.php" title="Dsipatch Orders">Manage Bank Transfer Details</a></li>
			<li> </li>
			<li><a href="order_list_credit.php" title="Dsipatch Orders">Manage Credit Orders</a></li>
			<li> </li>
			<li><a href="manage_payment.php" title="Manage Seller Payment">Manage Seller Payment</a></li>
			<li> </li><br>
			<li><a href="manage_track_refund.php" title="Track Refund Amount">Track Refund Amount</a></li><br>
			<li> </li>
			<li><a href="manage_order_change.php" title="Manage Order Change">Re-assign Seller</a></li>
			<li> </li>
			</ul>
			</div>
			
			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_users.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_buyers.php"){ ?><?php } ?> submenuheader" href="" 
			title="Local Logistics">Local Logistics</a>
			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="manage_local_logistics_vendor.php" title="Manage Local Logistics Vendors">Manage Local Logistics Vendors</a></li>
			<li> </li>
			<li><a href="manage_local_logistics.php" title="Manage Local Orders">Manage Local Orders</a></li>
			<li> </li>
			<li><a href="manage_local_shipping_charges.php" title="Manage Local Shipping Charges">Manage Local Shipping Charges</a></li>
			<li> </li>
			</ul>
			</div>
			
			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_coupons.php"){ ?><?php } ?> submenuheader" href="" 
			title="Manage Offers">Offers</a>
			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="manage_coupons.php" title="Manage Offer Coupons">Manage Offer Coupons</a></li>
			<li> </li>
			<li><a href="order_list_with_coupon.php" title=" View Coupon Applied Orders ">View Coupon Applied Orders</a></li>
			<li> </li>
			</ul>
			</div>
			
			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_prod_enquiry.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_quotation.php"){ ?><?php } ?> submenuheader" href="" 
			title="Manage Sales">Sales</a>

			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="manage_prod_enquiry.php" title="Manage Enquiry">Manage Enquiry</a></li>
			<li> </li>
			<li><a href="manage_quotation.php" title="Manage Quotation">Manage Quotation</a></li>
			<li> </li>
			<li><a href="manage_rfq.php" title="Manage Request For Quotation">Manage RfQ</a></li>
			<li> </li>
			</ul>
			</div>
			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_job_requirement.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_job_application.php"){ ?><?php } ?> submenuheader" href="" 
			title="Manage Job">Jobs</a>

			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="manage_job_requirement.php" title="Manage Job Requirement">Manage Job Requirement</a></li>
			<li> </li>
			<li><a href="manage_job_application.php" title="Manage Job Application">Manage Job Application</a></li>
			<li> </li>
			</ul>
			</div>
			
			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_briefcase_rfq.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_briefcase_quotation.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_briefcase_po.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_briefcase_sub-users.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_briefcase_users.php"){ ?><?php } ?> submenuheader" href="" 
			title="Manage Briefcase">Manage Briefcase</a>
			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="manage_briefcase_users.php" title="View Briefcase Users"> Briefcase Users</a></li>
			<li> </li>
			<li><a href="manage_briefcase_sub-users.php" title="View Briefcase Sub-Users"> Briefcase Sub-Users</a></li>
			<li> </li>
			<li><a href="manage_briefcase_rfq.php" title="View Briefcase RFQ"> Briefcase RFQ</a></li>
			<li> </li><br>
			<li><a href="manage_briefcase_quotation.php" title="View Briefcase Quotation"> Briefcase Quotation</a></li><br>
			<li> </li>
			<li><a href="manage_briefcase_po.php" title="View Briefcase Purchase"> Briefcase Purchase Order</a></li>
			<li> </li>
			</ul>
			</div>
			
		<?php }	//Web-admin?>
	

		<?php if($_SESSION["admin"]=="AM"){?>
			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_cats.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_brands.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_prods.php"){ ?><?php } ?> submenuheader" href="manage_cats.php?submenuheader=1" 
			title="Product Structure">Product Structure</a>
			<div class="submenu">

			<ul>
				<li> </li>
				<li><a href="manage_props.php" title="Manage Properties">Manage Properties</a></li>
				<li> </li>
				<li><a href="manage_cats.php" title="Manage Categories">Manage Categories</a></li>
				<li> </li>
				<li><a href="manage_brands.php" title="Manage Brands">Manage Brands</a></li>
				<li> </li>
				<li><a href="manage_prods.php" title="Manage Products">Manage Products</a></li>
				<li> </li>
				<li><a href="import_prods.php" title="Import Products">Import Products</a></li>
				<li> </li>
				<li><a href="import_prices.php" title="Import Products Prices">Import Products Price</a></li>
				<li> </li>			
			</ul>
			</div>
		
		<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_users.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_buyers.php"){ ?><?php } ?> submenuheader" href="" 
		title="Manage users">Users</a>
		<div class="submenu">
		<ul>
		<li> </li>
		<li><a href="manage_buyers.php" title="Manage Buyers">Manage Buyers</a></li>
		<li> </li>
		<li><a href="manage_sellers.php" title="Manage Sellers">Manage Sellers</a></li>
		<li> </li>
		<li><a href="manage_verification.php" title="Manage Sellers">Seller Verification</a></li>
		<li> </li>
		<li><a href="manage_seller_addr.php" title="Manage Seller Pick-up Location">Manage Seller Pick-up Location</a></li>
		<li> </li>
		<li><a href="manage_buyer_credits.php" title="Manage Buyer Credits">Manage Buyer Credits</a></li>
		<li> </li>
		</ul>
		</div>
		
		<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/order_list.php" or $_SERVER["SCRIPT_NAME"]="/admin/order_details.php"){ ?><?php } ?> submenuheader" href="" 
			title="Site Content">Orders</a>
			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="order_list.php" title="Page Contents">View Orders</a></li>
			<li> </li>
			<li><a href="order_list.php?ds=0" title="Dsipatch Orders">Dispatch Orders</a></li>
			<li> </li>
			<li><a href="manage_bt_details.php" title="Dsipatch Orders">Manage Bank Transfer Details</a></li>
			<li> </li>
			</ul>
			</div>
		<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_coupons.php"){ ?><?php } ?> submenuheader" href="" 
			title="Manage Offers">Offers</a>
			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="manage_coupons.php" title="Manage Offer Coupons">Manage Offer Coupons</a></li>
			<li> </li>
			</ul>
			</div>	
		<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]=="/admin/resources.php"){ ?>-active<?php } ?>" href="resources.php" title="Learning Portal">Learning Portal</a>
		
		<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_prod_enquiry.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_quotation.php"){ ?><?php } ?> submenuheader" href="" 
			title="Manage Sales">Sales</a>

			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="manage_prod_enquiry.php" title="Manage Enquiry">Manage Enquiry</a></li>
			<li> </li>
			<li><a href="manage_quotation.php" title="Manage Quotation">Manage Quotation</a></li>
			<li> </li>
			</ul>
			</div>
			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_job_requirement.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_job_application.php"){ ?><?php } ?> submenuheader" href="" 
			title="Manage Job">Jobs</a>

			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="manage_job_requirement.php" title="Manage Job Requirement">Manage Job Requirement</a></li>
			<li> </li>
			<li><a href="manage_job_application.php" title="Manage Job Application">Manage Job Application</a></li>
			<li> </li>
			</ul>
			</div>
		<?php } //Accounts manager?> 
			

		<?php if($_SESSION["admin"]=="FM"){?>
			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_shipping.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_pm.php"){ ?><?php } ?> submenuheader" href="" 
			title="Manage users">Checkout Options</a>
			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="manage_shipping.php" title="Shipping Charges">Manage Shipping Charges</a></li>
			<li> </li>
			<li><a href="manage_pm.php" title="Tax">Payment Options</a></li>
			<li> </li>
			</ul>
			</div>
			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/order_list.php" or $_SERVER["SCRIPT_NAME"]="/admin/order_details.php"){ ?><?php } ?> submenuheader" href="" 
			title="Site Content">Orders</a>
			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="order_list.php" title="Banner Images">Dispatch Orders</a></li>
			<li> </li>
			<li><a href="manage_bt_details.php" title="Dsipatch Orders">Manage Bank Transfer Details</a></li>
			<li> </li>
			<li><a href="order_list_credit.php" title="Dsipatch Orders">Manage Credit Orders</a></li>
			<li> </li>
			<li><a href="manage_payment.php" title="Manage Seller Payment">Manage Seller Payment</a></li>
			<li> </li><br>
			<li><a href="manage_track_refund.php" title="Track Refund Amount">Track Refund Amount</a></li>
			<li> </li>
			</ul>
			</div>
			
			<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_coupons.php"){ ?><?php } ?> submenuheader" href="" 
			title="Manage Offers">Offers</a>
			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="manage_coupons.php" title="Manage Offer Coupons">Manage Offer Coupons</a></li>
			</ul>
			</div>	
		<?php } //Finance manager?> 
		
		<?php if($_SESSION["admin"]=="LP"){?>
		
		<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/order_list.php" or $_SERVER["SCRIPT_NAME"]="/admin/order_details.php"){ ?><?php } ?> submenuheader" href="" 
			title="Site Content">Orders</a>
			<div class="submenu">
			<ul>
			<br>
			<li><a href="order_list.php?ds=0" title="Dsipatch Orders">Dispatch Orders</a></li>
			<li> </li>
			</ul>
			</div>
		<a class="menuitem<?php if($_SERVER["SCRIPT_NAME"]="/admin/manage_users.php" or $_SERVER["SCRIPT_NAME"]="/admin/manage_buyers.php"){ ?><?php } ?> submenuheader" href="" 
			title="Local Logistics">Local Logistics</a>
			<div class="submenu">
			<ul>
			<li> </li>
			<li><a href="manage_local_logistics_vendor.php" title="Manage Local Logistics Vendors">Manage Local Logistics Vendors</a></li>
			<li> </li>
			<li><a href="manage_local_logistics.php" title="Manage Local Orders">Manage Local Orders</a></li>
			<li> </li>
			<li><a href="manage_local_shipping_charges.php" title="Manage Local Shipping Charges">Manage Local Shipping Charges</a></li>
			<li> </li>
			</ul>
			</div>
		
		<?php } //Logistics manager?> 
	</div>
</div>